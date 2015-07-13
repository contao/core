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
 * @property string  $hash     The MD5 hash
 * @property string  $name     The folder name
 * @property string  $basename Alias of $name
 * @property string  $path     The folder path
 * @property string  $value    Alias of $path
 * @property integer $size     The folder size
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Folder extends \System
{

	/**
	 * Folder name
	 * @var string
	 */
	protected $strFolder;

	/**
	 * Files model
	 * @var \FilesModel
	 */
	protected $objModel;

	/**
	 * Synchronize the database
	 * @var boolean
	 */
	protected $blnSyncDb = false;


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

		// Check whether we need to sync the database
		$this->blnSyncDb = (\Config::get('uploadPath') != 'templates' && strncmp($strFolder . '/', \Config::get('uploadPath') . '/', strlen(\Config::get('uploadPath')) + 1) === 0);

		// Check the excluded folders
		if ($this->blnSyncDb && \Config::get('fileSyncExclude') != '')
		{
			$arrExempt = array_map(function($e) {
				return \Config::get('uploadPath') . '/' . $e;
			}, trimsplit(',', \Config::get('fileSyncExclude')));

			foreach ($arrExempt as $strExempt)
			{
				if (strncmp($strExempt . '/', $strFolder . '/', strlen($strExempt) + 1) === 0)
				{
					$this->blnSyncDb = false;
					break;
				}
			}
		}

		// Create the folder if it does not exist
		if (!is_dir(TL_ROOT . '/' . $this->strFolder))
		{
			$strPath = '';
			$arrChunks = explode('/', $this->strFolder);

			// Create the folder
			foreach ($arrChunks as $strFolder)
			{
				$strPath .= ($strPath ? '/' : '') . $strFolder;
				$this->Files->mkdir($strPath);
			}

			// Update the database
			if ($this->blnSyncDb)
			{
				$this->objModel = \Dbafs::addResource($this->strFolder);
			}
		}
	}


	/**
	 * Return an object property
	 *
	 * @param string $strKey The property name
	 *
	 * @return mixed The property value
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'hash':
				return $this->getHash();
				break;

			case 'name':
			case 'basename':
				return basename($this->strFolder);
				break;

			case 'path':
			case 'value':
				return $this->strFolder;
				break;

			case 'size':
				return $this->getSize();
				break;

			default:
				return parent::__get($strKey);
				break;
		}
	}


	/**
	 * Return true if the folder is empty
	 *
	 * @return boolean True if the folder is empty
	 */
	public function isEmpty()
	{
		return (count(scan(TL_ROOT . '/' . $this->strFolder, true)) < 1);
	}


	/**
	 * Purge the folder
	 */
	public function purge()
	{
		$this->Files->rrdir($this->strFolder, true);

		// Update the database
		if ($this->blnSyncDb)
		{
			$objFiles = \FilesModel::findMultipleByBasepath($this->strFolder . '/');

			if ($objFiles !== null)
			{
				while ($objFiles->next())
				{
					$objFiles->delete();
				}
			}

			\Dbafs::updateFolderHashes($this->strFolder);
		}
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

		// Update the database
		if ($this->blnSyncDb)
		{
			\Dbafs::deleteResource($this->strFolder);
		}
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
		$strParent = dirname($strNewName);

		// Create the parent folder if it does not exist
		if (!is_dir(TL_ROOT . '/' . $strParent))
		{
			new \Folder($strParent);
		}

		$return = $this->Files->rename($this->strFolder, $strNewName);

		// Update the database AFTER the folder has been renamed
		if ($this->blnSyncDb)
		{
			$this->objModel = \Dbafs::moveResource($this->strFolder, $strNewName);
		}

		// Reset the object AFTER the database has been updated
		if ($return != false)
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
		$strParent = dirname($strNewName);

		// Create the parent folder if it does not exist
		if (!is_dir(TL_ROOT . '/' . $strParent))
		{
			new \Folder($strParent);
		}

		$this->Files->rcopy($this->strFolder, $strNewName);

		// Update the database AFTER the folder has been renamed
		if ($this->blnSyncDb)
		{
			$this->objModel = \Dbafs::copyResource($this->strFolder, $strNewName);
		}

		return true;
	}


	/**
	 * Protect the folder by adding an .htaccess file
	 */
	public function protect()
	{
		if (!file_exists(TL_ROOT . '/' . $this->strFolder . '/.htaccess'))
		{
			\File::putContent($this->strFolder . '/.htaccess', "<IfModule !mod_authz_core.c>\n  Order deny,allow\n  Deny from all\n</IfModule>\n<IfModule mod_authz_core.c>\n  Require all denied\n</IfModule>");
		}
	}


	/**
	 * Unprotect the folder by removing the .htaccess file
	 */
	public function unprotect()
	{
		if (file_exists(TL_ROOT . '/' . $this->strFolder . '/.htaccess'))
		{
			$objFile = new \File($this->strFolder . '/.htaccess', true);
			$objFile->delete();
		}
	}


	/**
	 * Return the files model
	 *
	 * @return \FilesModel The files model
	 */
	public function getModel()
	{
		if ($this->blnSyncDb && $this->objModel === null)
		{
			$this->objModel = \FilesModel::findByPath($this->strFolder);
		}

		return $this->objModel;
	}


	/**
	 * Return the MD5 hash of the folder
	 *
	 * @return string The MD5 has
	 */
	protected function getHash()
	{
		$arrFiles = array();

		/** @var \SplFileInfo[] $it */
		$it = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator(
				TL_ROOT . '/' . $this->strFolder,
				\FilesystemIterator::UNIX_PATHS|\FilesystemIterator::FOLLOW_SYMLINKS|\FilesystemIterator::SKIP_DOTS
			), \RecursiveIteratorIterator::SELF_FIRST
		);

		foreach ($it as $i)
		{
			if (strncmp($i->getFilename(), '.', 1) !== 0)
			{
				$arrFiles[] = str_replace(TL_ROOT . '/' . $this->strFolder . '/', '', $i->getPathname());
			}
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

		foreach (scan(TL_ROOT . '/' . $this->strFolder, true) as $strFile)
		{
			if (strncmp($strFile, '.', 1) === 0)
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
				$objFile = new \File($this->strFolder . '/' . $strFile, true);
				$intSize += $objFile->size;
			}
		}

		return $intSize;
	}
}
