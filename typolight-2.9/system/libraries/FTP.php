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
 * Class FTP
 *
 * Provide methods to modify files and folders via FTP.
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Library
 */
class FTP extends Files
{

	/**
	 * FTP connection
	 * @var resource
	 */
	protected $resConnection;

	/**
	 * Files array
	 * @var array
	 */
	protected $arrFiles = array();


	/**
	 * Store the FTP resource and make sure that the temp folder is writable
	 * @param resource
	 */
	protected function __construct($resConnection)
	{
		if (!is_resource($resConnection))
		{
			throw new Exception('Class FTP requires a valid FTP connection resource');
		}

		$this->resConnection = $resConnection;

		// Make folders writable
		if (!is_writable(TL_ROOT . '/system/tmp'))
		{
			$this->chmod('system/tmp', 0777);
		}
		if (!is_writable(TL_ROOT . '/system/html'))
		{
			$this->chmod('system/html', 0777);
		}
		if (!is_writable(TL_ROOT . '/system/logs'))
		{
			$this->chmod('system/logs', 0777);
		}
	}


	/**
	 * Disconnect from ftp server
	 */
	public function __destruct()
	{
		@ftp_close($this->resConnection);
	}


	/**
	 * Create a directory
	 * @param string
	 * @return boolean
	 */
	public function mkdir($strDirectory)
	{
		return @ftp_mkdir($this->resConnection, $GLOBALS['TL_CONFIG']['ftpPath'] . $strDirectory) ? true : false;
	}


	/**
	 * Remove a directory
	 * @param string
	 * @return boolean
	 */
	public function rmdir($strDirectory)
	{
		return @ftp_rmdir($this->resConnection, $GLOBALS['TL_CONFIG']['ftpPath'] . $strDirectory);
	}


	/**
	 * Open a file and return the handle
	 * @param string
	 * @param string
	 * @return resource
	 */
	public function fopen($strFile, $strMode)
	{
		$resFile = fopen(TL_ROOT . '/system/tmp/' . md5(uniqid('', true)), $strMode);

		// Copy temp file
		if (!file_exists(TL_ROOT . '/' . $strFile))
		{
			if (!@ftp_fput($this->resConnection, $GLOBALS['TL_CONFIG']['ftpPath'] . $strFile, $resFile, FTP_BINARY))
			{
				return false;
			}
		}

		$arrData = stream_get_meta_data($resFile);
		$this->arrFiles[$arrData['uri']] = $strFile;

		return $resFile;
	}


	/**
	 * Close a file
	 * @param resource
	 * @return boolean
	 */
	public function fclose($resFile)
	{
		if (!is_resource($resFile))
		{
			return true;
		}

		$arrData = stream_get_meta_data($resFile);
		$fclose = fclose($resFile);

		// Move temp file
		if (isset($this->arrFiles[$arrData['uri']]))
		{
			$this->rename(preg_replace('/^' . preg_quote(TL_ROOT, '/') . '\//i', '', $arrData['uri']), $this->arrFiles[$arrData['uri']]);
		}

		return $fclose;
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

		// Rename directories
		if (is_dir(TL_ROOT . '/' . $strOldName))
		{
			return @ftp_rename($this->resConnection, $GLOBALS['TL_CONFIG']['ftpPath'] . $strOldName, $GLOBALS['TL_CONFIG']['ftpPath'] . $strNewName);
		}

		// Unix fix: rename case sensitively
		if (strcasecmp($strOldName, $strNewName) === 0 && strcmp($strOldName, $strNewName) !== 0)
		{
			@ftp_rename($this->resConnection, $GLOBALS['TL_CONFIG']['ftpPath'] . $strOldName, $GLOBALS['TL_CONFIG']['ftpPath'] . $strOldName . '__');
			$strOldName .= '__';
		}

		// Copy files to set the correct owner
		$return = $this->copy($strOldName, $strNewName);

		// Delete the old file
		if (!@unlink(TL_ROOT . '/' . $strOldName))
		{
			$this->delete($strOldName);
		}

		return $return;
	}


	/**
	 * Copy a file or folder
	 * @param string
	 * @param string
	 * @return boolean
	 */
	public function copy($strSource, $strDestination)
	{
		$return = @ftp_put($this->resConnection, $GLOBALS['TL_CONFIG']['ftpPath'] . $strDestination, TL_ROOT . '/' . $strSource, FTP_BINARY);
		$this->chmod($strDestination, 0644);

		return $return;
	}


	/**
	 * Delete a file
	 * @param string
	 * @return boolean
	 */
	public function delete($strFile)
	{
		return @ftp_delete($this->resConnection, $GLOBALS['TL_CONFIG']['ftpPath'] . $strFile);
	}


	/**
	 * Change file mode
	 * @param string
	 * @param mixed
	 * @return boolean
	 */
	public function chmod($strFile, $varMode)
	{
		return @ftp_chmod($this->resConnection, $varMode, $GLOBALS['TL_CONFIG']['ftpPath'] . $strFile);
	}


	/**
	 * Check whether a file is writeable
	 * @param string
	 * @return boolean
	 */
	public function is_writeable($strFile)
	{
		return true;
	}


	/**
	 * Move an uploaded file to another folder
	 * @param string
	 * @param string
	 * @return string
	 */
	public function move_uploaded_file($strSource, $strDestination)
	{
		return @ftp_put($this->resConnection, $GLOBALS['TL_CONFIG']['ftpPath'] . $strDestination, $strSource, FTP_BINARY);
	}
}

?>