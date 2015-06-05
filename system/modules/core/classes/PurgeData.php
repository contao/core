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
 * Maintenance module "purge data".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class PurgeData extends \Backend implements \executable
{

	/**
	 * Return true if the module is active
	 *
	 * @return boolean
	 */
	public function isActive()
	{
		return (\Input::post('FORM_SUBMIT') == 'tl_purge');
	}


	/**
	 * Generate the module
	 *
	 * @return string
	 */
	public function run()
	{
		$arrJobs = array();

		/** @var \BackendTemplate|object $objTemplate */
		$objTemplate = new \BackendTemplate('be_purge_data');
		$objTemplate->isActive = $this->isActive();

		// Confirmation message
		if ($_SESSION['CLEAR_CACHE_CONFIRM'] != '')
		{
			$objTemplate->message = sprintf('<p class="tl_confirm">%s</p>' . "\n", $_SESSION['CLEAR_CACHE_CONFIRM']);
			$_SESSION['CLEAR_CACHE_CONFIRM'] = '';
		}

		// Add potential error messages
		if (!empty($_SESSION['TL_ERROR']) && is_array($_SESSION['TL_ERROR']))
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

			if (!empty($purge) && is_array($purge))
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
				$total = 0;

				// Only check existing folders
				if (is_dir(TL_ROOT . '/' . $folder))
				{
					/** @var \SplFileInfo[] $objFiles */
					$objFiles = new \RecursiveIteratorIterator(
						new \RecursiveDirectoryIterator(
							TL_ROOT . '/' . $folder,
							\FilesystemIterator::UNIX_PATHS|\FilesystemIterator::FOLLOW_SYMLINKS|\FilesystemIterator::SKIP_DOTS
						)
					);

					// Ignore .gitignore and index.html files
					foreach ($objFiles as $objFile)
					{
						if ($objFile->getFilename() != '.gitignore' && $objFile->getFilename() != 'index.html')
						{
							++$total;
						}
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
		$objTemplate->help = (\Config::get('showHelp') && ($GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] != '')) ? $GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] : '';

		return $objTemplate->parse();
	}
}
