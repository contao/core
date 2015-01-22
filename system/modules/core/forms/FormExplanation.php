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
 * Form field "explanation".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FormExplanation extends \Widget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_explanation';


	/**
	 * Do not validate
	 */
	public function validate()
	{
		return;
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		global $objPage;

		// Clean RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$this->text = \String::toXhtml($this->text);
		}
		else
		{
			$this->text = \String::toHtml5($this->text);
		}

		// Add the static files URL to images
		if (TL_FILES_URL != '')
		{
			$path = $GLOBALS['TL_CONFIG']['uploadPath'] . '/';
			$this->text = str_replace(' src="' . $path, ' src="' . TL_FILES_URL . $path, $this->text);
		}

		return \String::encodeEmail($this->text);
	}
}
