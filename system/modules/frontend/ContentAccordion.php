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
 * Class ContentAccordion
 *
 * Front end content element "accordion".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ContentAccordion extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_accordion';


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		// Accordion start
		if ($this->mooType == 'start')
		{
			if (TL_MODE == 'FE')
			{
				$this->strTemplate = 'ce_accordion_start';
				$this->Template = new FrontendTemplate($this->strTemplate);
			}
			else
			{
				$this->strTemplate = 'be_wildcard';
				$this->Template = new BackendTemplate($this->strTemplate);
				$this->Template->wildcard = '### ACCORDION WRAPPER START ###';
			}
		}

		// Accordion end
		elseif ($this->mooType == 'stop')
		{
			if (TL_MODE == 'FE')
			{
				$this->strTemplate = 'ce_accordion_stop';
				$this->Template = new FrontendTemplate($this->strTemplate);
			}
			else
			{
				$this->strTemplate = 'be_wildcard';
				$this->Template = new BackendTemplate($this->strTemplate);
				$this->Template->wildcard = '### ACCORDION WRAPPER END ###';
			}
		}

		// Accordion default
		else
		{
			$this->import('String');

			$text = $this->String->encodeEmail($this->text);
			$text = str_ireplace(array('<u>', '</u>'), array('<span style="text-decoration:underline;">', '</span>'), $text);
			$text = str_ireplace(array('</p>', '<br /><br />'), array("</p>\n", "<br /><br />\n"), $text);

			// Use an image instead of the title
			if ($this->addImage && strlen($this->singleSRC) && is_file(TL_ROOT . '/' . $this->singleSRC))
			{
				// Image link
				if (strlen($this->imageUrl) && TL_MODE == 'FE')
				{
					$this->strTemplate = 'ce_accordion_image_link';
					$this->Template = new FrontendTemplate($this->strTemplate);
				}

				// Fullsize view
				elseif ($this->fullsize && TL_MODE == 'FE')
				{
					$this->strTemplate = 'ce_accordion_image_fullsize';
					$this->Template = new FrontendTemplate($this->strTemplate);
				}

				// Simple view
				else
				{
					$this->strTemplate = 'ce_accordion_image';
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
				$this->Template->addBefore = ($this->floating != 'below');
				$this->Template->margin = $this->generateMargin(deserialize($this->imagemargin), 'padding');
				$this->Template->float = in_array($this->floating, array('left', 'right')) ? sprintf(' float:%s;', $this->floating) : '';
				$this->Template->href = strlen($this->imageUrl) ? $this->imageUrl : $this->singleSRC;
				$this->Template->caption = $this->caption;
			}

			$this->Template->text = $text;
		}

		$classes = deserialize($this->mooClasses);

		$this->Template->toggler = strlen($classes[0]) ? $classes[0] : 'toggler';
		$this->Template->accordion = strlen($classes[1]) ? $classes[1] : 'accordion';
		$this->Template->headlineStyle = $this->mooStyle;
		$this->Template->headline = $this->mooHeadline;
	}
}

?>