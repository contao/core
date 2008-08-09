<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Widget
 *
 * Provide methods to handle form widgets.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
abstract class Widget extends Controller
{

	/**
	 * Id
	 * @var integer
	 */
	protected $strId;

	/**
	 * Name
	 * @var string
	 */
	protected $strName;

	/**
	 * Label
	 * @var string
	 */
	protected $strLabel;

	/**
	 * Value
	 * @var mixed
	 */
	protected $varValue;

	/**
	 * CSS class
	 * @var string
	 */
	protected $strClass;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate;

	/**
	 * Errors
	 * @var array
	 */
	protected $arrErrors = array();

	/**
	 * Attributes
	 * @var array
	 */
	protected $arrAttributes = array();

	/**
	 * Configuration
	 * @var array
	 */
	protected $arrConfiguration = array();

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = false;


	/**
	 * Initialize the object
	 * @param array
	 */
	public function __construct($arrAttributes=false)
	{
		parent::__construct();
		$this->addAttributes($arrAttributes);
	}


	/**
	 * Set a parameter
	 * @param string
	 * @param mixed
	 * @throws Exception
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'name':
				$this->strName = $varValue;
				break;

			case 'id':
				$this->strId = $varValue;
				break;

			case 'label':
				$this->strLabel = $varValue;
				break;

			case 'value':
				$this->varValue = $varValue;
				break;

			case 'class':
				$this->strClass = trim($this->strClass . ' ' . trim($varValue));
				break;

			case 'template':
				$this->strTemplate = $varValue;
				break;

			case 'alt':
			case 'style':
			case 'onclick':
			case 'onchange':
			case 'accesskey':
			case 'disabled':
				$this->arrAttributes[$strKey] = $varValue;
				break;

			case 'mandatory':
				$this->arrConfiguration[$strKey] = false;
				break;

			case 'nospace':
			case 'allowHtml':
			case 'addSubmit':
			case 'storeFile':
			case 'useHomeDir':
			case 'storeValues':
			case 'trailingSlash':
			case 'spaceToUnderscore':
				$this->arrConfiguration[$strKey] = $varValue ? true : false;
				break;

			case 'required':
				if ($varValue)
				{
					$this->strClass = trim($this->strClass . ' mandatory');
				}
				// Do not add a break; statement here

			default:
				$this->arrConfiguration[$strKey] = $varValue;
				break;
		}
	}


	/**
	 * Return a parameter
	 * @return string
	 * @throws Exception
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'id':
				return $this->strId;
				break;

			case 'name':
				return $this->strName;
				break;

			case 'label':
				return $this->strLabel;
				break;

			case 'value':
				return $this->varValue;
				break;

			case 'class':
				return $this->strClass;
				break;

			case 'template':
				return $this->strTemplate;
				break;

			default:
				return array_key_exists($strKey, $this->arrAttributes) ? $this->arrAttributes[$strKey] : $this->arrConfiguration[$strKey];
				break;
		}
	}


	/**
	 * Add an error message
	 * @param string
	 */
	public function addError($strError)
	{
		$this->arrErrors[] = $strError;
	}


	/**
	 * Return true if the widget has errors
	 * @return boolean
	 */
	public function hasErrors()
	{
		return count($this->arrErrors) ? true : false;
	}


	/**
	 * Return the error array
	 * @return array
	 */
	public function getErrors()
	{
		return $this->arrErrors;
	}


	/**
	 * Return a particular error as string
	 * @param integer
	 * @return string
	 */
	public function getErrorAsString($intIndex=0)
	{
		return $this->arrErrors[$intIndex];
	}


	/**
	 * Return all errors as string separated by a particular separator
	 * @param string
	 * @return string
	 */
	public function getErrorsAsString($strSeparator="<br />\n")
	{
		return $this->hasErrors() ? implode($strSeparator, $this->arrErrors) : '';
	}


