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
 * Class TextField
 *
 * Provide methods to handle text fields.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class TextField extends \Widget
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
	protected $strTemplate = 'be_widget';


	/**
	 * Disable the for attribute if the "multiple" option is set
	 * @param array
	 */
	public function __construct($arrAttributes=null)
	{
		parent::__construct($arrAttributes);

		if ($this->multiple)
		{
			$this->blnForAttribute = false;
		}
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
	 * Trim values
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		if (is_array($varInput))
		{
			return parent::validator($varInput);
		}

		return parent::validator(trim($varInput));
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$type = $this->hideInput ? 'password' : 'text';

		if (!$this->multiple)
		{
			// Hide the Punycode format (see #2750)
			if ($this->rgxp == 'email' || $this->rgxp == 'url')
			{
				$this->varValue = \Idna::decode($this->varValue);
			}

			return sprintf('<input type="%s" name="%s" id="ctrl_%s" class="tl_text%s" value="%s"%s onfocus="Backend.getScrollOffset()">%s',
							$type,
							$this->strName,
							$this->strId,
							(($this->strClass != '') ? ' ' . $this->strClass : ''),
							specialchars($this->varValue),
							$this->getAttributes(),
							$this->wizard);
		}

		// Return if field size is missing
		if (!$this->size)
		{
			return '';
		}

		if (!is_array($this->varValue))
		{
			$this->varValue = array($this->varValue);
		}

		$arrFields = array();

		for ($i=0; $i<$this->size; $i++)
		{
			$arrFields[] = sprintf('<input type="%s" name="%s[]" id="ctrl_%s" class="tl_text_%s" value="%s"%s onfocus="Backend.getScrollOffset()">',
									$type,
									$this->strName,
									$this->strId.'_'.$i,
									$this->size,
									specialchars(@$this->varValue[$i]), // see #4979
									$this->getAttributes());
		}

		return sprintf('<div id="ctrl_%s"%s>%s</div>%s',
						$this->strId,
						(($this->strClass != '') ? ' class="' . $this->strClass . '"' : ''),
						implode(' ', $arrFields),
						$this->wizard);
	}
}
