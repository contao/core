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
 * A wrapper class for accessing the file system
 *
 * The class handles file operations, either directly via the PHP functions or
 * through an FTP connection. The latter is a workaround for insufficient file
 * permissions when the PHP process runs under a different user than the file
 * owner (referred to as "Safe Mode Hack").
 *
 * Usage:
 *
 *     $files = Files::getInstance();
 *
 *     $files->mkdir('test');
 *
 *     $files->fopen('test/one.txt', 'wb');
 *     $files->fputs('My test');
 *     $files->fclose();
 *
 *     $files->rrdir('test');
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
abstract class Files
{

	/**
	 * Object instance (Singleton)
	 * @var \Files
	 */
	protected static $objInstance;


	/**
	 * Prevent direct instantiation (Singleton)
	 */
	protected function __construct() {}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final public function __clone() {}


	/**
	 * Instantiate the object (Factory)
	 *
	 * @return \Files The files object
	 */
	public static function getInstance()
	{
		if (self::$objInstance === null)
		{
			// Use FTP to modify files
			if (\Config::get('useFTP'))
			{
				self::$objInstance = new \Files\Ftp();
			}

			// HOOK: use the smhextended module
			elseif (\Config::get('useSmhExtended') && in_array('smhextended', \ModuleLoader::getActive()))
			{
				self::$objInstance = new \SMHExtended();
			}

			// Use PHP to modify files
			else
			{
				self::$objInstance = new \Files\Php();
			}
		}

		return self::$objInstance;
	}


	/**
	 * Create a directory
	 *
	 * @param string $strDirectory The directory name
	 *
	 * @return boolean True if the operation was successful
	 */
	abstract public function mkdir($strDirectory);


	/**
	 * Remove a directory
	 *
	 * @param string $strDirectory The directory name
	 *
	 * @return boolean True if the operation was successful
	 */
	abstract public function rmdir($strDirectory);


	/**
	 * Recursively remove a directory
	 *
	 * @param string  $strFolder       The directory name
	 * @param boolean $blnPreserveRoot If true, the root folder will not be removed
	 */
	public function rrdir($strFolder, $blnPreserveRoot=false)
	{
		$this->validate($strFolder);
		$arrFiles = scan(TL_ROOT . '/' . $strFolder, true);

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
	 *
	 * @param string $strFile The file name
	 * @param string $strMode The operation mode
	 *
	 * @return resource The file handle
	 */
	abstract public function fopen($strFile, $strMode);


	/**
	 * Write content to a file
	 *
	 * @param resource $resFile    The file handle
	 * @param string   $strContent The content to store in the file
	 */
	abstract public function fputs($resFile, $strContent);


	/**
	 * Close a file handle
	 *
	 * @param resource $resFile The file handle
	 *
	 * @return boolean True if the operation was successful
	 */
	abstract public function fclose($resFile);


	/**
	 * Rename a file or folder
	 *
	 * @param string $strOldName The old name
	 * @param string $strNewName The new name
	 *
	 * @return boolean True if the operation was successful
	 */
	abstract public function rename($strOldName, $strNewName);


	/**
	 * Copy a file or folder
	 *
	 * @param string $strSource      The source file or folder
	 * @param string $strDestination The new file or folder path
	 *
	 * @return boolean True if the operation was successful
	 */
	abstract public function copy($strSource, $strDestination);


	/**
	 * Recursively copy a directory
	 *
	 * @param string $strSource      The source file or folder
	 * @param string $strDestination The new file or folder path
	 */
	public function rcopy($strSource, $strDestination)
	{
		$this->validate($strSource, $strDestination);

		$this->mkdir($strDestination);
		$arrFiles = scan(TL_ROOT . '/' . $strSource, true);

		foreach ($arrFiles as $strFile)
		{
			if (is_dir(TL_ROOT . '/' . $strSource . '/' . $strFile))
			{
				$this->rcopy($strSource . '/' . $strFile, $strDestination . '/' . $strFile);
			}
			else
			{
				$this->copy($strSource . '/' . $strFile, $strDestination . '/' . $strFile);
			}
		}
	}


	/**
	 * Delete a file
	 *
	 * @param string $strFile The file name
	 *
	 * @return boolean True if the operation was successful
	 */
	abstract public function delete($strFile);


	/**
	 * Change the file mode
	 *
	 * @param string $strFile The file name
	 * @param mixed  $varMode The new file mode
	 *
	 * @return boolean True if the operation was successful
	 */
	abstract public function chmod($strFile, $varMode);


	/**
	 * Check whether a file is writeable
	 *
	 * @param string $strFile The file name
	 *
	 * @return boolean True if the file is writeable
	 */
	abstract public function is_writeable($strFile);


	/**
	 * Move an uploaded file to a folder
	 *
	 * @param string $strSource      The source file
	 * @param string $strDestination The new file path
	 *
	 * @return boolean True if the operation was successful
	 */
	abstract public function move_uploaded_file($strSource, $strDestination);


	/**
	 * Validate a path
	 *
	 * @throws \RuntimeException If the given paths are not valid
	 */
	protected function validate()
	{
		foreach (func_get_args() as $strPath)
		{
			if ($strPath == '') // see #5795
			{
				throw new \RuntimeException('No file or folder name given');
			}
			elseif (\Validator::isInsecurePath($strPath))
			{
				throw new \RuntimeException('Invalid file or folder name ' . $strPath);
			}
		}
	}
}
