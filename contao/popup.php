<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 */


/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once '../system/initialize.php';


/**
 * Class Popup
 *
 * Pop-up file preview (file manager).
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class Popup extends Backend
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
		$this->loadLanguageFile('default');

		$strFile = $this->Input->get('src', true);
		$strFile = base64_decode($strFile);
		$strFile = preg_replace('@^/+@', '', rawurldecode($strFile));

		$this->strFile = $strFile; 
	}


	/**
	 * Run the controller and parse the template
	 * @return void
	 */
	public function run()
	{
		// Make sure there are no attempts to hack the file system
		if (preg_match('@^\.+@i', $this->strFile) || preg_match('@\.+/@i', $this->strFile) || preg_match('@(://)+@i', $this->strFile))
		{
			die('Invalid file name');
		}

		// Limit preview to the files directory
		if (!preg_match('@^' . preg_quote($GLOBALS['TL_CONFIG']['uploadPath'], '@') . '@i', $this->strFile))
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

		// Open download dialogue
		if ($this->Input->get('download') && $this->strFile)
		{
			$objFile = new File($this->strFile);

			header('Content-Type: ' . $objFile->mime);
			header('Content-Transfer-Encoding: binary');
			header('Content-Disposition: attachment; filename="' . $objFile->basename . '"');
			header('Content-Length: ' . $objFile->filesize);
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Expires: 0');

			$resFile = fopen(TL_ROOT . '/' . $this->strFile, 'rb');
			fpassthru($resFile);
			fclose($resFile);
			ob_flush(); // see #3595

			$this->redirect(str_replace('&download=1', '', $this->Environment->request));
		}

		$this->Template = new BackendTemplate('be_popup');
		$objFile = new File($this->strFile);

		// Add file info
		$this->Template->icon = $objFile->icon;
		$this->Template->mime = $objFile->mime;
		$this->Template->ctime = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objFile->ctime);
		$this->Template->mtime = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objFile->mtime);
		$this->Template->atime = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objFile->atime);
		$this->Template->filesize = $this->getReadableSize($objFile->filesize) . ' (' . number_format($objFile->filesize, 0, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']) . ' Byte)';
		$this->Template->path = $this->strFile;

		// Image
		if ($objFile->isGdImage)
		{
			$this->Template->isImage = true;
			$this->Template->width = $objFile->width;
			$this->Template->height = $objFile->height;
			$this->Template->src = $this->urlEncode($this->strFile);
		}

		$this->output();
	}


	/**
	 * Output the template file
	 */
	protected function output()
	{
		$this->Template->theme = $this->getTheme();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->href = ampersand($this->Environment->request, true) . '&amp;download=1';
		$this->Template->headline = basename(utf8_convert_encoding($this->strFile, $GLOBALS['TL_CONFIG']['characterSet']));
		$this->Template->label_imagesize = $GLOBALS['TL_LANG']['MSC']['fileImageSize'];
		$this->Template->label_filesize = $GLOBALS['TL_LANG']['MSC']['fileSize'];
		$this->Template->label_ctime = $GLOBALS['TL_LANG']['MSC']['fileCreated'];
		$this->Template->label_mtime = $GLOBALS['TL_LANG']['MSC']['fileModified'];
		$this->Template->label_atime = $GLOBALS['TL_LANG']['MSC']['fileAccessed'];
		$this->Template->label_atime = $GLOBALS['TL_LANG']['MSC']['fileAccessed'];
		$this->Template->label_path = $GLOBALS['TL_LANG']['MSC']['filePath'];
		$this->Template->download = specialchars($GLOBALS['TL_LANG']['MSC']['fileDownload']);
		$this->Template->downloadTitle = specialchars($GLOBALS['TL_LANG']['MSC']['fileDownloadTitle']);

		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objPopup = new Popup();
$objPopup->run();
