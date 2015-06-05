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
 * Switch accounts in the front end preview.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class BackendSwitch extends \Backend
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
		if (\Environment::get('isAjaxRequest'))
		{
			$this->getDatalistOptions();
		}

		$strUser = '';
		$strHash = sha1(session_id() . (!\Config::get('disableIpCheck') ? \Environment::get('ip') : '') . 'FE_USER_AUTH');

		// Get the front end user
		if (FE_USER_LOGGED_IN)
		{
			$objUser = $this->Database->prepare("SELECT username FROM tl_member WHERE id=(SELECT pid FROM tl_session WHERE hash=?)")
									  ->limit(1)
									  ->execute($strHash);

			if ($objUser->numRows)
			{
				$strUser = $objUser->username;
			}
		}

		/** @var \BackendTemplate|object $objTemplate */
		$objTemplate = new \BackendTemplate('be_switch');
		$objTemplate->user = $strUser;
		$objTemplate->show = \Input::cookie('FE_PREVIEW');
		$objTemplate->update = false;

		// Switch
		if (\Input::post('FORM_SUBMIT') == 'tl_switch')
		{
			$time = time();

			// Hide unpublished elements
			if (\Input::post('unpublished') == 'hide')
			{
				$this->setCookie('FE_PREVIEW', 0, ($time - 86400));
				$objTemplate->show = 0;
			}

			// Show unpublished elements
			else
			{
				$this->setCookie('FE_PREVIEW', 1, ($time + \Config::get('sessionTimeout')));
				$objTemplate->show = 1;
			}

			// Allow admins to switch user accounts
			if ($this->User->isAdmin)
			{
				// Remove old sessions
				$this->Database->prepare("DELETE FROM tl_session WHERE tstamp<? OR hash=?")
							   ->execute(($time - \Config::get('sessionTimeout')), $strHash);

			   // Log in the front end user
				if (\Input::post('user'))
				{
					$objUser = \MemberModel::findByUsername(\Input::post('user'));

					if ($objUser !== null)
					{
						// Insert the new session
						$this->Database->prepare("INSERT INTO tl_session (pid, tstamp, name, sessionID, ip, hash) VALUES (?, ?, ?, ?, ?, ?)")
									   ->execute($objUser->id, $time, 'FE_USER_AUTH', session_id(), \Environment::get('ip'), $strHash);

						// Set the cookie
						$this->setCookie('FE_USER_AUTH', $strHash, ($time + \Config::get('sessionTimeout')), null, null, false, true);
						$objTemplate->user = \Input::post('user');
					}
				}

				// Log out the front end user
				else
				{
					// Remove cookie
					$this->setCookie('FE_USER_AUTH', $strHash, ($time - 86400), null, null, false, true);
					$objTemplate->user = '';
				}
			}

			$objTemplate->update = true;
		}

		// Default variables
		$objTemplate->theme = \Backend::getTheme();
		$objTemplate->base = \Environment::get('base');
		$objTemplate->language = $GLOBALS['TL_LANGUAGE'];
		$objTemplate->apply = $GLOBALS['TL_LANG']['MSC']['apply'];
		$objTemplate->reload = $GLOBALS['TL_LANG']['MSC']['reload'];
		$objTemplate->feUser = $GLOBALS['TL_LANG']['MSC']['feUser'];
		$objTemplate->username = $GLOBALS['TL_LANG']['MSC']['username'];
		$objTemplate->charset = \Config::get('characterSet');
		$objTemplate->lblHide = $GLOBALS['TL_LANG']['MSC']['hiddenHide'];
		$objTemplate->lblShow = $GLOBALS['TL_LANG']['MSC']['hiddenShow'];
		$objTemplate->fePreview = $GLOBALS['TL_LANG']['MSC']['fePreview'];
		$objTemplate->hiddenElements = $GLOBALS['TL_LANG']['MSC']['hiddenElements'];
		$objTemplate->closeSrc = TL_FILES_URL . 'system/themes/' . \Backend::getTheme() . '/images/close.gif';
		$objTemplate->action = ampersand(\Environment::get('request'));
		$objTemplate->isAdmin = $this->User->isAdmin;

		\Config::set('debugMode', false);
		$objTemplate->output();
	}


	/**
	 * Find ten matching usernames and return them as JSON
	 */
	protected function getDatalistOptions()
	{
		if (!$this->User->isAdmin)
		{
			header('HTTP/1.1 400 Bad Request');
			die('You must be an administrator to use the script');
		}

		$arrUsers = array();
		$time = \Date::floorToMinute();

		// Get the active front end users
		$objUsers = $this->Database->prepare("SELECT username FROM tl_member WHERE username LIKE ? AND login='1' AND disable!='1' AND (start='' OR start<='$time') AND (stop='' OR stop>'" . ($time + 60) . "') ORDER BY username")
								   ->limit(10)
								   ->execute(str_replace('%', '', \Input::post('value')) . '%');

		if ($objUsers->numRows)
		{
			$arrUsers = $objUsers->fetchEach('username');
		}

		header('Content-type: application/json');
		die(json_encode($arrUsers));
	}
}
