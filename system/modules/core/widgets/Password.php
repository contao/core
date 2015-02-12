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
 * Provide methods to handle password fields.
 *
 * @property integer $maxlength
 * @property boolean $mandatory
 * @property string  $placeholder
 * @property string  $description
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Password extends \Widget
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
	protected $strTemplate = 'be_widget_pw';


	/**
	 * Always decode entities
	 *
	 * @param array $arrAttributes
	 */
	public function __construct($arrAttributes=null)
	{
		parent::__construct($arrAttributes);
		$this->decodeEntities = true;
	}


	/**
	 * Add specific attributes
	 *
	 * @param string $strKey
	 * @param mixed  $varValue
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
	 * Validate input and set value
	 *
	 * @param mixed $varInput
	 *
	 * @return string
	 */
	protected function validator($varInput)
	{
		$this->blnSubmitInput = false;

		if (($varInput == '' || $varInput == '*****') && $this->varValue != '')
		{
			return '*****';
		}

		if (utf8_strlen($varInput) < \Config::get('minPasswordLength'))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['passwordLength'], \Config::get('minPasswordLength')));
		}

		if ($varInput != $this->getPost($this->strName . '_confirm'))
		{
			$this->addError($GLOBALS['TL_LANG']['ERR']['passwordMatch']);
		}

		if ($varInput == $GLOBALS['TL_USERNAME'])
		{
			$this->addError($GLOBALS['TL_LANG']['ERR']['passwordName']);
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
	 *
	 * @return string
	 */
	public function generate()
	{
		return sprintf('<input type="password" autocomplete="off" name="%s" id="ctrl_%s" class="tl_text tl_password%s" value="%s"%s onfocus="Backend.getScrollOffset()">%s%s',
						$this->strName,
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						(($this->varValue != '') ? '*****' : ''),
						$this->getAttributes(),
						$this->wizard,
						((strlen($this->description) && \Config::get('showHelp') && !$this->hasErrors()) ? "\n  " . '<p class="tl_help tl_tip">'.$this->description.'</p>' : ''));
	}


	/**
	 * Generate the label of the confirmation field and return it as string
	 *
	 * @return string
	 */
	public function generateConfirmationLabel()
	{
		return sprintf('<label for="ctrl_%s_confirm" class="confirm%s">%s%s%s</label>',
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						($this->mandatory ? '<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].'</span> ' : ''),
						$GLOBALS['TL_LANG']['MSC']['confirm'][0],
						($this->mandatory ? '<span class="mandatory">*</span>' : ''));
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string
	 */
	public function generateConfirmation()
	{
		return sprintf('<input type="password" autocomplete="off" name="%s_confirm" id="ctrl_%s_confirm" class="tl_text tl_password confirm%s" value="%s"%s onfocus="Backend.getScrollOffset()">%s',
						$this->strName,
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						(($this->varValue != '') ? '*****' : ''),
						$this->getAttributes(),
						((strlen($GLOBALS['TL_LANG']['MSC']['confirm'][1]) && \Config::get('showHelp')) ? "\n  " . '<p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['MSC']['confirm'][1].'</p>' : ''));
	}
}
