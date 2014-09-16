<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


/**
 * Creates, reads, writes and deletes files
 *
 * Usage:
 *
 *     $file = new File('test.txt');
 *     $file->write('This is a test');
 *     $file->close();
 *
 *     $file->delete();
 *
 *     File::putContent('test.txt', 'This is a test');
 *
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2014
 */
class File extends \System
{

	/**
	 * File handle
	 * @var resource
	 */
	protected $resFile;

	/**
	 * File name
	 * @var string
	 */
	protected $strFile;

	/**
	 * Temp name
	 * @var string
	 */
	protected $strTmp;

	/**
	 * Files model
	 * @var \FilesModel
	 */
	protected $objModel;

	/**
	 * Pathinfo
	 * @var array
	 */
	protected $arrPathinfo = array();

	/**
	 * Image size
	 * @var array
	 */
	protected $arrImageSize = array();

	/**
	 * Do not create the file
	 * @var string
	 */
	protected $blnDoNotCreate = false;


	/**
	 * Instantiate a new file object
	 *
	 * @param string  $strFile        The file path
	 * @param boolean $blnDoNotCreate If true, the file will not be autocreated
	 *
	 * @throws \Exception If $strFile is a directory
	 */
	public function __construct($strFile, $blnDoNotCreate=false)
	{
		// Handle open_basedir restrictions
		if ($strFile == '.')
		{
			$strFile = '';
		}

		// Make sure we are not pointing to a directory
		if (is_dir(TL_ROOT . '/' . $strFile))
		{
			throw new \Exception(sprintf('Directory "%s" is not a file', $strFile));
		}

		$this->import('Files');

		$this->strFile = $strFile;
		$this->blnDoNotCreate = $blnDoNotCreate;
		$strFolder = dirname($strFile);

		// Check whether we need to sync the database
		$this->blnSyncDb = (\Config::get('uploadPath') != 'templates' && strncmp($strFolder . '/', \Config::get('uploadPath') . '/', strlen(\Config::get('uploadPath')) + 1) === 0);

		// Check the excluded folders
		if ($this->blnSyncDb && \Config::get('fileSyncExclude') != '')
		{
			$arrExempt = array_map(function($e) {
				return \Config::get('uploadPath') . '/' . $e;
			}, trimsplit(',', \Config::get('fileSyncExclude')));

			foreach ($arrExempt as $strExempt)
			{
				if (strncmp($strExempt . '/', $strFolder . '/', strlen($strExempt) + 1) === 0)
				{
					$this->blnSyncDb = false;
					break;
				}
			}
		}

		if (!$blnDoNotCreate)
		{
			$this->createIfNotExists();
		}
	}


	/**
	 * Close the file handle if it has not been done yet
	 */
	public function __destruct()
	{
		if (is_resource($this->resFile))
		{
			$this->Files->fclose($this->resFile);
		}
	}


