<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class FormCaptcha
 *
 * File upload field.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class FormCaptcha extends Widget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_captcha';

	/**
	 * Captcha key
	 * @var string
	 */
	protected $strCaptchaKey;

	/**
	 * Security questions
	 * @var string
	 */
	protected $strQuestion;


	/**
	 * Initialize the object
	 * @param array
	 */
	public function __construct($arrAttributes=false)
	{
		parent::__construct($arrAttributes);

		$this->arrAttributes['maxlength'] = 2;
		$this->strCaptchaKey = 'c' . md5(uniqid(mt_rand(), true));
		$this->mandatory = true;
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
			case 'required':
			case 'mandatory':
				// Is set by default
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		$arrCaptcha = $this->Session->get('captcha_' . $this->strId);

		if (!is_array($arrCaptcha) || !strlen($arrCaptcha['key']) || !strlen($arrCaptcha['sum']) || $this->Input->post($arrCaptcha['key']) != $arrCaptcha['sum'] || $arrCaptcha['time'] > (time() - 3))
		{
			$this->class = 'error';
			$this->addError($GLOBALS['TL_LANG']['ERR']['captcha']);
		}

		$this->Session->set('captcha_' . $this->strId, '');
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

		if ($this->strQuestion == '')
		{
			$this->setQuestion();
		}

		return sprintf('<label for="ctrl_%s" class="mandatory%s">%s%s%s <span class="invisible">%s</span></label>',
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						'<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].'</span> ',
						$this->strLabel,
						'<span class="mandatory">*</span>',
						$this->strQuestion);
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		return sprintf('<input type="text" name="%s" id="ctrl_%s" class="captcha mandatory%s" value=""%s />',
						$this->strCaptchaKey,
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->getAttributes()) . $this->addSubmit();
	}


	/**
	 * Return the captcha question as string
	 * @return string
	 */
	public function generateQuestion()
	{
		if ($this->strQuestion == '')
		{
			$this->setQuestion();
		}

		return sprintf('<span class="captcha_text%s">%s</span>',
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->strQuestion);
	}


	/**
	 * Generate the captcha question
	 * @return string
	 */
	protected function setQuestion()
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

		$this->strQuestion = $strEncoded;
	}
}

?>