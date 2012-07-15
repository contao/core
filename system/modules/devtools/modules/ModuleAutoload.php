<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Devtools
 * @link    http://www.contao.org
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
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
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
	 * Generate module
	 */
	protected function compile()
	{
		$this->loadLanguageFile('tl_autoload');

		// Create the config/autoload.php file
		if (\Input::post('FORM_SUBMIT') == 'tl_autoload')
		{
			// Always scan all modules in ide_compat mode
			if (\Input::post('ide_compat'))
			{
				$arrModules = array_filter(scan(TL_ROOT . '/system/modules'), function($e) {
					return is_dir(TL_ROOT . '/system/modules/' . $e) ? $e : null;
				});
			}
			else
			{
				$arrModules = \Input::post('modules');
			}

			$intYear = date('Y');

			if (empty($arrModules))
			{
				\Message::addError($GLOBALS['TL_LANG']['tl_merge']['emptySelection']);
			}
			else
			{
				$arrCompat = array();

				foreach ($arrModules as $strModule)
				{
					// config/autoload.php exists
					if (!\Input::post('ide_compat') && !\Input::post('override') && file_exists(TL_ROOT . '/system/modules/' . $strModule . '/config/autoload.php'))
					{
						\Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_merge']['autoloadExists'], $strModule));
						continue;
					}

					$intClassWidth = 0;
					$intClassMappingWith = 0;
					$arrClassLoader = array();
					$arrNamespaces = array();
					$arrClassMapping = array();

					$arrClassFolders = array();
					$arrFiles = scan(TL_ROOT . '/system/modules/' . $strModule);

					// Support subfolders
					foreach ($arrFiles as $strFolder)
					{
						if ($strFolder != 'config' && $strFolder != 'dca' && $strFolder != 'html' && $strFolder != 'languages' && $strFolder != 'templates')
						{
							if (is_dir(TL_ROOT . '/system/modules/' . $strModule . '/' . $strFolder))
							{
								$files = scan(TL_ROOT . '/system/modules/' . $strModule . '/' . $strFolder);
								$files = array_map(function($val) use ($strFolder) { return $strFolder . '/' . $val; }, $files);
								$arrFiles = array_merge($arrFiles, $files);

								$files = array_filter($files, function($val) use ($strModule) {
									return is_dir(TL_ROOT . '/system/modules/' . $strModule . '/' . $val);
								});
								$arrClassFolders = array_merge($arrClassFolders, $files);
							}
						}
					}

					// Scan with PSR-0 for classes
					foreach ($arrClassFolders as $strClassFolder)
					{
						$this->scanPSR0($strModule, $strClassFolder, basename($strClassFolder), $intClassWidth, $intClassMappingWith, $arrClassLoader, $arrClassMapping, $arrCompat);
					}

					// Scan for classes
					foreach ($arrFiles as $strFile)
					{
						if (strrchr($strFile, '.') != '.php')
						{
							continue;
						}

						// Read the first 1200 characters of the file (should include the namespace tag)
						$fh = fopen(TL_ROOT . '/system/modules/' . $strModule . '/' . $strFile, 'rb');
						$strBuffer = fread($fh, 1200);
						fclose($fh);

						if (strpos($strBuffer, 'namespace') === false)
						{
							$strNamespace = '';
						}
						else
						{
							$strNamespace = preg_replace('/^.*namespace ([^;]+).*$/s', '$1', $strBuffer);
						}

						$strPSR0Namespace = str_replace('/', '\\', dirname($strModule . '/' . $strFile));

						// Skip class that follow the PSR-0 naming
						if ($strPSR0Namespace == $strNamespace)
						{
							$strRuntimeNamespace = 'Runtime\\' . $strNamespace;
							$strClassName = basename($strFile, '.php');
							$strClass = $strNamespace . '\\' . $strClassName;
							$strRuntimeClass = $strRuntimeNamespace . '\\' . $strClassName;

							// Check file for class mappings
							$strRuntimeClass = $this->checkClassMapping($strModule, $strFile, $strClassName, $strClass, $strRuntimeClass, $arrClassMapping, $intClassMappingWith);

							$arrCompat[$strModule][$strRuntimeClass] = $strClass;
							continue;
						}
						else if ($strNamespace)
						{
							if ($strNamespace != 'Contao')
							{
								$arrNamespaces[$strNamespace] = $strNamespace;
							}

							$strNamespace .= '\\';

							$strClass = basename($strFile, '.php');
							$arrCompat[$strModule][$strClass] = $strNamespace . $strClass;
						}

						$strKey = $strNamespace . basename($strFile, '.php');
						$arrClassLoader[$strKey] = 'system/modules/' . $strModule . '/' . $strFile;
						$intClassWidth = max(strlen($strKey), $intClassWidth);
					}

					$intTplWidth = 0;
					$arrTplLoader = array();

					// Scan for templates
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

					// Neither classes, templates nor class mappings found
					if (empty($arrClassLoader) && empty($arrTplLoader) && empty($arrClassMapping))
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
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

EOT
					);

					// Namespaces
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

					// Classes mapping
					$arrClassMapping = array_unique($arrClassMapping);

					if (!empty($arrClassMapping))
					{
						$objFile->append(
<<<EOT


/**
 * Register class mapping
 */
ClassLoader::addClassMappings(array
(
EOT
						);

						foreach ($arrClassMapping as $strOriginClass => $strTargetClass)
						{
							$strOriginClass = "'" . $strOriginClass . "'";
							$objFile->append("\t" . str_pad($strOriginClass, $intClassMappingWith+2) . " => '" . $strTargetClass . "',");
						}

						$objFile->append('));');
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
							if ($strGroup === null)
							{
								$strGroup = dirname($strPath);
								$objFile->append("\t// " . ucfirst(basename($strGroup)));
							}
							elseif (dirname($strPath) != $strGroup)
							{
								$strGroup = dirname($strPath);
								$objFile->append("\n\t// " . ucfirst(basename($strGroup)));
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
					\Message::addConfirmation('Module "' . $strModule . '" has been merged');
				}
			}

			// IDE compatibility
			if (\Input::post('ide_compat'))
			{
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
 * @link    http://www.contao.org
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

				$arrLibrary = array();

				// library/Contao
				foreach (scan(TL_ROOT . '/system/library/Contao') as $strFile)
				{
					if (strncmp($strFile, '.', 1) === 0)
					{
						continue;
					}

					if (is_file(TL_ROOT . '/system/library/Contao/' . $strFile))
					{
						$strClass = basename($strFile, '.php');
						$arrLibrary[$strClass] = 'Contao\\' . $strClass;
					}
					elseif ($strFile != 'Database')
					{
						foreach (scan(TL_ROOT . '/system/library/Contao/' . $strFile) as $strSubfile)
						{
							if (is_file(TL_ROOT . '/system/library/Contao/' . $strFile . '/' . $strSubfile))
							{
								$strClass = basename($strFile, '.php') . '_' . basename($strSubfile, '.php');
								$arrLibrary[$strClass] = 'Contao\\' . $strClass;
							}
						}
					}
				}

				// library/Contao/Database
				foreach (scan(TL_ROOT . '/system/library/Contao/Database') as $strFile)
				{
					if (strncmp($strFile, '.', 1) === 0)
					{
						continue;
					}

					if (is_file(TL_ROOT . '/system/library/Contao/Database/' . $strFile))
					{
						$strClass = 'Database_' . basename($strFile, '.php');
						$arrLibrary[$strClass] = 'Contao\\' . $strClass;
					}
					else
					{
						foreach (scan(TL_ROOT . '/system/library/Contao/Database/' . $strFile) as $strSubfile)
						{
							if (is_file(TL_ROOT . '/system/library/Contao/Database/' . $strFile . '/' . $strSubfile))
							{
								$strClass = 'Database_' . basename($strFile, '.php') . '_' . basename($strSubfile, '.php');
								$arrLibrary[$strClass] = 'Contao\\' . $strClass;
							}
						}
					}
				}

				array_unshift($arrCompat, $arrLibrary);
				$arrIsAbstract = array('Database', 'Database_Statement', 'Database_Result', 'Files', 'User', 'Widget', 'BackendModule', 'Events', 'ContentElement', 'Hybrid', 'Module', 'ModuleNews');

				// Add the classes
				foreach ($arrCompat as $strModule=>$arrClasses)
				{
					$objFile->append("\n// " . ($strModule ?: 'library'));

					foreach ($arrClasses as $strClass => $strBase)
					{
						$objFile->append((in_array($strClass, $arrIsAbstract) ? 'abstract ' : '') . "class $strClass extends $strBase {}");
					}
				}

				$objFile->close();
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
		$this->Template->headline = $GLOBALS['TL_LANG']['tl_merge']['headline'];
		$this->Template->action = ampersand(\Environment::get('request'));
		$this->Template->available = $GLOBALS['TL_LANG']['tl_merge']['available'];
		$this->Template->selectAll = $GLOBALS['TL_LANG']['MSC']['selectAll'];
		$this->Template->override = $GLOBALS['TL_LANG']['tl_merge']['override'];
		$this->Template->explain = $GLOBALS['TL_LANG']['tl_merge']['explain'];
		$this->Template->submitButton = specialchars($GLOBALS['TL_LANG']['MSC']['continue']);
		$this->Template->options = $GLOBALS['TL_LANG']['tl_merge']['options'];
		$this->Template->ide_compat = $GLOBALS['TL_LANG']['tl_merge']['ide_compat'];
	}


	/**
	 * Search a directory for PSR-0 compliant classes.
	 *
	 * @param string $strModule
	 * @param string $strFolder
	 * @param string $strNamespace
	 * @param int $intClassWidth
	 * @param array $arrClassLoader
	 * @param array $arrNamespaceMaps
	 * @param array $arrCompat
	 */
	protected function scanPSR0($strModule, $strFolder, $strNamespace, &$intClassWidth, &$intClassMappingWith, &$arrClassLoader, &$arrClassMapping, &$arrCompat)
	{
		$arrFiles = scan(TL_ROOT . '/system/modules/' . $strModule . '/' . $strFolder);

		$arrFiles = array_map(function($val) use ($strFolder, $strNamespace, &$arrNamespaceMaps) { return $strFolder . '/' . $val; }, $arrFiles);

		// Scan for classes
		foreach ($arrFiles as $strFile)
		{
			if (strrchr($strFile, '.') != '.php')
			{
				if (is_dir(TL_ROOT . '/system/modules/' . $strModule . '/' . $strFile)) {
					$this->scanPSR0($strModule, $strFile, $strNamespace . '\\' . basename($strFile), $intClassWidth, $intClassMappingWith, $arrClassLoader, $arrClassMapping, $arrCompat);
				}

				continue;
			}

			$strRuntimeNamespace = 'Runtime\\' . $strNamespace;
			$strClassName = basename($strFile, '.php');
			$strClass = $strNamespace . '\\' . $strClassName;
			$strRuntimeClass = $strRuntimeNamespace . '\\' . $strClassName;

			// Check file for class mappings
			$this->checkClassMapping($strModule, $strFile, $strClassName, $strClass, $strRuntimeClass, $arrClassMapping, $intClassMappingWith);

			// Add class to compat file
			$arrCompat[$strModule][$strRuntimeNamespace][basename($strFile, '.php')] = $strClass;
		}
	}

	/**
	 * Check a class file for mapping annotations @Overwrite
	 *
	 * @param string $strModule Name of the module.
	 * @param string $strFile Name of the file, including folders within the module directory.
	 * @param string $strClassName Name of the class, e.a. Example\My\Class.
	 * @param string $strRuntimeClass Name of the Runtime\ class, e.a. Runtime\Example\My\Class.
	 * @param array $arrClassMapping Array containing the class mappings.
	 * @param int $intClassMappingWith Max string size in class mappings array.
	 * @return string The target runtime class.
	 */
	protected function checkClassMapping($strModule, $strFile, $strClassName, $strClass, $strRuntimeClass, &$arrClassMapping, &$intClassMappingWith)
	{
		// Read until class declaration found
		$fh = fopen(TL_ROOT . '/system/modules/' . $strModule . '/' . $strFile, 'rb');
		$strBuffer = '';
		do {
			$strBuffer .= fread($fh, 1200);
		} while (strpos($strBuffer, 'class ' . $strClassName) === false && !feof($fh));
		fclose($fh);

		// search for class documentation
		if (preg_match('#/\*\*(.*)\*/[\s\n\r]*class ' . preg_quote($strClassName) . '#sU', $strBuffer, $arrMatch)) {
			$strClassComment = $arrMatch[1];

			if (preg_match('#@Overwrite ([a-zA-Z0-0_\\\\]+)#', $strClassComment, $arrMatch))
			{
				$strRuntimeClass = $arrMatch[1];

				while ($strRuntimeClass[0] == '\\')
				{
					$strRuntimeClass = substr($strRuntimeClass, 1);
				}
				if (!preg_match('#^Runtime\\\\#', $strRuntimeClass))
				{
					$strRuntimeClass = 'Runtime\\' . $strRuntimeClass;
				}
			}
		}

		$arrClassMapping[$strRuntimeClass] = $strClass;
		$intClassMappingWith = max(strlen($strRuntimeClass), $intClassMappingWith);

		return $strRuntimeClass;
	}
}
