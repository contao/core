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
				$objModel->hash      = '';
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
		foreach ($arrPaths as $strPath)
		{
			$objFolder = new \Folder($strPath);
			$objModel  = \FilesModel::findByPath($strPath, array('cached'=>true));

			// The DB entry does not yet exist
			if ($objModel === null)
			{
				static::addResource($strPath);
			}

			$objModel->hash = $objFolder->hash;
			$objModel->save();
		}
	}
}
