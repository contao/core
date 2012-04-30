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
use \Environment, \FilesModel, \Module, \String;


/**
 * Class ModuleFlash
 *
 * Front end module "flash".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleFlash extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_flash';


	/**
	 * Extend the parent method
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			return $this->altContent;
		}

		if ($this->source != 'external')
		{
			if ($this->singleSRC == '')
			{
				return '';
			}

			if (!is_numeric($this->singleSRC))
			{
				return '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
			}

			$objFile = FilesModel::findByPk($this->singleSRC);

			if ($objFile === null  || !is_file(TL_ROOT . '/' . $objFile->path))
			{
				return '';
			}

			$this->singleSRC = $objFile->path;
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 * @return void
	 */
	protected function compile()
	{
		$this->Template->src = $this->singleSRC;
		$this->Template->href = ($this->source == 'external') ? $this->url : $this->singleSRC;
		$this->Template->alt = $this->altContent;
		$this->Template->var = 'swf' . $this->id;
		$this->Template->transparent = $this->transparent ? true : false;
		$this->Template->interactive = $this->interactive ? true : false;
		$this->Template->flashId = $this->flashID ?: 'swf_' . $this->id;
		$this->Template->fsCommand = '  ' . preg_replace('/[\n\r]/', "\n  ", String::decodeEntities($this->flashJS));
		$this->Template->flashvars = 'URL=' . Environment::get('base');
		$this->Template->version = $this->version ?: '6.0.0';

		$size = deserialize($this->size);

		$this->Template->width = $size[0];
		$this->Template->height = $size[1];

		$intMaxWidth = (TL_MODE == 'BE') ? 320 : $GLOBALS['TL_CONFIG']['maxImageWidth'];

		// Adjust movie size
		if ($intMaxWidth > 0 && $size[0] > $intMaxWidth)
		{
			$this->Template->width = $intMaxWidth;
			$this->Template->height = floor($intMaxWidth * $size[1] / $size[0]);
		}

		if (strlen($this->flashvars))
		{
			$this->Template->flashvars .= '&' . String::decodeEntities($this->flashvars);
		}
	}
}
