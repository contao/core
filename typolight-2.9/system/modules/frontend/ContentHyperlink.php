<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ContentHyperlink
 *
 * Front end content element "hyperlink".
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Controller
 */
class ContentHyperlink extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_hyperlink';


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		$this->import('String');

		if (substr($this->url, 0, 7) == 'mailto:')
		{
			$this->url = $this->String->encodeEmail($this->url);
		}

		$embed = explode('%s', $this->embed);

		if (!strlen($this->linkTitle))
		{
			$this->linkTitle = $this->url;
		}

		// Use an image instead of the title
		if ($this->useImage && strlen($this->singleSRC) && is_file(TL_ROOT . '/' . $this->singleSRC))
		{
			$this->strTemplate = 'ce_hyperlink_image';

			$this->Template = new FrontendTemplate($this->strTemplate);
			$this->Template->setData($this->arrData);

			$objFile = new File($this->singleSRC);

			if ($objFile->isGdImage)
			{
				$size = deserialize($this->size);
				$intMaxWidth = (TL_MODE == 'BE') ? 320 : $GLOBALS['TL_CONFIG']['maxImageWidth'];

				// Adjust image size
				if ($intMaxWidth > 0  && ($size[0] > $intMaxWidth || (!$size[0] && $objFile->width > $intMaxWidth)))
				{
					$size[0] = $intMaxWidth;
					$size[1] = floor($intMaxWidth * $objFile->height / $objFile->width);
				}

				$src = $this->getImage($this->urlEncode($this->singleSRC), $size[0], $size[1], $size[2]);

				if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
				{
					$this->Template->imgSize = ' ' . $imgSize[3];
				}

				$this->Template->src = $src;
				$this->Template->alt = specialchars($this->alt);
				$this->Template->title = specialchars($this->linkTitle);
				$this->Template->caption = $this->caption;
			}
		}

		$this->Template->rel = $this->rel;
		$this->Template->href = $this->url;
		$this->Template->embed_pre = $embed[0];
		$this->Template->embed_post = $embed[1];
		$this->Template->link = $this->linkTitle;
		$this->Template->title = specialchars($this->linkTitle);
		$this->Template->target = $this->target ? LINK_NEW_WINDOW : '';
	}
}

?>