<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleRandomImage
 *
 * Front end module "random image".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleRandomImage extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_random_image';


	/**
	 * Check the source folder
	 * @return string
	 */
	public function generate()
	{
		$this->multiSRC = deserialize($this->multiSRC);

		if (!is_array($this->multiSRC))
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$images = array();

		foreach ($this->multiSRC as $file)
		{
			if (strncmp($file, '.', 1) === 0)
			{
				continue;
			}

			// Directory
			if (is_dir(TL_ROOT . '/' . $file))
			{
				$subfiles = scan(TL_ROOT . '/' . $file);
				$this->parseMetaFile($file);

				foreach ($subfiles as $subfile)
				{
					if (strncmp($subfile, '.', 1) === 0 || is_dir(TL_ROOT . '/' . $file . '/' . $subfile))
					{
						continue;
					}

					$objFile = new File($file . '/' . $subfile);

					if ($objFile->isGdImage)
					{
						$images[] = $file . '/' . $subfile;
					}
				}

				continue;
			}

			// File
			if (is_file(TL_ROOT . '/' . $file))
			{
				$objFile = new File($file);
				$this->parseMetaFile(dirname($file), true);

				if ($objFile->isGdImage)
				{
					$images[] = $file;
				}
			}
		}

		$images = array_unique($images);

		if (!is_array($images) || count($images) < 1)
		{
			return;
		}

		$i = mt_rand(0, (count($images)-1));
		$size = deserialize($this->imgSize);

		$objImage = new File($images[$i]);

		// Adjust image size in the back end
		if (TL_MODE == 'BE' && $size[0] > 640)
		{
			$size[0] = 640;
			$size[1] = floor(640 * $size[1] / $size[0]);
		}

		$src = $this->getImage($this->urlEncode($images[$i]), $size[0], $size[1]);

		if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
		{
			$this->Template->imgSize = ' ' . $imgSize[3];
		}

		$this->Template->src = $src;
		$this->Template->href = $images[$i];
		$this->Template->width = $objImage->width;
		$this->Template->height = $objImage->height;
		$this->Template->alt = (strlen($this->arrMeta[$objImage->basename][0]) ? $this->arrMeta[$objImage->basename][0] : ucfirst(str_replace('_', ' ', preg_replace('/^[0-9]+_/', '', $objImage->filename))));
		$this->Template->link = (strlen($this->arrMeta[$objImage->basename][1]) ? $this->arrMeta[$objImage->basename][1] : '');

		// Image caption
		if ($this->useCaption)
		{
			$this->Template->caption = (strlen($this->arrMeta[$objImage->basename][2]) ? $this->arrMeta[$objImage->basename][2] : specialchars($objImage->basename));
		}
	}
}

?>