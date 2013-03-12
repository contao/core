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
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Widget
 *
 * Provide methods to handle form widgets.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
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
	 * Wizard
	 * @var string
	 */
	protected $strWizard;

	/**
	 * Output format
	 * @var string
	 */
	protected $strFormat = 'html5';

	/**
	 * Tag ending
	 * @var string
	 */
	protected $strTagEnding = '>';

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
	 * @throws Exception
	 */
	public function __construct($arrAttributes=false)
	{
		parent::__construct();

		// Override the output format in the front end
		if (TL_MODE == 'FE')
		{
			global $objPage;

			if ($objPage->outputFormat != '')
			{
				$this->strFormat = $objPage->outputFormat;
			}

			$this->strTagEnding = ($this->strFormat == 'xhtml') ? ' />' : '>';
		}

		$this->addAttributes($arrAttributes);
	}


	/**
	 * Set a parameter
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'id':
				$this->strId = $varValue;
				break;

			case 'name':
				$this->strName = $varValue;
				break;

			case 'label':
				$this->strLabel = $varValue;
				break;

			case 'value':
				$this->varValue = deserialize($varValue);
				if ($this->arrConfiguration['encrypt'])
				{
					// Decrypt the value if it is encrypted
					$this->import('Encryption');
					$this->varValue = $this->Encryption->decrypt($this->varValue);
				}
				break;

			case 'class':
				if ($varValue != '' && strpos($this->strClass, $varValue) === false)
				{
					$this->strClass = trim($this->strClass . ' ' . $varValue);
				}
				break;

			case 'template':
				$this->strTemplate = $varValue;
				break;

			case 'wizard':
				$this->strWizard = $varValue;
				break;

			case 'alt':
			case 'style':
			case 'accesskey':
			case 'onblur':
			case 'onchange':
			case 'onclick':
			case 'ondblclick':
			case 'onfocus':
			case 'onmousedown':
			case 'onmousemove':
			case 'onmouseout':
			case 'onmouseover':
			case 'onmouseup':
			case 'onkeydown':
			case 'onkeypress':
			case 'onkeyup':
			case 'onselect':
				$this->arrAttributes[$strKey] = $varValue;
				break;

			case 'tabindex':
				if ($varValue > 0)
				{
					$this->arrAttributes['tabindex'] = $varValue;
				}
				break;

			case 'disabled':
			case 'readonly':
				if ($varValue)
				{
					$this->blnSubmitInput = false;
				}
				else
				{
					$this->blnSubmitInput = true;
				}
				// Do not add a break; statement here

			case 'autofocus':
				if ($varValue)
				{
					$this->arrAttributes[$strKey] = $strKey;
				}
				else
				{
					unset($this->arrAttributes[$strKey]);
				}
				break;

			case 'required':
				if ($varValue)
				{
					$this->strClass = trim($this->strClass . ' mandatory');
				}
				// Do not add a break; statement here

			case 'mandatory':
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

			default:
				$this->arrConfiguration[$strKey] = $varValue;
				break;
		}
	}


	/**
	 * Return a parameter
	 * @param string
	 * @return string
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
				if ($this->arrConfiguration['encrypt'])
				{
					// Encrypt the value
					$this->import('Encryption');
					return $this->Encryption->encrypt($this->varValue);
				}
				return $this->varValue;
				break;

			case 'class':
				return $this->strClass;
				break;

			case 'template':
				return $this->strTemplate;
				break;

			case 'wizard':
				return $this->strWizard;
				break;

			case 'required':
				return $this->arrConfiguration[$strKey];
				break;

			default:
				return isset($this->arrAttributes[$strKey]) ? $this->arrAttributes[$strKey] : $this->arrConfiguration[$strKey];
				break;
		}
	}


	/**
	 * Add an error message
	 * @param string
	 */
	public function addError($strError)
	{
		$this->class = 'error';
		$this->arrErrors[] = $strError;
	}


	/**
	 * Return true if the widget has errors
	 * @return boolean
	 */
	public function hasErrors()
	{
		return !empty($this->arrErrors);
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
	public function getErrorsAsString($strSeparator=null)
	{
		if ($strSeparator === null)
		{
			$strSeparator = '<br' . $this->strTagEnding . "\n";
		}

		return $this->hasErrors() ? implode($strSeparator, $this->arrErrors) : '';
	}


	/**
	 * Return a particular error as HTML string
	 * @param integer
	 * @return string
	 */
	public function getErrorAsHTML($intIndex=0)
	{
		return $this->hasErrors() ? sprintf('<p class="%s">%s</p>', ((TL_MODE == 'BE') ? 'tl_error tl_tip' : 'error'), $this->arrErrors[$intIndex]) : '';
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
	public function parse($arrAttributes=null)
	{
		if ($this->strTemplate == '')
		{
			return '';
		}

		$this->addAttributes($arrAttributes);

		ob_start();
		include($this->getTemplate($this->strTemplate, $this->strFormat));
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
		if ($this->strLabel == '')
		{
			return '';
		}

		return sprintf('<label for="ctrl_%s"%s>%s%s%s</label>',
						$this->strId,
						(strlen($this->strClass) ? ' class="' . $this->strClass . '"' : ''),
						($this->required ? '<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].'</span> ' : ''),
						$this->strLabel,
						($this->required ? '<span class="mandatory">*</span>' : ''));
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
	 * @param array
	 * @return string
	 */
	public function getAttributes($arrStrip=array())
	{
		$blnIsXhtml = false;

		// Remove HTML5 attributes in XHTML code
		if (TL_MODE == 'FE')
		{
			global $objPage;

			if ($objPage->outputFormat == 'xhtml')
			{
				$blnIsXhtml = true;
				unset($this->arrAttributes['autofocus']);
				unset($this->arrAttributes['placeholder']);
				unset($this->arrAttributes['required']);
			}
		}

		// Optionally strip certain attributes
		if (is_array($arrStrip))
		{
			foreach ($arrStrip as $strAttribute)
			{
				unset($this->arrAttributes[$strAttribute]);
			}
		}

		$strAttributes = '';

		// Add the remaining attributes
		foreach ($this->arrAttributes as $k=>$v)
		{
			if ($k == 'disabled' || $k == 'readonly' || $k == 'required' || $k == 'autofocus')
			{
				if (TL_MODE == 'FE') // see #3878
				{
					$strAttributes .= $blnIsXhtml ? ' ' . $k . '="' . $v . '"' : ' ' . $k;
				}
				elseif ($k == 'disabled' || $k == 'readonly') // see #4131
				{
					$strAttributes .= ' ' . $k;
				}
			}
			else
			{
				if ($v != '')
				{
					$strAttributes .= ' ' . $k . '="' . $v . '"';
				}
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

		return sprintf(' <input type="submit" id="ctrl_%s_submit" class="submit" value="%s"%s',
						$this->strId,
						specialchars($this->slabel),
						$this->strTagEnding);
	}


	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		$varValue = $this->validator(deserialize($this->getPost($this->strName)));

		if ($this->hasErrors())
		{
			$this->class = 'error';
		}

		$this->varValue = $varValue;
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

		// Support arrays (thanks to Andreas Schempp)
		$arrParts = explode('[', str_replace(']', '', $strKey));

		if (!empty($arrParts))
    	{
			$varValue = $this->Input->$strMethod(array_shift($arrParts), $this->decodeEntities);

			foreach($arrParts as $part)
			{
				if (!is_array($varValue))
				{
					break;
				}

				$varValue = $varValue[$part];
			}

			return $varValue;
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

		$varInput = trim($varInput);

		if ($varInput == '')
		{
			if (!$this->mandatory)
			{
				return '';
			}
			else
			{
				if ($this->strLabel == '')
				{
					$this->addError($GLOBALS['TL_LANG']['ERR']['mdtryNoLabel']);
				}
				else
				{
					$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory'], $this->strLabel));
				}
			}
		}

		if ($this->minlength && $varInput != '' && utf8_strlen($varInput) < $this->minlength)
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['minlength'], $this->strLabel, $this->minlength));
		}

		if ($this->maxlength && $varInput != '' && utf8_strlen($varInput) > $this->maxlength)
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['maxlength'], $this->strLabel, $this->maxlength));
		}

		if ($this->rgxp != '')
		{
			switch ($this->rgxp)
			{
				// Special validation rule for style sheets
				case (strncmp($this->rgxp, 'digit_', 6) === 0):
					$textual = explode('_', $this->rgxp);
					array_shift($textual);

					if (in_array($varInput, $textual) || strncmp($varInput, '$', 1) === 0)
					{
						break;
					}
					// DO NOT ADD A break; STATEMENT HERE

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

					if (!preg_match('~^'. $objDate->getRegexp($objDate->getNumericDateFormat()) .'$~i', $varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['date'], $objDate->getInputFormat($objDate->getNumericDateFormat())));
					}
					else
					{
						// Validate the date (see #5086)
						try
						{
							new Date($varInput);
						}
						catch (Exception $e)
						{
							$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalidDate'], $varInput));
						}
					}
					break;

				// Check whether the current value is a valid time format
				case 'time':
					$objDate = new Date();

					if (!preg_match('~^'. $objDate->getRegexp($objDate->getNumericTimeFormat()) .'$~i', $varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['time'], $objDate->getInputFormat($objDate->getNumericTimeFormat())));
					}
					break;

				// Check whether the current value is a valid date and time format
				case 'datim':
					$objDate = new Date();

					if (!preg_match('~^'. $objDate->getRegexp($objDate->getNumericDatimFormat()) .'$~i', $varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['dateTime'], $objDate->getInputFormat($objDate->getNumericDatimFormat())));
					}
					else
					{
						// Validate the date (see #5086)
						try
						{
							new Date($varInput);
						}
						catch (Exception $e)
						{
							$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalidDate'], $varInput));
						}
					}
					break;

				// Check whether the current value is a valid friendly name e-mail address
				case 'friendly':
					list ($strName, $varInput) = $this->splitFriendlyName($varInput);
					// no break;

				// Check whether the current value is a valid e-mail address
				case 'email':
					$varInput = $this->idnaEncodeEmail($varInput);

					if (!$this->isValidEmailAddress($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['email'], $this->strLabel));
					}
					if ($this->rgxp == 'friendly' && $strName != '')
					{
						$varInput = $strName . ' [' . $varInput . ']';
					}
					break;

				// Check whether the current value is list of valid e-mail addresses
				case 'emails':
					$arrEmails = trimsplit(',', $varInput);

					foreach ($arrEmails as $strEmail)
					{
						$strEmail = $this->idnaEncodeEmail($strEmail);

						if (!$this->isValidEmailAddress($strEmail))
						{
							$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['emails'], $this->strLabel));
							break;
						}
					}
					break;

				// Check whether the current value is a valid URL
				case 'url':
					$varInput = $this->idnaEncodeUrl($varInput);

					if (!preg_match('/^[a-zA-Z0-9\.\+\/\?#%:,;\{\}\(\)\[\]@&=~_-]*$/', $varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['url'], $this->strLabel));
					}
					break;

				// Phone numbers (numeric characters, space [ ], plus [+], minus [-], parentheses [()] and slash [/])
				case 'phone':
					if (!preg_match('/^(\+|\()?(\d+[ \+\(\)\/-]*)+$/', html_entity_decode($varInput)))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['phone'], $this->strLabel));
					}
					break;

				// Check whether the current value is a percent value
				case 'prcnt':
					if (!is_numeric($varInput) || $varInput < 0 || $varInput > 100)
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['prcnt'], $this->strLabel));
					}
					break;
				
				// HOOK: pass unknown tags to callback functions
				default:
					if (isset($GLOBALS['TL_HOOKS']['addCustomRegexp']) && is_array($GLOBALS['TL_HOOKS']['addCustomRegexp']))
					{
						foreach ($GLOBALS['TL_HOOKS']['addCustomRegexp'] as $callback)
						{
							$this->import($callback[0]);
							$break = $this->$callback[0]->$callback[1]($this->rgxp, $varInput, $this);

							// Stop the loop if a callback returned true
							if ($break === true)
							{
								break;
							}
						}
					}
					break;
			}
		}

		if ($this->isHexColor && $varInput != '' && strncmp($varInput, '$', 1) !== 0)
		{
			$varInput = preg_replace('/[^a-f0-9]+/i', '', $varInput);
		}

		if ($this->nospace && preg_match('/[\t ]+/i', $varInput))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['noSpace'], $this->strLabel));
		}

		if ($this->spaceToUnderscore)
		{
			$varInput = preg_replace('/\s+/i', '_', trim($varInput));
		}

		if (is_bool($this->trailingSlash) && $varInput != '')
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


	/**
	 * Check whether an option is checked
	 * @param array
	 * @return string
	 */
	protected function isChecked($arrOption)
	{
		if (empty($this->varValue) && $arrOption['default'])
		{
			return $this->optionChecked(1, 1);
		}

		return $this->optionChecked($arrOption['value'], $this->varValue);
	}


	/**
	 * Check whether an option is selected
	 * @param array
	 * @return string
	 */
	protected function isSelected($arrOption)
	{
		if (empty($this->varValue) && $arrOption['default'])
		{
			return $this->optionSelected(1, 1);
		}

		return $this->optionSelected($arrOption['value'], $this->varValue);
	}
}

?>