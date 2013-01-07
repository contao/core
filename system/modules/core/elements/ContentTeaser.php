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
 * Class ContentTeaser
 *
 * Front end content element "teaser".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
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
		global $objPage;

		$link = '/articles/';
		$objArticle = $this->objArticle;

		if ($objArticle->inColumn != 'main')
		{
			$link .= $objArticle->inColumn . ':';
		}

		$link .= ($objArticle->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objArticle->alias : $objArticle->id;
		$this->Template->href = $this->generateFrontendUrl($this->objParent->row(), $link);

		// Clean the RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$objArticle->teaser = \String::toXhtml($objArticle->teaser);
		}
		else
		{
			$objArticle->teaser = \String::toHtml5($objArticle->teaser);
		}

		$this->Template->headline = $objArticle->title;
		$this->Template->text = $objArticle->teaser;
		$this->Template->readMore = specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $objArticle->title));
		$this->Template->more = $GLOBALS['TL_LANG']['MSC']['more'];
	}
}
