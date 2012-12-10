<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class FormFileUpload
 *
 * File upload field.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://contao.org>
 * @package    Core
 */
class FormFileUpload extends \Widget implements \uploadable
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_widget';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
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

			case 'uploadFolder':
				if (version_compare(VERSION, '3.0', '>='))
				{
					$this->loadDataContainer('tl_files');

					$this->isDatabaseAssisted = ($GLOBALS['TL_DCA']['tl_files']['config']['databaseAssisted']) ? true : false;

					if ($this->isDatabaseAssisted && is_numeric($varValue) && !preg_match('/^'.preg_quote($GLOBALS['TL_CONFIG']['uploadPath'], '/').'/', $varValue))
					{
						$varValue = \FilesModel::findByPk($varValue)->path;
					}
				}
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

		// Romanize the filename
		$file['name'] = utf8_romanize($file['name']);

		// File was not uploaded
		if (!is_uploaded_file($file['tmp_name']))
		{
			if (in_array($file['error'], array(1, 2)))
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['filesize'], $maxlength_kb));
				$this->log('File "'.$file['name'].'" exceeds the maximum file size of '.$maxlength_kb, 'FormFileUpload validate()', TL_ERROR);
			}

			if ($file['error'] == 3)
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['filepartial'], $file['name']));
				$this->log('File "'.$file['name'].'" was only partially uploaded', 'FormFileUpload validate()', TL_ERROR);
			}

			unset($_FILES[$this->strName]);
			return;
		}

		// File is too big
		if ($this->maxlength > 0 && $file['size'] > $this->maxlength)
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['filesize'], $maxlength_kb));
			$this->log('File "'.$file['name'].'" exceeds the maximum file size of '.$maxlength_kb, 'FormFileUpload validate()', TL_ERROR);

			unset($_FILES[$this->strName]);
			return;
		}

		$strExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
		$uploadTypes = trimsplit(',', $this->extensions);

		// File type is not allowed
		if (!in_array(strtolower($strExtension), $uploadTypes))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $strExtension));
			$this->log('File type "'.$strExtension.'" is not allowed to be uploaded ('.$file['name'].')', 'FormFileUpload validate()', TL_ERROR);

			unset($_FILES[$this->strName]);
			return;
		}

		if (($arrImageSize = @getimagesize($file['tmp_name'])) != false)
		{
			// Image exceeds maximum image width
			if ($arrImageSize[0] > $GLOBALS['TL_CONFIG']['imageWidth'])
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['filewidth'], $file['name'], $GLOBALS['TL_CONFIG']['imageWidth']));
				$this->log('File "'.$file['name'].'" exceeds the maximum image width of '.$GLOBALS['TL_CONFIG']['imageWidth'].' pixels', 'FormFileUpload validate()', TL_ERROR);

				unset($_FILES[$this->strName]);
				return;
			}

			// Image exceeds maximum image height
			if ($arrImageSize[1] > $GLOBALS['TL_CONFIG']['imageHeight'])
			{
				$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['fileheight'], $file['name'], $GLOBALS['TL_CONFIG']['imageHeight']));
				$this->log('File "'.$file['name'].'" exceeds the maximum image height of '.$GLOBALS['TL_CONFIG']['imageHeight'].' pixels', 'FormFileUpload validate()', TL_ERROR);

				unset($_FILES[$this->strName]);
				return;
			}
		}

		// Store file in the session and optionally on the server
		if (!$this->hasErrors())
		{
			$_SESSION['FILES'][$this->strName] = $_FILES[$this->strName];
			$this->log('File "'.$file['name'].'" uploaded successfully', 'FormFileUpload validate()', TL_FILES);

			if ($this->storeFile)
			{
				$strUploadFolder = $this->uploadFolder;

				// Overwrite upload folder with user home directory
				if ($this->useHomeDir && FE_USER_LOGGED_IN)
				{
					$this->import('FrontendUser', 'User');

					if ($this->User->assignDir && $this->User->homeDir && is_dir(TL_ROOT . '/' . $this->User->homeDir))
					{
						$strUploadFolder = $this->User->homeDir;
					}
				}

				// Store the file if the upload folder exists
				if (strlen($strUploadFolder) && is_dir(TL_ROOT . '/' . $strUploadFolder))
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
					$this->Files->chmod($strUploadFolder . '/' . $file['name'], $GLOBALS['TL_CONFIG']['defaultFileChmod']);

   					// Generate the DB entry
					$pid = null;
					$id = null;

					if ($this->isDatabaseAssisted)
					{
						// Get the parent ID
						if ($strUploadFolder == $GLOBALS['TL_CONFIG']['uploadPath'])
						{
							$pid = 0;
						}
						else
						{
							$objModel = \FilesModel::findByPath($strUploadFolder);
							$pid = $objModel->id;
						}

						$objFile = \FilesModel::findByPath($strUploadFolder . '/' . $file['name']);

						// Existing file is being replaced
						if ($objFile !== null)
						{
							$objFile->tstamp = time();
							$objFile->path   = $strUploadFolder . '/' . $file['name'];
							$objFile->hash   = md5_file(TL_ROOT . '/' . $strUploadFolder . '/' . $file['name']);
							$objFile->save();
							$id = $objFile->id;
						}
						else
						{
							$objFile = new \File($strUploadFolder . '/' . $file['name']);

							$objNew = new \FilesModel();
							$objNew->pid       = $pid;
							$objNew->tstamp    = time();
							$objNew->type      = 'file';
							$objNew->path      = $strUploadFolder . '/' . $file['name'];
							$objNew->extension = $objFile->extension;
							$objNew->hash      = md5_file(TL_ROOT . '/' . $strUploadFolder . '/' . $file['name']);
							$objNew->name      = $objFile->basename;
							$objNew->save();
							$id = $objNew->id;
						}

					}

					$_SESSION['FILES'][$this->strName] = array
					(
						'name' => $file['name'],
						'type' => $file['type'],
						'tmp_name' => TL_ROOT . '/' . $strUploadFolder . '/' . $file['name'],
						'error' => $file['error'],
						'size' => $file['size'],
						'uploaded' => true
					);

 					// Add additional info when using database assisted file manager
					if ($pid !== null)					{
						$_SESSION['FILES'][$this->strName]['pid'] = $pid;
					}
					if ($id !== null)
					{
						$_SESSION['FILES'][$this->strName]['id'] = $id;
					}

					$this->log('File "'.$file['name'].'" has been moved to "'.$strUploadFolder.'"', 'FormFileUpload validate()', TL_FILES);
				}
			}
		}

		unset($_FILES[$this->strName]);
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		return sprintf('<input type="file" name="%s" id="ctrl_%s" class="upload%s"%s%s',
						$this->strName,
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->getAttributes(),
						$this->strTagEnding) . $this->addSubmit();
	}
}
