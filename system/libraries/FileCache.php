<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class FileCache
 *
 * Provide methods to create a file cache.
 * @copyright  Leo Feyer 2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class FileCache extends System
{

	/**
	 * Current object instances (Singleton)
	 * @var object
	 */
	protected static $arrInstances = array();

	/**
	 * Cache file name
	 * @var string
	 */
	protected $strFile;

	/**
	 * Indicates whether the cache has been modified
	 * @var boolean
	 */
	protected $blnIsModified = false;


	/**
	 * Try to load the cache file
	 * @param string
	 */
	protected function __construct($strFile)
	{
		$this->strFile = md5($strFile) . '.txt';
		$strPath = TL_ROOT . '/system/tmp/' . $this->strFile;

		// Read the file content if it exists
		if (file_exists($strPath))
		{
			$strBuffer = file_get_contents($strPath);
			$arrCache = deserialize($strBuffer);

			if (is_array($arrCache))
			{
				$this->arrCache = $arrCache;
			}
		}
	}


	/**
	 * Store the cache file
	 * 
	 * We can write directly to the system/tmp folder, because it will be set
	 * to CHMOD 777 during installation in case the Safe Mode Hack is required.
	 */
	public function __destruct()
	{
		// Return if the cache has not been modified
		if (!$this->blnIsModified)
		{
			return;
		}

		$strPath = TL_ROOT . '/system/tmp';

		// Return if the tmp folder is not writeable. This is typically
		// the case when Contao is installed the first time and the Safe
		// Mode Hack has not yet been configured.
		if (!is_writable(TL_ROOT . '/system/tmp'))
		{
			return;
		}

		ksort($this->arrCache);
		$strTemp = md5(uniqid(mt_rand(), true));

		// Write to a temp file first
		$fh = fopen($strPath . '/' . $strTemp, 'wb');
		fputs($fh, serialize($this->arrCache));
		fclose($fh);

		// Windows fix: delete the target file
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && file_exists($strPath . '/' . $this->strFile))
		{
			unlink($strPath . '/' . $this->strFile);
		}

		// Then move the file to its final destination
		rename($strPath . '/' . $strTemp, $strPath . '/' . $this->strFile);
	}


	/**
	 * Check whether a variable is set
	 * @param string
	 * @return boolean
	 */
	public function __isset($strKey)
	{
		return isset($this->arrCache[$strKey]);
	}


	/**
	 * Return a variable
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		return $this->arrCache[$strKey];
	}


	/**
	 * Set a variable
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		$this->blnIsModified = true;
		$this->arrCache[$strKey] = $varValue;
	}


	/**
	 * Unset an entry
	 * @param string
	 */
	public function __unset($strKey)
	{
		$this->blnIsModified = true;
		unset($this->arrCache[$strKey]);
	}


	/**
	 * Instantiate a new file cache object and return it (Factory)
	 * @param string
	 * @return object
	 */
	public static function getInstance($strFile)
	{
		if (!isset(self::$arrInstances[$strFile]))
		{
			self::$arrInstances[$strFile] = new self($strFile);
		}

		return self::$arrInstances[$strFile];
	}
}

?>