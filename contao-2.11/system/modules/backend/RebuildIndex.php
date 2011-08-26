<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * Class RebuildIndex
 *
 * Maintenance module "rebuild index".
 * @copyright  Leo Feyer 2005-2011
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
		return ($this->Input->get('act') == 'index');
	}


	/**
	 * Generate the module
	 * @return string
	 */
	public function run()
	{
		$time = time();
		$objTemplate = new BackendTemplate('be_rebuild_index');
		$objTemplate->action = ampersand($this->Environment->request);
		$objTemplate->indexHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'];
		$objTemplate->isActive = $this->isActive();

		// Add the error message
		if ($_SESSION['REBUILD_INDEX_ERROR'] != '')
		{
			$objTemplate->indexMessage = $_SESSION['REBUILD_INDEX_ERROR'];
			$_SESSION['REBUILD_INDEX_ERROR'] = '';
		}

		// Rebuild the index
		if ($this->Input->get('act') == 'index')
		{
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
			if (count($arrPages) < 1)
			{
				$_SESSION['REBUILD_INDEX_ERROR'] = $GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'];
				$this->redirect($this->getReferer());
			}

			$this->import('Search');

			// Truncate the search tables
			$this->Database->execute("TRUNCATE TABLE tl_search");
			$this->Database->execute("TRUNCATE TABLE tl_search_index");

			// Hide unpublished elements
			$this->setCookie('FE_PREVIEW', 0, ($time - 86400), $GLOBALS['TL_CONFIG']['websitePath']);

			// Purge the temporary directory
			$this->import('Automator');
			$this->Automator->purgeTempFolder();

			// Calculate the hash
			$strHash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? $this->Environment->ip : '') . 'FE_USER_AUTH');

			// Remove old sessions
			$this->Database->prepare("DELETE FROM tl_session WHERE tstamp<? OR hash=?")
						   ->execute(($time - $GLOBALS['TL_CONFIG']['sessionTimeout']), $strHash);

			// Log in the front end user
			if (is_numeric($this->Input->get('user')) && $this->Input->get('user') > 0)
			{
				// Insert a new session
				$this->Database->prepare("INSERT INTO tl_session (pid, tstamp, name, sessionID, ip, hash) VALUES (?, ?, ?, ?, ?, ?)")
							   ->execute($this->Input->get('user'), $time, 'FE_USER_AUTH', session_id(), $this->Environment->ip, $strHash);

				// Set the cookie
				$this->setCookie('FE_USER_AUTH', $strHash, ($time + $GLOBALS['TL_CONFIG']['sessionTimeout']), $GLOBALS['TL_CONFIG']['websitePath']);
			}

			// Log out the front end user
			else
			{
				// Unset the cookies
				$this->setCookie('FE_USER_AUTH', $strHash, ($time - 86400), $GLOBALS['TL_CONFIG']['websitePath']);
				$this->setCookie('FE_AUTO_LOGIN', $this->Input->cookie('FE_AUTO_LOGIN'), ($time - 86400), $GLOBALS['TL_CONFIG']['websitePath']);
			}

			$strBuffer = '';
			$rand = rand();
			$this->import('String');

			// Display the pages
			for ($i=0; $i<count($arrPages); $i++)
			{
				$strBuffer .= '<img src="' . $arrPages[$i] . '#' . $rand . $i . '" alt="" class="invisible">' . $this->String->substr($arrPages[$i], 100) . "<br>\n";
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

?>