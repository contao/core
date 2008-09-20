<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
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
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleArticle
 *
 * Provides methodes to handle articles.
 * @copyright  Leo Feyer 2005
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
	 * URL cache array
	 * @var array
	 */
	private static $arrCache = array();


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

		if (!strlen($this->cssID[0]))
		{
			$this->cssID = array(standardize($this->title), $this->cssID[1]);
		}

		$this->Template->column = $this->inColumn;

		// Add modification date
		$this->Template->timestamp = $this->tstamp;
		$this->Template->date = date($GLOBALS['TL_CONFIG']['datimFormat'], $this->tstamp);

		// Add author
		if (strlen($this->author))
		{
			$strCacheKey = 'author_' . $this->author;

			// Select from database
			if (!array_key_exists($strCacheKey, self::$arrCache))
			{
				$objAuthor = $this->Database->prepare("SELECT name FROM tl_user WHERE id=?")
											->limit(1)
											->execute($this->author);

				if ($objAuthor->numRows)
				{
					self::$arrCache[$strCacheKey] = $GLOBALS['TL_LANG']['MSC']['by'] . ' ' . $objAuthor->name;
				}
				else
				{
					self::$arrCache[$strCacheKey] = '';
				}
			}

			$this->Template->author = self::$arrCache[$strCacheKey];
		}

		// Show teaser only
		if ($this->multiMode && $this->showTeaser)
		{
			$this->Template = new FrontendTemplate('mod_article_teaser');

			$href = ($this->inColumn != 'main') ? 'sections=' . $this->inColumn . '&amp;' : '';
			$href .= 'articles=' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($this->alias)) ? $this->alias : $this->id);

			$this->Template->headline = $this->headline;
			$this->Template->teaser = $this->teaser;
			$this->Template->href = $this->addToUrl($href);
			$this->Template->more = $GLOBALS['TL_LANG']['MSC']['more'];

			return;
		}

		// Overwrite page title
		if (strlen($this->Input->get('articles')) && strlen($this->title))
		{
			global $objPage;
			$objPage->pageTitle = $this->title;
		}

		$this->Template->printable = false;
		$this->Template->backlink = (!$this->multiMode && strlen($this->Input->get('articles')) && ($this->Input->get('articles') == $this->id || $this->Input->get('articles') == $this->alias)) ? 'javascript:history.go(-1)' : false;
		$this->Template->back = htmlspecialchars($GLOBALS['TL_LANG']['MSC']['goBack']);

		$contentElements = '';

		$objCte = $this->Database->prepare("SELECT id FROM tl_content WHERE pid=?" . (!BE_USER_LOGGED_IN ? " AND invisible=''" : "") . " ORDER BY sorting")
								 ->execute($this->id);

		while ($objCte->next())
		{
			$contentElements .= $this->getContentElement($objCte->id);
		}

		$this->Template->contentElements = $contentElements;
		$GLOBALS['TL_KEYWORDS'] .= (strlen($GLOBALS['TL_KEYWORDS']) ? ', ' : '') . $this->keywords;

		if ($this->printable)
		{
			$this->Template->printable = true;
			$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['printAsPdf']);
			$this->Template->href = ampersand($this->Environment->request) . ((strpos($this->Environment->request, '?') !== false) ? '&amp;' : '?') . 'pdf=' . $this->id;
			$this->Template->label = strlen($this->label) ? $this->label : $GLOBALS['TL_LANG']['MSC']['printAsPdf'];
		}
	}
}

?>