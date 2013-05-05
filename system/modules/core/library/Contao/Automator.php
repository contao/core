<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class Automator
 *
 * Provide methods to run automated jobs.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Library
 */
class Automator extends \System
{

	/**
	 * Check for new \Contao versions
	 */
	public function checkForUpdates()
	{
		if (!is_numeric(BUILD))
		{
			return;
		}

		$objRequest = new \Request();
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
	 */
	public function purgeSearchTables()
	{
		$objDatabase = \Database::getInstance();

		// Truncate the tables
		$objDatabase->execute("TRUNCATE TABLE tl_search");
		$objDatabase->execute("TRUNCATE TABLE tl_search_index");

		// Purge the cache folder
		$objFolder = new \Folder('system/cache/search');
		$objFolder->purge();

		// Add a log entry
		$this->log('Purged the search tables', 'Automator purgeSearchTables()', TL_CRON);
	}


	/**
	 * Purge the undo table
	 */
	public function purgeUndoTable()
	{
		$objDatabase = \Database::getInstance();

		// Truncate the table
		$objDatabase->execute("TRUNCATE TABLE tl_undo");

		// Add a log entry
		$this->log('Purged the undo table', 'Automator purgeUndoTable()', TL_CRON);
	}


	/**
	 * Purge the version table
	 */
	public function purgeVersionTable()
	{
		$objDatabase = \Database::getInstance();

		// Truncate the table
		$objDatabase->execute("TRUNCATE TABLE tl_version");

		// Add a log entry
		$this->log('Purged the undo table', 'Automator purgeVersionTable()', TL_CRON);
	}


	/**
	 * Purge the system log
	 */
	public function purgeSystemLog()
	{
		$objDatabase = \Database::getInstance();

		// Truncate the table
		$objDatabase->execute("TRUNCATE TABLE tl_log");

		// Add a log entry
		$this->log('Purged the system log', 'Automator purgeSystemLog()', TL_CRON);
	}


	/**
	 * Purge the image cache
	 */
	public function purgeImageCache()
	{
		// Walk through the subfolders
		foreach (scan(TL_ROOT . '/assets/images') as $dir)
		{
			if ($dir != 'index.html' && strncmp($dir, '.', 1) !== 0)
			{
				// Purge the folder
				$objFolder = new \Folder('assets/images/' . $dir);
				$objFolder->purge();

				// Restore the index.html file
				$objFile = new \File('templates/index.html', true);
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
	 */
	public function purgeScriptCache()
	{
		// assets/js and assets/css
		foreach (array('assets/js', 'assets/css') as $dir)
		{
			// Purge the folder
			$objFolder = new \Folder($dir);
			$objFolder->purge();

			// Restore the index.html file
			$objFile = new \File('templates/index.html', true);
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
	 */
	public function purgePageCache()
	{
		// Purge the folder
		$objFolder = new \Folder('system/cache/html');
		$objFolder->purge();

		// Add a log entry
		$this->log('Purged the page cache', 'Automator purgePageCache()', TL_CRON);
	}


	/**
	 * Purge the internal cache
	 */
	public function purgeInternalCache()
	{
		// Check whether the cache exists
		if (is_dir(TL_ROOT . '/system/cache/dca'))
		{
			foreach (array('config', 'dca', 'language', 'sql') as $dir)
			{
				// Purge the folder
				$objFolder = new \Folder('system/cache/' . $dir);
				$objFolder->delete();
			}
		}

		// Add a log entry
		$this->log('Purged the internal cache', 'Automator purgeInternalCache()', TL_CRON);
	}


	/**
	 * Purge the temp folder
	 */
	public function purgeTempFolder()
	{
		// Purge the folder
		$objFolder = new \Folder('system/tmp');
		$objFolder->purge();

		// Restore the .htaccess file
		$objFile = new \File('system/logs/.htaccess', true);
		$objFile->copyTo('system/tmp/.htaccess');

		// Add a log entry
		$this->log('Purged the temp folder', 'Automator purgeTempFolder()', TL_CRON);
	}


	/**
	 * Regenerate the XML files
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
	 * Remove old XML files from the share directory
	 *
	 * @param boolean $blnReturn If true, only return the finds and don't delete
	 *
	 * @return array An array of old XML files
	 */
	public function purgeXmlFiles($blnReturn=false)
	{
		$arrFeeds = array();
		$objDatabase = \Database::getInstance();

		// XML sitemaps
		$objFeeds = $objDatabase->execute("SELECT sitemapName FROM tl_page WHERE type='root' AND createSitemap=1 AND sitemapName!=''");

		while ($objFeeds->next())
		{
			$arrFeeds[] = $objFeeds->sitemapName;
		}

		// HOOK: preserve third party feeds
		if (isset($GLOBALS['TL_HOOKS']['removeOldFeeds']) && is_array($GLOBALS['TL_HOOKS']['removeOldFeeds']))
		{
			foreach ($GLOBALS['TL_HOOKS']['removeOldFeeds'] as $callback)
			{
				$this->import($callback[0]);
				$arrFeeds = array_merge($arrFeeds, $this->$callback[0]->$callback[1]());
			}
		}

		// Delete the old files
		if (!$blnReturn)
		{
			foreach (scan(TL_ROOT . '/share') as $file)
			{
				$objFile = new \File('share/' . $file, true);

				if ($objFile->extension == 'xml' && !in_array($objFile->filename, $arrFeeds))
				{
					$objFile->delete();
				}
			}
		}

		return $arrFeeds;
	}


	/**
	 * Generate the Google XML sitemaps
	 * @param integer
	 */
	public function generateSitemap($intId=0)
	{
		$time = time();
		$objDatabase = \Database::getInstance();

		$this->purgeXmlFiles();

		// Only root pages should have sitemap names
		$objDatabase->execute("UPDATE tl_page SET createSitemap='', sitemapName='' WHERE type!='root'");

		// Get a particular root page
		if ($intId > 0)
		{
			do
			{
				$objRoot = $objDatabase->prepare("SELECT * FROM tl_page WHERE id=?")
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
			$objRoot = $objDatabase->execute("SELECT id, dns, language, useSSL, sitemapName FROM tl_page WHERE type='root' AND createSitemap=1 AND sitemapName!='' AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1");
		}

		// Return if there are no pages
		if ($objRoot->numRows < 1)
		{
			return;
		}

		// Create the XML file
		while($objRoot->next())
		{
			$objFile = new \File('share/' . $objRoot->sitemapName . '.xml', true);

			$objFile->truncate();
			$objFile->append('<?xml version="1.0" encoding="UTF-8"?>');
			$objFile->append('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">');

			$strDomain = '';

			// Overwrite the domain
			if ($objRoot->dns != '')
			{
				$strDomain = ($objRoot->useSSL ? 'https://' : 'http://') . $objRoot->dns . TL_PATH . '/';
			}

			$arrPages = \Backend::findSearchablePages($objRoot->id, $strDomain, true, $objRoot->language);

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

			// Add a log entry
			$this->log('Generated sitemap "' . $objRoot->sitemapName . '.xml"', 'Automator generateSitemap()', TL_CRON);
		}
	}


	/**
	 * Rotate the log files
	 */
	public function rotateLogs()
	{
		$arrFiles = preg_grep('/\.log$/', scan(TL_ROOT . '/system/logs'));

		foreach ($arrFiles as $strFile)
		{
			$objFile = new \File('system/logs/' . $strFile . '.9', true);

			// Delete the oldest file
			if ($objFile->exists())
			{
				$objFile->delete();
			}

			// Rotate the files (e.g. error.log.4 becomes error.log.5)
			for ($i=8; $i>0; $i--)
			{
				$strGzName = 'system/logs/' . $strFile . '.' . $i;

				if (file_exists(TL_ROOT . '/' . $strGzName))
				{
					$objFile = new \File($strGzName, true);
					$objFile->renameTo('system/logs/' . $strFile . '.' . ($i+1));
				}
			}

			// Add .1 to the latest file
			$objFile = new \File('system/logs/' . $strFile, true);
			$objFile->renameTo('system/logs/' . $strFile . '.1');
		}
	}


	/**
	 * Build the internal cache
	 */
	public function generateInternalCache()
	{
		// Purge
		$this->purgeInternalCache();

		// Rebuild
		$this->generateConfigCache();
		$this->generateDcaCache();
		$this->generateLanguageCache();
		$this->generateDcaExtracts();
	}


	/**
	 * Create the config cache files
	 */
	public function generateConfigCache()
	{
		// Generate the class/template laoder cache file
		$objCacheFile = new \File('system/cache/config/autoload.php', true);
		$objCacheFile->write('<?php '); // add one space to prevent the "unexpected $end" error

		foreach (\ModuleLoader::getActive() as $strModule)
		{
			$strFile = 'system/modules/' . $strModule . '/config/autoload.php';

			if (file_exists(TL_ROOT . '/' . $strFile))
			{
				$objCacheFile->append(static::readPhpFileWithoutTags($strFile));
			}
		}

		// Close the file (moves it to its final destination)
		$objCacheFile->close();

		// Generate the module loader cache file
		$objCacheFile = new \File('system/cache/config/modules.php', true);
		$objCacheFile->write('<?php '); // add one space to prevent the "unexpected $end" error

		$strContent = "\n\n";
		$strContent .= "/**\n * Active modules\n */\n";
		$strContent .= "static::\$active = array\n";
		$strContent .= "(\n";

		foreach (\ModuleLoader::getActive() as $strModule)
		{
			$strContent .= "\t'$strModule',\n";
		}

		$strContent .= ");\n\n";
		$strContent .= "/**\n * Disabled modules\n */\n";
		$strContent .= "static::\$disabled = array\n";
		$strContent .= "(\n";

		foreach (\ModuleLoader::getDisabled() as $strModule)
		{
			$strContent .= "\t'$strModule',\n";
		}

		$strContent .= ");";
		$objCacheFile->append($strContent);

		// Close the file (moves it to its final destination)
		$objCacheFile->close();

		// Generate the config cache file
		$objCacheFile = new \File('system/cache/config/config.php', true);
		$objCacheFile->write('<?php '); // add one space to prevent the "unexpected $end" error

		foreach (\ModuleLoader::getActive() as $strModule)
		{
			$strFile = 'system/modules/' . $strModule . '/config/config.php';

			if (file_exists(TL_ROOT . '/' . $strFile))
			{
				$objCacheFile->append(static::readPhpFileWithoutTags($strFile));
			}
		}

		// Close the file (moves it to its final destination)
		$objCacheFile->close();

		// Add a log entry
		$this->log('Generated the autoload cache', 'Automator generateDcaCache()', TL_CRON);
	}


	/**
	 * Create the data container cache files
	 */
	public function generateDcaCache()
	{
		$arrFiles = array();

		// Parse all active modules
		foreach ($this->Config->getActiveModules() as $strModule)
		{
			$strDir = 'system/modules/' . $strModule . '/dca';

			if (!is_dir(TL_ROOT . '/' . $strDir))
			{
				continue;
			}

			foreach (scan(TL_ROOT . '/' . $strDir) as $strFile)
			{
				if (in_array($strFile, $arrFiles) || $strFile == '.htaccess')
				{
					continue;
				}

				$arrFiles[] = substr($strFile, 0, -4);
			}
		}

		// Create one file per table
		foreach ($arrFiles as $strName)
		{
			// Generate the cache file
			$objCacheFile = new \File('system/cache/dca/' . $strName . '.php', true);
			$objCacheFile->write('<?php '); // add one space to prevent the "unexpected $end" error

			// Parse all active modules
			foreach ($this->Config->getActiveModules() as $strModule)
			{
				$strFile = 'system/modules/' . $strModule . '/dca/' . $strName . '.php';

				if (file_exists(TL_ROOT . '/' . $strFile))
				{
					$objCacheFile->append(static::readPhpFileWithoutTags($strFile));
				}
			}

			// Close the file (moves it to its final destination)
			$objCacheFile->close();
		}

		// Add a log entry
		$this->log('Generated the DCA cache', 'Automator generateDcaCache()', TL_CRON);
	}


	/**
	 * Create the language cache files
	 */
	public function generateLanguageCache()
	{
		$arrLanguages = scan(TL_ROOT . '/system/modules/core/languages');

		foreach ($arrLanguages as $strLanguage)
		{
			$arrFiles = array();

			// Parse all active modules
			foreach ($this->Config->getActiveModules() as $strModule)
			{
				$strDir = 'system/modules/' . $strModule . '/languages/' . $strLanguage;

				if (!is_dir(TL_ROOT . '/' . $strDir))
				{
					continue;
				}

				foreach (scan(TL_ROOT . '/' . $strDir) as $strFile)
				{
					if (in_array($strFile, $arrFiles) || $strFile == '.htaccess')
					{
						continue;
					}

					$arrFiles[] = substr($strFile, 0, -4);
				}
			}

			// Create one file per table
			foreach ($arrFiles as $strName)
			{
				$strCacheFile = 'system/cache/language/' . $strLanguage . '/' . $strName . '.php';

				// Add a short header with links to transifex.com
				$strHeader = "<?php\n\n"
						   . "/**\n"
						   . " * Contao Open Source CMS\n"
						   . " * \n"
						   . " * Copyright (c) 2005-2013 Leo Feyer\n"
						   . " * \n"
						   . " * Core translations are managed using Transifex. To create a new translation\n"
						   . " * or to help to maintain an existing one, please register at transifex.com.\n"
						   . " * \n"
						   . " * @link http://help.transifex.com/intro/translating.html\n"
						   . " * @link https://www.transifex.com/projects/p/contao/language/%s/\n"
						   . " * \n"
						   . " * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL\n"
						   . " */\n";

				// Generate the cache file
				$objCacheFile = new \File($strCacheFile, true);
				$objCacheFile->write(sprintf($strHeader, $strLanguage));

				// Parse all active modules and append to the cache file
				foreach ($this->Config->getActiveModules() as $strModule)
				{
					$strFile = 'system/modules/' . $strModule . '/languages/' . $strLanguage . '/' . $strName;

					if (file_exists(TL_ROOT . '/' . $strFile . '.xlf'))
					{
						$objCacheFile->append(static::convertXlfToPhp($strFile . '.xlf', $strLanguage));
					}
					elseif (file_exists(TL_ROOT . '/' . $strFile . '.php'))
					{
						$objCacheFile->append(static::readPhpFileWithoutTags($strFile . '.php'));
					}
				}

				// Close the file (moves it to its final destination)
				$objCacheFile->close();
			}
		}

		// Add a log entry
		$this->log('Generated the language cache', 'Automator generateLanguageCache()', TL_CRON);
	}


	/**
	 * Create the DCA extract cache files
	 */
	public function generateDcaExtracts()
	{
		$included = array();
		$arrExtracts = array();

		// Only check the active modules (see #4541)
		foreach ($this->Config->getActiveModules() as $strModule)
		{
			$strDir = 'system/modules/' . $strModule . '/dca';

			if (!is_dir(TL_ROOT . '/' . $strDir))
			{
				continue;
			}

			foreach (scan(TL_ROOT . '/' . $strDir) as $strFile)
			{
				if (in_array($strFile, $included) || $strFile == '.htaccess')
				{
					continue;
				}

				$strTable = substr($strFile, 0, -4);
				$objExtract = new \DcaExtractor($strTable);

				if ($objExtract->isDbTable())
				{
					$arrExtracts[$strTable] = $objExtract;
				}

				$included[] = $strFile;
			}
		}

		// Create one file per table
		foreach ($arrExtracts as $strTable=>$objExtract)
		{
			// Create the file
			$objFile = new \File('system/cache/sql/' . $strTable . '.php', true);
			$objFile->write("<?php\n\n");

			// Meta
			$arrMeta = $objExtract->getMeta();

			$objFile->append("\$this->arrMeta = array\n(");
			$objFile->append("\t'engine' => '{$arrMeta['engine']}',");
			$objFile->append("\t'charset' => '{$arrMeta['charset']}',");
			$objFile->append(');', "\n\n");

			// Fields
			$arrFields = $objExtract->getFields();
			$objFile->append("\$this->arrFields = array\n(");

			foreach ($arrFields as $field=>$sql)
			{
				$objFile->append("\t'$field' => \"$sql\",");
			}

			$objFile->append(');', "\n\n");

			// Keys
			$arrKeys = $objExtract->getKeys();
			$objFile->append("\$this->arrKeys = array\n(");

			foreach ($arrKeys as $field=>$type)
			{
				$objFile->append("\t'$field' => '$type',");
			}

			$objFile->append(');', "\n\n");

			// Relations
			$arrRelations = $objExtract->getRelations();
			$objFile->append("\$this->arrRelations = array\n(");

			foreach ($arrRelations as $field=>$config)
			{
				$objFile->append("\t'$field' => array\n\t(");

				foreach ($config as $k=>$v)
				{
					$objFile->append("\t\t'$k' => '$v',");
				}

				$objFile->append("\t),");
			}

			$objFile->append(');', "\n\n");

			// Set the database table flag
			$objFile->append("\$this->blnIsDbTable = true;", "\n");

			// Close the file (moves it to its final destination)
			$objFile->close();
		}

		// Add a log entry
		$this->log('Generated the DCA extracts', 'Automator generateDcaExtracts()', TL_CRON);
	}
}
