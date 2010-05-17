<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ContentTeaser
 *
 * Front end content element "teaser".
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Controller
 */
class ContentTeaser extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_teaser';


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		$objArticle = $this->Database->prepare("SELECT p.id AS id, p.alias AS alias, a.id AS aid, a.title AS title, a.alias AS aalias, a.teaser AS teaser, a.inColumn AS inColumn FROM tl_article a, tl_page p WHERE a.id=? AND a.pid=p.id")
									 ->limit(1)
									 ->execute($this->article);

		if ($objArticle->numRows < 1)
		{
			return;
		}

		$link = '/articles/';

		if ($objArticle->inColumn != 'main')
		{
			$link .= $objArticle->inColumn . ':';
		}

		$link .= (strlen($objArticle->aalias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objArticle->aalias : $objArticle->aid;
		$this->Template->href = $this->generateFrontendUrl($objArticle->row(), $link);

		$this->Template->headline = $objArticle->title;
		$this->Template->text = $objArticle->teaser;
		$this->Template->readMore = specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $objArticle->title));
		$this->Template->more = $GLOBALS['TL_LANG']['MSC']['more'];
	}
}

?>