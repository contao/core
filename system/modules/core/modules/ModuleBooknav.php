<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleBooknav
 *
 * Front end module "book navigation".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ModuleBooknav extends \Module
{

	/**
	 * Pages array
	 * @var array
	 */
	protected $arrPages = array();

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_booknav';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### BOOK NAVIGATION ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		global $objPage;

		if (!$this->rootPage || !in_array($this->rootPage, $objPage->trail))
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
		// Get the root page
		if (($objTarget = $this->objModel->getRelated('rootPage')) === null)
		{
			return;
		}

		$groups = array();

		// Get all groups of the current front end user
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			$groups = $this->User->groups;
		}

		// Get all book pages
		$this->arrPages[$objTarget->id] = $objTarget->row();
		$this->getBookPages($objTarget->id, $groups, time());

		global $objPage;

		// Upper page
		if ($objPage->id != $objTarget->id)
		{
			$intKey = $objPage->pid;

			$this->Template->upHref = $this->generateFrontendUrl($this->arrPages[$intKey]);
			$this->Template->upTitle = specialchars($this->arrPages[$intKey]['title'], true);
			$this->Template->upPageTitle = specialchars($this->arrPages[$intKey]['pageTitle'], true);
			$this->Template->upLink = $GLOBALS['TL_LANG']['MSC']['up'];
		}

		$arrLookup = array_keys($this->arrPages);
		$intCurrent = array_search($objPage->id, $arrLookup);

		// HOOK: add pagination info
		$this->Template->currentPage = $intCurrent;
		$this->Template->pageCount = count($arrLookup);

		// Previous page
		if ($intCurrent > 0)
		{
			$intKey = $arrLookup[($intCurrent - 1)];

			$this->Template->prevHref = $this->generateFrontendUrl($this->arrPages[$intKey]);
			$this->Template->prevTitle = specialchars($this->arrPages[$intKey]['title'], true);
			$this->Template->prevPageTitle = specialchars($this->arrPages[$intKey]['pageTitle'], true);
			$this->Template->prevLink = $this->arrPages[$intKey]['title'];
		}

		// Next page
		if ($intCurrent < (count($arrLookup) - 1))
		{
			$intKey = $arrLookup[($intCurrent + 1)];

			$this->Template->nextHref = $this->generateFrontendUrl($this->arrPages[$intKey]);
			$this->Template->nextTitle = specialchars($this->arrPages[$intKey]['title'], true);
			$this->Template->nextPageTitle = specialchars($this->arrPages[$intKey]['pageTitle'], true);
			$this->Template->nextLink = $this->arrPages[$intKey]['title'];
		}
	}


	/**
	 * Recursively get all book pages
	 * @param integer
	 * @param array
	 * @param integer
	 */
	protected function getBookPages($intParentId, $groups, $time)
	{
		$objPages = \PageModel::findPublishedSubpagesWithoutGuestsByPid($intParentId, $this->showHidden);

		if ($objPages !== null)
		{
			while ($objPages->next())
			{
				$_groups = deserialize($objPages->groups);

				// Do not show protected pages unless a back end or front end user is logged in
				if (!$objPages->protected || BE_USER_LOGGED_IN || (is_array($_groups) && count(array_intersect($groups, $_groups))) || $this->showProtected)
				{
					$this->arrPages[$objPages->id] = $objPages->row();

					if ($objPages->subpages > 0)
					{
						$this->getBookPages($objPages->id, $groups, $time);
					}
				}
			}
		}
	}
}
