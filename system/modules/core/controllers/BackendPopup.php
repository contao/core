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
 * Class BackendPopup
 *
 * Pop-up file preview (file manager).
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
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

		$this->Template = new \BackendTemplate('be_popup');
		$this->Template->uuid = \String::binToUuid($objModel->uuid); // see #5211

		// Add the file info
		if (is_dir(TL_ROOT . '/' . $this->strFile))
		{
			$objFile = new \Folder($this->strFile, true);
		}
		else
		{
			$objFile = new \File($this->strFile, true);

			// Image
			if ($objFile->isGdImage)
			{
				$this->Template->isImage = true;
				$this->Template->width = $objFile->width;
				$this->Template->height = $objFile->height;
				$this->Template->src = $this->urlEncode($this->strFile);
			}

			$this->Template->href = ampersand(\Environment::get('request'), true) . '&amp;download=1';
			$this->Template->filesize = $this->getReadableSize($objFile->filesize) . ' (' . number_format($objFile->filesize, 0, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']) . ' Byte)';
		}

		$this->Template->icon = $objFile->icon;
		$this->Template->mime = $objFile->mime;
		$this->Template->ctime = \Date::parse(\Config::get('datimFormat'), $objFile->ctime);
		$this->Template->mtime = \Date::parse(\Config::get('datimFormat'), $objFile->mtime);
		$this->Template->atime = \Date::parse(\Config::get('datimFormat'), $objFile->atime);
		$this->Template->path = $this->strFile;

		$this->output();
	}


	/**
	 * Output the template file
	 */
	protected function output()
	{
		$this->Template->theme = \Backend::getTheme();
		$this->Template->base = \Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = specialchars($this->strFile);
		$this->Template->charset = \Config::get('characterSet');
		$this->Template->headline = basename(utf8_convert_encoding($this->strFile, \Config::get('characterSet')));
		$this->Template->label_uuid = $GLOBALS['TL_LANG']['MSC']['fileUuid'];
		$this->Template->label_imagesize = $GLOBALS['TL_LANG']['MSC']['fileImageSize'];
		$this->Template->label_filesize = $GLOBALS['TL_LANG']['MSC']['fileSize'];
		$this->Template->label_ctime = $GLOBALS['TL_LANG']['MSC']['fileCreated'];
		$this->Template->label_mtime = $GLOBALS['TL_LANG']['MSC']['fileModified'];
		$this->Template->label_atime = $GLOBALS['TL_LANG']['MSC']['fileAccessed'];
		$this->Template->label_path = $GLOBALS['TL_LANG']['MSC']['filePath'];
		$this->Template->download = specialchars($GLOBALS['TL_LANG']['MSC']['fileDownload']);

		\Config::set('debugMode', false);
		$this->Template->output();
	}
}
