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
 * Class FormFieldset
 *
 * Form field "fieldset".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
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
				$objTemplate->wildcard = '### FIELDSET START' . ($this->label ? ': ' . $this->label : '') . ' ###';
			}
			else
			{
				$objTemplate->wildcard = '### FIELDSET END ###';
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
