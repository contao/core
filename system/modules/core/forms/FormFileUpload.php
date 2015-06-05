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
 * Class FormFileUpload
 *
 * @property boolean $mandatory
 * @property integer $maxlength
 * @property integer $fSize
 * @property string  $extensions
 * @property string  $uploadFolder
 * @property boolean $doNotOverwrite
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FormFileUpload extends \Widget implements \uploadable
{

	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'form_upload';

	/**
	 * The CSS class prefix
	 *
	 * @var string
	 */
	protected $strPrefix = 'widget widget-upload';


	/**
	 * Add specific attributes
	 *
	 * @param string $strKey   The attribute name
	 * @param mixed  $varValue The attribute value
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'maxlength':
				// Do not add as attribute (see #3094)
				$this->arrConfiguration['maxlength'] = $varValue;
				break;

			case 'mandatory':
				if ($varValue)
				{
					$this->arrAttributes['required'] = 'required';
				}
				else
				{
					unset($this->arrAttributes['required']);
				}
				parent::__set($strKey, $varValue);
				break;

			case 'fSize':
				if ($varValue > 0)
				{
					$this->arrAttributes['size'] = $varValue;
				}
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Validate the input and set the value
	 */
	public function validate()
	{
		// No file specified
		if (!isset($_FILES[$this->strName]) || empty($_FILES[$this->strName]['name']))
		{
			if ($this->mandatory)
			{
				if ($this->strLabel == '')
				{
					$this->addError($GLOBALS['TL_LANG']['ERR']['mdtryNoLabel']);
				}
				else
				{
					$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory'], $this->strLabel));
				}
			}

			return;
		}

		$file = $_FILES[$this->strName];
		$maxlength_kb = $this->getReadableSize($this->maxlength);

		// Sanitize the filename
		try
		{
			$file['name'] = \String::sanitizeFileName($file['name']);
		}
		catch (\InvalidArgumentException $e)
		{
			$this->addError($GLOBALS['TL_LANG']['ERR']['filename']);

			return;
		}

		// Invalid file name
		if (!\Validator::isValidFileName($file['name']))
		{
			$this->addError($GLOBALS['TL_LANG']['ERR']['filename']);

			return;
		}

		// File was not uploaded
		if (!is_uploaded_file($file['tmp_name']))
		{
			if ($file['error'] == 1 || $file['error'] == 2)
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['filesize'], $maxlength_kb));
				$this->log('File "'.$file['name'].'" exceeds the maximum file size of '.$maxlength_kb, __METHOD__, TL_ERROR);
			}
			elseif ($file['error'] == 3)
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['filepartial'], $file['name']));
				$this->log('File "'.$file['name'].'" was only partially uploaded', __METHOD__, TL_ERROR);
			}
			elseif ($file['error'] > 0)
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['fileerror'], $file['error'], $file['name']));
				$this->log('File "'.$file['name'].'" could not be uploaded (error '.$file['error'].')' , __METHOD__, TL_ERROR);
			}

			unset($_FILES[$this->strName]);

			return;
		}

		// File is too big
		if ($this->maxlength > 0 && $file['size'] > $this->maxlength)
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['filesize'], $maxlength_kb));
			$this->log('File "'.$file['name'].'" exceeds the maximum file size of '.$maxlength_kb, __METHOD__, TL_ERROR);

			unset($_FILES[$this->strName]);

			return;
		}

		$strExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
		$uploadTypes = trimsplit(',', $this->extensions);

		// File type is not allowed
		if (!in_array(strtolower($strExtension), $uploadTypes))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $strExtension));
			$this->log('File type "'.$strExtension.'" is not allowed to be uploaded ('.$file['name'].')', __METHOD__, TL_ERROR);

			unset($_FILES[$this->strName]);

			return;
		}

		if (($arrImageSize = @getimagesize($file['tmp_name'])) != false)
		{
			// Image exceeds maximum image width
			if ($arrImageSize[0] > \Config::get('imageWidth'))
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['filewidth'], $file['name'], \Config::get('imageWidth')));
				$this->log('File "'.$file['name'].'" exceeds the maximum image width of '.\Config::get('imageWidth').' pixels', __METHOD__, TL_ERROR);

				unset($_FILES[$this->strName]);

				return;
			}

			// Image exceeds maximum image height
			if ($arrImageSize[1] > \Config::get('imageHeight'))
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['fileheight'], $file['name'], \Config::get('imageHeight')));
				$this->log('File "'.$file['name'].'" exceeds the maximum image height of '.\Config::get('imageHeight').' pixels', __METHOD__, TL_ERROR);

				unset($_FILES[$this->strName]);

				return;
			}
		}

		// Store file in the session and optionally on the server
		if (!$this->hasErrors())
		{
			$_SESSION['FILES'][$this->strName] = $_FILES[$this->strName];
			$this->log('File "'.$file['name'].'" uploaded successfully', __METHOD__, TL_FILES);

			if ($this->storeFile)
			{
				$intUploadFolder = $this->uploadFolder;

				// Overwrite the upload folder with user's home directory
				if ($this->useHomeDir && FE_USER_LOGGED_IN)
				{
					$this->import('FrontendUser', 'User');

					if ($this->User->assignDir && $this->User->homeDir)
					{
						$intUploadFolder = $this->User->homeDir;
					}
				}

				$objUploadFolder = \FilesModel::findByUuid($intUploadFolder);

				// The upload folder could not be found
				if ($objUploadFolder === null)
				{
					throw new \Exception("Invalid upload folder ID $intUploadFolder");
				}

				$strUploadFolder = $objUploadFolder->path;

				// Store the file if the upload folder exists
				if ($strUploadFolder != '' && is_dir(TL_ROOT . '/' . $strUploadFolder))
				{
					$this->import('Files');

					// Do not overwrite existing files
					if ($this->doNotOverwrite && file_exists(TL_ROOT . '/' . $strUploadFolder . '/' . $file['name']))
					{
						$offset = 1;
						$pathinfo = pathinfo($file['name']);
						$name = $pathinfo['filename'];

						$arrAll = scan(TL_ROOT . '/' . $strUploadFolder);
						$arrFiles = preg_grep('/^' . preg_quote($name, '/') . '.*\.' . preg_quote($pathinfo['extension'], '/') . '/', $arrAll);

						foreach ($arrFiles as $strFile)
						{
							if (preg_match('/__[0-9]+\.' . preg_quote($pathinfo['extension'], '/') . '$/', $strFile))
							{
								$strFile = str_replace('.' . $pathinfo['extension'], '', $strFile);
								$intValue = intval(substr($strFile, (strrpos($strFile, '_') + 1)));

								$offset = max($offset, $intValue);
							}
						}

						$file['name'] = str_replace($name, $name . '__' . ++$offset, $file['name']);
					}

					$this->Files->move_uploaded_file($file['tmp_name'], $strUploadFolder . '/' . $file['name']);
					$this->Files->chmod($strUploadFolder . '/' . $file['name'], \Config::get('defaultFileChmod'));

					// Generate the DB entries
					$strFile = $strUploadFolder . '/' . $file['name'];
					$objFile = \FilesModel::findByPath($strFile);

					// Existing file is being replaced (see #4818)
					if ($objFile !== null)
					{
						$objFile->tstamp = time();
						$objFile->path   = $strFile;
						$objFile->hash   = md5_file(TL_ROOT . '/' . $strFile);
						$objFile->save();
					}
					else
					{
						$objFile = \Dbafs::addResource($strFile);
					}

					// Update the hash of the target folder
					\Dbafs::updateFolderHashes($strUploadFolder);

					// Add the session entry (see #6986)
					$_SESSION['FILES'][$this->strName] = array
					(
						'name'     => $file['name'],
						'type'     => $file['type'],
						'tmp_name' => TL_ROOT . '/' . $strFile,
						'error'    => $file['error'],
						'size'     => $file['size'],
						'uploaded' => true,
						'uuid'     => \String::binToUuid($objFile->uuid)
					);

					// Add a log entry
					$this->log('File "'.$file['name'].'" has been moved to "'.$strUploadFolder.'"', __METHOD__, TL_FILES);
				}
			}
		}

		unset($_FILES[$this->strName]);
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string The widget markup
	 */
	public function generate()
	{
		return sprintf('<input type="file" name="%s" id="ctrl_%s" class="upload%s"%s%s',
						$this->strName,
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						$this->getAttributes(),
						$this->strTagEnding) . $this->addSubmit();
	}
}
