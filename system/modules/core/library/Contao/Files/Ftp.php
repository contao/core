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
 * Manage files via FTP ("Safe Mode Hack")
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Ftp extends \Files
{

	/**
	 * FTP connection
	 * @var resource
	 */
	protected $resConnection;

	/**
	 * Connection indicator
	 * @var boolean
	 */
	protected $blnIsConnected = false;

	/**
	 * Files
	 * @var array
	 */
	protected $arrFiles = array();


	/**
	 * Disconnect from FTP server
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
	 *
	 * @throws \Exception If an FTP connection cannot be established
	 */
	public function connect()
	{
		if ($this->blnIsConnected)
		{
			return;
		}

		// Check the FTP credentials
		if (\Config::get('ftpHost') == '')
		{
			throw new \Exception('The FTP host must not be empty');
		}
		elseif (\Config::get('ftpUser') == '')
		{
			throw new \Exception('The FTP username must not be empty');
		}
		elseif (\Config::get('ftpPass') == '')
		{
			throw new \Exception('The FTP password must not be empty');
		}

		$ftp_connect = (\Config::get('ftpSSL') && function_exists('ftp_ssl_connect')) ? 'ftp_ssl_connect' : 'ftp_connect';

		// Try to connect
		if (($resConnection = $ftp_connect(\Config::get('ftpHost'), \Config::get('ftpPort'), 5)) == false)
		{
			throw new \Exception('Could not connect to the FTP server');
		}

		// Try to login
		elseif (ftp_login($resConnection, \Config::get('ftpUser'), \Config::get('ftpPass')) == false)
		{
			throw new \Exception('Authentication failed');
		}

		// Switch to passive mode
		ftp_pasv($resConnection, true);

		$this->blnIsConnected = true;
		$this->resConnection = $resConnection;
	}


	/**
	 * Create a directory
	 *
	 * @param string $strDirectory The directory name
	 *
	 * @return boolean True if the operation was successful
	 */
	public function mkdir($strDirectory)
	{
		$this->connect();
		$this->validate($strDirectory);
		$return = @ftp_mkdir($this->resConnection, \Config::get('ftpPath') . $strDirectory) ? true : false;
		$this->chmod($strDirectory, \Config::get('defaultFolderChmod'));

		return $return;
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
		$this->connect();
		$this->validate($strDirectory);

		return @ftp_rmdir($this->resConnection, \Config::get('ftpPath') . $strDirectory);
	}


	/**
	 * Open a file and return the handle
	 *
	 * @param string $strFile The file name
	 * @param string $strMode The operation mode
	 *
	 * @return resource|boolean The file handle or false if there was an error
	 */
	public function fopen($strFile, $strMode)
	{
		$this->validate($strFile);
		$resFile = fopen(TL_ROOT . '/system/tmp/' . md5(uniqid(mt_rand(), true)), $strMode);

		// Copy the temp file
		if (!file_exists(TL_ROOT . '/' . $strFile))
		{
			$this->connect();

			if (!@ftp_fput($this->resConnection, \Config::get('ftpPath') . $strFile, $resFile, FTP_BINARY))
			{
				return false;
			}
		}

		$arrData = stream_get_meta_data($resFile);
		$this->arrFiles[$arrData['uri']] = $strFile;

		return $resFile;
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
			return @ftp_rename($this->resConnection, \Config::get('ftpPath') . $strOldName, \Config::get('ftpPath') . $strNewName);
		}

		// Unix fix: rename case sensitively
		if (strcasecmp($strOldName, $strNewName) === 0 && strcmp($strOldName, $strNewName) !== 0)
		{
			@ftp_rename($this->resConnection, \Config::get('ftpPath') . $strOldName, \Config::get('ftpPath') . $strOldName . '__');
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
	 *
	 * @param string $strSource      The source file or folder
	 * @param string $strDestination The new file or folder path
	 *
	 * @return boolean True if the operation was successful
	 */
	public function copy($strSource, $strDestination)
	{
		$this->connect();
		$this->validate($strSource, $strDestination);
		$return = @ftp_put($this->resConnection, \Config::get('ftpPath') . $strDestination, TL_ROOT . '/' . $strSource, FTP_BINARY);

		if (is_dir(TL_ROOT . '/' . $strDestination))
		{
			$this->chmod($strDestination, \Config::get('defaultFolderChmod'));
		}
		else
		{
			$this->chmod($strDestination, \Config::get('defaultFileChmod'));
		}

		return $return;
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
		$this->connect();
		$this->validate($strFile);

		return @ftp_delete($this->resConnection, \Config::get('ftpPath') . $strFile);
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
		$this->connect();
		$this->validate($strFile);

		return @ftp_chmod($this->resConnection, $varMode, \Config::get('ftpPath') . $strFile);
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
		return true;
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
		$this->connect();
		$this->validate($strSource, $strDestination);

		return @ftp_put($this->resConnection, \Config::get('ftpPath') . $strDestination, $strSource, FTP_BINARY);
	}
}
