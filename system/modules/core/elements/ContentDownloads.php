<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ContentDownloads
 *
 * Front end content element "downloads".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ContentDownloads extends \ContentElement
{

	/**
	 * Files object
	 * @var \FilesModel
	 */
	protected $objFiles;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_downloads';


	/**
	 * Return if there are no files
	 * @return string
	 */
	public function generate()
	{
		// Use the home directory of the current user as file source
		if ($this->useHomeDir && FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');

			if ($this->User->assignDir && $this->User->homeDir)
			{
				$this->multiSRC = array($this->User->homeDir);
			}
		}
		else
		{
			$this->multiSRC = deserialize($this->multiSRC);
		}

		// Return if there are no files
		if (!is_array($this->multiSRC) || empty($this->multiSRC))
		{
			return '';
		}

		// Check for version 3 format
		if (!is_numeric($this->multiSRC[0]))
		{
			return '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
		}

		// Get the file entries from the database
		$this->objFiles = \FilesModel::findMultipleByIds($this->multiSRC);

		if ($this->objFiles === null)
		{
			return '';
		}

		$file = \Input::get('file', true);

		// Send the file to the browser and do not send a 404 header (see #4632)
		if ($file != '' && !preg_match('/^meta(_[a-z]{2})?\.txt$/', basename($file)))
		{
			while ($this->objFiles->next())
			{
				if ($file == $this->objFiles->path || dirname($file) == $this->objFiles->path)
				{
					$this->sendFileToBrowser($file);
				}
			}
		}

		return parent::generate();
	}


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		global $objPage;

		$files = array();
		$auxDate = array();
		$auxId = array();

		$objFiles = $this->objFiles;
		$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

		// Get all files
		while ($objFiles->next())
		{
			// Continue if the files has been processed or does not exist
			if (isset($files[$objFiles->path]) || !file_exists(TL_ROOT . '/' . $objFiles->path))
			{
				continue;
			}

			// Single files
			if ($objFiles->type == 'file')
			{
				$objFile = new \File($objFiles->path);

				if (!in_array($objFile->extension, $allowedDownload) || preg_match('/^meta(_[a-z]{2})?\.txt$/', $objFile->basename))
				{
					continue;
				}

				$arrMeta = $this->getMetaData($objFiles->meta, $objPage->language);

				// Use the file name as title if none is given
				if ($arrMeta['title'] == '')
				{
					$arrMeta['title'] = specialchars(str_replace('_', ' ', preg_replace('/^[0-9]+_/', '', $objFile->filename)));
				}

				// Add the image
				$files[$objFiles->path] = array
				(
					'id'        => $objFiles->id,
					'name'      => $objFile->basename,
					'title'     => $arrMeta['title'],
					'link'      => $arrMeta['title'],
					'caption'   => $arrMeta['caption'],
					'href'      => \Environment::get('request') . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos(\Environment::get('request'), '?') !== false) ? '&amp;' : '?') . 'file=' . $this->urlEncode($objFiles->path),
					'filesize'  => $this->getReadableSize($objFile->filesize, 1),
					'icon'      => TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/' . $objFile->icon,
					'mime'      => $objFile->mime,
					'meta'      => $arrMeta,
					'extension' => $objFile->extension,
					'path'      => $objFile->dirname
				);

				$auxDate[] = $objFile->mtime;
				$auxId[] = $objFiles->id;
			}

			// Folders
			else
			{
				$objSubfiles = \FilesModel::findByPid($objFiles->id);

				if ($objSubfiles === null)
				{
					continue;
				}

				while ($objSubfiles->next())
				{
					// Skip subfolders
					if ($objSubfiles->type == 'folder')
					{
						continue;
					}

					$objFile = new \File($objSubfiles->path);

					if (!in_array($objFile->extension, $allowedDownload) || preg_match('/^meta(_[a-z]{2})?\.txt$/', $objFile->basename))
					{
						continue;
					}

					$arrMeta = $this->getMetaData($objSubfiles->meta, $objPage->language);

					// Use the file name as title if none is given
					if ($arrMeta['title'] == '')
					{
						$arrMeta['title'] = specialchars(str_replace('_', ' ', preg_replace('/^[0-9]+_/', '', $objFile->filename)));
					}

					// Add the image
					$files[$objSubfiles->path] = array
					(
						'id'        => $objSubfiles->id,
						'name'      => $objFile->basename,
						'title'     => $arrMeta['title'],
						'link'      => $arrMeta['title'],
						'caption'   => $arrMeta['caption'],
						'href'      => \Environment::get('request') . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos(\Environment::get('request'), '?') !== false) ? '&amp;' : '?') . 'file=' . $this->urlEncode($objSubfiles->path),
						'filesize'  => $this->getReadableSize($objFile->filesize, 1),
						'icon'      => 'system/themes/' . $this->getTheme() . '/images/' . $objFile->icon,
						'mime'      => $objFile->mime,
						'meta'      => $arrMeta,
						'extension' => $objFile->extension,
						'path'      => $objFile->dirname
					);

					$auxDate[] = $objFile->mtime;
					$auxId[] = $objSubfiles->id;
				}
			}
		}

		// Sort array
		switch ($this->sortBy)
		{
			default:
			case 'name_asc':
				uksort($files, 'basename_natcasecmp');
				break;

			case 'name_desc':
				uksort($files, 'basename_natcasercmp');
				break;

			case 'date_asc':
				array_multisort($files, SORT_NUMERIC, $auxDate, SORT_ASC);
				break;

			case 'date_desc':
				array_multisort($files, SORT_NUMERIC, $auxDate, SORT_DESC);
				break;

			case 'meta': // Backwards compatibility
			case 'custom':
				if ($this->orderSRC != '')
				{
					// Turn the order string into an array
					$arrOrder = array_flip(array_map('intval', explode(',', $this->orderSRC)));

					// Move the matching elements to their position in $arrOrder
					foreach ($files as $k=>$v)
					{
						if (isset($arrOrder[$v['id']]))
						{
							$arrOrder[$v['id']] = $v;
							unset($files[$k]);
						}
					}

					// Append the left-over images at the end
					if (!empty($files))
					{
						$arrOrder = array_merge($arrOrder, $files);
					}

					// Remove empty or numeric (not replaced) entries
					foreach ($arrOrder as $k=>$v)
					{
						if ($v == '' || is_numeric($v))
						{
							unset($arrOrder[$k]);
						}
					}

					$files = $arrOrder;
					unset($arrOrder);
				}
				break;

			case 'random':
				shuffle($files);
				break;
		}

		$this->Template->files = array_values($files);
	}
}
