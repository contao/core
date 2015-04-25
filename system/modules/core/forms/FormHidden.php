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
 * Class FormHidden
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FormHidden extends \Widget
{

	/**
	 * Submit user input
	 *
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'form_hidden';


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string The widget markup
	 */
	public function generate()
	{
		return sprintf('<input type="hidden" name="%s" value="%s"%s',
						$this->strName,
						specialchars($this->varValue),
						$this->strTagEnding);
	}
}
