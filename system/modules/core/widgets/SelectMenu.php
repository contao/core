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
 * Provide methods to handle select menus.
 *
 * @property boolean $mandatory
 * @property integer $size
 * @property boolean $multiple
 * @property array   $options
 * @property boolean $chosen
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class SelectMenu extends \Widget
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
	protected $strTemplate = 'be_widget';


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

			case 'size':
				if ($this->multiple)
				{
					$this->arrAttributes['size'] = $varValue;
				}
				break;

			case 'multiple':
				if ($varValue)
				{
					$this->arrAttributes['multiple'] = 'multiple';
				}
				break;

			case 'options':
				$this->arrOptions = deserialize($varValue);
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Check for a valid option (see #4383)
	 */
	public function validate()
	{
		$varValue = $this->getPost($this->strName);

		if (!empty($varValue) && !$this->isValidOption($varValue))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalid'], (is_array($varValue) ? implode(', ', $varValue) : $varValue)));
		}

		parent::validate();
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string
	 */
	public function generate()
	{
		$arrOptions = array();
		$strClass = 'tl_select';

		if ($this->multiple)
		{
			$this->strName .= '[]';
			$strClass = 'tl_mselect';
		}

		// Add an empty option (XHTML) if there are none
		if (empty($this->arrOptions))
		{
			$this->arrOptions = array(array('value'=>'', 'label'=>'-'));
		}

		foreach ($this->arrOptions as $strKey=>$arrOption)
		{
			if (isset($arrOption['value']))
			{
				$arrOptions[] = sprintf('<option value="%s"%s>%s</option>',
										 specialchars($arrOption['value']),
										 $this->isSelected($arrOption),
										 $arrOption['label']);
			}
			else
			{
				$arrOptgroups = array();

				foreach ($arrOption as $arrOptgroup)
				{
					$arrOptgroups[] = sprintf('<option value="%s"%s>%s</option>',
											   specialchars($arrOptgroup['value']),
											   $this->isSelected($arrOptgroup),
											   $arrOptgroup['label']);
				}

				$arrOptions[] = sprintf('<optgroup label="&nbsp;%s">%s</optgroup>', specialchars($strKey), implode('', $arrOptgroups));
			}
		}

		// Chosen
		if ($this->chosen)
		{
			$strClass .= ' tl_chosen';
		}

		return sprintf('<select name="%s" id="ctrl_%s" class="%s%s"%s onfocus="Backend.getScrollOffset()">%s</select>%s',
						$this->strName,
						$this->strId,
						$strClass,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						$this->getAttributes(),
						implode('', $arrOptions),
						$this->wizard);
	}
}
