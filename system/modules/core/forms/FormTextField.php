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
 * Class FormTextField
 *
 * @property string  $value
 * @property string  $type
 * @property integer $maxlength
 * @property boolean $mandatory
 * @property integer $min
 * @property integer $max
 * @property integer $step
 * @property string  $placeholder
 * @property boolean $hideInput
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FormTextField extends \Widget
{

	/**
	 * Submit user input
	 *
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Add a for attribute
	 *
	 * @var boolean
	 */
	protected $blnForAttribute = true;

	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'form_textfield';

	/**
	 * The CSS class prefix
	 *
	 * @var string
	 */
	protected $strPrefix = 'widget widget-text';


	/**
	 * Add specific attributes
	 *
	 * @param string $strKey   The attribute key
	 * @param mixed  $varValue The attribute value
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

			case 'min':
			case 'max':
			case 'step':
			case 'placeholder':
				$this->arrAttributes[$strKey] = $varValue;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Return a parameter
	 *
	 * @param string $strKey The parameter key
	 *
	 * @return mixed The parameter value
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'value':
				// Hide the Punycode format (see #2750)
				if ($this->rgxp == 'email' || $this->rgxp == 'friendly' || $this->rgxp == 'url')
				{
					return \Idna::decode($this->varValue);
				}
				else
				{
					return $this->varValue;
				}
				break;

			case 'type':
				// Use the HTML5 types (see #4138) but not the date, time and datetime types (see #5918)
				if ($this->hideInput)
				{
					return 'password';
				}

				if ($this->strFormat != 'xhtml')
				{
					switch ($this->rgxp)
					{
						case 'digit':
							// Allow floats (see #7257)
							if (!isset($this->arrAttributes['step']))
							{
								$this->addAttribute('step', 'any');
							}
							// NO break; here

						case 'natural':
							return 'number';
							break;

						case 'phone':
							return 'tel';
							break;

						case 'email':
							return 'email';
							break;

						case 'url':
							return 'url';
							break;
					}
				}

				return 'text';
				break;

			default:
				return parent::__get($strKey);
				break;
		}
	}


	/**
	 * Trim the values
	 *
	 * @param mixed $varInput The user input
	 *
	 * @return mixed The validated user input
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

		return parent::validator($varInput);
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string The widget markup
	 */
	public function generate()
	{
		return sprintf('<input type="%s" name="%s" id="ctrl_%s" class="text%s%s" value="%s"%s%s',
						$this->type,
						$this->strName,
						$this->strId,
						($this->hideInput ? ' password' : ''),
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						specialchars($this->value),
						$this->getAttributes(),
						$this->strTagEnding) . $this->addSubmit();
	}
}
