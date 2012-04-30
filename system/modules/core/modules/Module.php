<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Frontend
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \Frontend, \FrontendTemplate, \Input, \Model, \Model_Collection, \ModuleSitemap, \PageModel, \String;


/**
 * Class Module
 *
 * Parent class for front end modules.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
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
	 * Column
	 * @var string
	 */
	protected $strColumn;

	/**
	 * Model
	 * @var Model
	 */
	protected $objModel;

	/**
	 * Current record
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * Style array
	 * @var array
	 */
	protected $arrStyle = array();


	/**
	 * Initialize the object
	 * @param object
	 * @param string
	 */
	public function __construct($objModule, $strColumn='main')
	{
		if ($objModule instanceof Model || $objModule instanceof Model_Collection)
		{
			$this->objModel = $objModule;
		}

		parent::__construct();

		$this->arrData = $objModule->row();
		$this->space = deserialize($objModule->space);
		$this->cssID = deserialize($objModule->cssID, true);

		$arrHeadline = deserialize($objModule->headline);
		$this->headline = is_array($arrHeadline) ? $arrHeadline['value'] : $arrHeadline;
		$this->hl = is_array($arrHeadline) ? $arrHeadline['unit'] : 'h1';
		$this->strColumn = $strColumn;
	}


	/**
	 * Set an object property
	 * @param string
	 * @param mixed
	 * @return void
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
		if (isset($this->arrData[$strKey]))
		{
			return $this->arrData[$strKey];
		}

		return parent::__get($strKey);
	}


	/**
	 * Check whether a property is set
	 * @param string
	 * @return boolean
	 */
	public function __isset($strKey)
	{
		return isset($this->arrData[$strKey]);
	}


	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		if ($this->arrData['space'][0] != '')
		{
			$this->arrStyle[] = 'margin-top:'.$this->arrData['space'][0].'px;';
		}

		if ($this->arrData['space'][1] != '')
		{
			$this->arrStyle[] = 'margin-bottom:'.$this->arrData['space'][1].'px;';
		}

		$this->Template = new FrontendTemplate($this->strTemplate);
		$this->Template->setData($this->arrData);

		$this->compile();

		$this->Template->inColumn = $this->strColumn;
		$this->Template->style = !empty($this->arrStyle) ? implode(' ', $this->arrStyle) : '';
		$this->Template->cssID = ($this->cssID[0] != '') ? ' id="' . $this->cssID[0] . '"' : '';
		$this->Template->class = trim('mod_' . $this->type . ' ' . $this->cssID[1]);

		if ($this->Template->headline == '')
		{
			$this->Template->headline = $this->headline;
		}

		if ($this->Template->hl == '')
		{
			$this->Template->hl = $this->hl;
		}

		return $this->Template->parse();
	}


	/**
	 * Compile the current element
	 * @return void
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
		// Get all active subpages
		$objSubpages = PageModel::findPublishedSubpagesWithoutGuestsByPid($pid, $this->showHidden, $this instanceof ModuleSitemap);

		if ($objSubpages === null)
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

		// Layout template fallback
		if ($this->navigationTpl == '')
		{
			$this->navigationTpl = 'nav_default';
		}

		$objTemplate = new FrontendTemplate($this->navigationTpl);

		$objTemplate->type = get_class($this);
		$objTemplate->level = 'level_' . $level++;

		// Get page object
		global $objPage;

		// Browse subpages
		while($objSubpages->next())
		{
			// Skip hidden sitemap pages
			if ($this instanceof ModuleSitemap && $objSubpages->sitemap == 'map_never')
			{
				continue;
			}

			$subitems = '';
			$_groups = deserialize($objSubpages->groups);

			// Do not show protected pages unless a back end or front end user is logged in
			if (!$objSubpages->protected || BE_USER_LOGGED_IN || (is_array($_groups) && count(array_intersect($_groups, $groups))) || $this->showProtected || ($this instanceof ModuleSitemap && $objSubpages->sitemap == 'map_always'))
			{
				// Check whether there will be subpages
				if ($objSubpages->subpages > 0 && (!$this->showLevel || $this->showLevel >= $level || (!$this->hardLimit && ($objPage->id == $objSubpages->id || in_array($objPage->id, $this->getChildRecords($objSubpages->id, 'tl_page'))))))
				{
					$subitems = $this->renderNavigation($objSubpages->id, $level);
				}

				// Get href
				switch ($objSubpages->type)
				{
					case 'redirect':
						$href = $objSubpages->url;

						if (strncasecmp($href, 'mailto:', 7) === 0)
						{
							$href = String::encodeEmail($href);
						}
						break;

					case 'forward':
						if ($objSubpages->jumpTo)
						{
							$objNext = PageModel::findPublishedById($objSubpages->jumpTo);
						}
						else
						{
							$objNext = PageModel::findFirstPublishedRegularByPid($objSubpages->id);
						}

						if ($objNext !== null)
						{
							$href = $this->generateFrontendUrl($objNext->row());
							break;
						}
						// DO NOT ADD A break; STATEMENT

					default:
						$href = $this->generateFrontendUrl($objSubpages->row());
						break;
				}

				// Active page
				if (($objPage->id == $objSubpages->id || $objSubpages->type == 'forward' && $objPage->id == $objSubpages->jumpTo) && !$this instanceof ModuleSitemap && !Input::get('articles'))
				{
					$strClass = (($subitems != '') ? 'submenu' : '') . ($objSubpages->protected ? ' protected' : '') . (($objSubpages->cssClass != '') ? ' ' . $objSubpages->cssClass : '');
					$row = $objSubpages->row();

					$row['isActive'] = true;
					$row['subitems'] = $subitems;
					$row['class'] = trim($strClass);
					$row['title'] = specialchars($objSubpages->title, true);
					$row['pageTitle'] = specialchars($objSubpages->pageTitle, true);
					$row['link'] = $objSubpages->title;
					$row['href'] = $href;
					$row['nofollow'] = (strncmp($objSubpages->robots, 'noindex', 7) === 0);
					$row['target'] = '';
					$row['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $objSubpages->description);

					// Override the link target
					if ($objSubpages->type == 'redirect' && $objSubpages->target)
					{
						$row['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="window.open(this.href);return false"' : ' target="_blank"';
					}

					$items[] = $row;
				}

				// Regular page
				else
				{
					$strClass = (($subitems != '') ? 'submenu' : '') . ($objSubpages->protected ? ' protected' : '') . (($objSubpages->cssClass != '') ? ' ' . $objSubpages->cssClass : '') . (in_array($objSubpages->id, $objPage->trail) ? ' trail' : '');

					// Mark pages on the same level (see #2419)
					if ($objSubpages->pid == $objPage->pid)
					{
						$strClass .= ' sibling';
					}

					$row = $objSubpages->row();

					$row['isActive'] = false;
					$row['subitems'] = $subitems;
					$row['class'] = trim($strClass);
					$row['title'] = specialchars($objSubpages->title, true);
					$row['pageTitle'] = specialchars($objSubpages->pageTitle, true);
					$row['link'] = $objSubpages->title;
					$row['href'] = $href;
					$row['nofollow'] = (strncmp($objSubpages->robots, 'noindex', 7) === 0);
					$row['target'] = '';
					$row['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $objSubpages->description);

					// Override the link target
					if ($objSubpages->type == 'redirect' && $objSubpages->target)
					{
						$row['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="window.open(this.href);return false"' : ' target="_blank"';
					}

					$items[] = $row;
				}
			}
		}

		// Add classes first and last
		if (!empty($items))
		{
			$last = count($items) - 1;

			$items[0]['class'] = trim($items[0]['class'] . ' first');
			$items[$last]['class'] = trim($items[$last]['class'] . ' last');
		}

		$objTemplate->items = $items;
		return !empty($items) ? $objTemplate->parse() : '';
	}
}
