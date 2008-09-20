<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleCustomnav
 *
 * Front end module "custom navigation".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
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
			$objPages = $this->Database->prepare("SELECT * FROM tl_page WHERE id=? AND type!=? AND type!=? AND type!=?" . ((FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN) ? " AND guests!=1" : "") . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : ""))
									   ->limit(1)
									   ->execute($intId, 'root', 'error_403', 'error_404', $time, $time);

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

		// Determine the layout template
		if (!strlen($this->navigationTpl))
		{
			$this->navigationTpl = (file_exists(TL_ROOT . '/system/modules/frontend/templates/mod_navigation_items.tpl') ? 'mod_navigation_items' : 'nav_default');
		}

		$objTemplate = new BackendTemplate($this->navigationTpl);

		$objTemplate->type = get_class($this);
		$objTemplate->level = 'level_1';

		$count = 0;
		$limit = count($arrPages);

		foreach ($arrPages as $arrPage)
		{
			$_groups = deserialize($arrPage['groups']);

			// Do not show protected pages unless a back end or front end user is logged in
			if (!$arrPage['protected'] || (!is_array($_groups) && FE_USER_LOGGED_IN) || BE_USER_LOGGED_IN || (is_array($_groups) && array_intersect($_groups, $groups)) || $this->showProtected)
			{
				$class = '';

				if (++$count == 1)
				{
					$class .= ' first';
				}

				if ($count == $limit)
				{
					$class .= ' last';
				}

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

				// Active page == main page
				if ($objPage->id == $arrPage['id'])
				{
					$items[] = array
					(
						'isActive' => true,
						'class' => trim((strlen($arrPage['cssClass']) ? $arrPage['cssClass'] : '') . $class),
						'pageTitle' => specialchars($arrPage['pageTitle']),
						'title' => specialchars($arrPage['title']),
						'link' => $arrPage['title'],
						'href' => $href,
						'target' => (($arrPage['type'] == 'redirect' && $arrPage['target']) ? ' window.open(this.href); return false;' : ''),
						'description' => str_replace(array("\n", "\r"), array(' ' , ''), $arrPage['description']),
						'accesskey' => $arrPage['accesskey'],
						'tabindex' => $arrPage['tabindex']
					);

					continue;
				}

				$items[] = array
				(
					'isActive' => false,
					'class' => trim((strlen($arrPage['cssClass']) ? $arrPage['cssClass'] : '') . $class),
					'pageTitle' => specialchars($arrPage['pageTitle']),
					'title' => specialchars($arrPage['title']),
					'link' => $arrPage['title'],
					'href' => $href,
					'target' => (($arrPage['type'] == 'redirect' && $arrPage['target']) ? ' window.open(this.href); return false;' : ''),
					'description' => str_replace(array("\n", "\r"), array(' ' , ''), $arrPage['description']),
					'accesskey' => $arrPage['accesskey'],
					'tabindex' => $arrPage['tabindex']
				);
			}
		}

		$objTemplate->items = $items;

		$this->Template->skipId = 'skipNavigation' . $this->id;
		$this->Template->request = ampersand($this->Environment->request, ENCODE_AMPERSANDS);
		$this->Template->skipNavigation = specialchars($GLOBALS['TL_LANG']['MSC']['skipNavigation']);
		$this->Template->items = count($items) ? $objTemplate->parse() : '';
	}
}

?>