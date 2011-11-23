<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * Class Uploader
 *
 * Handle different uploader scripts/tools for the back end file manager
 * This is the basic sequence of how an upload (MyUploaderClass::upload()) is being treated
 * but you can do whathever you want in your child classes and use whathever methods you want to:
 * 1. getFilesRomanized()
 * 2. fileCorrectlyUploaded()
 * 3. checkFileSize()
 * 4. checkFileType()
 * 5. moveUploadedFile()
 * 6. resizeImageToSystemLimits()
 * 7. addUploadedFile()
 * 
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @author     Yanick Witschi <yanick.witschi@certo-net.ch>
 * @see		   DC_Folder::move()
 * @package    Library
 */
abstract class Uploader extends Controller
{
	/**
	 * Datacontainer object
	 * @var DataContainer
	 */
	protected $objDC = null;
	
	/**
	 * Destination folder
	 * @var string
	 */
	protected $strDestinationFolder = '';
	
	/**
	 * Allowed upload file types
	 * @var array
	 */
	protected $arrUploadTypes = array();
	
	/**
	 * Upload max file size in bytes
	 * @var int
	 */
	protected $intUploadMaxSize = 0;
	
	/**
	 * Upload max file size in human readable format
	 * @var string
	 */
	protected $strUploadMaxSizeReadable = '';
	
	/**
	 * Array containing all successfully uploaded files (thier paths)
	 * @var array
	 */
	protected $arrUploadedFiles = array();
	
