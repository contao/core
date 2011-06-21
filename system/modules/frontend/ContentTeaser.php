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
 * Class ContentTeaser
 *
 * Front end content element "teaser".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
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
	 * Article object
	 * @var object
	 */
	protected $objArticle;


	/**
	 * Check whether the target page and article are published
	 */
	public function generate()
	{
		$time = time();

		$objArticle = $this->Database->prepare("SELECT p.id AS id, p.alias AS alias, a.id AS aid, a.title AS title, a.alias AS aalias, a.teaser AS teaser, a.inColumn AS inColumn FROM tl_article a, tl_page p WHERE a.id=? AND a.pid=p.id" . (!BE_USER_LOGGED_IN ? " AND (p.start='' OR p.start<$time) AND (p.stop='' OR p.stop>$time) AND p.published=1 AND (a.start='' OR a.start<$time) AND (a.stop='' OR a.stop>$time) AND a.published=1" : ""))
									 ->limit(1)
									 ->execute($this->article);

		if ($objArticle->numRows < 1)
		{
			return '';
		}

		$this->objArticle = $objArticle;
		return parent::generate();
	}


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		global $objPage;
		$this->import('String');

		$link = '/articles/';
		$objArticle = $this->objArticle;

		if ($objArticle->inColumn != 'main')
		{
			$link .= $objArticle->inColumn . ':';
		}

		$link .= (strlen($objArticle->aalias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objArticle->aalias : $objArticle->aid;
		$this->Template->href = $this->generateFrontendUrl($objArticle->row(), $link);

		// Clean the RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$objArticle->teaser = $this->String->toXhtml($objArticle->teaser);
		}
		else
		{
			$objArticle->teaser = $this->String->toHtml5($objArticle->teaser);
		}

		$this->Template->headline = $objArticle->title;
		$this->Template->text = $objArticle->teaser;
		$this->Template->readMore = specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $objArticle->title));
		$this->Template->more = $GLOBALS['TL_LANG']['MSC']['more'];
	}
}

?>