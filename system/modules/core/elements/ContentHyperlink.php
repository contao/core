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
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \ContentElement, \File, \FilesModel, \FrontendTemplate, \Image, \String;


/**
 * Class ContentHyperlink
 *
 * Front end content element "hyperlink".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Core
 */
class ContentHyperlink extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_hyperlink';


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		global $objPage;

		if (substr($this->url, 0, 7) == 'mailto:')
		{
			$this->url = String::encodeEmail($this->url);
		}
		else
		{
			$this->url = ampersand($this->url);
		}

		$embed = explode('%s', $this->embed);

		if ($this->linkTitle == '')
		{
			$this->linkTitle = $this->url;
		}

		// Use an image instead of the title
		if ($this->useImage && $this->singleSRC != '' && is_numeric($this->singleSRC))
		{
			$objModel = FilesModel::findByPk($this->singleSRC);

			if ($objModel !== null && is_file(TL_ROOT . '/' . $objModel->path))
			{
				$this->Template = new FrontendTemplate('ce_hyperlink_image');
				$this->Template->setData($this->arrData);

				$objFile = new File($objModel->path);

				if ($objFile->isGdImage)
				{
					$size = deserialize($this->size);
					$intMaxWidth = (TL_MODE == 'BE') ? 320 : $GLOBALS['TL_CONFIG']['maxImageWidth'];

					// Adjust the image size
					if ($intMaxWidth > 0  && ($size[0] > $intMaxWidth || (!$size[0] && $objFile->width > $intMaxWidth)))
					{
						$size[0] = $intMaxWidth;
						$size[1] = floor($intMaxWidth * $objFile->height / $objFile->width);
					}

					$src = Image::get($objModel->path, $size[0], $size[1], $size[2]);

					if (($imgSize = @getimagesize(TL_ROOT . '/' . rawurldecode($src))) !== false)
					{
						$this->Template->arrSize = $imgSize;
						$this->Template->imgSize = ' ' . $imgSize[3];
					}

					$this->Template->src = TL_FILES_URL . $src;
					$this->Template->alt = specialchars($this->alt);
					$this->Template->linkTitle = specialchars($this->linkTitle);
					$this->Template->caption = $this->caption;
				}
			}
		}

		if (strncmp($this->rel, 'lightbox', 8) !== 0 || $objPage->outputFormat == 'xhtml')
		{
			$this->Template->attribute = ' rel="'. $this->rel .'"';
		}
		else
		{
			$this->Template->attribute = ' data-lightbox="'. substr($this->rel, 9, -1) .'"';
		}

		$this->Template->rel = $this->rel; // Backwards compatibility
		$this->Template->href = $this->url;
		$this->Template->embed_pre = $embed[0];
		$this->Template->embed_post = $embed[1];
		$this->Template->link = $this->linkTitle;
		$this->Template->linkTitle = specialchars($this->linkTitle);
		$this->Template->target = '';

		// Override the link target
		if ($this->target)
		{
			$this->Template->target = ($objPage->outputFormat == 'xhtml') ? ' onclick="window.open(this.href);return false"' : ' target="_blank"';
		}
	}
}
