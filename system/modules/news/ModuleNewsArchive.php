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
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleNewsArchive
 *
 * Front end module "news archive".
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleNewsArchive extends ModuleNews
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_newsarchive';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### NEWS ARCHIVE ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->news_archives = $this->sortOutProtected(deserialize($this->news_archives, true));

		if (!is_array($this->news_archives) || count($this->news_archives) < 1 || (!$this->news_jumpToCurrent && !strlen($this->Input->get('month'))))
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
		$limit = null;
		$offset = 0;
		$strDate = $this->Input->get('month') ? $this->Input->get('month') : date('Ym');
		$objDate = new Date($strDate, 'Ym');

		// Display month
		$intBegin = $objDate->monthBegin;
		$intEnd = $objDate->monthEnd;

		// Display year
		if ($this->news_format == 'news_year')
		{
			$intBegin = $objDate->yearBegin;
			$intEnd = $objDate->yearEnd;
		}

		$time = time();

		// Split result
		if ($this->perPage > 0)
		{
			// Get total number of items
			$objTotal = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_news WHERE pid IN(" . implode(',', $this->news_archives) . ") AND date>=? AND date<=?" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : "") . " ORDER BY date DESC")
									   ->execute($intBegin, $intEnd, $time, $time);

			$total = $objTotal->total;

			// Get current page
			$page = $this->Input->get('page') ? $this->Input->get('page') : 1;

			if ($page > ($total/$this->perPage))
			{
				$page = ceil($total/$this->perPage);
			}

			// Set limit and offset
			$limit = ((is_null($limit) || $this->perPage < $limit) ? $this->perPage : $limit);
			$offset = ((($page > 1) ? $page : 1) - 1) * $this->perPage;

			// Add pagination menu
			$objPagination = new Pagination($total, $this->perPage);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		$objArticlesStmt = $this->Database->prepare("SELECT *, author AS authorId, (SELECT title FROM tl_news_archive WHERE tl_news_archive.id=tl_news.pid) AS archive, (SELECT jumpTo FROM tl_news_archive WHERE tl_news_archive.id=tl_news.pid) AS parentJumpTo, (SELECT name FROM tl_user WHERE id=author) AS author FROM tl_news WHERE pid IN(" . implode(',', $this->news_archives) . ") AND date>=? AND date<=?" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : "") . " ORDER BY date DESC");

		// Limit result
		if ($limit)
		{
			$objArticlesStmt->limit($limit, $offset);
		}

		$objArticles = $objArticlesStmt->execute($intBegin, $intEnd, $time, $time);

		// No items found
		if ($objArticles->numRows < 1)
		{
			$this->Template = new FrontendTemplate('mod_newsarchive_empty');
		}

		$this->Template->headline = $GLOBALS['TL_LANG']['MONTHS'][(date('m', $objDate->tstamp) - 1)] . ' ' . substr($strDate, 0, 4);

		// Overwrite headline
		if ($this->news_format == 'news_year')
		{
			$this->Template->headline = substr($strDate, 0, 4);
		}

		$this->Template->articles = $this->parseArticles($objArticles);
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['empty'];
	}
}

?>