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
 * @package    System
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \Cache, \File, \System, \Exception;


/**
 * Class Folder
 *
 * Provide methods to handle folders.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Folder extends System
{

	/**
	 * Folder name
	 * @var string
	 */
	protected $strFolder;


	/**
	 * Check whether a folder exists
	 * @param string
	 * @throws \Exception
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
			throw new Exception(sprintf('File "%s" is not a directory', $strFolder));
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
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		$strCacheKey = __METHOD__ . '-' . $this->strFolder . '-' . $strKey;

		if (!Cache::has($strCacheKey))
		{
			switch ($strKey)
			{
				case 'hash':
					Cache::set($strCacheKey, $this->getHash());
					break;

				case 'value':
					Cache::set($strCacheKey, $this->strFolder);
					break;

				default:
					return parent::__get($strKey);
					break;
			}
		}

		return Cache::get($strCacheKey);
	}


	/**
	 * Return true if the folder is empty
	 * @return boolean
	 */
	public function isEmpty()
	{
		return (count(scan(TL_ROOT . '/' . $this->strFolder)) < 1);
	}


	/**
	 * Empty the folder
	 * @return void
	 */
	public function purge()
	{
		$this->Files->rrdir($this->strFolder, true);
	}


	/**
	 * Backwards compatibility
	 * @return void
	 * @deprecated
	 */
	public function clear()
	{
		$this->purge();
	}


	/**
	 * Delete the folder
	 * @return void
	 */
	public function delete()
	{
		$this->Files->rrdir($this->strFolder);
	}


	/**
	 * Set the folder permissions
	 * @param string
	 * @return boolean
	 */
	public function chmod($strChmod)
	{
		return $this->Files->chmod($this->strFolder, $strChmod);
	}


	/**
	 * Rename the folder
	 * @param string
	 * @return boolean
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
	 * @param string
	 * @return boolean
	 */
	public function copyTo($strNewName)
	{
		return $this->Files->copy($this->strFolder, $strNewName);
	}


	/**
	 * Protect the folder by adding an .htaccess file
	 * @return void
	 */
	public function protect()
	{
		if (!file_exists(TL_ROOT . '/' . $this->strFolder . '/.htaccess'))
		{
			$objFile = new File($this->strFolder . '/.htaccess');
			$objFile->write("order deny,allow\ndeny from all");
			$objFile->close();
		}
	}


	/**
	 * Unprotect the folder by removing the .htaccess file
	 * @return void
	 */
	public function unprotect()
	{
		if (file_exists(TL_ROOT . '/' . $this->strFolder . '/.htaccess'))
		{
			$this->Files->delete($this->strFolder . '/.htaccess');
		}
	}


	/**
	 * Return the MD5 hash
	 * @return string
	 */
	protected function getHash()
	{
		$arrFiles = array();

		foreach (scan(TL_ROOT . '/' . $this->strFolder) as $strFile)
		{
			if ($strFile == '.svn' || $strFile == '.DS_Store')
			{
				continue;
			}

			$arrFiles[] = $strFile;
		}

		return md5(implode('-', $arrFiles));
	}

}
