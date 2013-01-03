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
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class LiveUpdate
 *
 * Maintenance module "Live Update".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class LiveUpdate extends \Backend implements \executable
{

	/**
	 * Return true if the module is active
	 * @return boolean
	 */
	public function isActive()
	{
		return false;
	}


	/**
	 * Generate the module
	 * @return string
	 */
	public function run()
	{
		$objTemplate = new \BackendTemplate('be_live_update');

		$objTemplate->updateClass = 'tl_confirm';
		$objTemplate->updateHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'];
		$objTemplate->isActive = $this->isActive();
		$strMessage = ' <a href="contao/changelog.php" onclick="Backend.openModalIframe({\'width\':860,\'title\':\'CHANGELOG\',\'url\':this.href});return false" title="' . specialchars($GLOBALS['TL_LANG']['tl_maintenance']['changelog']) . '"><img src="' . TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/changelog.gif" width="14" height="14" alt="" style="vertical-align:text-bottom;padding-left:3px"></a>';

		// Newer version available
		if (isset($GLOBALS['TL_CONFIG']['latestVersion']) && version_compare(VERSION . '.' . BUILD, $GLOBALS['TL_CONFIG']['latestVersion'], '<'))
		{
			$objTemplate->updateClass = 'tl_info';
			$objTemplate->updateMessage = sprintf($GLOBALS['TL_LANG']['tl_maintenance']['newVersion'], $GLOBALS['TL_CONFIG']['latestVersion']) . $strMessage;
		}
		// Current version up to date
		else
		{
			$objTemplate->updateClass = 'tl_confirm';
			$objTemplate->updateMessage = sprintf($GLOBALS['TL_LANG']['tl_maintenance']['upToDate'], VERSION . '.' . BUILD) . $strMessage;
		}

		// Automatically switch to SSL
		if (\Environment::get('ssl'))
		{
			$GLOBALS['TL_CONFIG']['liveUpdateBase'] = str_replace('http://', 'https://', $GLOBALS['TL_CONFIG']['liveUpdateBase']);
		}
		else
		{
			$GLOBALS['TL_CONFIG']['liveUpdateBase'] = str_replace('https://', 'http://', $GLOBALS['TL_CONFIG']['liveUpdateBase']);
		}

		$objTemplate->uid = $GLOBALS['TL_CONFIG']['liveUpdateId'];
		$objTemplate->updateServer = $GLOBALS['TL_CONFIG']['liveUpdateBase'] . 'index.php';

		// Run the update
		if (\Input::get('token') != '')
		{
			$this->runLiveUpdate($objTemplate);
		}

		$objTemplate->version = VERSION . '.' .  BUILD;
		$objTemplate->liveUpdateId = $GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'];
		$objTemplate->runLiveUpdate = specialchars($GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate']);
		$objTemplate->referer = base64_encode(\Environment::get('base') . \Environment::get('request') . '|' . \Environment::get('server'));
		$objTemplate->updateHelp = sprintf($GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'], '<a href="http://luid.inetrobots.com" target="_blank">Live Update ID</a>');
		$objTemplate->phar = file_exists(TL_ROOT . '/contao/update.phar.php');
		$objTemplate->toLiveUpdate = $GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'];

		return $objTemplate->parse();
	}


	/**
	 * Run the live update
	 * @param \BackendTemplate
	 */
	protected function runLiveUpdate(\BackendTemplate $objTemplate)
	{
		$archive = 'system/tmp/' . \Input::get('token');

		// Download the archive
		if (!file_exists(TL_ROOT . '/' . $archive))
		{
			$objRequest = new \Request();
			$objRequest->send($GLOBALS['TL_CONFIG']['liveUpdateBase'] . 'request.php?token=' . \Input::get('token'));

			if ($objRequest->hasError())
			{
				$objTemplate->updateClass = 'tl_error';
				$objTemplate->updateMessage = $objRequest->response;
				return;
			}

			$objFile = new \File($archive);
			$objFile->write($objRequest->response);
			$objFile->close();
		}

		$objArchive = new \ZipReader($archive);

		// Extract
		while ($objArchive->next())
		{
			if ($objArchive->file_name != 'TOC.txt')
			{
				try
				{
					$objFile = new \File($objArchive->file_name);
					$objFile->write($objArchive->unzip());
					$objFile->close();
				}
				catch (\Exception $e)
				{
					$objTemplate->updateClass = 'tl_error';
					$objTemplate->updateMessage = 'Error updating ' . $objArchive->file_name . ': ' . $e->getMessage();
					return;
				}
			}
		}

		// Delete the archive
		$this->import('Files');
		$this->Files->delete($archive);

		// Run once
		$this->handleRunOnce();
	}
}
