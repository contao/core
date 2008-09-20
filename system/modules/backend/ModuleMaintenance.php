<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleMaintenance
 *
 * Back end module "maintenance".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
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

		$this->Template->href = $this->getReferer(ENCODE_AMPERSANDS);
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['backBT']);
		$this->Template->action = ampersand($this->Environment->request, true);
		$this->Template->selectAll = $GLOBALS['TL_LANG']['MSC']['selectAll'];
		$this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];
	}


	/**
	 * Handle the "clear cache" module
	 */
	private function cacheTables()
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

			foreach ($tables as $table)
			{
				// Temporary folder
				if ($table == 'temp_folder')
				{
					foreach ($arrTmp as $strFile)
					{
						if ($strFile != '.htaccess')
						{
							@unlink(TL_ROOT . '/system/tmp/' . $strFile);
						}
					}
				}

				// Html folder
				elseif ($table == 'html_folder')
				{
					foreach ($arrHtml as $strFile)
					{
						if ($strFile != 'index.html')
						{
							@unlink(TL_ROOT . '/system/html/' . $strFile);
						}
					}
				}

				// XML sitemaps
				elseif ($table == 'xml_sitemap')
				{
					include(TL_ROOT . '/system/config/dcaconfig.php');
					
					$this->import('Automator');
					$this->Automator->generateSitemap();
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
	private function liveUpdate()
	{
		$this->Template->updateClass = 'tl_confirm';
		$this->Template->updateHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'];

		// Current version up to date
		$this->Template->updateMessage = sprintf('%s <a href="%sCHANGELOG.txt" title="%s" onclick="this.blur(); window.open(this.href); return false;"><img src="%s" alt="%s" style="vertical-align:text-bottom; padding-left:3px;" /></a>',
												 sprintf($GLOBALS['TL_LANG']['tl_maintenance']['upToDate'], VERSION . '.' . BUILD),
												 $this->Environment->base,
												 specialchars($GLOBALS['TL_LANG']['tl_maintenance']['changelog']),
												 'system/themes/'.$this->getTheme().'/images/changelog.gif',
												 specialchars($GLOBALS['TL_LANG']['tl_maintenance']['changelog']));

		// Newer version available
		if (strlen($GLOBALS['TL_CONFIG']['latestVersion']) && version_compare(VERSION . '.' . BUILD, $GLOBALS['TL_CONFIG']['latestVersion'], '<'))
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
		$this->Template->updateServer = 'http://www.inetrobots.com/liveupdate/index.php';

		if ($this->Environment->ssl)
		{
			$this->Template->updateServer = 'https://sslwebsites.net/inetrobots.com/liveupdate/index.php';
		}

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
	private function runLiveUpdate()
	{
		$archive = 'system/tmp/' . $this->Input->get('token');

		// Download the archive
		if (!file_exists(TL_ROOT . '/' . $archive))
		{
			$objRequest = new Request();
			$objRequest->send('http://www.inetrobots.com/liveupdate/request.php?token=' . $this->Input->get('token'));

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

			echo '<h1 style="font-family:Verdana, sans-serif; font-size:13px; margin:12px 3px; line-height:1; padding:0;">Table of contents</h1>';
			echo '<div style="font-family:Verdana, sans-serif; font-size:11px; margin:0px 3px; line-height:1.3;">';
			echo implode('<br />', $arrFiles);
			echo '</div>';
			echo '<div style="font-family:Verdana, sans-serif; font-size:11px; margin:18px 3px 12px 3px;">';
			echo '<a href="' . str_replace('toc=1', 'toc=', $this->Environment->base . $this->Environment->request) . '">Click here to run the update</a><br />';
			echo '<a href="' . $this->Environment->base . 'typolight/main.php?do=maintenance">Click here to go back</a>';
			echo '</div>';

			exit;
		}

		// Create backup
		if ($this->Input->get('bup'))
		{
			echo '<h1 style="font-family:Verdana, sans-serif; font-size:13px; margin:12px 3px; line-height:1; padding:0;">Creating backup</h1>';
			echo '<div style="font-family:Verdana, sans-serif; font-size:11px; margin:0px 3px; line-height:1.3;">';

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
					echo 'Backed up <strong>' . $strFile . '</strong><br />';
				}

				catch (Exception $e)
				{
					echo 'Skipped <strong>' . $strFile . '</strong> (' . $e->getMessage() . ')<br />';
				}
			}

			$objBackup->close();
			$url = str_replace('bup=1', 'bup=', $this->Environment->base . $this->Environment->request);

			echo '</div>';
			echo '<div style="font-family:Verdana, sans-serif; font-size:11px; margin:18px 3px 12px 3px;">';
			echo '<a href="' . $url . '">Click here to proceed if you are not using JavaScript</a>';
			echo '</div>';
			echo '<script type="text/javascript">setTimeout(\'window.location="' . $url . '"\', 1000);</script>';

			exit;
 		}

		echo '<h1 style="font-family:Verdana, sans-serif; font-size:13px; margin:12px 3px; line-height:1; padding:0;">Updating files</h1>';
		echo '<div style="font-family:Verdana, sans-serif; font-size:11px; margin:0px 3px; line-height:1.3;">';

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

				echo 'Updated <strong>' . $objArchive->file_name . '</strong><br />';
			}

			catch (Exception $e)
			{
				echo '<span style="color:#ff0000;">Error updating <strong>' . $objArchive->file_name . '</strong>: ' . $e->getMessage() . '</span><br />';
			}
		}

		// Delete archive
		$this->import('Files');
		$this->Files->delete($archive);

		// Add log entry
		$this->log('Live update from version ' . VERSION . '.' . BUILD . ' to version ' . $GLOBALS['TL_CONFIG']['latestVersion'] . ' completed', 'ModuleMaintenance runLiveUpdate()', TL_GENERAL);

		// Reset latest version
		$GLOBALS['TL_CONFIG']['latestVersion'] = '';
		$this->Config->update("\$GLOBALS['TL_CONFIG']['latestVersion']", '');

		echo '</div>';
		echo '<div style="font-family:Verdana, sans-serif; font-size:11px; margin:18px 3px 12px 3px;">';
		echo '<a href="main.php?do=maintenance">Click here to continue</a><br />';
		echo '</div>';

		exit;
	}


	/**
	 * Rebuild the search index
	 */
	private function searchIndex()
	{
		$time = time();

		// Add error message
		if (strlen($_SESSION['REBUILD_INDEX_ERROR']))
		{
			$this->Template->indexMessage = $_SESSION['REBUILD_INDEX_ERROR'];
			$_SESSION['REBUILD_INDEX_ERROR'] = '';
		}

		// Rebuild index
		if ($this->Input->get('act') == 'index')
		{
			$arrPages = $this->getSearchablePages();

			// HOOK: take additional pages
			if (array_key_exists('getSearchablePages', $GLOBALS['TL_HOOKS']) && is_array($GLOBALS['TL_HOOKS']['getSearchablePages']))
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

			// Calculate hash
			$strHash = sha1(session_id().$this->Environment->ip.'FE_USER_AUTH');

			// Remove old sessions
			$this->Database->prepare("DELETE FROM tl_session WHERE tstamp<? OR hash=?")
						   ->execute(($time - $GLOBALS['TL_CONFIG']['sessionTimeout']), $strHash);

			// Log in front end user
			if (is_numeric($this->Input->get('user')) && $this->Input->get('user') > 0)
			{
				// Insert new session
				$this->Database->prepare("INSERT INTO tl_session (pid, tstamp, name, sessionID, ip, hash) VALUES (?, ?, ?, ?, ?, ?)")
							   ->execute($this->Input->get('user'), $time, 'FE_USER_AUTH', session_id(), $this->Environment->ip, $strHash);

				// Set cookie
				$this->setCookie('FE_USER_AUTH', $strHash, ($time + $GLOBALS['TL_CONFIG']['sessionTimeout']), $GLOBALS['TL_CONFIG']['websitePath']);
			}

			// Log out front end user
			else
			{
				$this->setCookie('FE_USER_AUTH', $strHash, ($time - 86400), $GLOBALS['TL_CONFIG']['websitePath']);

				// Unset the recall cookies
				$this->setCookie('tl_recall_fe', '', ($time - 86400), '/');
				$this->setCookie('tl_recall_fe', '', ($time - 86400), $GLOBALS['TL_CONFIG']['websitePath']);
			}

			$strBuffer = '';
			$rand = rand();

			// Display pages
			for ($i=0; $i<count($arrPages); $i++)
			{
				$strBuffer .= '<img src="' . $arrPages[$i] . '#' . $rand . $i . '" alt="" />' . ((strlen(($page = substr($arrPages[$i], 0, 108))) < 108) ? $page : $page . ' …') . "<br />\n";
			}

			$this->Template = new BackendTemplate('be_index');

			$this->Template->content = $strBuffer;
			$this->Template->note = $GLOBALS['TL_LANG']['tl_maintenance']['indexNote'];
		}

		$arrUser = array(''=>'-');

		// Get active front end users
		$objUser = $this->Database->prepare("SELECT id, username FROM tl_member WHERE disable!=1 AND (start='' OR start<?) AND (stop='' OR stop>?) ORDER BY username")
								  ->execute($time, $time);

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