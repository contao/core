<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Library
 * @link    http://contao.org
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
 * @copyright Leo Feyer 2011-2012
 */
class Config
{
	
	const LOCALCONFIG_FILE = 'system/config/localconfig.php';

	const MODULES_CACHE_FILE = 'system/cache/modules.php';
	
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
	
	protected $arrModules;

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
		if (!is_object(static::$objInstance))
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
		$this->Files = \Files::getInstance();
		
		// Load the default files
		include TL_ROOT . '/system/config/default.php';
		include TL_ROOT . '/system/config/agents.php';
		
		$this->blnHasLcf = file_exists(TL_ROOT . '/system/config/localconfig.php');
		
		if($this->blnHasLcf) {
			include TL_ROOT . '/' . static::LOCALCONFIG_FILE;
		}
		
		// Get the module configuration files
		foreach($this->getActiveModules() as $strModule) {
			$strFile = static::getModuleBasePath($strModule) . '/config/config.php';
		
			if(file_exists($strFile)) {
				include $strFile;
			}
		}
		
		if($this->blnHasLcf) {
			include TL_ROOT . '/' . static::LOCALCONFIG_FILE;
			list($this->strTop, $this->strBottom, $this->arrData) = $this->readLcf();
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
	public function getActiveModules($blnNoCache = false, $blnWriteCache = true) {
		if(!$blnNoCache && isset($this->arrModules)) {
			return $this->arrModules;
		}
		
		if($GLOBALS['TL_CONFIG']['coreOnlyMode']) {
			$arrModules = static::scanModules(true, true);
			
		} else {
			$arrModules = static::scanModules(false);
			$arrInactive = deserialize($GLOBALS['TL_CONFIG']['inactiveModules']);
			$arrInactive && $arrModules = array_diff($arrModules, $arrInactive);
			
			if(!$blnNoCache && file_exists(TL_ROOT . '/' . static::MODULES_CACHE_FILE)) {
				$arrCached = include TL_ROOT . '/' . static::MODULES_CACHE_FILE;
			}
			
			if($arrCached && count($arrCached) == count($arrModules) && !array_diff($arrCached, $arrModules)) {
				$arrModules = $arrCached;
				
			} else {
				$arrModules = array_filter($arrModules, 'static::isModuleActive');
				
				$arrDepend = static::loadDependencyMap($arrModules);
				list($arrModules, $arrCycles, $arrUnresolvable) = static::resolveDependencies($arrModules, $arrDepend);
				if($arrCycles || $arrUnresolvable) {
					$this->log('Errors in dependency definitions. Cycles: ' . implode(',', $arrCycles) . ' - Unresolvable: ' . implode(',', $arrUnresolvable));
				}
				
				$blnWriteCache && $this->writeModules($arrModules);
			}
		}
		
		$this->arrModules = $arrModules;
		return $arrModules;
	}
	
	/**
	 * Save the local configuration file
	 */
	public function save()
	{
		$this->writeLcf();
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

		if ($varValue == 'true' || $varValue == 'false')
		{
			return strval($varValue);
		}

		$varValue = preg_replace('/[\n\r\t ]+/', ' ', str_replace("'", "\\'", $varValue));
		$varValue = "'" . $varValue . "'";

		return $varValue;
	}

	protected function readLcf() {
		if(!$this->blnHasLcf) {
			return array('', '', array());
		}
		
		// Read the local configuration file
		$strTop = $strBottom = '';
		$arrData = array();
		$strMode = 'top';
		$resFile = fopen(TL_ROOT . '/' . static::LOCALCONFIG_FILE, 'rb');
		
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
				$strTop .= $strLine;
			}
			elseif ($strMode == 'bottom')
			{
				$strBottom .= $strLine;
			}
			elseif ($strTrim != '')
			{
				$arrChunks = array_map('trim', explode('=', $strLine, 2));
				$arrData[$arrChunks[0]] = $arrChunks[1];
			}
		}
		
		fclose($resFile);
		
