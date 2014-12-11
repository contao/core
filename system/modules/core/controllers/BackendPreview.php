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
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class BackendPreview
 *
 * Set up the front end preview frames.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
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
		$this->Template = new \BackendTemplate('be_preview');

		$this->Template->base = \Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['fePreview']);
		$this->Template->charset = \Config::get('characterSet');
		$this->Template->site = \Input::get('site', true);

		if (\Input::get('url'))
		{
			$this->Template->url = \Environment::get('base') . \Input::get('url');
		}
		elseif (\Input::get('page'))
		{
			$this->Template->url = $this->redirectToFrontendPage(\Input::get('page'), \Input::get('article'), true);
		}
		else
		{
			$this->Template->url = \Environment::get('base');
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
				$this->Template->user = \Input::post('user');
			}
		}

		\Config::set('debugMode', false);
		$this->Template->output();
	}
}
