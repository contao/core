<?php

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
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
 * Class Index
 *
 * Back end login controller.
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Controller
 */
class Index extends Backend
{

	/**
	 * Initialize the controller
	 * 
	 * 1. Import user
	 * 2. Call parent constructor
	 * 3. Login user
	 * 4. Load language files
	 * DO NOT CHANGE THIS ORDER!
	 */
	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct();

		// Login
		if ($this->User->login())
		{
			$strUrl = 'typolight/main.php';

			// Redirect to last page visited
			if (strlen($this->Input->get('referer', true)))
			{
				$strUrl = base64_decode($this->Input->get('referer', true));
			}

			$this->redirect($strUrl);
		}

		// Reload the page if authentication fails
		elseif ($this->Input->post('username') && $this->Input->post('password'))
		{
			$this->reload();
		}

		$this->User->logout();

		$this->loadLanguageFile('default');
		$this->loadLanguageFile('tl_user');
	}


	/**
	 * Reload the page once after a logout to create a new session_id()
	 */
	public function __destruct()
	{
		if (!strlen(session_id()))
		{
			$this->reload();
		}
	}


	/**
	 * Run controller and parse the login template
	 */
	public function run()
	{
		$this->Template = new BackendTemplate('be_login');

		$this->Template->theme = $this->getTheme();
		$this->Template->messages = $this->getMessages();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->languages = $this->getBackendLanguages();
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->action = ampersand($this->Environment->request);
		$this->Template->userLanguage = $GLOBALS['TL_LANG']['tl_user']['language'][0];
		$this->Template->isMac = preg_match('/mac/i', $this->Environment->httpUserAgent);
		$this->Template->headline = sprintf($GLOBALS['TL_LANG']['MSC']['loginTo'], $GLOBALS['TL_CONFIG']['websiteTitle']);
		$this->Template->curLanguage = $this->Input->post('language') ? $this->Input->post('language') : $GLOBALS['TL_LANGUAGE'];
		$this->Template->curUsername = $this->Input->post('username') ? $this->Input->post('username') : '';
		$this->Template->uClass = ($_POST && !$this->Input->post('username')) ? ' class="login_error"' : '';
		$this->Template->pClass = ($_POST && !$this->Input->post('password')) ? ' class="login_error"' : '';
		$this->Template->loginButton = specialchars($GLOBALS['TL_LANG']['MSC']['loginBT']);
		$this->Template->username = $GLOBALS['TL_LANG']['tl_user']['username'][0];
		$this->Template->password = $GLOBALS['TL_LANG']['MSC']['password'][0];
		$this->Template->feLink = $GLOBALS['TL_LANG']['MSC']['feLink'];
		$this->Template->frontendFile = $this->Environment->base;

		$this->Template->output();
	}
}


/**
 * Instantiate controller
 */
$objIndex = new Index();
$objIndex->run();

?>