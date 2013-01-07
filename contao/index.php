<?php

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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class Index extends Backend
{

	/**
	 * Initialize the controller
	 * 
	 * 1. Import the user
	 * 2. Call the parent constructor
	 * 3. Login the user
	 * 4. Load the language files
	 * DO NOT CHANGE THIS ORDER!
	 */
	public function __construct()
	{
		// Redirect to the install tool
		if (!Config::getInstance()->isComplete())
		{
			$this->redirect('install.php');
		}

		$this->import('BackendUser', 'User');
		parent::__construct();

		// Login
		if ($this->User->login())
		{
			$strUrl = 'contao/main.php';

			// Redirect to the last page visited
			if ($this->Input->get('referer', true) != '')
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

		// Reload the page once after a logout to create a new session_id()
		elseif ($this->User->logout())
		{
			$this->reload();
		}

		$this->loadLanguageFile('default');
		$this->loadLanguageFile('tl_user');
	}


	/**
	 * Run the controller and parse the login template
	 */
	public function run()
	{
		$this->Template = new BackendTemplate('be_login');

		// Show a cookie warning
		if ($this->Input->get('referer', true) != '' && empty($_COOKIE))
		{
			$this->Template->noCookies = $GLOBALS['TL_LANG']['MSC']['noCookies'];
		}

		$this->Template->theme = $this->getTheme();
		$this->Template->messages = $this->getMessages();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->languages = $this->getBackendLanguages();
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->action = ampersand($this->Environment->request);
		$this->Template->userLanguage = $GLOBALS['TL_LANG']['tl_user']['language'][0];
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
		$this->Template->disableCron = $GLOBALS['TL_CONFIG']['disableCron'];
		$this->Template->ie6warning = sprintf($GLOBALS['TL_LANG']['ERR']['ie6warning'], '<a href="http://ie6countdown.com">', '</a>');

		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objIndex = new Index();
$objIndex->run();

?>