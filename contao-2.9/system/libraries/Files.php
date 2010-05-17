<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Files
 *
 * Provide methods to modify files and folders.
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Library
 */
class Files
{

	/**
	 * Current object instance (Singleton)
	 * @var object
	 */
	protected static $objInstance;


	/**
	 * Prevent direct instantiation (Singleton)
	 */
	protected function __construct() {}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}


	/**
	 * Instantiate a files driver object and return it (Factory)
	 * @return object
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			// Use FTP to modify files
			if ($GLOBALS['TL_CONFIG']['useFTP'] && strlen($GLOBALS['TL_CONFIG']['ftpHost']) && strlen($GLOBALS['TL_CONFIG']['ftpUser']) && strlen($GLOBALS['TL_CONFIG']['ftpPass']))
			{
				// Connect to FTP server
				if (($resConnection = ftp_connect($GLOBALS['TL_CONFIG']['ftpHost'])) != false)
				{
					// Login
					if (ftp_login($resConnection, $GLOBALS['TL_CONFIG']['ftpUser'], $GLOBALS['TL_CONFIG']['ftpPass']))
					{
						// Passive mode
						ftp_pasv($resConnection, true);
						self::$objInstance = new FTP($resConnection);
					}
				}
			}

			// HOOK: use the smhextended module
			elseif ($GLOBALS['TL_CONFIG']['useSmhExtended'] && in_array('smhextended', Config::getInstance()->getActiveModules()))
			{
				self::$objInstance = new SMHExtended();
			}

			// Use PHP to modify files
			else
			{
				self::$objInstance = new Files();
			}
		}

		return self::$objInstance;
	}


	/**
	 * Create a directory
	 * @param string
	 * @return boolean
	 */
	public function mkdir($strDirectory)
	{
		return @mkdir(TL_ROOT . '/' . $strDirectory);
	}


	/**
	 * Remove a directory
	 * @param string
	 * @return boolean
	 */
	public function rmdir($strDirectory)
	{
		return @rmdir(TL_ROOT. '/' . $strDirectory);
	}


	/**
	 * Recursively remove a directory
	 * @param string
	 * @param boolean
	 */
	public function rrdir($strFolder, $blnPreserveRoot=false)
	{
		$arrFiles = scan(TL_ROOT . '/' . $strFolder);

		foreach ($arrFiles as $strFile)
		{
			if (is_dir(TL_ROOT . '/' . $strFolder . '/' . $strFile))
			{
				$this->rmdir($strFolder . '/' . $strFile);
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
	public function fopen($strFile, $strMode)
	{
		return @fopen(TL_ROOT . '/' . $strFile, $strMode);
	}


	/**
	 * Write content to a file
	 * @param string
	 * @param string
	 * @return boolean
	 */
	public function fputs($resFile, $strContent)
	{
		return @fputs($resFile, $strContent);
	}


	/**
	 * Close a file
	 * @param resource
	 * @return boolean
	 */
	public function fclose($resFile)
	{
		return @fclose($resFile);
	}


	/**
	 * Rename a file or folder
	 * @param string
	 * @param string
	 * @return boolean
	 */
	public function rename($strOldName, $strNewName)
	{
		// Windows fix: delete target file
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && file_exists(TL_ROOT . '/' . $strNewName))
		{
			$this->delete($strNewName);
		}

		// Unix fix: rename case sensitively
		if (strcasecmp($strOldName, $strNewName) === 0 && strcmp($strOldName, $strNewName) !== 0)
		{
			@rename(TL_ROOT . '/' . $strOldName, TL_ROOT . '/' . $strOldName . '__');
			$strOldName .= '__';
		}

		return @rename(TL_ROOT . '/' . $strOldName, TL_ROOT . '/' . $strNewName);
	}


	/**
	 * Copy a file or folder
	 * @param string
	 * @param string
	 * @return boolean
	 */
	public function copy($strSource, $strDestination)
	{
		return @copy(TL_ROOT . '/' . $strSource, TL_ROOT . '/' . $strDestination);
	}


	/**
	 * Delete a file
	 * @param string
	 * @return boolean
	 */
	public function delete($strFile)
	{
		return @unlink(TL_ROOT . '/' . $strFile);
	}


	/**
	 * Change file mode
	 * @param string
	 * @param mixed
	 * @return boolean
	 */
	public function chmod($strFile, $varMode)
	{
		return @chmod(TL_ROOT . '/' . $strFile, $varMode);
	}


	/**
	 * Check whether a file is writeable
	 * @param string
	 * @return boolean
	 */
	public function is_writeable($strFile)
	{
		return @is_writeable(TL_ROOT . '/' . $strFile);
	}


	/**
	 * Move an uploaded file to another folder
	 * @param string
	 * @param string
	 * @return string
	 */
	public function move_uploaded_file($strSource, $strDestination)
	{
		return @move_uploaded_file($strSource, TL_ROOT . '/' . $strDestination);
	}
}

?>