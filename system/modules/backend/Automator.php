<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class Automator
 *
 * Provide methods to run automated jobs.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class Automator extends Backend
{

	/**
	 * Generate Google XML sitemaps
	 * @param integer
	 */
	public function generateSitemap($intId=0)
	{
		$time = time();
		$this->removeOldFeeds();

		// Only root pages should have sitemap names
		$this->Database->execute("UPDATE tl_page SET createSitemap='', sitemapName='' WHERE type!='root'");

		// Get a particular root page
		if ($intId > 0)
		{
			do
			{
				$objRoot = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($intId);

				if ($objRoot->numRows < 1)
				{
					break;
				}

				$intId = $objRoot->pid;
			}
			while ($objRoot->type != 'root' && $intId > 0);

			// Make sure the page is published
			if (!$objRoot->published || ($objRoot->start != '' && $objRoot->start > $time) || ($objRoot->stop != '' && $objRoot->stop < $time))
			{
				return;
			}

			// Check the sitemap name
			if (!$objRoot->createSitemap || !$objRoot->sitemapName)
			{
				return;
			}

			$objRoot->reset();
		}

		// Get all published root pages
		else
		{
			$objRoot = $this->Database->execute("SELECT id, dns, language, useSSL, sitemapName FROM tl_page WHERE type='root' AND createSitemap=1 AND sitemapName!='' AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1");
		}

		// Return if there are no pages
		if ($objRoot->numRows < 1)
		{
			return;
		}

		// Create the XML file
		while($objRoot->next())
		{
			$objFile = new File($objRoot->sitemapName . '.xml');

			$objFile->write('');
			$objFile->append('<?xml version="1.0" encoding="UTF-8"?>');
			$objFile->append('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">');

			$strDomain = '';

			// Overwrite the domain
			if ($objRoot->dns != '')
			{
				$strDomain = ($objRoot->useSSL ? 'https://' : 'http://') . $objRoot->dns . TL_PATH . '/';
			}

			$arrPages = $this->findSearchablePages($objRoot->id, $strDomain, true, $objRoot->language);

			// HOOK: take additional pages
			if (isset($GLOBALS['TL_HOOKS']['getSearchablePages']) && is_array($GLOBALS['TL_HOOKS']['getSearchablePages']))
			{
				foreach ($GLOBALS['TL_HOOKS']['getSearchablePages'] as $callback)
				{
					$this->import($callback[0]);
					$arrPages = $this->$callback[0]->$callback[1]($arrPages, $objRoot->id, true, $objRoot->language);
				}
			}

			// Add pages
			foreach ($arrPages as $strUrl)
			{
				$strUrl = rawurlencode($strUrl);
				$strUrl = str_replace(array('%2F', '%3F', '%3D', '%26', '%3A//'), array('/', '?', '=', '&', '://'), $strUrl);
				$strUrl = ampersand($strUrl, true);

				$objFile->append('  <url><loc>' . $strUrl . '</loc></url>');
			}

			$objFile->append('</urlset>');
			$objFile->close();

			// Add log entry
			$this->log('Generated sitemap "' . $objRoot->sitemapName . '.xml"', 'Automator generateSitemap()', TL_CRON);
		}
	}


	/**
	 * Purge the thumbnail directory
	 */
	public function purgeHtmlFolder()
	{
		$arrHtml = scan(TL_ROOT . '/system/html', true);

		// Remove files
		if (is_array($arrHtml))
		{
			foreach ($arrHtml as $strFile)
			{
				if ($strFile != 'index.html' && $strFile != 'cron.txt' && !is_dir(TL_ROOT . '/system/html/' . $strFile))
				{
					@unlink(TL_ROOT . '/system/html/' . $strFile);
				}
			}
		}

		// Add log entry
		$this->log('Purged the thumbnail directory', 'Automator purgeHtmlFolder()', TL_CRON);
	}


	/**
	 * Purge the scripts directory
	 */
	public function purgeScriptsFolder()
	{
		$arrScripts = scan(TL_ROOT . '/system/scripts', true);

		// Remove files
		if (is_array($arrScripts))
		{
			foreach ($arrScripts as $strFile)
			{
				if ($strFile != 'index.html' && !is_dir(TL_ROOT . '/system/scripts/' . $strFile))
				{
					unlink(TL_ROOT . '/system/scripts/' . $strFile);
				}
			}
		}

		// Generate the style sheets (see #2400)
		$this->import('StyleSheets');
		$this->StyleSheets->updateStyleSheets();

		// Add log entry
		$this->log('Purged the scripts directory', 'Automator purgeScriptsFolder()', TL_CRON);
	}


	/**
	 * Purge the temporary directory
	 */
	public function purgeTempFolder()
	{
		$arrTmp = scan(TL_ROOT . '/system/tmp', true);

		// Remove files
		if (is_array($arrTmp))
		{
			foreach ($arrTmp as $strFile)
			{
				if ($strFile != '.htaccess' && !is_dir(TL_ROOT . '/system/tmp/' . $strFile))
				{
					@unlink(TL_ROOT . '/system/tmp/' . $strFile);
				}
			}
		}

		// Check for .htaccess
		if (!file_exists(TL_ROOT . '/system/tmp/.htaccess'))
		{
			$objFolder = new Folder('system/tmp');
			$objFolder->protect();
		}

		// Add log entry
		$this->log('Purged the temporary directory', 'Automator purgeTempFolder()', TL_CRON);
	}


	/**
	 * Check for new Contao versions
	 */
	public function checkForUpdates()
	{
		if (!is_numeric(BUILD))
		{
			return;
		}

		$objRequest = new Request();
		$objRequest->send($GLOBALS['TL_CONFIG']['liveUpdateBase'] . (LONG_TERM_SUPPORT ? 'lts-version.txt' : 'version.txt'));

		if (!$objRequest->hasError())
		{
			$this->Config->update("\$GLOBALS['TL_CONFIG']['latestVersion']", $objRequest->response);
			$GLOBALS['TL_CONFIG']['latestVersion'] = $objRequest->response;
		}

		// Add log entry
		$this->log('Checked for Contao updates', 'Automator checkForUpdates()', TL_CRON);
	}
}

?>