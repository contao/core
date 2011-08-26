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
 * Class ContentGallery
 *
 * Front end content element "gallery".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ContentGallery extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_gallery';


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

		if (!is_array($this->multiSRC) || count($this->multiSRC) < 1)
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		$images = array();
		$auxDate = array();

		// Get all images
		foreach ($this->multiSRC as $file)
		{
			if (isset($images[$file]) || !file_exists(TL_ROOT . '/' . $file))
			{
				continue;
			}

			// Single files
			if (is_file(TL_ROOT . '/' . $file))
			{
				$objFile = new File($file);
				$this->parseMetaFile(dirname($file), true);
				$arrMeta = $this->arrMeta[$objFile->basename];

				if ($arrMeta[0] == '')
				{
					$arrMeta[0] = str_replace('_', ' ', preg_replace('/^[0-9]+_/', '', $objFile->filename));
				}

				if ($objFile->isGdImage)
				{
					$images[$file] = array
					(
						'name' => $objFile->basename,
						'singleSRC' => $file,
						'alt' => $arrMeta[0],
						'imageUrl' => $arrMeta[1],
						'caption' => $arrMeta[2]
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

				if ($objFile->isGdImage)
				{
					$arrMeta = $this->arrMeta[$subfile];

					if ($arrMeta[0] == '')
					{
						$arrMeta[0] = str_replace('_', ' ', preg_replace('/^[0-9]+_/', '', $objFile->filename));
					}

					$images[$file . '/' . $subfile] = array
					(
						'name' => $objFile->basename,
						'singleSRC' => $file . '/' . $subfile,
						'alt' => $arrMeta[0],
						'imageUrl' => $arrMeta[1],
						'caption' => $arrMeta[2]
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
				uksort($images, 'basename_natcasecmp');
				break;

			case 'name_desc':
				uksort($images, 'basename_natcasercmp');
				break;

			case 'date_asc':
				array_multisort($images, SORT_NUMERIC, $auxDate, SORT_ASC);
				break;

			case 'date_desc':
				array_multisort($images, SORT_NUMERIC, $auxDate, SORT_DESC);
				break;

			case 'meta':
				$arrImages = array();
				foreach ($this->arrAux as $k)
				{
					if (strlen($k))
					{
						$arrImages[] = $images[$k];
					}
				}
				$images = $arrImages;
				break;

			case 'random':
				shuffle($images);
				break;
		}

		$images = array_values($images);
		$total = count($images);
		$limit = $total;
		$offset = 0;

		// Pagination
		if ($this->perPage > 0)
		{
			$page = $this->Input->get('page') ? $this->Input->get('page') : 1;
			$offset = ($page - 1) * $this->perPage;
			$limit = min($this->perPage + $offset, $total);

			$objPagination = new Pagination($total, $this->perPage);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		$rowcount = 0;
		$colwidth = floor(100/$this->perRow);
		$intMaxWidth = (TL_MODE == 'BE') ? floor((640 / $this->perRow)) : floor(($GLOBALS['TL_CONFIG']['maxImageWidth'] / $this->perRow));
		$strLightboxId = 'lightbox[lb' . $this->id . ']';
		$body = array();

		// Rows
		for ($i=$offset; $i<$limit; $i=($i+$this->perRow))
		{
			$class_tr = '';

			if ($rowcount == 0)
			{
				$class_tr .= ' row_first';
			}

			if (($i + $this->perRow) >= $limit)
			{
				$class_tr .= ' row_last';
			}

			$class_eo = (($rowcount % 2) == 0) ? ' even' : ' odd';

			// Columns
			for ($j=0; $j<$this->perRow; $j++)
			{
				$class_td = '';

				if ($j == 0)
				{
					$class_td = ' col_first';
				}

				if ($j == ($this->perRow - 1))
				{
					$class_td = ' col_last';
				}

				$objCell = new stdClass();
				$key = 'row_' . $rowcount . $class_tr . $class_eo;

				// Empty cell
				if (!is_array($images[($i+$j)]) || ($j+$i) >= $limit)
				{
					$objCell->class = 'col_'.$j . $class_td;
					$body[$key][$j] = $objCell;

					continue;
				}

				// Add size and margin
				$images[($i+$j)]['size'] = $this->size;
				$images[($i+$j)]['imagemargin'] = $this->imagemargin;
				$images[($i+$j)]['fullsize'] = $this->fullsize;

				$this->addImageToTemplate($objCell, $images[($i+$j)], $intMaxWidth, $strLightboxId);

				// Add column width and class
				$objCell->colWidth = $colwidth . '%';
				$objCell->class = 'col_'.$j . $class_td;

				$body[$key][$j] = $objCell;
			}

			++$rowcount;
		}

		$strTemplate = 'gallery_default';

		// Use a custom template
		if (TL_MODE == 'FE' && $this->galleryTpl != '')
		{
			$strTemplate = $this->galleryTpl;
		}

		$objTemplate = new FrontendTemplate($strTemplate);
		$objTemplate->setData($this->arrData);

		$objTemplate->body = $body;
		$objTemplate->headline = $this->headline; // see #1603

		$this->Template->images = $objTemplate->parse();
	}
}

?>