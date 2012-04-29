<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \Backend, \BackendTemplate, \Environment, \Input, \RequestToken, \String, \executable;


/**
 * Class RebuildIndex
 *
 * Maintenance module "rebuild index".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class RebuildIndex extends Backend implements executable
{

	/**
	 * Return true if the module is active
	 * @return boolean
	 */
	public function isActive()
	{
		return ($GLOBALS['TL_CONFIG']['enableSearch'] && Input::get('act') == 'index');
	}


	/**
	 * Generate the module
	 * @return string
	 */
	public function run()
	{
		if (!$GLOBALS['TL_CONFIG']['enableSearch'])
		{
			return '';
		}

		$time = time();
		$objTemplate = new BackendTemplate('be_rebuild_index');
		$objTemplate->action = ampersand(Environment::get('request'));
		$objTemplate->indexHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'];
		$objTemplate->isActive = $this->isActive();

		// Add the error message
		if ($_SESSION['REBUILD_INDEX_ERROR'] != '')
		{
			$objTemplate->indexMessage = $_SESSION['REBUILD_INDEX_ERROR'];
			$_SESSION['REBUILD_INDEX_ERROR'] = '';
		}

		// Rebuild the index
		if (Input::get('act') == 'index')
		{
			// Check the request token (see #4007)
			if (!isset($_GET['rt']) || !RequestToken::validate(Input::get('rt')))
			{
				$this->Session->set('INVALID_TOKEN_URL', Environment::get('request'));
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
			$this->setCookie('FE_PREVIEW', 0, ($time - 86400), $GLOBALS['TL_CONFIG']['websitePath']);

			// Calculate the hash
			$strHash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? Environment::get('ip') : '') . 'FE_USER_AUTH');

			// Remove old sessions
			$this->Database->prepare("DELETE FROM tl_session WHERE tstamp<? OR hash=?")
						   ->execute(($time - $GLOBALS['TL_CONFIG']['sessionTimeout']), $strHash);

			// Log in the front end user
			if (is_numeric(Input::get('user')) && Input::get('user') > 0)
			{
				// Insert a new session
				$this->Database->prepare("INSERT INTO tl_session (pid, tstamp, name, sessionID, ip, hash) VALUES (?, ?, ?, ?, ?, ?)")
							   ->execute(Input::get('user'), $time, 'FE_USER_AUTH', session_id(), Environment::get('ip'), $strHash);

				// Set the cookie
				$this->setCookie('FE_USER_AUTH', $strHash, ($time + $GLOBALS['TL_CONFIG']['sessionTimeout']), $GLOBALS['TL_CONFIG']['websitePath']);
			}

			// Log out the front end user
			else
			{
				// Unset the cookies
				$this->setCookie('FE_USER_AUTH', $strHash, ($time - 86400), $GLOBALS['TL_CONFIG']['websitePath']);
				$this->setCookie('FE_AUTO_LOGIN', Input::cookie('FE_AUTO_LOGIN'), ($time - 86400), $GLOBALS['TL_CONFIG']['websitePath']);
			}

			$strBuffer = '';
			$rand = rand();

			// Display the pages
			for ($i=0; $i<count($arrPages); $i++)
			{
				$strBuffer .= '<img src="' . $arrPages[$i] . '#' . $rand . $i . '" alt="" class="invisible">' . String::substr($arrPages[$i], 100) . "<br>\n";
			}

			$objTemplate->content = $strBuffer;
			$objTemplate->note = $GLOBALS['TL_LANG']['tl_maintenance']['indexNote'];
			$objTemplate->loading = $GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'];
			$objTemplate->complete = $GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'];
			$objTemplate->indexContinue = $GLOBALS['TL_LANG']['MSC']['continue'];
			$objTemplate->theme = $this->getTheme();
			$objTemplate->isRunning = true;

			return $objTemplate->parse();
		}

		$arrUser = array(''=>'-');

		// Get active front end users
		$objUser = $this->Database->execute("SELECT id, username FROM tl_member WHERE disable!=1 AND (start='' OR start<$time) AND (stop='' OR stop>$time) ORDER BY username");

		while ($objUser->next())
		{
			$arrUser[$objUser->id] = $objUser->username . ' (' . $objUser->id . ')';
		}

		// Default variables
		$objTemplate->user = $arrUser;
		$objTemplate->indexLabel = $GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0];
		$objTemplate->indexHelp = ($GLOBALS['TL_CONFIG']['showHelp'] && strlen($GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1])) ? $GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] : '';
		$objTemplate->indexSubmit = $GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'];

		return $objTemplate->parse();
	}
}
