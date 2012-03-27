<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Frontend
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ContentTeaser
 *
 * Front end content element "teaser".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ContentTeaser extends \ContentElement
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
	 * @return string
	 */
	public function generate()
	{
		$objArticle = \ArticleModel::findPublishedById($this->article);

		if ($objArticle === null)
		{
			return '';
		}

		$objPage = $objArticle->getPage();

		if ($objPage === null)
		{
			return '';
		}

		$objArticle->pid = $objPage;
		$this->objArticle = $objArticle;

		return parent::generate();
	}


	/**
	 * Generate the content element
	 * @return void
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

		$link .= ($objArticle->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objArticle->alias : $objArticle->id;
		$this->Template->href = $this->generateFrontendUrl($objArticle->pid->row(), $link);

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
