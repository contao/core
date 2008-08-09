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
 * Class Automator
 *
 * Provide methods to run automated jobs.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
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
			if (!$objRoot->published || (strlen($objRoot->start) && $objRoot->start > $time) || (strlen($objRoot->stop) && $objRoot->stop < $time))
			{
				return;
			}

			// Check sitemap name
			if (!$objRoot->createSitemap || !$objRoot->sitemapName)
			{
				return;
			}

			$objRoot->reset();
		}

		// Get all published root pages
		else
		{
			$objRoot = $this->Database->prepare("SELECT id, dns, sitemapName FROM tl_page WHERE type=? AND createSitemap=1 AND sitemapName!='' AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1")
									  ->execute('root', $time, $time);
		}

		// Return if there are no pages
		if ($objRoot->numRows < 1)
		{
			return;
		}

		// Create XML file
		while($objRoot->next())
		{
			$objFile = new File($objRoot->sitemapName . '.xml');

			$objFile->write('');
			$objFile->append('<?xml version="1.0" encoding="UTF-8"?>');
			$objFile->append('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">');

			$strDomain = '';

			// Overwrite domain
			if (strlen($objRoot->dns))
			{
				$strDomain = ($this->Environment->ssl ? 'https://' : 'http://') . $objRoot->dns . TL_PATH . '/';
			}

			$arrPages = $this->getSearchablePages($objRoot->id, $strDomain);

			// HOOK: take additional pages
			if (array_key_exists('getSearchablePages', $GLOBALS['TL_HOOKS']) && is_array($GLOBALS['TL_HOOKS']['getSearchablePages']))
			{
				foreach ($GLOBALS['TL_HOOKS']['getSearchablePages'] as $callback)
				{
					$this->import($callback[0]);
					$arrPages = $this->$callback[0]->$callback[1]($arrPages, $objRoot->id);
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
	 * Purge the temporary directory
	 */
	public function purgeTempFolder()
	{
		$arrTmp = scan(TL_ROOT . '/system/tmp');

		// Remove files
		if (is_array($arrTmp))
		{
			foreach ($arrTmp as $strFile)
			{
				if ($strFile != '.htaccess')
				{
					@unlink(TL_ROOT . '/system/tmp/' . $strFile);
				}
			}
		}

		// Check for .htaccess
		if (!file_exists(TL_ROOT . '/system/tmp/.htaccess'))
		{
			$objFile = new File('/system/tmp/.htaccess');
			$objFile->write("order deny,allow\ndeny from all");
			$objFile->close();
		}

		// Add log entry
		$this->log('Purged temporary directory', 'Automator purgeTempFolder()', TL_CRON);
	}
}

?>