<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ContentModule
 *
 * Front end content element "module".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ContentModule extends \ContentElement
{

	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		if (!BE_USER_LOGGED_IN && ($this->invisible || ($this->start > 0 && $this->start > time()) || ($this->stop > 0 && $this->stop < time())))
		{
			return '';
		}

		$objModule = \ModuleModel::findByPk($this->module);

		if ($objModule === null)
		{
			return '';
		}

		$strClass = $this->findFrontendModule($objModule->type);

		if (!class_exists($strClass))
		{
			return '';
		}

		$objModule->typePrefix = 'ce_';
		$objModule = new $strClass($objModule);

		// Overwrite spacing and CSS ID
		$objModule->space = $this->space;
		$objModule->cssID = $this->cssID;

		return $objModule->generate();
	}


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		return;
	}
}
