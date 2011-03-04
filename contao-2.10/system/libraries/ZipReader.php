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
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class ZipReader
 *
 * This class provides methods to read a ZIP file.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class ZipReader
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
	 * Index of the current file
	 * @var integer
	 */
	private $intIndex = -1;

	/**
	 * Index of the last file
	 * @var integer
	 */
	private $intLast = 0;

	/**
	 * Archive header
	 * @var array
	 */
	protected $arrHeader = array();

	/**
	 * File list
	 * @var array
	 */
	protected $arrFiles = array();


	/**
	 * Constants
	 */
	const FILE_SIGNATURE    = "\x50\x4b\x03\x04";
	const CENTRAL_DIR_START = "\x50\x4b\x01\x02";
	const CENTRAL_DIR_END   = "\x50\x4b\x05\x06";


	/**
	 * Open the archive and return the file handle
	 * @param string
	 * @throws Exception
	 */
	public function __construct($strFile)
	{
		// Handle open_basedir restrictions
		if ($strFile == '.')
		{
			$strFile = '';
		}

		$this->strFile = $strFile;

		// Check if file exists
		if (!file_exists(TL_ROOT . '/' . $strFile))
		{
			throw new Exception("Could not find file $strFile");
		}

		$this->resFile = @fopen(TL_ROOT . '/' . $strFile, 'rb');

		// Could not open file
		if (!is_resource($this->resFile))
		{
			throw new Exception("Could not open file $strFile");
		}

		$this->readCentralDirectory();
	}


	/**
	 * Close the file handle
	 */
	public function __destruct()
	{
		@fclose($this->resFile);
	}


	/**
	 * Return a particular parameter of the header or the current file
	 *
	 * Supported header parameters
	 * - number_of_this_disk:       number of this disk
	 * - number_of_disk_with_cd:    number of the disk with thestart of the central directory
	 * - total_cd_entries_disk:     total number of entries in the central directory on this disk
	 * - total_cd_entries:          total number of entries in the central directory
	 * - size_of_cd:                size of the central directory
	 * - offset_start_cd:           offset of start of central directory with respect to the starting disk number
	 * - zipfile_comment_length:    ZIP file comment length
	 * - zipfile_comment:           ZIP file comment
	 * 
	 * Supported file parameters
	 * - version_made_by:           version made by
	 * - version_needed_to_extract: version needed to extract
	 * - general_purpose_bit_flag:  general purpose bit flag
	 * - compression_method:        compression method
	 * - last_mod_file_time:        last mod file time
	 * - last_mod_file_date:        last mod file date
	 * - last_mod_file_unix:        last mod file unix timestamp
	 * - crc-32:                    CRC32 checksum
	 * - compressed_size:           compressed size
	 * - uncompressed_size:         uncompressed size
	 * - file_name_length:          file name length
	 * - extra_field_length:        extra field length
	 * - file_comment_length:       file comment length
	 * - disk_number_start:         disk number start
	 * - internal_file_attributes:  internal file attributes
	 * - external_file_attributes:  external file attributes
	 * - offset_of_local_header:    relative offset of local header
	 * - file_name:                 file name
	 * - file_basename:             file basename
	 * - file_dirname:              file dirname
	 * - extra_field:               extra field
	 * - file_comment:              file comment
	 *
	 * Throws an exception on requests for unknown fields.
	 * @param  string
	 * @return string
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			// Header
			case 'number_of_this_disk':
			case 'number_of_disk_with_cd':
			case 'total_cd_entries_disk':
			case 'total_cd_entries':
			case 'size_of_cd':
			case 'offset_start_cd':
			case 'zipfile_comment_length':
			case 'zipfile_comment':
				return $this->arrHeader[$strKey];
				break;

			// Current file
			default:
				if ($this->intIndex < 0)
				{
					$this->first();
				}
				if (isset($this->arrFiles[$this->intIndex][$strKey]))
				{
					return $this->arrFiles[$this->intIndex][$strKey];
				}
				return null;
				break;
		}
	}


	/**
	 * Return a list of all files in the archive
	 * @return array
	 */
	public function getFileList()
	{
		$arrFiles = array();

		foreach ($this->arrFiles as $arrFile)
		{
			$arrFiles[] = $arrFile['file_name'];
		}

		return $arrFiles;
	}


	/**
	 * Set the internal pointer to a particular file and return true if successful
	 * @param string
	 * @return boolean
	 */
	public function getFile($strName)
	{
		foreach ($this->arrFiles as $k=>$v)
		{
			if ($strName == $v['file_name'])
			{
				$this->intIndex = $k;
				return true;
			}
		}

		return false;
	}


	/**
	 * Go to the first file of the archive
	 * @return object
	 */
	public function first()
	{
		$this->intIndex = 0;
		return $this;
	}


	/**
	 * Go to the next file of the archive
	 * @return mixed
	 */
	public function next()
	{
		if ($this->intIndex >= $this->intLast)
		{
			return false;
		}

		++$this->intIndex;
		return $this;
	}


	/**
	 * Go to the previous file of the archive
	 * @return mixed
	 */
	public function prev()
	{
		if ($this->intIndex <= 0)
		{
			return false;
		}

		--$this->intIndex;
		return $this;
	}


	/**
	 * Go to the last file of the archive
	 * @return mixed
	 */
	public function last()
	{
		$this->intIndex = $this->intLast;
		return $this;
	}


	/**
	 * Return the current file as array
	 * @return array
	 */
	public function current()
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return $this->arrFiles[$this->intIndex];
	}


	/**
	 * Reset the archive
	 * @return object
	 */
	public function reset()
	{
		$this->intIndex = -1;
		return $this;
	}


	/**
	 * Unzip the current file and return its contents as string
	 * @return string
	 * @throws Exception
	 */
	public function unzip()
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		$strName = $this->arrFiles[$this->intIndex]['file_name'];

		// Encrypted files are not supported
		if ($this->arrFiles[$this->intIndex]['general_purpose_bit_flag'] & 0x0001)
		{
			throw new Exception("File $strName is encrypted");
		}

		// Reposition pointer
		if (@fseek($this->resFile, $this->arrFiles[$this->intIndex]['offset_of_local_header']) !== 0)
		{
			throw new Exception("Cannot reposition pointer");
		}

		$strSignature = @fread($this->resFile, 4);

		// Not a file
		if ($strSignature != self::FILE_SIGNATURE)
		{
			throw new Exception("$strName is not a compressed file");
		}

		// Get extra field length
		@fseek($this->resFile, 24, SEEK_CUR);
		$arrEFL = unpack('v', @fread($this->resFile, 2));

		// Reposition pointer
		@fseek($this->resFile, ($this->arrFiles[$this->intIndex]['file_name_length'] + $arrEFL[1]), SEEK_CUR);

		// Empty file
		if ($this->arrFiles[$this->intIndex]['compressed_size'] < 1)
		{
			return '';
		}

		// Read data
		$strBuffer = @fread($this->resFile, $this->arrFiles[$this->intIndex]['compressed_size']);

		// Decompress data
		switch ($this->arrFiles[$this->intIndex]['compression_method'])
		{
			// Stored
			case 0:
				break;

			// Deflated
			case 8:
				$strBuffer = gzinflate($strBuffer);
				break;

			// BZIP2
			case 12:
				if (!extension_loaded('bz2'))
				{
					throw new Exception('PHP extension "bz2" required to decompress BZIP2 files');
				}
				$strBuffer = bzdecompress($strBuffer);
				break;

			// Unknown
			default:
				throw new Exception('Unknown compression method');
				break;
		}

		// Check uncompressed data
		if ($strBuffer === false)
		{
			throw new Exception('Could not decompress data');
		}

		// Check uncompressed size
		if (strlen($strBuffer) != $this->arrFiles[$this->intIndex]['uncompressed_size'])
		{
			throw new Exception('Size of the uncompressed file does not match header value');
		}

		return $strBuffer;
	}


	/**
	 * Return a list of all files in the archive
	 * @return array
	 * @throws Exception
	 */
	protected function readCentralDirectory()
	{
		$intOffset = 0;
		$intInterval = min(filesize(TL_ROOT . '/' . $this->strFile), 1024);
		$strBuffer = '';

		// Read to delimiter
		do
		{
			$intOffset -= $intInterval;

			$fseek = @fseek($this->resFile, $intOffset, SEEK_END);
			$strBuffer = @fread($this->resFile, abs($intOffset)) . $strBuffer;
		}
		while ($fseek != -1 && ($pos = strpos($strBuffer, self::CENTRAL_DIR_END)) === false);

		// Reposition pointer
		@fseek($this->resFile, ($intOffset + $pos), SEEK_END);
		$strSignature = @fread($this->resFile, 4);

		// Read archive header
		if ($strSignature != self::CENTRAL_DIR_END)
		{
			throw new Exception("Error reading central directory");
		}

		$arrHeader = array();

		$arrHeader['number_of_this_disk']    = unpack('v', @fread($this->resFile, 2));
		$arrHeader['number_of_disk_with_cd'] = unpack('v', @fread($this->resFile, 2));
		$arrHeader['total_cd_entries_disk']  = unpack('v', @fread($this->resFile, 2));
		$arrHeader['total_cd_entries']       = unpack('v', @fread($this->resFile, 2));
		$arrHeader['size_of_cd']             = unpack('V', @fread($this->resFile, 4));
		$arrHeader['offset_start_cd']        = unpack('V', @fread($this->resFile, 4));
		$arrHeader['zipfile_comment_length'] = unpack('v', @fread($this->resFile, 2));
		$arrHeader['zipfile_comment']        = $arrHeader['zipfile_comment_length'][1] ? @fread($this->resFile, $arrHeader['zipfile_comment_length'][1]) : '';

		// Eliminate nested arrays
		foreach ($arrHeader as $k=>$v)
		{
			$arrHeader[$k] = is_array($v) ? $v[1] : $v;
		}

		$this->arrHeader = $arrHeader;

		// Reposition pointer to begin of the central directory
		@fseek($this->resFile, $this->arrHeader['offset_start_cd'], SEEK_SET);
		$strSignature = @fread($this->resFile, 4);

		// Build file list
		while ($strSignature == self::CENTRAL_DIR_START )
		{
			$arrFile = array();

			$arrFile['version_made_by']           = unpack('v', @fread($this->resFile, 2));
			$arrFile['version_needed_to_extract'] = unpack('v', @fread($this->resFile, 2));
			$arrFile['general_purpose_bit_flag']  = unpack('v', @fread($this->resFile, 2));
			$arrFile['compression_method']        = unpack('v', @fread($this->resFile, 2));
			$arrFile['last_mod_file_time']        = unpack('v', @fread($this->resFile, 2));
			$arrFile['last_mod_file_date']        = unpack('v', @fread($this->resFile, 2));
			$arrFile['crc-32']                    = unpack('V', @fread($this->resFile, 4));
			$arrFile['compressed_size']           = unpack('V', @fread($this->resFile, 4));
			$arrFile['uncompressed_size']         = unpack('V', @fread($this->resFile, 4));
			$arrFile['file_name_length']          = unpack('v', @fread($this->resFile, 2));
			$arrFile['extra_field_length']        = unpack('v', @fread($this->resFile, 2));
			$arrFile['file_comment_length']       = unpack('v', @fread($this->resFile, 2));
			$arrFile['disk_number_start']         = unpack('v', @fread($this->resFile, 2));
			$arrFile['internal_file_attributes']  = unpack('v', @fread($this->resFile, 2));
			$arrFile['external_file_attributes']  = unpack('V', @fread($this->resFile, 4));
			$arrFile['offset_of_local_header']    = unpack('V', @fread($this->resFile, 4));
			$arrFile['file_name']                 = @fread($this->resFile, $arrFile['file_name_length'][1]);
			$arrFile['extra_field']               = $arrFile['extra_field_length'][1] ? @fread($this->resFile, $arrFile['extra_field_length'][1]) : '';
			$arrFile['file_comment']              = $arrFile['file_comment_length'][1] ? @fread($this->resFile, $arrFile['file_comment_length'][1]) : '';

			// Skip directories
			if (substr($arrFile['file_name'], -1) == '/')
			{
				$strSignature = @fread($this->resFile, 4);
				continue;
			}

			// Eliminate nested arrays
			foreach ($arrFile as $k=>$v)
			{
				$arrFile[$k] = is_array($v) ? $v[1] : $v;
			}

			// Split file path
			$arrFile['file_basename'] = basename($arrFile['file_name']);
			$arrFile['file_dirname'] = (($path = dirname($arrFile['file_name'])) != '.' ? $path : '');

			// Add UNIX time
			$arrFile['last_mod_file_unix'] = $this->decToUnix($arrFile['last_mod_file_time'], $arrFile['last_mod_file_date']);

			// Read next signature
			$strSignature = @fread($this->resFile, 4);
			$this->arrFiles[] = $arrFile;
		}

		$this->intLast = (count($this->arrFiles) - 1);

	}


	/**
	 * Calculate the Unix timestamp from two hexadecimal values
	 * @param integer
	 * @param integer
	 * @return integer
	 */
	protected function decToUnix($intTime, $intDate)
	{
		return mktime
		(
			 ($intTime & 0xf800) >> 11,
			 ($intTime & 0x07e0) >>  5,
			 ($intTime & 0x001f) <<  1,
			 ($intDate & 0x01e0) >>  5,
			 ($intDate & 0x001f),
			(($intDate & 0xfe00) >>  9) + 1980
		);
	}
}

?>