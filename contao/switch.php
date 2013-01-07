<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
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
 * Class PreviewSwitch
 *
 * Switch accounts in the front end preview.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class PreviewSwitch extends Backend
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
	}


	/**
	 * Run the controller and parse the template
	 */
	public function run()
	{
		$intUser = null;
		$strHash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? Environment::get('ip') : '') . 'FE_USER_AUTH');

		// Get the front end user
		if (FE_USER_LOGGED_IN)
		{
			$objUser = $this->Database->prepare("SELECT id FROM tl_member WHERE id=(SELECT pid FROM tl_session WHERE hash=?)")
									  ->limit(1)
									  ->execute($strHash);

			if ($objUser->numRows)
			{
				$intUser = $objUser->id;
			}
		}

		// Create the template object
		$this->Template = new BackendTemplate('be_switch');
		$this->Template->user = $intUser;
		$this->Template->show = Input::cookie('FE_PREVIEW');
		$this->Template->update = false;

		$time = time();

		// Switch
		if (Input::post('FORM_SUBMIT') == 'tl_switch')
		{
			// Hide unpublished elements
			if (Input::post('unpublished') == 'hide')
			{
				$this->setCookie('FE_PREVIEW', 0, ($time - 86400));
				$this->Template->show = 0;
			}

			// Show unpublished elements
			else
			{
				$this->setCookie('FE_PREVIEW', 1, ($time + $GLOBALS['TL_CONFIG']['sessionTimeout']));
				$this->Template->show = 1;
			}

			// Allow admins to switch user accounts
			if ($this->User->isAdmin)
			{
				// Remove old sessions
				$this->Database->prepare("DELETE FROM tl_session WHERE tstamp<? OR hash=?")
							   ->execute(($time - $GLOBALS['TL_CONFIG']['sessionTimeout']), $strHash);

			   // Log in the front end user
				if (is_numeric(Input::post('user')) && Input::post('user') > 0)
				{
					// Insert new session
					$this->Database->prepare("INSERT INTO tl_session (pid, tstamp, name, sessionID, ip, hash) VALUES (?, ?, ?, ?, ?, ?)")
								   ->execute(Input::post('user'), $time, 'FE_USER_AUTH', session_id(), Environment::get('ip'), $strHash);

					// Set cookie
					$this->setCookie('FE_USER_AUTH', $strHash, ($time + $GLOBALS['TL_CONFIG']['sessionTimeout']), null, null, false, true);
					$this->Template->user = Input::post('user');
				}

				// Log out the front end user
				else
				{
					// Remove cookie
					$this->setCookie('FE_USER_AUTH', $strHash, ($time - 86400), null, null, false, true);
					$this->Template->user = 0;
				}
			}

			$this->Template->update = true;
		}

		$arrUser = array(''=>'-');

		// Switch the user accounts
		if ($this->User->isAdmin)
		{
			// Get the active front end users
			$objUser = $this->Database->execute("SELECT id, username FROM tl_member WHERE login=1 AND disable!=1 AND (start='' OR start<$time) AND (stop='' OR stop>$time) ORDER BY username");

			while ($objUser->next())
			{
				$arrUser[$objUser->id] = $objUser->username . ' (' . $objUser->id . ')';
			}
		}

		// Default variables
		$this->Template->users = $arrUser;
		$this->Template->theme = $this->getTheme();
		$this->Template->base = Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->apply = $GLOBALS['TL_LANG']['MSC']['apply'];
		$this->Template->reload = $GLOBALS['TL_LANG']['MSC']['reload'];
		$this->Template->feUser = $GLOBALS['TL_LANG']['MSC']['feUser'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->lblHide = $GLOBALS['TL_LANG']['MSC']['hiddenHide'];
		$this->Template->lblShow = $GLOBALS['TL_LANG']['MSC']['hiddenShow'];
		$this->Template->fePreview = $GLOBALS['TL_LANG']['MSC']['fePreview'];
		$this->Template->hiddenElements = $GLOBALS['TL_LANG']['MSC']['hiddenElements'];
		$this->Template->closeSrc = TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/close.gif';
		$this->Template->action = ampersand(Environment::get('request'));
		$this->Template->isAdmin = $this->User->isAdmin;

		$GLOBALS['TL_CONFIG']['debugMode'] = false;
		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objPreviewSwitch = new PreviewSwitch();
$objPreviewSwitch->run();
