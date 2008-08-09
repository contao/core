<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * @package    Development
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleExtension
 *
 * Back end module "extension".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleExtension extends BackendModule
{

	/**
	 * Data container
	 * @var object
	 */
	protected $objDc;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'dev_extension';


	/**
	 * Generate the module
	 * @return string
	 */
	public function generate()
	{
		$this->objDc = func_get_arg(0);
		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		// Create files
		if ($this->Input->post('FORM_SUBMIT') == 'tl_extension')
		{
			$objModule = $this->Database->prepare("SELECT * FROM tl_extension WHERE id=?")
							  ->limit(1)
							  ->execute($this->objDc->id);

			if ($objModule->numRows < 1)
			{
				return;
			}

			$tplHtaccess = new Template('dev_htaccess');

			// config/.htaccess
			$objHtaccess = new File('system/modules/' . $objModule->folder . '/config/.htaccess');
			$objHtaccess->write($tplHtaccess->parse());
			$objHtaccess->close();

			$tplConfig = $this->newTemplate('dev_config', $objModule);

			// config/config.php
			$objConfig = new File('system/modules/' . $objModule->folder . '/config/config.php');
			$objConfig->write($tplConfig->parse());
			$objConfig->close();

			$arrTables = trimsplit(',', $objModule->beTables);

			// config/database.php and dca/.htaccess
			if (strlen($objModule->beTables) || strlen($objModule->feTables))
			{
				$objHtaccess = new File('system/modules/' . $objModule->folder . '/dca/.htaccess');
				$objHtaccess->write($tplHtaccess->parse());
				$objHtaccess->close();

				$tplDatabase = new Template('dev_database');
				$tplDatabase->tables = array_merge($arrTables, trimsplit(',', $objModule->feTables));

				$objDatabase = new File('system/modules/' . $objModule->folder . '/config/database.sql');
				$objDatabase->write($tplDatabase->parse());
				$objDatabase->close();
			}

			// templates/.htaccess
			if (strlen($objModule->beTemplates) || strlen($objModule->feTemplates))
			{
				$objHtaccess = new File('system/modules/' . $objModule->folder . '/templates/.htaccess');
				$objHtaccess->write($tplHtaccess->parse());
				$objHtaccess->close();
			}

			// Back end
			if ($objModule->addBeMod)
			{
				$arrClasses = trimsplit(',', $objModule->beClasses);

				// Classes
				foreach ($arrClasses as $strClass)
				{
					$tplClass = $this->newTemplate('dev_beClass', $objModule);
					$tplClass->class = $strClass;

					$objClass = new File('system/modules/' . $objModule->folder . '/' . $strClass . '.php');
					$objClass->write($tplClass->parse());
					$objClass->close();
				}

				// Data container files
				foreach ($arrTables as $strTable)
				{
					$tplTable = $this->newTemplate('dev_dca', $objModule);
					$tplTable->table = $strTable;

					$objTable = new File('system/modules/' . $objModule->folder . '/dca/' . $strTable . '.php');
					$objTable->write($tplTable->parse());
					$objTable->close();
				}

				$arrTemplates = trimsplit(',', $objModule->beTemplates);

				// Templates
				foreach ($arrTemplates as $strTemplate)
				{
					new File('system/modules/' . $objModule->folder . '/templates/' . $strTemplate . '.tpl');
				}
			}

			// Front end
			if ($objModule->addFeMod)
			{
				$arrClasses = trimsplit(',', $objModule->feClasses);

				// Classes
				foreach ($arrClasses as $strClass)
				{
					$tplClass = $this->newTemplate('dev_feClass', $objModule);
					$tplClass->class = $strClass;

					$objClass = new File('system/modules/' . $objModule->folder . '/' . $strClass . '.php');
					$objClass->write($tplClass->parse());
					$objClass->close();
				}

				$arrTemplates = trimsplit(',', $objModule->feTemplates);

				// Templates
				foreach ($arrTemplates as $strTemplate)
				{
					new File('system/modules/' . $objModule->folder . '/templates/' . $strTemplate . '.tpl');
				}
			}

			// Language packs
			if ($objModule->addLanguage)
			{
				$arrLanguages = trimsplit(',', $objModule->languages);

				foreach ($arrLanguages as $strLanguage)
				{
					$objHtaccess = new File('system/modules/' . $objModule->folder . '/languages/' . $strLanguage . '/.htaccess');
					$objHtaccess->write($tplHtaccess->parse());
					$objHtaccess->close();

					// languages/xx/default.php
					$tplLanguage = $this->newTemplate('dev_default', $objModule);
					$tplLanguage->language = $strLanguage;

					$objLanguage = new File('system/modules/' . $objModule->folder . '/languages/' . $strLanguage . '/default.php');
					$objLanguage->write($tplLanguage->parse());
					$objLanguage->close();

					// languages/xx/modules.php
					$tplLanguage = $this->newTemplate('dev_modules', $objModule);
					$tplLanguage->language = $strLanguage;

					$objLanguage = new File('system/modules/' . $objModule->folder . '/languages/' . $strLanguage . '/modules.php');
					$objLanguage->write($tplLanguage->parse());
					$objLanguage->close();

					foreach ($arrTables as $strTable)
					{
						$tplLanguage = $this->newTemplate('dev_table', $objModule);

						$tplLanguage->language = $strLanguage;
						$tplLanguage->table = $strTable;

						$objLanguage = new File('system/modules/' . $objModule->folder . '/languages/' . $strLanguage . '/' . $strTable . '.php');
						$objLanguage->write($tplLanguage->parse());
						$objLanguage->close();
					}
				}
			}

			$_SESSION['TL_CONFIRM'][] = $GLOBALS['TL_LANG']['tl_extension']['confirm'];
			$this->reload();
		}

		$this->Template->base = $this->Environment->base;
		$this->Template->href = $this->getReferer(ENCODE_AMPERSANDS);
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['backBT']);
		$this->Template->action = ampersand($this->Environment->request, true);
		$this->Template->selectAll = $GLOBALS['TL_LANG']['MSC']['selectAll'];
		$this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];

		$this->Template->message = $this->getMessages();
		$this->Template->submit = specialchars($GLOBALS['TL_LANG']['tl_extension']['make'][0]);
		$this->Template->headline = sprintf($GLOBALS['TL_LANG']['tl_extension']['headline'], $this->Input->get('id'));
		$this->Template->explain = $GLOBALS['TL_LANG']['tl_extension']['make'][1];
		$this->Template->label = $GLOBALS['TL_LANG']['tl_extension']['label'];
	}


	/**
	 * Return a new template object
	 * @param string
	 * @param object
	 * @return object
	 */
	private function newTemplate($strTemplate, Database_Result $objModule)
	{
		$objTemplate = new Template($strTemplate);

		$objTemplate->folder = $objModule->folder;
		$objTemplate->author = str_replace(array('[', ']'), array('<', '>'), $objModule->author);
		$objTemplate->copyright = $objModule->copyright;
		$objTemplate->package = $objModule->package;
		$objTemplate->license = $objModule->license;

		return $objTemplate;
	}
}

?>