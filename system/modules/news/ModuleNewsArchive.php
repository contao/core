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
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleNewsArchive
 *
 * Front end module "news archive".
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
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

		$this->news_archives = $this->sortOutProtected(deserialize($this->news_archives));

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

		// Jump to current period
		if (!isset($_GET['year']) && !isset($_GET['month']) && !isset($_GET['day']))
		{
			switch ($this->news_format)
			{
				case 'news_year':
					$this->Input->setGet('year', date('Y'));
					break;

				default:
				case 'news_month':
					$this->Input->setGet('month', date('Ym'));
					break;

				case 'news_day':
					$this->Input->setGet('day', date('Ymd'));
					break;
			}
		}

		// Display year
		if ($this->Input->get('year'))
		{
			$strDate = $this->Input->get('year');
			$objDate = new Date($strDate, 'Y');
			$intBegin = $objDate->yearBegin;
			$intEnd = $objDate->yearEnd;
			$this->headline .= ' ' . date('Y', $objDate->tstamp);
		}

		// Display month
		elseif ($this->Input->get('month'))
		{
			$strDate = $this->Input->get('month');
			$objDate = new Date($strDate, 'Ym');
			$intBegin = $objDate->monthBegin;
			$intEnd = $objDate->monthEnd;
			$this->headline .= ' ' . $this->parseDate('F Y', $objDate->tstamp);
		}

		// Display day
		elseif ($this->Input->get('day'))
		{
			$strDate = $this->Input->get('day');
			$objDate = new Date($strDate, 'Ymd');
			$intBegin = $objDate->dayBegin;
			$intEnd = $objDate->dayEnd;
			$this->headline .= ' ' . $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objDate->tstamp);
		}

		$time = time();

		// Split result
		if ($this->perPage > 0)
		{
			// Get total number of items
			$objTotal = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_news WHERE pid IN(" . implode(',', $this->news_archives) . ") AND date>=? AND date<=?" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY date DESC")
									   ->execute($intBegin, $intEnd);

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

		$objArticlesStmt = $this->Database->prepare("SELECT *, author AS authorId, (SELECT title FROM tl_news_archive WHERE tl_news_archive.id=tl_news.pid) AS archive, (SELECT jumpTo FROM tl_news_archive WHERE tl_news_archive.id=tl_news.pid) AS parentJumpTo, (SELECT name FROM tl_user WHERE id=author) AS author FROM tl_news WHERE pid IN(" . implode(',', $this->news_archives) . ") AND date>=? AND date<=?" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY date DESC");

		// Limit result
		if ($limit)
		{
			$objArticlesStmt->limit($limit, $offset);
		}

		$objArticles = $objArticlesStmt->execute($intBegin, $intEnd);

		// No items found
		if ($objArticles->numRows < 1)
		{
			$this->Template = new FrontendTemplate('mod_newsarchive_empty');
		}

		$this->Template->headline = trim($this->headline);
		$this->Template->articles = $this->parseArticles($objArticles);
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['empty'];
	}
}

?>