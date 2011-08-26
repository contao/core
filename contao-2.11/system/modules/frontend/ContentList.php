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
 * Class ContentList
 *
 * Front end content element "list".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ContentList extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_list';


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		$arrItems = array();
		$items = deserialize($this->listitems);
		$limit = count($items) - 1;

		for ($i=0; $i<count($items); $i++)
		{
			$arrItems[] = array
			(
				'class' => (($i == 0) ? 'first' : (($i == $limit) ? 'last' : '')),
				'content' => $items[$i]
			);
		}

		$this->Template->items = $arrItems;
		$this->Template->tag = ($this->listtype == 'ordered') ? 'ol' : 'ul';
	}
}

?>