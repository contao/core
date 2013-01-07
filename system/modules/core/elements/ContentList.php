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
 * Class ContentList
 *
 * Front end content element "list".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ContentList extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_list';


	/**
	 * Generate the content element
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
