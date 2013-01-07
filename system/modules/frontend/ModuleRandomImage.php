<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleRandomImage
 *
 * Front end module "random image".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
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
	 * Generate the module
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

		if (!is_array($images) || empty($images))
		{
			return;
		}

		$i = mt_rand(0, (count($images)-1));

		$objImage = new File($images[$i]);
		$arrMeta = $this->arrMeta[$objImage->basename];

		if ($arrMeta[0] == '')
		{
			$arrMeta[0] = str_replace('_', ' ', preg_replace('/^[0-9]+_/', '', $objImage->filename));
		}

		$arrImage = array();

		$arrImage['size'] = $this->imgSize;
		$arrImage['singleSRC'] = $objImage->value;
		$arrImage['alt'] = specialchars($arrMeta[0]);
		$arrImage['imageUrl'] = $arrMeta[1];
		$arrImage['fullsize'] = $this->fullsize;

		// Image caption
		if ($this->useCaption)
		{
			if ($arrMeta[2] == '')
			{
				$arrMeta[2] = $arrMeta[0];
			}

			$arrImage['caption'] = $arrMeta[2];
		}

		$this->addImageToTemplate($this->Template, $arrImage);
	}
}

?>