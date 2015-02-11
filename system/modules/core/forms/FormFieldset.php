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
 * Class FormFieldset
 *
 * @property string  $fsType
 * @property boolean $tableless
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FormFieldset extends \Widget
{

	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'form_fieldset';


	/**
	 * Do not validate
	 */
	public function validate()
	{
		return;
	}


	/**
	 * Parse the template file and return it as string
	 *
	 * @param array $arrAttributes An optional attributes array
	 *
	 * @return string The template markup
	 */
	public function parse($arrAttributes=null)
	{
		// Return a wildcard in the back end
		if (TL_MODE == 'BE')
		{
			/** @var \BackendTemplate|object $objTemplate */
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

		return parent::parse($arrAttributes);
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string The widget markup
	 */
	public function generate()
	{
		// Only tableless forms are supported
		if (!$this->tableless)
		{
			return '';
		}

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
