<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
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
 * Class ContentAlias
 *
 * Front end content element "alias".
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ContentAlias extends \ContentElement
{

	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		$objElement = \ContentModel::findByPk($this->cteAlias);

		if ($objElement === null)
		{
			return '';
		}

		$strClass = static::findClass($objElement->type);

		if (!class_exists($strClass))
		{
			return '';
		}

		$objElement->id = $this->id;
		$objElement->typePrefix = 'ce_';

		$objElement = new $strClass($objElement);
		
		foreach($objElement->arrData as $key => $value)
			if(!in_array($key, array('type','pid')) && $this->$key)
				$objElement->$key = $this->$key;

		// Overwrite spacing and CSS ID
		$objElement->space = $this->space;
		$objElement->cssID = $this->cssID;

		return $objElement->generate();
	}


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		return;
	}
}
