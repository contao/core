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
 * Loads a set of DCA files
 *
 * The class loads the DCA files of a certain table and stores a combined
 * version in the system/cache directory.
 *
 * Usage:
 *
 *     $dca = new DcaLoader('tl_user');
 *     $dca->load();
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class DcaLoader extends \Controller
{

	/**
	 * Table name
	 * @var string
	 */
	protected $strTable;


	/**
	 * Store the table name
	 *
	 * @param string $strTable The table name
	 *
	 * @throws \Exception If $strTable is empty
	 */
	public function __construct($strTable)
	{
		if ($strTable == '')
		{
			throw new \Exception('The table name must not be empty');
		}

		parent::__construct();

		$this->strTable = $strTable;
	}


	/**
	 * Load a set of DCA files
	 *
	 * @param boolean $blnNoCache If true, the cache will be bypassed
	 */
	public function load($blnNoCache=false)
	{
		// Return if the data has been loaded already
		if (isset($GLOBALS['loadDataContainer'][$this->strTable]) && !$blnNoCache)
		{
			return;
		}

		$GLOBALS['loadDataContainer'][$this->strTable] = true; // see #6145
		$strCacheFile = 'system/cache/dca/' . $this->strTable . '.php';

		// Try to load from cache
		if (!\Config::get('bypassCache') && file_exists(TL_ROOT . '/' . $strCacheFile))
		{
			include TL_ROOT . '/' . $strCacheFile;
		}
		else
		{
			foreach (\ModuleLoader::getActive() as $strModule)
			{
				$strFile = 'system/modules/' . $strModule . '/dca/' . $this->strTable . '.php';

				if (file_exists(TL_ROOT . '/' . $strFile))
				{
					include TL_ROOT . '/' . $strFile;
				}
			}
		}

		// HOOK: allow to load custom settings
		if (isset($GLOBALS['TL_HOOKS']['loadDataContainer']) && is_array($GLOBALS['TL_HOOKS']['loadDataContainer']))
		{
			foreach ($GLOBALS['TL_HOOKS']['loadDataContainer'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($this->strTable);
			}
		}

		// Local configuration file
		if (file_exists(TL_ROOT . '/system/config/dcaconfig.php'))
		{
			include TL_ROOT . '/system/config/dcaconfig.php';
		}
	}
}
