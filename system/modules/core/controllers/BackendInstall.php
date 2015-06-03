<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Back end install tool.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class BackendInstall extends \Backend
{

	/**
	 * @var \BackendTemplate|object
	 */
	protected $Template;


	/**
	 * Initialize the controller
	 */
	public function __construct()
	{
		$this->import('Config');
		$this->import('Session');

		\Config::set('showHelp', false);
		\Config::set('displayErrors', false);

		$this->setStaticUrls();

		\System::loadLanguageFile('default');
		\System::loadLanguageFile('tl_install');
	}


	/**
	 * Run the controller and parse the login template
	 */
	public function run()
	{
		$this->Template = new \BackendTemplate('be_install');

		// Lock the tool if there are too many login attempts
		if (\Config::get('installCount') >= 3)
		{
			$this->Template->locked = true;
			$this->outputAndExit();
		}

		// Store the FTP login credentials
		if (\Input::post('FORM_SUBMIT') == 'tl_ftp')
		{
			$this->storeFtpCredentials();
		}

		// Import the Files object AFTER storing the FTP settings
		$this->import('Files');

		// If the files are not writeable, the SMH is required
		if (!$this->Files->is_writeable('system/config/default.php'))
		{
			$this->outputAndExit();
		}

		$this->Template->lcfWriteable = true;

		// Create the local configuration files if not done yet
		if (!\Config::get('useFTP'))
		{
			$this->createLocalConfigurationFiles();
		}

		// Show the license text
		if (!\Config::get('licenseAccepted'))
		{
			$this->acceptLicense();
		}

		// Log in the user
		if (\Input::post('FORM_SUBMIT') == 'tl_login')
		{
			$this->loginUser();
		}

		// Auto-login on fresh installations
		if (\Config::get('installPassword') == '')
		{
			$this->setAuthCookie();
		}

		// Login required
		elseif (!\Input::cookie('TL_INSTALL_AUTH') || $_SESSION['TL_INSTALL_AUTH'] == '' || \Input::cookie('TL_INSTALL_AUTH') != $_SESSION['TL_INSTALL_AUTH'] || $_SESSION['TL_INSTALL_EXPIRE'] < time())
		{
			$this->Template->login = true;
			$this->outputAndExit();
		}

		// Authenticated, so renew the cookie
		else
		{
			$this->setAuthCookie();
		}

		// Store the relative path
		$this->storeRelativePath();

		// Store the install tool password
		if (\Input::post('FORM_SUBMIT') == 'tl_install')
		{
			$this->storeInstallToolPassword();
		}

		// Require a password
		if (\Config::get('installPassword') == '')
		{
			$this->Template->setPassword = true;
			$this->outputAndExit();
		}

		// Save the encryption key
		if (\Input::post('FORM_SUBMIT') == 'tl_encryption')
		{
			\Config::persist('encryptionKey', \Input::post('key'));
			$this->reload();
		}

		// Autogenerate an encryption key
		if (\Config::get('encryptionKey') == '')
		{
			$strKey = md5(uniqid(mt_rand(), true));

			\Config::set('encryptionKey', $strKey);
			\Config::persist('encryptionKey', $strKey);
		}

		$this->Template->encryptionKey = \Config::get('encryptionKey');

		// Check the minimum length of the encryption key
		if (utf8_strlen(\Config::get('encryptionKey')) < 12)
		{
			$this->Template->encryptionLength = true;
			$this->outputAndExit();
		}

		// Purge the internal cache (see #6357)
		if (is_dir(TL_ROOT . '/system/cache/dca'))
		{
			foreach (array('config', 'dca', 'language', 'sql') as $dir)
			{
				$objFolder = new \Folder('system/cache/' . $dir);
				$objFolder->delete();
			}
		}

		// Set up the database connection
		$this->setUpDatabaseConnection();

		// Run the version-specific database updates
		$this->runDatabaseUpdates();

		// Store the collation
		$this->storeCollation();

		// Adjust the database tables
		$this->adjustDatabaseTables();

		// Import the example website
		try
		{
			$this->importExampleWebsite();
		}
		catch (\Exception $e)
		{
			\Config::remove('exampleWebsite');
			$this->Template->importException = true;
			error_log("\nPHP Fatal error: {$e->getMessage()} in {$e->getFile()} on line {$e->getLine()}\n{$e->getTraceAsString()}\n");
			$this->outputAndExit();
		}

		// Create an admin user
		$this->createAdminUser();

		// Clear the cron timestamps so the jobs are run
		\Config::remove('cron_hourly');
		\Config::remove('cron_daily');
		\Config::remove('cron_weekly');

		$this->outputAndExit();
	}


	/**
	 * Store the FTP login credentials
	 */
	protected function storeFtpCredentials()
	{
		if (\Config::get('installPassword') != '')
		{
			return;
		}

		\Config::set('useFTP', true);
		\Config::set('ftpHost', \Input::post('host'));
		\Config::set('ftpPath', \Input::post('path'));
		\Config::set('ftpUser', \Input::post('username', true));

		if (\Input::postUnsafeRaw('password') != '*****')
		{
			\Config::set('ftpPass', \Input::postUnsafeRaw('password'));
		}

		\Config::set('ftpSSL', \Input::post('ssl'));
		\Config::set('ftpPort', (int) \Input::post('port'));

		// Add a trailing slash
		if (\Config::get('ftpPath') != '' && substr(\Config::get('ftpPath'), -1) != '/')
		{
			\Config::set('ftpPath', \Config::get('ftpPath') . '/');
		}

		// Re-insert the data into the form
		$this->Template->ftpHost = \Config::get('ftpHost');
		$this->Template->ftpPath = \Config::get('ftpPath');
		$this->Template->ftpUser = \Config::get('ftpUser');
		$this->Template->ftpPass = (\Config::get('ftpPass') != '') ? '*****' : '';
		$this->Template->ftpSSL  = \Config::get('ftpSSL');
		$this->Template->ftpPort = \Config::get('ftpPort');

		$ftp_connect = (\Config::get('ftpSSL') && function_exists('ftp_ssl_connect')) ? 'ftp_ssl_connect' : 'ftp_connect';

		// Try to connect and locate the Contao directory
		if (($resFtp = $ftp_connect(\Config::get('ftpHost'), \Config::get('ftpPort'), 5)) == false)
		{
			$this->Template->ftpHostError = true;
			$this->outputAndExit();
		}
		elseif (!ftp_login($resFtp, \Config::get('ftpUser'), \Config::get('ftpPass')))
		{
			$this->Template->ftpUserError = true;
			$this->outputAndExit();
		}
		elseif (ftp_size($resFtp, \Config::get('ftpPath') . 'assets/contao/css/debug.css') == -1)
		{
			$this->Template->ftpPathError = true;
			$this->outputAndExit();
		}

		// Update the local configuration file
		else
		{
			$this->import('Files');

			// The system/tmp folder must be writable for fopen()
			if (!is_writable(TL_ROOT . '/system/tmp'))
			{
				$this->Files->chmod('system/tmp', 0777);
			}

			// The assets/images folder must be writable for image*()
			if (!is_writable(TL_ROOT . '/assets/images'))
			{
				$this->Files->chmod('assets/images', 0777);
			}

			$folders = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f');

			foreach ($folders as $folder)
			{
				if (!is_writable(TL_ROOT . '/assets/images/' . $folder))
				{
					$this->Files->chmod('assets/images/' . $folder, 0777);
				}
			}

			// The system/logs folder must be writable for error_log()
			if (!is_writable(TL_ROOT . '/system/logs'))
			{
				$this->Files->chmod('system/logs', 0777);
			}

			// Create the local configuration files
			$this->createLocalConfigurationFiles();

			// Save the FTP credentials
			\Config::persist('useFTP', true);
			\Config::persist('ftpHost', \Config::get('ftpHost'));
			\Config::persist('ftpPath', \Config::get('ftpPath'));
			\Config::persist('ftpUser', \Config::get('ftpUser'));

			if (\Input::postUnsafeRaw('password') != '*****')
			{
				\Config::persist('ftpPass', \Config::get('ftpPass'));
			}

			\Config::persist('ftpSSL', \Config::get('ftpSSL'));
			\Config::persist('ftpPort', \Config::get('ftpPort'));

			$this->reload();
		}
	}


	/**
	 * Accept the license
	 */
	protected function acceptLicense()
	{
		if (\Input::post('FORM_SUBMIT') == 'tl_license')
		{
			\Config::persist('licenseAccepted', true);
			$this->reload();
		}

		$this->Template->license = true;
		$this->outputAndExit();
	}


	/**
	 * Log in the user
	 */
	protected function loginUser()
	{
		$_SESSION['TL_INSTALL_AUTH'] = '';
		$_SESSION['TL_INSTALL_EXPIRE'] = 0;

		// The password has been generated with crypt()
		if (\Encryption::test(\Config::get('installPassword')))
		{
			if (\Encryption::verify(\Input::postUnsafeRaw('password'), \Config::get('installPassword')))
			{
				$this->setAuthCookie();
				\Config::persist('installCount', 0);

				$this->reload();
			}
		}
		else
		{
			list($strPassword, $strSalt) = explode(':', \Config::get('installPassword'));
			$blnAuthenticated = ($strSalt == '') ? ($strPassword === sha1(\Input::postUnsafeRaw('password'))) : ($strPassword === sha1($strSalt . \Input::postUnsafeRaw('password')));

			if ($blnAuthenticated)
			{
				// Store a crypt() version of the password
				$strPassword = \Encryption::hash(\Input::postUnsafeRaw('password'));
				\Config::persist('installPassword', $strPassword);

				$this->setAuthCookie();
				\Config::persist('installCount', 0);

				$this->reload();
			}
		}

		// Increase the login count if we get here
		\Config::persist('installCount', \Config::get('installCount') + 1);

		$this->Template->passwordError = $GLOBALS['TL_LANG']['ERR']['invalidPass'];
	}


	/**
	 * Store the install tool password
	 */
	protected function storeInstallToolPassword()
	{
		$strPassword = \Input::postUnsafeRaw('password');

		// The passwords do not match
		if ($strPassword != \Input::postUnsafeRaw('confirm_password'))
		{
			$this->Template->passwordError = $GLOBALS['TL_LANG']['ERR']['passwordMatch'];
		}

		// The password is too short
		elseif (utf8_strlen($strPassword) < \Config::get('minPasswordLength'))
		{
			$this->Template->passwordError = sprintf($GLOBALS['TL_LANG']['ERR']['passwordLength'], \Config::get('minPasswordLength'));
		}

		// Save the password
		else
		{
			$strPassword = \Encryption::hash($strPassword);
			\Config::persist('installPassword', $strPassword);

			$this->reload();
		}
	}


	/**
	 * Set up the database connection
	 */
	protected function setUpDatabaseConnection()
	{
		$strDrivers = '';
		$arrDrivers = array('');

		if (class_exists('mysqli', false))
		{
			$arrDrivers[] = 'MySQLi';
		}
		if (function_exists('mysql_connect'))
		{
			$arrDrivers[] = 'MySQL';
		}

		// If there is another driver defined, add it here as well
		if (\Config::get('dbDriver') != '' && !in_array(\Config::get('dbDriver'), $arrDrivers))
		{
			$arrDrivers[] = \Config::get('dbDriver');
		}

		foreach ($arrDrivers as $strDriver)
		{
			$strDrivers .= sprintf('<option value="%s"%s>%s</option>',
									$strDriver,
									(($strDriver == \Config::get('dbDriver')) ? ' selected="selected"' : ''),
									($strDriver ?: '-'));
		}

		$this->Template->drivers = $strDrivers;
		$this->Template->driver = \Config::get('dbDriver');
		$this->Template->host = \Config::get('dbHost');
		$this->Template->user = \Config::get('dbUser');
		$this->Template->pass = (\Config::get('dbPass') != '') ? '*****' : '';
		$this->Template->port = \Config::get('dbPort');
		$this->Template->socket = \Config::get('dbSocket');
		$this->Template->pconnect = \Config::get('dbPconnect');
		$this->Template->dbcharset = \Config::get('dbCharset');
		$this->Template->database = \Config::get('dbDatabase');

		// Store the database connection parameters
		if (\Input::post('FORM_SUBMIT') == 'tl_database_login')
		{
			foreach (preg_grep('/^db/', array_keys($_POST)) as $strKey)
			{
				if ($strKey == 'dbPass' && \Input::post($strKey, true) == '*****')
				{
					continue;
				}

				\Config::persist($strKey, \Input::post($strKey, true));
			}

			$this->reload();
		}

		// No driver selected (see #6088)
		if (\Config::get('dbDriver') == '')
		{
			$this->Template->dbConnection = false;
			$this->outputAndExit();
		}

		// Try to connect
		try
		{
			$this->import('Database');
			$this->Database->listTables();
			$this->Template->dbConnection = true;
		}
		catch (\Exception $e)
		{
			$this->Template->dbConnection = false;
			$this->Template->dbError = $e->getMessage();
			$this->outputAndExit();
		}
	}


	/**
	 * Run the database updates
	 */
	protected function runDatabaseUpdates()
	{
		// Fresh installation
		if (!$this->Database->tableExists('tl_module'))
		{
			return;
		}

		$objRow = $this->Database->query("SELECT COUNT(*) AS count FROM tl_page");

		// Still a fresh installation
		if ($objRow->count < 1)
		{
			return;
		}

		// Run the updates
		foreach (get_class_methods($this) as $method)
		{
			if (strncmp($method, 'update', 6) === 0)
			{
				$this->$method();
			}
		}
	}


	/**
	 * Store the collation
	 */
	protected function storeCollation()
	{
		if (\Input::post('FORM_SUBMIT') == 'tl_collation')
		{
			$strCharset = strtolower(\Config::get('dbCharset'));
			$strCollation = \Input::post('dbCollation');

			try
			{
				$this->Database->query("ALTER DATABASE " . \Config::get('dbDatabase') . " DEFAULT CHARACTER SET $strCharset COLLATE $strCollation");
			}
			catch (\Exception $e) {}

			$arrTables = $this->Database->listTables();

			foreach ($arrTables as $strTable)
			{
				if (strncmp($strTable, 'tl_', 3) !== 0)
				{
					continue;
				}

				if (!in_array($strTable, $arrTables))
				{
					$this->Database->query("ALTER TABLE $strTable DEFAULT CHARACTER SET $strCharset COLLATE $strCollation");
					$arrTables[] = $strTable;
				}

				$arrFields = $this->Database->listFields($strTable);

				foreach ($arrFields as $arrField)
				{
					if ($arrField['collation'] === null)
					{
						continue;
					}

					$strQuery = "ALTER TABLE $strTable CHANGE {$arrField['name']} {$arrField['name']} {$arrField['origtype']} CHARACTER SET $strCharset COLLATE $strCollation";

					if ($arrField['null'] == 'NULL')
					{
						$strQuery .= " NULL";
					}
					else
					{
						$strQuery .= " NOT NULL DEFAULT '{$arrField['default']}'";
					}

					$this->Database->query($strQuery);
				}
			}

			\Config::persist('dbCollation', $strCollation);
			$this->reload();
		}

		$arrOptions = array();

		$objCollation = $this->Database->prepare("SHOW COLLATION LIKE ?")
									   ->execute(\Config::get('dbCharset') . '\_%');

		while ($objCollation->next())
		{
			$key = $objCollation->Collation;

			$arrOptions[$key] = sprintf('<option value="%s"%s>%s</option>',
										$key,
										(($key == \Config::get('dbCollation')) ? ' selected="selected"' : ''),
										$key);
		}

		ksort($arrOptions);
		$this->Template->collations = implode('', $arrOptions);
	}


	/**
	 * Adjust the database tables
	 */
	protected function adjustDatabaseTables()
	{
		if (\Input::post('FORM_SUBMIT') == 'tl_tables')
		{
			$sql = \Input::post('sql');

			if (!empty($sql) && is_array($sql))
			{
				foreach ($sql as $key)
				{
					if (isset($_SESSION['sql_commands'][$key]))
					{
						$this->Database->query(str_replace('DEFAULT CHARSET=utf8;', 'DEFAULT CHARSET=utf8 COLLATE ' . \Config::get('dbCollation') . ';', $_SESSION['sql_commands'][$key]));
					}
				}
			}

			$_SESSION['sql_commands'] = array();
			$this->reload();
		}

		// Wait for the tables to be created (see #5061)
		if ($this->Database->tableExists('tl_log'))
		{
			$this->handleRunOnce();
		}

		$this->import('Database\\Installer', 'Installer');

		$this->Template->dbUpdate = $this->Installer->generateSqlForm();
		$this->Template->dbUpToDate = ($this->Template->dbUpdate != '') ? false : true;
	}


	/**
	 * Import the example website
	 */
	protected function importExampleWebsite()
	{
		/** @var \SplFileInfo[] $objFiles */
		$objFiles = new \RecursiveIteratorIterator(
			new \Filter\SqlFiles(
				new \RecursiveDirectoryIterator(
					TL_ROOT . '/templates',
					\FilesystemIterator::UNIX_PATHS|\FilesystemIterator::FOLLOW_SYMLINKS|\FilesystemIterator::SKIP_DOTS
				)
			)
		);

		$arrTemplates = array();

		// Add the relative paths
		foreach ($objFiles as $objFile)
		{
			$arrTemplates[] = str_replace(TL_ROOT . '/templates/', '', $objFile->getPathname());
		}

		$strTemplates = '<option value="">-</option>';

		// Build the select options
		foreach ($arrTemplates as $strTemplate)
		{
			$strTemplates .= sprintf('<option value="%s">%s</option>', $strTemplate, specialchars($strTemplate));
		}

		$this->Template->templates = $strTemplates;

		// Process the request after the select menu has been generated
		// so the options show up even if the import throws an Exception
		if (\Input::post('FORM_SUBMIT') == 'tl_tutorial')
		{
			$this->Template->emptySelection = true;
			$strTemplate = \Input::post('template');

			// Template selected
			if ($strTemplate != '' && in_array($strTemplate, $arrTemplates))
			{
				$tables = preg_grep('/^tl_/i', $this->Database->listTables());

				// Truncate tables
				if (!isset($_POST['preserve']))
				{
					foreach ($tables as $table)
					{
						// Preserve the repository tables (see #6037)
						if (isset($_POST['override']) || ($table != 'tl_repository_installs' && $table != 'tl_repository_instfiles'))
						{
							$this->Database->execute("TRUNCATE TABLE " . $table);
						}
					}
				}

				// Import data
				$file = file(TL_ROOT . '/templates/' . $strTemplate);
				$sql = preg_grep('/^INSERT /', $file);

				foreach ($sql as $query)
				{
					// Skip the repository tables (see #6037)
					if (isset($_POST['override']) || (strpos($query, '`tl_repository_installs`') === false && strpos($query, '`tl_repository_instfiles`') === false))
					{
						$this->Database->execute($query);
					}
				}

				\Config::persist('exampleWebsite', time());
				$this->reload();
			}
		}

		$this->Template->dateImported = \Date::parse(\Config::get('datimFormat'), \Config::get('exampleWebsite'));
	}


	/**
	 * Create an admin user
	 */
	protected function createAdminUser()
	{
		try
		{
			$objAdmin = $this->Database->execute("SELECT COUNT(*) AS count FROM tl_user WHERE admin=1");

			if ($objAdmin->count > 0)
			{
				$this->Template->adminCreated = true;
			}
			elseif (\Input::post('FORM_SUBMIT') == 'tl_admin')
			{
				// Do not allow special characters in usernames
				if (preg_match('/[#\(\)\/<=>]/', \Input::post('username', true)))
				{
					$this->Template->usernameError = $GLOBALS['TL_LANG']['ERR']['extnd'];
				}
				// The username must not contain whitespace characters (see #4006)
				elseif (strpos(\Input::post('username', true), ' ') !== false)
				{
					$this->Template->usernameError = sprintf($GLOBALS['TL_LANG']['ERR']['noSpace'], $GLOBALS['TL_LANG']['MSC']['username']);
				}
				// Validate the e-mail address (see #6003)
				elseif (!\Validator::isEmail(\Input::post('email', true)))
				{
					$this->Template->emailError = $GLOBALS['TL_LANG']['ERR']['email'];
				}
				// The passwords do not match
				elseif (\Input::post('pass', true) != \Input::post('confirm_pass', true))
				{
					$this->Template->passwordError = $GLOBALS['TL_LANG']['ERR']['passwordMatch'];
				}
				// The password is too short
				elseif (utf8_strlen(\Input::post('pass', true)) < \Config::get('minPasswordLength'))
				{
					$this->Template->passwordError = sprintf($GLOBALS['TL_LANG']['ERR']['passwordLength'], \Config::get('minPasswordLength'));
				}
				// Password and username are the same
				elseif (\Input::post('pass', true) == \Input::post('username', true))
				{
					$this->Template->passwordError = $GLOBALS['TL_LANG']['ERR']['passwordName'];
				}
				// Save the data
				elseif (\Input::post('name') != '' && \Input::post('email', true) != '' && \Input::post('username', true) != '')
				{
					$time = time();
					$strPassword = \Encryption::hash(\Input::post('pass', true));

					$this->Database->prepare("INSERT INTO tl_user (tstamp, name, email, username, password, language, backendTheme, admin, showHelp, useRTE, useCE, thumbnails, dateAdded) VALUES ($time, ?, ?, ?, ?, ?, ?, 1, 1, 1, 1, 1, $time)")
								   ->execute(\Input::post('name'), \Input::post('email', true), \Input::post('username', true), $strPassword, str_replace('-', '_', $GLOBALS['TL_LANGUAGE']), \Config::get('backendTheme'));

					\Config::persist('adminEmail', \Input::post('email', true));

					// Scan the upload folder (see #6134)
					if ($this->Database->tableExists('tl_files') && $this->Database->query("SELECT COUNT(*) AS count FROM tl_files")->count < 1)
					{
						$this->import('Database\\Updater', 'Updater');
						$this->Updater->scanUploadFolder();
					}

					$this->reload();
				}

				$this->Template->adminName = \Input::post('name');
				$this->Template->adminEmail = \Input::post('email', true);
				$this->Template->adminUser = \Input::post('username', true);
			}
		}
		catch (\Exception $e)
		{
			$this->Template->adminCreated = false;
		}
	}


	/**
	 * Create the local configuration files if they do not exist
	 */
	protected function createLocalConfigurationFiles()
	{
		if (\Config::get('installPassword') != '')
		{
			return;
		}

		// The localconfig.php file is created by the Config class
		foreach (array('dcaconfig', 'initconfig', 'langconfig') as $file)
		{
			if (!file_exists(TL_ROOT . '/system/config/' . $file . '.php'))
			{
				\File::putContent('system/config/'. $file .'.php', '<?php' . "\n\n// Put your custom configuration here\n");
			}
		}
	}


	/**
	 * Set the authentication cookie
	 */
	protected function setAuthCookie()
	{
		$_SESSION['TL_INSTALL_EXPIRE'] = (time() + 300);
		$_SESSION['TL_INSTALL_AUTH'] = md5(uniqid(mt_rand(), true) . (!\Config::get('disableIpCheck') ? \Environment::get('ip') : '') . session_id());
		$this->setCookie('TL_INSTALL_AUTH', $_SESSION['TL_INSTALL_AUTH'], $_SESSION['TL_INSTALL_EXPIRE'], null, null, false, true);
	}


	/**
	 * Store the relative path
	 */
	protected function storeRelativePath()
	{
		if (TL_PATH === null)
		{
			return;
		}

		if (file_exists(TL_ROOT . '/system/config/pathconfig.php'))
		{
			$strPath = include TL_ROOT . '/system/config/pathconfig.php';

			if (TL_PATH == $strPath)
			{
				return;
			}
		}

		try
		{
			\File::putContent('system/config/pathconfig.php', '<?php' . "\n\n// Relative path to the installation\nreturn " . var_export(TL_PATH, true) . ";\n");
		}
		catch (\Exception $e)
		{
			log_message($e->getMessage());
		}
	}


	/**
	 * Output the template file and exit
	 */
	protected function outputAndExit()
	{
		$this->Template->theme = \Backend::getTheme();
		$this->Template->base = \Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->charset = \Config::get('characterSet');
		$this->Template->pageOffset = \Input::cookie('BE_PAGE_OFFSET');
		$this->Template->action = ampersand(\Environment::get('request'));
		$this->Template->noCookies = $GLOBALS['TL_LANG']['MSC']['noCookies'];
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['tl_install']['installTool'][0]);
		$this->Template->expandNode = $GLOBALS['TL_LANG']['MSC']['expandNode'];
		$this->Template->collapseNode = $GLOBALS['TL_LANG']['MSC']['collapseNode'];
		$this->Template->loadingData = $GLOBALS['TL_LANG']['MSC']['loadingData'];
		$this->Template->ie6warning = sprintf($GLOBALS['TL_LANG']['ERR']['ie6warning'], '<a href="http://ie6countdown.com">', '</a>');
		$this->Template->hasComposer = is_dir(TL_ROOT . '/system/modules/!composer');

		$this->Template->output();
		exit;
	}


	/**
	 * Enable the safe mode and switch to maintenance mode
	 */
	protected function enableSafeMode()
	{
		if (!\Config::get('maintenanceMode'))
		{
			\Config::set('maintenanceMode', true);
			\Config::persist('maintenanceMode', true);
		}

		if (!\Config::get('coreOnlyMode') && count(array_diff(scan(TL_ROOT . '/system/modules'), array('core', 'calendar', 'comments', 'devtools', 'faq', 'listing', 'news', 'newsletter', 'repository'))) > 0)
		{
			\Config::set('coreOnlyMode', true);
			\Config::persist('coreOnlyMode', true);
		}
	}


	/**
	 * Version 2.8.0 update
	 */
	protected function update28()
	{
		if ($this->Database->tableExists('tl_layout') && !$this->Database->fieldExists('script', 'tl_layout'))
		{
			$this->enableSafeMode();

			if (\Input::post('FORM_SUBMIT') == 'tl_28update')
			{
				$this->import('Database\\Updater', 'Updater');
				$this->Updater->run28Update();
				$this->reload();
			}

			$this->Template->is28Update = true;
			$this->outputAndExit();
		}
	}


	/**
	 * Version 2.9.0 update
	 */
	protected function update29()
	{
		if ($this->Database->tableExists('tl_layout') && !$this->Database->tableExists('tl_theme'))
		{
			$this->enableSafeMode();

			if (\Input::post('FORM_SUBMIT') == 'tl_29update')
			{
				$this->import('Database\\Updater', 'Updater');
				$this->Updater->run29Update();
				$this->reload();
			}

			$this->Template->is29Update = true;
			$this->outputAndExit();
		}
	}


	/**
	 * Version 2.9.2 update
	 */
	protected function update292()
	{
		if ($this->Database->tableExists('tl_calendar_events'))
		{
			$arrFields = $this->Database->listFields('tl_calendar_events');

			foreach ($arrFields as $arrField)
			{
				if ($arrField['name'] == 'startDate' && $arrField['type'] != 'int')
				{
					$this->enableSafeMode();

					if (\Input::post('FORM_SUBMIT') == 'tl_292update')
					{
						$this->import('Database\\Updater', 'Updater');
						$this->Updater->run292Update();
						$this->reload();
					}

					$this->Template->is292Update = true;
					$this->outputAndExit();
				}
			}
		}
	}


	/**
	 * Version 2.10.0 update
	 */
	protected function update210()
	{
		if ($this->Database->tableExists('tl_style') && !$this->Database->fieldExists('positioning', 'tl_style'))
		{
			$this->enableSafeMode();

			if (\Input::post('FORM_SUBMIT') == 'tl_210update')
			{
				$this->import('Database\\Updater', 'Updater');
				$this->Updater->run210Update();
				$this->reload();
			}

			$this->Template->is210Update = true;
			$this->outputAndExit();
		}
	}


	/**
	 * Version 3.0.0 update
	 */
	protected function update300()
	{
		// Step 1: database structure
		if (!$this->Database->tableExists('tl_files'))
		{
			$this->enableSafeMode();

			if (\Input::post('FORM_SUBMIT') == 'tl_30update')
			{
				$this->import('Database\\Updater', 'Updater');
				$this->Updater->run300Update();
				$this->reload();
			}

			// Disable the tasks extension (see #4907)
			if (is_dir(TL_ROOT . '/system/modules/tasks'))
			{
				\System::disableModule('tasks');
			}

			// Reset the upload path if it has been changed already (see #5560 and #5870)
			if (\Config::get('uploadPath') == 'files' && is_dir(TL_ROOT . '/tl_files'))
			{
				\Config::set('uploadPath', 'tl_files');
				\Config::persist('uploadPath', 'tl_files');
			}

			// Show a warning if the upload folder does not exist (see #4626)
			if (!is_dir(TL_ROOT . '/' . \Config::get('uploadPath')))
			{
				$this->Template->filesWarning = sprintf($GLOBALS['TL_LANG']['tl_install']['filesWarning'], '<a href="https://gist.github.com/3304014" target="_blank">https://gist.github.com/3304014</a>');
			}

			$this->Template->step = 1;
			$this->Template->is30Update = true;
			$this->outputAndExit();
		}

		$objRow = $this->Database->query("SELECT COUNT(*) AS count FROM tl_files");

		// Step 2: scan the upload folder if it is not empty (see #6061)
		if ($objRow->count < 1 && count(scan(TL_ROOT . '/' . \Config::get('uploadPath'))) > 0)
		{
			$this->enableSafeMode();

			if (\Input::post('FORM_SUBMIT') == 'tl_30update')
			{
				$this->import('Database\\Updater', 'Updater');
				$this->Updater->scanUploadFolder();

				\Config::persist('checkFileTree', true);
				$this->reload();
			}

			$this->Template->step = 2;
			$this->Template->is30Update = true;
			$this->outputAndExit();
		}

		// Step 3: update the database fields
		elseif (\Config::get('checkFileTree'))
		{
			$this->enableSafeMode();

			if (\Input::post('FORM_SUBMIT') == 'tl_30update')
			{
				$this->import('Database\\Updater', 'Updater');
				$this->Updater->updateFileTreeFields();

				\Config::persist('checkFileTree', false);
				$this->reload();
			}

			$this->Template->step = 3;
			$this->Template->is30Update = true;
			$this->outputAndExit();
		}
	}


	/**
	 * Version 3.1.0 update
	 */
	protected function update31()
	{
		if ($this->Database->tableExists('tl_content') && $this->Database->fieldExists('mooType', 'tl_content'))
		{
			$this->enableSafeMode();

			if (\Input::post('FORM_SUBMIT') == 'tl_31update')
			{
				$this->import('Database\\Updater', 'Updater');
				$this->Updater->run31Update();
				$this->reload();
			}

			$this->Template->is31Update = true;
			$this->outputAndExit();
		}
	}


	/**
	 * Version 3.2.0 update
	 */
	protected function update32()
	{
		if ($this->Database->tableExists('tl_files'))
		{
			$blnDone = false;

			// Check whether the field has been changed already
			foreach ($this->Database->listFields('tl_layout') as $arrField)
			{
				if ($arrField['name'] == 'sections' && $arrField['length'] == 1022)
				{
					$blnDone = true;
					break;
				}
			}

			// Run the version 3.2.0 update
			if (!$blnDone)
			{
				$this->enableSafeMode();

				if (\Input::post('FORM_SUBMIT') == 'tl_32update')
				{
					$this->import('Database\\Updater', 'Updater');
					$this->Updater->run32Update();
					$this->reload();
				}

				$this->Template->is32Update = true;
				$this->outputAndExit();
			}
		}
	}


	/**
	 * Version 3.3.0 update
	 */
	protected function update33()
	{
		if ($this->Database->tableExists('tl_layout') && !$this->Database->fieldExists('viewport', 'tl_layout'))
		{
			$this->enableSafeMode();

			if (\Input::post('FORM_SUBMIT') == 'tl_33update')
			{
				$this->import('Database\\Updater', 'Updater');
				$this->Updater->run33Update();
				$this->reload();
			}

			$this->Template->is33Update = true;
			$this->outputAndExit();
		}
	}


	/**
	 * Version 3.5.0 update
	 */
	protected function update35()
	{
		if ($this->Database->tableExists('tl_member'))
		{
			$strIndex = null;

			foreach ($this->Database->listFields('tl_member') as $arrField)
			{
				if ($arrField['name'] == 'username' && $arrField['type'] == 'index')
				{
					$strIndex = $arrField['index'];
					break;
				}
			}

			if ($strIndex != 'UNIQUE')
			{
				$this->enableSafeMode();

				if (\Input::post('FORM_SUBMIT') == 'tl_35update')
				{
					$this->import('Database\\Updater', 'Updater');
					$this->Updater->run35Update();
					$this->reload();
				}

				$this->Template->is35Update = true;
				$this->outputAndExit();
			}
		}
	}
}
