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
 * Class ContentImage
 *
 * Front end content element "image".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ContentImage extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_image';


	/**
	 * Return if the image does not exist
	 * @return string
	 */
	public function generate()
	{
		if (!strlen($this->singleSRC) || !is_file(TL_ROOT . '/' . $this->singleSRC))
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
		// Image link
		if (strlen($this->imageUrl) && TL_MODE == 'FE')
		{
			$this->strTemplate = 'ce_image_link';
			$this->Template = new FrontendTemplate($this->strTemplate);
		}

		// Fullsize view
		elseif ($this->fullsize && TL_MODE == 'FE')
		{
			$this->strTemplate = 'ce_image_fullsize';
			$this->Template = new FrontendTemplate($this->strTemplate);
		}

		$size = deserialize($this->size);
		$arrImageSize = getimagesize(TL_ROOT . '/' . $this->singleSRC);

		// Adjust image size in the back end
		if (TL_MODE == 'BE' && $arrImageSize[0] > 640 && ($size[0] > 640 || !$size[0]))
		{
			$size[0] = 640;
			$size[1] = floor(640 * $arrImageSize[1] / $arrImageSize[0]);
		}

		$src = $this->getImage($this->urlEncode($this->singleSRC), $size[0], $size[1]);

		if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
		{
			$this->Template->imgSize = ' ' . $imgSize[3];
		}

		$this->Template->src = $src;
		$this->Template->width = $arrImageSize[0];
		$this->Template->height = $arrImageSize[1];
		$this->Template->alt = specialchars($this->alt);
		$this->Template->margin = $this->generateMargin(deserialize($this->imagemargin), 'padding');
		$this->Template->href = strlen($this->imageUrl) ? $this->imageUrl : $this->singleSRC;
		$this->Template->caption = $this->caption;
	}
}

?>