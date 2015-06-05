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
 * Handles the database assisted file system (DBAFS)
 *
 * The class provides static methods to add, move, copy and delete resources as
 * well as a method to synchronize the file system and the database.
 *
 * Usage:
 *
 *     $file = Dbafs::addResource('files/james-wilson.jpg');
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Dbafs
{

	/**
	 * Adds a file or folder with its parent folders
	 *
	 * @param string  $strResource      The path to the file or folder
	 * @param boolean $blnUpdateFolders If true, the parent folders will be updated
	 *
	 * @return \FilesModel The files model
	 *
	 * @throws \Exception                If a parent ID entry is missing
	 * @throws \InvalidArgumentException If the resource is outside the upload folder
	 */
	public static function addResource($strResource, $blnUpdateFolders=true)
	{
		$strUploadPath = \Config::get('uploadPath') . '/';

		// Remove trailing slashes (see #5707)
		if (substr($strResource, -1) == '/')
		{
			$strResource = substr($strResource, 0, -1);
		}

		// Normalize the path (see #6034)
		$strResource = str_replace('//', '/', $strResource);

		// The resource does not exist or lies outside the upload directory
		if ($strResource == '' || strncmp($strResource,  $strUploadPath, strlen($strUploadPath)) !== 0 || !file_exists(TL_ROOT . '/' . $strResource))
		{
			throw new \InvalidArgumentException("Invalid resource $strResource");
		}

		$arrPaths    = array();
		$arrChunks   = explode('/', $strResource);
		$strPath     = array_shift($arrChunks);
		$arrPids     = array($strPath => null);
		$arrUpdate   = array($strResource);
		$objDatabase = \Database::getInstance();

		// Build the paths
		while (count($arrChunks))
		{
			$strPath .= '/' . array_shift($arrChunks);
			$arrPaths[] = $strPath;
		}

		unset($arrChunks);

		$objModel  = null;
		$objModels = \FilesModel::findMultipleByPaths($arrPaths);

		// Unset the entries in $arrPaths if the DB entry exists
		if ($objModels !== null)
		{
			while ($objModels->next())
			{
				if (($i = array_search($objModels->path, $arrPaths)) !== false)
				{
					unset($arrPaths[$i]);
					$arrPids[$objModels->path] = $objModels->uuid;
				}

				// Store the model if it exists
				if ($objModels->path == $strResource)
				{
					$objModel = $objModels->current();
				}
			}
		}

		// Return the model if it exists already
		if (empty($arrPaths))
		{
			return $objModel;
		}

		$arrPaths = array_values($arrPaths);

		// If the resource is a folder, also add its contents
		if (is_dir(TL_ROOT . '/' . $strResource))
		{
			/** @var \SplFileInfo[] $objFiles */
			$objFiles = new \RecursiveIteratorIterator(
				new \Filter\SyncExclude(
					new \RecursiveDirectoryIterator(
						TL_ROOT . '/' . $strResource,
						\FilesystemIterator::UNIX_PATHS|\FilesystemIterator::FOLLOW_SYMLINKS|\FilesystemIterator::SKIP_DOTS
					)
				), \RecursiveIteratorIterator::SELF_FIRST
			);

			// Add the relative path
			foreach ($objFiles as $objFile)
			{
				$strRelpath = str_replace(TL_ROOT . '/', '', $objFile->getPathname());

				if ($objFile->isDir())
				{
					$arrUpdate[] = $strRelpath;
				}

				$arrPaths[] = $strRelpath;
			}
		}

		$objReturn = null;

		// Create the new resources
		foreach ($arrPaths as $strPath)
		{
			$strParent = dirname($strPath);

			// The parent ID should be in $arrPids
			// Do not use isset() here, because the PID can be null
			if (array_key_exists($strParent, $arrPids))
			{
				$strPid = $arrPids[$strParent];
			}
			else
			{
				throw new \Exception("No parent entry for $strParent");
			}

			// Create the file or folder
			if (is_file(TL_ROOT . '/' . $strPath))
			{
				$objFile = new \File($strPath, true);

				$objModel = new \FilesModel();
				$objModel->pid       = $strPid;
				$objModel->tstamp    = time();
				$objModel->name      = $objFile->name;
				$objModel->type      = 'file';
				$objModel->path      = $objFile->path;
				$objModel->extension = $objFile->extension;
				$objModel->hash      = $objFile->hash;
				$objModel->uuid      = $objDatabase->getUuid();
				$objModel->save();

				$arrPids[$objFile->path] = $objModel->uuid;
			}
			else
			{
				$objFolder = new \Folder($strPath);

				$objModel = new \FilesModel();
				$objModel->pid       = $strPid;
				$objModel->tstamp    = time();
				$objModel->name      = $objFolder->name;
				$objModel->type      = 'folder';
				$objModel->path      = $objFolder->path;
				$objModel->extension = '';
				$objModel->hash      = $objFolder->hash;
				$objModel->uuid      = $objDatabase->getUuid();
				$objModel->save();

				$arrPids[$objFolder->path] = $objModel->uuid;
			}

			// Store the model to be returned (see #5979)
			if ($objModel->path == $strResource)
			{
				$objReturn = $objModel;
			}
		}

		// Update the folder hashes
		if ($blnUpdateFolders)
		{
			static::updateFolderHashes($arrUpdate);
		}

		return $objReturn;
	}


	/**
	 * Moves a file or folder to a new location
	 *
	 * @param string $strSource      The source path
	 * @param string $strDestination The target path
	 *
	 * @return \FilesModel The files model
	 */
	public static function moveResource($strSource, $strDestination)
	{
		$objFile = \FilesModel::findByPath($strSource);

		// If there is no entry, directly add the destination
		if ($objFile === null)
		{
			$objFile = static::addResource($strDestination);
		}

		$strFolder = dirname($strDestination);

		// Set the new parent ID
		if ($strFolder == \Config::get('uploadPath'))
		{
			$objFile->pid = null;
		}
		else
		{
			$objFolder = \FilesModel::findByPath($strFolder);

			if ($objFolder === null)
			{
				$objFolder = static::addResource($strFolder);
			}

			$objFile->pid = $objFolder->uuid;
		}

		// Save the resource
		$objFile->path = $strDestination;
		$objFile->name = basename($strDestination);
		$objFile->save();

		// Update all child records
		if ($objFile->type == 'folder')
		{
			$objFiles = \FilesModel::findMultipleByBasepath($strSource . '/');

			if ($objFiles !== null)
			{
				while ($objFiles->next())
				{
					$objFiles->path = preg_replace('@^' . $strSource . '/@', $strDestination . '/', $objFiles->path);
					$objFiles->save();
				}
			}
		}

		// Update the MD5 hash of the parent folders
		if (($strPath = dirname($strSource)) != \Config::get('uploadPath'))
		{
			static::updateFolderHashes($strPath);
		}
		if (($strPath = dirname($strDestination)) != \Config::get('uploadPath'))
		{
			static::updateFolderHashes($strPath);
		}

		return $objFile;
	}


	/**
	 * Copies a file or folder to a new location
	 *
	 * @param string $strSource      The source path
	 * @param string $strDestination The target path
	 *
	 * @return \FilesModel The files model
	 */
	public static function copyResource($strSource, $strDestination)
	{
		$objDatabase = \Database::getInstance();
		$objFile = \FilesModel::findByPath($strSource);

		// Add the source entry
		if ($objFile === null)
		{
			$objFile = static::addResource($strSource);
		}

		$strFolder = dirname($strDestination);

		/** @var \FilesModel $objNewFile */
		$objNewFile = clone $objFile->current();

		// Set the new parent ID
		if ($strFolder == \Config::get('uploadPath'))
		{
			$objNewFile->pid = null;
		}
		else
		{
			$objFolder = \FilesModel::findByPath($strFolder);

			if ($objFolder === null)
			{
				$objFolder = static::addResource($strFolder);
			}

			$objNewFile->pid = $objFolder->uuid;
		}

		// Save the resource
		$objNewFile->tstamp = time();
		$objNewFile->uuid   = $objDatabase->getUuid();
		$objNewFile->path   = $strDestination;
		$objNewFile->name   = basename($strDestination);
		$objNewFile->save();

		// Update all child records
		if ($objFile->type == 'folder')
		{
			$objFiles = \FilesModel::findMultipleByBasepath($strSource . '/');

			if ($objFiles !== null)
			{
				while ($objFiles->next())
				{
					/**@var \FilesModel $objNew */
					$objNew = clone $objFiles->current();

					$objNew->pid    = $objNewFile->uuid;
					$objNew->tstamp = time();
					$objNew->uuid   = $objDatabase->getUuid();
					$objNew->path   = str_replace($strSource . '/', $strDestination . '/', $objFiles->path);
					$objNew->save();
				}
			}
		}

		// Update the MD5 hash of the parent folders
		if (($strPath = dirname($strSource)) != \Config::get('uploadPath'))
		{
			static::updateFolderHashes($strPath);
		}
		if (($strPath = dirname($strDestination)) != \Config::get('uploadPath'))
		{
			static::updateFolderHashes($strPath);
		}

		return $objNewFile;
	}


	/**
	 * Removes a file or folder
	 *
	 * @param string $strResource The path to the file or folder
	 */
	public static function deleteResource($strResource)
	{
		$objModel = \FilesModel::findByPath($strResource);

		// Remove the resource
		if ($objModel !== null)
		{
			$objModel->delete();
		}

		// Look for subfolders and files
		$objFiles = \FilesModel::findMultipleByBasepath($strResource . '/');

		// Remove subfolders and files as well
		if ($objFiles !== null)
		{
			while ($objFiles->next())
			{
				$objFiles->delete();
			}
		}

		static::updateFolderHashes(dirname($strResource));
	}


	/**
	 * Update the hashes of all parent folders of a resource
	 *
	 * @param mixed $varResource A path or an array of paths to update
	 */
	public static function updateFolderHashes($varResource)
	{
		$arrPaths  = array();

		if (!is_array($varResource))
		{
			$varResource = array($varResource);
		}

		foreach ($varResource as $strResource)
		{
			$arrChunks = explode('/', $strResource);
			$strPath   = array_shift($arrChunks);

			// Do not check files
			if (is_file(TL_ROOT . '/' . $strResource))
			{
				array_pop($arrChunks);
			}

			// Build the paths
			while (count($arrChunks))
			{
				$strPath .= '/' . array_shift($arrChunks);
				$arrPaths[] = $strPath;
			}

			unset($arrChunks);
		}

		$arrPaths = array_values(array_unique($arrPaths));

		// Store the hash of each folder
		foreach (array_reverse($arrPaths) as $strPath)
		{
			$objFolder = new \Folder($strPath);
			$objModel  = \FilesModel::findByPath($strPath);

			// The DB entry does not yet exist
			if ($objModel === null)
			{
				$objModel = static::addResource($strPath, false);
			}

			$objModel->hash = $objFolder->hash;
			$objModel->save();
		}
	}


	/**
	 * Synchronize the file system with the database
	 *
	 * @return string The path to the synchronization log file
	 *
	 * @throws \Exception If a parent ID entry is missing
	 */
	public static function syncFiles()
	{
		@ini_set('max_execution_time', 0);

		// Consider the suhosin.memory_limit (see #7035)
		if (extension_loaded('suhosin'))
		{
			if ($limit = ini_get('suhosin.memory_limit'))
			{
				@ini_set('memory_limit', $limit);
			}
		}
		else
		{
			@ini_set('memory_limit', -1);
		}

		$objDatabase = \Database::getInstance();

		// Lock the files table
		$objDatabase->lockTables(array('tl_files'=>'WRITE'));

		// Reset the "found" flag
		$objDatabase->query("UPDATE tl_files SET found=''");

		/** @var \SplFileInfo[] $objFiles */
		$objFiles = new \RecursiveIteratorIterator(
			new \Filter\SyncExclude(
				new \RecursiveDirectoryIterator(
					TL_ROOT . '/' . \Config::get('uploadPath'),
					\FilesystemIterator::UNIX_PATHS|\FilesystemIterator::FOLLOW_SYMLINKS|\FilesystemIterator::SKIP_DOTS
				)
			), \RecursiveIteratorIterator::SELF_FIRST
		);

		$strLog = 'system/tmp/' . md5(uniqid(mt_rand(), true));

		// Open the log file
		$objLog = new \File($strLog, true);
		$objLog->truncate();

		$arrModels = array();

		// Create or update the database entries
		foreach ($objFiles as $objFile)
		{
			$strRelpath = str_replace(TL_ROOT . '/', '', $objFile->getPathname());

			// Get all subfiles in a single query
			if ($objFile->isDir())
			{
				$objSubfiles = \FilesModel::findMultipleFilesByFolder($strRelpath);

				if ($objSubfiles !== null)
				{
					while ($objSubfiles->next())
					{
						$arrModels[$objSubfiles->path] = $objSubfiles->current();
					}
				}
			}

			// Get the model
			if (isset($arrModels[$strRelpath]))
			{
				$objModel = $arrModels[$strRelpath];
			}
			else
			{
				$objModel = \FilesModel::findByPath($strRelpath);
			}

			if ($objModel === null)
			{
				// Add a log entry
				$objLog->append("[Added] $strRelpath");

				// Get the parent folder
				$strParent = dirname($strRelpath);

				// Get the parent ID
				if ($strParent == \Config::get('uploadPath'))
				{
					$strPid = null;
				}
				else
				{
					$objParent = \FilesModel::findByPath($strParent);

					if ($objParent === null)
					{
						throw new \Exception("No parent entry for $strParent");
					}

					$strPid = $objParent->uuid;
				}

				// Create the file or folder
				if (is_file(TL_ROOT . '/' . $strRelpath))
				{
					$objFile = new \File($strRelpath, true);

					$objModel = new \FilesModel();
					$objModel->pid       = $strPid;
					$objModel->tstamp    = time();
					$objModel->name      = $objFile->name;
					$objModel->type      = 'file';
					$objModel->path      = $objFile->path;
					$objModel->extension = $objFile->extension;
					$objModel->found     = 2;
					$objModel->hash      = $objFile->hash;
					$objModel->uuid      = $objDatabase->getUuid();
					$objModel->save();
				}
				else
				{
					$objFolder = new \Folder($strRelpath);

					$objModel = new \FilesModel();
					$objModel->pid       = $strPid;
					$objModel->tstamp    = time();
					$objModel->name      = $objFolder->name;
					$objModel->type      = 'folder';
					$objModel->path      = $objFolder->path;
					$objModel->extension = '';
					$objModel->found     = 2;
					$objModel->hash      = $objFolder->hash;
					$objModel->uuid      = $objDatabase->getUuid();
					$objModel->save();
				}
			}
			else
			{
				// Check whether the MD5 hash has changed
				$objResource = $objFile->isDir() ? new \Folder($strRelpath) : new \File($strRelpath, true);
				$strType = ($objModel->hash != $objResource->hash) ? 'Changed' : 'Unchanged';

				// Add a log entry
				$objLog->append("[$strType] $strRelpath");

				// Update the record
				$objModel->found = 1;
				$objModel->hash  = $objResource->hash;
				$objModel->save();
			}
		}

		// Check for left-over entries in the DB
		$objFiles = \FilesModel::findByFound('');

		if ($objFiles !== null)
		{
			$arrMapped = array();
			$arrPidUpdate = array();

			/** @var \Model\Collection|\FilesModel $objFiles */
			while ($objFiles->next())
			{
				$objFound = \FilesModel::findBy(array('hash=?', 'found=2'), $objFiles->hash);

				if ($objFound !== null)
				{
					// Check for matching file names if the result is ambiguous (see #5644)
					if ($objFound->count() > 1)
					{
						while ($objFound->next())
						{
							if ($objFound->name == $objFiles->name)
							{
								$objFound = $objFound->current();
								break;
							}
						}
					}

					// If another file has been mapped already, delete the entry (see #6008)
					if (in_array($objFound->path, $arrMapped))
					{
						$objLog->append("[Deleted] {$objFiles->path}");
						$objFiles->delete();
						continue;
					}

					$arrMapped[] = $objFound->path;

					// Store the PID change
					if ($objFiles->type == 'folder')
					{
						$arrPidUpdate[$objFound->uuid] = $objFiles->uuid;
					}

					// Add a log entry BEFORE changing the object
					$objLog->append("[Moved] {$objFiles->path} to {$objFound->path}");

					// Update the original entry
					$objFiles->pid    = $objFound->pid;
					$objFiles->tstamp = $objFound->tstamp;
					$objFiles->name   = $objFound->name;
					$objFiles->type   = $objFound->type;
					$objFiles->path   = $objFound->path;
					$objFiles->found  = 1;

					// Delete the newer (duplicate) entry
					$objFound->delete();

					// Then save the modified original entry (prevents duplicate key errors)
					$objFiles->save();
				}
				else
				{
					// Add a log entry BEFORE changing the object
					$objLog->append("[Deleted] {$objFiles->path}");

					// Delete the entry if the resource has gone
					$objFiles->delete();
				}
			}

			// Update the PID of the child records
			if (!empty($arrPidUpdate))
			{
				foreach ($arrPidUpdate as $from=>$to)
				{
					$objChildren = \FilesModel::findByPid($from);

					if ($objChildren !== null)
					{
						while ($objChildren->next())
						{
							$objChildren->pid = $to;
							$objChildren->save();
						}
					}
				}
			}
		}

		// Close the log file
		$objLog->close();

		// Reset the found flag
		$objDatabase->query("UPDATE tl_files SET found=1 WHERE found=2");

		// Unlock the tables
		$objDatabase->unlockTables();

		// Return the path to the log file
		return $strLog;
	}
}
