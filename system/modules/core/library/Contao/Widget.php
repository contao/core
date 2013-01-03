<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


/**
 * Generates and validates form fields
 * 
 * The class functions as abstract parent class for all widget classes and
 * provides methods to generate the form field markup and to validate the form
 * field input.
 * 
 * Usage:
 * 
 *     $widget = new TextField();
 *     $widget->name = 'test';
 *     $widget->label = 'Test';
 * 
 *     if ($_POST)
 *     {
 *         $widget->validate();
 * 
 *         if (!$widget->hasErrors())
 *         {
 *             echo $widget->value;
 *         }
 *     }
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
abstract class Widget extends \Controller
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
	 * Submit indicator
	 * @var boolean
	 */
	protected $blnSubmitInput = false;

	/**
	 * For attribute indicator
	 * @var boolean
	 */
	protected $blnForAttribute = false;


	/**
	 * Initialize the object
	 * 
	 * @param array An optional attributes array
	 */
	public function __construct($arrAttributes=null)
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
	 * Set an object property
	 * 
	 * Supported keys:
	 * 
	 * * id:                the field ID
	 * * name:              the field name
	 * * label:             the field label
	 * * value:             the field value
	 * * class:             one or more CSS classes
	 * * template:          the template name
	 * * wizard:            the field wizard markup
	 * * alt:               an alternative text
	 * * style:             the style attribute
	 * * accesskey:         the key to focus the field
	 * * tabindex:          the tabindex of the field
	 * * disabled:          adds the disabled attribute
	 * * readonly:          adds the readonly attribute
	 * * autofocus:         adds the autofocus attribute
	 * * required:          adds the required attribute
	 * 
	 * Event handler:
	 * 
	 * * onblur:            the blur event
	 * * onchange:          the change event
	 * * onclick:           the click event
	 * * ondblclick:        the double click event
	 * * onfocus:           the focus event
	 * * onmousedown:       the mouse down event
	 * * onmousemove:       the mouse move event
	 * * onmouseout:        the mouse out event
	 * * onmouseover:       the mouse over event
	 * * onmouseup:         the mouse up event
	 * * onkeydown:         the key down event
	 * * onkeypress:        the key press event
	 * * onkeyup:           the key up event
	 * * onselect:          the select event
	 * 
	 * Miscellaneous:
	 * 
	 * * mandatory:         the field value must not be empty
	 * * nospace:           does not allow whitespace characters
	 * * allowHtml:         allows HTML tags in the field value
	 * * addSubmit:         adds an inline submit button
	 * * storeFile:         store uploaded files in a given folder
	 * * useHomeDir:        store uploaded files in the user's home directory
	 * * trailingSlash:     add or remove a trailing slash
	 * * spaceToUnderscore: convert spaces to underscores
	 * 
	 * @param string $strKey   The property name
	 * @param mixed  $varValue The property value
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

				// Decrypt the value if it is encrypted
				if ($this->arrConfiguration['encrypt'])
				{
					$this->varValue = \Encryption::decrypt($this->varValue);
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

			case 'forAttribute':
				$this->blnForAttribute = $varValue;
				break;

			default:
				$this->arrConfiguration[$strKey] = $varValue;
				break;
		}
	}


	/**
	 * Return an object property
	 * 
	 * Supported keys:
	 * 
	 * * id:       the field ID
	 * * name:     the field name
	 * * label:    the field label
	 * * value:    the field value
	 * * class:    one or more CSS classes
	 * * template: the template name
	 * * wizard:   the field wizard markup
	 * * required: makes the widget a required field
	 * 
	 * @param string $strKey The property name
	 * 
	 * @return string The property value
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
				// Encrypt the value
				if ($this->arrConfiguration['encrypt'])
				{
					return \Encryption::encrypt($this->varValue);
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

			case 'forAttribute':
				return $this->blnForAttribute;
				break;

			default:
				if (isset($this->arrAttributes[$strKey]))
				{
					return $this->arrAttributes[$strKey];
				}
				elseif (isset($this->arrConfiguration[$strKey]))
				{
					return $this->arrConfiguration[$strKey];
				}
				break;
		}

		return parent::__get($strKey);
	}


	/**
	 * Add an error message
	 * 
	 * @param string $strError The error message
	 */
	public function addError($strError)
	{
		$this->class = 'error';
		$this->arrErrors[] = $strError;
	}


	/**
	 * Return true if the widget has errors
	 * 
	 * @return boolean True if there are errors
	 */
	public function hasErrors()
	{
		return !empty($this->arrErrors);
	}


	/**
	 * Return the errors array
	 * 
	 * @return array An array of error messages
	 */
	public function getErrors()
	{
		return $this->arrErrors;
	}


	/**
	 * Return a particular error as string
	 * 
	 * @param integer $intIndex The message index
	 * 
	 * @return string The corresponding error message
	 */
	public function getErrorAsString($intIndex=0)
	{
		return $this->arrErrors[$intIndex];
	}


	/**
	 * Return all errors as string separated by a given separator
	 * 
	 * @param string $strSeparator An optional separator (defaults to "<br>")
	 * 
	 * @return string The error messages string
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
	 * 
	 * @param integer $intIndex The message index
	 * 
	 * @return string The HTML markup of the corresponding error message
	 */
	public function getErrorAsHTML($intIndex=0)
	{
		return $this->hasErrors() ? sprintf('<p class="%s">%s</p>', ((TL_MODE == 'BE') ? 'tl_error tl_tip' : 'error'), $this->arrErrors[$intIndex]) : '';
	}


	/**
	 * Return true if the widgets submits user input
	 * 
	 * @return boolean True if the widget submits user input
	 */
	public function submitInput()
	{
		return $this->blnSubmitInput;
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
		if ($this->strTemplate == '')
		{
			return '';
		}

		$this->addAttributes($arrAttributes);

		ob_start();
		include $this->getTemplate($this->strTemplate, $this->strFormat);
		$strBuffer = ob_get_contents();
		ob_end_clean();

		return $strBuffer;
	}


	/**
	 * Generate the label and return it as string
	 * 
	 * @return string The label markup
	 */
	public function generateLabel()
	{
		if ($this->strLabel == '')
		{
			return '';
		}

		return sprintf('<label%s%s>%s%s%s</label>',
						($this->blnForAttribute ? ' for="ctrl_' . $this->strId . '"' : ''),
						(($this->strClass != '') ? ' class="' . $this->strClass . '"' : ''),
						($this->required ? '<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].'</span> ' : ''),
						$this->strLabel,
						($this->required ? '<span class="mandatory">*</span>' : ''));
	}


	/**
	 * Generate the widget and return it as string
	 * 
	 * @return string The widget markup
	 */
	abstract public function generate();


	/**
	 * Generate the widget with error message and return it as string
	 * 
	 * @param boolean $blnSwitchOrder If true, the error message will be shown below the field
	 * 
	 * @return string The form field markup
	 */
	public function generateWithError($blnSwitchOrder=false)
	{
		$strWidget = $this->generate();
		$strError = $this->getErrorAsHTML();

		return $blnSwitchOrder ? $strWidget . $strError : $strError . $strWidget;
	}


	/**
	 * Return all attributes as string
	 * 
	 * @param array $arrStrip An optional array with attributes to strip
	 * 
	 * @return string The attributes string
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
			if ($k == 'disabled' || $k == 'readonly' || $k == 'required' || $k == 'autofocus' || $k == 'multiple')
			{
				if (TL_MODE == 'FE') // see #3878
				{
					$strAttributes .= $blnIsXhtml ? ' ' . $k . '="' . $v . '"' : ' ' . $k;
				}
				elseif ($k == 'disabled' || $k == 'readonly' || $k == 'multiple') // see #4131
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
	 * Generate a submit button
	 * 
	 * @return string The submit button markup
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
	 * Validate the user input and set the value
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
	 * Find and return a $_POST variable
	 * 
	 * @param string $strKey The variable name
	 * 
	 * @return mixed The variable value
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
			$varValue = \Input::$strMethod(array_shift($arrParts), $this->decodeEntities);

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

		return \Input::$strMethod($strKey, $this->decodeEntities);
	}


	/**
	 * Recursively validate an input variable
	 * 
	 * @param mixed $varInput The user input
	 * 
	 * @return mixed The original or modified user input
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
					if (!\Validator::isNumeric($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['digit'], $this->strLabel));
					}
					break;

				// Alphabetic characters (including full stop [.] minus [-] and space [ ])
				case 'alpha':
					if (!\Validator::isAlphabetic($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['alpha'], $this->strLabel));
					}
					break;

				// Alphanumeric characters (including full stop [.] minus [-], underscore [_] and space [ ])
				case 'alnum':
					if (!\Validator::isAlphanumeric($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['alnum'], $this->strLabel));
					}
					break;

				// Do not allow any characters that are usually encoded by class Input [=<>()#/])
				case 'extnd':
					if (!\Validator::isExtendedAlphanumeric(html_entity_decode($varInput)))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['extnd'], $this->strLabel));
					}
					break;

				// Check whether the current value is a valid date format
				case 'date':
					if (!\Validator::isDate($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['date'], \Date::getInputFormat($GLOBALS['TL_CONFIG']['dateFormat'])));
					}

					// Validate the date (see #5086)
					try
					{
						new \Date($varInput);
					}
					catch (\OutOfBoundsException $e)
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalidDate'], $varInput));
					}
					break;

				// Check whether the current value is a valid time format
				case 'time':
					if (!\Validator::isTime($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['time'], \Date::getInputFormat($GLOBALS['TL_CONFIG']['timeFormat'])));
					}
					break;

				// Check whether the current value is a valid date and time format
				case 'datim':
					if (!\Validator::isDatim($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['dateTime'], \Date::getInputFormat($GLOBALS['TL_CONFIG']['datimFormat'])));
					}

					// Validate the date (see #5086)
					try
					{
						new \Date($varInput);
					}
					catch (\OutOfBoundsException $e)
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalidDate'], $varInput));
					}
					break;

				// Check whether the current value is a valid friendly name e-mail address
				case 'friendly':
					list ($strName, $varInput) = $this->splitFriendlyName($varInput);
					// no break;

				// Check whether the current value is a valid e-mail address
				case 'email':
					if (!\Validator::isEmail($varInput))
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
						$strEmail = \Idna::encodeEmail($strEmail);

						if (!\Validator::isEmail($strEmail))
						{
							$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['emails'], $this->strLabel));
							break;
						}
					}
					break;

				// Check whether the current value is a valid URL
				case 'url':
					if (!\Validator::isUrl($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['url'], $this->strLabel));
					}
					break;

				// Check whether the current value is a valid alias
				case 'alias':
					if (!\Validator::isAlias($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['alias'], $this->strLabel));
					}
					break;

				// Check whether the current value is a valid folder URL alias
				case 'folderalias':
					if (!\Validator::isFolderAlias($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['folderalias'], $this->strLabel));
					}
					break;

				// Phone numbers (numeric characters, space [ ], plus [+], minus [-], parentheses [()] and slash [/])
				case 'phone':
					if (!\Validator::isPhone(html_entity_decode($varInput)))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['phone'], $this->strLabel));
					}
					break;

				// Check whether the current value is a percent value
				case 'prcnt':
					if (!\Validator::isPercent($varInput))
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

		if ($this->nospace && preg_match('/[\t ]+/', $varInput))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['noSpace'], $this->strLabel));
		}

		if ($this->spaceToUnderscore)
		{
			$varInput = preg_replace('/\s+/', '_', trim($varInput));
		}

		if (is_bool($this->trailingSlash) && $varInput != '')
		{
			$varInput = preg_replace('/\/+$/', '', $varInput) . ($this->trailingSlash ? '/' : '');
		}

		return $varInput;
	}


	/**
	 * Take an associative array and add it to the object's attributes
	 * 
	 * @param array $arrAttributes An array of attributes
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
	 * 
	 * @param array $arrOption The options array
	 * 
	 * @return string The "checked" attribute or an empty string
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
	 * 
	 * @param array $arrOption The options array
	 * 
	 * @return string The "selected" attribute or an empty string
	 */
	protected function isSelected($arrOption)
	{
		if (empty($this->varValue) && $arrOption['default'])
		{
			return $this->optionSelected(1, 1);
		}

		return $this->optionSelected($arrOption['value'], $this->varValue);
	}


	/**
	 * Check whether an input is one of the given options
	 * 
	 * @param mixed $varInput The input string or array
	 * 
	 * @return boolean True if the selected option exists
	 */
	protected function isValidOption($varInput)
	{
		if (!is_array($varInput))
		{
			$varInput = array($varInput);
		}

		foreach ($varInput as $strInput)
		{
			foreach ($this->arrOptions as $k=>$v)
			{
				// Single dimensional array
				if (is_numeric($k))
				{
					if ($strInput == $v['value'])
					{
						return true;
					}
				}
				// Multi-dimensional array
				else
				{
					foreach ($v as $vv)
					{
						if ($strInput == $vv['value'])
						{
							return true;
						}
					}
				}
			}
		}

		return false;
	}
}
