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
 * Front end content element "list".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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

		for ($i=0, $c=count($items); $i<$c; $i++)
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
