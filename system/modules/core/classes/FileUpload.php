<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class FileUpload
 *
 * Provide methods to handle file uploads in the back end.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class FileUpload extends \Backend
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
	 * Field name
	 * @var string
	 */
	protected $strName = 'files';


	/**
	 * Make the constructor public
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
	 * Override the field name
	 * @param string
	 */
	public function setName($strName)
	{
		$this->strName = $strName;
	}


	/**
	 * Check the uploaded files and move them to the target directory
	 * @param string
	 * @return array
	 * @throws \Exception
	 */
	public function uploadTo($strTarget)
	{
		if ($strTarget == '' || strpos($strTarget, '../') !== false)
		{
			throw new \Exception("Invalid target path $strTarget");
		}

		$maxlength_kb = $this->getMaximumUploadSize();
		$maxlength_kb_readable = $this->getReadableSize($maxlength_kb);
		$arrUploaded = array();
		$arrFiles = $this->getFilesFromGlobal();

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
					\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['filesize'], $maxlength_kb_readable));
					$this->log('File "'.$file['name'].'" exceeds the maximum file size of '.$maxlength_kb_readable, __METHOD__, TL_ERROR);
					$this->blnHasError = true;
				}
				elseif ($file['error'] == 3)
				{
					\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['filepartial'], $file['name']));
					$this->log('File "'.$file['name'].'" was only partially uploaded' , __METHOD__, TL_ERROR);
					$this->blnHasError = true;
				}
				elseif ($file['error'] > 0)
				{
					\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['fileerror'], $file['error'], $file['name']));
					$this->log('File "'.$file['name'].'" could not be uploaded (error '.$file['error'].')' , __METHOD__, TL_ERROR);
					$this->blnHasError = true;
				}
			}

			// File is too big
			elseif ($file['size'] > $maxlength_kb)
			{
				\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['filesize'], $maxlength_kb_readable));
				$this->log('File "'.$file['name'].'" exceeds the maximum file size of '.$maxlength_kb_readable, __METHOD__, TL_ERROR);
				$this->blnHasError = true;
			}

			// Move the file to its destination
			else
			{
				$strExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
				$arrAllowedTypes = trimsplit(',', strtolower(\Config::get('uploadTypes')));

				// File type not allowed
				if (!in_array(strtolower($strExtension), $arrAllowedTypes))
				{
					\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $strExtension));
					$this->log('File type "'.$strExtension.'" is not allowed to be uploaded ('.$file['name'].')', __METHOD__, TL_ERROR);
					$this->blnHasError = true;
				}
				else
				{
					$this->import('Files');
					$strNewFile = $strTarget . '/' . $file['name'];

					// Set CHMOD and resize if neccessary
					if ($this->Files->move_uploaded_file($file['tmp_name'], $strNewFile))
					{
						$this->Files->chmod($strNewFile, \Config::get('defaultFileChmod'));
						$blnResized = $this->resizeUploadedImage($strNewFile, $file);

						// Notify the user
						if (!$blnResized)
						{
							\Message::addConfirmation(sprintf($GLOBALS['TL_LANG']['MSC']['fileUploaded'], $file['name']));
							$this->log('File "'.$file['name'].'" uploaded successfully', __METHOD__, TL_FILES);
						}

						$arrUploaded[] = $strNewFile;
					}
				}
			}
		}

		return $arrUploaded;
	}


	/**
	 * Generate the markup for the default uploader
	 * @return string
	 */
	public function generateMarkup()
	{
		$fields = '';

		for ($i=0; $i<\Config::get('uploadFields'); $i++)
		{
			$fields .= '
  <input type="file" name="' . $this->strName . '[]" class="tl_upload_field" onfocus="Backend.getScrollOffset()"><br>';
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
	 * @return array
	 */
	protected function getFilesFromGlobal()
	{
		$arrFiles = array();
		$intCount = count($_FILES[$this->strName]['name']);

		for ($i=0; $i<$intCount; $i++)
		{
			if ($_FILES[$this->strName]['name'][$i] == '')
			{
				continue;
			}

			$arrFiles[] = array
			(
				'name'     => $_FILES[$this->strName]['name'][$i],
				'type'     => $_FILES[$this->strName]['type'][$i],
				'tmp_name' => $_FILES[$this->strName]['tmp_name'][$i],
				'error'    => $_FILES[$this->strName]['error'][$i],
				'size'     => $_FILES[$this->strName]['size'][$i],
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
		if (stripos($upload_max_filesize, 'K') !== false)
		{
			$upload_max_filesize = round($upload_max_filesize * 1024);
		}
		elseif (stripos($upload_max_filesize, 'M') !== false)
		{
			$upload_max_filesize = round($upload_max_filesize * 1024 * 1024);
		}
		elseif (stripos($upload_max_filesize, 'G') !== false)
		{
			$upload_max_filesize = round($upload_max_filesize * 1024 * 1024 * 1024);
		}

		return min($upload_max_filesize, \Config::get('maxFileSize'));
	}


	/**
	 * Resize an uploaded image if neccessary
	 * @param string
	 * @return boolean
	 */
	protected function resizeUploadedImage($strImage)
	{
		// The feature is disabled
		if (\Config::get('imageWidth') < 1 && \Config::get('imageHeight') < 1)
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
		if ($arrImageSize[0] > \Config::get('gdMaxImgWidth') || $arrImageSize[1] > \Config::get('gdMaxImgHeight'))
		{
			\Message::addInfo(sprintf($GLOBALS['TL_LANG']['MSC']['fileExceeds'], $strName));
			$this->log('File "'.$strName.'" uploaded successfully but was too big to be resized automatically', __METHOD__, TL_FILES);
			return false;
		}

		$blnResize = false;

		// The image exceeds the maximum image width
		if ($arrImageSize[0] > \Config::get('imageWidth'))
		{
			$blnResize = true;
			$intWidth = \Config::get('imageWidth');
			$intHeight = round(\Config::get('imageWidth') * $arrImageSize[1] / $arrImageSize[0]);
			$arrImageSize = array($intWidth, $intHeight);
		}

		// The image exceeds the maximum image height
		if ($arrImageSize[1] > \Config::get('imageHeight'))
		{
			$blnResize = true;
			$intWidth = round(\Config::get('imageHeight') * $arrImageSize[0] / $arrImageSize[1]);
			$intHeight = \Config::get('imageHeight');
			$arrImageSize = array($intWidth, $intHeight);
		}

		// Resized successfully
		if ($blnResize)
		{
			\Image::resize($strImage, $arrImageSize[0], $arrImageSize[1]);
			\Message::addInfo(sprintf($GLOBALS['TL_LANG']['MSC']['fileResized'], $strName));
			$this->log('File "'.$strName.'" uploaded successfully and was scaled down to the maximum dimensions', __METHOD__, TL_FILES);
			$this->blnHasResized = true;
			return true;
		}

		return false;
	}
}
