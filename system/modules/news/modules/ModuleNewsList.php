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
 * Front end module "news list".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleNewsList extends \ModuleNews
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_newslist';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			/** @var \BackendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['newslist'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->news_archives = $this->sortOutProtected(deserialize($this->news_archives));

		// Return if there are no archives
		if (!is_array($this->news_archives) || empty($this->news_archives))
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
		$offset = intval($this->skipFirst);
		$limit = null;

		// Maximum number of items
		if ($this->numberOfItems > 0)
		{
			$limit = $this->numberOfItems;
		}

		// Handle featured news
		if ($this->news_featured == 'featured')
		{
			$blnFeatured = true;
		}
		elseif ($this->news_featured == 'unfeatured')
		{
			$blnFeatured = false;
		}
		else
		{
			$blnFeatured = null;
		}

		$this->Template->articles = array();
		$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyList'];

		// Get the total number of items
		$intTotal = \NewsModel::countPublishedByPids($this->news_archives, $blnFeatured);

		if ($intTotal < 1)
		{
			return;
		}

		$total = $intTotal - $offset;

		// Split the results
		if ($this->perPage > 0 && (!isset($limit) || $this->numberOfItems > $this->perPage))
		{
			// Adjust the overall limit
			if (isset($limit))
			{
				$total = min($limit, $total);
			}

			// Get the current page
			$id = 'page_n' . $this->id;
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
			$offset += (max($page, 1) - 1) * $this->perPage;
			$skip = intval($this->skipFirst);

			// Overall limit
			if ($offset + $limit > $total + $skip)
			{
				$limit = $total + $skip - $offset;
			}

			// Add the pagination menu
			$objPagination = new \Pagination($total, $this->perPage, \Config::get('maxPaginationLinks'), $id);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		// Get the items
		if (isset($limit))
		{
			$objArticles = \NewsModel::findPublishedByPids($this->news_archives, $blnFeatured, $limit, $offset);
		}
		else
		{
			$objArticles = \NewsModel::findPublishedByPids($this->news_archives, $blnFeatured, 0, $offset);
		}

		// Add the articles
		if ($objArticles !== null)
		{
			$this->Template->articles = $this->parseArticles($objArticles);
		}

		$this->Template->archives = $this->news_archives;
	}
}
