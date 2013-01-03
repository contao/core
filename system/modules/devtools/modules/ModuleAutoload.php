<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Devtools
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleAutoload
 *
 * Back end module "autoload files".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Devtools
 */
class ModuleAutoload extends \BackendModule
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'dev_autoload';


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->loadLanguageFile('tl_autoload');

		// Process the request
		if (\Input::post('FORM_SUBMIT') == 'tl_autoload')
		{
			if (isset($_POST['autoload']))
			{
				$this->createAutoloadFiles();
			}
			elseif (isset($_POST['ide_compat']))
			{
				$this->createIdeCompatFile();
			}

			$this->reload();
		}

		$arrModules = array();

		// List all modules
		foreach (scan(TL_ROOT . '/system/modules') as $strFile)
		{
			if (strncmp($strFile, '.', 1) === 0 || !is_dir(TL_ROOT . '/system/modules/' . $strFile))
			{
				continue;
			}

			$arrModules[] = $strFile;
		}

		$this->Template->modules = $arrModules;
		$this->Template->messages = \Message::generate();
		$this->Template->href = $this->getReferer(true);
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']);
		$this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];
		$this->Template->headline = $GLOBALS['TL_LANG']['tl_autoload']['headline'];
		$this->Template->action = ampersand(\Environment::get('request'));
		$this->Template->available = $GLOBALS['TL_LANG']['tl_autoload']['available'];
		$this->Template->xplAvailable = $GLOBALS['TL_LANG']['tl_autoload']['xplAvailable'];
		$this->Template->selectAll = $GLOBALS['TL_LANG']['MSC']['selectAll'];
		$this->Template->override = $GLOBALS['TL_LANG']['tl_autoload']['override'];
		$this->Template->xplOverride = $GLOBALS['TL_LANG']['tl_autoload']['xplOverride'];
		$this->Template->submitButton = specialchars($GLOBALS['TL_LANG']['MSC']['continue']);
		$this->Template->autoload = $GLOBALS['TL_LANG']['tl_autoload']['autoload'];
		$this->Template->ideCompat = $GLOBALS['TL_LANG']['tl_autoload']['ideCompat'];
	}


	/**
	 * Generate the autoload.php files
	 */
	protected function createAutoloadFiles()
	{
		$arrModules = \Input::post('modules');

		if (empty($arrModules))
		{
			\Message::addError($GLOBALS['TL_LANG']['tl_autoload']['emptySelection']);
			return;
		}

		$intYear = date('Y');

		foreach ($arrModules as $strModule)
		{
			// The autoload.php file exists
			if (!\Input::post('override') && file_exists(TL_ROOT . '/system/modules/' . $strModule . '/config/autoload.php'))
			{
				\Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_autoload']['autoloadExists'], $strModule));
				continue;
			}

			$intClassWidth = 0;
			$arrFiles = array();
			$arrClassLoader = array();
			$arrNamespaces = array();

			// Default configuration
			$arrDefaultConfig = array
			(
				'register_namespaces' => true,
				'register_classes'    => true,
				'register_templates'  => true,
			);

			// Create the autoload.ini file if it does not yet exist
			if (!file_exists(TL_ROOT . '/system/modules/' . $strModule . '/config/autoload.ini'))
			{
				$objIni = new \File('system/modules/devtools/config/autoload.ini');
				$objIni->copyTo('system/modules/' . $strModule . '/config/autoload.ini');
			}

			$arrDefaultConfig = array_merge($arrDefaultConfig, parse_ini_file(TL_ROOT . '/system/modules/' . $strModule . '/config/autoload.ini', true));

			// Recursively scan all subfolders
			$objFiles = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator(TL_ROOT . '/system/modules/' . $strModule, \FilesystemIterator::UNIX_PATHS)
			);

			// Get all PHP files
			foreach ($objFiles as $objFile)
			{
				if ($objFile->isFile() && pathinfo($objFile->getFilename(), PATHINFO_EXTENSION) == 'php')
				{
					$strRelpath = str_replace(TL_ROOT . '/system/modules/' . $strModule . '/', '', $objFile->getPathname());

					if (strncmp($strRelpath, 'assets/', 7) !== 0 && strncmp($strRelpath, 'config/', 7) !== 0 && strncmp($strRelpath, 'dca/', 4) !== 0 && strncmp($strRelpath, 'languages/', 10) !== 0 && strncmp($strRelpath, 'templates/', 10) !== 0)
					{
						$arrFiles[] = $strRelpath;
					}
				}
			}

			// Scan for classes
			if ($arrDefaultConfig['register_namespaces'] || $arrDefaultConfig['register_classes'])
			{
				foreach ($arrFiles as $strFile)
				{
					$strBuffer = '';
					$arrMatches = array();

					// Store the file size for fread()
					$size = filesize(TL_ROOT . '/system/modules/' . $strModule . '/' . $strFile);
					$fh = fopen(TL_ROOT . '/system/modules/' . $strModule . '/' . $strFile, 'rb');

					// Read until a class or interface definition has been found
					while (!preg_match('/(class|interface) ' . preg_quote(basename($strFile, '.php'), '/') . '/', $strBuffer, $arrMatches) && $size > 0 && !feof($fh))
					{
						$length = min(512, $size);
						$strBuffer .= fread($fh, $length);
						$size -= $length; // see #4876
					}

					fclose($fh);

					// The file does not contain a class or interface
					if (empty($arrMatches))
					{
						continue;
					}

					$strNamespace = preg_replace('/^.*namespace ([^; ]+);.*$/s', '$1', $strBuffer);

					// No namespace declaration found
					if ($strNamespace == $strBuffer)
					{
						$strNamespace = '';
					}

					unset($strBuffer);
					$arrConfig = $arrDefaultConfig;

					// Search for a path configuration
					foreach ($arrDefaultConfig as $strPattern=>$arrPathConfig)
					{
						// Merge the path configuration with the global configuration
						if (is_array($arrPathConfig) && fnmatch($strPattern, $strFile))
						{
							$arrConfig = array_merge($arrDefaultConfig, $arrPathConfig);
							break;
						}
					}

					// Register the namespace
					if ($strNamespace != '')
					{
						if ($arrConfig['register_namespaces'] && $strNamespace != 'Contao')
						{
							// Register only the first chunk as namespace
							if (strpos($strNamespace, '\\') !== false)
							{
								$arrNamespaces[] = substr($strNamespace, 0, strpos($strNamespace, '\\'));
							}
							else
							{
								$arrNamespaces[] = $strNamespace;
							}
						}

						$strNamespace .=  '\\';
					}

					// Register the class
					if ($arrConfig['register_classes'])
					{
						$strKey = $strNamespace . basename($strFile, '.php');
						$arrClassLoader[$strKey] = 'system/modules/' . $strModule . '/' . $strFile;
						$intClassWidth = max(strlen($strKey), $intClassWidth);
					}
				}
			}

			$intTplWidth = 0;
			$arrTplLoader = array();

			// Scan for templates
			if ($arrDefaultConfig['register_templates'])
			{
				if (is_dir(TL_ROOT . '/system/modules/' . $strModule . '/templates'))
				{
					foreach (scan(TL_ROOT . '/system/modules/' . $strModule . '/templates') as $strFile)
					{
						if (strrchr($strFile, '.') != '.html5' && strrchr($strFile, '.') != '.xhtml')
						{
							continue;
						}

						$strKey = basename($strFile, strrchr($strFile, '.'));
						$arrTplLoader[$strKey] = 'system/modules/' . $strModule . '/templates';
						$intTplWidth = max(strlen($strKey), $intTplWidth);
					}
				}
			}

			// Neither classes nor templates found
			if (empty($arrNamespaces) && empty($arrClassLoader) && empty($arrTplLoader))
			{
				continue;
			}

			$strPackage = ucfirst($strModule);

			$objFile = new \File('system/modules/' . $strModule . '/config/autoload.php');
			$objFile->write(
<<<EOT
<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-$intYear Leo Feyer
 * 
 * @package $strPackage
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

EOT
			);

			// Namespaces
			if (!empty($arrNamespaces))
			{
				$arrNamespaces = array_unique($arrNamespaces);

				if (!empty($arrNamespaces))
				{
					$objFile->append(
<<<EOT


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
EOT
					);

					foreach ($arrNamespaces as $strNamespace)
					{
						$objFile->append("\t'" . $strNamespace . "',");
					}

					$objFile->append('));');
				}
			}

			// Classes
			if (!empty($arrClassLoader))
			{
				$objFile->append(
<<<EOT


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
EOT
				);

				$strGroup = null;

				foreach ($arrClassLoader as $strClass=>$strPath)
				{
					$strRelpath = str_replace('system/modules/' . $strModule . '/', '', $strPath);
					$strBasedir = substr($strRelpath, 0, strpos($strRelpath, '/'));

					if ($strBasedir != '')
					{
						if ($strGroup === null)
						{
							$strGroup = $strBasedir;
							$objFile->append("\t// " . ucfirst($strBasedir));
						}
						elseif ($strBasedir != $strGroup)
						{
							$strGroup = $strBasedir;
							$objFile->append("\n\t// " . ucfirst($strBasedir));
						}
					}

					$strClass = "'" . $strClass . "'";
					$objFile->append("\t" . str_pad($strClass, $intClassWidth+2) . " => '$strPath',");
				}

				$objFile->append('));');
			}

			// Templates
			if (!empty($arrTplLoader))
			{
				$objFile->append(
<<<EOT


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
EOT
				);

				foreach ($arrTplLoader as $strName=>$strPath)
				{
					$strName = "'" . $strName . "'";
					$objFile->append("\t" . str_pad($strName, $intTplWidth+2) . " => '$strPath',");
				}

				$objFile->append('));');
			}

			$objFile->close();
			\Message::addConfirmation(sprintf($GLOBALS['TL_LANG']['tl_autoload']['autoloadConfirm'], $strModule));
		}
	}


	/**
	 * Generate the ide_compat.php file
	 */
	protected function createIdeCompatFile()
	{
		$arrCompat = array();

		// Scan all modules
		$arrModules = array_filter(scan(TL_ROOT . '/system/modules'), function($e) {
			return is_dir(TL_ROOT . '/system/modules/' . $e) ? $e : null;
		});

		foreach ($arrModules as $strModule)
		{
			$arrFiles = array();

			// Recursively scan all subfolders
			$objFiles = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator(TL_ROOT . '/system/modules/' . $strModule, \FilesystemIterator::UNIX_PATHS)
			);

			// Get all PHP files
			foreach ($objFiles as $objFile)
			{
				if ($objFile->isFile() && pathinfo($objFile->getFilename(), PATHINFO_EXTENSION) == 'php')
				{
					$strRelpath = str_replace(TL_ROOT . '/system/modules/' . $strModule . '/', '', $objFile->getPathname());

					if (strncmp($strRelpath, 'assets/', 7) !== 0 && strncmp($strRelpath, 'config/', 7) !== 0 && strncmp($strRelpath, 'dca/', 4) !== 0 && strncmp($strRelpath, 'languages/', 10) !== 0 && strncmp($strRelpath, 'templates/', 10) !== 0)
					{
						$arrFiles[] = $strRelpath;
					}
				}
			}

			// Scan for classes
			foreach ($arrFiles as $strFile)
			{
				$strBuffer = '';
				$arrMatches = array();

				$fh = fopen(TL_ROOT . '/system/modules/' . $strModule . '/' . $strFile, 'rb');

				// Read until a class or interface definition has been found
				while (!feof($fh) && !preg_match('/(class|interface) ' . preg_quote(basename($strFile, '.php'), '/') . '/', $strBuffer, $arrMatches))
				{
					$strBuffer .= fread($fh, 512);
				}

				fclose($fh);

				// The file does not contain a class or interface
				if (empty($arrMatches))
				{
					continue;
				}

				$strType = $arrMatches[1];
				$strNamespace = preg_replace('/^.*namespace ([^; ]+);.*$/s', '$1', $strBuffer);

				// No namespace declaration found
				if ($strNamespace == $strBuffer)
				{
					continue;
				}

				list($strFirst, $strRest) = explode('\\', $strNamespace, 2);

				// Add the ide_compat information
				$arrCompat[$strModule][$strRest][] = array
				(
					'namespace' => $strFirst,
					'class'     => basename($strFile, '.php'),
					'abstract'  => preg_match('/^.*abstract ' . preg_quote($strType, '/') . ' [^;]+.*$/s', $strBuffer),
					'type'      => $strType
				);

				unset($strBuffer);
			}
		}

		$intYear = date('Y');

		// Write the file
		$objFile = new \File('system/helper/ide_compat.php');
		$objFile->write(
<<<EOT
<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-$intYear Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * This file is not used in Contao. Its only purpose is to make
 * PHP IDEs like Eclipse, Zend Studio or PHPStorm realize the
 * class origins, since the dynamic class aliasing we are using
 * is a bit too complex for them to understand.
 */

EOT
		);

		// Add the classes
		foreach ($arrCompat as $strModule=>$arrNamespaces)
		{
			$objFile->append("\n// " . $strModule);

			foreach ($arrNamespaces as $strNamespace=>$arrClasses)
			{
				$objFile->append('namespace ' . $strNamespace . ' {');

				foreach ($arrClasses as $arrClass)
				{
					$objFile->append("\t" . ($arrClass['abstract'] ? 'abstract ' : '') . $arrClass['type'] . ' ' . $arrClass['class'] . ' extends ' . $arrClass['namespace'] . '\\' . ($strNamespace ? $strNamespace . '\\' : '') . $arrClass['class'] . ' {}');
				}

				$objFile->append('}');
			}
		}

		$objFile->close();
		\Message::addConfirmation($GLOBALS['TL_LANG']['tl_autoload']['ideCompatConfirm']);
	}
}
