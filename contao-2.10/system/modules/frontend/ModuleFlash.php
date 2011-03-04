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
 * Class ModuleFlash
 *
 * Front end module "flash".
 * @copyright  Leo Feyer 2005-2011
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
		if ($this->source != 'external' && (!strlen($this->singleSRC) || !is_file(TL_ROOT . '/' . $this->singleSRC)))
		{
			return '';
		}

		if (TL_MODE == 'BE')
		{
			return $this->altContent;
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$this->import('String');

		$this->Template->src = $this->singleSRC;
		$this->Template->href = ($this->source == 'external') ? $this->url : $this->singleSRC;
		$this->Template->alt = $this->altContent;
		$this->Template->var = 'swf' . $this->id;
		$this->Template->transparent = $this->transparent ? true : false;
		$this->Template->interactive = $this->interactive ? true : false;
		$this->Template->flashId = strlen($this->flashID) ? $this->flashID : 'swf_' . $this->id;
		$this->Template->fsCommand = '  ' . preg_replace('/[\n\r]/', "\n  ", $this->String->decodeEntities($this->flashJS));
		$this->Template->flashvars = 'URL=' . $this->Environment->base;
		$this->Template->version = strlen($this->version) ? $this->version : '6.0.0';

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
			$this->Template->flashvars .= '&' . $this->String->decodeEntities($this->flashvars);
		}
	}
}

?>