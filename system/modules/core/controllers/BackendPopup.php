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
 * Pop-up file preview (file manager).
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class BackendPopup extends \Backend
{

	/**
	 * File
	 * @var string
	 */
	protected $strFile;


	/**
	 * Initialize the controller
	 *
	 * 1. Import the user
	 * 2. Call the parent constructor
	 * 3. Authenticate the user
	 * 4. Load the language files
	 * DO NOT CHANGE THIS ORDER!
	 */
	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct();

		$this->User->authenticate();
		\System::loadLanguageFile('default');

		$strFile = \Input::get('src', true);
		$strFile = base64_decode($strFile);
		$strFile = preg_replace('@^/+@', '', rawurldecode($strFile));

		$this->strFile = $strFile;
	}


	/**
	 * Run the controller and parse the template
	 */
	public function run()
	{
		if ($this->strFile == '')
		{
			die('No file given');
		}

		// Make sure there are no attempts to hack the file system
		if (preg_match('@^\.+@i', $this->strFile) || preg_match('@\.+/@i', $this->strFile) || preg_match('@(://)+@i', $this->strFile))
		{
			die('Invalid file name');
		}

		// Limit preview to the files directory
		if (!preg_match('@^' . preg_quote(\Config::get('uploadPath'), '@') . '@i', $this->strFile))
		{
			die('Invalid path');
		}

		// Check whether the file exists
		if (!file_exists(TL_ROOT . '/' . $this->strFile))
		{
			die('File not found');
		}

		// Check whether the file is mounted (thanks to Marko Cupic)
		if (!$this->User->hasAccess($this->strFile, 'filemounts'))
		{
			die('Permission denied');
		}

		// Open the download dialogue
		if (\Input::get('download'))
		{
			$objFile = new \File($this->strFile, true);
			$objFile->sendToBrowser();
		}

		// Add the resource (see #6880)
		if (($objModel = \FilesModel::findByPath($this->strFile)) === null)
		{
			$objModel = \Dbafs::addResource($this->strFile);
		}

		/** @var \BackendTemplate|object $objTemplate */
		$objTemplate = new \BackendTemplate('be_popup');
		$objTemplate->uuid = \String::binToUuid($objModel->uuid); // see #5211

		// Add the file info
		if (is_dir(TL_ROOT . '/' . $this->strFile))
		{
			$objFile = new \Folder($this->strFile, true);
			$objTemplate->filesize = $this->getReadableSize($objFile->size) . ' (' . number_format($objFile->size, 0, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']) . ' Byte)';
		}
		else
		{
			$objFile = new \File($this->strFile, true);

			// Image
			if ($objFile->isImage)
			{
				$objTemplate->isImage = true;
				$objTemplate->width = $objFile->width;
				$objTemplate->height = $objFile->height;
				$objTemplate->src = $this->urlEncode($this->strFile);
			}

			$objTemplate->href = ampersand(\Environment::get('request'), true) . '&amp;download=1';
			$objTemplate->filesize = $this->getReadableSize($objFile->filesize) . ' (' . number_format($objFile->filesize, 0, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']) . ' Byte)';
		}

		$objTemplate->icon = $objFile->icon;
		$objTemplate->mime = $objFile->mime;
		$objTemplate->ctime = \Date::parse(\Config::get('datimFormat'), $objFile->ctime);
		$objTemplate->mtime = \Date::parse(\Config::get('datimFormat'), $objFile->mtime);
		$objTemplate->atime = \Date::parse(\Config::get('datimFormat'), $objFile->atime);
		$objTemplate->path = specialchars($this->strFile);
		$objTemplate->theme = \Backend::getTheme();
		$objTemplate->base = \Environment::get('base');
		$objTemplate->language = $GLOBALS['TL_LANGUAGE'];
		$objTemplate->title = specialchars($this->strFile);
		$objTemplate->charset = \Config::get('characterSet');
		$objTemplate->headline = basename(utf8_convert_encoding($this->strFile, \Config::get('characterSet')));
		$objTemplate->label_uuid = $GLOBALS['TL_LANG']['MSC']['fileUuid'];
		$objTemplate->label_imagesize = $GLOBALS['TL_LANG']['MSC']['fileImageSize'];
		$objTemplate->label_filesize = $GLOBALS['TL_LANG']['MSC']['fileSize'];
		$objTemplate->label_ctime = $GLOBALS['TL_LANG']['MSC']['fileCreated'];
		$objTemplate->label_mtime = $GLOBALS['TL_LANG']['MSC']['fileModified'];
		$objTemplate->label_atime = $GLOBALS['TL_LANG']['MSC']['fileAccessed'];
		$objTemplate->label_path = $GLOBALS['TL_LANG']['MSC']['filePath'];
		$objTemplate->download = specialchars($GLOBALS['TL_LANG']['MSC']['fileDownload']);

		\Config::set('debugMode', false);
		$objTemplate->output();
	}
}
