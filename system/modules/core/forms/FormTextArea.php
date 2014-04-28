<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
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
 * Class FormTextArea
 *
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
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

			default:
				return parent::__get($strKey);
				break;
		}
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
		global $objPage;
		$arrStrip = array();

		// XHTML does not support maxlength
		if ($objPage->outputFormat == 'xhtml')
		{
			$arrStrip[] = 'maxlength';
		}

		$this->fieldAttributes = $this->getAttributes($arrStrip);
		$this->fieldValue = specialchars(str_replace('\n', "\n", $this->varValue));

		return parent::parse($arrAttributes);
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
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->intRows,
						$this->intCols,
						$this->fieldAttributes,
						$this->fieldValue) . $this->addSubmit();
	}
}
