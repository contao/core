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
 * Form field "fieldset".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FormFieldset extends \Widget
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
		// Return a wildcard in the back end
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			if ($this->fsType == 'fsStart')
			{
				$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['tl_form_field']['fsStart'][0]) . ' ###' . ($this->label ? '<br>' . $this->label : '');
			}
			else
			{
				$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['tl_form_field']['fsStop'][0]) . ' ###';
			}

			return $objTemplate->parse();
		}

		// Only tableless forms are supported
		if (!$this->tableless)
		{
			return '';
		}

		// Return the HTML code in the front end
		if ($this->fsType == 'fsStart')
		{
			return "  <fieldset" . ($this->strClass ? ' class="' . $this->strClass . '"' : '') . ">\n" . (($this->label != '') ? "  <legend>" . $this->label . "</legend>\n" : '');
		}
		else
		{
			return "  </fieldset>\n";
		}
	}
}
