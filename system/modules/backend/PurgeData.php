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
 * Class PurgeData
 *
 * Maintenance module "purge data".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class PurgeData extends Backend implements executable
{

	/**
	 * Return true if the module is active
	 * @return boolean
	 */
	public function isActive()
	{
		return ($this->Input->post('FORM_SUBMIT') == 'tl_purge');
	}


	/**
	 * Generate the module
	 * @return string
	 */
	public function run()
	{
		$arrCacheTables = array();
		$objTemplate = new BackendTemplate('be_purge_data');
		$objTemplate->isActive = $this->isActive();

		// Confirmation message
		if ($_SESSION['CLEAR_CACHE_CONFIRM'] != '')
		{
			$objTemplate->cacheMessage = sprintf('<p class="tl_confirm">%s</p>' . "\n", $_SESSION['CLEAR_CACHE_CONFIRM']);
			$_SESSION['CLEAR_CACHE_CONFIRM'] = '';
		}

		// Add potential error messages
		if (is_array($_SESSION['TL_ERROR']) && !empty($_SESSION['TL_ERROR']))
		{
			foreach ($_SESSION['TL_ERROR'] as $message)
			{
				$objTemplate->cacheMessage .= sprintf('<p class="tl_error">%s</p>' . "\n", $message);
			}

			$_SESSION['TL_ERROR'] = array();
		}

		// Purge the resources
		if ($this->Input->post('FORM_SUBMIT') == 'tl_purge')
		{
			$tables = deserialize($this->Input->post('tables'));

			if (!is_array($tables))
			{
				$this->reload();
			}

			$this->import('Automator');
			$this->import('StyleSheets');

			foreach ($tables as $table)
			{
				// Html folder
				if ($table == 'html_folder')
				{
					$this->Automator->purgeHtmlFolder();
				}

				// Scripts folder
				elseif ($table == 'scripts_folder')
				{
					$this->Automator->purgeScriptsFolder();
				}

				// Temporary folder
				elseif ($table == 'temp_folder')
				{
					$this->Automator->purgeTempFolder();
				}

				// CSS files
				elseif ($table == 'css_files')
				{
					$this->StyleSheets->updateStyleSheets();
				}

				// XML files
				elseif ($table == 'xml_files')
				{
					// HOOK: use the googlesitemap module
					if (in_array('googlesitemap', $this->Config->getActiveModules()))
					{
						$this->import('GoogleSitemap');
						$this->GoogleSitemap->generateSitemap();
					}
					else
					{
						$this->Automator->generateSitemap();
					}

					// HOOK: recreate news feeds
					if (in_array('news', $this->Config->getActiveModules()))
					{
						$this->import('News');
						$this->News->generateFeeds();
					}

					// HOOK: recreate calendar feeds
					if (in_array('calendar', $this->Config->getActiveModules()))
					{
						$this->import('Calendar');
						$this->Calendar->generateFeeds();
					}
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

		// Get all cachable tables from TL_CACHE
		foreach ($GLOBALS['TL_CACHE'] as $k=>$v)
		{
			$objCount = $this->Database->execute("SELECT COUNT(*) AS count FROM " . $v);

			$arrCacheTables[] = array
			(
				'id' => 'cache_' . $k,
				'value' => specialchars($v),
				'name' => $v,
				'entries' => sprintf($GLOBALS['TL_LANG']['MSC']['entries'], $objCount->count),
				'size' => $this->getReadableSize($this->Database->getSizeOf($v))
			);
		}

		$objTemplate->action = ampersand($this->Environment->request);
		$objTemplate->selectAll = $GLOBALS['TL_LANG']['MSC']['selectAll'];
		$objTemplate->cacheHtml = $GLOBALS['TL_LANG']['tl_maintenance']['clearHtml'];
		$objTemplate->cacheScripts = $GLOBALS['TL_LANG']['tl_maintenance']['clearScripts'];
		$objTemplate->cacheTmp = $GLOBALS['TL_LANG']['tl_maintenance']['clearTemp'];
		$objTemplate->cacheXml = $GLOBALS['TL_LANG']['tl_maintenance']['clearXml'];
		$objTemplate->cacheCss = $GLOBALS['TL_LANG']['tl_maintenance']['clearCss'];
		$objTemplate->cacheHeadline = $GLOBALS['TL_LANG']['tl_maintenance']['clearCache'];
		$objTemplate->cacheLabel = $GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0];
		$objTemplate->htmlEntries = sprintf($GLOBALS['TL_LANG']['MSC']['entries'], (count(scan(TL_ROOT . '/system/html')) - 2));
		$objTemplate->scriptEntries = sprintf($GLOBALS['TL_LANG']['MSC']['entries'], (count(scan(TL_ROOT . '/system/scripts')) - 1));
		$objTemplate->cacheEntries = sprintf($GLOBALS['TL_LANG']['MSC']['entries'], (count(scan(TL_ROOT . '/system/tmp')) - 1));
		$objTemplate->cacheHelp = ($GLOBALS['TL_CONFIG']['showHelp'] && strlen($GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1])) ? $GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] : '';
		$objTemplate->cacheSubmit = specialchars($GLOBALS['TL_LANG']['tl_maintenance']['clearCache']);
		$objTemplate->cacheTables = $arrCacheTables;

		return $objTemplate->parse();
	}
}

?>