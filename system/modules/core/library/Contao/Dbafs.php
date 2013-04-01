<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


/**
 * Handles the database assisted file system (DBAFS)
 *
 * The class functions as an adapter for the PHP $_SESSION array and separates
 * back end from front end session data.
 *
 * Usage:
 *
 *     $session = Session::getInstance();
 *     $session->set('foo', 'bar');
 *     echo $session::->('foo');
 *
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class Dbafs
{

	/**
	 * Adds a file or folder with its parent folders
	 *
	 * @param string $strResource The path to the file or folder
	 *
	 * @return \FilesModel The files model
	 *
	 * @throws \Exception                If a parent ID entry is missing
	 * @throws \InvalidArgumentException If the resource is outside the upload folder
	 */
	public static function addResource($strResource)
	{
		$strUploadPath = $GLOBALS['TL_CONFIG']['uploadPath'] . '/';

		// The resource does not exist or lies outside the upload directory
		if ($strResource == '' || strncmp($strResource,  $strUploadPath, strlen($strUploadPath)) !== 0 || !file_exists(TL_ROOT . '/' . $strResource))
		{
			throw new \InvalidArgumentException("Invalid resource $strResource");
		}

		$arrPaths  = array();
		$arrChunks = explode('/', $strResource);
		$strPath   = array_shift($arrChunks);
		$arrPids   = array($strPath=>0);

		// Build the paths
		while (count($arrChunks))
		{
			$strPath .= '/' . array_shift($arrChunks);
			$arrPaths[] = $strPath;
		}

		unset($arrChunks);

		$objModel  = null;
		$objModels = \FilesModel::findMultipleByPaths($arrPaths, array('cached'=>true));

		// Unset the entries in $arrPaths if the DB entry exists
		if ($objModels !== null)
		{
			while ($objModels->next())
			{
				if (($i = array_search($objModels->path, $arrPaths)) !== false)
				{
					unset($arrPaths[$i]);
					$arrPids[$objModels->path] = $objModels->id;
				}
			}

			// Store the model if it exists
			if ($objModels->path == $strResource)
			{
				$objModel = $objModels->current();
			}
		}

		// Create the new resources
		foreach ($arrPaths as $strPath)
		{
			$strParent = dirname($strPath);

			// The parent ID should be in $arrPids
			if (isset($arrPids[$strParent]))
			{
				$intPid = $arrPids[$strParent];
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
				$objModel->pid       = $intPid;
				$objModel->tstamp    = time();
				$objModel->name      = $objFile->name;
				$objModel->type      = 'file';
				$objModel->path      = $objFile->path;
				$objModel->extension = $objFile->extension;
				$objModel->hash      = $objFile->hash;
				$objModel->save();

				$arrPids[$objFile->path] = $objModel->id;
			}
			else
			{
				$objFolder = new \Folder($strPath);

				$objModel = new \FilesModel();
				$objModel->pid       = $intPid;
				$objModel->tstamp    = time();
				$objModel->name      = $objFolder->name;
				$objModel->type      = 'folder';
				$objModel->path      = $objFolder->path;
				$objModel->extension = '';
				$objModel->hash      = $objFolder->hash;
				$objModel->save();

				$arrPids[$objFolder->path] = $objModel->id;
			}
		}

		// If the resource is a folder, also add its contents
		if ($objModel->type == 'folder')
		{
			foreach (scan(TL_ROOT . '/' . $strResource) as $strPath)
			{
				static::addResource($strResource . '/' . $strPath);
			}
		}

		// Update the folder hashes
		static::updateFolderHashes($strResource);

		// The last model is the resource itself
		return $objModel;
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
		$objFile = \FilesModel::findByPath($strSource, array('cached'=>true));

		// If there is no entry, directly add the destination
		if ($objFile === null)
		{
			$objFile = static::addResource($strDestination);
		}

		$strFolder = dirname($strDestination);

		// Set the new parent ID
		if ($strFolder == $GLOBALS['TL_CONFIG']['uploadPath'])
		{
			$objFile->pid = 0;
		}
		else
		{
			$objFolder = \FilesModel::findByPath($strFolder, array('cached'=>true));

			if ($objFolder === null)
			{
				$objFolder = static::addResource($strFolder);
			}

			$objFile->pid = $objFolder->id;
		}

		// Save the resource
		$objFile->path = $strDestination;
		$objFile->name = basename($strDestination);
		$objFile->save();

		// Update all child records
		if ($objFile->type == 'folder')
		{
			$objFiles = \FilesModel::findMultipleByBasepath($strSource . '/', array('cached'=>true));

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
		if (($strPath = dirname($strSource)) != $GLOBALS['TL_CONFIG']['uploadPath'])
		{
			static::updateFolderHashes($strPath);
		}
		if (($strPath = dirname($strDestination)) != $GLOBALS['TL_CONFIG']['uploadPath'])
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
		$objFile = \FilesModel::findByPath($strSource, array('cached'=>true));

		// Add the source entry
		if ($objFile === null)
		{
			$objFile = static::addResource($strSource);
		}

		$strFolder = dirname($strDestination);
		$objNewFile = clone $objFile->current();

		// Set the new parent ID
		if ($strFolder == $GLOBALS['TL_CONFIG']['uploadPath'])
		{
			$objNewFile->pid = 0;
		}
		else
		{
			$objFolder = \FilesModel::findByPath($strFolder, array('cached'=>true));

			if ($objFolder === null)
			{
				$objFolder = static::addResource($strFolder);
			}

			$objNewFile->pid = $objFolder->id;
		}

		// Save the resource
		$objNewFile->tstamp = time();
		$objNewFile->path   = $strDestination;
		$objNewFile->name   = basename($strDestination);
		$objNewFile->save();

		// Update all child records
		if ($objFile->type == 'folder')
		{
			$objFiles = \FilesModel::findMultipleByBasepath($strSource . '/', array('cached'=>true));

			if ($objFiles !== null)
			{
				while ($objFiles->next())
				{
					$objNew = clone $objFiles->current();

					$objNew->pid    = $objNewFile->id;
					$objNew->tstamp = time();
					$objNew->path   = str_replace($strSource . '/', $strDestination . '/', $objFiles->path);
					$objNew->save();
				}
			}
		}

		// Update the MD5 hash of the parent folders
		if (($strPath = dirname($strSource)) != $GLOBALS['TL_CONFIG']['uploadPath'])
		{
			static::updateFolderHashes($strPath);
		}
		if (($strPath = dirname($strDestination)) != $GLOBALS['TL_CONFIG']['uploadPath'])
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
		$objModel = \FilesModel::findByPath($strResource, array('uncached'=>true));

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
	 * @param string $strResource The path to the file or folder
	 */
	public static function updateFolderHashes($strResource)
	{
		$arrPaths  = array();
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

		// Store the hash of each folder
		foreach (array_reverse($arrPaths) as $strPath)
		{
			$objFolder = new \Folder($strPath);
			$objModel  = \FilesModel::findByPath($strPath, array('cached'=>true));

			// The DB entry does not yet exist
			if ($objModel === null)
			{
				$objModel = static::addResource($strPath);
			}

			$objModel->hash = $objFolder->hash;
			$objModel->save();
		}
	}


	/**
	 * Synchronize the file system with the database
	 */
	public static function syncFiles()
	{
		$objDatabase = \Database::getInstance();

		// Lock the files table
		$objDatabase->lockTables(array('tl_files'));

		// Reset the "found" flag
		$objDatabase->query("UPDATE tl_files SET found=''");

		// Get a filtered list of all files
		$objFiles = new \RecursiveIteratorIterator(
			new \Dbafs\Filter(
				new \RecursiveDirectoryIterator(
					TL_ROOT . '/' . $GLOBALS['TL_CONFIG']['uploadPath'],
					\FilesystemIterator::UNIX_PATHS|\FilesystemIterator::FOLLOW_SYMLINKS|\FilesystemIterator::SKIP_DOTS
				)
			), \RecursiveIteratorIterator::SELF_FIRST
		);

		// Open the log file
		$objLog = new \File('system/logs/sync.log', true);
		$objLog->truncate();

		// Create or update the database entries
		foreach ($objFiles as $objFile)
		{
			$strRelpath = str_replace(TL_ROOT . '/', '', $objFile->getPathname());
			$objModel   = \FilesModel::findByPath($strRelpath);

			if ($objModel === null)
			{
				// Add a log entry
				$objLog->append("[Added] $strRelpath");

				// Add the resource
				static::addResource($strRelpath);
			}
			else
			{
				// Check whether the MD5 hash has changed
				$objResource = $objFile->isDir() ? new \Folder($strRelpath) : new \File($strRelpath);
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
			while ($objFiles->next())
			{
				$objFound = \FilesModel::findBy(array('hash=?', 'found=1'), $objFiles->hash);

				if ($objFound !== null)
				{
					// Add a log entry BEFORE changing the object
					$objLog->append("[Moved] {$objFiles->path} to {$objFound->path}");

					// Update the original entry
					$objFiles->pid    = $objFound->pid;
					$objFiles->tstamp = $objFound->tstamp;
					$objFiles->name   = $objFound->name;
					$objFiles->type   = $objFound->type;
					$objFiles->path   = $objFound->path;

					// Update the PID of the child records
					if ($objFound->type == 'folder')
					{
						$objChildren = \FilesModel::findByPid($objFound->id);

						if ($objChildren !== null)
						{
							while ($objChildren->next())
							{
								$objChildren->pid = $objFiles->id;
								$objChildren->save();
							}
						}
					}

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
		}

		// Close the log file
		$objLog->close();

		// Unlock the tables
		$objDatabase->unlockTables();
	}
}
