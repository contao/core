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
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Config
 *
 * Provide methods to manage configuration files.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Library
 */
class Config
{

	/**
	 * Current object instance (Singleton)
	 * @var object
	 */
	protected static $objInstance;

	/**
	 * Files object
	 * @var object
	 */
	protected $Files;


	/**
	 * Load all configuration files
	 */
	private function __construct()
	{
		include(TL_ROOT . '/system/config/config.php');
		include(TL_ROOT . '/system/config/localconfig.php');

		foreach ($this->getActiveModules() as $strModule)
		{
			$strFile = sprintf('%s/system/modules/%s/config/config.php', TL_ROOT, $strModule);
			@include($strFile);
		}

		include(TL_ROOT . '/system/config/localconfig.php');
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}


	/**
	 * Return the current object instance (Singleton)
	 * @return object
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new Config();
		}

		return self::$objInstance;
	}


	/**
	 * Return all active modules (starting with "backend" and "frontend") as array
	 * @return array
	 */
	public function getActiveModules()
	{
		$arrActiveModules = array('backend', 'frontend');
		$arrAllModules = scan(TL_ROOT . '/system/modules');

		$arrInactiveModules = deserialize($GLOBALS['TL_CONFIG']['inactiveModules']);
		$blnCheckInactiveModules = is_array($arrInactiveModules);

		foreach ($arrAllModules as $strModule)
		{
			if (substr($strModule, 0, 1) == '.')
			{
				continue;
			}

			if ($strModule == 'backend' || $strModule == 'frontend' || !is_dir(TL_ROOT . '/system/modules/' . $strModule))
			{
				continue;
			}

			if ($blnCheckInactiveModules && in_array($strModule, $arrInactiveModules))
			{
				continue;
			}

			$arrActiveModules[] = $strModule;
		}

		return $arrActiveModules;
	}


	/**
	 * Add a configuration variable to the local configuration file
	 * @param string
	 * @param mixed
	 */
	public function add($strKey, $varValue)
	{
		$arrFile = $this->read();
		array_push($arrFile, sprintf('%s = %s;', $strKey, $this->escape($varValue)));

		$this->save($arrFile);
	}


	/**
	 * Update a configuration variable in the local configuration file
	 * @param string
	 * @param mixed
	 */
	public function update($strKey, $varValue)
	{
		$arrFile = $this->read();

		for ($i=0; $i<count($arrFile); $i++)
		{
			if (preg_match('/' . preg_quote($strKey, '/') . '/i', $arrFile[$i]))
			{
				unset($arrFile[$i]);
			}
		}

		$arrFile[] = sprintf('%s = %s;', $strKey, $this->escape($varValue));
		$this->save($arrFile);
	}


	/**
	 * Delete a configuration variable from the local configuration file
	 * @param string
	 * @param mixed
	 */
	public function delete($strKey)
	{
		$arrFile = $this->read();

		for ($i=0; $i<count($arrFile); $i++)
		{
			if (preg_match('/' . preg_quote($strKey, '/') . '/i', $arrFile[$i]))
			{
				unset($arrFile[$i]);
			}
		}

		$this->save($arrFile);
	}


	/**
	 * Read the configuration file and return it as array
	 * @return array
	 * @throws Exception
	 */
	private function read()
	{
		$arrFile = file(TL_ROOT . '/system/config/localconfig.php');

		if (!is_array($arrFile) || count($arrFile) < 1)
		{
			throw new Exception('Cannot read local configuration file <strong>system/config/localconfig.php</strong>!');
		}

		// Unset the closing tag
		for ($i=(count($arrFile)-1); (!strlen(trim($arrFile[$i])) || trim($arrFile[$i]) == '?>'); $i--)
		{
			unset($arrFile[$i]);
		}

		return $arrFile;
	}


	/**
	 * Save the configuration file
	 * @param mixed
	 * @throws Exception
	 */
	private function save($arrFile)
	{
		$strFile = implode('', $arrFile);

		$objFile = new File('system/config/localconfig.php');
		$objFile->write($strFile . "\n\n?>");
		$objFile->close();
	}


	/**
	 * Escape a parameter depending on its type and return it
	 * @param mixed
	 * @return mixed
	 */
	private function escape($varValue)
	{
		if (is_numeric($varValue))
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

?>