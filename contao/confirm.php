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
 * @package    Backend
 * @license    LGPL
 */


/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once '../system/initialize.php';


/**
 * Class Confirm
 *
 * Confirm an invalid token URL.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class Confirm extends Backend
{

	/**
	 * Initialize the controller
	 * 
	 * 1. Import the user
	 * 2. Call the parent constructor
	 * 3. Authenticate the user
	 * 4. Load the language files
	 * DO NOT CHANGE THIS ORDER!
	 */
	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct();

		$this->User->authenticate();

		$this->loadLanguageFile('default');
		$this->loadLanguageFile('modules');
	}


	/**
	 * Run the controller
	 */
	public function run()
	{
		// Redirect to the back end home page
		if ($this->Input->post('FORM_SUBMIT') == 'invalid_token_url')
		{
			list($strUrl) = explode('?', $this->Session->get('INVALID_TOKEN_URL'));
			$this->redirect($strUrl);
		}

		$this->Template = new BackendTemplate('be_confirm');

		// Prepare the URL
		$url = preg_replace('/(\?|&)rt=[^&]*/', '', $this->Session->get('INVALID_TOKEN_URL'));
		$this->Template->href = $url . ((strpos($url, '?') !== false) ? '&rt=' : '?rt=') . REQUEST_TOKEN;

		// Tempalte variables
		$this->Template->confirm = true;
		$this->Template->link = specialchars($this->Session->get('INVALID_TOKEN_URL'));
		$this->Template->h2 = $GLOBALS['TL_LANG']['MSC']['invalidTokenUrl'];
		$this->Template->explain = $GLOBALS['TL_LANG']['ERR']['invalidTokenUrl'];
		$this->Template->cancel = $GLOBALS['TL_LANG']['MSC']['cancelBT'];
		$this->Template->continue = $GLOBALS['TL_LANG']['MSC']['continue'];
		$this->Template->theme = $this->getTheme();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];

		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objConfirm = new Confirm();
$objConfirm->run();
