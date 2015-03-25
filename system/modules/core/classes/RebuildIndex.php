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
 * Maintenance module "rebuild index".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class RebuildIndex extends \Backend implements \executable
{

	/**
	 * Return true if the module is active
	 *
	 * @return boolean
	 */
	public function isActive()
	{
		return (\Config::get('enableSearch') && \Input::get('act') == 'index');
	}


	/**
	 * Generate the module
	 *
	 * @return string
	 */
	public function run()
	{
		if (!\Config::get('enableSearch'))
		{
			return '';
		}

		$time = time();

		/** @var \BackendTemplate|object $objTemplate */
		$objTemplate = new \BackendTemplate('be_rebuild_index');
		$objTemplate->action = ampersand(\Environment::get('request'));
		$objTemplate->indexHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'];
		$objTemplate->isActive = $this->isActive();

		// Add the error message
		if ($_SESSION['REBUILD_INDEX_ERROR'] != '')
		{
			$objTemplate->indexMessage = $_SESSION['REBUILD_INDEX_ERROR'];
			$_SESSION['REBUILD_INDEX_ERROR'] = '';
		}

		// Rebuild the index
		if (\Input::get('act') == 'index')
		{
			// Check the request token (see #4007)
			if (!isset($_GET['rt']) || !\RequestToken::validate(\Input::get('rt')))
			{
				$this->Session->set('INVALID_TOKEN_URL', \Environment::get('request'));
				$this->redirect('contao/confirm.php');
			}

			$arrPages = $this->findSearchablePages();

			// HOOK: take additional pages
			if (isset($GLOBALS['TL_HOOKS']['getSearchablePages']) && is_array($GLOBALS['TL_HOOKS']['getSearchablePages']))
			{
				foreach ($GLOBALS['TL_HOOKS']['getSearchablePages'] as $callback)
				{
					$this->import($callback[0]);
					$arrPages = $this->$callback[0]->$callback[1]($arrPages);
				}
			}

			// Return if there are no pages
			if (empty($arrPages))
			{
				$_SESSION['REBUILD_INDEX_ERROR'] = $GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'];
				$this->redirect($this->getReferer());
			}

			// Truncate the search tables
			$this->import('Automator');
			$this->Automator->purgeSearchTables();

			// Hide unpublished elements
			$this->setCookie('FE_PREVIEW', 0, ($time - 86400));

			// Calculate the hash
			$strHash = sha1(session_id() . (!\Config::get('disableIpCheck') ? \Environment::get('ip') : '') . 'FE_USER_AUTH');

			// Remove old sessions
			$this->Database->prepare("DELETE FROM tl_session WHERE tstamp<? OR hash=?")
						   ->execute(($time - \Config::get('sessionTimeout')), $strHash);

			// Log in the front end user
			if (is_numeric(\Input::get('user')) && \Input::get('user') > 0)
			{
				// Insert a new session
				$this->Database->prepare("INSERT INTO tl_session (pid, tstamp, name, sessionID, ip, hash) VALUES (?, ?, ?, ?, ?, ?)")
							   ->execute(\Input::get('user'), $time, 'FE_USER_AUTH', session_id(), \Environment::get('ip'), $strHash);

				// Set the cookie
				$this->setCookie('FE_USER_AUTH', $strHash, ($time + \Config::get('sessionTimeout')), null, null, false, true);
			}

			// Log out the front end user
			else
			{
				// Unset the cookies
				$this->setCookie('FE_USER_AUTH', $strHash, ($time - 86400), null, null, false, true);
				$this->setCookie('FE_AUTO_LOGIN', \Input::cookie('FE_AUTO_LOGIN'), ($time - 86400), null, null, false, true);
			}

			$strBuffer = '';
			$rand = rand();

			// Display the pages
			for ($i=0, $c=count($arrPages); $i<$c; $i++)
			{
				$strBuffer .= '<span class="page_url" data-url="' . $arrPages[$i] . '#' . $rand . $i . '">' . \String::substr($arrPages[$i], 100) . '</span><br>';
				unset($arrPages[$i]); // see #5681
			}

			$objTemplate->content = $strBuffer;
			$objTemplate->note = $GLOBALS['TL_LANG']['tl_maintenance']['indexNote'];
			$objTemplate->loading = $GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'];
			$objTemplate->complete = $GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'];
			$objTemplate->indexContinue = $GLOBALS['TL_LANG']['MSC']['continue'];
			$objTemplate->theme = \Backend::getTheme();
			$objTemplate->isRunning = true;

			return $objTemplate->parse();
		}

		$arrUser = array(''=>'-');

		// Get active front end users
		$objUser = $this->Database->execute("SELECT id, username FROM tl_member WHERE disable!='1' AND (start='' OR start<='$time') AND (stop='' OR stop>'" . ($time + 60) . "') ORDER BY username");

		while ($objUser->next())
		{
			$arrUser[$objUser->id] = $objUser->username . ' (' . $objUser->id . ')';
		}

		// Default variables
		$objTemplate->user = $arrUser;
		$objTemplate->indexLabel = $GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0];
		$objTemplate->indexHelp = (\Config::get('showHelp') && strlen($GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1])) ? $GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] : '';
		$objTemplate->indexSubmit = $GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'];

		return $objTemplate->parse();
	}
}
