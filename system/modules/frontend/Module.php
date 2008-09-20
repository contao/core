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
 * Class Module
 *
 * Parent class for front end modules.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
abstract class Module extends Frontend
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate;

	/**
	 * Current record
	 * @var array
	 */
	protected $arrData = array();


	/**
	 * Initialize the object
	 * @param object
	 */
	public function __construct(Database_Result $objModule)
	{
		parent::__construct();

		$this->arrData = $objModule->row();
		$this->space = deserialize($objModule->space);
		$this->cssID = deserialize($objModule->cssID, true);

		$arrHeadline = deserialize($objModule->headline);
		$this->headline = is_array($arrHeadline) ? $arrHeadline['value'] : $arrHeadline;
		$this->hl = is_array($arrHeadline) ? $arrHeadline['unit'] : 'h1';
	}


	/**
	 * Set an object property
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}


	/**
	 * Return an object property
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		return $this->arrData[$strKey];
	}


	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		$style = array();

		// Margin
		if (strlen($this->arrData['space'][0]))
		{
			$style[] = 'margin-top:'.$this->arrData['space'][0].'px;';
		}

		if (strlen($this->arrData['space'][1]))
		{
			$style[] = 'margin-bottom:'.$this->arrData['space'][1].'px;';
		}

		// Align
		if (strlen($this->align))
		{
			$style[] = 'text-align:'.$this->align.';';
		}

		$this->Template = new FrontendTemplate($this->strTemplate);

		$this->compile();

		$this->Template->style = count($style) ? implode(' ', $style) : '';
		$this->Template->cssID = strlen($this->cssID[0]) ? ' id="' . $this->cssID[0] . '"' : '';
		$this->Template->class = trim('mod_' . $this->type . ' ' . $this->cssID[1]);

		if (!strlen($this->Template->headline))
		{
			$this->Template->headline = $this->headline;
		}

		if (!strlen($this->Template->hl))
		{
			$this->Template->hl = $this->hl;
		}

		return $this->Template->parse();
	}


	/**
	 * Compile the current element
	 */
	abstract protected function compile();


	/**
	 * Recursively compile the navigation menu and return it as HTML string
	 * @param integer
	 * @param integer
	 * @return string
	 */
	protected function renderNavigation($pid, $level=1)
	{
		$time = time();

		// Get all active subpages
		$objSubpages = $this->Database->prepare("SELECT * FROM tl_page WHERE pid=? AND type!=? AND type!=? AND type!=?" . ((!$this instanceof ModuleSitemap || !$this->showHidden) ? " AND hide!=1" : "") . ((!$this instanceof ModuleSitemap && FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN) ? " AND guests!=1" : "") . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : "") . " ORDER BY sorting")
									  ->execute($pid, 'root', 'error_403', 'error_404', $time, $time);

		if ($objSubpages->numRows < 1)
		{
			return '';
		}

		$items = array();
		$groups = array();

		// Get all groups of the current front end user
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			$groups = $this->User->groups;
		}

		// Determine the layout template
		if (!strlen($this->navigationTpl))
		{
			$this->navigationTpl = (file_exists(TL_ROOT . '/system/modules/frontend/templates/mod_navigation_items.tpl') ? 'mod_navigation_items' : 'nav_default');
		}

		// Overwrite template
		$objTemplate = new FrontendTemplate($this->navigationTpl);

		$objTemplate->type = get_class($this);
		$objTemplate->level = 'level_' . $level++;

		// Get page object
		global $objPage;

		// Browse subpages
		while($objSubpages->next())
		{
			$subitems = '';
			$_groups = deserialize($objSubpages->groups);

			// Do not show protected pages unless a back end or front end user is logged in
			if (!strlen($objSubpages->protected) || (!is_array($_groups) && FE_USER_LOGGED_IN) || BE_USER_LOGGED_IN || (is_array($_groups) && count(array_intersect($groups, $_groups))) || $this->showProtected)
			{
				// Check whether there will be subpages
				if (!$this->showLevel || $this->showLevel >= $level || (!$this->hardLimit && ($objPage->id == $objSubpages->id || in_array($objPage->id, $this->getChildRecords($objSubpages->id, 'tl_page')))))
				{
					$subitems = $this->renderNavigation($objSubpages->id, $level);
				}

				// Get href
				switch ($objSubpages->type)
				{
					case 'redirect':
						$href = $objSubpages->url;
						break;

					case 'forward':
						$objNext = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
												  ->limit(1)
												  ->execute($objSubpages->jumpTo);

						if ($objNext->numRows)
						{
							$href = $this->generateFrontendUrl($objNext->fetchAssoc());
							break;
						}
						// DO NOT ADD A break; STATEMENT

					default:
						$href = $this->generateFrontendUrl($objSubpages->row());
						break;
				}

				// Active page
				if (($objPage->id == $objSubpages->id || $objSubpages->type == 'forward' && $objPage->id == $objSubpages->jumpTo) && !$this instanceof ModuleSitemap)
				{
					$strClass = trim((strlen($subitems) ? 'submenu' : '') . (strlen($objSubpages->cssClass) ? ' ' . $objSubpages->cssClass : ''));

					$items[] = array
					(
						'isActive' => true,
						'subitems' => $subitems,
						'class' => (strlen($strClass) ? $strClass : ''),
						'pageTitle' => specialchars($objSubpages->pageTitle),
						'title' => specialchars($objSubpages->title),
						'link' => $objSubpages->title,
						'href' => $href,
						'alias' => $objSubpages->alias,
						'target' => (($objSubpages->type == 'redirect' && $objSubpages->target) ? ' window.open(this.href); return false;' : ''),
						'description' => str_replace(array("\n", "\r"), array(' ' , ''), $objSubpages->description),
						'accesskey' => $objSubpages->accesskey,
						'tabindex' => $objSubpages->tabindex
					);

					continue;
				}

				$strClass = trim((strlen($subitems) ? 'submenu' : '') . (strlen($objSubpages->cssClass) ? ' ' . $objSubpages->cssClass : '') . (in_array($objSubpages->id, $objPage->trail) ? ' trail' : ''));

				$items[] = array
				(
					'isActive' => false,
					'subitems' => $subitems,
					'class' => (strlen($strClass) ? $strClass : ''),
					'pageTitle' => specialchars($objSubpages->pageTitle),
					'title' => specialchars($objSubpages->title),
					'link' => $objSubpages->title,
					'href' => $href,
					'alias' => $objSubpages->alias,
					'target' => (($objSubpages->type == 'redirect' && $objSubpages->target) ? ' window.open(this.href); return false;' : ''),
					'description' => str_replace(array("\n", "\r"), array(' ' , ''), $objSubpages->description),
					'accesskey' => $objSubpages->accesskey,
					'tabindex' => $objSubpages->tabindex
				);
			}
		}

		// Add classes first and last
		if (count($items))
		{
			$last = count($items) - 1;

			$items[0]['class'] = trim($items[0]['class'] . ' first');
			$items[$last]['class'] = trim($items[$last]['class'] . ' last');
		}

		$objTemplate->items = $items;
		return count($items) ? $objTemplate->parse() : '';
	}
}

?>