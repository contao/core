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
 * Creates, reads, writes and deletes folders
 * 
 * Usage:
 * 
 *     $folder = new Folder('test');
 * 
 *     if (!$folder->isEmpty())
 *     {
 *         $folder->purge();
 *     }
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class Folder extends \System
{

	/**
	 * Folder name
	 * @var string
	 */
	protected $strFolder;


	/**
	 * Check whether the folder exists
	 * 
	 * @param string $strFolder The folder path
	 * 
	 * @throws \Exception If $strFolder is not a folder
	 */
	public function __construct($strFolder)
	{
		// Handle open_basedir restrictions
		if ($strFolder == '.')
		{
			$strFolder = '';
		}

		// Check whether it is a directory
		if (is_file(TL_ROOT . '/' . $strFolder))
		{
			throw new \Exception(sprintf('File "%s" is not a directory', $strFolder));
		}

		$this->import('Files');
		$this->strFolder = $strFolder;

		// Create folder if it does not exist
		if (!is_dir(TL_ROOT . '/' . $this->strFolder))
		{
			$strPath = '';
			$arrChunks = explode('/', $this->strFolder);

			foreach ($arrChunks as $strFolder)
			{
				$strPath .= $strFolder . '/';
				$this->Files->mkdir($strPath);
			}
		}
	}


	/**
	 * Return an object property
	 * 
	 * Supported keys:
	 * 
	 * * hash: the folder's MD5 hash
	 * * path: the path to the folder
	 * * size: the folder size
	 * 
	 * @param string $strKey The property name
	 * 
	 * @return mixed The property value
	 */
	public function __get($strKey)
	{
		$strCacheKey = __METHOD__ . '-' . $this->strFolder . '-' . $strKey;

		if (!\Cache::has($strCacheKey))
		{
			switch ($strKey)
			{
				case 'hash':
					\Cache::set($strCacheKey, $this->getHash());
					break;

				case 'path':
				case 'value':
					\Cache::set($strCacheKey, $this->strFolder);
					break;

				case 'size':
					\Cache::set($strCacheKey, $this->getSize());
					break;

				default:
					return parent::__get($strKey);
					break;
			}
		}

		return \Cache::get($strCacheKey);
	}


	/**
	 * Return true if the folder is empty
	 * 
	 * @return boolean True if the folder is empty
	 */
	public function isEmpty()
	{
		return (count(scan(TL_ROOT . '/' . $this->strFolder)) < 1);
	}


	/**
	 * Purge the folder
	 */
	public function purge()
	{
		$this->Files->rrdir($this->strFolder, true);
	}


	/**
	 * Purge the folder
	 * 
	 * @deprecated Use $this->purge() instead
	 */
	public function clear()
	{
		$this->purge();
	}


	/**
	 * Delete the folder
	 */
	public function delete()
	{
		$this->Files->rrdir($this->strFolder);
	}


	/**
	 * Set the folder permissions
	 * 
	 * @param string $intChmod The CHMOD settings
	 * 
	 * @return boolean True if the operation was successful
	 */
	public function chmod($intChmod)
	{
		return $this->Files->chmod($this->strFolder, $intChmod);
	}


	/**
	 * Rename the folder
	 * 
	 * @param string $strNewName The new path
	 * 
	 * @return boolean True if the operation was successful
	 */
	public function renameTo($strNewName)
	{
		$return = $this->Files->rename($this->strFolder, $strNewName);

		if ($return)
		{
			$this->strFolder = $strNewName;
		}

		return $return;
	}


	/**
	 * Copy the folder
	 * 
	 * @param string $strNewName The target path
	 * 
	 * @return boolean True if the operation was successful
	 */
	public function copyTo($strNewName)
	{
		return $this->Files->copy($this->strFolder, $strNewName);
	}


	/**
	 * Protect the folder by adding an .htaccess file
	 */
	public function protect()
	{
		if (!file_exists(TL_ROOT . '/' . $this->strFolder . '/.htaccess'))
		{
			$objFile = new \File($this->strFolder . '/.htaccess');
			$objFile->write("order deny,allow\ndeny from all");
			$objFile->close();
		}
	}


	/**
	 * Unprotect the folder by removing the .htaccess file
	 */
	public function unprotect()
	{
		if (file_exists(TL_ROOT . '/' . $this->strFolder . '/.htaccess'))
		{
			$this->Files->delete($this->strFolder . '/.htaccess');
		}
	}


	/**
	 * Return the MD5 hash of the folder
	 * 
	 * @return string The MD5 has
	 */
	protected function getHash()
	{
		$arrFiles = array();

		$it = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator(TL_ROOT . '/' . $this->strFolder, \FilesystemIterator::UNIX_PATHS)
		);

		while ($it->valid())
		{
			if ($it->isFile() && $it->getFilename() != '.DS_Store')
			{
				$arrFiles[] = $it->getSubPathname();
				$arrFiles[] = md5_file($it->getPathname());
			}

			$it->next();
		}

		return md5(implode('-', $arrFiles));
	}


	/**
	 * Return the size of the folder
	 * 
	 * @return integer The folder size in bytes
	 */
	protected function getSize()
	{
		$intSize = 0;

		foreach (scan(TL_ROOT . '/' . $this->strFolder) as $strFile)
		{
			if ($strFile == '.svn' || $strFile == '.DS_Store')
			{
				continue;
			}

			if (is_dir(TL_ROOT . '/' . $this->strFolder . '/' . $strFile))
			{
				$objFolder = new \Folder($this->strFolder . '/' . $strFile);
				$intSize += $objFolder->size;
			}
			else
			{
				$objFile = new \File($this->strFolder . '/' . $strFile);
				$intSize += $objFile->size;
			}
		}

		return $intSize;
	}
}
