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
 * Class ModuleFlash
 *
 * Front end module "flash".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
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

			$objFile = \FilesModel::findByPk($this->singleSRC);

			if ($objFile === null || !is_file(TL_ROOT . '/' . $objFile->path))
			{
				return '';
			}

			$this->singleSRC = $objFile->path;
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

		$intMaxWidth = (TL_MODE == 'BE') ? 320 : $GLOBALS['TL_CONFIG']['maxImageWidth'];

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