		return array($strTop, $strBottom, $arrData);
	}
	
	protected function writeLcf() {
		$strTop = trim($this->strTop);
		$strContent = ($strTop == '' ? '<?php' : $strTop) . "\n\n";
		$strContent .= "### INSTALL SCRIPT START ###\n";
		
		foreach($this->arrData as $k=>$v) $strContent .= $k . ' = ' . $v . "\n";
		
		$strContent .= "### INSTALL SCRIPT STOP ###\n";
		$strBottom = trim($this->strBottom);
		$strBottom == '' && $strContent .= $strBottom . "\n";
		
		$strTemp = static::writeTempFile($strContent);
		
		if(!$strTemp) {
			$this->log('The local configuration file could not be written. Have you reached your quota limit?');
			return false;
		}
		
		// Then move the file to its final destination
		$this->Files->rename($strTemp, static::LOCALCONFIG_FILE);
		static::clearCaches(static::LOCALCONFIG_FILE);
		
		return true;
	}
	
	protected function writeModules($arrModules) {
		$strModules = '<?php return ' . var_export($arrModules, true) . ';';
		$strTemp = static::writeTempFile($strModules);
		
		if(!$strTemp) {
			$this->log('Modules cache file could not be written. Have you reached your quota limit?');
			return false;
		}
		
		$this->Files->rename($strTemp, static::MODULES_CACHE_FILE);
		static::clearCaches(static::MODULES_CACHE_FILE);
		
		return true;
	}
	
	public static function isModuleActive($strModule) {
		return $strModule[0] != '.' && !file_exists(static::getModuleBasePath($strModule) . '/.skip');
	}
	
	public static function getModuleBasePath($strModule) {
		return TL_ROOT . '/system/modules/' . $strModule;
	}
	
	public static function writeTempFile($strContent) {
		$strTemp = 'system/tmp/' . md5(uniqid(mt_rand(), true));
		
		// Write to a temp file first
		$objFile = fopen(TL_ROOT . '/' . $strTemp, 'wb');
		fwrite($objFile, $strContent);
		fclose($objFile);
		
		// Make sure the file has been written (see #4483)
		return filesize(TL_ROOT . '/' . $strTemp) == 0 ? null : $strTemp;
	}

	public static function clearCaches($strFile) {
		// Reset the Zend Optimizer+ cache (unfortunately no API to delete just a single file)
		if (function_exists('accelerator_reset'))
		{
			accelerator_reset();
		}
		
		// Recompile the APC file (thanks to Trenker)
		if (function_exists('apc_compile_file') && !ini_get('apc.stat'))
		{
			apc_compile_file($strFile);
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
	
	public static function scanModules($blnActiveOnly = true, $blnCoreOnlyMode = false) {
		$arrModules = array(
			'core', 'calendar', 'comments',
			'devtools', 'faq', 'listing',
			'news', 'newsletter', 'repository'
		);
		
		if(!$blnCoreOnlyMode) {
			$arrExtensions = scan(TL_ROOT . '/system/modules');
			$arrModules = array_merge($arrModules, array_diff($arrExtensions, $arrModules));
		}
		
		return $blnActiveOnly ? array_filter($arrModules, 'static::isModuleActive') : $arrModules;
	}
	
	public static function loadDependencyMap($arrModules) {
		unset($GLOBALS['TL_DEPEND']);
		
		foreach($arrModules as $strModule) {
			$strDepend = static::getModuleBasePath($strModule) . '/config/depend.php';
			
			if(file_exists($strDepend)) {
				include $strDepend;
			}
		}
		
		return $GLOBALS['TL_DEPEND'];
	}
	
	public static function resolveDependencies($arrModules, $arrDepend) {
		// prepare input and early out checks
		if(!is_array($arrModules) || !$arrModules || !is_array($arrDepend) || !$arrDepend) {
			return array($arrModules, array(), array());
		}
	
		$arrModules = array_flip($arrModules);
		$arrDepend = array_intersect_key($arrDepend, $arrModules);
		foreach($arrDepend as &$arrDependencies) {
			$arrDependencies = array_intersect_key(array_flip($arrDependencies), $arrModules);
		}
		unset($arrDependencies);
	
		$arrDepend = array_filter($arrDepend);
		if(!$arrDepend) {
			return array(array_keys($arrModules), array(), array());
		}
	
		// calculate dependency closure
		$intCount = 0;
		do {
			$intOldCount = $intCount;
			foreach($arrDepend as $strDependant => &$arrDependencies) {
				$arrStep = array_intersect_key($arrDepend, $arrDependencies);
				if($arrStep) {
					$arrStep[] = $arrDependencies;
					$arrDependencies = call_user_func_array('array_merge', $arrStep);
				}
			}
			$intCount = array_sum(array_map('count', $arrDepend));
		} while($intOldCount != $intCount);
		unset($arrDependencies);
		
		// check for cycles
		$arrCycles = array();
		foreach($arrDepend as $strDependant => $arrDependencies) {
			if(isset($arrDependencies[$strDependant])) {
				$arrCycles[$strDependant] = true;
			}
		}
		
		// special treatment for core modules
		$arrCore = array(
			'core', 'calendar', 'comments',
			'devtools', 'faq', 'listing',
			'news', 'newsletter', 'repository'
		);
		$arrCore = array_intersect_key(array_fill_keys($arrCore, array()), $arrModules);
	
		// core modules are involved in cycles, drop dependency calculation
		if(array_intersect_key($arrCycles, $arrCore)) {
			$arrModules = array_keys($arrModules);
			return array($arrModules, array_keys($arrCycles), $arrModules);
		}
	
		// remove cycles from dependency closure
		$arrDepend = array_diff_key($arrDepend, $arrCycles);
		// get core dependencies
		$arrCore = array_merge($arrCore, array_intersect_key($arrDepend, $arrCore));
	
		// add core modules as dependencies for non-core modules,
		// but respect injected core dependency
		foreach($arrModules as $strModule => $_) {
			if(!isset($arrCore[$strModule])) {
				$arrStep = array();
				foreach($arrCore as $strCore => $arrDependencies) {
					if(!isset($arrDependencies[$strModule])) {
						$arrStep[$strCore] = $arrDependencies;
					}
				}
				if($arrStep) {
					$arrStep[] = array_flip(array_keys($arrStep));
					isset($arrDepend[$strModule]) && $arrStep[] = $arrDepend[$strModule];
					$arrDepend[$strModule] = call_user_func_array(
						'array_merge', $arrStep
					);
				}
			}
		}
		
		// add independant modules (either its just the core module or third party modules, which injected a core module dependency)
		$arrResolved = array_diff_key($arrModules, $arrDepend, $arrCycles);
	
		// add dependant modules
		$intCount = count($arrDepend);
		do {
			$intOldCount = $intCount;
			foreach($arrDepend as $strDependant => $arrDependencies) {
				if(!array_diff_key($arrDependencies, $arrResolved)) {
					$arrResolved[$strDependant] = true;
				}
			}
			$arrDepend = array_diff_key($arrDepend, $arrResolved);
			$intCount = count($arrDepend);
		} while($intCount != $intOldCount);
	
		// add cycles and unresolvable dependencies
		$arrResolved = array_merge($arrResolved, $arrCycles, $arrDepend);
	
		return array(array_keys($arrResolved), array_keys($arrCycles), array_keys($arrDepend));
	}
	
}
