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
 * Provide methodes to render a pagination menu.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Pagination
{

	/**
	 * Current page number
	 * @var integer
	 */
	protected $intPage;

	/**
	 * Total number of rows
	 * @var integer
	 */
	protected $intRows;

	/**
	 * Number of rows per page
	 * @var integer
	 */
	protected $intRowsPerPage;

	/**
	 * Total number of pages
	 * @var integer
	 */
	protected $intTotalPages;

	/**
	 * Total number of links
	 * @var integer
	 */
	protected $intNumberOfLinks;

	/**
	 * Label for the "<< first" link
	 * @var string
	 */
	protected $lblFirst;

	/**
	 * Label for the "< previous" link
	 * @var string
	 */
	protected $lblPrevious;

	/**
	 * Label for the "next >" link
	 * @var string
	 */
	protected $lblNext;

	/**
	 * Label for the "last >>" link
	 * @var string
	 */
	protected $lblLast;

	/**
	 * Label for "total pages"
	 * @var string
	 */
	protected $lblTotal;

	/**
	 * Template object
	 * @var \Template|object
	 */
	protected $objTemplate;

	/**
	 * Show "<< first" and "last >>" links
	 * @var boolean
	 */
	protected $blnShowFirstLast = true;

	/**
	 * Request url
	 * @var string
	 */
	protected $strUrl = '';

	/**
	 * Page paramenter
	 * @var string
	 */
	protected $strParameter = 'page';

	/**
	 * Variable connector
	 * @var string
	 */
	protected $strVarConnector = '?';

	/**
	 * Data array
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * Data array
	 * @var array
	 */
	protected $blnForceParam = false;


	/**
	 * Set the number of rows, the number of results per pages and the number of links
	 *
	 * @param integer   $intRows          The number of rows
	 * @param integer   $intPerPage       The number of items per page
	 * @param integer   $intNumberOfLinks The number of links to generate
	 * @param string    $strParameter     The parameter name
	 * @param \Template $objTemplate      The template object
	 * @param boolean   $blnForceParam    Force the URL parameter
	 */
	public function __construct($intRows, $intPerPage, $intNumberOfLinks=7, $strParameter='page', \Template $objTemplate=null, $blnForceParam=false)
	{
		$this->intPage = 1;
		$this->intRows = (int) $intRows;
		$this->intRowsPerPage = (int) $intPerPage;
		$this->intNumberOfLinks = (int) $intNumberOfLinks;

		// Initialize default labels
		$this->lblFirst = $GLOBALS['TL_LANG']['MSC']['first'];
		$this->lblPrevious = $GLOBALS['TL_LANG']['MSC']['previous'];
		$this->lblNext = $GLOBALS['TL_LANG']['MSC']['next'];
		$this->lblLast = $GLOBALS['TL_LANG']['MSC']['last'];
		$this->lblTotal = $GLOBALS['TL_LANG']['MSC']['totalPages'];

		if (\Input::get($strParameter) > 0)
		{
			$this->intPage = \Input::get($strParameter);
		}

		$this->strParameter = $strParameter;

		// Backwards compatibility
		if ($objTemplate === null)
		{
			/** @var \FrontendTemplate|object $objTemplate */
			$objTemplate = new \FrontendTemplate('pagination');
		}

		$this->objTemplate = $objTemplate;
		$this->blnForceParam = $blnForceParam;
	}


	/**
	 * Return true if the pagination menu has a "<< first" link
	 *
	 * @return boolean True if the pagination menu has a "<< first" link
	 */
	public function hasFirst()
	{
		return ($this->blnShowFirstLast && $this->intPage > 2) ? true : false;
	}


	/**
	 * Return true if the pagination menu has a "< previous" link
	 *
	 * @return boolean True if the pagination menu has a "< previous" link
	 */
	public function hasPrevious()
	{
		return ($this->intPage > 1) ? true : false;
	}


	/**
	 * Return true if the pagination menu has a "next >" link
	 *
	 * @return boolean True if the pagination menu has a "next >" link
	 */
	public function hasNext()
	{
		return ($this->intPage < $this->intTotalPages) ? true : false;
	}


	/**
	 * Return true if the pagination menu has a "last >>" link
	 *
	 * @return boolean True if the pagination menu has a "last >>" link
	 */
	public function hasLast()
	{
		return ($this->blnShowFirstLast && $this->intPage < ($this->intTotalPages - 1)) ? true : false;
	}


	/**
	 * Generate the pagination menu and return it as HTML string
	 *
	 * @param string $strSeparator The separator string
	 *
	 * @return string The pagination menu as HTML string
	 */
	public function generate($strSeparator=' ')
	{
		if ($this->intRowsPerPage < 1)
		{
			return '';
		}

		$blnQuery = false;
		$this->strUrl = preg_replace('/\?.*$/', '', \Environment::get('request'));

		// Prepare the URL
		foreach (preg_split('/&(amp;)?/', \Environment::get('queryString'), -1, PREG_SPLIT_NO_EMPTY) as $fragment)
		{
			if (strpos($fragment, $this->strParameter . '=') === false)
			{
				$this->strUrl .= (!$blnQuery ? '?' : '&amp;') . $fragment;
				$blnQuery = true;
			}
		}

		$this->strVarConnector = $blnQuery ? '&amp;' : '?';
		$this->intTotalPages = ceil($this->intRows / $this->intRowsPerPage);

		// Return if there is only one page
		if ($this->intTotalPages < 2 || $this->intRows < 1)
		{
			return '';
		}

		if ($this->intPage > $this->intTotalPages)
		{
			$this->intPage = $this->intTotalPages;
		}

		$objTemplate = $this->objTemplate;

		$objTemplate->hasFirst = $this->hasFirst();
		$objTemplate->hasPrevious = $this->hasPrevious();
		$objTemplate->hasNext = $this->hasNext();
		$objTemplate->hasLast = $this->hasLast();

		// Backwards compatibility
		$objTemplate->items = $this->getItemsAsString($strSeparator);

		$objTemplate->pages = $this->getItemsAsArray();
		$objTemplate->total = sprintf($this->lblTotal, $this->intPage, $this->intTotalPages);

		$objTemplate->first = array
		(
			'link' => $this->lblFirst,
			'href' => $this->linkToPage(1),
			'title' => sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['goToPage']), 1)
		);

		$objTemplate->previous = array
		(
			'link' => $this->lblPrevious,
			'href' => $this->linkToPage($this->intPage - 1),
			'title' => sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['goToPage']), ($this->intPage - 1))
		);

		$objTemplate->next = array
		(
			'link' => $this->lblNext,
			'href' => $this->linkToPage($this->intPage + 1),
			'title' => sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['goToPage']), ($this->intPage + 1))
		);

		$objTemplate->last = array
		(
			'link' => $this->lblLast,
			'href' => $this->linkToPage($this->intTotalPages),
			'title' => sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['goToPage']), $this->intTotalPages)
		);

		$objTemplate->class = 'pagination-' . $this->strParameter;

		// Adding rel="prev" and rel="next" links is not possible
		// anymore with unique variable names (see #3515 and #4141)

		return $objTemplate->parse();
	}


	/**
	 * Generate all page links separated with the given argument and return them as string
	 *
	 * @param string $strSeparator The separator string
	 *
	 * @return string The page links as HTML string
	 */
	public function getItemsAsString($strSeparator=' ')
	{
		$arrLinks = array();

		foreach ($this->getItemsAsArray() as $arrItem)
		{
			if ($arrItem['href'] === null)
			{
				$arrLinks[] = sprintf('<li><span class="current">%s</span></li>', $arrItem['page']);
			}
			else
			{
				$arrLinks[] = sprintf('<li><a href="%s" class="link" title="%s">%s</a></li>', $arrItem['href'], $arrItem['title'], $arrItem['page']);
			}
		}

		return implode($strSeparator, $arrLinks);
	}


	/**
	 * Generate all page links and return them as array
	 *
	 * @return array The page links as array
	 */
	public function getItemsAsArray()
	{
		$arrLinks = array();

		$intNumberOfLinks = floor($this->intNumberOfLinks / 2);
		$intFirstOffset = $this->intPage - $intNumberOfLinks - 1;

		if ($intFirstOffset > 0)
		{
			$intFirstOffset = 0;
		}

		$intLastOffset = $this->intPage + $intNumberOfLinks - $this->intTotalPages;

		if ($intLastOffset < 0)
		{
			$intLastOffset = 0;
		}

		$intFirstLink = $this->intPage - $intNumberOfLinks - $intLastOffset;

		if ($intFirstLink < 1)
		{
			$intFirstLink = 1;
		}

		$intLastLink = $this->intPage + $intNumberOfLinks - $intFirstOffset;

		if ($intLastLink > $this->intTotalPages)
		{
			$intLastLink = $this->intTotalPages;
		}

		for ($i=$intFirstLink; $i<=$intLastLink; $i++)
		{
			if ($i == $this->intPage)
			{
				$arrLinks[] = array
				(
					'page'  => $i,
					'href'  => null,
					'title' => null
				);
			}
			else
			{
				$arrLinks[] = array
				(
					'page'  => $i,
					'href'  => $this->linkToPage($i),
					'title' => specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['goToPage'], $i))
				);
			}
		}

		return $arrLinks;
	}


	/**
	 * Generate a link and return the URL
	 *
	 * @param integer $intPage The page ID
	 *
	 * @return string The URL string
	 */
	protected function linkToPage($intPage)
	{
		if ($intPage <= 1 && !$this->blnForceParam)
		{
			return ampersand($this->strUrl);
		}
		else
		{
			return ampersand($this->strUrl) . $this->strVarConnector . $this->strParameter . '=' . $intPage;
		}
	}
}
