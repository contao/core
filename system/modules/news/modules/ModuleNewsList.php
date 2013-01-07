<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
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
 * Class ModuleNewsList
 *
 * Front end module "news list".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    News
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
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### NEWS LIST ###';
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
		$this->Template->articles = array();

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

		// Get the total number of items
		$intTotal = \NewsModel::countPublishedByPids($this->news_archives, $blnFeatured);

		if ($intTotal < 1)
		{
			$this->Template->articles = array();
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

			// Overall limit
			if ($offset + $limit > $total)
			{
				$limit = $total - $offset;
			}

			// Add the pagination menu
			$objPagination = new \Pagination($total, $this->perPage, 7, $id);
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

		// No items found
		if ($objArticles === null)
		{
			$this->Template = new \FrontendTemplate('mod_newsarchive_empty');
		}
		else
		{
			$this->Template->articles = $this->parseArticles($objArticles);
		}

		$this->Template->archives = $this->news_archives;
		$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyList'];
	}
}
