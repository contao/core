<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class FormFieldset
 *
 * Form field "fieldset".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class FormFieldset extends Widget
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
			$objTemplate = new BackendTemplate('be_wildcard');

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

?>