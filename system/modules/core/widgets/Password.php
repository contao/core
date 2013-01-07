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
 * Class Password
 *
 * Provide methods to handle password fields.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
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
	 * @param mixed
	 * @return string
	 */
	protected function validator($varInput)
	{
		$this->blnSubmitInput = false;

		if (($varInput == '' || $varInput == '*****') && $this->varValue != '')
		{
			return '*****';
		}

		if (utf8_strlen($varInput) < $GLOBALS['TL_CONFIG']['minPasswordLength'])
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['passwordLength'], $GLOBALS['TL_CONFIG']['minPasswordLength']));
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
						((strlen($this->description) && $GLOBALS['TL_CONFIG']['showHelp'] && !$this->hasErrors()) ? "\n  " . '<p class="tl_help tl_tip">'.$this->description.'</p>' : ''));
	}


	/**
	 * Generate the label of the confirmation field and return it as string
	 * @return string
	 */
	public function generateConfirmationLabel()
	{
		return sprintf('<label for="ctrl_%s_confirm" class="confirm%s">%s</label>',
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$GLOBALS['TL_LANG']['MSC']['confirm'][0]);
	}


	/**
	 * Generate the widget and return it as string
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
						((strlen($GLOBALS['TL_LANG']['MSC']['confirm'][1]) && $GLOBALS['TL_CONFIG']['showHelp']) ? "\n  " . '<p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['MSC']['confirm'][1].'</p>' : ''));
	}
}
