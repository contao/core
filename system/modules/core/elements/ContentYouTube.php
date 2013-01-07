<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ContentYouTube
 *
 * Content element "YouTube".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ContentYouTube extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_player';


	/**
	 * Extend the parent method
	 * @return string
	 */
	public function generate()
	{
		if ($this->youtube == '')
		{
			return '';
		}

		if (TL_MODE == 'BE')
		{
			return '<p><a href="http://youtu.be/' . $this->youtube . '" target="_blank">http://youtu.be/' . $this->youtube . '</a></p>';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->Template->size = '';

		// Set the size
		if ($this->playerSize != '')
		{
			$size = deserialize($this->playerSize);

			if (is_array($size))
			{
				$this->Template->size = ' width="' . $size[0] . 'px" height="' . $size[1] . 'px"';
			}
		}

		$this->Template->poster = false;

		// Optional poster
		if ($this->posterSRC != '')
		{
			if (($objFile = \FilesModel::findByPk($this->posterSRC)) !== null)
			{
				$this->Template->poster = $objFile->path;
			}
		}

		$objFile = new \stdClass();
		$objFile->mime = 'video/x-youtube';
		$objFile->path = 'http://www.youtube.com/watch?v=' . $this->youtube;

		$this->Template->isVideo = true;
		$this->Template->files = array($objFile);
		$this->Template->autoplay = $this->autoplay;
	}
}
