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
 * Set up the front end preview frames.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class BackendPreview extends \Backend
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
		\System::loadLanguageFile('default');
	}


	/**
	 * Run the controller and parse the template
	 */
	public function run()
	{
		/** @var \BackendTemplate|object $objTemplate */
		$objTemplate = new \BackendTemplate('be_preview');

		$objTemplate->base = \Environment::get('base');
		$objTemplate->language = $GLOBALS['TL_LANGUAGE'];
		$objTemplate->title = specialchars($GLOBALS['TL_LANG']['MSC']['fePreview']);
		$objTemplate->charset = \Config::get('characterSet');
		$objTemplate->site = \Input::get('site', true);

		if (\Input::get('url'))
		{
			$objTemplate->url = \Environment::get('base') . \Input::get('url');
		}
		elseif (\Input::get('page'))
		{
			$objTemplate->url = $this->redirectToFrontendPage(\Input::get('page'), \Input::get('article'), true);
		}
		else
		{
			$objTemplate->url = \Environment::get('base');
		}

		// Switch to a particular member (see #6546)
		if (\Input::get('user') && $this->User->isAdmin)
		{
			$objUser = \MemberModel::findByUsername(\Input::get('user'));

			if ($objUser !== null)
			{
				$strHash = sha1(session_id() . (!\Config::get('disableIpCheck') ? \Environment::get('ip') : '') . 'FE_USER_AUTH');

				// Remove old sessions
				$this->Database->prepare("DELETE FROM tl_session WHERE tstamp<? OR hash=?")
							   ->execute((time() - \Config::get('sessionTimeout')), $strHash);

				// Insert the new session
				$this->Database->prepare("INSERT INTO tl_session (pid, tstamp, name, sessionID, ip, hash) VALUES (?, ?, ?, ?, ?, ?)")
							   ->execute($objUser->id, time(), 'FE_USER_AUTH', session_id(), \Environment::get('ip'), $strHash);

				// Set the cookie
				$this->setCookie('FE_USER_AUTH', $strHash, (time() + \Config::get('sessionTimeout')), null, null, false, true);
				$objTemplate->user = \Input::post('user');
			}
		}

		\Config::set('debugMode', false);
		$objTemplate->output();
	}
}
