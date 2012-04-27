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
use \BackendTemplate, \ContentElement, \FilesModel, \FrontendTemplate, \String;


/**
 * Class ContentAccordion
 *
 * Front end content element "accordion".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
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
	 * Generate the content element
	 * @return void
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
				$this->Template->setData($this->arrData);
			}
			else
			{
				$this->strTemplate = 'be_wildcard';
				$this->Template = new BackendTemplate($this->strTemplate);
				$this->Template->title = $this->mooHeadline;
			}
		}

		// Accordion end
		elseif ($this->mooType == 'stop')
		{
			if (TL_MODE == 'FE')
			{
				$this->strTemplate = 'ce_accordion_stop';
				$this->Template = new FrontendTemplate($this->strTemplate);
				$this->Template->setData($this->arrData);
			}
			else
			{
				$this->strTemplate = 'be_wildcard';
				$this->Template = new BackendTemplate($this->strTemplate);
			}
		}

		// Accordion default
		else
		{
			global $objPage;

			// Clean RTE output
			if ($objPage->outputFormat == 'xhtml')
			{
				$this->text = String::toXhtml($this->text);
			}
			else
			{
				$this->text = String::toHtml5($this->text);
			}

			$this->Template->text = String::encodeEmail($this->text);
			$this->Template->addImage = false;

			// Add an image
			if ($this->addImage && $this->singleSRC != '')
			{
				if (!is_numeric($this->singleSRC))
				{
					$this->Template->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
				else
				{
					$objModel = FilesModel::findByPk($this->singleSRC);

					if ($objModel !== null && is_file(TL_ROOT . '/' . $objModel->path))
					{
						$this->singleSRC = $objModel->path;
						$this->addImageToTemplate($this->Template, $this->arrData);
					}
				}
			}
		}

		$classes = deserialize($this->mooClasses);

		$this->Template->toggler = $classes[0] ?: 'toggler';
		$this->Template->accordion = $classes[1] ?: 'accordion';
		$this->Template->headlineStyle = $this->mooStyle;
		$this->Template->headline = $this->mooHeadline;
	}
}
