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
 * Back end module "extension".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleExtension extends \BackendModule
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
	 *
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
		if (\Input::post('FORM_SUBMIT') == 'tl_extension')
		{
			$objModule = $this->Database->prepare("SELECT * FROM tl_extension WHERE id=?")
							  ->limit(1)
							  ->execute($this->objDc->id);

			if ($objModule->numRows < 1)
			{
				return;
			}

			// Disable the debug mode (see #7068)
			\Config::set('debugMode', false);

			// config/config.php
			$tplConfig = $this->newTemplate('dev_config', $objModule);
			\File::putContent('system/modules/' . $objModule->folder . '/config/config.php', $tplConfig->parse());

			// config/autoload.ini
			$tplConfig = $this->newTemplate('dev_ini', $objModule);
			\File::putContent('system/modules/' . $objModule->folder . '/config/autoload.ini', $tplConfig->parse());

			// Back end
			if ($objModule->addBeMod)
			{
				$arrClasses = array_filter(trimsplit(',', $objModule->beClasses));

				// Classes
				foreach ($arrClasses as $strClass)
				{
					$tplClass = $this->newTemplate('dev_beClass', $objModule);
					$tplClass->class = $strClass;

					\File::putContent('system/modules/' . $objModule->folder . '/' . $this->guessSubfolder($strClass) . '/' . $strClass . '.php', $tplClass->parse());
				}

				$arrTables = array_filter(trimsplit(',', $objModule->beTables));

				// Back end data container files
				foreach ($arrTables as $strTable)
				{
					$tplTable = $this->newTemplate('dev_dca', $objModule);
					$tplTable->table = $strTable;

					\File::putContent('system/modules/' . $objModule->folder . '/dca/' . $strTable . '.php', $tplTable->parse());
				}

				$arrTemplates = array_filter(trimsplit(',', $objModule->beTemplates));

				// Templates
				foreach ($arrTemplates as $strTemplate)
				{
					$tplTemplate = $this->newTemplate('dev_beTemplate', $objModule);
					\File::putContent('system/modules/' . $objModule->folder . '/templates/' . $strTemplate . '.html5', $tplTemplate->parse());
				}
			}

			$arrTables = array();

			// Front end
			if ($objModule->addFeMod)
			{
				$arrClasses = array_filter(trimsplit(',', $objModule->feClasses));

				// Classes
				foreach ($arrClasses as $strClass)
				{
					$tplClass = $this->newTemplate('dev_feClass', $objModule);
					$tplClass->class = $strClass;
					$tplClass->extends = $this->guessParentClass($strClass);

					\File::putContent('system/modules/' . $objModule->folder . '/' . $this->guessSubfolder($strClass) . '/' . $strClass . '.php', $tplClass->parse());
				}

				$arrTables = array_filter(trimsplit(',', $objModule->feTables));

				// Front end data container files
				foreach ($arrTables as $strTable)
				{
					$tplTable = $this->newTemplate('dev_feDca', $objModule);
					$tplTable->table = $strTable;

					\File::putContent('system/modules/' . $objModule->folder . '/dca/' . $strTable . '.php', $tplTable->parse());
				}

				// Models
				foreach ($arrTables as $strTable)
				{
					$strModel = \Model::getClassFromTable($strTable);

					$tplTable = $this->newTemplate('dev_model', $objModule);
					$tplTable->table = $strTable;
					$tplTable->class = $strModel;

					\File::putContent('system/modules/' . $objModule->folder . '/models/' . $strModel . '.php', $tplTable->parse());
				}

				$arrTemplates = array_filter(trimsplit(',', $objModule->feTemplates));

				// Templates
				foreach ($arrTemplates as $strTemplate)
				{
					$tplTemplate = $this->newTemplate('dev_feTemplate', $objModule);
					$objTemplate = new \File('system/modules/' . $objModule->folder . '/templates/' . $strTemplate . '.html5', true);
					$objTemplate->write($tplTemplate->parse());
					$objTemplate->close();
					$objTemplate->copyTo('system/modules/' . $objModule->folder . '/templates/' . $strTemplate . '.xhtml');
				}
			}

			// Language packs
			if ($objModule->addLanguage)
			{
				$arrLanguages = array_filter(trimsplit(',', $objModule->languages));

				foreach ($arrLanguages as $strLanguage)
				{
					// languages/xx/default.php
					$tplLanguage = $this->newTemplate('dev_default', $objModule);
					$tplLanguage->language = $strLanguage;

					\File::putContent('system/modules/' . $objModule->folder . '/languages/' . $strLanguage . '/default.php', $tplLanguage->parse());

					// languages/xx/modules.php
					$tplLanguage = $this->newTemplate('dev_modules', $objModule);
					$tplLanguage->language = $strLanguage;

					\File::putContent('system/modules/' . $objModule->folder . '/languages/' . $strLanguage . '/modules.php', $tplLanguage->parse());

					// languages/xx/<table>.php
					foreach ($arrTables as $strTable)
					{
						$tplLanguage = $this->newTemplate('dev_table', $objModule);
						$tplLanguage->language = $strLanguage;
						$tplLanguage->table = $strTable;

						\File::putContent('system/modules/' . $objModule->folder . '/languages/' . $strLanguage . '/' . $strTable . '.php', $tplLanguage->parse());
					}
				}
			}

			// Public folder
			$tplConfig = $this->newTemplate('dev_htaccess', $objModule);
			\File::putContent('system/modules/' . $objModule->folder . '/assets/.htaccess', $tplConfig->parse());

			// Confirm and reload
			\Message::addConfirmation($GLOBALS['TL_LANG']['tl_extension']['confirm']);
			$this->reload();
		}

		$this->Template->base = \Environment::get('base');
		$this->Template->href = $this->getReferer(true);
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']);
		$this->Template->action = ampersand(\Environment::get('request'));
		$this->Template->selectAll = $GLOBALS['TL_LANG']['MSC']['selectAll'];
		$this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];
		$this->Template->message = \Message::generate();
		$this->Template->submit = specialchars($GLOBALS['TL_LANG']['tl_extension']['make'][0]);
		$this->Template->headline = sprintf($GLOBALS['TL_LANG']['tl_extension']['headline'], \Input::get('id'));
		$this->Template->explain = $GLOBALS['TL_LANG']['tl_extension']['make'][1];
		$this->Template->label = $GLOBALS['TL_LANG']['tl_extension']['label'];
	}


	/**
	 * Return a new template object
	 * @param string $strTemplate
	 * @param \Database\Result|object $objModule
	 *
	 * @return \BackendTemplate|object
	 */
	protected function newTemplate($strTemplate, \Database\Result $objModule)
	{
		/** @var \BackendTemplate|object $objTemplate */
		$objTemplate = new \BackendTemplate($strTemplate);

		$objTemplate->folder = $objModule->folder;
		$objTemplate->author = str_replace(array('[', ']'), array('<', '>'), $objModule->author);
		$objTemplate->copyright = $objModule->copyright;
		$objTemplate->package = $objModule->package;
		$objTemplate->license = $objModule->license;

		return $objTemplate;
	}


	/**
	 * Try to guess the subfolder of a class depending on its name
	 *
	 * @param string $strClassName
	 *
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
	 *
	 * @param string $strClassName
	 *
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
