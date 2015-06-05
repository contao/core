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
 * @property string                  $id                The field ID
 * @property string                  $name              the field name
 * @property string                  $label             The field label
 * @property mixed                   $value             The field value
 * @property string                  $class             One or more CSS classes
 * @property string                  $prefix            The CSS class prefix
 * @property string                  $template          The template name
 * @property string                  $wizard            The field wizard markup
 * @property string                  $alt               The alternative text
 * @property string                  $style             The style attribute
 * @property string                  $accesskey         The key to focus the field
 * @property integer                 $tabindex          The tabindex of the field
 * @property boolean                 $disabled          Adds the disabled attribute
 * @property boolean                 $readonly          Adds the readonly attribute
 * @property boolean                 $autofocus         Adds the autofocus attribute
 * @property boolean                 $required          Adds the required attribute
 * @property string                  $onblur            The blur event
 * @property string                  $onchange          The change event
 * @property string                  $onclick           The click event
 * @property string                  $ondblclick        The double click event
 * @property string                  $onfocus           The focus event
 * @property string                  $onmousedown       The mouse down event
 * @property string                  $onmousemove       The mouse move event
 * @property string                  $onmouseout        The mouse out event
 * @property string                  $onmouseover       The mouse over event
 * @property string                  $onmouseup         The mouse up event
 * @property string                  $onkeydown         The key down event
 * @property string                  $onkeypress        The key press event
 * @property string                  $onkeyup           The key up event
 * @property string                  $onselect          The select event
 * @property boolean                 $mandatory         The field value must not be empty
 * @property boolean                 $nospace           Do not allow whitespace characters
 * @property boolean                 $allowHtml         Allow HTML tags in the field value
 * @property boolean                 $addSubmit         Add an inline submit button
 * @property boolean                 $storeFile         Store uploaded files in a given folder
 * @property boolean                 $useHomeDir        Store uploaded files in the user's home directory
 * @property boolean                 $trailingSlash     Add or remove a trailing slash
 * @property boolean                 $spaceToUnderscore Convert spaces to underscores
 * @property boolean                 $nullIfEmpty       Set to NULL if the value is empty
 * @property boolean                 $doNotTrim         Do not trim the user input
 * @property string                  $forAttribute      The "for" attribute
 * @property \DataContainer          $dataContainer     The data container object
 * @property \Database\Result|object $activeRecord      The active record
 * @property string                  $mandatoryField    The "mandatory field" label
 * @property string                  $customTpl         A custom template name
 * @property string                  $slabel            The submit button label
 * @property boolean                 $preserveTags      Preserve HTML tags
 * @property boolean                 $decodeEntities    Decode HTML entities
 * @property integer                 $minlength         The minimum length
 * @property integer                 $maxlength         The maximum length
 * @property integer                 $minval            The minimum value
 * @property integer                 $maxval            The maximum value
 * @property integer                 $rgxp              The regular expression name
 * @property boolean                 $isHexColor        The field value is a hex color
 * @property string                  $strTable          The table name
 * @property string                  $strField          The field name
 * @property string                  $xlabel
 * @property integer                 $currentRecord
 * @property integer                 $rowClass
 * @property integer                 $rowClassConfirm
 * @property integer                 $storeValues
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
abstract class Widget extends \BaseTemplate
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
	 * CSS class prefix
	 * @var string
	 */
	protected $strPrefix;

	/**
	 * Wizard
	 * @var string
	 */
	protected $strWizard;

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
	 * Options
	 * @var array
	 */
	protected $arrOptions = array();

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
	 * Data container
	 * @var object
	 */
	protected $objDca;


	/**
	 * Initialize the object
	 *
	 * @param array $arrAttributes An optional attributes array
	 */
	public function __construct($arrAttributes=null)
	{
		parent::__construct();

		// Override the output format in the front end
		if (TL_MODE == 'FE')
		{
			/** @var \PageModel $objPage */
			global $objPage;

			if ($objPage->outputFormat != '')
			{
				$this->strFormat = $objPage->outputFormat;
				$this->strTagEnding = ($this->strFormat == 'xhtml') ? ' />' : '>';
			}
		}

		$this->addAttributes($arrAttributes);
	}


	/**
	 * Set an object property
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

			case 'prefix':
				$this->strPrefix = $varValue;
				break;

			case 'template':
				$this->strTemplate = $varValue;
				break;

			case 'wizard':
				$this->strWizard = $varValue;
				break;

			case 'autocomplete':
			case 'autocorrect':
			case 'autocapitalize':
			case 'spellcheck':
				if (is_bool($varValue))
				{
					$varValue = $varValue ? 'on' : 'off';
				}
				// Do not add a break; statement here

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
				$this->blnSubmitInput = $varValue ? false : true;
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
			case 'nullIfEmpty':
			case 'doNotTrim':
				$this->arrConfiguration[$strKey] = $varValue ? true : false;
				break;

			case 'forAttribute':
				$this->blnForAttribute = $varValue;
				break;

			case 'dataContainer':
				$this->objDca = $varValue;
				break;

			case strncmp($strKey, 'ng-', 3) === 0:
			case strncmp($strKey, 'data-', 5) === 0:
				$this->arrAttributes[$strKey] = $varValue;
				break;

			default:
				$this->arrConfiguration[$strKey] = $varValue;
				break;
		}
	}


	/**
	 * Return an object property
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
				elseif ($this->arrConfiguration['nullIfEmpty'] && $this->varValue == '')
				{
					return null;
				}
				return $this->varValue;
				break;

			case 'class':
				return $this->strClass;
				break;

			case 'prefix':
				return $this->strPrefix;
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

			case 'dataContainer':
				return $this->objDca;
				break;

			case 'activeRecord':
				return $this->objDca->activeRecord;
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
	 * Check whether an object property exists
	 *
	 * @param string $strKey The property name
	 *
	 * @return boolean True if the property exists
	 */
	public function __isset($strKey)
	{
		switch ($strKey)
		{
			case 'id':
				return isset($this->strId);
				break;

			case 'name':
				return isset($this->strName);
				break;

			case 'label':
				return isset($this->strLabel);
				break;

			case 'value':
				return isset($this->varValue);
				break;

			case 'class':
				return isset($this->strClass);
				break;

			case 'template':
				return isset($this->strTemplate);
				break;

			case 'wizard':
				return isset($this->strWizard);
				break;

			case 'required':
				return isset($this->arrConfiguration[$strKey]);
				break;

			case 'forAttribute':
				return isset($this->blnForAttribute);
				break;

			case 'dataContainer':
				return isset($this->objDca);
				break;

			case 'activeRecord':
				return isset($this->objDca->activeRecord);
				break;

			default:
				return isset($this->arrAttributes[$strKey]) || isset($this->arrConfiguration[$strKey]);
				break;
		}
	}


	/**
	 * Add an attribute
	 *
	 * @param string $strName  The attribute name
	 * @param mixed  $varValue The attribute value
	 */
	public function addAttribute($strName, $varValue)
	{
		$this->arrAttributes[$strName] = $varValue;
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

		$this->mandatoryField = $GLOBALS['TL_LANG']['MSC']['mandatory'];

		if ($this->customTpl != '' && TL_MODE == 'FE')
		{
			$this->strTemplate = $this->customTpl;
		}

		$strBuffer = parent::parse();

		// HOOK: add custom parse filters (see #5553)
		if (isset($GLOBALS['TL_HOOKS']['parseWidget']) && is_array($GLOBALS['TL_HOOKS']['parseWidget']))
		{
			foreach ($GLOBALS['TL_HOOKS']['parseWidget'] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($strBuffer, $this);
			}
		}

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
						($this->mandatory ? '<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].'</span> ' : ''),
						$this->strLabel,
						($this->mandatory ? '<span class="mandatory">*</span>' : ''));
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
		$strAttributes = '';

		foreach (array_keys($this->arrAttributes) as $strKey)
		{
			if (!in_array($strKey, $arrStrip))
			{
				$strAttributes .= $this->getAttribute($strKey);
			}
		}

		return $strAttributes;
	}


	/**
	 * Return a single attribute
	 *
	 * @param string $strKey The attribute name
	 *
	 * @return string The attribute markup
	 */
	public function getAttribute($strKey)
	{
		if (!isset($this->arrAttributes[$strKey]))
		{
			return '';
		}

		$blnIsXhtml = false;

		if (TL_MODE == 'FE')
		{
			/** @var \PageModel $objPage */
			global $objPage;

			if ($objPage->outputFormat == 'xhtml')
			{
				$blnIsXhtml = true;
			}
		}

		if ($blnIsXhtml)
		{
			if ($strKey == 'autofocus' || $strKey == 'placeholder' || $strKey == 'required')
			{
				return '';
			}
		}

		$varValue = $this->arrAttributes[$strKey];

		if ($strKey == 'disabled' || $strKey == 'readonly' || $strKey == 'required' || $strKey == 'autofocus' || $strKey == 'multiple')
		{
			if (TL_MODE == 'FE') // see #3878
			{
				return $blnIsXhtml ? ' ' . $strKey . '="' . $varValue . '"' : ' ' . $strKey;
			}
			elseif ($strKey == 'disabled' || $strKey == 'readonly' || $strKey == 'multiple') // see #4131
			{
				return ' ' . $strKey;
			}
		}
		else
		{
			if ($varValue != '')
			{
				return ' ' . $strKey . '="' . $varValue . '"';
			}
		}

		return '';
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
		$varValue = $this->validator($this->getPost($this->strName));

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

			foreach ($arrParts as $part)
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

		if (!$this->doNotTrim)
		{
			$varInput = trim($varInput);
		}

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

		if ($this->minval && is_numeric($varInput) && $varInput < $this->minval)
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['minval'], $this->strLabel, $this->minval));
		}

		if ($this->maxval && is_numeric($varInput) && $varInput > $this->maxval)
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['maxval'], $this->strLabel, $this->maxval));
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

				// Numeric characters (including full stop [.] and minus [-])
				case 'digit':
					// Support decimal commas and convert them automatically (see #3488)
					if (substr_count($varInput, ',') == 1 && strpos($varInput, '.') === false)
					{
						$varInput = str_replace(',', '.', $varInput);
					}
					if (!\Validator::isNumeric($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['digit'], $this->strLabel));
					}
					break;

				// Natural numbers (positive integers)
				case 'natural':
					if (!\Validator::isNatural($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['natural'], $this->strLabel));
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
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['date'], \Date::getInputFormat(\Date::getNumericDateFormat())));
					}
					else
					{
						// Validate the date (see #5086)
						try
						{
							new \Date($varInput, \Date::getNumericDateFormat());
						}
						catch (\OutOfBoundsException $e)
						{
							$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalidDate'], $varInput));
						}
					}
					break;

				// Check whether the current value is a valid time format
				case 'time':
					if (!\Validator::isTime($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['time'], \Date::getInputFormat(\Date::getNumericTimeFormat())));
					}
					break;

				// Check whether the current value is a valid date and time format
				case 'datim':
					if (!\Validator::isDatim($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['dateTime'], \Date::getInputFormat(\Date::getNumericDatimFormat())));
					}
					else
					{
						// Validate the date (see #5086)
						try
						{
							new \Date($varInput, \Date::getNumericDatimFormat());
						}
						catch (\OutOfBoundsException $e)
						{
							$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalidDate'], $varInput));
						}
					}
					break;

				// Check whether the current value is a valid friendly name e-mail address
				case 'friendly':
					list ($strName, $varInput) = \String::splitFriendlyEmail($varInput);
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

				// Check whether the current value is a locale
				case 'locale':
					if (!\Validator::isLocale($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['locale'], $this->strLabel));
					}
					break;

				// Check whether the current value is a language code
				case 'language':
					if (!\Validator::isLanguage($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['language'], $this->strLabel));
					}
					break;

				// Check whether the current value is a Google+ ID or vanity name
				case 'google+':
					if (!\Validator::isGooglePlusId($varInput))
					{
						$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalidGoogleId'], $this->strLabel));
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
		if (empty($this->varValue) && empty($_POST) && $arrOption['default'])
		{
			return static::optionChecked(1, 1);
		}

		return static::optionChecked($arrOption['value'], $this->varValue);
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
		if (empty($this->varValue) && empty($_POST) && $arrOption['default'])
		{
			return static::optionSelected(1, 1);
		}

		return static::optionSelected($arrOption['value'], $this->varValue);
	}


	/**
	 * Return a "selected" attribute if the option is selected
	 *
	 * @param string $strOption The option to check
	 * @param mixed  $varValues One or more values to check against
	 *
	 * @return string The attribute or an empty string
	 */
	public static function optionSelected($strOption, $varValues)
	{
		if ($strOption === '')
		{
			return '';
		}

		$attribute = ' selected';

		if (TL_MODE == 'FE')
		{
			/** @var \PageModel $objPage */
			global $objPage;

			if ($objPage->outputFormat == 'xhtml')
			{
				$attribute = ' selected="selected"';
			}
		}

		return (is_array($varValues) ? in_array($strOption, $varValues) : $strOption == $varValues) ? $attribute : '';
	}


	/**
	 * Return a "checked" attribute if the option is checked
	 *
	 * @param string $strOption The option to check
	 * @param mixed  $varValues One or more values to check against
	 *
	 * @return string The attribute or an empty string
	 */
	public static function optionChecked($strOption, $varValues)
	{
		if ($strOption === '')
		{
			return '';
		}

		$attribute = ' checked';

		if (TL_MODE == 'FE')
		{
			/** @var \PageModel $objPage */
			global $objPage;

			if ($objPage->outputFormat == 'xhtml')
			{
				$attribute = ' checked="checked"';
			}
		}

		return (is_array($varValues) ? in_array($strOption, $varValues) : $strOption == $varValues) ? $attribute : '';
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

		// Check each option
		foreach ($varInput as $strInput)
		{
			$blnFound = false;

			foreach ($this->arrOptions as $v)
			{
				// Single dimensional array
				if (array_key_exists('value', $v))
				{
					if ($strInput == $v['value'])
					{
						$blnFound = true;
					}
				}
				// Multi-dimensional array
				else
				{
					foreach ($v as $vv)
					{
						if ($strInput == $vv['value'])
						{
							$blnFound = true;
						}
					}
				}
			}

			if (!$blnFound)
			{
				return false;
			}
		}

		return true;
	}


	/**
	 * Extract the Widget attributes from a Data Container array
	 *
	 * @param array  $arrData  The field configuration array
	 * @param string $strName  The field name in the form
	 * @param mixed  $varValue The field value
	 * @param string $strField The field name in the database
	 * @param string $strTable The table name in the database
	 * @param object $objDca   An optional DataContainer object
	 *
	 * @return array An attributes array that can be passed to a widget
	 */
	public static function getAttributesFromDca($arrData, $strName, $varValue=null, $strField='', $strTable='', $objDca=null)
	{
		$arrAttributes = $arrData['eval'];

		$arrAttributes['id'] = $strName;
		$arrAttributes['name'] = $strName;
		$arrAttributes['strField'] = $strField;
		$arrAttributes['strTable'] = $strTable;
		$arrAttributes['label'] = (($label = is_array($arrData['label']) ? $arrData['label'][0] : $arrData['label']) != false) ? $label : $strField;
		$arrAttributes['description'] = $arrData['label'][1];
		$arrAttributes['type'] = $arrData['inputType'];
		$arrAttributes['dataContainer'] = $objDca;

		// Internet Explorer does not support onchange for checkboxes and radio buttons
		if ($arrData['eval']['submitOnChange'])
		{
			if ($arrData['inputType'] == 'checkbox' || $arrData['inputType'] == 'checkboxWizard' || $arrData['inputType'] == 'radio' || $arrData['inputType'] == 'radioTable')
			{
				$arrAttributes['onclick'] = trim($arrAttributes['onclick'] . " Backend.autoSubmit('".$strTable."')");
			}
			else
			{
				$arrAttributes['onchange'] = trim($arrAttributes['onchange'] . " Backend.autoSubmit('".$strTable."')");
			}
		}

		$arrAttributes['allowHtml'] = ($arrData['eval']['allowHtml'] || strlen($arrData['eval']['rte']) || $arrData['eval']['preserveTags']) ? true : false;

		// Decode entities if HTML is allowed
		if ($arrAttributes['allowHtml'] || $arrData['inputType'] == 'fileTree')
		{
			$arrAttributes['decodeEntities'] = true;
		}

		// Add Ajax event
		if ($arrData['inputType'] == 'checkbox' && is_array($GLOBALS['TL_DCA'][$strTable]['subpalettes']) && in_array($strField, array_keys($GLOBALS['TL_DCA'][$strTable]['subpalettes'])) && $arrData['eval']['submitOnChange'])
		{
			$arrAttributes['onclick'] = "AjaxRequest.toggleSubpalette(this, 'sub_".$strName."', '".$strField."')";
		}

		// Options callback
		if (is_array($arrData['options_callback']))
		{
			$arrCallback = $arrData['options_callback'];
			$arrData['options'] = static::importStatic($arrCallback[0])->$arrCallback[1]($objDca);
		}
		elseif (is_callable($arrData['options_callback']))
		{
			$arrData['options'] = $arrData['options_callback']($objDca);
		}

		// Foreign key
		elseif (isset($arrData['foreignKey']))
		{
			$arrKey = explode('.', $arrData['foreignKey'], 2);
			$objOptions = \Database::getInstance()->query("SELECT id, " . $arrKey[1] . " AS value FROM " . $arrKey[0] . " WHERE tstamp>0 ORDER BY value");
			$arrData['options'] = array();

			while ($objOptions->next())
			{
				$arrData['options'][$objOptions->id] = $objOptions->value;
			}
		}

		// Add default option to single checkbox
		if ($arrData['inputType'] == 'checkbox' && !isset($arrData['options']) && !isset($arrData['options_callback']) && !isset($arrData['foreignKey']))
		{
			if (TL_MODE == 'FE' && isset($arrAttributes['description']))
			{
				$arrAttributes['options'][] = array('value'=>1, 'label'=>$arrAttributes['description']);
			}
			else
			{
				$arrAttributes['options'][] = array('value'=>1, 'label'=>$arrAttributes['label']);
			}
		}

		// Add options
		if (is_array($arrData['options']))
		{
			$blnIsAssociative = ($arrData['eval']['isAssociative'] || array_is_assoc($arrData['options']));
			$blnUseReference = isset($arrData['reference']);

			if ($arrData['eval']['includeBlankOption'] && !$arrData['eval']['multiple'])
			{
				$strLabel = isset($arrData['eval']['blankOptionLabel']) ? $arrData['eval']['blankOptionLabel'] : '-';
				$arrAttributes['options'][] = array('value'=>'', 'label'=>$strLabel);
			}

			foreach ($arrData['options'] as $k=>$v)
			{
				if (!is_array($v))
				{
					$arrAttributes['options'][] = array('value'=>($blnIsAssociative ? $k : $v), 'label'=>($blnUseReference ? ((($ref = (is_array($arrData['reference'][$v]) ? $arrData['reference'][$v][0] : $arrData['reference'][$v])) != false) ? $ref : $v) : $v));
					continue;
				}

				$key = $blnUseReference ? ((($ref = (is_array($arrData['reference'][$k]) ? $arrData['reference'][$k][0] : $arrData['reference'][$k])) != false) ? $ref : $k) : $k;
				$blnIsAssoc = array_is_assoc($v);

				foreach ($v as $kk=>$vv)
				{
					$arrAttributes['options'][$key][] = array('value'=>($blnIsAssoc ? $kk : $vv), 'label'=>($blnUseReference ? ((($ref = (is_array($arrData['reference'][$vv]) ? $arrData['reference'][$vv][0] : $arrData['reference'][$vv])) != false) ? $ref : $vv) : $vv));
				}
			}
		}

		$arrAttributes['value'] = deserialize($varValue);

		// Convert timestamps
		if ($varValue != '' && in_array($arrData['eval']['rgxp'], array('date', 'time', 'datim')))
		{
			$objDate = new \Date($varValue, \Date::getFormatFromRgxp($arrData['eval']['rgxp']));
			$arrAttributes['value'] = $objDate->{$arrData['eval']['rgxp']};
		}

		// Add the "rootNodes" array as attribute (see #3563)
		if (isset($arrData['rootNodes']) && !isset($arrData['eval']['rootNodes']))
		{
			$arrAttributes['rootNodes'] = $arrData['rootNodes'];
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getAttributesFromDca']) && is_array($GLOBALS['TL_HOOKS']['getAttributesFromDca']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getAttributesFromDca'] as $callback)
			{
				$arrAttributes = static::importStatic($callback[0])->$callback[1]($arrAttributes, $objDca);
			}
		}

		return $arrAttributes;
	}


	/**
	 * Return the empty value based on the SQL string
	 *
	 * @return string|integer|null The empty value
	 */
	public function getEmptyValue()
	{
		if (!isset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['sql']))
		{
			return '';
		}

		return static::getEmptyValueByFieldType($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['sql']);
	}


	/**
	 * Return the empty value based on the SQL string
	 *
	 * @param string $sql The SQL string
	 *
	 * @return string|integer|null The empty value
	 */
	public static function getEmptyValueByFieldType($sql)
	{
		if ($sql == '')
		{
			return '';
		}

		$type = preg_replace('/^([A-Za-z]+)(\(| ).*$/', '$1', $sql);

		if (in_array($type, array('binary', 'varbinary', 'tinyblob', 'blob', 'mediumblob', 'longblob')))
		{
			return null;
		}
		elseif (in_array($type, array('int', 'integer', 'tinyint', 'smallint', 'mediumint', 'bigint', 'float', 'double', 'dec', 'decimal')))
		{
			return 0;
		}
		else
		{
			return '';
		}
	}
}
