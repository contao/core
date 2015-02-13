<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Front end module "flash".
 *
 * @property string $version
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleFlash extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_flash';


	/**
	 * Extend the parent method
	 *
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

			$objFile = \FilesModel::findByUuid($this->singleSRC);

			if ($objFile === null)
			{
				if (!\Validator::isUuid($this->singleSRC))
				{
					return '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}
			elseif (!is_file(TL_ROOT . '/' . $objFile->path))
			{
				return '';
			}
			else
			{
				$this->singleSRC = $objFile->path;
			}
		}

		return parent::generate();
	}


	/**
	 * Generate the module
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
		$this->Template->fsCommand = '  ' . preg_replace('/[\n\r]/', "\n  ", \String::decodeEntities($this->flashJS));
		$this->Template->flashvars = 'URL=' . \Environment::get('base');
		$this->Template->version = $this->version ?: '6.0.0';

		$size = deserialize($this->size);

		$this->Template->width = $size[0];
		$this->Template->height = $size[1];

		$intMaxWidth = (TL_MODE == 'BE') ? 320 : \Config::get('maxImageWidth');

		// Adjust movie size
		if ($intMaxWidth > 0 && $size[0] > $intMaxWidth)
		{
			$this->Template->width = $intMaxWidth;
			$this->Template->height = floor($intMaxWidth * $size[1] / $size[0]);
		}

		if (strlen($this->flashvars))
		{
			$this->Template->flashvars .= '&' . \String::decodeEntities($this->flashvars);
		}
	}
}
