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
use \Backend, \File, \Folder, \Request;


/**
 * Class Automator
 *
 * Provide methods to run automated jobs.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class Automator extends Backend
{

	/**
	 * Check for new Contao versions
	 * @return void
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

		// Add a log entry
		$this->log('Checked for Contao updates', 'Automator checkForUpdates()', TL_CRON);
	}


	/**
	 * Purge the search tables
	 * @return void
	 */
	public function purgeSearchTables()
	{
		// Truncate the tables
		$this->Database->execute("TRUNCATE TABLE tl_search");
		$this->Database->execute("TRUNCATE TABLE tl_search_index");

		// Purge the cache folder
		$objFolder = new Folder('system/cache/search');
		$objFolder->purge();

		// Add a log entry
		$this->log('Purged the search tables', 'Automator purgeSearchTables()', TL_CRON);
	}


	/**
	 * Purge the undo table
	 * @return void
	 */
	public function purgeUndoTable()
	{
		// Truncate the table
		$this->Database->execute("TRUNCATE TABLE tl_undo");

		// Add a log entry
		$this->log('Purged the undo table', 'Automator purgeUndoTable()', TL_CRON);
	}


	/**
	 * Purge the version table
	 * @return void
	 */
	public function purgeVersionTable()
	{
		// Truncate the table
		$this->Database->execute("TRUNCATE TABLE tl_version");

		// Add a log entry
		$this->log('Purged the undo table', 'Automator purgeVersionTable()', TL_CRON);
	}


	/**
	 * Purge the image cache
	 * @return void
	 */
	public function purgeImageCache()
	{
		// Walk through the subfolders
		foreach (scan(TL_ROOT . '/assets/images') as $dir)
		{
			if ($dir != 'index.html')
			{
				// Purge the folder
				$objFolder = new Folder('assets/images/' . $dir);
				$objFolder->purge();

				// Restore the index.html file
				$objFile = new File('assets/contao/index.html');
				$objFile->copyTo('assets/images/' . $dir . '/index.html');
			}
		}

		// Also empty the page cache so there are no links to deleted images
		$this->purgePageCache();

		// Add a log entry
		$this->log('Purged the image cache', 'Automator purgeImageCache()', TL_CRON);
	}


	/**
	 * Purge the script cache
	 * @return void
	 */
	public function purgeScriptCache()
	{
		// assets/js and assets/css
		foreach (array('assets/js', 'assets/css') as $dir)
		{
			// Purge the folder
			$objFolder = new Folder($dir);
			$objFolder->purge();

			// Restore the index.html file
			$objFile = new File('assets/contao/index.html');
			$objFile->copyTo($dir . '/index.html');
		}

		// Recreate the internal style sheets
		$this->import('StyleSheets');
		$this->StyleSheets->updateStylesheets();

		// Also empty the page cache so there are no links to deleted scripts
		$this->purgePageCache();

		// Add a log entry
		$this->log('Purged the script cache', 'Automator purgeScriptCache()', TL_CRON);
	}


	/**
	 * Purge the page cache
	 * @return void
	 */
	public function purgePageCache()
	{
		// Purge the folder
		$objFolder = new Folder('system/cache/html');
		$objFolder->purge();

		// Add a log entry
		$this->log('Purged the page cache', 'Automator purgePageCache()', TL_CRON);
	}


	/**
	 * Purge the internal cache
	 * @return void
	 */
	public function purgeInternalCache()
	{
		// system/cache/dca and system/cache/sql
		foreach (array('system/cache/dca', 'system/cache/sql') as $dir)
		{
			// Purge the folder
			$objFolder = new Folder($dir);
			$objFolder->purge();
		}

		// Walk through the language subfolders
		foreach (scan(TL_ROOT . '/system/cache/language') as $dir)
		{
			// Remove the folder
			$objFolder = new Folder('system/cache/language/' . $dir);
			$objFolder->delete();
		}

		// Add a log entry
		$this->log('Purged the internal cache', 'Automator purgeInternalCache()', TL_CRON);
	}


	/**
	 * Purge the temp folder
	 * @return void
	 */
	public function purgeTempFolder()
	{
		// Purge the folder
		$objFolder = new Folder('system/tmp');
		$objFolder->purge();

		// Restore the .htaccess file
		$objFile = new File('system/logs/.htaccess');
		$objFile->copyTo('system/tmp/.htaccess');

		// Add a log entry
		$this->log('Purged the temp folder', 'Automator purgeTempFolder()', TL_CRON);
	}


	/**
	 * Regenerate the XML files
	 * @return void
	 */
	public function generateXmlFiles()
	{
		// Sitemaps
		$this->generateSitemap();

		// HOOK: add custom jobs
		if (isset($GLOBALS['TL_HOOKS']['generateXmlFiles']) && is_array($GLOBALS['TL_HOOKS']['generateXmlFiles']))
		{
			foreach ($GLOBALS['TL_HOOKS']['generateXmlFiles'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]();
			}
		}

		// Also empty the page cache so there are no links to deleted files
		$this->purgePageCache();

		// Add a log entry
		$this->log('Regenerated the XML files', 'Automator generateXmlFiles()', TL_CRON);
	}


	/**
	 * Generate the Google XML sitemaps
	 * @param integer
	 * @return void
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
			$objFile = new File('share/' . $objRoot->sitemapName . '.xml');

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
	 * Rotate the log files
	 * @return void
	 */
	public function rotateLogs()
	{
		$arrFiles = preg_grep('/\.log$/', scan(TL_ROOT . '/system/logs'));

		foreach ($arrFiles as $strFile)
		{
			// Delete the oldest file
			if (file_exists(TL_ROOT . '/system/logs/' . $strFile . '.9'))
			{
				$objFile = new File('system/logs/' . $strFile . '.9');
				$objFile->delete();
			}

			// Rotate the files (e.g. error.log.4 becomes error.log.5)
			for ($i=8; $i>0; $i--)
			{
				$strGzName = 'system/logs/' . $strFile . '.' . $i;

				if (file_exists(TL_ROOT . '/' . $strGzName))
				{
					$objFile = new File($strGzName);
					$objFile->renameTo('system/logs/' . $strFile . '.' . ($i+1));
				}
			}

			// Add .1 to the latest file
			$objFile = new File('system/logs/' . $strFile);
			$objFile->renameTo('system/logs/' . $strFile . '.1');
		}
	}
}
