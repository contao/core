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
 * Class ModuleCustomnav
 *
 * Front end module "custom navigation".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleCustomnav extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_navigation';


	/**
	 * Redirect to the selected page
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### CUSTOM NAVIGATION MENU ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->pages = deserialize($this->pages);

		if (!is_array($this->pages) || !strlen($this->pages[0]))
		{
			return '';
		}

		$strBuffer = parent::generate();
		return strlen($this->Template->items) ? $strBuffer : '';
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		global $objPage;

		$items = array();
		$groups = array();

		// Get all groups of the current front end user
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			$groups = $this->User->groups;
		}

		$time = time();
		$arrPages = array();

		// Get all active pages
		foreach ($this->pages as $intId)
		{
			$objPages = $this->Database->prepare("SELECT * FROM tl_page WHERE id=? AND type!='root' AND type!='error_403' AND type!='error_404'" . ((FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN) ? " AND guests!=1" : "") . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : ""))
									   ->limit(1)
									   ->execute($intId);

			if ($objPages->numRows < 1)
			{
				continue;
			}

			$arrPages[] = $objPages->fetchAssoc();
		}

		// Return if there are no pages
		if (count($arrPages) < 1)
		{
			return;
		}

		// Set default template
		if ($this->navigationTpl == '')
		{
			$this->navigationTpl = 'nav_default';
		}

		$objTemplate = new FrontendTemplate($this->navigationTpl);

		$objTemplate->type = get_class($this);
		$objTemplate->level = 'level_1';

		foreach ($arrPages as $arrPage)
		{
			$_groups = deserialize($arrPage['groups']);

			// Do not show protected pages unless a back end or front end user is logged in
			if (!$arrPage['protected'] || BE_USER_LOGGED_IN || (is_array($_groups) && count(array_intersect($_groups, $groups))) || $this->showProtected)
			{
				// Get href
				switch ($arrPage['type'])
				{
					case 'redirect':
						$href = $arrPage['url'];
						break;

					case 'forward':
						$objNext = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
												  ->limit(1)
												  ->execute($arrPage['jumpTo']);

						if ($objNext->numRows)
						{
							$href = $this->generateFrontendUrl($objNext->fetchAssoc());
							break;
						}
						// DO NOT ADD A break; STATEMENT

					default:
						$href = $this->generateFrontendUrl($arrPage);
						break;
				}

				// Active page
				if ($objPage->id == $arrPage['id'])
				{
					$strClass = trim((strlen($arrPage['cssClass']) ? $arrPage['cssClass'] : ''));
					$row = $arrPage;

					$row['isActive'] = true;
					$row['class'] = $strClass;
					$row['title'] = specialchars($arrPage['title'], true);
					$row['pageTitle'] = specialchars($arrPage['pageTitle'], true);
					$row['link'] = $arrPage['title'];
					$row['href'] = $href;
					$row['nofollow'] = (strncmp($arrPage['robots'], 'noindex', 7) === 0);
					$row['target'] = '';
					$row['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $arrPage['description']);

					// Override the link target
					if ($arrPage['type'] == 'redirect' && $arrPage['target'])
					{
						$row['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="window.open(this.href); return false;"' : ' target="_blank"';
					}

					$items[] = $row;
				}

				// Regular page
				else
				{
					$strClass = trim((strlen($arrPage['cssClass']) ? $arrPage['cssClass'] : '') . (in_array($arrPage['id'], $objPage->trail) ? ' trail' : ''));
					$row = $arrPage;

					$row['isActive'] = false;
					$row['class'] = $strClass;
					$row['title'] = specialchars($arrPage['title'], true);
					$row['pageTitle'] = specialchars($arrPage['pageTitle'], true);
					$row['link'] = $arrPage['title'];
					$row['href'] = $href;
					$row['nofollow'] = (strncmp($arrPage['robots'], 'noindex', 7) === 0);
					$row['target'] = '';
					$row['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $arrPage['description']);

					// Override the link target
					if ($arrPage['type'] == 'redirect' && $arrPage['target'])
					{
						$row['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="window.open(this.href); return false;"' : ' target="_blank"';
					}

					$items[] = $row;
				}
			}
		}

		// Add classes first and last
		$items[0]['class'] = trim($items[0]['class'] . ' first');
		$last = count($items) - 1;
		$items[$last]['class'] = trim($items[$last]['class'] . ' last');

		$objTemplate->items = $items;

		$this->Template->request = $this->getIndexFreeRequest(true);
		$this->Template->skipId = 'skipNavigation' . $this->id;
		$this->Template->skipNavigation = specialchars($GLOBALS['TL_LANG']['MSC']['skipNavigation']);
		$this->Template->items = count($items) ? $objTemplate->parse() : '';
	}
}

?>