	/**
	 * Return an object property
	 *
	 * Supported keys:
	 *
	 * * size:        the file size
	 * * name:        the file name and extension
	 * * dirname:     the path of the parent folder
	 * * extension:   the file extension
	 * * filename:    the file name without extension
	 * * mime:        the file's mime type
	 * * hash:        the file's MD5 checksum
	 * * ctime:       the file's ctime
	 * * mtime:       the file's mtime
	 * * atime:       the file's atime
	 * * icon:        the name of the corresponding mime icon
	 * * path:        the path to the file
	 * * width:       the file width (images only)
	 * * height:      the file height (images only)
	 * * isGdImage:   true if the file can be handled by the GDlib
	 * * channels:    the number of channels (images only)
	 * * bits:        the number of bits for each color (images only)
	 * * isRgbImage:  true if the file is an RGB image
	 * * isCmykImage: true if the file is a CMYK image
	 * * handle:      the file handle (returned by fopen())
	 *
	 * @param string $strKey The property name
	 *
	 * @return mixed The property value
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'size':
			case 'filesize':
				return filesize(TL_ROOT . '/' . $this->strFile);
				break;

			case 'name':
			case 'basename':
				if (!isset($this->arrPathinfo[$strKey]))
				{
					$this->arrPathinfo = pathinfo(TL_ROOT . '/' . $this->strFile);
				}
				return $this->arrPathinfo['basename'];
				break;

			case 'dirname':
				if (!isset($this->arrPathinfo[$strKey]))
				{
					$this->arrPathinfo = pathinfo(TL_ROOT . '/' . $this->strFile);
				}
				return $this->arrPathinfo['dirname'];
				break;

			case 'extension':
				if (!isset($this->arrPathinfo['extension']))
				{
					$this->arrPathinfo = pathinfo(TL_ROOT . '/' . $this->strFile);
				}
				return strtolower($this->arrPathinfo['extension']);
				break;

			case 'filename':
				if (!isset($this->arrPathinfo[$strKey]))
				{
					$this->arrPathinfo = pathinfo(TL_ROOT . '/' . $this->strFile);
				}
				return $this->arrPathinfo['filename'];
				break;

			case 'tmpname':
				return basename($this->strTmp);
				break;

			case 'path':
			case 'value':
				return $this->strFile;
				break;

			case 'mime':
				return $this->getMimeType();
				break;

			case 'hash':
				return $this->getHash();
				break;

			case 'ctime':
				return filectime(TL_ROOT . '/' . $this->strFile);
				break;

			case 'mtime':
				return filemtime(TL_ROOT . '/' . $this->strFile);
				break;

			case 'atime':
				return fileatime(TL_ROOT . '/' . $this->strFile);
				break;

			case 'icon':
				return $this->getIcon();
				break;

			case 'width':
				if (empty($this->arrImageSize))
				{
					$this->arrImageSize = @getimagesize(TL_ROOT . '/' . $this->strFile);
				}
				return $this->arrImageSize[0];
				break;

			case 'height':
				if (empty($this->arrImageSize))
				{
					$this->arrImageSize = @getimagesize(TL_ROOT . '/' . $this->strFile);
				}
				return $this->arrImageSize[1];
				break;

			case 'isGdImage':
				return in_array($this->extension, array('gif', 'jpg', 'jpeg', 'png'));
				break;

			case 'channels':
				if (empty($this->arrImageSize))
				{
					$this->arrImageSize = @getimagesize(TL_ROOT . '/' . $this->strFile);
				}
				return $this->arrImageSize['channels'];
				break;

			case 'bits':
				if (empty($this->arrImageSize))
				{
					$this->arrImageSize = @getimagesize(TL_ROOT . '/' . $this->strFile);
				}
				return $this->arrImageSize['bits'];
				break;

			case 'isRgbImage':
				return ($this->channels == 3);
				break;

			case 'isCmykImage':
				return ($this->channels == 4);
				break;

			case 'handle':
				if (!is_resource($this->resFile))
				{
					$this->resFile = fopen(TL_ROOT . '/' . $this->strFile, 'rb');
				}
				return $this->resFile;
				break;

			default:
				return parent::__get($strKey);
				break;
		}
	}


	/**
	 * Create the file if it does not yet exist
	 *
	 * @throws \Exception If the file cannot be written
	 */
	protected function createIfNotExists()
	{
		// The file exists
		if (file_exists(TL_ROOT . '/' . $this->strFile))
		{
			return;
		}

		// Handle open_basedir restrictions
		if (($strFolder = dirname($this->strFile)) == '.')
		{
			$strFolder = '';
		}

		// Create the folder
		if (!is_dir(TL_ROOT . '/' . $strFolder))
		{
			new \Folder($strFolder);
		}

		// Open the file
		if (($this->resFile = $this->Files->fopen($this->strFile, 'wb')) == false)
		{
			throw new \Exception(sprintf('Cannot create file "%s"', $this->strFile));
		}
	}


	/**
	 * Check whether the file exists
	 *
	 * @return boolean True if the file exists
	 */
	public function exists()
	{
		return $this->blnDoNotCreate ? file_exists(TL_ROOT . '/' . $this->strFile) : true;
	}


	/**
	 * Truncate the file
	 *
	 * @return boolean True if the operation was successful
	 */
	public function truncate()
	{
		if (is_resource($this->resFile))
		{
			ftruncate($this->resFile, 0);
		}

		return $this->write('');
	}


	/**
	 * Write data to the file
	 *
	 * @param mixed $varData The data to be written
	 *
	 * @return boolean True if the operation was successful
	 */
	public function write($varData)
	{
		return $this->fputs($varData, 'wb');
	}


