<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ContentAlias
 *
 * Front end content element "alias".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ContentAlias extends ContentElement
{

	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		$objElement = $this->Database->prepare("SELECT * FROM tl_content WHERE id=?")
									 ->limit(1)
									 ->execute($this->cteAlias);

		if ($objElement->numRows < 1)
		{
			return '';
		}

		$strClass = $this->findContentElement($objElement->type);

		if (!$this->classFileExists($strClass))
		{
			return '';
		}

		$objElement->id = $this->id;
		$objElement->typePrefix = 'ce_';

		$objElement = new $strClass($objElement);

		// Overwrite spacing and CSS ID
		$objElement->space = $this->space;
		$objElement->cssID = $this->cssID;

		return $objElement->generate();
	}


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		return;
	}
}

?>