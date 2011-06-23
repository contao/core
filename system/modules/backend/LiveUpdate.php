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
		elseif (strlen($GLOBALS['TL_CONFIG']['latestVersion']) && version_compare(VERSION . '.' . BUILD, $GLOBALS['TL_CONFIG']['latestVersion'], '<'))
		{
			$objTemplate->updateClass = 'tl_info';
			$objTemplate->updateMessage = sprintf($GLOBALS['TL_LANG']['tl_maintenance']['newVersion'], $GLOBALS['TL_CONFIG']['latestVersion']);
		}

		// Live update error
		if (strlen($_SESSION['LIVE_UPDATE_ERROR']))
		{
			$objTemplate->updateClass = 'tl_error';
			$objTemplate->updateMessage = $_SESSION['LIVE_UPDATE_ERROR'];

			$_SESSION['LIVE_UPDATE_ERROR'] = '';
		}

		// Live update successful
		if (strlen($_SESSION['LIVE_UPDATE_CONFIRM']))
		{
			$objTemplate->updateClass = 'tl_confirm';
			$objTemplate->updateMessage = $_SESSION['LIVE_UPDATE_CONFIRM'];

			$_SESSION['LIVE_UPDATE_CONFIRM'] = '';
		}

		$objTemplate->uid = $GLOBALS['TL_CONFIG']['liveUpdateId'];
		$objTemplate->updateServer = $GLOBALS['TL_CONFIG']['liveUpdateBase'] . 'index.php';

		// Run update
		if ($this->Input->get('token') != '')
		{
			$this->runLiveUpdate();
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
				$objTemplate->updateClass = 'tl_error';
				$objTemplate->updateMessage = $objRequest->response;

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
}

?>