<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


/**
 * Creates, reads, writes and deletes files
 * 
 * Usage:
 * 
 *     $file = new File('test.txt');
 *     $file->write('This is a test');
 *     $file->close();
 * 
 *     $file->delete();
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class File extends \System
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
	 * 
	 * @param string $strFile The file path
	 * 
	 * @throws \Exception If $strFile is not writeable or not a file
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
			throw new \Exception(sprintf('Directory "%s" is not a file', $strFile));
		}

		$this->import('Files');
		$this->strFile = $strFile;

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
				new \Folder($strFolder);
			}

			// Open file
			if (($this->resFile = $this->Files->fopen($this->strFile, 'wb')) == false)
			{
				throw new \Exception(sprintf('Cannot create file "%s"', $this->strFile));
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
	 * 
	 * Supported keys:
	 * 
	 * * size:      the file size
	 * * name:      the file name without extension
	 * * dirname:   the path of the parent folder
	 * * extension: the file extension
	 * * filename:  the file name and extension
	 * * mime:      the file's mime type
	 * * hash:      the file's MD5 checksum
	 * * ctime:     the file's ctime
	 * * mtime:     the file's mtime
	 * * atime:     the file's atime
	 * * icon:      the name of the corresponding mime icon
	 * * path:      the path to the file
	 * * width:     the file width (images only)
	 * * height:    the file height (images only)
	 * * isGdImage: true if the file can be handled by the GDlib
	 * * handle:    the file handle (returned by fopen())
	 * 
	 * @param string $strKey The property name
	 * 
	 * @return mixed The property value
	 */
	public function __get($strKey)
	{
		$strCacheKey = __METHOD__ . '-' . $this->strFile . '-' . $strKey;

		if (!\Cache::has($strCacheKey))
		{
			switch ($strKey)
			{
				case 'size':
				case 'filesize':
					\Cache::set($strCacheKey, filesize(TL_ROOT . '/' . $this->strFile));
					break;

				case 'name':
				case 'basename':
					if (!isset($this->arrPathinfo[$strKey]))
					{
						$this->arrPathinfo = pathinfo(TL_ROOT . '/' . $this->strFile);
					}
					\Cache::set($strCacheKey, $this->arrPathinfo['basename']);
					break;

				case 'dirname':
					if (!isset($this->arrPathinfo[$strKey]))
					{
						$this->arrPathinfo = pathinfo(TL_ROOT . '/' . $this->strFile);
					}
					\Cache::set($strCacheKey, $this->arrPathinfo['dirname']);
					break;

				case 'extension':
					if (!isset($this->arrPathinfo['extension']))
					{
						$this->arrPathinfo = pathinfo(TL_ROOT . '/' . $this->strFile);
					}
					\Cache::set($strCacheKey, strtolower($this->arrPathinfo['extension']));
					break;

				case 'filename':
					\Cache::set($strCacheKey, basename($this->basename, '.' . $this->extension));
					break;

				case 'mime':
					\Cache::set($strCacheKey, $this->getMimeType());
					break;

				case 'hash':
					\Cache::set($strCacheKey, $this->getHash());
					break;

				case 'ctime':
					\Cache::set($strCacheKey, filectime(TL_ROOT . '/' . $this->strFile));
					break;

				case 'mtime':
					\Cache::set($strCacheKey, filemtime(TL_ROOT . '/' . $this->strFile));
					break;

				case 'atime':
					\Cache::set($strCacheKey, fileatime(TL_ROOT . '/' . $this->strFile));
					break;

				case 'icon':
					\Cache::set($strCacheKey, $this->getIcon());
					break;

				case 'path':
				case 'value':
					\Cache::set($strCacheKey, $this->strFile);
					break;

				case 'width':
					if (empty($this->arrImageSize))
					{
						$this->arrImageSize = @getimagesize(TL_ROOT . '/' . $this->strFile);
					}
					\Cache::set($strCacheKey, $this->arrImageSize[0]);
					break;

				case 'height':
					if (empty($this->arrImageSize))
					{
						$this->arrImageSize = @getimagesize(TL_ROOT . '/' . $this->strFile);
					}
					\Cache::set($strCacheKey, $this->arrImageSize[1]);
					break;

				case 'isGdImage':
					\Cache::set($strCacheKey, in_array($this->extension, array('gif', 'jpg', 'jpeg', 'png')));
					break;

				case 'handle':
					if (!is_resource($this->resFile))
					{
						$this->resFile = fopen(TL_ROOT . '/' . $this->strFile, 'rb');
					}
					return $this->resFile;
					break;

				default:
					return parent::__get($strKey);
					break;
			}
		}

		return \Cache::get($strCacheKey);
	}


	/**
	 * Truncate the file
	 * 
	 * @return boolean True if the operation was successful
	 */
	public function truncate()
	{
		return $this->write('');
	}


	/**
	 * Write data to the file
	 * 
	 * @param mixed $varData The data to be written
	 * 
	 * @return boolean True if the operation was successful
	 */
	public function write($varData)
	{
		return $this->fputs($varData, 'wb');
	}


	/**
	 * Append data to the file
	 * 
	 * @param mixed  $varData The data to be appended
	 * @param string $strLine The line ending (defaults to LF)
	 * 
	 * @return boolean True if the operation was successful
	 */
	public function append($varData, $strLine="\n")
	{
		return $this->fputs($varData . $strLine, 'ab');
	}


	/**
	 * Delete the file
	 * 
	 * @return boolean True if the operation was successful
	 */
	public function delete()
	{
		return $this->Files->delete($this->strFile);
	}


	/**
	 * Set the file permissions
	 * 
	 * @param integer $intChmod The CHMOD settings
	 * 
	 * @return boolean True if the operation was successful
	 */
	public function chmod($intChmod)
	{
		return $this->Files->chmod($this->strFile, $intChmod);
	}


	/**
	 * Close the file handle
	 * 
	 * @return boolean True if the operation was successful
	 */
	public function close()
	{
		return $this->Files->fclose($this->resFile);
	}


	/**
	 * Return the file content as string
	 * 
	 * @return string The file content without BOM
	 */
	public function getContent()
	{
		$strContent = file_get_contents(TL_ROOT . '/' . $this->strFile);

		// Remove BOMs (see #4469)
		if (strncmp($strContent, "\xEF\xBB\xBF", 3) === 0)
		{
			$strContent = substr($strContent, 3);
		}
		elseif (strncmp($strContent, "\xFF\xFE", 2) === 0)
		{
			$strContent = substr($strContent, 2);
		}
		elseif (strncmp($strContent, "\xFE\xFF", 2) === 0)
		{
			$strContent = substr($strContent, 2);
		}

		return $strContent;
	}


	/**
	 * Return the file content as array
	 * 
	 * @return array The file content as array
	 */
	public function getContentAsArray()
	{
		return array_map('rtrim', file(TL_ROOT . '/' . $this->strFile));
	}


	/**
	 * Rename the file
	 * 
	 * @param string $strNewName The new path
	 * 
	 * @return boolean True if the operation was successful
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
	 * 
	 * @param string $strNewName The target path
	 * 
	 * @return boolean True if the operation was successful
	 */
	public function copyTo($strNewName)
	{
		return $this->Files->copy($this->strFile, $strNewName);
	}


	/**
	 * Write data to a file
	 * 
	 * @param mixed  $varData The data to be written
	 * @param string $strMode The operation mode
	 * 
	 * @return boolean True if the operation was successful
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
	 * Return the mime type and icon of the file based on its extension
	 * 
	 * @return array An array with mime type and icon name
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
			'm4a'   => array('audio/x-m4a', 'iconAUDIO.gif'),
			'mp3'   => array('audio/mp3', 'iconAUDIO.gif'),
			'wma'   => array('audio/wma', 'iconAUDIO.gif'),
			'mpeg'  => array('audio/mpeg', 'iconAUDIO.gif'),
			'wav'   => array('audio/wav', 'iconAUDIO.gif'),
			'mid'   => array('audio/midi', 'iconAUDIO.gif'),
			'midi'  => array('audio/midi', 'iconAUDIO.gif'),
			'aif'   => array('audio/x-aiff', 'iconAUDIO.gif'),
			'aiff'  => array('audio/x-aiff', 'iconAUDIO.gif'),
			'aifc'  => array('audio/x-aiff', 'iconAUDIO.gif'),
			'ram'   => array('audio/x-pn-realaudio', 'iconAUDIO.gif'),
			'rm'    => array('audio/x-pn-realaudio', 'iconAUDIO.gif'),
			'rpm'   => array('audio/x-pn-realaudio-plugin', 'iconAUDIO.gif'),
			'ra'    => array('audio/x-realaudio', 'iconAUDIO.gif'),

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
			'mp4'   => array('video/mp4', 'iconVIDEO.gif'),
			'm4v'   => array('video/x-m4v', 'iconVIDEO.gif'),
			'mov'   => array('video/mov', 'iconVIDEO.gif'),
			'wmv'   => array('video/wmv', 'iconVIDEO.gif'),
			'webm'  => array('video/webm', 'iconVIDEO.gif'),
			'qt'    => array('video/quicktime', 'iconVIDEO.gif'),
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
	 * Get the mime type of the file based on its extension
	 *  
	 * @return string The mime type
	 */
	protected function getMimeType()
	{
		$arrMime = $this->getMimeInfo();
		return $arrMime[0];
	}


	/**
	 * Return the file icon depending on the file type
	 * 
	 * @return string The icon name
	 */
	protected function getIcon()
	{
		$arrMime = $this->getMimeInfo();
		return $arrMime[1];
	}


	/**
	 * Return the MD5 hash of the file
	 * 
	 * @return string The MD5 hash
	 */
	protected function getHash()
	{
		return md5_file(TL_ROOT . '/' . $this->strFile);
	}
}
