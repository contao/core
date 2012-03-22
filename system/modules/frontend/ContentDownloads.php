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
 * @package    Frontend
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ContentDownloads
 *
 * Front end content element "downloads".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ContentDownloads extends \ContentElement
{

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
		$this->multiSRC = deserialize($this->multiSRC);

		// Use the home directory of the current user as file source
		if ($this->useHomeDir && FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			
			if ($this->User->assignDir && is_dir(TL_ROOT . '/' . $this->User->homeDir))
			{
				$this->multiSRC = array($this->User->homeDir);
			}
		}

		// Return if there are no files
		if (!is_array($this->multiSRC) || empty($this->multiSRC))
		{
			return '';
		}

		$file = $this->Input->get('file', true);

		// Send the file to the browser
		# FIXME: $this->multiSRC now has IDs
		if ($file != '' && (in_array($file, $this->multiSRC) || in_array(dirname($file), $this->multiSRC)) && !preg_match('/^meta(_[a-z]{2})?\.txt$/', basename($file)))
		{
			$this->sendFileToBrowser($file);
		}

		return parent::generate();
	}


	/**
	 * Generate the content element
	 * @return void
	 */
	protected function compile()
	{
		$files = array();
		$auxDate = array();
		$auxId = array();

		// Get the file entries from the database
		$objFiles = \FilesCollection::findMultipleByIds($this->multiSRC);

		if ($objFiles === null)
		{
			// Check for version 3 format
			foreach ($this->multiSRC as $val)
			{
				if (!is_numeric($val))
				{
					$this->Template->files = array();
					$this->Template->v2warning = '<p class="error">This element still uses the old Contao 2 multiSRC format. Did you upgrade the database?</p>';
					break;
				}
			}

			return;
		}

		global $objPage;
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
					'href'      => $this->Environment->request . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($this->Environment->request, '?') !== false) ? '&amp;' : '?') . 'file=' . $this->urlEncode($objFiles->path),
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
				$objSubfiles = \FilesCollection::findBy('pid', $objFiles->id);

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
						'href'      => $this->Environment->request . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($this->Environment->request, '?') !== false) ? '&amp;' : '?') . 'file=' . $this->urlEncode($objFiles->path),
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
