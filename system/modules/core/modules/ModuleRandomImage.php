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
 * Front end module "random image".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleRandomImage extends \Module
{

	/**
	 * Files object
	 * @var \Model\Collection|\FilesModel
	 */
	protected $objFiles;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_random_image';


	/**
	 * Check the source folder
	 *
	 * @return string
	 */
	public function generate()
	{
		$this->multiSRC = deserialize($this->multiSRC);

		if (!is_array($this->multiSRC) || empty($this->multiSRC))
		{
			return '';
		}

		$this->objFiles = \FilesModel::findMultipleByUuids($this->multiSRC);

		if ($this->objFiles === null)
		{
			if (!\Validator::isUuid($this->multiSRC[0]))
			{
				return '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
			}

			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		/** @var \PageModel $objPage */
		global $objPage;

		$images = array();
		$objFiles = $this->objFiles;

		// Get all images
		while ($objFiles->next())
		{
			// Continue if the files has been processed or does not exist
			if (isset($images[$objFiles->path]) || !file_exists(TL_ROOT . '/' . $objFiles->path))
			{
				continue;
			}

			// Single files
			if ($objFiles->type == 'file')
			{
				$objFile = new \File($objFiles->path, true);

				if (!$objFile->isImage)
				{
					continue;
				}

				$arrMeta = $this->getMetaData($objFiles->meta, $objPage->language);

				// Use the file name as title if none is given
				if ($arrMeta['title'] == '')
				{
					$arrMeta['title'] = specialchars($objFile->basename);
				}

				// Add the image
				$images[$objFiles->path] = array
				(
					'id'        => $objFiles->id,
					'name'      => $objFile->basename,
					'singleSRC' => $objFiles->path,
					'alt'       => $arrMeta['title'],
					'imageUrl'  => $arrMeta['link'],
					'caption'   => $arrMeta['caption']
				);
			}

			// Folders
			else
			{
				$objSubfiles = \FilesModel::findByPid($objFiles->uuid);

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

					$objFile = new \File($objSubfiles->path, true);

					if (!$objFile->isImage)
					{
						continue;
					}

					$arrMeta = $this->getMetaData($objSubfiles->meta, $objPage->language);

					// Use the file name as title if none is given
					if ($arrMeta['title'] == '')
					{
						$arrMeta['title'] = specialchars($objFile->basename);
					}

					// Add the image
					$images[$objSubfiles->path] = array
					(
						'id'        => $objSubfiles->id,
						'name'      => $objFile->basename,
						'singleSRC' => $objSubfiles->path,
						'alt'       => $arrMeta['title'],
						'imageUrl'  => $arrMeta['link'],
						'caption'   => $arrMeta['caption']
					);
				}
			}
		}

		$images = array_values($images);

		if (empty($images))
		{
			return;
		}

		$i = mt_rand(0, (count($images)-1));

		$arrImage = $images[$i];

		$arrImage['size'] = $this->imgSize;
		$arrImage['fullsize'] = $this->fullsize;

		if (!$this->useCaption)
		{
			$arrImage['caption'] = null;
		}
		elseif ($arrImage['caption'] == '')
		{
			$arrImage['caption'] = $arrImage['title'];
		}

		$this->addImageToTemplate($this->Template, $arrImage);
	}
}
