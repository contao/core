<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class FormTextField
 *
 * Form field "text".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class FormTextField extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_widget';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'maxlength':
				if ($varValue > 0)
				{
					$this->arrAttributes['maxlength'] =  $varValue;
				}
				break;

			case 'mandatory':
				if ($varValue)
				{
					$this->arrAttributes['required'] = 'required';
				}
				else
				{
					unset($this->arrAttributes['required']);
				}
				parent::__set($strKey, $varValue);
				break;

			case 'placeholder':
				$this->arrAttributes['placeholder'] = $varValue;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Trim values
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		if (is_array($varInput))
		{
			return parent::validator($varInput);
		}

		return parent::validator(trim($varInput));
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		// Hide the Punycode format (see #2750)
		if ($this->rgxp == 'email' || $this->rgxp == 'url')
		{
			$this->varValue = $this->idnaDecode($this->varValue);
		}

		return sprintf('<input type="%s" name="%s" id="ctrl_%s" class="text%s%s" value="%s"%s%s',
						($this->hideInput ? 'password' : 'text'),
						$this->strName,
						$this->strId,
						($this->hideInput ? ' password' : ''),
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						specialchars($this->varValue),
						$this->getAttributes(),
						$this->strTagEnding) . $this->addSubmit();
	}
}

?>