	/**
	 * Return a particular error as HTML string
	 * @param integer
	 * @return string
	 */
	public function getErrorAsHTML($intIndex=0)
	{
		return $this->hasErrors() ? sprintf('<div class="%s">%s</div>', ((TL_MODE == 'BE') ? 'tl_error' : 'error'), $this->arrErrors[$intIndex]) : '';
	}


	/**
	 * Return true if the current input shall be submitted
	 * @return boolean
	 */
	public function submitInput()
	{
		return $this->blnSubmitInput;
	}


	/**
	 * Parse the template file and return it as string
	 * @param array
	 * @return string
	 */
	public function parse($arrAttributes=false)
	{
		if (!strlen($this->strTemplate))
		{
			return '';
		}

		$this->addAttributes($arrAttributes);

		ob_start();
		include($this->getTemplate($this->strTemplate));
		$strBuffer = ob_get_contents();
		ob_end_clean();

		return $strBuffer;
	}


	/**
	 * Generate the label and return it as string
	 * @return string
	 */
	public function generateLabel()
	{
		if (!strlen($this->strLabel))
		{
			return '';
		}

		return sprintf('<label for="ctrl_%s"%s>%s</label>',
						$this->strId,
						(strlen($this->strClass) ? ' class="' . $this->strClass . '"' : ''),
						$this->strLabel);
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	abstract public function generate();


	/**
	 * Generate the widget with error message and return it as string
	 * @param boolean
	 * @return string
	 */
	public function generateWithError($blnSwitchOrder=false)
	{
		$strWidget = $this->generate();
		$strError = $this->getErrorAsHTML();

		return $blnSwitchOrder ? $strWidget . $strError : $strError . $strWidget;
	}


	/**
	 * Return all attributes as string
	 * @return string
	 */
	public function getAttributes()
	{
		$strAttributes = '';

		foreach ($this->arrAttributes as $k=>$v)
		{
			if (strlen($v))
			{
				$strAttributes .= sprintf(' %s="%s"', $k, $v);
			}
		}

		return $strAttributes;
	}


	/**
	 * Return a submit button
	 * @return string
	 */
	protected function addSubmit()
	{
		if (!$this->addSubmit)
		{
			return '';
		}

		return sprintf(' <input type="submit" id="ctrl_%s_submit" class="submit" value="%s" />',
						$this->strId,
						specialchars($this->slabel));
	}


	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		$varInput = $this->validator(deserialize($this->getPost($this->strName)));

		if (!$this->hasErrors())
		{
			$this->varValue = $varInput;
		}
	}


	/**
	 * Get a $_POST parameter
	 * @param string
	 * @return mixed
	 */
	protected function getPost($strKey)
	{
		$strMethod = $this->allowHtml ? 'postHtml' : 'post';

		if ($this->preserveTags)
		{
			$strMethod = 'postRaw';
		}

		return $this->Input->$strMethod($strKey, $this->decodeEntities);
	}


