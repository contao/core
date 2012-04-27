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
use \Files_Ftp, \Files_Php, \Exception;


/**
 * Class Files
 *
 * Provide methods to modify files and folders.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
abstract class Files
{

	/**
	 * Current object instance (Singleton)
	 * @var Files
	 */
	protected static $objInstance;


	/**
	 * Prevent direct instantiation (Singleton)
	 */
	protected function __construct() {}


	/**
	 * Prevent cloning of the object (Singleton)
	 * @return mixed|void
	 */
	final public function __clone() {}


	/**
	 * Instantiate a files driver object and return it (Factory)
	 * @return \Files
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			// Use FTP to modify files
			if ($GLOBALS['TL_CONFIG']['useFTP'])
			{
				self::$objInstance = new Files_Ftp();
			}

			// HOOK: use the smhextended module
			elseif ($GLOBALS['TL_CONFIG']['useSmhExtended'] && in_array('smhextended', Config::getInstance()->getActiveModules()))
			{
				self::$objInstance = new \SMHExtended();
			}

			// Use PHP to modify files
			else
			{
				self::$objInstance = new Files_Php();
			}
		}

		return self::$objInstance;
	}


	/**
	 * Create a directory
	 * @param string
	 * @return boolean
	 */
	abstract public function mkdir($strDirectory);


	/**
	 * Remove a directory
	 * @param string
	 * @return boolean
	 */
	abstract public function rmdir($strDirectory);


	/**
	 * Recursively remove a directory
	 * @param string
	 * @param boolean
	 */
	public function rrdir($strFolder, $blnPreserveRoot=false)
	{
		$this->validate($strFolder);
		$arrFiles = scan(TL_ROOT . '/' . $strFolder);

		foreach ($arrFiles as $strFile)
		{
			if (is_dir(TL_ROOT . '/' . $strFolder . '/' . $strFile))
			{
				$this->rrdir($strFolder . '/' . $strFile);
			}
			else
			{
				$this->delete($strFolder . '/' . $strFile);
			}
		}

		if (!$blnPreserveRoot)
		{
			$this->rmdir($strFolder);
		}
	}


	/**
	 * Open a file and return the handle
	 * @param string
	 * @param string
	 * @return resource
	 */
	abstract public function fopen($strFile, $strMode);


	/**
	 * Write content to a file
	 * @param string
	 * @param string
	 * @return boolean
	 */
	abstract public function fputs($resFile, $strContent);


	/**
	 * Close a file
	 * @param resource
	 * @return boolean
	 */
	abstract public function fclose($resFile);


	/**
	 * Rename a file or folder
	 * @param string
	 * @param string
	 * @return boolean
	 */
	abstract public function rename($strOldName, $strNewName);


	/**
	 * Copy a file or folder
	 * @param string
	 * @param string
	 * @return boolean
	 */
	abstract public function copy($strSource, $strDestination);


	/**
	 * Delete a file
	 * @param string
	 * @return boolean
	 */
	abstract public function delete($strFile);


	/**
	 * Change file mode
	 * @param string
	 * @param mixed
	 * @return boolean
	 */
	abstract public function chmod($strFile, $varMode);


	/**
	 * Check whether a file is writeable
	 * @param string
	 * @return boolean
	 */
	abstract public function is_writeable($strFile);


	/**
	 * Move an uploaded file to another folder
	 * @param string
	 * @param string
	 * @return boolean
	 */
	abstract public function move_uploaded_file($strSource, $strDestination);


	/**
	 * Validate the path
	 * @return void
	 * @throws \Exception
	 */
	protected function validate()
	{
		foreach (func_get_args() as $strPath)
		{
			if (strpos($strPath, '../') !== false)
			{
				throw new Exception('Invalid file or folder name ' . $strPath);
			}
		}
	}
}
