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
 * Class FileUpload
 *
 * Provide methods to handle file uploads in the back end.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Backend
 */
class FileUpload extends Backend
{

	/**
	 * Error indicator
	 * @var boolean
	 */
	protected $blnHasError = false;

	/**
	 * Resized indicator
	 * @var boolean
	 */
	protected $blnHasResized = false;


	/**
	 * Make the constructor public (see #5054)
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Return true if there was an error
	 * @return boolean
	 */
	public function hasError()
	{
		return $this->blnHasError;
	}


	/**
	 * Return true if there was a resized image
	 * @return boolean
	 */
	public function hasResized()
	{
		return $this->blnHasResized;
	}


	/**
	 * Check the uploaded files and move them to the target directory
	 * @param string
	 * @param string
	 * @return array
	 * @throws Expcetion
	 */
	public function uploadTo($strTarget, $strKey)
	{
		if ($strTarget == '' || strpos($strTarget, '../') !== false)
		{
			throw new Exception("Invalid target path $strTarget");
		}

		if ($strKey == '')
		{
			throw new Exception('The key must not be empty');
		}

		$maxlength_kb = $this->getMaximumUploadSize();
		$maxlength_kb_readable = $this->getReadableSize($maxlength_kb);
		$arrUploaded = array();
		$arrFiles = $this->getFilesFromGlobal($strKey);

		foreach ($arrFiles as $file)
		{
			// Romanize the filename
			$file['name'] = strip_tags($file['name']);
			$file['name'] = utf8_romanize($file['name']);
			$file['name'] = str_replace('"', '', $file['name']);

			// File was not uploaded
			if (!is_uploaded_file($file['tmp_name']))
			{
				if ($file['error'] == 1 || $file['error'] == 2)
				{
					$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['filesize'], $maxlength_kb_readable));
					$this->log('File "'.$file['name'].'" exceeds the maximum file size of '.$maxlength_kb_readable, 'Uploader uploadTo()', TL_ERROR);
					$this->blnHasError = true;
				}
				elseif ($file['error'] == 3)
				{
					$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['filepartial'], $file['name']));
					$this->log('File "'.$file['name'].'" was only partially uploaded' , 'Uploader uploadTo()', TL_ERROR);
					$this->blnHasError = true;
				}
			}

			// File is too big
			elseif ($file['size'] > $maxlength_kb)
			{
				$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['filesize'], $maxlength_kb_readable));
				$this->log('File "'.$file['name'].'" exceeds the maximum file size of '.$maxlength_kb_readable, 'Uploader uploadTo()', TL_ERROR);
				$this->blnHasError = true;
			}

			// Move the file to its destination
			else
			{
				$pathinfo = pathinfo($file['name']);
				$arrAllowedTypes = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['uploadTypes']));

				// File type not allowed
				if (!in_array(strtolower($pathinfo['extension']), $arrAllowedTypes))
				{
					$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $pathinfo['extension']));
					$this->log('File type "'.$pathinfo['extension'].'" is not allowed to be uploaded ('.$file['name'].')', 'Uploader uploadTo()', TL_ERROR);
					$this->blnHasError = true;
				}
				else
				{
					$this->import('Files');
					$strNewFile = $strTarget . '/' . $file['name'];

					// Set CHMOD and resize if neccessary
					if ($this->Files->move_uploaded_file($file['tmp_name'], $strNewFile))
					{
						$this->Files->chmod($strNewFile, $GLOBALS['TL_CONFIG']['defaultFileChmod']);
						$blnResized = $this->resizeUploadedImage($strNewFile, $file);

						// Notify the user
						if (!$blnResized)
						{
							$this->addConfirmationMessage(sprintf($GLOBALS['TL_LANG']['MSC']['fileUploaded'], $file['name']));
							$this->log('File "'.$file['name'].'" uploaded successfully', 'Uploader uploadTo()', TL_FILES);
						}

						$arrUploaded[] = $strNewFile;
					}
				}
			}
		}

		return $arrUploaded;
	}


	public function generateMarkup()
	{
		$fields = '';

		for ($i=0; $i<$GLOBALS['TL_CONFIG']['uploadFields']; $i++)
		{
			$fields .= '
  <input type="file" name="files[]" class="tl_upload_field" onfocus="Backend.getScrollOffset()"><br>';
		}

		return '
  <div id="upload-fields">'.$fields.'
  </div>
  <script>
  window.addEvent("domready", function() {
    if ("multiple" in document.createElement("input")) {
      var div = $("upload-fields");
      var input = div.getElement("input");
      div.empty();
      input.set("multiple", true);
      input.inject(div);
    }
  });
  </script>';
	}

  
	/**
	 * Get the files from the global $_FILES array
	 * @param key
	 * @return array
	 */
	protected function getFilesFromGlobal($strKey)
	{
		$arrFiles = array();

		for ($i=0; $i<count($_FILES[$strKey]['name']); $i++)
		{
			if ($_FILES[$strKey]['name'][$i] == '')
			{
				continue;
			}

			$arrFiles[] = array
			(
				'name'     => $_FILES[$strKey]['name'][$i],
				'type'     => $_FILES[$strKey]['type'][$i],
				'tmp_name' => $_FILES[$strKey]['tmp_name'][$i],
				'error'    => $_FILES[$strKey]['error'][$i],
				'size'     => $_FILES[$strKey]['size'][$i],
			);
		}

		return $arrFiles;
	}


	/**
	 * Return the maximum upload file size in bytes
	 * @return string
	 */
	protected function getMaximumUploadSize()
	{
		// Get the upload_max_filesize from the php.ini
		$upload_max_filesize = ini_get('upload_max_filesize');

		// Convert the value to bytes
		if (strpos($upload_max_filesize, 'K') !== false)
		{
			$upload_max_filesize = round($upload_max_filesize * 1024);
		}
		elseif (strpos($upload_max_filesize, 'M') !== false)
		{
			$upload_max_filesize = round($upload_max_filesize * 1024 * 1024);
		}
		elseif (strpos($upload_max_filesize, 'G') !== false)
		{
			$upload_max_filesize = round($upload_max_filesize * 1024 * 1024 * 1024);
		}

		return min($upload_max_filesize, $GLOBALS['TL_CONFIG']['maxFileSize']);
	}


	/**
	 * Resize an uploaded image if neccessary
	 * @param string
	 * @return boolean
	 */
	protected function resizeUploadedImage($strImage)
	{
		// The feature is disabled
		if ($GLOBALS['TL_CONFIG']['imageWidth'] < 1 && $GLOBALS['TL_CONFIG']['imageHeight'] < 1)
		{
			return false;
		}

		// Not an image
		if (($arrImageSize = @getimagesize(TL_ROOT . '/' . $strImage)) === false)
		{
			return false;
		}

		$strName = basename($strImage);

		// The image is too big to be handled by the GD library
		if ($arrImageSize[0] > $GLOBALS['TL_CONFIG']['gdMaxImgWidth'] || $arrImageSize[1] > $GLOBALS['TL_CONFIG']['gdMaxImgHeight'])
		{
			$this->addInfoMessage(sprintf($GLOBALS['TL_LANG']['MSC']['fileExceeds'], $strName));
			$this->log('File "'.$strName.'" uploaded successfully but was too big to be resized automatically', 'Uploader resizeUploadedImage()', TL_FILES);
			return false;
		}

		$blnResize = false;

		// The image exceeds the maximum image width
		if ($arrImageSize[0] > $GLOBALS['TL_CONFIG']['imageWidth'])
		{
			$blnResize = true;
			$intWidth = $GLOBALS['TL_CONFIG']['imageWidth'];
			$intHeight = round($GLOBALS['TL_CONFIG']['imageWidth'] * $arrImageSize[1] / $arrImageSize[0]);
			$arrImageSize = array($intWidth, $intHeight);
		}

		// The image exceeds the maximum image height
		if ($arrImageSize[1] > $GLOBALS['TL_CONFIG']['imageHeight'])
		{
			$blnResize = true;
			$intWidth = round($GLOBALS['TL_CONFIG']['imageHeight'] * $arrImageSize[0] / $arrImageSize[1]);
			$intHeight = $GLOBALS['TL_CONFIG']['imageHeight'];
			$arrImageSize = array($intWidth, $intHeight);
		}

		// Resized successfully
		if ($blnResize)
		{
			$this->resizeImage($strImage, $arrImageSize[0], $arrImageSize[1]);
			$this->addInfoMessage(sprintf($GLOBALS['TL_LANG']['MSC']['fileResized'], $strName));
			$this->log('File "'.$strName.'" uploaded successfully and was scaled down to the maximum dimensions', 'Uploader resizeUploadedImage()', TL_FILES);
			$this->blnHasResized = true;
			return true;
		}

		return false;
	}
}

?>