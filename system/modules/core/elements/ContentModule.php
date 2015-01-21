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
 * Front end content element "module".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ContentModule extends \ContentElement
{

	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'FE' && !BE_USER_LOGGED_IN && ($this->invisible || ($this->start > 0 && $this->start > time()) || ($this->stop > 0 && $this->stop < time())))
		{
			return '';
		}

		$objModule = \ModuleModel::findByPk($this->module);

		if ($objModule === null)
		{
			return '';
		}

		$strClass = \Module::findClass($objModule->type);

		if (!class_exists($strClass))
		{
			return '';
		}

		$objModule->typePrefix = 'ce_';
		$objModule = new $strClass($objModule, $this->strColumn);

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
