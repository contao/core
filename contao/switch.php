<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
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
 * Class PreviewSwitch
 *
 * Switch accounts in the front end preview.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class PreviewSwitch extends Backend
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
		$intUser = null;
		$strHash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? $this->Environment->ip : '') . 'FE_USER_AUTH');

		// Get front end user
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

		// Create template object
		$this->Template = new BackendTemplate('be_switch');

		$this->Template->user = $intUser;
		$this->Template->show = $this->Input->cookie('FE_PREVIEW');
		$this->Template->update = false;

		$time = time();

		// Switch
		if ($this->Input->post('FORM_SUBMIT') == 'tl_switch')
		{
			// Hide unpublished elements
			if ($this->Input->post('unpublished') == 'hide')
			{
				$this->setCookie('FE_PREVIEW', 0, ($time - 86400), $GLOBALS['TL_CONFIG']['websitePath']);
				$this->Template->show = 0;
			}

			// Show unpublished elements
			else
			{
				$this->setCookie('FE_PREVIEW', 1, ($time + $GLOBALS['TL_CONFIG']['sessionTimeout']), $GLOBALS['TL_CONFIG']['websitePath']);
				$this->Template->show = 1;
			}

			// Allow admins to switch user accounts
			if ($this->User->isAdmin)
			{
				// Remove old sessions
				$this->Database->prepare("DELETE FROM tl_session WHERE tstamp<? OR hash=?")
							   ->execute(($time - $GLOBALS['TL_CONFIG']['sessionTimeout']), $strHash);

			   // Log in front end user
				if (is_numeric($this->Input->post('user')) && $this->Input->post('user') > 0)
				{
					// Insert new session
					$this->Database->prepare("INSERT INTO tl_session (pid, tstamp, name, sessionID, ip, hash) VALUES (?, ?, ?, ?, ?, ?)")
								   ->execute($this->Input->post('user'), $time, 'FE_USER_AUTH', session_id(), $this->Environment->ip, $strHash);

					// Set cookie
					$this->setCookie('FE_USER_AUTH', $strHash, ($time + $GLOBALS['TL_CONFIG']['sessionTimeout']), $GLOBALS['TL_CONFIG']['websitePath']);
					$this->Template->user = $this->Input->post('user');
				}

				// Log out front end user
				else
				{
					// Remove cookie
					$this->setCookie('FE_USER_AUTH', $strHash, ($time - 86400), $GLOBALS['TL_CONFIG']['websitePath']);
					$this->Template->user = 0;
				}
			}

			$this->Template->update = true;
		}

		// Switch user accounts
		if ($this->User->isAdmin)
		{
			$arrUser = array(''=>'-');

			// Get active front end users
			$objUser = $this->Database->execute("SELECT id, username FROM tl_member WHERE login=1 AND disable!=1 AND (start='' OR start<$time) AND (stop='' OR stop>$time) ORDER BY username");

			while ($objUser->next())
			{
				$arrUser[$objUser->id] = $objUser->username . ' (' . $objUser->id . ')';
			}
		}

		// Default variables
		$this->Template->users = $arrUser;
		$this->Template->theme = $this->getTheme();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->apply = $GLOBALS['TL_LANG']['MSC']['apply'];
		$this->Template->reload = $GLOBALS['TL_LANG']['MSC']['reload'];
		$this->Template->feUser = $GLOBALS['TL_LANG']['MSC']['feUser'];
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->lblHide = $GLOBALS['TL_LANG']['MSC']['hiddenHide'];
		$this->Template->lblShow = $GLOBALS['TL_LANG']['MSC']['hiddenShow'];
		$this->Template->fePreview = $GLOBALS['TL_LANG']['MSC']['fePreview'];
		$this->Template->hiddenElements = $GLOBALS['TL_LANG']['MSC']['hiddenElements'];
		$this->Template->closeSrc = TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/close.gif';
		$this->Template->action = ampersand($this->Environment->request);
		$this->Template->isAdmin = $this->User->isAdmin;

		$this->Template->output();
	}
}


/**
 * Instantiate controller
 */
$objPreviewSwitch = new PreviewSwitch();
$objPreviewSwitch->run();

?>