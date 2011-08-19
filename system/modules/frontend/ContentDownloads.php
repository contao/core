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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ContentDownloads
 *
 * Front end content element "downloads".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ContentDownloads extends ContentElement
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
		if (!is_array($this->multiSRC) || count($this->multiSRC) < 1)
		{
			return '';
		}

		$file = $this->Input->get('file', true);

		// Send the file to the browser
		if ($file != '' && (in_array($file, $this->multiSRC) || in_array(dirname($file), $this->multiSRC)) && !preg_match('/^meta(_[a-z]{2})?\.txt$/', basename($file)))
		{
			$this->sendFileToBrowser($file);
		}

		return parent::generate();
	}


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		$files = array();
		$auxDate = array();

		$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

		// Get all files
		foreach ($this->multiSRC as $file)
		{
			if (isset($files[$file]) || !file_exists(TL_ROOT . '/' . $file))
			{
				continue;
			}

			// Single files
			if (is_file(TL_ROOT . '/' . $file))
			{
				$objFile = new File($file);

				if (in_array($objFile->extension, $allowedDownload) && !preg_match('/^meta(_[a-z]{2})?\.txt$/', basename($file)))
				{
					$this->parseMetaFile(dirname($file), true);
					$arrMeta = $this->arrMeta[$objFile->basename];

					if ($arrMeta[0] == '')
					{
						$arrMeta[0] = specialchars($objFile->basename);
					}

					$files[$file] = array
					(
						'link' => $arrMeta[0],
						'title' => $arrMeta[0],
						'href' => $this->Environment->request . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($this->Environment->request, '?') !== false) ? '&amp;' : '?') . 'file=' . $this->urlEncode($file),
						'caption' => $arrMeta[2],
						'filesize' => $this->getReadableSize($objFile->filesize, 1),
						'icon' => TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/' . $objFile->icon,
						'mime' => $objFile->mime,
						'meta' => $arrMeta,
						'extension' => $objFile->extension
					);

					$auxDate[] = $objFile->mtime;
				}

				continue;
			}

			$subfiles = scan(TL_ROOT . '/' . $file);
			$this->parseMetaFile($file);

			// Folders
			foreach ($subfiles as $subfile)
			{
				if (is_dir(TL_ROOT . '/' . $file . '/' . $subfile))
				{
					continue;
				}

				$objFile = new File($file . '/' . $subfile);

				if (in_array($objFile->extension, $allowedDownload) && !preg_match('/^meta(_[a-z]{2})?\.txt$/', basename($subfile)))
				{
					$arrMeta = $this->arrMeta[$objFile->basename];

					if ($arrMeta[0] == '')
					{
						$arrMeta[0] = specialchars($objFile->basename);
					}

					$files[$file . '/' . $subfile] = array
					(
						'link' => $arrMeta[0],
						'title' => $arrMeta[0],
						'href' => $this->Environment->request . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($this->Environment->request, '?') !== false) ? '&amp;' : '?') . 'file=' . $this->urlEncode($file . '/' . $subfile),
						'caption' => $arrMeta[2],
						'filesize' => $this->getReadableSize($objFile->filesize, 1),
						'icon' => 'system/themes/' . $this->getTheme() . '/images/' . $objFile->icon,
						'meta' => $arrMeta,
						'extension' => $objFile->extension
					);

					$auxDate[] = $objFile->mtime;
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

			case 'meta':
				$arrFiles = array();
				foreach ($this->arrAux as $k)
				{
					if (strlen($k))
					{
						$arrFiles[] = $files[$k];
					}
				}
				$files = $arrFiles;
				break;
		}

		$this->Template->files = array_values($files);
	}
}

?>