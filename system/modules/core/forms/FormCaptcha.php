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
 * Class FormCaptcha
 *
 * @property string $name
 * @property string $question
 * @property string $placeholder
 * @property string $rowClass
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FormCaptcha extends \Widget
{

	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'form_captcha';

	/**
	 * Captcha key
	 *
	 * @var string
	 */
	protected $strCaptchaKey;

	/**
	 * Security questions
	 *
	 * @var string
	 */
	protected $strQuestion;

	/**
	 * The CSS class prefix
	 *
	 * @var string
	 */
	protected $strPrefix = 'widget widget-captcha mandatory';


	/**
	 * Initialize the object
	 *
	 * @param array $arrAttributes An optional attributes array
	 */
	public function __construct($arrAttributes=null)
	{
		parent::__construct($arrAttributes);

		$this->arrAttributes['maxlength'] = 2;
		$this->strCaptchaKey = 'c' . md5(uniqid(mt_rand(), true));
		$this->arrAttributes['required'] = true;
		$this->arrConfiguration['mandatory'] = true;
	}


	/**
	 * Add specific attributes
	 *
	 * @param string $strKey   The attribute name
	 * @param mixed  $varValue The attribute value
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'placeholder':
				$this->arrAttributes['placeholder'] = $varValue;
				break;

			case 'required':
			case 'mandatory':
			case 'minlength':
			case 'maxlength':
				// Ignore
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
			case 'name':
				return $this->strCaptchaKey;
				break;

			case 'question':
				return $this->strQuestion;
				break;

			default:
				return parent::__get($strKey);
				break;
		}
	}


	/**
	 * Validate the input and set the value
	 */
	public function validate()
	{
		$arrCaptcha = $this->Session->get('captcha_' . $this->strId);

		if (!is_array($arrCaptcha) || !strlen($arrCaptcha['key']) || !strlen($arrCaptcha['sum']) || \Input::post($arrCaptcha['key']) != $arrCaptcha['sum'] || $arrCaptcha['time'] > (time() - 3))
		{
			$this->class = 'error';
			$this->addError($GLOBALS['TL_LANG']['ERR']['captcha']);
		}

		$this->Session->set('captcha_' . $this->strId, '');
	}


	/**
	 * Generate the captcha question
	 *
	 * @return string The question string
	 */
	protected function getQuestion()
	{
		$int1 = rand(1, 9);
		$int2 = rand(1, 9);

		$question = $GLOBALS['TL_LANG']['SEC']['question' . rand(1, 3)];
		$question = sprintf($question, $int1, $int2);

		$this->Session->set('captcha_' . $this->strId, array
		(
			'sum' => $int1 + $int2,
			'key' => $this->strCaptchaKey,
			'time' => time()
		));

		$strEncoded = '';
		$arrCharacters = utf8_str_split($question);

		foreach ($arrCharacters as $strCharacter)
		{
			$strEncoded .= sprintf('&#%s;', utf8_ord($strCharacter));
		}

		return $strEncoded;
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

		return sprintf('<label for="ctrl_%s" class="mandatory%s">%s%s%s <span class="invisible">%s</span></label>',
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						'<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].'</span> ',
						$this->strLabel,
						'<span class="mandatory">*</span>',
						$this->getQuestion());
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string The widget markup
	 */
	public function generate()
	{
		return sprintf('<input type="text" name="%s" id="ctrl_%s" class="captcha mandatory%s" value=""%s%s',
						$this->strCaptchaKey,
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						$this->getAttributes(),
						$this->strTagEnding) . $this->addSubmit();
	}


	/**
	 * Return the captcha question as string
	 *
	 * @return string The question markup
	 */
	public function generateQuestion()
	{
		return sprintf('<span class="captcha_text%s">%s</span>',
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						$this->getQuestion());
	}
}