	/**
	 * Append data to the file
	 *
	 * @param mixed  $varData The data to be appended
	 * @param string $strLine The line ending (defaults to LF)
	 *
	 * @return boolean True if the operation was successful
	 */
	public function append($varData, $strLine="\n")
	{
		return $this->fputs($varData . $strLine, 'ab');
	}


	/**
	 * Prepend data to the file
	 *
	 * @param mixed  $varData The data to be prepended
	 * @param string $strLine The line ending (defaults to LF)
	 *
	 * @return boolean True if the operation was successful
	 */
	public function prepend($varData, $strLine="\n")
	{
		return $this->fputs($varData . $strLine . $this->getContent(), 'wb');
	}


	/**
	 * Delete the file
	 *
	 * @return boolean True if the operation was successful
	 */
	public function delete()
	{
		$return = $this->Files->delete($this->strFile);

		// Update the database
		if ($this->blnSyncDb)
		{
			\Dbafs::deleteResource($this->strFile);
		}

		return $return;
	}


	/**
	 * Set the file permissions
	 *
	 * @param integer $intChmod The CHMOD settings
	 *
	 * @return boolean True if the operation was successful
	 */
	public function chmod($intChmod)
	{
		return $this->Files->chmod($this->strFile, $intChmod);
	}


	/**
	 * Close the file handle
	 *
	 * @return boolean True if the operation was successful
	 */
	public function close()
	{
		$return = $this->Files->fclose($this->resFile);

		// Move the temporary file to its destination
		if ($this->blnDoNotCreate)
		{
			// Create the file path
			if (!file_exists(TL_ROOT . '/' . $this->strFile))
			{
				// Handle open_basedir restrictions
				if (($strFolder = dirname($this->strFile)) == '.')
				{
					$strFolder = '';
				}

				// Create the parent folder
				if (!is_dir(TL_ROOT . '/' . $strFolder))
				{
					new \Folder($strFolder);
				}
			}

			$return = $this->Files->rename($this->strTmp, $this->strFile);
		}

		// Update the database
		if ($this->blnSyncDb)
		{
			$this->objModel = \Dbafs::addResource($this->strFile);
		}

		return $return;
	}


	/**
	 * Return the files model
	 *
	 * @return \FilesModel The files model
	 */
	public function getModel()
	{
		return $this->objModel;
	}


	/**
	 * Return the file content as string
	 *
	 * @return string The file content without BOM
	 */
	public function getContent()
	{
		$strContent = file_get_contents(TL_ROOT . '/' . $this->strFile);

		// Remove BOMs (see #4469)
		if (strncmp($strContent, "\xEF\xBB\xBF", 3) === 0)
		{
			$strContent = substr($strContent, 3);
		}
		elseif (strncmp($strContent, "\xFF\xFE", 2) === 0)
		{
			$strContent = substr($strContent, 2);
		}
		elseif (strncmp($strContent, "\xFE\xFF", 2) === 0)
		{
			$strContent = substr($strContent, 2);
		}

		return $strContent;
	}


	/**
	 * Write to a file
	 *
	 * @param string $strFile    Relative file name
	 * @param string $strContent Content to be written
	 */
	public static function putContent($strFile, $strContent)
	{
		$objFile = new static($strFile, true);
		$objFile->write($strContent);
		$objFile->close();
	}


	/**
	 * Return the file content as array
	 *
	 * @return array The file content as array
	 */
	public function getContentAsArray()
	{
		return array_map('rtrim', file(TL_ROOT . '/' . $this->strFile));
	}


	/**
	 * Rename the file
	 *
	 * @param string $strNewName The new path
	 *
	 * @return boolean True if the operation was successful
	 */
	public function renameTo($strNewName)
	{
		$strParent = dirname($strNewName);

		// Create the parent folder if it does not exist
		if (!is_dir(TL_ROOT . '/' . $strParent))
		{
			new \Folder($strParent);
		}

		$return = $this->Files->rename($this->strFile, $strNewName);

		// Update the database AFTER the file has been renamed
		if ($this->blnSyncDb)
		{
			$this->objModel = \Dbafs::moveResource($this->strFile, $strNewName);
		}

		// Reset the object AFTER the database has been updated
		if ($return != false)
		{
			$this->strFile = $strNewName;
			$this->arrImageSize = array();
			$this->arrPathinfo = array();
		}

		return $return;
	}


