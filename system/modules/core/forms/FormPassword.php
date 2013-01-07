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
 * Class FormPassword
 *
 * Form field "password".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class FormPassword extends \Widget
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
	protected $strTemplate = 'form_password';


	/**
	 * Always decode entities
	 * @param array
	 */
	public function __construct($arrAttributes=null)
	{
		parent::__construct($arrAttributes);
		$this->decodeEntities = true;
	}


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
					$this->arrAttributes['maxlength'] = $varValue;
				}
				break;

			case 'mandatory':
				// Do not set the "required" attribute here, because password
				// fields can remain empty if a password has been set already
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
	 * Validate input and set value
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		$this->blnSubmitInput = false;

		if (!strlen($varInput) && (strlen($this->varValue) || !$this->mandatory))
		{
			return '';
		}

		if (utf8_strlen($varInput) < $GLOBALS['TL_CONFIG']['minPasswordLength'])
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['passwordLength'], $GLOBALS['TL_CONFIG']['minPasswordLength']));
		}

		if ($varInput != $this->getPost($this->strName . '_confirm'))
		{
			$this->addError($GLOBALS['TL_LANG']['ERR']['passwordMatch']);
		}

		$varInput = parent::validator($varInput);

		if (!$this->hasErrors())
		{
			$this->blnSubmitInput = true;
			\Message::addConfirmation($GLOBALS['TL_LANG']['MSC']['pw_changed']);
			return \Encryption::hash($varInput);
		}

		return '';
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		return sprintf('<input type="password" name="%s" id="ctrl_%s" class="text password%s" value=""%s%s',
						$this->strName,
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->getAttributes(),
						$this->strTagEnding) . $this->addSubmit();
	}


	/**
	 * Generate the label of the confirmation field and return it as string
	 * @return string
	 */
	public function generateConfirmationLabel()
	{
		return sprintf('<label for="ctrl_%s_confirm" class="confirm%s">%s%s%s</label>',
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						($this->required ? '<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].'</span> ' : ''),
						sprintf($GLOBALS['TL_LANG']['MSC']['confirmation'], $this->strLabel),
						($this->required ? '<span class="mandatory">*</span>' : ''));
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generateConfirmation()
	{
		return sprintf('<input type="password" name="%s_confirm" id="ctrl_%s_confirm" class="text password confirm%s" value=""%s%s',
						$this->strName,
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->getAttributes(),
						$this->strTagEnding);
	}
}
