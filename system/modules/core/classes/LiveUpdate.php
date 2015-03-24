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
 * Maintenance module "Live Update".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class LiveUpdate extends \Backend implements \executable
{

	/**
	 * Return true if the module is active
	 *
	 * @return boolean
	 */
	public function isActive()
	{
		return false;
	}


	/**
	 * Generate the module
	 *
	 * @return string
	 */
	public function run()
	{
		/** @var \BackendTemplate|object $objTemplate */
		$objTemplate = new \BackendTemplate('be_live_update');

		$objTemplate->updateClass = 'tl_confirm';
		$objTemplate->updateHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'];
		$objTemplate->isActive = $this->isActive();
		$strMessage = ' <a href="contao/changelog.php" onclick="Backend.openModalIframe({\'width\':860,\'title\':\'CHANGELOG\',\'url\':this.href});return false" title="' . specialchars($GLOBALS['TL_LANG']['tl_maintenance']['changelog']) . '"><img src="' . TL_FILES_URL . 'system/themes/' . \Backend::getTheme() . '/images/changelog.gif" width="14" height="14" alt="" style="vertical-align:text-bottom;padding-left:3px"></a>';

		// Newer version available
		if (\Config::get('latestVersion') && version_compare(VERSION . '.' . BUILD, \Config::get('latestVersion'), '<'))
		{
			$objTemplate->updateClass = 'tl_info';
			$objTemplate->updateMessage = sprintf($GLOBALS['TL_LANG']['tl_maintenance']['newVersion'], \Config::get('latestVersion')) . $strMessage;
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
			\Config::set('liveUpdateBase', str_replace('http://', 'https://', \Config::get('liveUpdateBase')));
		}

		$objTemplate->uid = \Config::get('liveUpdateId');
		$objTemplate->updateServer = \Config::get('liveUpdateBase') . 'index.php';

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
	 * Run the Live Update
	 *
	 * @param \BackendTemplate|object $objTemplate
	 */
	protected function runLiveUpdate(\BackendTemplate $objTemplate)
	{
		$archive = 'system/tmp/' . \Input::get('token');

		// Download the archive
		if (!file_exists(TL_ROOT . '/' . $archive))
		{
			// HOOK: proxy module
			if (Config::get('useProxy')) {
				$objRequest = new \ProxyRequest();
			} else {
				$objRequest = new \Request();
			}

			$objRequest->send(\Config::get('liveUpdateBase') . 'request.php?token=' . \Input::get('token'));

			if ($objRequest->hasError())
			{
				$objTemplate->updateClass = 'tl_error';
				$objTemplate->updateMessage = $objRequest->response;

				return;
			}

			\File::putContent($archive, $objRequest->response);
		}

		$objArchive = new \ZipReader($archive);

		// Extract
		while ($objArchive->next())
		{
			if ($objArchive->file_name != 'TOC.txt')
			{
				try
				{
					\File::putContent($objArchive->file_name, $objArchive->unzip());
				}
				catch (\Exception $e)
				{
					/** @var \BackendTemplate|object $objTemplate */
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