	/**
	 * Copy the file
	 *
	 * @param string $strNewName The target path
	 *
	 * @return boolean True if the operation was successful
	 */
	public function copyTo($strNewName)
	{
		$strParent = dirname($strNewName);

		// Create the parent folder if it does not exist
		if (!is_dir(TL_ROOT . '/' . $strParent))
		{
			new \Folder($strParent);
		}

		$return = $this->Files->copy($this->strFile, $strNewName);

		// Update the database AFTER the file has been renamed
		if ($this->blnSyncDb)
		{
			$this->objModel = \Dbafs::copyResource($this->strFile, $strNewName);
		}

		return $return;
	}


	/**
	 * Resize the file if it is an image
	 *
	 * @param integer $width  The target width
	 * @param integer $height The target height
	 * @param string  $mode   The resize mode
	 *
	 * @return boolean True if the image could be resized successfully
	 */
	public function resizeTo($width, $height, $mode='')
	{
		if (!$this->isGdImage)
		{
			return false;
		}

		$return = \Image::resize($this->strFile, $width, $height, $mode);

		if ($return)
		{
			$this->arrPathinfo = array();
			$this->arrImageSize = array();
		}

		return $return;
	}


	/**
	 * Send the file to the browser
	 *
	 * @param string $filename An optional filename
	 */
	public function sendToBrowser($filename=null)
	{
		// Make sure no output buffer is active
		// @see http://ch2.php.net/manual/en/function.fpassthru.php#74080
		while (@ob_end_clean());

		// Prevent session locking (see #2804)
		session_write_close();

		// Disable zlib.output_compression (see #6717)
		ini_set('zlib.output_compression', 'Off');

		// Open the "save as …" dialogue
		header('Content-Type: ' . $this->mime);
		header('Content-Transfer-Encoding: binary');
		header('Content-Disposition: attachment; filename="' . ($filename ?: $this->basename) . '"');
		header('Content-Length: ' . $this->filesize);
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Expires: 0');
		header('Connection: close');

		// Output the file
		$resFile = fopen(TL_ROOT . '/' . $this->strFile, 'rb');
		fpassthru($resFile);
		fclose($resFile);

		// Stop the script
		exit;
	}


	/**
	 * Write data to a file
	 *
	 * @param mixed  $varData The data to be written
	 * @param string $strMode The operation mode
	 *
	 * @return boolean True if the operation was successful
	 */
	protected function fputs($varData, $strMode)
	{
		if (!is_resource($this->resFile))
		{
			if (!$this->blnDoNotCreate)
			{
				// Open the original file
				if (($this->resFile = $this->Files->fopen($this->strFile, $strMode)) == false)
				{
					return false;
				}
			}
			else
			{
				$this->strTmp = 'system/tmp/' . md5(uniqid(mt_rand(), true));

				// Copy the contents of the original file to append data
				if (strncmp($strMode, 'a', 1) === 0 && file_exists(TL_ROOT . '/' . $this->strFile))
				{
					$this->Files->copy($this->strFile, $this->strTmp);
				}

				// Open the temporary file
				if (($this->resFile = $this->Files->fopen($this->strTmp, $strMode)) == false)
				{
					return false;
				}
			}
		}

		fputs($this->resFile, $varData);
		return true;
	}


	/**
	 * Return the mime type and icon of the file based on its extension
	 *
	 * @return array An array with mime type and icon name
	 */
	protected function getMimeInfo()
	{
		if (isset($GLOBALS['TL_MIME'][$this->extension]))
		{
			return $GLOBALS['TL_MIME'][$this->extension];
		}

		return array('application/octet-stream', 'iconPLAIN.gif');
	}


	/**
	 * Get the mime type of the file based on its extension
	 *
	 * @return string The mime type
	 */
	protected function getMimeType()
	{
		$arrMime = $this->getMimeInfo();
		return $arrMime[0];
	}


	/**
	 * Return the file icon depending on the file type
	 *
	 * @return string The icon name
	 */
	protected function getIcon()
	{
		$arrMime = $this->getMimeInfo();
		return $arrMime[1];
	}


	/**
	 * Return the MD5 hash of the file
	 *
	 * @return string The MD5 hash
	 */
	protected function getHash()
	{
		// Do not try to hash if bigger than 2 GB
		if ($this->filesize >= 2147483648)
		{
			return '';
		}
		else
		{
			return md5_file(TL_ROOT . '/' . $this->strFile);
		}
	}
}
