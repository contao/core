<?php

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once('../system/initialize.php');


/**
 * Show error messages
 */
@ini_set('display_errors', 1);
@error_reporting(1);


/**
 * Class InstallTool
 *
 * Back end install tool.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class InstallTool extends Controller
{

	/**
	 * Initialize the controller
	 */
	public function __construct()
	{
		$this->import('String');
		$this->import('Files');

		parent::__construct();

		$GLOBALS['TL_LANGUAGE'] = 'en';
		$GLOBALS['TL_CONFIG']['showHelp'] = false;
		$GLOBALS['TL_CONFIG']['displayErrors'] = true;
	}


	/**
	 * Run controller and parse the login template
	 */
	public function run()
	{
		$this->Template = new BackendTemplate('be_install');


		/**
		 * Lock the tool if there are too many login attempts
		 */
		if ($GLOBALS['TL_CONFIG']['installCount'] >= 3)
		{
			$this->Template->locked = true;
			$this->outputAndExit();
		}


		/**
		 * Check whether the local configuration file is writeable
		 */
		if (!$this->Files->is_writeable('system/config/localconfig.php'))
		{
			$this->Template->lcfWriteable = false;
			$this->outputAndExit();
		}

		$this->Template->lcfWriteable = true;


		/**
		 * Check the websitePath
		 */
		if (!preg_match('/^' . preg_quote(TL_PATH, '/') . '\/typolight\/install\.php/', $this->Environment->requestUri) && !is_null($GLOBALS['TL_CONFIG']['websitePath']))
		{
			$this->Config->delete("\$GLOBALS['TL_CONFIG']['websitePath']");
			$this->reload();
		}


		/**
		 * Authenticate user
		 */
		if ($this->Input->post('FORM_SUBMIT') == 'tl_login')
		{
			$password =  sha1($this->Input->post('password', true));

			if (strlen($password) && $password != 'da39a3ee5e6b4b0d3255bfef95601890afd80709')
			{
				// Set cookie
				if ($password == $GLOBALS['TL_CONFIG']['installPassword'])
				{
					$this->setCookie('TL_INSTALL_AUTH', md5($this->Environment->ip.session_id()), (time()+300), $GLOBALS['TL_CONFIG']['websitePath']);
					$this->Config->update("\$GLOBALS['TL_CONFIG']['installCount']", 0);

					$this->reload();
				}

				// Increase count
				$this->Config->update("\$GLOBALS['TL_CONFIG']['installCount']", $GLOBALS['TL_CONFIG']['installCount'] + 1);
			}

			$this->Template->passwordError = 'Invalid password!';
		}

		// Check cookie
		if (!$this->Input->cookie('TL_INSTALL_AUTH'))
		{
			$this->Template->login = true;
			$this->outputAndExit();
		}

		// Renew cookie
		$this->setCookie('TL_INSTALL_AUTH', md5($this->Environment->ip.session_id()), (time()+300), $GLOBALS['TL_CONFIG']['websitePath']);


		/**
		 * Set install script password
		 */
		if ($this->Input->post('FORM_SUBMIT') == 'tl_install')
		{
			// Passwords do not match
			if ($this->Input->post('password') != $this->Input->post('confirm_password'))
			{
				$this->Template->passwordError = 'The passwords did not match!';
			}

			// Password too short
			elseif (utf8_strlen($this->Input->post('password')) < 8)
			{
				$this->Template->passwordError = 'A password has to be at least 8 characters long!';
			}

			// Save password
			else
			{
				$this->Config->update("\$GLOBALS['TL_CONFIG']['installPassword']", sha1($this->Input->post('password', true)));
				$this->reload();
			}
		}

		// Password must not be "typolight"
		if ($GLOBALS['TL_CONFIG']['installPassword'] == '77e9b7542ac04858d99a0eaaf77adf54b5e18910')
		{
			$this->Template->setPassword = true;
			$this->outputAndExit();
		}


		/**
		 * Save encryption key
		 */
		if ($this->Input->post('FORM_SUBMIT') == 'tl_encryption')
		{
			$this->Config->update("\$GLOBALS['TL_CONFIG']['encryptionKey']", (($this->Input->post('key')) ? $this->Input->post('key') : md5(uniqid('', true))));
			$this->reload();
		}

		$this->Template->encryptionKey = $GLOBALS['TL_CONFIG']['encryptionKey'];

		if (!strlen($GLOBALS['TL_CONFIG']['encryptionKey']))
		{
			$this->Template->encryption = true;
			$this->outputAndExit();
		}

		if (utf8_strlen($GLOBALS['TL_CONFIG']['encryptionKey']) < 8)
		{
			$this->Template->encryptionLength = true;
			$this->outputAndExit();
		}

		$strDrivers = '';
		$arrDrivers = array('MySQL', 'MySQLi', 'Oracle', 'MSSQL', 'PostgreSQL', 'Sybase');

		foreach ($arrDrivers as $strDriver)
		{
			$strDrivers .= sprintf('  <option value="%s"%s>%s</option>' . "\n",
									$strDriver,
									(($strDriver == $GLOBALS['TL_CONFIG']['dbDriver']) ? ' selected="selected"' : ''),
									$strDriver);
		}

		$this->Template->drivers = $strDrivers;
		$this->Template->driver = $GLOBALS['TL_CONFIG']['dbDriver'];
		$this->Template->host = $GLOBALS['TL_CONFIG']['dbHost'];
		$this->Template->user = $GLOBALS['TL_CONFIG']['dbUser'];
		$this->Template->pass = $GLOBALS['TL_CONFIG']['dbPass'];
		$this->Template->port = $GLOBALS['TL_CONFIG']['dbPort'];
		$this->Template->pconnect = $GLOBALS['TL_CONFIG']['dbPconnect'];
		$this->Template->dbcharset = $GLOBALS['TL_CONFIG']['dbCharset'];
		$this->Template->database = $GLOBALS['TL_CONFIG']['dbDatabase'];


		/**
		 * Store database connection parameters
		 */
		if ($this->Input->post('FORM_SUBMIT') == 'tl_database_login')
		{
			foreach (preg_grep('/^db/', array_keys($_POST)) as $strKey)
			{
				$this->Config->update("\$GLOBALS['TL_CONFIG']['$strKey']", $this->Input->post($strKey, true));
			}

			$this->reload();
		}

		try
		{
			$this->import('Database');
			$this->Database->listTables();

			$this->Template->dbConnection = true;
		}

		catch (Exception $e)
		{
			$this->Template->dbConnection = false;
			$this->Template->dbError = $e->getMessage();

			$this->outputAndExit();
		}


		/**
		 * Update database tables
		 */
		if ($this->Input->post('FORM_SUBMIT') == 'tl_tables')
		{
			$sql = deserialize($this->Input->post('sql'));

			if (is_array($sql))
			{
				foreach ($sql as $command)
				{
					$this->Database->execute($this->String->decodeEntities($command));
				}
			}

			$this->reload();
		}

		$this->Template->dbUpdate = $this->generateSqlForm();
		$this->Template->dbUpToDate = strlen($this->Template->dbUpdate) ? false : true;


		/**
		 * Import the example website
		 */
		if ($this->Input->post('FORM_SUBMIT') == 'tl_tutorial' && file_exists(TL_ROOT . '/templates/' . $this->Input->post('template')))
		{
			$this->Config->update("\$GLOBALS['TL_CONFIG']['exampleWebsite']", time());
			$tables = preg_grep('/^tl_/i', $this->Database->listTables());

			// Truncate tables
			if (!array_key_exists('preserve', $_POST) || !$this->Input->post('preserve'))
			{
				foreach ($tables as $table)
				{
					$this->Database->execute("TRUNCATE TABLE " . $table);
				}
			}

			// Import data
			$file = file(TL_ROOT . '/templates/' . $this->Input->post('template'));
			$sql = preg_grep('/^INSERT /', $file);

			foreach ($sql as $query)
			{
				$this->Database->execute($query);
			}

			$this->reload();
		}

		$strTemplates = '';

		foreach (scan(TL_ROOT . '/templates') as $strFile)
		{
			if (preg_match('/.sql$/', $strFile))
			{
				$strTemplates .= sprintf('<option value="%s">%s</option>', $strFile, specialchars($strFile));
			}
		}

		$this->Template->templates = $strTemplates;
		$this->Template->dateImported = strlen($GLOBALS['TL_CONFIG']['exampleWebsite']) ? date($GLOBALS['TL_CONFIG']['datimFormat'], $GLOBALS['TL_CONFIG']['exampleWebsite']) : false;


		/**
		 * Create an admin user
		 */
		if ($this->Input->post('FORM_SUBMIT') == 'tl_admin')
		{
			// Passwords do not match
			if ($this->Input->post('pass') != $this->Input->post('confirm_pass'))
			{
				$this->Template->adminError = 'The passwords did not match!';
			}

			// Do not allow special characters
			elseif (preg_match('/[#\(\)\/<=>]/', $this->Input->post('pass')))
			{
				$this->Template->adminError = 'For security reasons you can not use these characters (=<>&/()#) here!';
			}

			// Password too short
			elseif (utf8_strlen($this->Input->post('pass')) < 8)
			{
				$this->Template->adminError = 'A password has to be at least 8 characters long!';
			}

			// Save data
			elseif(strlen($this->Input->post('name')) && strlen($this->Input->post('email')) && strlen($this->Input->post('username')))
			{
				$this->Database->prepare("INSERT INTO tl_user (tstamp, name, email, username, password, admin, showHelp, useRTE, thumbnails) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)")
							   ->execute(time(), $this->Input->post('name'), $this->Input->post('email'), $this->Input->post('username'), sha1($this->Input->post('pass', true)), 1, 1, 1, 1);

				$this->Config->update("\$GLOBALS['TL_CONFIG']['adminEmail']", $this->Input->post('email'));
				$this->reload();
			}

			$this->Template->adminName = $this->Input->post('name');
			$this->Template->adminEmail = $this->Input->post('email');
			$this->Template->adminUser = $this->Input->post('username');
		}

		try
		{
			$objAdmin = $this->Database->prepare("SELECT * FROM tl_user WHERE admin=?")->limit(1)->execute(1);
			$this->Template->adminCreated = $objAdmin->numRows ? true : false;
		}

		catch (Exception $e)
		{
			$this->Template->adminCreated = false;
		}


		/**
		 * Clear the cron timestamps so the jobs are run
		 */
		$this->Config->delete("\$GLOBALS['TL_CONFIG']['cron_hourly']");
		$this->Config->delete("\$GLOBALS['TL_CONFIG']['cron_daily']");
		$this->Config->delete("\$GLOBALS['TL_CONFIG']['cron_weekly']");


		/**
		 * Output the template file
		 */
		$this->outputAndExit();
	}


	/**
	 * Generate a HTML form with update commands and return it as string
	 * @return string
	 */
	private function generateSqlForm()
	{
		$count = 0;
		$return = '';
		$sql_command = $this->compileCommands();

		if (!count($sql_command))
		{
			return '';
		}

		$arrOperations = array
		(
			'CREATE'        => 'Create new tables',
			'ALTER_ADD'     => 'Add new columns',
			'ALTER_CHANGE'  => 'Change existing columns',
			'ALTER_DROP'    => 'Drop existing columns',
			'DROP'          => 'Drop existing tables'
		);

		foreach ($arrOperations as $command=>$label)
		{
			if (is_array($sql_command[$command]))
			{
				// Headline
				$return .= '
  <tr>
    <td colspan="2" class="tl_col_0">'.$label.'</td>
  </tr>';

				// Fields
				foreach ($sql_command[$command] as $vv)
				{
					$return .= '
  <tr>
    <td class="tl_col_1"><input type="checkbox" name="sql[]" id="sql_'.$count.'" class="tl_checkbox" value="'.specialchars($vv).'"'.((stristr($command, 'DROP') === false) ? ' checked="checked"' : '').' /></td>
    <td class="tl_col_2"><pre><label for="sql_'.$count++.'">'.$vv.'</label></pre></td>
  </tr>';
				}
			}
		}

		return '
<table cellspacing="0" cellpadding="0" id="sql_table" style="margin-top:9px;" summary="Necessary database modifications">'.$return.'
</table>' . "\n";
	}


	/**
	 * Compile a command array for each necessary database modification
	 * @return array
	 */
	private function compileCommands()
	{
		$drop = array();
		$create = array();
		$return = array();

		$sql_current = $this->getFromDb();
		$sql_target = $this->getFromFile();

		// Create tables
		foreach (array_diff(array_keys($sql_target), array_keys($sql_current)) as $table)
		{
			$return['CREATE'][] = "CREATE TABLE `" . $table . "` (\n  " . implode(",\n  ", $sql_target[$table]['TABLE_FIELDS']) . (count($sql_target[$table]['TABLE_CREATE_DEFINITIONS']) ? ',' : '') . "\n  " . implode(",\n  ", $sql_target[$table]['TABLE_CREATE_DEFINITIONS']) . "\n)".$sql_target[$table]['TABLE_OPTIONS'].';';
			$create[] = $table;
		}

		// Add or change fields
		foreach ($sql_target as $k=>$v)
		{
			if (in_array($k, $create))
			{
				continue;
			}

			// Fields
			if (is_array($v['TABLE_FIELDS']))
			{
				foreach ($v['TABLE_FIELDS'] as $kk=>$vv)
				{
					if (!isset($sql_current[$k]['TABLE_FIELDS'][$kk]))
					{
						$return['ALTER_ADD'][] = 'ALTER TABLE `'.$k.'` ADD '.$vv.';';
					}
					elseif ($sql_current[$k]['TABLE_FIELDS'][$kk] != $vv)
					{
						$return['ALTER_CHANGE'][] = 'ALTER TABLE `'.$k.'` CHANGE `'.$kk.'` '.$vv.';';
					}
				}
			}

			// Create definitions
			if (is_array($v['TABLE_CREATE_DEFINITIONS']))
			{
				foreach ($v['TABLE_CREATE_DEFINITIONS'] as $kk=>$vv)
				{
					if (!isset($sql_current[$k]['TABLE_CREATE_DEFINITIONS'][$kk]))
					{
						$return['ALTER_ADD'][] = 'ALTER TABLE `'.$k.'` ADD '.$vv.';';
					}
					elseif ($sql_current[$k]['TABLE_CREATE_DEFINITIONS'][$kk] != str_replace('FULLTEXT ', '', $vv))
					{
						$return['ALTER_CHANGE'][] = 'ALTER TABLE `'.$k.'` DROP INDEX `'.$kk.'`, ADD '.$vv.';';
					}
				}
			}

			// Move auto_increment fields to the end of the array
			if (is_array($return['ALTER_ADD']))
			{
				foreach (preg_grep('/auto_increment/i', $return['ALTER_ADD']) as $kk=>$vv)
				{
					unset($return['ALTER_ADD'][$kk]);
					$return['ALTER_ADD'][$kk] = $vv;
				}
			}

			if (is_array($return['ALTER_CHANGE']))
			{
				foreach (preg_grep('/auto_increment/i', $return['ALTER_CHANGE']) as $kk=>$vv)
				{
					unset($return['ALTER_CHANGE'][$kk]);
					$return['ALTER_CHANGE'][$kk] = $vv;
				}
			}
		}

		// Drop tables
		foreach (array_diff(array_keys($sql_current), array_keys($sql_target)) as $table)
		{
			$return['DROP'][] = 'DROP TABLE `'.$table.'`;';
			$drop[] = $table;
		}

		// Drop fields
		foreach ($sql_current as $k=>$v)
		{
			if (!in_array($k, $drop))
			{
				// Fields
				if (is_array($v['TABLE_FIELDS']))
				{
					foreach ($v['TABLE_FIELDS'] as $kk=>$vv)
					{
						if (!isset($sql_target[$k]['TABLE_FIELDS'][$kk]))
						{
							$return['ALTER_DROP'][] = 'ALTER TABLE `'.$k.'` DROP `'.$kk.'`;';
						}
					}
				}

				// Create definitions
				if (is_array($v['TABLE_CREATE_DEFINITIONS']))
				{
					foreach ($v['TABLE_CREATE_DEFINITIONS'] as $kk=>$vv)
					{
						if (!isset($sql_target[$k]['TABLE_CREATE_DEFINITIONS'][$kk]))
						{
							$return['ALTER_DROP'][] = 'ALTER TABLE `'.$k.'` DROP INDEX `'.$kk.'`;';
						}
					}
				}
			}
		}

		return $return;
	}


	/**
	 * Compile a table array from all SQL files and return it
	 * @return array
	 */
	private function getFromFile()
	{
		$return = array();

		// Get all SQL files
		foreach (scan(TL_ROOT . '/system/modules') as $strModule)
		{
			if (strncmp($strModule, '.', 1) === 0 || strncmp($strModule, '__', 2) === 0)
			{
				continue;
			}

			$strFile = sprintf('%s/system/modules/%s/config/database.sql', TL_ROOT, $strModule);

			if (!file_exists($strFile))
			{
				continue;
			}

			$data = file($strFile);

			foreach ($data as $k=>$v)
			{
				$key_name = array();
				$subpatterns = array();

				// Unset comments and empty lines
				if (preg_match('/^[#-]+/i', $v) || !strlen(trim($v)))
				{
					unset($data[$k]);
					continue;
				}

				// Store table names
				if (preg_match('/^CREATE TABLE `([^`]+)`/i', $v, $subpatterns))
				{
					$table = $subpatterns[1];
				}

				// Get table options
				elseif (strlen($table) && preg_match('/^\)([^;]+);/i', $v, $subpatterns))
				{
					$return[$table]['TABLE_OPTIONS'] = $subpatterns[1];
					$table = '';
				}

				// Add fields
				elseif (strlen($table))
				{
					preg_match('/^[^`]*`([^`]+)`/i', trim($v), $key_name);

					$first = preg_replace('/\s[^\n\r]+/i', '', $key_name[0]);
					$key = $key_name[1];

					// Create definitions
					if (in_array($first, array('KEY', 'PRIMARY', 'PRIMARY KEY', 'FOREIGN', 'FOREIGN KEY', 'INDEX', 'UNIQUE', 'FULLTEXT', 'CHECK')))
					{
						$return[$table]['TABLE_CREATE_DEFINITIONS'][$key] = preg_replace('/,$/i', '', trim($v));
					}

					else
					{
						$return[$table]['TABLE_FIELDS'][$key] = preg_replace('/,$/i', '', trim($v));
					}
				}
			}
		}

		return $return;
	}


	/**
	 * Compile a table array from the database and return it
	 * @return array
	 */
	private function getFromDB()
	{
		$tables = preg_grep('/^tl_/i', $this->Database->listTables());

		if (!count($tables))
		{
			return array();
		}

		$return = array();

		foreach ($tables as $table)
		{
			$fields = $this->Database->listFields($table);

			foreach ($fields as $field)
			{
				$name = $field['name'];
				$field['name'] = '`'.$field['name'].'`';

				// Field type
				if (strlen($field['length']))
				{
					$field['type'] .= '(' . $field['length'] . (strlen($field['precision']) ? ',' . $field['precision'] : '') . ')';

					unset($field['length']);
					unset($field['precision']);
				}

				// Default values
				if (in_array(strtolower($field['type']), array('text', 'tinytext', 'mediumtext', 'longtext', 'blob', 'tinyblob', 'mediumblob', 'longblob')) || stristr($field['extra'], 'auto_increment'))
				{
					unset($field['default']);
				}

				elseif (is_null($field['default']) || strtolower($field['default']) == 'null')
				{
					$field['default'] = "default NULL";
				}

				else
				{
					$field['default'] = "default '" . $field['default'] . "'";
				}

				// Indices
				if (strlen($field['index']))
				{
					switch ($field['index'])
					{
						case 'PRIMARY':
							$return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'PRIMARY KEY  (`'.$name.'`)';
							break;

						case 'UNIQUE':
							$return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'UNIQUE KEY `'.$name.'` (`'.$name.'`)';
							break;

						case 'FULLTEXT':
							$return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'FULLTEXT KEY `'.$name.'` (`'.$name.'`)';
							break;

						default:
							$return[$table]['TABLE_CREATE_DEFINITIONS'][$name] = 'KEY `'.$name.'` (`'.$name.'`)';
							break;
					}

					unset($field['index']);
				}

				$return[$table]['TABLE_FIELDS'][$name] = trim(implode(' ', $field));
			}
		}

		return $return;
	}


	/**
	 * Output the template file and exit
	 */
	private function outputAndExit()
	{
		$this->Template->theme = $this->getTheme();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->isMac = preg_match('/mac/i', $this->Environment->httpUserAgent);
		$this->Template->pageOffset = $this->Input->cookie('BE_PAGE_OFFSET');
		$this->Template->action = ampersand($this->Environment->request);

		$this->Template->output();
		exit;
	}
}


/**
 * Instantiate controller
 */
$objInstallTool = new InstallTool();
$objInstallTool->run();

?>