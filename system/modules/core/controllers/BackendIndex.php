<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Handle back end logins and logouts.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class BackendIndex extends \Backend
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
			if (\Input::get('referer', true) != '')
			{
				$strUrl = base64_decode(\Input::get('referer', true));
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

		\System::loadLanguageFile('default');
		\System::loadLanguageFile('tl_user');
	}


	/**
	 * Run the controller and parse the login template
	 */
	public function run()
	{
		/** @var \BackendTemplate|object $objTemplate */
		$objTemplate = new \BackendTemplate('be_login');

		// Show a cookie warning
		if (\Input::get('referer', true) != '' && empty($_COOKIE))
		{
			$objTemplate->noCookies = $GLOBALS['TL_LANG']['MSC']['noCookies'];
		}

		$strHeadline = sprintf($GLOBALS['TL_LANG']['MSC']['loginTo'], \Config::get('websiteTitle'));

		$objTemplate->theme = \Backend::getTheme();
		$objTemplate->messages = \Message::generate();
		$objTemplate->base = \Environment::get('base');
		$objTemplate->language = $GLOBALS['TL_LANGUAGE'];
		$objTemplate->languages = \System::getLanguages(true);
		$objTemplate->title = specialchars($strHeadline);
		$objTemplate->charset = \Config::get('characterSet');
		$objTemplate->action = ampersand(\Environment::get('request'));
		$objTemplate->userLanguage = $GLOBALS['TL_LANG']['tl_user']['language'][0];
		$objTemplate->headline = $strHeadline;
		$objTemplate->curLanguage = \Input::post('language') ?: str_replace('-', '_', $GLOBALS['TL_LANGUAGE']);
		$objTemplate->curUsername = \Input::post('username') ?: '';
		$objTemplate->uClass = ($_POST && empty($_POST['username'])) ? ' class="login_error"' : '';
		$objTemplate->pClass = ($_POST && empty($_POST['password'])) ? ' class="login_error"' : '';
		$objTemplate->loginButton = specialchars($GLOBALS['TL_LANG']['MSC']['loginBT']);
		$objTemplate->username = $GLOBALS['TL_LANG']['tl_user']['username'][0];
		$objTemplate->password = $GLOBALS['TL_LANG']['MSC']['password'][0];
		$objTemplate->feLink = $GLOBALS['TL_LANG']['MSC']['feLink'];
		$objTemplate->frontendFile = \Environment::get('base');
		$objTemplate->disableCron = \Config::get('disableCron');
		$objTemplate->ie6warning = sprintf($GLOBALS['TL_LANG']['ERR']['ie6warning'], '<a href="http://ie6countdown.com">', '</a>');
		$objTemplate->default = $GLOBALS['TL_LANG']['MSC']['default'];

		$objTemplate->output();
	}
}
