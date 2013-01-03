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
 * Class ModuleArticle
 *
 * Provides methodes to handle articles.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ModuleArticle extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_article';

	/**
	 * No markup
	 * @var boolean
	 */
	protected $blnNoMarkup = false;


	/**
	 * Check whether the article is published
	 * @param boolean
	 * @return string
	 */
	public function generate($blnNoMarkup=false)
	{
		$this->type = 'article';
		$this->blnNoMarkup = $blnNoMarkup;

		if (!BE_USER_LOGGED_IN && (!$this->published || ($this->start > 0 && $this->start > time()) || ($this->stop > 0 && $this->stop < time())))
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;

		if ($this->blnNoMarkup)
		{
			$this->Template = new \FrontendTemplate('mod_article_plain');
			$this->Template->setData($this->arrData);
		}

		$alias = $this->alias ?: $this->title;

		if (in_array($alias, array('header', 'container', 'left', 'main', 'right', 'footer')))
		{
			$alias .= '-' . $this->id;
		}

		$alias = standardize($alias);

		// Generate the cssID if it is not set
		if ($this->cssID[0] == '')
		{
			$this->cssID = array($alias, $this->cssID[1]);
		}

		$this->Template->column = $this->inColumn;

		// Add the modification date
		$this->Template->timestamp = $this->tstamp;
		$this->Template->date = $this->parseDate($objPage->datimFormat, $this->tstamp);

		// Clean the RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$this->teaser = \String::toXhtml($this->teaser);
		}
		else
		{
			$this->teaser = \String::toHtml5($this->teaser);
		}

		// Show the teaser only
		if ($this->multiMode && $this->showTeaser)
		{
			$this->Template = new \FrontendTemplate('mod_article_teaser');
			$this->Template->setData($this->arrData);

			$this->cssID = array($alias, '');
			$arrCss = deserialize($this->teaserCssID);

			// Override the CSS ID and class
			if (is_array($arrCss) && count($arrCss) == 2)
			{
				if ($arrCss[0] == '')
				{
					$arrCss[0] = $alias;
				}

				$this->cssID = $arrCss;
			}

			$article = (!$GLOBALS['TL_CONFIG']['disableAlias'] && $this->alias != '') ? $this->alias : $this->id;
			$href = 'articles=' . (($this->inColumn != 'main') ? $this->inColumn . ':' : '') . $article;

			$this->Template->headline = $this->headline;
			$this->Template->href = $this->addToUrl($href);
			$this->Template->teaser = $this->teaser;
			$this->Template->readMore = specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $this->headline), true);
			$this->Template->more = $GLOBALS['TL_LANG']['MSC']['more'];

			return;
		}

		// Get section and article alias
		list($strSection, $strArticle) = explode(':', \Input::get('articles'));

		if ($strArticle === null)
		{
			$strArticle = $strSection;
		}

		// Overwrite the page title (see #2853 and #4955)
		if (!$this->blnNoMarkup && $strArticle != '' && ($strArticle == $this->id || $strArticle == $this->alias) && $this->title != '')
		{
			$objPage->pageTitle = strip_tags(strip_insert_tags($this->title));
		}

		$this->Template->printable = false;
		$this->Template->backlink = false;

		// Back link
		if (!$this->multiMode && $strArticle != '' && ($strArticle == $this->id || $strArticle == $this->alias))
		{
			$this->Template->back = specialchars($GLOBALS['TL_LANG']['MSC']['goBack']);

			// Remove the "/articles/…" part from the URL
			if ($GLOBALS['TL_CONFIG']['disableAlias'])
			{
				$this->Template->backlink = preg_replace('@&(amp;)?articles=[^&]+@', '', \Environment::get('request'));
			}
			else
			{
				$this->Template->backlink = preg_replace('@/articles/[^/]+@', '', \Environment::get('request')) . $GLOBALS['TL_CONFIG']['urlSuffix'];
			}
		}

		$arrElements = array();
		$objCte = \ContentModel::findPublishedByPidAndTable($this->id, 'tl_article');

		if ($objCte !== null)
		{
			while ($objCte->next())
			{
				$arrElements[] = $this->getContentElement($objCte);
			}
		}

		$this->Template->teaser = $this->teaser;
		$this->Template->elements = $arrElements;

		if ($this->keywords != '')
		{
			$GLOBALS['TL_KEYWORDS'] .= (($GLOBALS['TL_KEYWORDS'] != '') ? ', ' : '') . $this->keywords;
		}

		// Backwards compatibility
		if ($this->printable == 1)
		{
			$this->Template->printable = true;
			$this->Template->pdfButton = true;
		}

		// New structure
		elseif ($this->printable != '')
		{
			$options = deserialize($this->printable);

			if (is_array($options) && !empty($options))
			{
				$this->Template->printable = true;
				$this->Template->printButton = in_array('print', $options);
				$this->Template->pdfButton = in_array('pdf', $options);
				$this->Template->facebookButton = in_array('facebook', $options);
				$this->Template->twitterButton = in_array('twitter', $options);
				$this->Template->gplusButton = in_array('gplus', $options);
			}
		}

		// Add syndication variables
		if ($this->Template->printable)
		{
			$request = $this->getIndexFreeRequest(true);

			$this->Template->print = '#';
			$this->Template->encUrl = rawurlencode(\Environment::get('base') . \Environment::get('request'));
			$this->Template->encTitle = rawurlencode($objPage->pageTitle);
			$this->Template->href = $request . ((strpos($request, '?') !== false) ? '&amp;' : '?') . 'pdf=' . $this->id;

			$this->Template->printTitle = specialchars($GLOBALS['TL_LANG']['MSC']['printPage']);
			$this->Template->pdfTitle = specialchars($GLOBALS['TL_LANG']['MSC']['printAsPdf']);
			$this->Template->facebookTitle = specialchars($GLOBALS['TL_LANG']['MSC']['facebookShare']);
			$this->Template->twitterTitle = specialchars($GLOBALS['TL_LANG']['MSC']['twitterShare']);
			$this->Template->gplusTitle = specialchars($GLOBALS['TL_LANG']['MSC']['gplusShare']);
		}
	}
}
