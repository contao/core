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
 * Loads and writes the local configuration file
 *
 * Custom settings above or below the `### INSTALL SCRIPT ###` markers will be
 * preserved.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Config
{

	/**
	 * Object instance (Singleton)
	 * @var \Config
	 */
	protected static $objInstance;

	/**
	 * Files object
	 * @var \Files
	 */
	protected $Files;

	/**
	 * Top content
	 * @var string
	 */
	protected $strTop = '';

	/**
	 * Bottom content
	 * @var string
	 */
	protected $strBottom = '';

	/**
	 * Modification indicator
	 * @var boolean
	 */
	protected $blnIsModified = false;

	/**
	 * Local file existance
	 * @var boolean
	 */
	protected static $blnHasLcf;

	/**
	 * Data
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * Cache
	 * @var array
	 */
	protected $arrCache = array();


	/**
	 * Prevent direct instantiation (Singleton)
	 */
	protected function __construct() {}


	/**
	 * Automatically save the local configuration
	 */
	public function __destruct()
	{
		if ($this->blnIsModified)
		{
			$this->save();
		}
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final public function __clone() {}


	/**
	 * Return the current object instance (Singleton)
	 *
	 * @return \Config The object instance
	 */
	public static function getInstance()
	{
		if (static::$objInstance === null)
		{
			static::$objInstance = new static();
			static::$objInstance->initialize();
		}

		return static::$objInstance;
	}


	/**
	 * Load all configuration files
	 */
	protected function initialize()
	{
		if (static::$blnHasLcf === null)
		{
			static::preload();
		}

		$strCacheFile = 'system/cache/config/config.php';

		// Try to load from cache
		if (!static::get('bypassCache') && file_exists(TL_ROOT . '/' . $strCacheFile))
		{
			include TL_ROOT . '/' . $strCacheFile;
		}
		else
		{
			// Get the module configuration files
			foreach (\ModuleLoader::getActive() as $strModule)
			{
				$strFile = TL_ROOT . '/system/modules/' . $strModule . '/config/config.php';

				if (file_exists($strFile))
				{
					include $strFile;
				}
			}
		}

		// Include the local configuration file again
		if (static::$blnHasLcf)
		{
			include TL_ROOT . '/system/config/localconfig.php';
		}
	}


	/**
	 * Mark the object as modified
	 */
	protected function markModified()
	{
		// Return if marked as modified already
		if ($this->blnIsModified === true)
		{
			return;
		}

		$this->blnIsModified = true;

		// Import the Files object (required in the destructor)
		$this->Files = \Files::getInstance();

		// Parse the local configuration file
		if (static::$blnHasLcf)
		{
			$strMode = 'top';
			$resFile = fopen(TL_ROOT . '/system/config/localconfig.php', 'rb');

			while (!feof($resFile))
			{
				$strLine = fgets($resFile);
				$strTrim = trim($strLine);

				if ($strTrim == '?>')
				{
					continue;
				}

				if ($strTrim == '### INSTALL SCRIPT START ###')
				{
					$strMode = 'data';
					continue;
				}

				if ($strTrim == '### INSTALL SCRIPT STOP ###')
				{
					$strMode = 'bottom';
					continue;
				}

				if ($strMode == 'top')
				{
					$this->strTop .= $strLine;
				}
				elseif ($strMode == 'bottom')
				{
					$this->strBottom .= $strLine;
				}
				elseif ($strTrim != '')
				{
					$arrChunks = array_map('trim', explode('=', $strLine, 2));
					$this->arrData[$arrChunks[0]] = $arrChunks[1];
				}
			}

			fclose($resFile);
		}
	}


	/**
	 * Save the local configuration file
	 */
	public function save()
	{
		if ($this->strTop == '')
		{
			$this->strTop = '<?php';
		}

		$strFile  = trim($this->strTop) . "\n\n";
		$strFile .= "### INSTALL SCRIPT START ###\n";

		foreach ($this->arrData as $k=>$v)
		{
			$strFile .= "$k = $v\n";
		}

		$strFile .= "### INSTALL SCRIPT STOP ###\n";
		$this->strBottom = trim($this->strBottom);

		if ($this->strBottom != '')
		{
			$strFile .= "\n" . $this->strBottom . "\n";
		}

		$strTemp = md5(uniqid(mt_rand(), true));

		// Write to a temp file first
		$objFile = fopen(TL_ROOT . '/system/tmp/' . $strTemp, 'wb');
		fputs($objFile, $strFile);
		fclose($objFile);

		// Make sure the file has been written (see #4483)
		if (!filesize(TL_ROOT . '/system/tmp/' . $strTemp))
		{
			\System::log('The local configuration file could not be written. Have your reached your quota limit?', __METHOD__, TL_ERROR);

			return;
		}

		// Then move the file to its final destination
		$this->Files->rename('system/tmp/' . $strTemp, 'system/config/localconfig.php');

		// Reset the Zend OPcache
		if (function_exists('opcache_invalidate'))
		{
			opcache_invalidate(TL_ROOT . '/system/config/localconfig.php', true);
		}

		// Reset the Zend Optimizer+ cache (unfortunately no API to delete just a single file)
		if (function_exists('accelerator_reset'))
		{
			accelerator_reset();
		}

		// Recompile the APC file (thanks to Trenker)
		if (function_exists('apc_compile_file') && !ini_get('apc.stat'))
		{
			apc_compile_file(TL_ROOT . '/system/config/localconfig.php');
		}

		// Purge the eAccelerator cache (thanks to Trenker)
		if (function_exists('eaccelerator_purge') && !ini_get('eaccelerator.check_mtime'))
		{
			@eaccelerator_purge();
		}

		// Purge the XCache cache (thanks to Trenker)
		if (function_exists('xcache_count') && !ini_get('xcache.stat'))
		{
			if (($count = xcache_count(XC_TYPE_PHP)) > 0)
			{
				for ($id=0; $id<$count; $id++)
				{
					xcache_clear_cache(XC_TYPE_PHP, $id);
				}
			}
		}
	}


	/**
	 * Return true if the installation is complete
	 *
	 * @return boolean True if the installation is complete
	 */
	public function isComplete()
	{
		if (!static::$blnHasLcf)
		{
			return false;
		}

		if (!$this->get('licenseAccepted'))
		{
			return false;
		}

		if ($this->get('dbDriver') == '')
		{
			return false;
		}

		return true;
	}


	/**
	 * Return all active modules as array
	 *
	 * @return array An array of active modules
	 *
	 * @deprecated Use ModuleLoader::getActive() instead
	 */
	public function getActiveModules()
	{
		return \ModuleLoader::getActive();
	}


	/**
	 * Add a configuration variable to the local configuration file
	 *
	 * @param string $strKey   The full variable name
	 * @param mixed  $varValue The configuration value
	 */
	public function add($strKey, $varValue)
	{
		$this->markModified();
		$this->arrData[$strKey] = $this->escape($varValue) . ';';
	}


	/**
	 * Alias for Config::add()
	 *
	 * @param string $strKey   The full variable name
	 * @param mixed  $varValue The configuration value
	 */
	public function update($strKey, $varValue)
	{
		$this->add($strKey, $varValue);
	}


	/**
	 * Remove a configuration variable
	 *
	 * @param string $strKey The full variable name
	 */
	public function delete($strKey)
	{
		$this->markModified();
		unset($this->arrData[$strKey]);
	}


	/**
	 * Return a configuration value
	 *
	 * @param string $strKey The short key (e.g. "displayErrors")
	 *
	 * @return mixed|null The configuration value
	 */
	public static function get($strKey)
	{
		if (isset($GLOBALS['TL_CONFIG'][$strKey]))
		{
			return $GLOBALS['TL_CONFIG'][$strKey];
		}

		return null;
	}


	/**
	 * Temporarily set a configuration value
	 *
	 * @param string $strKey   The short key (e.g. "displayErrors")
	 * @param string $varValue The configuration value
	 */
	public static function set($strKey, $varValue)
	{
		$GLOBALS['TL_CONFIG'][$strKey] = $varValue;
	}


	/**
	 * Permanently set a configuration value
	 *
	 * @param string $strKey   The short key or full variable name
	 * @param mixed  $varValue The configuration value
	 */
	public static function persist($strKey, $varValue)
	{
		$objConfig = static::getInstance();

		if (strncmp($strKey, '$GLOBALS', 8) !== 0)
		{
			$strKey = "\$GLOBALS['TL_CONFIG']['$strKey']";
		}

		$objConfig->add($strKey, $varValue);
	}


	/**
	 * Permanently remove a configuration value
	 *
	 * @param string $strKey The short key or full variable name
	 */
	public static function remove($strKey)
	{
		$objConfig = static::getInstance();

		if (strncmp($strKey, '$GLOBALS', 8) !== 0)
		{
			$strKey = "\$GLOBALS['TL_CONFIG']['$strKey']";
		}

		$objConfig->delete($strKey);
	}


	/**
	 * Preload the default and local configuration
	 */
	public static function preload()
	{
		// Load the default files
		include TL_ROOT . '/system/config/default.php';
		include TL_ROOT . '/system/config/agents.php';
		include TL_ROOT . '/system/config/mimetypes.php';

		// Include the local configuration file
		if (($blnHasLcf = file_exists(TL_ROOT . '/system/config/localconfig.php')) === true)
		{
			include TL_ROOT . '/system/config/localconfig.php';
		}

		static::$blnHasLcf = $blnHasLcf;
	}


	/**
	 * Escape a value depending on its type
	 *
	 * @param mixed $varValue The value
	 *
	 * @return mixed The escaped value
	 */
	protected function escape($varValue)
	{
		if (is_numeric($varValue) && !preg_match('/e|^00+/', $varValue) && $varValue < PHP_INT_MAX)
		{
			return $varValue;
		}

		if (is_bool($varValue))
		{
			return $varValue ? 'true' : 'false';
		}

		if ($varValue == 'true')
		{
			return 'true';
		}

		if ($varValue == 'false')
		{
			return 'false';
		}

		return "'" . str_replace('\\"', '"', preg_replace('/[\n\r\t ]+/', ' ', addslashes($varValue))) . "'";
	}
}
