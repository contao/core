<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Development
 * @license    LGPL
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
 * @package    Controller
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
	 * @return void
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
				$this->addErrorMessage($GLOBALS['TL_LANG']['tl_merge']['emptySelection']);
			}
			else
			{
				$arrCompat = array();

				foreach ($arrModules as $strModule)
				{
					// config/autoload.php exists
					if (!\Input::post('ide_compat') && !\Input::post('override') && file_exists(TL_ROOT . '/system/modules/' . $strModule . '/config/autoload.php'))
					{
						$this->addInfoMessage(sprintf($GLOBALS['TL_LANG']['tl_merge']['autoloadExists'], $strModule));
						continue;
					}

					$intClassWidth = 0;
					$arrClassLoader = array();
					$arrNamespaces = array();

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
							}
						}
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
							$strNamespace = str_replace('\\', '\\\\', $strNamespace);

							if ($strNamespace != 'Contao')
							{
								$arrNamespaces[] = $strNamespace;
							}
							else
							{
								$arrCompat[$strModule][] = basename($strFile, '.php');
							}

							$strNamespace .=  '\\\\';
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

					// Neither classes nor templates found
					if (empty($arrClassLoader) && empty($arrTplLoader))
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
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-$intYear
 * @author     Leo Feyer <http://www.contao.org>
 * @package    $strPackage
 * @license    LGPL
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
					$this->addConfirmationMessage('Module "' . $strModule . '" has been merged');
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
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-$intYear
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Helper
 * @license    LGPL
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
						$arrLibrary[] = basename($strFile, '.php');
					}
					elseif ($strFile != 'Database')
					{
						foreach (scan(TL_ROOT . '/system/library/Contao/' . $strFile) as $strSubfile)
						{
							if (is_file(TL_ROOT . '/system/library/Contao/' . $strFile . '/' . $strSubfile))
							{
								$arrLibrary[] = basename($strFile, '.php') . '_' . basename($strSubfile, '.php');
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
						$arrLibrary[] = 'Database_' . basename($strFile, '.php');
					}
					else
					{
						foreach (scan(TL_ROOT . '/system/library/Contao/Database/' . $strFile) as $strSubfile)
						{
							if (is_file(TL_ROOT . '/system/library/Contao/Database/' . $strFile . '/' . $strSubfile))
							{
								$arrLibrary[] = 'Database_' . basename($strFile, '.php') . '_' . basename($strSubfile, '.php');
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

					foreach ($arrClasses as $strClass)
					{
						$objFile->append((in_array($strClass, $arrIsAbstract) ? 'abstract ' : '') . "class $strClass extends Contao\\$strClass {}");
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

		$this->Template->messages = $this->getMessages();
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
}
