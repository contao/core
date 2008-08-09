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
 * Class ModuleFlash
 *
 * Front end module "flash".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
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
	 * Make sure the UFO plugin is available
	 * @return string
	 */
	public function generate()
	{
		if (!file_exists(TL_ROOT . '/plugins/ufo/ufo.js'))
		{
			throw new Exception('Plugin "ufo" required');
		}

		if ($this->source != 'external' && (!strlen($this->singleSRC) || !is_file(TL_ROOT . '/' . $this->singleSRC)))
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
		$this->import('String');

		$this->Template->src = $this->singleSRC;
		$this->Template->href = ($this->source == 'external') ? $this->url : $this->singleSRC;
		$this->Template->alt = $this->altContent;
		$this->Template->var = 'swf' . $this->id;
		$this->Template->searchable = $this->searchable;
		$this->Template->transparent = $this->transparent ? true : false;
		$this->Template->interactive = $this->interactive ? true : false;
		$this->Template->flashId = strlen($this->flashID) ? $this->flashID : 'swf_' . $this->id;
		$this->Template->fsCommand = '  ' . preg_replace('/[\n\r]/', "\n  ", $this->String->decodeEntities($this->flashJS));
		$this->Template->flashvars = 'URL=' . $this->Environment->base;

		$size = deserialize($this->size);

		$this->Template->width = $size[0];
		$this->Template->height = $size[1];

		// Adjust movie size in the back end
		if (TL_MODE == 'BE' && $size[0] > 640)
		{
			$this->Template->width = 640;
			$this->Template->height = floor(640 * $size[1] / $size[0]);
		}

		if (strlen($this->flashvars))
		{
			$this->Template->flashvars .= '&' . $this->String->decodeEntities($this->flashvars);
		}

		$version = deserialize($this->version);

		$this->Template->build = strlen($version[1]) ? intval($version[1]) : 1;
		$this->Template->version = strlen($version[0]) ? intval($version[0]) : 1;

		// Add JavaScript
		$GLOBALS['TL_JAVASCRIPT'][] = 'plugins/ufo/ufo.js';
	}
}

?>