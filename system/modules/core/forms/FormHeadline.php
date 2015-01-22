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
 * Form field "headline".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FormHeadline extends \Widget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_headline';


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
			return \String::toXhtml($this->text);
		}
		else
		{
			return \String::toHtml5($this->text);
		}
	}
}