	/**
	 * Recursively validate an input variable
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		if (is_array($varInput))
		{
			foreach ($varInput as $k=>$v)
			{
				$varInput[$k] = $this->validator($v);
			}

			return $varInput;
		}

		if (!strlen($varInput) && !$this->mandatory)
		{
			return '';
		}

		if ($this->mandatory && !strlen($varInput))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory'], $this->strLabel));
		}

		if ($this->minlength && strlen($varInput) && utf8_strlen($varInput) < $this->minlength)
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['minlength'], $this->strLabel, $this->minlength));
		}

		if (strlen($this->rgxp))
		{
			switch ($this->rgxp)
			{
				// Numeric characters (including full stop [.] minus [-] and space [ ])
				case 'digit':
					if (!preg_match('/^[\d \.-]*$/', $varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['digit'], $this->strLabel));
					}
					break;

				// Alphabetic characters (including full stop [.] minus [-] and space [ ])
				case 'alpha':
					if (function_exists('mb_eregi'))
					{
						if (!mb_eregi('^[[:alpha:] \.-]*$', $varInput))
						{
							$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['alpha'], $this->strLabel));
						}
					}
					else
					{
						if (!preg_match('/^[\pL \.-]*$/u', $varInput))
						{
							$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['alpha'], $this->strLabel));
						}
					}
					break;

				// Alphanumeric characters (including full stop [.] minus [-], underscore [_] and space [ ])
				case 'alnum':
					if (function_exists('mb_eregi'))
					{
						if (!mb_eregi('^[[:alnum:] \._-]*$', $varInput))
						{
							$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['alnum'], $this->strLabel));
						}
					}
					else
					{
						if (!preg_match('/^[\pN\pL \._-]*$/u', $varInput))
						{
							$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['alnum'], $this->strLabel));
						}
					}
					break;

				// Do not allow any characters that are usually encoded by class Input [=<>()#/])
				case 'extnd':
					if (preg_match('/[#\(\)\/<=>]/', html_entity_decode($varInput)))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['extnd'], $this->strLabel));
					}
					break;

				// Check whether the current value is a valid date format
				case 'date':
					$objDate = new Date();
					if (!preg_match('/'. $objDate->getRegexp($GLOBALS['TL_CONFIG']['dateFormat']) .'/i', $varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['date'], $objDate->getInputFormat($GLOBALS['TL_CONFIG']['dateFormat'])));
					}
					break;

				// Check whether the current value is a valid time format
				case 'time':
					$objDate = new Date();
					if (!preg_match('/'. $objDate->getRegexp($GLOBALS['TL_CONFIG']['timeFormat']) .'/i', $varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['time'], $objDate->getInputFormat($GLOBALS['TL_CONFIG']['timeFormat'])));
					}
					break;

				// Check whether the current value is a valid date and time format
				case 'datim':
					$objDate = new Date();
					if (!preg_match('/'. $objDate->getRegexp($GLOBALS['TL_CONFIG']['datimFormat']) .'/i', $varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['dateTime'], $objDate->getInputFormat($GLOBALS['TL_CONFIG']['datimFormat'])));
					}
					break;

				// Check whether the current value is a valid e-mail address
				case 'email':
					if (!preg_match('/^\w+([_\.-]*\w+)*@\w+([_\.-]*\w+)*\.[a-z]{2,6}$/i', $varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['email'], $this->strLabel));
					}
					break;

				// Phone numbers (numeric characters, space [ ], plus [+], minus [-], parentheses [()] and slash [/])
				case 'phone':
					if (!preg_match('/^[\d \+\(\)\/-]*$/', html_entity_decode($varInput)))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['phone'], $this->strLabel));
					}
					break;

				// Check whether the current value is a valid URL
				case 'url':
					if (!preg_match('/^[a-zA-Z0-9\.\+\/\?#%:,;\{\}\[\]@&=~_-]*$/', $varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['url'], $this->strLabel));
					}
					break;

				// Check whether the current value is a percent value
				case 'prcnt':
					if (!is_numeric($varInput) || $varInput < 0 || $varInput > 100)
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['prcnt'], $this->strLabel));
					}
					break;
			}
		}

		if ($this->nospace && preg_match('/\s+/i', $varInput))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['noSpace'], $this->strLabel));
		}

		if ($this->spaceToUnderscore)
		{
			$varInput = preg_replace('/\s+/i', '_', trim($varInput));
		}

		if (is_bool($this->trailingSlash) && strlen($varInput))
		{
			$varInput = preg_replace('/\/+$/i', '', $varInput) . ($this->trailingSlash ? '/' : '');
		}

		return $varInput;
	}


	/**
	 * Take an associative array and add it to the object's attributes
	 * @param array
	 */
	public function addAttributes($arrAttributes)
	{
		if (!is_array($arrAttributes))
		{
			return;
		}

		foreach ($arrAttributes as $k=>$v)
		{
			$this->$k = $v;
		}
	}
}

?>