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
 * Class FormTextArea
 *
 * @property string  $value
 * @property integer $maxlength
 * @property boolean $mandatory
 * @property string  $placeholder
 * @property string  $size
 * @property integer $rows
 * @property integer $cols
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FormTextArea extends \Widget
{

	/**
	 * Submit user input
	 *
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Add a for attribute
	 *
	 * @var boolean
	 */
	protected $blnForAttribute = true;

	/**
	 * Rows
	 *
	 * @var integer
	 */
	protected $intRows = 12;

	/**
	 * Columns
	 *
	 * @var integer
	 */
	protected $intCols = 80;

	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'form_textarea';

	/**
	 * The CSS class prefix
	 *
	 * @var string
	 */
	protected $strPrefix = 'widget widget-textarea';


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

			case 'size':
				$arrSize = deserialize($varValue);
				$this->intRows = $arrSize[0];
				$this->intCols = $arrSize[1];
				break;

			case 'rows':
				$this->intRows = $varValue;
				break;

			case 'cols':
				$this->intCols = $varValue;
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
			case 'cols':
				return $this->intCols;
				break;

			case 'rows':
				return $this->intRows;
				break;

			case 'value':
				return specialchars(str_replace('\n', "\n", $this->varValue));
				break;

			default:
				return parent::__get($strKey);
				break;
		}
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
		/** @var \PageModel $objPage */
		global $objPage;

		$arrStrip = array();

		// XHTML does not support maxlength
		if ($objPage->outputFormat == 'xhtml')
		{
			$arrStrip[] = 'maxlength';
		}

		return parent::getAttributes($arrStrip);
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string The widget markup
	 */
	public function generate()
	{
		return sprintf('<textarea name="%s" id="ctrl_%s" class="textarea%s" rows="%s" cols="%s"%s>%s</textarea>',
						$this->strName,
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						$this->intRows,
						$this->intCols,
						$this->getAttributes(),
						$this->value) . $this->addSubmit();
	}
}
