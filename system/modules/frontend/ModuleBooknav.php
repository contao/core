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
 * Class ModuleBooknav
 *
 * Front end module "book navigation".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleBooknav extends Module
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
			$objTemplate = new BackendTemplate('be_wildcard');

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
	 * Generate module
	 */
	protected function compile()
	{
		// Get root page
		$objRoot = $this->Database->prepare("SELECT id, pid, sorting, alias, title, pageTitle FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($this->rootPage);

		if ($objRoot->numRows < 1)
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
		$this->arrPages[$objRoot->id] = $objRoot->row();
		$this->getBookPages($this->rootPage, $groups, time());

		global $objPage;

		// Upper page
		if ($objPage->id != $this->rootPage)
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
		$objPages = $this->Database->prepare("SELECT id, pid, sorting, alias, title, pageTitle, protected, groups, (SELECT COUNT(*) FROM tl_page p2 WHERE p1.id=p2.pid) AS hasChilds FROM tl_page p1 WHERE p1.pid=? AND p1.id!=? AND type!='root' AND type!='error_403' AND type!='error_404'" . (!$this->showHidden ? " AND hide!=1" : "") . ((FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN && !$this->showProtected) ? " AND guests!=1" : "") . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY sorting")
								   ->execute($intParentId, $intParentId);

		if ($objPages->numRows > 0)
		{
			while ($objPages->next())
			{
				$_groups = deserialize($objPages->groups);

				// Do not show protected pages unless a back end or front end user is logged in
				if (!$objPages->protected || BE_USER_LOGGED_IN || (is_array($_groups) && count(array_intersect($groups, $_groups))) || $this->showProtected)
				{
					$this->arrPages[$objPages->id] = $objPages->row();

					if ($objPages->hasChilds > 0)
					{
						$this->getBookPages($objPages->id, $groups, $time);
					}
				}
			}
		}
	}
}

?>