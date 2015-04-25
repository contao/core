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
 * Creates .zip files and stores them on the disk
 *
 * Usage:
 *
 *     $zip = new ZipWriter('test.zip');
 *     $zip->addFile('test.txt');
 *     $zip->close();
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ZipWriter
{

	/**
	 * File signatur
	 * @var string
	 */
	const FILE_SIGNATURE = "\x50\x4b\x03\x04";

	/**
	 * Central directory begin marker
	 * @var string
	 */
	const CENTRAL_DIR_START = "\x50\x4b\x01\x02";

	/**
	 * Central directory end marker
	 * @var string
	 */
	const CENTRAL_DIR_END   = "\x50\x4b\x05\x06";

	/**
	 * Temp folder path
	 * @var string
	 */
	const TEMPORARY_FOLDER  = 'system/tmp';

	/**
	 * File handle
	 * @var resource
	 */
	protected $resFile;

	/**
	 * File name
	 * @var resource
	 */
	protected $strFile;

	/**
	 * Temporary name
	 * @var resource
	 */
	protected $strTemp;

	/**
	 * Central directory
	 * @var string
	 */
	protected $strCentralDir;

	/**
	 * File count
	 * @var integer
	 */
	protected $intCount = 0;


	/**
	 * Create a new zip archive
	 *
	 * @param string $strFile The file path
	 *
	 * @throws \Exception If the temporary file cannot be created or opened
	 */
	public function __construct($strFile)
	{
		$this->strFile = $strFile;

		// Create temporary file
		if (($this->strTemp = tempnam(TL_ROOT . '/' . self::TEMPORARY_FOLDER , 'zip')) == false)
		{
			throw new \Exception("Cannot create temporary file");
		}

		// Open temporary file
		if (($this->resFile = @fopen($this->strTemp, 'wb')) == false)
		{
			throw new \Exception("Cannot open temporary file");
		}
	}


	/**
	 * Close the file handle if it has not been done yet
	 */
	public function __destruct()
	{
		if (is_resource($this->resFile))
		{
			@fclose($this->resFile);
		}

		if (file_exists($this->strTemp))
		{
			@unlink($this->strTemp);
		}
	}


	/**
	 * Add a file to the archive
	 *
	 * @param string $strFile The file path
	 * @param string $strName An optional file name
	 *
	 * @throws \Exception If $strFile does not exist
	 */
	public function addFile($strFile, $strName=null)
	{
		if (!file_exists(TL_ROOT . '/' . $strFile))
		{
			throw new \Exception("File $strFile does not exist");
		}

		// Remove leading slashes (see #4502)
		if (strncmp($strName, '/', 1) === 0)
		{
			$strName = substr($strName, 1);
		}

		$this->addString(file_get_contents(TL_ROOT . '/' . $strFile), $strName ?: $strFile, filemtime(TL_ROOT . '/' . $strFile));
	}


	/**
	 * Add a file from a string to the archive
	 *
	 * @param string  $strData The data to be added
	 * @param string  $strName The file path
	 * @param integer $intTime An optional modification timestamp
	 */
	public function addString($strData, $strName, $intTime=0)
	{
		++$this->intCount;
		$strName = str_replace('\\', '/', $strName);

		// Start file
		$arrFile['file_signature']            = self::FILE_SIGNATURE;
		$arrFile['version_needed_to_extract'] = "\x14\x00";
		$arrFile['general_purpose_bit_flag']  = "\x00\x00";
		$arrFile['compression_method']        = "\x08\x00";
		$arrFile['last_mod_file_hex']         = $this->unixToHex($intTime);
		$arrFile['crc-32']                    = pack('V', crc32($strData));

		$intUncompressed = strlen($strData);

		// Compress data
		$strData = gzcompress($strData);
		$strData = substr(substr($strData, 0, strlen($strData) - 4), 2);

		$intCompressed = strlen($strData);

		// Continue file
		$arrFile['compressed_size']           = pack('V', $intCompressed);
		$arrFile['uncompressed_size']         = pack('V', $intUncompressed);
		$arrFile['file_name_length']          = pack('v', strlen($strName));
		$arrFile['extra_field_length']        = "\x00\x00";
		$arrFile['file_name']                 = $strName;
		$arrFile['extra_field']               = '';

		// Store file offset
		$intOffset = @ftell($this->resFile);

		// Add file to archive
		@fputs($this->resFile, implode('', $arrFile));
		@fputs($this->resFile, $strData);

		// Start central directory
		$arrHeader['header_signature']          = self::CENTRAL_DIR_START;
		$arrHeader['version_made_by']           = "\x00\x00";
		$arrHeader['version_needed_to_extract'] = $arrFile['version_needed_to_extract'];
		$arrHeader['general_purpose_bit_flag']  = $arrFile['general_purpose_bit_flag'];
		$arrHeader['compression_method']        = $arrFile['compression_method'];
		$arrHeader['last_mod_file_hex']         = $arrFile['last_mod_file_hex'];
		$arrHeader['crc-32']                    = $arrFile['crc-32'];
		$arrHeader['compressed_size']           = $arrFile['compressed_size'];
		$arrHeader['uncompressed_size']         = $arrFile['uncompressed_size'];
		$arrHeader['file_name_length']          = $arrFile['file_name_length'];
		$arrHeader['extra_field_length']        = $arrFile['extra_field_length'];
		$arrHeader['file_comment_length']       = "\x00\x00";
		$arrHeader['disk_number_start']         = "\x00\x00";
		$arrHeader['internal_file_attributes']  = "\x00\x00";
		$arrHeader['external_file_attributes']  = pack('V', 32);
		$arrHeader['offset_of_local_header']    = pack('V', $intOffset);
		$arrHeader['file_name']                 = $arrFile['file_name'];
		$arrHeader['extra_field']               = $arrFile['extra_field'];
		$arrHeader['file_comment']              = '';

		// Add entry to central directory
		$this->strCentralDir .= implode('', $arrHeader);
	}


	/**
	 * Write the central directory and close the file handle
	 */
	public function close()
	{
		// Add archive header
		$arrArchive['archive_signature']      = self::CENTRAL_DIR_END;
		$arrArchive['number_of_this_disk']    = "\x00\x00";
		$arrArchive['number_of_disk_with_cd'] = "\x00\x00";
		$arrArchive['total_cd_entries_disk']  = pack('v', $this->intCount);
		$arrArchive['total_cd_entries']       = pack('v', $this->intCount);
		$arrArchive['size_of_cd']             = pack('V', strlen($this->strCentralDir));
		$arrArchive['offset_start_cd']        = pack('V', @ftell($this->resFile));
		$arrArchive['zipfile_comment_length'] = "\x00\x00";
		$arrArchive['zipfile_comment']        = '';

		// Add central directory and archive header (do not change this order)
		@fputs($this->resFile, $this->strCentralDir);
		@fputs($this->resFile, implode('', $arrArchive));

		// Close the file before renaming it
		@fclose($this->resFile);

		// Check if target file exists
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
		}

		// Rename file
		\Files::getInstance()->rename(self::TEMPORARY_FOLDER . '/' . basename($this->strTemp), $this->strFile);
	}


	/**
	 * Convert a Unix timestamp to a hexadecimal value
	 *
	 * @param integer $intTime The Unix timestamp
	 *
	 * @return integer The hexadecimal value
	 */
	protected function unixToHex($intTime=0)
	{
		$arrTime = $intTime ? getdate($intTime) : getdate();

		$hexTime = dechex
		(
			(($arrTime['year'] - 1980) << 25) |
			 ($arrTime['mon'] << 21) |
			 ($arrTime['mday'] << 16) |
			 ($arrTime['hours'] << 11) |
			 ($arrTime['minutes'] << 5) |
			 ($arrTime['seconds'] >> 1)
		);

		return pack("H*", $hexTime[6] . $hexTime[7] . $hexTime[4] . $hexTime[5] . $hexTime[2] . $hexTime[3] . $hexTime[0] . $hexTime[1]);
	}
}
