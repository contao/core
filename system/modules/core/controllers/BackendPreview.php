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
 * Set up the front end preview bar.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class BackendPreview extends Controller
{

	/**
	 * Initialize the controller
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}

	/**
	 * Genrate the preview bar and parse the template
	 *
	 * @return String
	 */
	public function generate()
	{
		$objUser = $this->getCurrentBackendUser();

		if ($objUser === null)
		{
			return;
		}

		if (Environment::get('isAjaxRequest') && $objUser->admin)
		{
			$this->getDatalistOptions($objUser);
		}

		// Create the template object
		$this->Template = new BackendTemplate('be_preview');
		$this->Template->user = $this->getCurrentMemberName();
		$this->Template->show = Input::cookie('FE_PREVIEW');
		$this->Template->update = false;

		// Switch
		if (Input::post('FORM_SUBMIT') == 'tl_switch')
		{
			$this->doFormSubmit($objUser);
		}

		// Default variables
		$this->Template->theme = Backend::getTheme();
		$this->Template->base = Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->apply = $GLOBALS['TL_LANG']['MSC']['apply'];
		$this->Template->reload = $GLOBALS['TL_LANG']['MSC']['reload'];
		$this->Template->feUser = $GLOBALS['TL_LANG']['MSC']['feUser'];
		$this->Template->username = $GLOBALS['TL_LANG']['MSC']['username'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->lblHide = $GLOBALS['TL_LANG']['MSC']['hiddenHide'];
		$this->Template->lblShow = $GLOBALS['TL_LANG']['MSC']['hiddenShow'];
		$this->Template->fePreview = $GLOBALS['TL_LANG']['MSC']['fePreview'];
		$this->Template->hiddenElements = $GLOBALS['TL_LANG']['MSC']['hiddenElements'];
		$this->Template->closeSrc = TL_FILES_URL . 'system/themes/' . Backend::getTheme() . '/images/close.gif';
		$this->Template->action = ampersand(Environment::get('request'));

		// Display member switch
		$this->Template->isAdmin = false;
		$objResult = $this->Database->execute("SELECT id FROM tl_member");
		if ($objResult->count() > 0)
		{
			$this->Template->isAdmin = $objUser->admin;
		}

		return $this->Template->parse();
	}

	/**
	 * Find ten matching usernames and return them as JSON
	 *
	 * @param \UserModel $objUser
	 */
	protected function getDatalistOptions($objUser)
	{
		if (!$objUser->admin)
		{
			header('HTTP/1.1 400 Bad Request');
			die('You must be an administrator to use the script');
		}

		$time = time();
		$arrUsers = array();

		// Get the active front end users
		$objUsers = $this->Database->prepare("SELECT username FROM tl_member WHERE username LIKE ? AND login=1 AND disable!=1 AND (start='' OR start<$time) AND (stop='' OR stop>$time) ORDER BY username")
			->limit(10)
			->execute(str_replace('%', '', Input::post('value')) . '%');

		if ($objUsers->numRows)
		{
			$arrUsers = $objUsers->fetchEach('username');
		}

		header('Content-type: application/json');
		die(json_encode($arrUsers));
	}

	/**
	 * get the current logged in back end user
	 *
	 * @return null|\UserModel
	 */
	protected function getCurrentBackendUser()
	{
		$strHash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? Environment::get('ip') : '') . 'BE_USER_AUTH');

		// Get the back end user
		$objSession = $this->Database->prepare("SELECT * FROM tl_session WHERE hash=? AND name='BE_USER_AUTH'")
			->limit(1)
			->execute($strHash);

		// Try to find the session in the database
		if ($objSession->numRows < 1)
		{
			$this->log('Could not find the session record', __METHOD__, TL_ACCESS);
			return null;
		}

		$time = time();
		$objSession = $objSession->fetchAssoc();

		// Validate the session
		if (
			$objSession['sessionID'] != session_id() || (!$GLOBALS['TL_CONFIG']['disableIpCheck'] && $objSession['ip'] != Environment::get('ip')) || $objSession['hash'] != $strHash || ($objSession['tstamp'] + $GLOBALS['TL_CONFIG']['sessionTimeout']) < $time
		)
		{
			$this->log('Could not verify the session', __METHOD__, TL_ACCESS);
			return null;
		}

		$objUser = \UserModel::findByPk($objSession['pid']);

		if ($objUser === null)
		{
			return null;
		}
		return $objUser;
	}

	/**
	 * get the current logged in front end member username
	 *
	 * @return String
	 */
	protected function getCurrentMemberName()
	{
		$strHash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? Environment::get('ip') : '') . 'FE_USER_AUTH');

		// Get the front end user
		if (FE_USER_LOGGED_IN)
		{
			$objMember = $this->Database->prepare("SELECT username FROM tl_member WHERE id=(SELECT pid FROM tl_session WHERE hash=?)")
				->limit(1)
				->execute($strHash);

			if ($objMember->numRows)
			{
				return $objMember->username;
			}
		}
		return '';
	}

	/**
	 * Do the preview form submit
	 *
	 * @param \UserModel $objUser
	 */
	public function doFormSubmit($objUser)
	{
		$time = time();
		$strHash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? Environment::get('ip') : '') . 'FE_USER_AUTH');

		// Hide unpublished elements
		if (Input::post('preview_unpublished') == 'hide')
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
		if ($objUser->admin)
		{
			// Remove old sessions
			$this->Database->prepare("DELETE FROM tl_session WHERE tstamp<? OR hash=?")
				->execute(($time - $GLOBALS['TL_CONFIG']['sessionTimeout']), $strHash);

			// Log in the front end user
			if (Input::post('preview_user') != '')
			{
				if (($objMember = MemberModel::findByUsername(Input::post('preview_user'))) !== null)
				{
					// Insert the new session
					$this->Database->prepare("INSERT INTO tl_session (pid, tstamp, name, sessionID, ip, hash) VALUES (?, ?, ?, ?, ?, ?)")
						->execute($objMember->id, $time, 'FE_USER_AUTH', session_id(), Environment::get('ip'), $strHash);

					// Set the cookie
					$this->setCookie('FE_USER_AUTH', $strHash, ($time + $GLOBALS['TL_CONFIG']['sessionTimeout']), null, null, false, true);
					$this->Template->user = Input::post('preview_user');
				}
			}

			// Log out the front end user
			else
			{
				// Remove cookie
				$this->setCookie('FE_USER_AUTH', $strHash, ($time - 86400), null, null, false, true);
				$this->Template->user = '';
			}
		}
		$this->reload();
	}

}
