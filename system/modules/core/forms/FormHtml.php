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
 * Form field "HTML".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FormHtml extends \Widget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_html';


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
		return (TL_MODE == 'FE') ? $this->html : htmlspecialchars($this->html);
	}
}
