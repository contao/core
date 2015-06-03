<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\Files;


/**
 * Manage files with the PHP functions
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Php extends \Files
{

	/**
	 * Create a directory
	 *
	 * @param string $strDirectory The directory name
	 *
	 * @return boolean True if the operation was successful
	 */
	public function mkdir($strDirectory)
	{
		$this->validate($strDirectory);
		return @mkdir(TL_ROOT . '/' . $strDirectory);
	}


	/**
	 * Remove a directory
	 *
	 * @param string $strDirectory The directory name
	 *
	 * @return boolean True if the operation was successful
	 */
	public function rmdir($strDirectory)
	{
		$this->validate($strDirectory);
		return @rmdir(TL_ROOT. '/' . $strDirectory);
	}


	/**
	 * Open a file and return the handle
	 *
	 * @param string $strFile The file name
	 * @param string $strMode The operation mode
	 *
	 * @return resource The file handle
	 */
	public function fopen($strFile, $strMode)
	{
		$this->validate($strFile);
		return @fopen(TL_ROOT . '/' . $strFile, $strMode);
	}


	/**
	 * Write content to a file
	 *
	 * @param resource $resFile    The file handle
	 * @param string   $strContent The content to store in the file
	 */
	public function fputs($resFile, $strContent)
	{
		@fputs($resFile, $strContent);
	}


	/**
	 * Close a file handle
	 *
	 * @param resource $resFile The file handle
	 *
	 * @return boolean True if the operation was successful
	 */
	public function fclose($resFile)
	{
		return @fclose($resFile);
	}


	/**
	 * Rename a file or folder
	 *
	 * @param string $strOldName The old name
	 * @param string $strNewName The new name
	 *
	 * @return boolean True if the operation was successful
	 */
	public function rename($strOldName, $strNewName)
	{
		// Source file == target file
		if ($strOldName == $strNewName)
		{
			return true;
		}

		$this->validate($strOldName, $strNewName);

		// Prepare paths by combining with TL_ROOT
		$strOldFilePath = TL_ROOT . '/' . $strOldName;
		$strNewFilePath = TL_ROOT . '/' . $strNewName;

		// Resolve the path, if the file is a symbolic link. This may break
		// out of TL_ROOT, but as the link already existed before, we can
		// assume the user is aware of this behaviour.
		if (is_link($strNewFilePath)) {
			$strNewFilePath = readlink($strNewFilePath);
		}

		// Windows fix: delete the target file
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && file_exists($strNewFilePath))
		{
			$this->delete($strNewName);
		}

		// Unix fix: rename case sensitively
		if (strcasecmp($strOldName, $strNewName) === 0 && strcmp($strOldName, $strNewName) !== 0)
		{
			@rename($strOldFilePath, $strOldFilePath . '__');
			$strOldName .= '__';
			$strOldFilePath .= '__';
		}

		return @rename($strOldFilePath, $strNewFilePath);
	}


	/**
	 * Copy a file or folder
	 *
	 * @param string $strSource      The source file or folder
	 * @param string $strDestination The new file or folder path
	 *
	 * @return boolean True if the operation was successful
	 */
	public function copy($strSource, $strDestination)
	{
		$this->validate($strSource, $strDestination);

		// Prepare paths by combining with TL_ROOT
		$strSourcePath = TL_ROOT . '/' . $strSource;
		$strDestinationPath = TL_ROOT . '/' . $strDestination;

		if (is_link($strSourcePath)) {
			return symlink(readlink($strSourcePath), $strDestinationPath);
		}

		return @copy($strSourcePath, $strDestinationPath);
	}

	/**
	 * Delete a file
	 *
	 * @param string $strFile The file name
	 *
	 * @return boolean True if the operation was successful
	 */
	public function delete($strFile)
	{
		$this->validate($strFile);
		return @unlink(TL_ROOT . '/' . $strFile);
	}


	/**
	 * Change the file mode
	 *
	 * @param string $strFile The file name
	 * @param mixed  $varMode The new file mode
	 *
	 * @return boolean True if the operation was successful
	 */
	public function chmod($strFile, $varMode)
	{
		$this->validate($strFile);
		return @chmod(TL_ROOT . '/' . $strFile, $varMode);
	}


	/**
	 * Check whether a file is writeable
	 *
	 * @param string $strFile The file name
	 *
	 * @return boolean True if the file is writeable
	 */
	public function is_writeable($strFile)
	{
		$this->validate($strFile);
		return @is_writeable(TL_ROOT . '/' . $strFile);
	}


	/**
	 * Move an uploaded file to a folder
	 *
	 * @param string $strSource      The source file
	 * @param string $strDestination The new file path
	 *
	 * @return boolean True if the operation was successful
	 */
	public function move_uploaded_file($strSource, $strDestination)
	{
		$this->validate($strSource, $strDestination);
		return @move_uploaded_file($strSource, TL_ROOT . '/' . $strDestination);
	}
}
