<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package News
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleNewsArchive
 *
 * Front end module "news archive".
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 */
class ModuleNewsArchive extends \ModuleNews
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
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['newsarchive'][0]) . ' ###';
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
		if ($this->news_readerModule > 0 && (isset($_GET['items']) || (\Config::get('useAutoItem') && isset($_GET['auto_item']))))
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
	 */
	protected function compile()
	{
		global $objPage;

		$limit = null;
		$offset = 0;
		$intBegin = 0;
		$intEnd = 0;

		$intYear = \Input::get('year');
		$intMonth = \Input::get('month');
		$intDay = \Input::get('day');

		// Jump to the current period
		if (!isset($_GET['year']) && !isset($_GET['month']) && !isset($_GET['day']) && $this->news_jumpToCurrent != 'all_items')
		{
			switch ($this->news_format)
			{
				case 'news_year':
					$intYear = date('Y');
					break;

				default:
				case 'news_month':
					$intMonth = date('Ym');
					break;

				case 'news_day':
					$intDay = date('Ymd');
					break;
			}
		}

		// Display year
		if ($intYear)
		{
			$strDate = $intYear;
			$objDate = new \Date($strDate, 'Y');
			$intBegin = $objDate->yearBegin;
			$intEnd = $objDate->yearEnd;
			$this->headline .= ' ' . date('Y', $objDate->tstamp);
		}

		// Display month
		elseif ($intMonth)
		{
			$strDate = $intMonth;
			$objDate = new \Date($strDate, 'Ym');
			$intBegin = $objDate->monthBegin;
			$intEnd = $objDate->monthEnd;
			$this->headline .= ' ' . \Date::parse('F Y', $objDate->tstamp);
		}

		// Display day
		elseif ($intDay)
		{
			$strDate = $intDay;
			$objDate = new \Date($strDate, 'Ymd');
			$intBegin = $objDate->dayBegin;
			$intEnd = $objDate->dayEnd;
			$this->headline .= ' ' . \Date::parse($objPage->dateFormat, $objDate->tstamp);
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
			$intTotal = \NewsModel::countPublishedFromToByPids($intBegin, $intEnd, $this->news_archives);

			if ($intTotal > 0)
			{
				$total = $intTotal;

				// Get the current page
				$id = 'page_a' . $this->id;
				$page = \Input::get($id) ?: 1;

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
				$objPagination = new \Pagination($total, $this->perPage, \Config::get('maxPaginationLinks'), $id);
				$this->Template->pagination = $objPagination->generate("\n  ");
			}
		}

		// Get the news items
		if (isset($limit))
		{
			$objArticles = \NewsModel::findPublishedFromToByPids($intBegin, $intEnd, $this->news_archives, $limit, $offset);
		}
		else
		{
			$objArticles = \NewsModel::findPublishedFromToByPids($intBegin, $intEnd, $this->news_archives);
		}

		// Add the articles
		if ($objArticles !== null)
		{
			$this->Template->articles = $this->parseArticles($objArticles);
		}

		$this->Template->headline = trim($this->headline);
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['empty'];
	}
}
