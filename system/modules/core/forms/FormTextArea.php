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
 * Form field "textarea".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FormTextArea extends \Widget
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
	 * Rows
	 * @var integer
	 */
	protected $intRows = 12;

	/**
	 * Columns
	 * @var integer
	 */
	protected $intCols = 80;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_widget';


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
					$this->arrAttributes['maxlength'] =  $varValue;
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
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		global $objPage;
		$arrStrip = array();

		// XHTML does not support maxlength
		if ($objPage->outputFormat == 'xhtml')
		{
			$arrStrip[] = 'maxlength';
		}

		return sprintf('<textarea name="%s" id="ctrl_%s" class="textarea%s" rows="%s" cols="%s"%s>%s</textarea>',
						$this->strName,
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->intRows,
						$this->intCols,
						$this->getAttributes($arrStrip),
						specialchars(str_replace('\n', "\n", $this->varValue))) . $this->addSubmit();
	}
}
