<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class File
 *
 * Provide methods to handle files.
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Library
 */
class File extends System
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
	 * Check whether a file exists
	 * @param string
	 * @throws Expcetion
	 */
	public function __construct($strFile)
	{
		$this->import('Files');

		// Handle open_basedir restrictions
		if ($strFile == '.')
		{
			$strFile = '';
		}

		// Check whether it is a file
		if (is_dir(TL_ROOT . '/' . $strFile))
		{
			throw new Exception(sprintf('Directory "%s" is not a file', $strFile));
		}

		$this->strFile = $strFile;

		// Create file if it does not exist
		if (!file_exists(TL_ROOT . '/' . $this->strFile))
		{
			// Handle open_basedir restrictions
			if (($strFolder = dirname($this->strFile)) == '.')
			{
				$strFolder = '';
			}

			// Create folder
			if (!is_dir(TL_ROOT . '/' . $strFolder))
			{
				new Folder($strFolder);
			}

			// Open file
			if (($this->resFile = $this->Files->fopen($this->strFile, 'wb')) == false)
			{
				throw new Exception(sprintf('Cannot create file "%s"', $this->strFile));
			}
		}
	}


	/**
	 * Close the file handle if it has not been done yet
	 * @param string
	 * @throws Exception
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
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		if (!isset($this->arrCache[$strKey]))
		{
			switch ($strKey)
			{
				case 'size':
				case 'filesize':
					$this->arrCache[$strKey] = filesize(TL_ROOT . '/' . $this->strFile);
					break;

				case 'dirname':
				case 'basename':
					if (!isset($this->arrPathinfo[$strKey]))
					{
						$this->arrPathinfo = pathinfo(TL_ROOT . '/' . $this->strFile);
					}
					$this->arrCache[$strKey] = $this->arrPathinfo[$strKey];
					break;

				case 'extension':
					if (!isset($this->arrPathinfo['extension']))
					{
						$this->arrPathinfo = pathinfo(TL_ROOT . '/' . $this->strFile);
					}
					$this->arrCache['extension'] = strtolower($this->arrPathinfo['extension']);
					break;

				case 'filename':
					$this->arrCache['filename'] = preg_replace('/\.' . $this->extension . '$/i', '', $this->basename);
					break;

				case 'mime':
					$this->arrCache['mime'] = $this->getMimeType();
					break;

				case 'ctime':
					$this->arrCache['ctime'] = filectime(TL_ROOT . '/' . $this->strFile);
					break;

				case 'mtime':
					$this->arrCache['mtime'] = filemtime(TL_ROOT . '/' . $this->strFile);
					break;

				case 'atime':
					$this->arrCache['atime'] = fileatime(TL_ROOT . '/' . $this->strFile);
					break;

				case 'icon':
					$this->arrCache['icon'] = $this->getIcon();
					break;

				case 'value':
					$this->arrCache['value'] = $this->strFile;
					break;

				case 'width':
					if (count($this->arrImageSize) < 1)
					{
						$this->arrImageSize = @getimagesize(TL_ROOT . '/' . $this->strFile);
					}
					$this->arrCache['width'] = $this->arrImageSize[0];
					break;

				case 'height':
					if (count($this->arrImageSize) < 1)
					{
						$this->arrImageSize = @getimagesize(TL_ROOT . '/' . $this->strFile);
					}
					$this->arrCache['height'] = $this->arrImageSize[1];
					break;

				case 'isGdImage':
					$this->arrCache['isGdImage'] = in_array($this->extension, array('gif', 'jpg', 'jpeg', 'png'));
					break;

				case 'handle':
					if (!is_resource($this->resFile))
					{
						$this->resFile = fopen(TL_ROOT . '/' . $this->strFile, 'rb');
					}
					return $this->resFile;
					break;

				default:
					return null;
					break;
			}
		}

		return $this->arrCache[$strKey];
	}


	/**
	 * Write data to the file
	 * @param mixed
	 * @return boolean
	 */
	public function write($varData)
	{
		return $this->fputs($varData, 'wb');
	}


	/**
	 * Append data to the file
	 * @param mixed
	 * @return boolean
	 */
	public function append($varData)
	{
		return $this->fputs($varData . "\n", 'ab');
	}


	/**
	 * Delete the file
	 * @return boolean
	 */
	public function delete()
	{
		return $this->Files->delete($this->strFile);
	}


	/**
	 * Close the file handle
	 * @return boolean
	 */
	public function close()
	{
		return $this->Files->fclose($this->resFile);
	}


	/**
	 * Return file content as string
	 * @return string
	 */
	public function getContent()
	{
		return file_get_contents(TL_ROOT . '/' . $this->strFile);
	}


	/**
	 * Return file content as array
	 * @return string
	 */
	public function getContentAsArray()
	{
		return array_map('rtrim', file(TL_ROOT . '/' . $this->strFile));
	}


	/**
	 * Write data to a file
	 * @param mixed
	 * @param string
	 * @return boolean
	 */
	protected function fputs($varData, $strMode)
	{
		if (!is_resource($this->resFile))
		{
			if (($this->resFile = $this->Files->fopen($this->strFile, $strMode)) == false)
			{
				return false;
			}
		}

		fputs($this->resFile, $varData);
		return true;
	}


	/**
	 * Get the mime type of a file based on its extension
	 * @return string
	 */
	protected function getMimeType()
	{
		$arrMimeTypes = array
		(
			'xl'    => 'application/excel',
			'hqx'   => 'application/mac-binhex40',
			'cpt'   => 'application/mac-compactpro',
			'doc'   => 'application/msword',
			'word'  => 'application/msword',
			'bin'   => 'application/macbinary',
			'dms'   => 'application/octet-stream',
			'lha'   => 'application/octet-stream',
			'lzh'   => 'application/octet-stream',
			'exe'   => 'application/octet-stream',
			'class' => 'application/octet-stream',
			'psd'   => 'application/x-photoshop',
			'so'    => 'application/octet-stream',
			'sea'   => 'application/octet-stream',
			'dll'   => 'application/octet-stream',
			'oda'   => 'application/oda',
			'pdf'   => 'application/pdf',
			'ai'    => 'application/postscript',
			'eps'   => 'application/postscript',
			'ps'    => 'application/postscript',
			'smi'   => 'application/smil',
			'smil'  => 'application/smil',
			'mif'   => 'application/vnd.mif',
			'xls'   => 'application/excel',
			'ppt'   => 'application/powerpoint',
			'wbxml' => 'application/wbxml',
			'wmlc'  => 'application/wmlc',
			'dmg'   => 'application/x-apple-diskimage',
			'dcr'   => 'application/x-director',
			'dir'   => 'application/x-director',
			'dxr'   => 'application/x-director',
			'dvi'   => 'application/x-dvi',
			'gtar'  => 'application/x-gtar',
			'php'   => 'application/x-httpd-php',
			'php3'  => 'application/x-httpd-php',
			'php4'  => 'application/x-httpd-php',
			'php5'  => 'application/x-httpd-php',
			'phtml' => 'application/x-httpd-php',
			'phps'  => 'application/x-httpd-php-source',
			'js'    => 'application/x-javascript',
			'swf'   => 'application/x-shockwave-flash',
			'sit'   => 'application/x-stuffit',
			'tar'   => 'application/x-tar',
			'tgz'   => 'application/x-tar',
			'xhtml' => 'application/xhtml+xml',
			'xht'   => 'application/xhtml+xml',
			'zip'   => 'application/zip',
			'mid'   => 'audio/midi',
			'midi'  => 'audio/midi',
			'mpga'  => 'audio/mpeg',
			'mp2'   => 'audio/mpeg',
			'mp3'   => 'audio/mpeg',
			'wav'   => 'audio/x-wav',
			'aif'   => 'audio/x-aiff',
			'aiff'  => 'audio/x-aiff',
			'aifc'  => 'audio/x-aiff',
			'ram'   => 'audio/x-pn-realaudio',
			'rm'    => 'audio/x-pn-realaudio',
			'rpm'   => 'audio/x-pn-realaudio-plugin',
			'ra'    => 'audio/x-realaudio',
			'bmp'   => 'image/bmp',
			'gif'   => 'image/gif',
			'jpeg'  => 'image/jpeg',
			'jpg'   => 'image/jpeg',
			'jpe'   => 'image/jpeg',
			'png'   => 'image/png',
			'tiff'  => 'image/tiff',
			'tif'   => 'image/tiff',
			'eml'   => 'message/rfc822',
			'css'   => 'text/css',
			'html'  => 'text/html',
			'htm'   => 'text/html',
			'shtml' => 'text/html',
			'txt'   => 'text/plain',
			'text'  => 'text/plain',
			'log'   => 'text/plain',
			'rtx'   => 'text/richtext',
			'rtf'   => 'text/rtf',
			'xml'   => 'text/xml',
			'xsl'   => 'text/xml',
			'mpeg'  => 'video/mpeg',
			'mpg'   => 'video/mpeg',
			'mpe'   => 'video/mpeg',
			'qt'    => 'video/quicktime',
			'mov'   => 'video/quicktime',
			'avi'   => 'video/x-msvideo',
			'movie' => 'video/x-sgi-movie',
			'rv'    => 'video/vnd.rn-realvideo'
		);

		$strMime = 'application/octet-stream';

		if (isset($arrMimeTypes[$this->extension]))
		{
			$strMime = $arrMimeTypes[$this->extension];
		}

		return $strMime;
	}


	/**
	 * Return an icon depending on the file type
	 * @return string
	 */
	protected function getIcon()
	{
		switch ($this->extension)
		{
			// HTML
			case 'html':
			case 'htm':
				return 'iconHTML.gif';
				break;

			// PHP
			case 'php':
			case 'php3':
			case 'php4':
			case 'php5':
			case 'inc':
				return 'iconPHP.gif';
				break;

			// JavaScript
			case 'js':
				return 'iconJS.gif';
				break;

			// Style sheets
			case 'css':
				return 'iconCSS.gif';
				break;

			// Flash
			case 'swf':
			case 'fla':
				return 'iconSWF.gif';
				break;

			// GIF
			case 'gif':
				return 'iconGIF.gif';
				break;

			// JPG
			case 'jpg':
			case 'jpeg':
				return 'iconJPG.gif';
				break;

			// TIF
			case 'png':
			case 'tif':
			case 'tiff':
				return 'iconTIF.gif';
				break;

			// Bitmap
			case 'bmp':
				return 'iconBMP.gif';
				break;

			// PDF
			case 'pdf':
				return 'iconPDF.gif';
				break;

			// Archive
			case 'zip':
			case 'tar':
			case 'rar':
				return 'iconRAR.gif';
				break;

			// ASP
			case 'jsp':
			case 'asp':
				return 'iconJSP.gif';
				break;

			// Audio
			case 'mp3':
			case 'wav':
			case 'wma':
				return 'iconAUDIO.gif';
				break;

			// Video
			case 'mov':
			case 'wmv':
			case 'avi':
			case 'ram':
			case 'rm':
				return 'iconVIDEO.gif';
				break;

			// Office
			case 'doc':
			case 'xls':
			case 'ppt':
			case 'pps':
			case 'odt':
			case 'ods':
			case 'odp':
				return 'iconOFFICE.gif';
				break;

			default:
				return 'iconPLAIN.gif';
				break;
		}
	}
}

?>