<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once '../system/initialize.php';


/**
 * Class Index
 *
 * Handle back end logins and logouts.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
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
		$this->import('BackendUser', 'User');
		parent::__construct();

		// Login
		if ($this->User->login())
		{
			$strUrl = 'contao/main.php';

			// Redirect to the last page visited
			if (Input::get('referer', true) != '')
			{
				$strUrl = base64_decode(Input::get('referer', true));
			}

			$this->redirect($strUrl);
		}

		// Reload the page if authentication fails
		elseif (!empty($_POST['username']) && !empty($_POST['password']))
		{
			$this->reload();
		}

		// Reload the page once after a logout to create a new session_id()
		elseif ($this->User->logout())
		{
			$this->reload();
		}

		System::loadLanguageFile('default');
		System::loadLanguageFile('tl_user');
	}


	/**
	 * Run the controller and parse the login template
	 */
	public function run()
	{
		$this->Template = new BackendTemplate('be_login');

		// Show a cookie warning
		if (Input::get('referer', true) != '' && empty($_COOKIE))
		{
			$this->Template->noCookies = $GLOBALS['TL_LANG']['MSC']['noCookies'];
		}

		$strHeadline = sprintf($GLOBALS['TL_LANG']['MSC']['loginTo'], $GLOBALS['TL_CONFIG']['websiteTitle']);

		$this->Template->theme = Backend::getTheme();
		$this->Template->messages = Message::generate();
		$this->Template->base = Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->languages = System::getLanguages(true);
		$this->Template->title = specialchars($strHeadline);
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->action = ampersand(Environment::get('request'));
		$this->Template->userLanguage = $GLOBALS['TL_LANG']['tl_user']['language'][0];
		$this->Template->headline = $strHeadline;
		$this->Template->curLanguage = Input::post('language') ?: str_replace('-', '_', $GLOBALS['TL_LANGUAGE']);
		$this->Template->curUsername = Input::post('username') ?: '';
		$this->Template->uClass = ($_POST && empty($_POST['username'])) ? ' class="login_error"' : '';
		$this->Template->pClass = ($_POST && empty($_POST['password'])) ? ' class="login_error"' : '';
		$this->Template->loginButton = specialchars($GLOBALS['TL_LANG']['MSC']['loginBT']);
		$this->Template->username = $GLOBALS['TL_LANG']['tl_user']['username'][0];
		$this->Template->password = $GLOBALS['TL_LANG']['MSC']['password'][0];
		$this->Template->feLink = $GLOBALS['TL_LANG']['MSC']['feLink'];
		$this->Template->frontendFile = Environment::get('base');
		$this->Template->disableCron = $GLOBALS['TL_CONFIG']['disableCron'];
		$this->Template->ie6warning = sprintf($GLOBALS['TL_LANG']['ERR']['ie6warning'], '<a href="http://ie6countdown.com">', '</a>');
		$this->Template->default = $GLOBALS['TL_LANG']['MSC']['default'];

		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objIndex = new Index();
$objIndex->run();
