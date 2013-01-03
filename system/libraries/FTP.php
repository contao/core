<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class FTP
 *
 * Provide methods to modify files and folders via FTP.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
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
	 * Is connected
	 * @var boolean
	 */
	protected $blnIsConnected = false;

	/**
	 * Files array
	 * @var array
	 */
	protected $arrFiles = array();


	/**
	 * Disconnect from ftp server
	 */
	public function __destruct()
	{
		if ($this->blnIsConnected)
		{
			@ftp_close($this->resConnection);
		}
	}


	/**
	 * Establish an FTP connection
	 * @throws Exception
	 */
	public function connect()
	{
		if ($this->blnIsConnected)
		{
			return;
		}

		// Check the FTP credentials
		if ($GLOBALS['TL_CONFIG']['ftpHost'] == '')
		{
			throw new Exception('The FTP host must not be empty');
		}
		elseif ($GLOBALS['TL_CONFIG']['ftpUser'] == '')
		{
			throw new Exception('The FTP username must not be empty');
		}
		elseif ($GLOBALS['TL_CONFIG']['ftpPass'] == '')
		{
			throw new Exception('The FTP password must not be empty');
		}

		$ftp_connect = ($GLOBALS['TL_CONFIG']['ftpSSL'] && function_exists('ftp_ssl_connect')) ? 'ftp_ssl_connect' : 'ftp_connect';

		// Try to connect
		if (($resConnection = $ftp_connect($GLOBALS['TL_CONFIG']['ftpHost'], $GLOBALS['TL_CONFIG']['ftpPort'], 5)) == false)
		{
			throw new Exception('Could not connect to the FTP server');
		}

		// Try to login
		elseif (ftp_login($resConnection, $GLOBALS['TL_CONFIG']['ftpUser'], $GLOBALS['TL_CONFIG']['ftpPass']) == false)
		{
			throw new Exception('Authentication failed');
		}

		// Switch to passive mode
		ftp_pasv($resConnection, true);

		$this->blnIsConnected = true;
		$this->resConnection = $resConnection;
	}


	/**
	 * Create a directory
	 * @param string
	 * @return boolean
	 */
	public function mkdir($strDirectory)
	{
		$this->connect();
		$this->validate($strDirectory);
		$return = @ftp_mkdir($this->resConnection, $GLOBALS['TL_CONFIG']['ftpPath'] . $strDirectory) ? true : false;
		$this->chmod($strDirectory, $GLOBALS['TL_CONFIG']['defaultFolderChmod']);

		return $return;
	}


	/**
	 * Remove a directory
	 * @param string
	 * @return boolean
	 */
	public function rmdir($strDirectory)
	{
		$this->connect();
		$this->validate($strDirectory);

		return @ftp_rmdir($this->resConnection, $GLOBALS['TL_CONFIG']['ftpPath'] . $strDirectory);
	}


	/**
	 * Open a file and return the handle
	 * @param string
	 * @param string
	 * @return resource|boolean
	 */
	public function fopen($strFile, $strMode)
	{
		$this->validate($strFile);
		$resFile = fopen(TL_ROOT . '/system/tmp/' . md5(uniqid(mt_rand(), true)), $strMode);

		// Copy the temp file
		if (!file_exists(TL_ROOT . '/' . $strFile))
		{
			$this->connect();

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

		// Move the temp file
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
		// Source file == target file
		if ($strOldName == $strNewName)
		{
			return true;
		}

		$this->connect();
		$this->validate($strOldName, $strNewName);

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
		$this->connect();
		$this->validate($strSource, $strDestination);
		$return = @ftp_put($this->resConnection, $GLOBALS['TL_CONFIG']['ftpPath'] . $strDestination, TL_ROOT . '/' . $strSource, FTP_BINARY);

		if (is_dir(TL_ROOT . '/' . $strDestination))
		{
			$this->chmod($strDestination, $GLOBALS['TL_CONFIG']['defaultFolderChmod']);
		}
		else
		{
			$this->chmod($strDestination, $GLOBALS['TL_CONFIG']['defaultFileChmod']);
		}

		return $return;
	}


	/**
	 * Delete a file
	 * @param string
	 * @return boolean
	 */
	public function delete($strFile)
	{
		$this->connect();
		$this->validate($strFile);

		return @ftp_delete($this->resConnection, $GLOBALS['TL_CONFIG']['ftpPath'] . $strFile);
	}


	/**
	 * Change the file mode
	 * @param string
	 * @param mixed
	 * @return boolean
	 */
	public function chmod($strFile, $varMode)
	{
		$this->connect();
		$this->validate($strFile);

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
	 * @return boolean
	 */
	public function move_uploaded_file($strSource, $strDestination)
	{
		$this->connect();
		$this->validate($strSource, $strDestination);

		return @ftp_put($this->resConnection, $GLOBALS['TL_CONFIG']['ftpPath'] . $strDestination, $strSource, FTP_BINARY);
	}
}

?>