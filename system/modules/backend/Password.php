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
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class Password
 *
 * Provide methods to handle password fields.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class Password extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget_pw';


	/**
	 * Always decode entities
	 * @param array
	 */
	public function __construct($arrAttributes=false)
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
				$this->arrAttributes[$strKey] = ($varValue > 0) ? $varValue : '';
				break;

			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
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

		if (!strlen($varInput) && strlen($this->varValue))
		{
			return '';
		}

		if (utf8_strlen($varInput) < 8)
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['passwordLength'], 8));
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
			$_SESSION['TL_CONFIRM'][] = $GLOBALS['TL_LANG']['MSC']['pw_changed'];

			return sha1($varInput);
		}

		return '';
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		return sprintf('<input type="password" name="%s" id="ctrl_%s" class="tl_text tl_password%s" value=""%s onfocus="Backend.getScrollOffset();" />%s',
						$this->strName,
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->getAttributes(),
						((strlen($this->description) && $GLOBALS['TL_CONFIG']['showHelp']) ? "\n  " . '<p class="tl_help">'.$this->description.'</p>' : ''));
	}


	/**
	 * Generate the label of the confirmation field and return it as string
	 * @param array
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
	 * @param array
	 * @return string
	 */
	public function generateConfirmation()
	{
		return sprintf('<input type="password" name="%s_confirm" id="ctrl_%s_confirm" class="tl_text tl_password confirm%s" value=""%s onfocus="Backend.getScrollOffset();" />%s',
						$this->strName,
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->getAttributes(),
						((strlen($GLOBALS['TL_LANG']['MSC']['confirm'][1]) && $GLOBALS['TL_CONFIG']['showHelp']) ? "\n  " . '<p class="tl_help">'.$GLOBALS['TL_LANG']['MSC']['confirm'][1].'</p>' : ''));
	}
}

?>