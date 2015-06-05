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
 * Front end content element "teaser".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 * Parent page object
	 * @var object
	 */
	protected $objParent;


	/**
	 * Check whether the target page and the article are published
	 *
	 * @return string
	 */
	public function generate()
	{
		$objArticle = \ArticleModel::findPublishedById($this->article);

		if ($objArticle === null)
		{
			return '';
		}

		// Use findPublished() instead of getRelated()
		$objParent = \PageModel::findPublishedById($objArticle->pid);

		if ($objParent === null)
		{
			return '';
		}

		$this->objArticle = $objArticle;
		$this->objParent = $objParent;

		return parent::generate();
	}


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		/** @var \PageModel $objPage */
		global $objPage;

		$link = '/articles/';
		$objArticle = $this->objArticle;

		if ($objArticle->inColumn != 'main')
		{
			$link .= $objArticle->inColumn . ':';
		}

		$link .= ($objArticle->alias != '' && !\Config::get('disableAlias')) ? $objArticle->alias : $objArticle->id;
		$this->Template->href = $this->generateFrontendUrl($this->objParent->row(), $link);

		// Clean the RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$this->Template->text = \String::toXhtml($objArticle->teaser);
		}
		else
		{
			$this->Template->text = \String::toHtml5($objArticle->teaser);
		}

		$this->Template->headline = $objArticle->title;
		$this->Template->readMore = specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $objArticle->title));
		$this->Template->more = $GLOBALS['TL_LANG']['MSC']['more'];
	}
}
