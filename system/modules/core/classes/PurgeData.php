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
 * Class PurgeData
 *
 * Maintenance module "purge data".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class PurgeData extends \Backend implements \executable
{

	/**
	 * Return true if the module is active
	 * @return boolean
	 */
	public function isActive()
	{
		return (\Input::post('FORM_SUBMIT') == 'tl_purge');
	}


	/**
	 * Generate the module
	 * @return string
	 */
	public function run()
	{
		$arrJobs = array();
		$objTemplate = new \BackendTemplate('be_purge_data');
		$objTemplate->isActive = $this->isActive();

		// Confirmation message
		if ($_SESSION['CLEAR_CACHE_CONFIRM'] != '')
		{
			$objTemplate->message = sprintf('<p class="tl_confirm">%s</p>' . "\n", $_SESSION['CLEAR_CACHE_CONFIRM']);
			$_SESSION['CLEAR_CACHE_CONFIRM'] = '';
		}

		// Add potential error messages
		if (is_array($_SESSION['TL_ERROR']) && !empty($_SESSION['TL_ERROR']))
		{
			foreach ($_SESSION['TL_ERROR'] as $message)
			{
				$objTemplate->message .= sprintf('<p class="tl_error">%s</p>' . "\n", $message);
			}

			$_SESSION['TL_ERROR'] = array();
		}

		// Run the jobs
		if (\Input::post('FORM_SUBMIT') == 'tl_purge')
		{
			$purge = \Input::post('purge');

			if (is_array($purge) && !empty($purge))
			{
				foreach ($purge as $group=>$jobs)
				{
					foreach ($jobs as $job)
					{
						list($class, $method) = $GLOBALS['TL_PURGE'][$group][$job]['callback'];
						$this->import($class);
						$this->$class->$method();
					}
				}
			}

			$_SESSION['CLEAR_CACHE_CONFIRM'] = $GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'];
			$this->reload();
		}

		// Tables
		foreach ($GLOBALS['TL_PURGE']['tables'] as $key=>$config)
		{
			$arrJobs[$key] = array
			(
				'id' => 'purge_' . $key,
				'title' => $GLOBALS['TL_LANG']['tl_maintenance_jobs'][$key][0],
				'description' => $GLOBALS['TL_LANG']['tl_maintenance_jobs'][$key][1],
				'group' => 'tables',
				'affected' => ''
			);

			// Get the current table size
			foreach ($config['affected'] as $table)
			{
				$objCount = $this->Database->execute("SELECT COUNT(*) AS count FROM " . $table);
				$arrJobs[$key]['affected'] .= '<br>' . $table . ': <span>' . sprintf($GLOBALS['TL_LANG']['MSC']['entries'], $objCount->count) . ', ' . $this->getReadableSize($this->Database->getSizeOf($table), 0) . '</span>';
			}
		}

		// Folders
		foreach ($GLOBALS['TL_PURGE']['folders'] as $key=>$config)
		{
			$arrJobs[$key] = array
			(
				'id' => 'purge_' . $key,
				'title' => $GLOBALS['TL_LANG']['tl_maintenance_jobs'][$key][0],
				'description' => $GLOBALS['TL_LANG']['tl_maintenance_jobs'][$key][1],
				'group' => 'folders',
				'affected' => ''
			);

			// Get the current folder size
			foreach ($config['affected'] as $folder)
			{
				// Create the folder if it does not yet exist
				if (!is_dir(TL_ROOT . '/' . $folder))
				{
					\Files::getInstance()->mkdir($folder);
				}

				$total = 0;

				// Recursively scan all subfolders
				$objFiles = new \RecursiveIteratorIterator(
					new \RecursiveDirectoryIterator(TL_ROOT . '/' . $folder, \FilesystemIterator::UNIX_PATHS)
				);

				// Ignore the index.html and .htaccess files
				foreach ($objFiles as $objFile)
				{
					if ($objFile->isFile() && $objFile->getFilename() != '.htaccess' && $objFile->getFilename() != 'index.html')
					{
						++$total;
					}
				}

				$arrJobs[$key]['affected'] .= '<br>' . $folder . ': <span>' . sprintf($GLOBALS['TL_LANG']['MSC']['files'], $total) . '</span>';
			}
		}

		// Custom
		foreach ($GLOBALS['TL_PURGE']['custom'] as $key=>$job)
		{
			$arrJobs[$key] = array
			(
				'id' => 'purge_' . $key,
				'title' => $GLOBALS['TL_LANG']['tl_maintenance_jobs'][$key][0],
				'description' => $GLOBALS['TL_LANG']['tl_maintenance_jobs'][$key][1],
				'group' => 'custom'
			);
		}

		$objTemplate->jobs = $arrJobs;
		$objTemplate->action = ampersand(\Environment::get('request'));
		$objTemplate->headline = $GLOBALS['TL_LANG']['tl_maintenance']['clearCache'];
		$objTemplate->job = $GLOBALS['TL_LANG']['tl_maintenance']['job'];
		$objTemplate->description = $GLOBALS['TL_LANG']['tl_maintenance']['description'];
		$objTemplate->submit = specialchars($GLOBALS['TL_LANG']['tl_maintenance']['clearCache']);
		$objTemplate->help = ($GLOBALS['TL_CONFIG']['showHelp'] && ($GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] != '')) ? $GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] : '';

		return $objTemplate->parse();
	}
}
