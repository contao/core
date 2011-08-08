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
 * Class LiveUpdate
 *
 * Maintenance module "Live Update".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class LiveUpdate extends Backend implements executable
{

	/**
	 * Return true if the module is active
	 * @return boolean
	 */
	public function isActive()
	{
		return ($this->Input->get('token') != '');
	}


	/**
	 * Generate the module
	 * @return string
	 */
	public function run()
	{
		$objTemplate = new BackendTemplate('be_live_update');

		$objTemplate->updateClass = 'tl_confirm';
		$objTemplate->updateHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'];
		$objTemplate->isActive = $this->isActive();

		// Current version up to date
		$objTemplate->updateMessage = sprintf('%s <a href="%sCHANGELOG.txt" rel="lightbox[external 80%% 80%%]" title="%s"><img src="%s" width="14" height="14" alt="%s" style="vertical-align:text-bottom; padding-left:3px;"></a>',
												 sprintf($GLOBALS['TL_LANG']['tl_maintenance']['upToDate'], VERSION . '.' . BUILD),
												 $this->Environment->base,
												 specialchars($GLOBALS['TL_LANG']['tl_maintenance']['changelog']),
												 TL_FILES_URL . 'system/themes/'.$this->getTheme().'/images/changelog.gif',
												 specialchars($GLOBALS['TL_LANG']['tl_maintenance']['changelog']));

		// No live update for beta versions
		if (!is_numeric(BUILD))
		{
			$objTemplate->updateClass = 'tl_info';
			$objTemplate->updateMessage = $GLOBALS['TL_LANG']['tl_maintenance']['betaVersion'];
		}

		// Newer version available
		elseif ($GLOBALS['TL_CONFIG']['latestVersion'] != '' && version_compare(VERSION . '.' . BUILD, $GLOBALS['TL_CONFIG']['latestVersion'], '<'))
		{
			$objTemplate->updateClass = 'tl_info';
			$objTemplate->updateMessage = sprintf($GLOBALS['TL_LANG']['tl_maintenance']['newVersion'], $GLOBALS['TL_CONFIG']['latestVersion']);
		}

		// Live update error
		if ($_SESSION['LIVE_UPDATE_ERROR'] != '')
		{
			$objTemplate->updateClass = 'tl_error';
			$objTemplate->updateMessage = $_SESSION['LIVE_UPDATE_ERROR'];

			$_SESSION['LIVE_UPDATE_ERROR'] = '';
		}

		// Live update successful
		if ($_SESSION['LIVE_UPDATE_CONFIRM'] != '')
		{
			$objTemplate->updateClass = 'tl_confirm';
			$objTemplate->updateMessage = $_SESSION['LIVE_UPDATE_CONFIRM'];

			$_SESSION['LIVE_UPDATE_CONFIRM'] = '';
		}

		$objTemplate->uid = $GLOBALS['TL_CONFIG']['liveUpdateId'];
		$objTemplate->updateServer = $GLOBALS['TL_CONFIG']['liveUpdateBase'] . 'index.php';

		// Run the update
		if ($this->Input->get('token') != '')
		{
			$this->runLiveUpdate($objTemplate);
		}
		elseif ($this->Input->get('act') == 'runonce')
		{
			$this->handleRunOnce();
			$this->redirect('contao/main.php?do=maintenance');
		}

		$objTemplate->version = VERSION . '.' .  BUILD;
		$objTemplate->liveUpdateId = $GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'];
		$objTemplate->runLiveUpdate = specialchars($GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate']);
		$objTemplate->referer = base64_encode($this->Environment->base . $this->Environment->request . '|' . $this->Environment->server);
		$objTemplate->backupFiles = $GLOBALS['TL_LANG']['tl_maintenance']['backupFiles'];
		$objTemplate->showToc = $GLOBALS['TL_LANG']['tl_maintenance']['showToc'];

		return $objTemplate->parse();
	}


	/**
	 * Run the live update
	 * @param object
	 */
	protected function runLiveUpdate(BackendTemplate $objTemplate)
	{
		$archive = 'system/tmp/' . $this->Input->get('token');

		// Download the archive
		if (!file_exists(TL_ROOT . '/' . $archive))
		{
			$objRequest = new Request();
			$objRequest->send($GLOBALS['TL_CONFIG']['liveUpdateBase'] . 'request.php?token=' . $this->Input->get('token'));

			if ($objRequest->hasError())
			{
				$objTemplate->updateClass = 'tl_error';
				$objTemplate->updateMessage = $objRequest->response;

				return;
			}

			$objFile = new File($archive);
			$objFile->write($objRequest->response);
			$objFile->close();
		}

		$objArchive = new ZipReader($archive);
		$arrHidden = array();

		foreach (array_keys($_GET) as $key)
		{
			$arrHidden[$key] = $this->Input->get($key);
		}

		$objTemplate->isRunning = true;
		$objTemplate->action = ampersand($this->Environment->request);
		$objTemplate->continue = $GLOBALS['TL_LANG']['MSC']['continue'];
		$arrElements = array();

		// Show the files
		if ($this->Input->get('toc'))
		{
			$arrFiles = $objArchive->getFileList();
			array_shift($arrFiles);
			$objTemplate->elements = $arrFiles;

			unset($arrHidden['toc']);
			$objTemplate->hidden = $arrHidden;
			$objTemplate->headline = $GLOBALS['TL_LANG']['tl_maintenance']['toc'];

			return;
		}

		// Create the backup
		if ($this->Input->get('bup'))
		{
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
					$arrElements[] = 'Backed up ' . $strFile;
				}
				catch (Exception $e)
				{
					$arrElements[] = 'Skipped ' . $strFile . ' (' . $e->getMessage() . ')';
				}
			}

			$objBackup->close();
			$objTemplate->elements = $arrElements;

			unset($arrHidden['bup']);
			$objTemplate->hidden = $arrHidden;
			$objTemplate->headline = $GLOBALS['TL_LANG']['tl_maintenance']['backup'];

			return;
 		}

		// Unzip the files
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

				$arrElements[] = 'Updated ' . $objArchive->file_name . '';
			}
			catch (Exception $e)
			{
				$arrElements[] = '<span style="color:#c55;">Error updating ' . $objArchive->file_name . ': ' . $e->getMessage() . '</span>';
			}
		}

		$objTemplate->elements = $arrElements;

		unset($arrHidden['token']);
		$arrHidden['act'] = 'runonce';

		$objTemplate->hidden = $arrHidden;
		$objTemplate->headline = $GLOBALS['TL_LANG']['tl_maintenance']['update'];

		// Delete the archive
		$this->import('Files');
		$this->Files->delete($archive);

		// Add a log entry
		$this->log('Live update from version ' . VERSION . '.' . BUILD . ' to version ' . $GLOBALS['TL_CONFIG']['latestVersion'] . ' completed', 'LiveUpdate run()', TL_GENERAL);
	}
}

?>