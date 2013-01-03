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
 * Class File
 *
 * Provide methods to handle files.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
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

		$this->import('Files');
		$this->import('Cache');

		// Create the file if it does not exist
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
		$strCacheKey = __METHOD__ . '-' . $this->strFile . '-' . $strKey;

		if (!isset($this->Cache->$strCacheKey))
		{
			switch ($strKey)
			{
				case 'size':
				case 'filesize':
					$this->Cache->$strCacheKey = filesize(TL_ROOT . '/' . $this->strFile);
					break;

				case 'dirname':
				case 'basename':
					if (!isset($this->arrPathinfo[$strKey]))
					{
						$this->arrPathinfo = pathinfo(TL_ROOT . '/' . $this->strFile);
					}
					$this->Cache->$strCacheKey = $this->arrPathinfo[$strKey];
					break;

				case 'extension':
					if (!isset($this->arrPathinfo['extension']))
					{
						$this->arrPathinfo = pathinfo(TL_ROOT . '/' . $this->strFile);
					}
					$this->Cache->$strCacheKey = strtolower($this->arrPathinfo['extension']);
					break;

				case 'filename':
					$this->Cache->$strCacheKey = basename($this->basename, '.'.$this->extension);
					break;

				case 'mime':
					$this->Cache->$strCacheKey = $this->getMimeType();
					break;

				case 'ctime':
					$this->Cache->$strCacheKey = filectime(TL_ROOT . '/' . $this->strFile);
					break;

				case 'mtime':
					$this->Cache->$strCacheKey = filemtime(TL_ROOT . '/' . $this->strFile);
					break;

				case 'atime':
					$this->Cache->$strCacheKey = fileatime(TL_ROOT . '/' . $this->strFile);
					break;

				case 'icon':
					$this->Cache->$strCacheKey = $this->getIcon();
					break;

				case 'value':
					$this->Cache->$strCacheKey = $this->strFile;
					break;

				case 'width':
					if (empty($this->arrImageSize))
					{
						$this->arrImageSize = @getimagesize(TL_ROOT . '/' . $this->strFile);
					}
					$this->Cache->$strCacheKey = $this->arrImageSize[0];
					break;

				case 'height':
					if (empty($this->arrImageSize))
					{
						$this->arrImageSize = @getimagesize(TL_ROOT . '/' . $this->strFile);
					}
					$this->Cache->$strCacheKey = $this->arrImageSize[1];
					break;

				case 'isGdImage':
					$this->Cache->$strCacheKey = in_array($this->extension, array('gif', 'jpg', 'jpeg', 'png'));
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

		return $this->Cache->$strCacheKey;
	}


	/**
	 * Truncate the file
	 * @return boolean
	 */
	public function truncate()
	{
		return $this->write('');
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
	 * @param string
	 * @return boolean
	 */
	public function append($varData, $strLine="\n")
	{
		return $this->fputs($varData . $strLine, 'ab');
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
	 * Set the file permissions
	 * @param string
	 * @return boolean
	 */
	public function chmod($strChmod)
	{
		return $this->Files->chmod($this->strFile, $strChmod);
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
	 * Rename the file
	 * @param string
	 * @return boolean
	 */
	public function renameTo($strNewName)
	{
		$return = $this->Files->rename($this->strFile, $strNewName);

		if ($return)
		{
			$this->strFile = $strNewName;
			$this->arrPathinfo = array();
		}

		return $return;
	}


	/**
	 * Copy the file
	 * @param string
	 * @return boolean
	 */
	public function copyTo($strNewName)
	{
		return $this->Files->copy($this->strFile, $strNewName);
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
	 * Return the mime type and icon of a file based on its extension
	 * @return array
	 */
	protected function getMimeInfo()
	{
		$arrMimeTypes = array
		(
			// Application files
			'xl'    => array('application/excel', 'iconOFFICE.gif'),
			'xls'   => array('application/excel', 'iconOFFICE.gif'),
			'hqx'   => array('application/mac-binhex40', 'iconPLAIN.gif'),
			'cpt'   => array('application/mac-compactpro', 'iconPLAIN.gif'),
			'bin'   => array('application/macbinary', 'iconPLAIN.gif'),
			'doc'   => array('application/msword', 'iconOFFICE.gif'),
			'word'  => array('application/msword', 'iconOFFICE.gif'),
			'cto'   => array('application/octet-stream', 'iconCTO.gif'),
			'dms'   => array('application/octet-stream', 'iconPLAIN.gif'),
			'lha'   => array('application/octet-stream', 'iconPLAIN.gif'),
			'lzh'   => array('application/octet-stream', 'iconPLAIN.gif'),
			'exe'   => array('application/octet-stream', 'iconPLAIN.gif'),
			'class' => array('application/octet-stream', 'iconPLAIN.gif'),
			'so'    => array('application/octet-stream', 'iconPLAIN.gif'),
			'sea'   => array('application/octet-stream', 'iconPLAIN.gif'),
			'dll'   => array('application/octet-stream', 'iconPLAIN.gif'),
			'oda'   => array('application/oda', 'iconPLAIN.gif'),
			'pdf'   => array('application/pdf', 'iconPDF.gif'),
			'ai'    => array('application/postscript', 'iconPLAIN.gif'),
			'eps'   => array('application/postscript', 'iconPLAIN.gif'),
			'ps'    => array('application/postscript', 'iconPLAIN.gif'),
			'pps'   => array('application/powerpoint', 'iconOFFICE.gif'),
			'ppt'   => array('application/powerpoint', 'iconOFFICE.gif'),
			'smi'   => array('application/smil', 'iconPLAIN.gif'),
			'smil'  => array('application/smil', 'iconPLAIN.gif'),
			'mif'   => array('application/vnd.mif', 'iconPLAIN.gif'),
			'odc'   => array('application/vnd.oasis.opendocument.chart', 'iconOFFICE.gif'),
			'odf'   => array('application/vnd.oasis.opendocument.formula', 'iconOFFICE.gif'),
			'odg'   => array('application/vnd.oasis.opendocument.graphics', 'iconOFFICE.gif'),
			'odi'   => array('application/vnd.oasis.opendocument.image', 'iconOFFICE.gif'),
			'odp'   => array('application/vnd.oasis.opendocument.presentation', 'iconOFFICE.gif'),
			'ods'   => array('application/vnd.oasis.opendocument.spreadsheet', 'iconOFFICE.gif'),
			'odt'   => array('application/vnd.oasis.opendocument.text', 'iconOFFICE.gif'),
			'wbxml' => array('application/wbxml', 'iconPLAIN.gif'),
			'wmlc'  => array('application/wmlc', 'iconPLAIN.gif'),
			'dmg'   => array('application/x-apple-diskimage', 'iconRAR.gif'),
			'dcr'   => array('application/x-director', 'iconPLAIN.gif'),
			'dir'   => array('application/x-director', 'iconPLAIN.gif'),
			'dxr'   => array('application/x-director', 'iconPLAIN.gif'),
			'dvi'   => array('application/x-dvi', 'iconPLAIN.gif'),
			'gtar'  => array('application/x-gtar', 'iconRAR.gif'),
			'inc'   => array('application/x-httpd-php', 'iconPHP.gif'),
			'php'   => array('application/x-httpd-php', 'iconPHP.gif'),
			'php3'  => array('application/x-httpd-php', 'iconPHP.gif'),
			'php4'  => array('application/x-httpd-php', 'iconPHP.gif'),
			'php5'  => array('application/x-httpd-php', 'iconPHP.gif'),
			'phtml' => array('application/x-httpd-php', 'iconPHP.gif'),
			'phps'  => array('application/x-httpd-php-source', 'iconPHP.gif'),
			'js'    => array('application/x-javascript', 'iconJS.gif'),
			'psd'   => array('application/x-photoshop', 'iconPLAIN.gif'),
			'rar'   => array('application/x-rar', 'iconRAR.gif'),
			'fla'   => array('application/x-shockwave-flash', 'iconSWF.gif'),
			'swf'   => array('application/x-shockwave-flash', 'iconSWF.gif'),
			'sit'   => array('application/x-stuffit', 'iconRAR.gif'),
			'tar'   => array('application/x-tar', 'iconRAR.gif'),
			'tgz'   => array('application/x-tar', 'iconRAR.gif'),
			'xhtml' => array('application/xhtml+xml', 'iconPLAIN.gif'),
			'xht'   => array('application/xhtml+xml', 'iconPLAIN.gif'),
			'zip'   => array('application/zip', 'iconRAR.gif'),

			// Audio files
			'mid'   => array('audio/midi', 'iconAUDIO.gif'),
			'midi'  => array('audio/midi', 'iconAUDIO.gif'),
			'mpga'  => array('audio/mpeg', 'iconAUDIO.gif'),
			'mp2'   => array('audio/mpeg', 'iconAUDIO.gif'),
			'mp3'   => array('audio/mpeg', 'iconAUDIO.gif'),
			'aif'   => array('audio/x-aiff', 'iconAUDIO.gif'),
			'aiff'  => array('audio/x-aiff', 'iconAUDIO.gif'),
			'aifc'  => array('audio/x-aiff', 'iconAUDIO.gif'),
			'ram'   => array('audio/x-pn-realaudio', 'iconAUDIO.gif'),
			'rm'    => array('audio/x-pn-realaudio', 'iconAUDIO.gif'),
			'rpm'   => array('audio/x-pn-realaudio-plugin', 'iconAUDIO.gif'),
			'ra'    => array('audio/x-realaudio', 'iconAUDIO.gif'),
			'wav'   => array('audio/x-wav', 'iconAUDIO.gif'),

			// Images
			'bmp'   => array('image/bmp', 'iconBMP.gif'),
			'gif'   => array('image/gif', 'iconGIF.gif'),
			'jpeg'  => array('image/jpeg', 'iconJPG.gif'),
			'jpg'   => array('image/jpeg', 'iconJPG.gif'),
			'jpe'   => array('image/jpeg', 'iconJPG.gif'),
			'png'   => array('image/png', 'iconTIF.gif'),
			'tiff'  => array('image/tiff', 'iconTIF.gif'),
			'tif'   => array('image/tiff', 'iconTIF.gif'),

			// Mailbox files
			'eml'   => array('message/rfc822', 'iconPLAIN.gif'),

			// Text files
			'asp'   => array('text/asp', 'iconPLAIN.gif'),
			'css'   => array('text/css', 'iconCSS.gif'),
			'html'  => array('text/html', 'iconHTML.gif'),
			'htm'   => array('text/html', 'iconHTML.gif'),
			'shtml' => array('text/html', 'iconHTML.gif'),
			'txt'   => array('text/plain', 'iconPLAIN.gif'),
			'text'  => array('text/plain', 'iconPLAIN.gif'),
			'log'   => array('text/plain', 'iconPLAIN.gif'),
			'rtx'   => array('text/richtext', 'iconPLAIN.gif'),
			'rtf'   => array('text/rtf', 'iconPLAIN.gif'),
			'xml'   => array('text/xml', 'iconPLAIN.gif'),
			'xsl'   => array('text/xml', 'iconPLAIN.gif'),

			// Videos
			'mpeg'  => array('video/mpeg', 'iconVIDEO.gif'),
			'mpg'   => array('video/mpeg', 'iconVIDEO.gif'),
			'mpe'   => array('video/mpeg', 'iconVIDEO.gif'),
			'qt'    => array('video/quicktime', 'iconVIDEO.gif'),
			'mov'   => array('video/quicktime', 'iconVIDEO.gif'),
			'rv'    => array('video/vnd.rn-realvideo', 'iconVIDEO.gif'),
			'avi'   => array('video/x-msvideo', 'iconVIDEO.gif'),
			'movie' => array('video/x-sgi-movie', 'iconVIDEO.gif')
		);

		// Extend the default lookup array
		if (is_array($GLOBALS['TL_MIME']) && !empty($GLOBALS['TL_MIME']))
		{
			$arrMimeTypes = array_merge($arrMimeTypes, $GLOBALS['TL_MIME']);
		}

		// Fallback to application/octet-stream
		if (!isset($arrMimeTypes[$this->extension]))
		{
			return array('application/octet-stream', 'iconPLAIN.gif');
		}

		return $arrMimeTypes[$this->extension];
	}


	/**
	 * Get the mime type of a file based on its extension
	 * @return string
	 */
	protected function getMimeType()
	{
		$arrMime = $this->getMimeInfo();
		return $arrMime[0];
	}


	/**
	 * Return an icon depending on the file type
	 * @return string
	 */
	protected function getIcon()
	{
		$arrMime = $this->getMimeInfo();
		return $arrMime[1];
	}
}

?>