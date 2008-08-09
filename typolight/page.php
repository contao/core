<?php
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
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once('../system/initialize.php');


/**
 * Class PagePicker
 *
 * Back end page picker.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class PagePicker extends Backend
{

	/**
	 * Initialize the controller
	 * 
	 * 1. Import user
	 * 2. Call parent constructor
	 * 3. Authenticate user
	 * 4. Load language files
	 * DO NOT CHANGE THIS ORDER!
	 */
	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct();

		$this->User->authenticate();
		$this->loadLanguageFile('default');
	}


	/**
	 * Run controller and parse the template
	 */
	public function run()
	{
		$this->Template = new BackendTemplate('be_pagepicker');

		$this->Template->theme = $this->getTheme();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->headline = $GLOBALS['TL_LANG']['MSC']['ppHeadline'];
		$this->Template->isMac = preg_match('/mac/i', $this->Environment->httpUserAgent);
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->href = $this->Input->get('href');
		$this->Template->options = $this->createOptions();

		$this->Template->output();
	}


	/**
	 * Get all pages from the current root page
	 * @param integer
	 * @param integer
	 * @return array
	 */
	private function createOptions($intId=0, $level=-1)
	{
		$objPages = $this->Database->prepare("SELECT id, title FROM tl_page WHERE pid=? ORDER BY sorting")
								   ->execute($intId);

		if ($objPages->numRows < 1)
		{
			return '';
		}

		++$level;
		$strOptions = '';

		while ($objPages->next())
		{
			$strOptions .= sprintf('<option value="{{link_url::%s}}"%s>%s%s</option>', $objPages->id, (($this->Input->get('href') == '{{link_url::' . $objPages->id . '}}') ? ' selected="selected"' : ''), str_repeat("&nbsp;", (2 * $level)), specialchars($objPages->title));
			$strOptions .= $this->createOptions($objPages->id, $level);
		}

		return $strOptions;
	}
}


/**
 * Instantiate controller
 */
$objPagePicker = new PagePicker();
$objPagePicker->run();

?>