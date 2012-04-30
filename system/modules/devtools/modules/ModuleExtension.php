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
use \BackendModule, \BackendTemplate, \Environment, \File, \Input;


/**
 * Class ModuleExtension
 *
 * Back end module "extension".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
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
	 * @return void
	 */
	protected function compile()
	{
		// Create files
		if (Input::post('FORM_SUBMIT') == 'tl_extension')
		{
			$objModule = $this->Database->prepare("SELECT * FROM tl_extension WHERE id=?")
							  ->limit(1)
							  ->execute($this->objDc->id);

			if ($objModule->numRows < 1)
			{
				return;
			}

			// .htaccess
			$tplHtaccess = new BackendTemplate('dev_htaccess');
			$objHtaccess = new File('system/modules/' . $objModule->folder . '/.htaccess');
			$objHtaccess->write($tplHtaccess->parse());
			$objHtaccess->close();

			// config/config.php
			$tplConfig = $this->newTemplate('dev_config', $objModule);
			$objConfig = new File('system/modules/' . $objModule->folder . '/config/config.php');
			$objConfig->write($tplConfig->parse());
			$objConfig->close();

			// Back end
			if ($objModule->addBeMod)
			{
				$arrClasses = trimsplit(',', $objModule->beClasses);

				// Classes
				foreach ($arrClasses as $strClass)
				{
					$tplClass = $this->newTemplate('dev_beClass', $objModule);
					$tplClass->class = $strClass;

					$objClass = new File('system/modules/' . $objModule->folder . '/' . $this->guessSubfolder($strClass) . '/' . $strClass . '.php');
					$objClass->write($tplClass->parse());
					$objClass->close();
				}

				$arrTables = trimsplit(',', $objModule->beTables);

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
					$tplTemplate = $this->newTemplate('dev_beTemplate', $objModule);
					$objTemplate = new File('system/modules/' . $objModule->folder . '/templates/' . $strTemplate . '.html5');
					$objTemplate->write($tplTemplate->parse());
					$objTemplate->close();
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
					$tplClass->extends = $this->guessParentClass($strClass);

					$objClass = new File('system/modules/' . $objModule->folder . '/' . $this->guessSubfolder($strClass) . '/' . $strClass . '.php');
					$objClass->write($tplClass->parse());
					$objClass->close();
				}

				$arrTables = trimsplit(',', $objModule->feTables);

				// Models
				foreach ($arrTables as $strTable)
				{
					$strModel = $this->getModelClassFromTable($strTable);

					$tplTable = $this->newTemplate('dev_model', $objModule);
					$tplTable->table = $strTable;
					$tplTable->class = $strModel;

					$objTable = new File('system/modules/' . $objModule->folder . '/models/' . $strModel . 'Model.php');
					$objTable->write($tplTable->parse());
					$objTable->close();
				}

				$arrTemplates = trimsplit(',', $objModule->feTemplates);

				// Templates
				foreach ($arrTemplates as $strTemplate)
				{
					$tplTemplate = $this->newTemplate('dev_feTemplate', $objModule);
					$objTemplate = new File('system/modules/' . $objModule->folder . '/templates/' . $strTemplate . '.html5');
					$objTemplate->write($tplTemplate->parse());
					$objTemplate->close();
					$objTemplate->copyTo('system/modules/' . $objModule->folder . '/templates/' . $strTemplate . '.xhtml');
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

			$this->addConfirmationMessage($GLOBALS['TL_LANG']['tl_extension']['confirm']);
			$this->reload();
		}

		$this->Template->base = Environment::get('base');
		$this->Template->href = $this->getReferer(true);
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']);
		$this->Template->action = ampersand(Environment::get('request'));
		$this->Template->selectAll = $GLOBALS['TL_LANG']['MSC']['selectAll'];
		$this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];
		$this->Template->message = $this->getMessages();
		$this->Template->submit = specialchars($GLOBALS['TL_LANG']['tl_extension']['make'][0]);
		$this->Template->headline = sprintf($GLOBALS['TL_LANG']['tl_extension']['headline'], Input::get('id'));
		$this->Template->explain = $GLOBALS['TL_LANG']['tl_extension']['make'][1];
		$this->Template->label = $GLOBALS['TL_LANG']['tl_extension']['label'];
	}


	/**
	 * Return a new template object
	 * @param string
	 * @param \Database_Result
	 * @return \BackendTemplate
	 */
	protected function newTemplate($strTemplate, Database_Result $objModule)
	{
		$objTemplate = new BackendTemplate($strTemplate);
		$objTemplate->folder = $objModule->folder;
		$objTemplate->author = str_replace(array('[', ']'), array('<', '>'), $objModule->author);
		$objTemplate->copyright = $objModule->copyright;
		$objTemplate->package = $objModule->package;
		$objTemplate->license = $objModule->license;

		return $objTemplate;
	}


	/**
	 * Try to guess the subfolder of a class depending on its name
	 * @param string
	 * @return string
	 */
	protected function guessSubfolder($strClassName)
	{
		if (strncmp($strClassName, 'DC_', 3) === 0)
		{
			return 'drivers';
		}
		elseif (strncmp($strClassName, 'Content', 7) === 0)
		{
			return 'elements';
		}
		elseif (strncmp($strClassName, 'Form', 4) === 0)
		{
			return 'forms';
		}
		elseif (strncmp($strClassName, 'Module', 6) === 0)
		{
			return 'modules';
		}
		elseif (strncmp($strClassName, 'Page', 4) === 0)
		{
			return 'pages';
		}
		else
		{
			return 'classes';
		}
	}


	/**
	 * Try to guess the parent class of a class depending on its name
	 * @param string
	 * @return string
	 */
	protected function guessParentClass($strClassName)
	{
		if (strncmp($strClassName, 'Content', 7) === 0)
		{
			return 'ContentElement';
		}
		elseif (strncmp($strClassName, 'Form', 4) === 0)
		{
			return 'Widget';
		}
		elseif (strncmp($strClassName, 'Page', 4) === 0)
		{
			return 'Frontend';
		}
		else
		{
			return 'Module';
		}
	}
}
