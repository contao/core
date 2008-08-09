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
 * Class FTP
 *
 * Provide methods to modify files and folders via FTP.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
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
	 * Store the FTP resource and make sure that the temp directory is writeable
	 * @param resource
	 */
	protected function __construct($resConnection)
	{
		if (!is_resource($resConnection))
		{
			throw new Exception('Class FTP requires a valid FTP connection resource');
		}

		$this->resConnection = $resConnection;
		$this->chmod('system/tmp', 0777);
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
		if (strlen($this->arrFiles[$arrData['uri']]))
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

		return @ftp_rename($this->resConnection, $GLOBALS['TL_CONFIG']['ftpPath'] . $strOldName, $GLOBALS['TL_CONFIG']['ftpPath'] . $strNewName);
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
	 * @return boolean
	 */
	public function is_writeable()
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