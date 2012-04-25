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
 * @package    System
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class Config
 *
 * Provide methods to manage configuration files.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Config
{

	/**
	 * Current object instance (Singleton)
	 * @var Config
	 */
	protected static $objInstance;

	/**
	 * Files object
	 * @var Files
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
	 * Modified
	 * @var boolean
	 */
	protected $blnIsModified = false;

	/**
	 * Local configuration file
	 * @var boolean
	 */
	protected $blnHasLcf = false;

	/**
	 * Data array
	 * @var array
	 */
	protected $arrData = array();

	/**
	 * Cache array
	 * @var array
	 */
	protected $arrCache = array();


	/**
	 * Prevent direct instantiation (Singleton)
	 */
	protected function __construct() {}


	/**
	 * Save the local configuration
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
	 * @return \Contao\Config
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
	 * @return void
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
	 * @return void
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

		// Then move the file to its final destination
		$this->Files->rename('system/tmp/' . $strTemp, 'system/config/localconfig.php');

		// Reset the Zend Optimizer+ cache (unfortunately no API to delete just a single file)
		if (function_exists('accelerator_reset'))
		{
			accelerator_reset();
		}
	}


	/**
	 * Return true if the installation is completed
	 * @return boolean
	 */
	public function isComplete()
	{
		return $this->blnHasLcf;
	}


	/**
	 * Return all active modules (starting with "core") as array
	 * @param boolean
	 * @return array
	 */
	public function getActiveModules($blnNoCache=false)
	{
		if (!$blnNoCache && isset($this->arrCache['activeModules']))
		{
			return (array) $this->arrCache['activeModules']; // (array) = PHP 5.1.2 fix
		}

		$arrActiveModules = array('core');

		// Load only core modules in safe mode
		if ($GLOBALS['TL_CONFIG']['coreOnlyMode'])
		{
			$arrAllModules = array('core', 'calendar', 'comments', 'faq', 'listing', 'news', 'newsletter', 'repository');
		}
		else
		{
			$arrAllModules = scan(TL_ROOT . '/system/modules');
		}

		foreach ($arrAllModules as $strModule)
		{
			if (strncmp($strModule, '.', 1) === 0)
			{
				continue;
			}

			if ($strModule == 'core' || !is_dir(TL_ROOT . '/system/modules/' . $strModule))
			{
				continue;
			}

			if (file_exists(TL_ROOT . '/system/modules/' . $strModule . '/.skip'))
			{
				continue;
			}

			$arrActiveModules[] = $strModule;
		}

		$this->arrCache['activeModules'] = $arrActiveModules;
		return $arrActiveModules;
	}


	/**
	 * Add a configuration variable to the local configuration file
	 * @param string
	 * @param mixed
	 */
	public function add($strKey, $varValue)
	{
		$this->blnIsModified = true;
		$this->Files = \Files::getInstance(); // Required in the destructor
		$this->arrData[$strKey] = $this->escape($varValue) . ';';
	}


	/**
	 * Alias for Config::add()
	 * @param string
	 * @param mixed
	 * @return void
	 */
	public function update($strKey, $varValue)
	{
		$this->add($strKey, $varValue);
	}


	/**
	 * Delete a configuration variable from the local configuration file
	 * @param string
	 * @return void
	 */
	public function delete($strKey)
	{
		$this->blnIsModified = true;
		$this->Files = \Files::getInstance(); // Required in the destructor
		unset($this->arrData[$strKey]);
	}


	/**
	 * Escape a parameter depending on its type and return it
	 * @param mixed
	 * @return mixed
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

		$varValue = preg_replace('/[\n\r\t]+/i', ' ', str_replace("'", "\\'", $varValue));
		$varValue = "'" . preg_replace('/ {2,}/i', ' ', $varValue) . "'";

		return $varValue;
	}
}
