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
 * @package    News
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \BackendTemplate, \Date, \FrontendTemplate, \Input, \ModuleNews, \NewsModel, \Pagination;


/**
 * Class ModuleNewsArchive
 *
 * Front end module "news archive".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
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
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->news_archives = $this->sortOutProtected(deserialize($this->news_archives));

		// No news archives available
		if (!is_array($this->news_archives) || empty($this->news_archives))
		{
			return '';
		}

		// Show the news reader if an item has been selected
		if ($this->news_readerModule > 0 && (isset($_GET['items']) || ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))))
		{
			return $this->getFrontendModule($this->news_readerModule, $this->strColumn);
		}

		// Hide the module if no period has been selected
		if ($this->news_jumpToCurrent == 'hide_module' && !isset($_GET['year']) && !isset($_GET['month']) && !isset($_GET['day']))
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 * @return void
	 */
	protected function compile()
	{
		global $objPage;

		$limit = null;
		$offset = 0;
		$intBegin = 0;
		$intEnd = 0;

		// Jump to the current period
		if (!isset($_GET['year']) && !isset($_GET['month']) && !isset($_GET['day']) && $this->news_jumpToCurrent != 'all_items')
		{
			switch ($this->news_format)
			{
				case 'news_year':
					Input::setGet('year', date('Y'));
					break;

				default:
				case 'news_month':
					Input::setGet('month', date('Ym'));
					break;

				case 'news_day':
					Input::setGet('day', date('Ymd'));
					break;
			}
		}

		// Display year
		if (Input::get('year'))
		{
			$strDate = Input::get('year');
			$objDate = new Date($strDate, 'Y');
			$intBegin = $objDate->yearBegin;
			$intEnd = $objDate->yearEnd;
			$this->headline .= ' ' . date('Y', $objDate->tstamp);
		}
		// Display month
		elseif (Input::get('month'))
		{
			$strDate = Input::get('month');
			$objDate = new Date($strDate, 'Ym');
			$intBegin = $objDate->monthBegin;
			$intEnd = $objDate->monthEnd;
			$this->headline .= ' ' . $this->parseDate('F Y', $objDate->tstamp);
		}
		// Display day
		elseif (Input::get('day'))
		{
			$strDate = Input::get('day');
			$objDate = new Date($strDate, 'Ymd');
			$intBegin = $objDate->dayBegin;
			$intEnd = $objDate->dayEnd;
			$this->headline .= ' ' . $this->parseDate($objPage->dateFormat, $objDate->tstamp);
		}
		// Show all items
		elseif ($this->news_jumpToCurrent == 'all_items')
		{
			$intBegin = 0;
			$intEnd = time();
		}

		$this->Template->articles = array();

		// Split the result
		if ($this->perPage > 0)
		{
			// Get the total number of items
			$intTotal = NewsModel::countPublishedFromToByPids($intBegin, $intEnd, $this->news_archives);

			if ($intTotal > 0)
			{
				$total = $intTotal;

				// Get the current page
				$page = Input::get('page') ? Input::get('page') : 1;

				// Do not index or cache the page if the page number is outside the range
				if ($page < 1 || $page > max(ceil($total/$this->perPage), 1))
				{
					global $objPage;
					$objPage->noSearch = 1;
					$objPage->cache = 0;

					// Send a 404 header
					header('HTTP/1.1 404 Not Found');
					return;
				}

				// Set limit and offset
				$limit = $this->perPage;
				$offset = (max($page, 1) - 1) * $this->perPage;

				// Add the pagination menu
				$objPagination = new Pagination($total, $this->perPage);
				$this->Template->pagination = $objPagination->generate("\n  ");
			}
		}

		// Get the news items
		if (isset($limit))
		{
			$objArticles = NewsModel::findPublishedFromToByPids($intBegin, $intEnd, $this->news_archives, $limit, $offset);
		}
		else
		{
			$objArticles = NewsModel::findPublishedFromToByPids($intBegin, $intEnd, $this->news_archives);
		}

		// No items found
		if ($objArticles === null)
		{
			$this->Template = new FrontendTemplate('mod_newsarchive_empty');
		}
		else
		{
			$this->Template->articles = $this->parseArticles($objArticles);
		}

		$this->Template->headline = trim($this->headline);
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['empty'];
	}
}
