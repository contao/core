<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class Pagination
 *
 * Provide methodes to render a pagination menu.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Frontend
 */
class Pagination extends Frontend
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
	 * Set the number of rows, the number of results per pages and the number of links
	 * @param int
	 * @param int
	 * @param int
	 */
	public function __construct($intRows, $intPerPage, $intNumberOfLinks=7)
	{
		parent::__construct();

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

		if ($this->Input->get('page') != '' && $this->Input->get('page') > 0)
		{
			$this->intPage = $this->Input->get('page');
		}
	}


	/**
	 * Return true if the pagination menu has a "<< first" link
	 * @return boolean
	 */
	public function hasFirst()
	{
		return ($this->blnShowFirstLast && $this->intPage > 2) ? true : false;
	}


	/**
	 * Return true if the pagination menu has a "< previous" link
	 * @return boolean
	 */
	public function hasPrevious()
	{
		return ($this->intPage > 1) ? true : false;
	}


	/**
	 * Return true if the pagination menu has a "next >" link
	 * @return boolean
	 */
	public function hasNext()
	{
		return ($this->intPage < $this->intTotalPages) ? true : false;
	}


	/**
	 * Return true if the pagination menu has a "last >>" link
	 * @return boolean
	 */
	public function hasLast()
	{
		return ($this->blnShowFirstLast && $this->intPage < ($this->intTotalPages - 1)) ? true : false;
	}


	/**
	 * Generate the pagination menu and return it as HTML string
	 * @param string
	 * @return string
	 */
	public function generate($strSeparator=' ')
	{
		if ($this->intRowsPerPage < 1)
		{
			return '';
		}

		$blnQuery = false;
		$this->strUrl = preg_replace('/\?.*$/', '', $this->Environment->request);

		// Prepare URL
		foreach (preg_split('/&(amp;)?/', $_SERVER['QUERY_STRING'], -1, PREG_SPLIT_NO_EMPTY) as $fragment)
		{
			if (strncasecmp($fragment, 'page', 4) !== 0)
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

		$this->Template = new FrontendTemplate('pagination');

		$this->Template->hasFirst = $this->hasFirst();
		$this->Template->hasPrevious = $this->hasPrevious();
		$this->Template->hasNext = $this->hasNext();
		$this->Template->hasLast = $this->hasLast();

		$this->Template->items = $this->getItemsAsString($strSeparator);
		$this->Template->total = sprintf($this->lblTotal, $this->intPage, $this->intTotalPages);

		$this->Template->first = array
		(
			'link' => $this->lblFirst,
			'href' => ampersand($this->strUrl) . $this->strVarConnector . 'page=1',
			'title' => sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['goToPage']), 1)
		);

		$this->Template->previous = array
		(
			'link' => $this->lblPrevious,
			'href' => ampersand($this->strUrl) . $this->strVarConnector . 'page=' . ($this->intPage - 1),
			'title' => sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['goToPage']), ($this->intPage - 1))
		);

		$this->Template->next = array
		(
			'link' => $this->lblNext,
			'href' => ampersand($this->strUrl) . $this->strVarConnector . 'page=' . ($this->intPage + 1),
			'title' => sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['goToPage']), ($this->intPage + 1))
		);

		$this->Template->last = array
		(
			'link' => $this->lblLast,
			'href' => ampersand($this->strUrl) . $this->strVarConnector . 'page=' . $this->intTotalPages,
			'title' => sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['goToPage']), $this->intTotalPages)
		);

		return $this->Template->parse();
	}


	/**
	 * Generate all page links separated with the given argument and return them as string
	 * @param  string
	 * @return string
	 */
	public function getItemsAsString($strSeparator=' ')
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
				$arrLinks[] = sprintf('<li><span class="current">%s</span></li>', $i);
				continue;
			}

			$arrLinks[] = sprintf('<li><a href="%s" class="link" title="%s">%s</a></li>',
								ampersand($this->strUrl) . $this->strVarConnector . 'page=' . $i,
								sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['goToPage']), $i),
								$i);
		}

		return implode($strSeparator, $arrLinks);
	}
}

?>