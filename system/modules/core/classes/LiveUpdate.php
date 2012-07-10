<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://www.contao.org
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
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
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
		return (\Input::get('token') != '');
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

		// No live update for beta versions
		if (!is_numeric(BUILD))
		{
			$objTemplate->updateClass = 'tl_info';
			$objTemplate->updateMessage = $GLOBALS['TL_LANG']['tl_maintenance']['betaVersion'] . $strMessage;
		}
		// Newer version available
		elseif (isset($GLOBALS['TL_CONFIG']['latestVersion']) && version_compare(VERSION . '.' . BUILD, $GLOBALS['TL_CONFIG']['latestVersion'], '<'))
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

		// Live update error
		if (isset($_SESSION['LIVE_UPDATE_ERROR']))
		{
			$objTemplate->updateClass = 'tl_error';
			$objTemplate->updateMessage = $_SESSION['LIVE_UPDATE_ERROR'];
			$_SESSION['LIVE_UPDATE_ERROR'] = '';
		}

		// Live update successful
		if (isset($_SESSION['LIVE_UPDATE_CONFIRM']))
		{
			$objTemplate->updateClass = 'tl_confirm';
			$objTemplate->updateMessage = $_SESSION['LIVE_UPDATE_CONFIRM'];
			$_SESSION['LIVE_UPDATE_CONFIRM'] = '';
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
		elseif (\Input::get('act') == 'runonce')
		{
			$this->handleRunOnce();
			$this->Config->update("\$GLOBALS['TL_CONFIG']['coreOnlyMode']", true);
			$this->redirect('contao/main.php?do=maintenance');
		}

		$objTemplate->version = VERSION . '.' .  BUILD;
		$objTemplate->liveUpdateId = $GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'];
		$objTemplate->runLiveUpdate = specialchars($GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate']);
		$objTemplate->referer = base64_encode(\Environment::get('base') . \Environment::get('request') . '|' . \Environment::get('server'));
		$objTemplate->updateHelp = sprintf($GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'], '<a href="http://luid.inetrobots.com" target="_blank">Live Update ID</a>');

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

		// Create a minimalistic HTML5 document
		echo '<!DOCTYPE html>'
			.'<head>'
			  .'<meta charset="utf-8">'
			  .'<title>Contao Live Update</title>'
			  .'<style>'
			    .'body { background:#f5f5f5 url("../system/themes/default/images/hbg.jpg") left top repeat-x; font-family:Verdana,sans-serif; font-size:11px; color:#444; padding:1em; }'
			    .'div { max-width:680px; margin:0 auto; border:1px solid #bbb; background:#fff; }'
			    .'h1 { font-size:12px; color:#fff; margin:1px; padding:2px 6px 4px; background:#b3b6b3 url("../system/themes/default/images/headline.gif") left top repeat-x; }'
			    .'h2 { font-size:14px; color:#8ab858; margin:18px 15px; padding:6px 42px 8px; background:url("../system/themes/default/images/current.gif") left center no-repeat; }'
			    .'ol { border:1px solid #ccc; max-height:430px; margin:0 15px 18px; overflow:auto; padding:3px 18px 3px 48px; background:#fcfcfc; }'
			    .'li { margin-bottom:3px; }'
			    .'p { margin:0 12px; padding:0; overflow:hidden; }'
			    .'.button { font-family:"Trebuchet MS",Verdana,sans-serif; font-size:12px; display:block; float:right; margin:0 3px 18px; border-radius:3px; background:#808080; text-decoration:none; color:#fff; padding:4px 18px; box-shadow:0 1px 3px #bbb; text-shadow:1px 1px 0 #666; }'
			    .'.button:hover,.button:focus { box-shadow:0 0 6px #8ab858; }'
			    .'.button:active { background:#8ab858; }'
			  .'</style>'
			.'<body>'
			.'<div>';

		$objArchive = new \ZipReader($archive);

		// Table of contents
		if (\Input::get('toc'))
		{
			$arrFiles = $objArchive->getFileList();
			array_shift($arrFiles);

			echo '<hgroup>'
				  .'<h1>Contao Live Update</h1>'
				  .'<h2>' . $GLOBALS['TL_LANG']['tl_maintenance']['toc'] . '</h2>'
				.'</hgroup>'
				.'<ol>'
				  .'<li>' . implode('</li><li>', $arrFiles) . '</li>'
				.'</ol>'
				.'<p>'
				  .'<a href="' . ampersand(str_replace('toc=1', 'toc=', \Environment::get('base') . \Environment::get('request'))) . '" class="button">' . $GLOBALS['TL_LANG']['MSC']['continue'] . '</a>'
				  .'<a href="' . \Environment::get('base') . 'contao/main.php?do=maintenance" class="button">' . $GLOBALS['TL_LANG']['MSC']['cancelBT'] . '</a>'
				  .'</p>'
				.'</div>';

			exit;
		}

		// Backup
		if (\Input::get('bup'))
		{
			echo '<hgroup>'
				  .'<h1>Contao Live Update</h1>'
				  .'<h2>' . $GLOBALS['TL_LANG']['tl_maintenance']['backup'] . '</h2>'
				.'</hgroup>'
				.'<ol>';

			$arrFiles = $objArchive->getFileList();
			$objBackup = new \ZipWriter('LU' . date('YmdHi') . '.zip');

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
				catch (\Exception $e)
				{
					echo '<li>' . $e->getMessage() . ' (skipped)</li>';
				}
			}

			$objBackup->close();

			echo '</ol>'
				.'<p>'
				  .'<a href="' . ampersand(str_replace('bup=1', 'bup=', \Environment::get('base') . \Environment::get('request'))) . '" id="continue" class="button">' . $GLOBALS['TL_LANG']['MSC']['continue'] . '</a>'
				  .'<a href="' . \Environment::get('base') . 'contao/main.php?do=maintenance" id="back" class="button">' . $GLOBALS['TL_LANG']['MSC']['cancelBT'] . '</a>'
				  .'</p>'
				.'</div>';

			exit;
 		}

 		echo '<hgroup>'
			  .'<h1>Contao Live Update</h1>'
			  .'<h2>' . $GLOBALS['TL_LANG']['tl_maintenance']['update'] . '</h2>'
			.'</hgroup>'
			.'<ol>';

		// Update
		while ($objArchive->next())
		{
			if ($objArchive->file_name == 'TOC.txt')
			{
				continue;
			}

			try
			{
				$objFile = new \File($objArchive->file_name);
				$objFile->write($objArchive->unzip());
				$objFile->close();

				echo '<li>Updated ' . $objArchive->file_name . '</li>';
			}
			catch (\Exception $e)
			{
				echo '<li><span style="color:#c55">Error updating ' . $objArchive->file_name . ': ' . $e->getMessage() . '</span></li>';
			}
		}

		// Delete the archive
		$this->import('Files');
		$this->Files->delete($archive);

		// Add a log entry
		$this->log('Live update from version ' . VERSION . '.' . BUILD . ' to version ' . $GLOBALS['TL_CONFIG']['latestVersion'] . ' completed', 'LiveUpdate run()', TL_GENERAL);

		echo '</ol>'
			.'<p>'
			  .'<a href="' . \Environment::get('base') . 'contao/main.php?do=maintenance&amp;act=runonce" id="continue" class="button">' . $GLOBALS['TL_LANG']['MSC']['continue'] . '</a>'
			.'</p>'
			.'</div>';

		exit;
	}
}
