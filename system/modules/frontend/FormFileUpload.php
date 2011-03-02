<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class FormFileUpload
 *
 * File upload field.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class FormFileUpload extends Widget implements uploadable
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
				$this->arrAttributes[$strKey] = ($varValue > 0) ? $varValue : '';
				break;

			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Validate input and set value
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

		$pathinfo = pathinfo($file['name']);
		$uploadTypes = trimsplit(',', $this->extensions);

		// File type is not allowed
		if (!in_array(strtolower($pathinfo['extension']), $uploadTypes))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $pathinfo['extension']));
			$this->log('File type "'.$pathinfo['extension'].'" is not allowed to be uploaded ('.$file['name'].')', 'FormFileUpload validate()', TL_ERROR);

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
					$this->Files->chmod($strUploadFolder . '/' . $file['name'], 0644);

					$_SESSION['FILES'][$this->strName] = array
					(
						'name' => $file['name'],
						'type' => $file['type'],
						'tmp_name' => TL_ROOT . '/' . $strUploadFolder . '/' . $file['name'],
						'error' => $file['error'],
						'size' => $file['size'],
						'uploaded' => true
					);

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
		return sprintf('<input type="file" name="%s" id="ctrl_%s" class="upload%s"%s />',
						$this->strName,
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->getAttributes()) . $this->addSubmit();
	}
}

?>