	/**
	 * True if there are errors
	 * @var boolean
	 */
	private $blnHasErrors = false;
	
	
	/**
	 * Set basic data
	 * @param DataContainer
	 * @param string destination folder
	 */
	public function __construct(DataContainer $dc, $strFolder)
	{
		// initialize the object
		parent::__construct();
		
		// data container
		$this->objDC = $dc;
		
		// destination folder
		$this->strDestinationFolder = $strFolder;
		
		// upload types
		$this->arrUploadTypes = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['uploadTypes']));
		
		// Get the upload_max_filesize from the php.ini
		$this->intUploadMaxSize = ini_get('upload_max_filesize');

		// Convert the value to bytes
		if (strpos($this->intUploadMaxSize, 'K') !== false)
		{
			$this->intUploadMaxSize = round($this->intUploadMaxSize * 1024);
		}
		elseif (strpos($this->intUploadMaxSize, 'M') !== false)
		{
			$this->intUploadMaxSize = round($this->intUploadMaxSize * 1024 * 1024);
		}
		elseif (strpos($this->intUploadMaxSize, 'G') !== false)
		{
			$this->intUploadMaxSize = round($this->intUploadMaxSize * 1024 * 1024 * 1024);
		}
		
		// Convert the maximum file size into a human-readable format
		$this->strUploadMaxSizeReadable = $this->getReadableSize(min($upload_max_filesize, $GLOBALS['TL_CONFIG']['maxFileSize']));

	}


	/**
	 * Get an array of all files that have been uploaded
	 * You can easily overwrite this method in your child class for your purposes
	 * @return array
	 */
	public function getUploadedFiles()
	{
		return $this->arrUploadedFiles;
	}
	
	
	/**
	 * Get files with cleaned up file names
	 * @return array
	 */
	protected function getFilesRomanized()
	{
		// never overwrite the original array
		$arrFiles = $_FILES;
		
		foreach ($arrFiles as $k => $file)
		{
			// Romanize the filename
			$arrFiles[$k]['name'] = strip_tags($file['name']);
			$arrFiles[$k]['name'] = utf8_romanize($file['name']);
			$arrFiles[$k]['name'] = str_replace('"', '', $file['name']);
		}
		
		return $arrFiles;
	}
	
	
	/**
	 * Check if a file has not been uploaded correctly
	 * @param array $_FILES array of the specific file
	 * @return boolean
	 */
	protected function fileCorrectlyUploaded($arrFile)
	{
		if (!is_uploaded_file($arrFile['tmp_name']))
		{
			if (in_array($arrFile['error'], array(UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE)))
			{
				$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['filesize'], $this->strUploadMaxSizeReadable));
				$this->log('File "'.$arrFile['name'].'" exceeds the maximum file size of '.$this->strUploadMaxSizeReadable, __METHOD__, TL_ERROR);
			}

			if ($arrFile['error'] == UPLOAD_ERR_PARTIAL)
			{
				$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['filepartial'], $arrFile['name']));
				$this->log('File "'.$arrFile['name'].'" was only partially uploaded' , __METHOD__, TL_ERROR);
			}
			
			return false;
		}
		
		return true;
	}
	
	
	/**
	 * Check if a file size does not exceed the size settings
	 * @param array $_FILES array of the specific file
	 * @return boolean
	 */
	protected function checkFileSize($arrFile)
	{
		// File is too big
		if ($arrFile['size'] > $GLOBALS['TL_CONFIG']['maxFileSize'])
		{
			$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['filesize'], $this->strUploadMaxSizeReadable));
			$this->log('File "'.$arrFile['name'].'" exceeds the maximum file size of '.$this->strUploadMaxSizeReadable, __METHOD__, TL_ERROR);
			return false;
		}

		return true;
	}


	/**
	 * Check if file type is allowed
	 * @param array $_FILES array of the specific file
	 * @return boolean
	 */
	protected function checkFileType($arrFile)
	{
		$pathinfo = pathinfo($arrFile['name']);

		// File type not allowed
		if (!in_array(strtolower($pathinfo['extension']), $this->arrUploadTypes))
		{
			$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $pathinfo['extension']));
			$this->log('File type "'.$pathinfo['extension'].'" is not allowed to be uploaded ('.$arrFile['name'].')', __METHOD__, TL_ERROR);
			return false;
		}
		
		return true;
	}


	/**
	 * Move a file to its destination
	 * @param array $_FILES array of the specific file
	 * @param string path to the new file
	 * @return boolean
	 */
	protected function moveUploadedFile($arrFile, $strNewFile)
	{
		$this->import('Files');

		// Move file to destination
		if ($this->Files->move_uploaded_file($arrFile['tmp_name'], $strNewFile))
		{
			$this->Files->chmod($strNewFile, 0644);
			return true;
		}
		// @todo: add error message here?
		$this->log('The file could not be moved to its final destination ('.$arrFile['name'].')', __METHOD__, TL_ERROR);
		return false;
	}
	
	
	/**
	 * Resizes an image to given system limits
	 * @param array $_FILES array of the specific file
	 * @param string file path (TL_ROOT is applied automatically)
	 * @return array
	 */
	protected function resizeImageToSystemLimits($arrFile, $strFilePath)
	{
		$blnExceeds = false;
		$blnResized = false;
		
		// Resize image if necessary
		if (($arrImageSize = @getimagesize(TL_ROOT . '/' . $strFilePath)) !== false)
		{
			// Image is too big
			if ($arrImageSize[0] > $GLOBALS['TL_CONFIG']['gdMaxImgWidth'] || $arrImageSize[1] > $GLOBALS['TL_CONFIG']['gdMaxImgHeight'])
			{
				$blnExceeds = true;
			}
			else
			{
				// Image exceeds maximum image width
				if ($arrImageSize[0] > $GLOBALS['TL_CONFIG']['imageWidth'])
				{
					$this->resizeImage($strFilePath, $GLOBALS['TL_CONFIG']['imageWidth'], 0);

					// Recalculate image size because it might still be too high
					$arrImageSize = @getimagesize(TL_ROOT . '/' . $strFilePath);
					
					$blnResized = true;
				}

				// Image exceeds maximum image height
				if ($arrImageSize[1] > $GLOBALS['TL_CONFIG']['imageHeight'])
				{
					$this->resizeImage($strFilePath, 0, $GLOBALS['TL_CONFIG']['imageHeight']);
					
					$blnResized = true;
				}
			}
		}
		
		// messaging
		if ($blnExceeds)
		{
			$this->addInfoMessage(sprintf($GLOBALS['TL_LANG']['MSC']['fileExceeds'], $arrFile['name']));
			$this->log('File "'.$arrFile['name'].'" uploaded successfully but was too big to be resized automatically', __METHOD__, TL_FILES);
		}
		
		if ($blnResized)
		{
			$this->addInfoMessage(sprintf($GLOBALS['TL_LANG']['MSC']['fileResized'], $arrFile['name']));
			$this->log('File "'.$arrFile['name'].'" uploaded successfully and was scaled down to the maximum dimensions', __METHOD__, TL_FILES);
		}
		
		return array
		(
			'exceeds'	=> $blnExceeds,
			'resized'	=> $blnResized
		);
	}


	/**
	 * Check if there are errors
	 * @return boolean
	 */
	protected function hasErrors()
	{
		return $this->blnHasErrors;
	}
	
	
	/**
	 * Add an error message (overwrites System::addErrorMessage just to set a boolean)
	 * @param string custom error message
	 */
	protected function addErrorMessage($strMessage)
	{
		$this->blnHasErrors = true;
		parent::addErrorMessage($strMessage);
	}
	

	/**
	 * Add an uploaded file
	 * @param string file path
	 */
	public function addUploadedFile($strFile)
	{
		$this->arrUploadedFiles[] = $strFile;
	}
	
	
	/**
	 * Generate the default HTML view
	 * @abstract
	 * @return string
	 */
	abstract public function generate();
	
	/**
	 * Called when POST data is action=uploadProvider&providerKey=<key>
	 * @abstract
	 */
	abstract public function generateAjax();
	
	/**
	 * Use this method to return true if the upload should be invoked (e.g. if FORM_SUBMIT is set)
	 * @abstract
	 * @return boolean
	 */
	abstract public function invokeUpload();
	
	/**
	 * Processes the upload
	 * @abstract
	 */
	abstract public function upload();
	
	/**
	 * Gets called after the upload
	 * @abstract
	 */
	abstract public function postUpload();
}

?>