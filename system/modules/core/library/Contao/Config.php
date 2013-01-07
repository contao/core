<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


/**
 * Loads and writes the local configuration file
 * 
 * Custom settings above or below the `### INSTALL SCRIPT ###` markers will be
 * preserved.
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
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
	protected $blnHasLcf = false;

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
		// Load the default files
		include TL_ROOT . '/system/config/default.php';
		include TL_ROOT . '/system/config/agents.php';

		// Get the module configuration files
		foreach ($this->getActiveModules() as $strModule)
		{
			$strFile = TL_ROOT . '/system/modules/' . $strModule . '/config/config.php';

			if (file_exists($strFile))
			{
				include $strFile;
			}
		}

		// Return if there is no local configuration file yet
		if (!file_exists(TL_ROOT . '/system/config/localconfig.php'))
		{
			return;
		}

		$this->blnHasLcf = true;
		include TL_ROOT . '/system/config/localconfig.php';

		// Read the local configuration file
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
			$strFile .= $this->strBottom . "\n";
		}

		$strTemp = md5(uniqid(mt_rand(), true));

		// Write to a temp file first
		$objFile = fopen(TL_ROOT . '/system/tmp/' . $strTemp, 'wb');
		fputs($objFile, $strFile);
		fclose($objFile);

		// Make sure the file has been written (see #4483)
		if (!filesize(TL_ROOT . '/system/tmp/' . $strTemp))
		{
			$this->log('The local configuration file could not be written. Have your reached your quota limit?');
			return;
		}

		// Then move the file to its final destination
		$this->Files->rename('system/tmp/' . $strTemp, 'system/config/localconfig.php');

		// Reset the Zend Optimizer+ cache (unfortunately no API to delete just a single file)
		if (function_exists('accelerator_reset'))
		{
			accelerator_reset();
		}

		// Recompile the APC file (thanks to Trenker)
		if (function_exists('apc_compile_file') && !ini_get('apc.stat'))
		{
			apc_compile_file('system/config/localconfig.php');
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
	 * Return true if the installation is completed
	 * 
	 * @return boolean True if the local configuration file exists
	 */
	public function isComplete()
	{
		return $this->blnHasLcf;
	}


	/**
	 * Return all active modules (starting with "core") as array
	 * 
	 * @param boolean $blnNoCache Override the cache
	 * 
	 * @return array An array of active modules
	 */
	public function getActiveModules($blnNoCache=false)
	{
		if (!$blnNoCache && isset($this->arrCache['activeModules']))
		{
			return (array) $this->arrCache['activeModules']; // (array) = PHP 5.1.2 fix
		}

		$arrActiveModules = array();
		$arrCoreModules = array('core', 'calendar', 'comments', 'devtools', 'faq', 'listing', 'news', 'newsletter', 'repository');
		$arrDeprecatedModules = array('backend', 'frontend', 'rep_base', 'rep_client', 'registration', 'rss_reader', 'tpl_editor');
		$arrExtensionModules = scan(TL_ROOT . '/system/modules');

		// Load the core modules first
		foreach ($arrCoreModules as $strModule)
		{
			if (!file_exists(TL_ROOT . '/system/modules/' . $strModule . '/.skip'))
			{
				$arrActiveModules[] = $strModule;
			}
		}

		// Then load the extension modules
		if (!$GLOBALS['TL_CONFIG']['coreOnlyMode'])
		{
			foreach ($arrExtensionModules as $strModule)
			{
				if (in_array($strModule, $arrDeprecatedModules))
				{
					continue; // see #4907
				}

				if (strncmp($strModule, '.', 1) !== 0 && !in_array($strModule, $arrCoreModules) && is_dir(TL_ROOT . '/system/modules/' . $strModule) && !file_exists(TL_ROOT . '/system/modules/' . $strModule . '/.skip'))
				{
					$arrActiveModules[] = $strModule;
				}
			}
		}

		$this->arrCache['activeModules'] = $arrActiveModules;
		return $arrActiveModules;
	}


	/**
	 * Add a configuration variable to the local configuration file
	 * 
	 * @param string $strKey   The full variable name
	 * @param mixed  $varValue The configuration value
	 */
	public function add($strKey, $varValue)
	{
		$this->blnIsModified = true;
		$this->Files = \Files::getInstance(); // Required in the destructor
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
	 * Return a configuration value
	 * 
	 * @param string $strKey The short key (e.g. "displayErrors")
	 * 
	 * @return mixed|null The configuration value
	 */
	public function get($strKey)
	{
		if (isset($GLOBALS['TL_CONFIG'][$strKey]))
		{
			return $GLOBALS['TL_CONFIG'][$strKey];
		}

		return null;
	}


	/**
	 * Remove a configuration variable
	 * 
	 * @param string $strKey The full variable name
	 */
	public function delete($strKey)
	{
		$this->blnIsModified = true;
		$this->Files = \Files::getInstance(); // Required in the destructor
		unset($this->arrData[$strKey]);
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
		if (is_numeric($varValue) && !preg_match('/e|^00+/', $varValue))
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

		$varValue = preg_replace('/[\n\r\t]+/', ' ', str_replace("'", "\\'", $varValue));
		$varValue = "'" . preg_replace('/ {2,}/', ' ', $varValue) . "'";

		return $varValue;
	}
}
