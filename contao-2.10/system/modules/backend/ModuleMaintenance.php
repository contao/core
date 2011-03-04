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
 * Class ModuleMaintenance
 *
 * Back end module "maintenance".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleMaintenance extends BackendModule
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_maintenance';


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$this->loadLanguageFile('tl_maintenance');

		$this->Template->cacheMessage = '';
		$this->Template->updateMessage = '';

		$this->cacheTables();
		$this->liveUpdate();
		$this->searchIndex();

		$this->Template->href = $this->getReferer(true);
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['backBT']);
		$this->Template->action = ampersand($this->Environment->request);
		$this->Template->selectAll = $GLOBALS['TL_LANG']['MSC']['selectAll'];
		$this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];
	}


	/**
	 * Handle the "clear cache" module
	 */
	protected function cacheTables()
	{
		$arrCacheTables = array();

		$arrTmp = scan(TL_ROOT . '/system/tmp');
		$arrHtml = scan(TL_ROOT . '/system/html');

		// Confirmation message
		if (strlen($_SESSION['CLEAR_CACHE_CONFIRM']))
		{
			$this->Template->cacheMessage = sprintf('<p class="tl_confirm">%s</p>', $_SESSION['CLEAR_CACHE_CONFIRM']);
			$_SESSION['CLEAR_CACHE_CONFIRM'] = '';
		}

		// Truncate cache tables
		if ($this->Input->post('FORM_SUBMIT') == 'tl_cache')
		{
			$tables = deserialize($this->Input->post('tables'));

			if (!is_array($tables))
			{
				$this->reload();
			}

			$this->import('Automator');
			$this->import('StyleSheets');

			foreach ($tables as $table)
			{
				// Temporary folder
				if ($table == 'temp_folder')
				{
					$this->Automator->purgeTempFolder();
				}

				// Html folder
				elseif ($table == 'html_folder')
				{
					$this->Automator->purgeHtmlFolder();
				}

				// CSS files
				elseif ($table == 'css_files')
				{
					$this->StyleSheets->updateStyleSheets();
				}

				// XML files
				elseif ($table == 'xml_files')
				{
					// HOOK: use the googlesitemap module
					if (in_array('googlesitemap', $this->Config->getActiveModules()))
					{
						$this->import('GoogleSitemap');
						$this->GoogleSitemap->generateSitemap();
					}
					else
					{
						$this->Automator->generateSitemap();
					}

					// HOOK: recreate news feeds
					if (in_array('news', $this->Config->getActiveModules()))
					{
						$this->import('News');
						$this->News->generateFeeds();
					}

					// HOOK: recreate calendar feeds
					if (in_array('calendar', $this->Config->getActiveModules()))
					{
						$this->import('Calendar');
						$this->Calendar->generateFeeds();
					}
				}

				// Database table
				else
				{
					$this->Database->execute("TRUNCATE TABLE " . $table);
				}
			}

			$_SESSION['CLEAR_CACHE_CONFIRM'] = $GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'];
			$this->reload();
		}

		// Get all cachable tables from TL_API
		foreach ($GLOBALS['TL_CACHE'] as $k=>$v)
		{
			$objCount = $this->Database->execute("SELECT COUNT(*) AS count FROM " . $v);

			$arrCacheTables[] = array
			(
				'id' => 'cache_' . $k,
				'value' => specialchars($v),
				'name' => $v,
				'entries' => sprintf($GLOBALS['TL_LANG']['MSC']['entries'], $objCount->count)
			);
		}

		$this->Template->cacheTmp = $GLOBALS['TL_LANG']['tl_maintenance']['clearTemp'];
		$this->Template->cacheHtml = $GLOBALS['TL_LANG']['tl_maintenance']['clearHtml'];
		$this->Template->cacheXml = $GLOBALS['TL_LANG']['tl_maintenance']['clearXml'];
		$this->Template->cacheCss = $GLOBALS['TL_LANG']['tl_maintenance']['clearCss'];
		$this->Template->cacheHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['clearCache'];
		$this->Template->cacheLabel = $GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0];
		$this->Template->cacheEntries = sprintf($GLOBALS['TL_LANG']['MSC']['entries'], (count($arrTmp) - 1));
		$this->Template->htmlEntries = sprintf($GLOBALS['TL_LANG']['MSC']['entries'], (count($arrHtml) - 1));
		$this->Template->cacheHelp = ($GLOBALS['TL_CONFIG']['showHelp'] && strlen($GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1])) ? $GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] : '';
		$this->Template->cacheSubmit = specialchars($GLOBALS['TL_LANG']['tl_maintenance']['clearCache']);
		$this->Template->cacheTables = $arrCacheTables;
	}


	/**
	 * Handle the "live update" module
	 */
	protected function liveUpdate()
	{
		$this->Template->updateClass = 'tl_confirm';
		$this->Template->updateHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'];

		// Current version up to date
		$this->Template->updateMessage = sprintf('%s <a href="%sCHANGELOG.txt" title="%s"%s><img src="%s" alt="%s" style="vertical-align:text-bottom; padding-left:3px;" /></a>',
												 sprintf($GLOBALS['TL_LANG']['tl_maintenance']['upToDate'], VERSION . '.' . BUILD),
												 $this->Environment->base,
												 specialchars($GLOBALS['TL_LANG']['tl_maintenance']['changelog']),
												 LINK_NEW_WINDOW,
												 'system/themes/'.$this->getTheme().'/images/changelog.gif',
												 specialchars($GLOBALS['TL_LANG']['tl_maintenance']['changelog']));

		// No live update for beta versions
		if (!is_numeric(BUILD))
		{
			$this->Template->updateClass = 'tl_info';
			$this->Template->updateMessage = $GLOBALS['TL_LANG']['tl_maintenance']['betaVersion'];
		}

		// Newer version available
		elseif (strlen($GLOBALS['TL_CONFIG']['latestVersion']) && version_compare(VERSION . '.' . BUILD, $GLOBALS['TL_CONFIG']['latestVersion'], '<'))
		{
			$this->Template->updateClass = 'tl_info';
			$this->Template->updateMessage = sprintf($GLOBALS['TL_LANG']['tl_maintenance']['newVersion'], $GLOBALS['TL_CONFIG']['latestVersion']);
		}

		// Live update error
		if (strlen($_SESSION['LIVE_UPDATE_ERROR']))
		{
			$this->Template->updateClass = 'tl_error';
			$this->Template->updateMessage = $_SESSION['LIVE_UPDATE_ERROR'];

			$_SESSION['LIVE_UPDATE_ERROR'] = '';
		}

		// Live update successful
		if (strlen($_SESSION['LIVE_UPDATE_CONFIRM']))
		{
			$this->Template->updateClass = 'tl_confirm';
			$this->Template->updateMessage = $_SESSION['LIVE_UPDATE_CONFIRM'];

			$_SESSION['LIVE_UPDATE_CONFIRM'] = '';
		}

		$this->Template->uid = $GLOBALS['TL_CONFIG']['liveUpdateId'];
		$this->Template->updateServer = $GLOBALS['TL_CONFIG']['liveUpdateBase'] . 'index.php';

		// Run update
		if (strlen($this->Input->get('token')))
		{
			$this->runLiveUpdate();
		}

		$this->Template->version = VERSION . '.' .  BUILD;
		$this->Template->liveUpdateId = $GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'];
		$this->Template->runLiveUpdate = specialchars($GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate']);
		$this->Template->referer = base64_encode($this->Environment->base . $this->Environment->request . '|' . $this->Environment->server);
		$this->Template->backupFiles = $GLOBALS['TL_LANG']['tl_maintenance']['backupFiles'];
		$this->Template->showToc = $GLOBALS['TL_LANG']['tl_maintenance']['showToc'];
	}


	/**
	 * Run the live update
	 */
	protected function runLiveUpdate()
	{
		$archive = 'system/tmp/' . $this->Input->get('token');

		// Download the archive
		if (!file_exists(TL_ROOT . '/' . $archive))
		{
			$objRequest = new Request();
			$objRequest->send($GLOBALS['TL_CONFIG']['liveUpdateBase'] . 'request.php?token=' . $this->Input->get('token'));

			if ($objRequest->hasError())
			{
				$this->Template->updateClass = 'tl_error';
				$this->Template->updateMessage = $objRequest->response;

				return;
			}

			$objFile = new File($archive);
			$objFile->write($objRequest->response);
			$objFile->close();
		}

		$objArchive = new ZipReader($archive);

		// Show files
		if ($this->Input->get('toc'))
		{
			$arrFiles = $objArchive->getFileList();
			array_shift($arrFiles);

			echo '<div style="width:720px; margin:0 auto;">';
			echo '<h1 style="font-family:Verdana,sans-serif; font-size:16px; margin:18px 3px;">Table of contents</h1>';
			echo '<ol style="font-family:Verdana,sans-serif; font-size:11px; height:496px; overflow:auto; background:#eee; border:1px solid #999;">';
			echo '<li>';
			echo implode('</li><li>', $arrFiles);
			echo '</li>';
			echo '</ol>';
			echo '<div style="font-family:Verdana,sans-serif; font-size:11px; margin:18px 3px 12px 3px; overflow:hidden;">';
			echo '<a href="' . $this->Environment->base . 'contao/main.php?do=maintenance" style="float:left;">&lt; Click here to go back</a>';
			echo '<a href="' . str_replace('toc=1', 'toc=', $this->Environment->base . $this->Environment->request) . '" style="float:right;">Click here to proceed &gt;</a>';
			echo '</div>';
			echo '</div>';

			exit;
		}

		// Create backup
		if ($this->Input->get('bup'))
		{
			echo '<div style="width:720px; margin:0 auto;">';
			echo '<h1 style="font-family:Verdana,sans-serif; font-size:16px; margin:18px 3px;">Creating backup</h1>';
			echo '<ol style="font-family:Verdana,sans-serif; font-size:11px; height:496px; overflow:auto; background:#eee; border:1px solid #999;">';

			$arrFiles = $objArchive->getFileList();
			$objBackup = new ZipWriter('LU' . date('YmdHi') . '.zip');

			foreach ($arrFiles as $strFile)
			{
				if ($strFile == 'TOC.txt' || $strFile == 'system/runonce.php')
				{
					continue;
				}

				try
				{
					$objBackup->addFile($strFile);
					echo '<li>Backed up ' . $strFile . '</li>';
				}

				catch (Exception $e)
				{
					echo '<li>Skipped ' . $strFile . ' (' . $e->getMessage() . ')</li>';
				}
			}

			$objBackup->close();
			$url = str_replace('bup=1', 'bup=', $this->Environment->base . $this->Environment->request);

			echo '</ol>';
			echo '<div style="font-family:Verdana,sans-serif; font-size:11px; margin:18px 3px 12px 3px; overflow:hidden;">';
			echo '<a href="' . $this->Environment->base . 'contao/main.php?do=maintenance" style="float:left;">&lt; Click here to go back</a>';
			echo '<a href="' . $url . '" style="float:right;">Click here to proceed &gt;</a>';
			echo '</div>';
			echo '</div>';

			exit;
 		}

		echo '<div style="width:720px; margin:0 auto;">';
		echo '<h1 style="font-family:Verdana,sans-serif; font-size:16px; margin:18px 3px;">Updating files</h1>';
		echo '<ol style="font-family:Verdana,sans-serif; font-size:11px; height:496px; overflow:auto; background:#eee; border:1px solid #999;">';

		// Unzip files
		while ($objArchive->next())
		{
			if ($objArchive->file_name == 'TOC.txt')
			{
				continue;
			}

			try
			{
				$objFile = new File($objArchive->file_name);
				$objFile->write($objArchive->unzip());
				$objFile->close();

				echo '<li>Updated ' . $objArchive->file_name . '</li>';
			}

			catch (Exception $e)
			{
				echo '<li style="color:#ff0000;">Error updating ' . $objArchive->file_name . ': ' . $e->getMessage() . '</li>';
			}
		}

		// Delete archive
		$this->import('Files');
		$this->Files->delete($archive);

		// Add log entry
		$this->log('Live update from version ' . VERSION . '.' . BUILD . ' to version ' . $GLOBALS['TL_CONFIG']['latestVersion'] . ' completed', 'ModuleMaintenance runLiveUpdate()', TL_GENERAL);

		echo '</ol>';
		echo '<div style="font-family:Verdana,sans-serif; font-size:11px; margin:18px 3px 12px 3px; overflow:hidden;">';
		echo '<a href="main.php?do=maintenance" style="float:right;">Click here to proceed &gt;</a>';
		echo '</div>';
		echo '</div>';

		exit;
	}


	/**
	 * Rebuild the search index
	 */
	protected function searchIndex()
	{
		$time = time();

		// Add error message
		if ($_SESSION['REBUILD_INDEX_ERROR'] != '')
		{
			$this->Template->indexMessage = $_SESSION['REBUILD_INDEX_ERROR'];
			$_SESSION['REBUILD_INDEX_ERROR'] = '';
		}

		// Rebuild index
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

			// Truncate search tables
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

			// Display pages
			for ($i=0; $i<count($arrPages); $i++)
			{
				$strBuffer .= '<img src="' . $arrPages[$i] . '#' . $rand . $i . '" alt="" class="invisible" />' . $this->String->substr($arrPages[$i], 100, true) . "<br />\n";
			}

			$this->Template = new BackendTemplate('be_index');

			$this->Template->content = $strBuffer;
			$this->Template->note = $GLOBALS['TL_LANG']['tl_maintenance']['indexNote'];
			$this->Template->loading = $GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'];
			$this->Template->complete = $GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'];
			$this->Template->theme = $this->getTheme();
		}

		$arrUser = array(''=>'-');

		// Get active front end users
		$objUser = $this->Database->execute("SELECT id, username FROM tl_member WHERE disable!=1 AND (start='' OR start<$time) AND (stop='' OR stop>$time) ORDER BY username");

		while ($objUser->next())
		{
			$arrUser[$objUser->id] = $objUser->username . ' (' . $objUser->id . ')';
		}

		// Default variables
		$this->Template->user = $arrUser;
		$this->Template->indexContinue = $GLOBALS['TL_LANG']['MSC']['continue'];
		$this->Template->indexLabel = $GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0];
		$this->Template->indexHelp = ($GLOBALS['TL_CONFIG']['showHelp'] && strlen($GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1])) ? $GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] : '';
		$this->Template->indexHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'];
		$this->Template->indexSubmit = $GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'];
	}
}

?>