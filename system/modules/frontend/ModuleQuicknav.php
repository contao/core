<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleQuicknav
 *
 * Front end module "quick navigation".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class ModuleQuicknav extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_quicknav';


	/**
	 * Redirect to the selected page
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### QUICK NAVIGATION ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		if ($this->Input->post('FORM_SUBMIT') == 'tl_quicknav')
		{
			if (strlen($this->Input->post('target', true)))
			{
				$this->redirect($this->Input->post('target', true));
			}

			$this->reload();
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		// Start from the website root if there is no reference page
		if ($this->rootPage == 0)
		{
			global $objPage;
			$this->rootPage = $objPage->rootId;
		}

		$this->Template->targetPage = $GLOBALS['TL_LANG']['MSC']['targetPage'];
		$this->Template->button = specialchars($GLOBALS['TL_LANG']['MSC']['go']);
		$this->Template->title = ($this->customLabel != '') ? $this->customLabel : $GLOBALS['TL_LANG']['MSC']['quicknav'];
		$this->Template->request = ampersand($this->Environment->request, true);
		$this->Template->items = $this->getQuicknavPages($this->rootPage);
	}


	/**
	 * Recursively get all quicknav pages and return them as array
	 * @param integer
	 * @param integer
	 * @return array
	 */
	protected function getQuicknavPages($pid, $level=1)
	{
		global $objPage;

		$groups = array();
		$arrPages = array();

		// Get all groups of the current front end user
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			$groups = $this->User->groups;
		}

		$time = time();

		// Get all active subpages
		$objSubpages = $this->Database->prepare("SELECT * FROM tl_page WHERE pid=? AND type!='root' AND type!='error_403' AND type!='error_404'" . ((FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN) ? " AND guests!=1" : "") . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY sorting")
									  ->execute($pid);

		if ($objSubpages->numRows < 1)
		{
			return array();
		}

		++$level;

		while($objSubpages->next())
		{
			$_groups = deserialize($objSubpages->groups);

			// Do not show protected pages unless a back end or front end user is logged in
			if (!$objSubpages->protected || (!is_array($_groups) && FE_USER_LOGGED_IN) || BE_USER_LOGGED_IN || (is_array($_groups) && array_intersect($_groups, $groups)) || $this->showProtected)
			{
				// Do not skip the current page here! (see #4523)

				// Check hidden pages
				if (!$objSubpages->hide || $this->showHidden)
				{
					$objSubpages->title = strip_insert_tags($objSubpages->title);
					$objSubpages->pageTitle = strip_insert_tags($objSubpages->pageTitle);

					$arrPages[] = array
					(
						'level' => ($level - 2),
						'title' => (strlen($objSubpages->pageTitle) ? specialchars($objSubpages->pageTitle) : specialchars($objSubpages->title)),
						'href' => $this->generateFrontendUrl($objSubpages->row()),
						'link' => $objSubpages->title
					);

					// Subpages
					if (!$this->showLevel || $this->showLevel >= $level || (!$this->hardLimit && ($objPage->id == $objSubpages->id || in_array($objPage->id, $this->getChildRecords($objSubpages->id, 'tl_page')))))
					{
						$subpages = $this->getQuicknavPages($objSubpages->id, $level);

						if (is_array($subpages))
						{
							$arrPages = array_merge($arrPages, $subpages);
						}
					}
				}
			}
		}

		return $arrPages;
	}
}

?>