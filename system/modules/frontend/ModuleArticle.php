<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleArticle
 *
 * Provides methodes to handle articles.
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleArticle extends Module
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
	 * Generate module
	 */
	protected function compile()
	{
		if ($this->blnNoMarkup)
		{
			$this->Template = new FrontendTemplate('mod_article_plain');
		}

		// Store raw data
		$this->Template->setData($this->arrData);

		// Generate the cssID if it is not set
		if (!strlen($this->cssID[0]))
		{
			$alias = strlen($this->alias) ? $this->alias : $this->title;

			if (in_array($alias, array('header', 'container', 'left', 'main', 'right', 'footer')))
			{
				$alias .= '-' . $this->id;
			}

			$this->cssID = array(standardize($alias), $this->cssID[1]);
		}

		$this->Template->column = $this->inColumn;

		// Add modification date
		$this->Template->timestamp = $this->tstamp;
		$this->Template->date = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $this->tstamp);
		$this->Template->author = $this->author;

		// Show teaser only
		if ($this->multiMode && $this->showTeaser)
		{
			$this->Template = new FrontendTemplate('mod_article_teaser');

			// Store raw data
			$this->Template->setData($this->arrData);

			$article = (!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($this->alias)) ? $this->alias : $this->id;
			$href = 'articles=' . (($this->inColumn != 'main') ? $this->inColumn . ':' : '') . $article;

			$this->Template->headline = $this->headline;
			$this->Template->href = $this->addToUrl($href);
			$this->Template->teaser = $this->teaser;
			$this->Template->readMore = specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $this->headline));
			$this->Template->more = $GLOBALS['TL_LANG']['MSC']['more'];

			return;
		}

		// Overwrite page title
		if (!$this->blnNoMarkup && strlen($this->Input->get('articles')) && strlen($this->title))
		{
			global $objPage;
			$objPage->pageTitle = $this->title;
		}

		$this->Template->printable = false;
		$this->Template->backlink = false;

		// Back link
		if (!$this->multiMode && strlen($this->Input->get('articles')))
		{
			list($strSection, $strArticle) = explode(':', $this->Input->get('articles'));

			if (is_null($strArticle))
			{
				$strArticle = $strSection;
			}

			if ($strArticle == $this->id || $strArticle == $this->alias)
			{
				$this->Template->backlink = 'javascript:history.go(-1)';
				$this->Template->back = htmlspecialchars($GLOBALS['TL_LANG']['MSC']['goBack']);
			}
		}

		$contentElements = false;

		// HOOK: trigger psishop extension
		if (in_array('psishop', $this->Config->getActiveModules()))
		{
			$contentElements = Psishop::getProductlisting($this);
		}

		// Default routine
		if ($contentElements === false)
		{
			$contentElements = '';
			$objCte = $this->Database->prepare("SELECT id FROM tl_content WHERE pid=?" . (!BE_USER_LOGGED_IN ? " AND invisible=''" : "") . " ORDER BY sorting")
									 ->execute($this->id);

			while ($objCte->next())
			{
				$contentElements .= $this->getContentElement($objCte->id);
			}
		}

		$this->Template->teaser = $this->teaser;
		$this->Template->contentElements = $contentElements;

		if ($this->keywords != '')
		{
			$GLOBALS['TL_KEYWORDS'] .= (strlen($GLOBALS['TL_KEYWORDS']) ? ', ' : '') . $this->keywords;
		}

		if ($this->printable)
		{
			$request = ampersand($this->Environment->request, true);

			if ($request == 'index.php')
			{
				$request = '';
			}

			$this->Template->href = $request . ((strpos($request, '?') !== false) ? '&amp;' : '?') . 'pdf=' . $this->id;
			$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['printAsPdf']);
			$this->Template->label = $GLOBALS['TL_LANG']['MSC']['printAsPdf'];
			$this->Template->printable = true;
		}
	}
}

?>