<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
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
 * Class FormTextField
 *
 * Form field "text".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class FormTextField extends \Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Add a for attribute
	 * @var boolean
	 */
	protected $blnForAttribute = true;

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

		// Convert to Punycode format (see #5571)
		if ($this->rgxp == 'url')
		{
			$varInput = \Idna::encodeUrl($varInput);
		}
		elseif ($this->rgxp == 'email' || $this->rgxp == 'friendly')
		{
			$varInput = \Idna::encodeEmail($varInput);
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
		if ($this->rgxp == 'email' || $this->rgxp == 'friendly' || $this->rgxp == 'url')
		{
			$this->varValue = \Idna::decode($this->varValue);
		}

		if ($this->hideInput)
		{
			$strType = 'password';
		}
		elseif ($this->strFormat != 'xhtml')
		{
			// Use the HTML5 types (see #4138)
			switch ($this->rgxp)
			{
				case 'digit':
					$strType = 'number';
					break;

				case 'date':
					$strType = 'date';
					break;

				case 'time':
					$strType = 'time';
					break;

				case 'datim':
					$strType = 'datetime';
					break;

				case 'phone':
					$strType = 'tel';
					break;

				case 'email':
					$strType = 'email';
					break;

				case 'url':
					$strType = 'url';
					break;

				default:
					$strType = 'text';
					break;
			}
		}
		else
		{
			$strType = 'text';
		}

		return sprintf('<input type="%s" name="%s" id="ctrl_%s" class="text%s%s" value="%s"%s%s',
						$strType,
						$this->strName,
						$this->strId,
						($this->hideInput ? ' password' : ''),
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						specialchars($this->varValue),
						$this->getAttributes(),
						$this->strTagEnding) . $this->addSubmit();
	}
}
