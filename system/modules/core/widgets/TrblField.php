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
 * Class TrblField
 *
 * Provide methods to handle text fields with unit drop down menu.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class TrblField extends \Widget
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
	 * Units
	 * @var array
	 */
	protected $arrUnits = array();


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

			case 'options':
				$this->arrUnits = deserialize($varValue);
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Do not validate unit fields
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		foreach ($varInput as $k=>$v)
		{
			if ($k != 'unit')
			{
				$varInput[$k] = parent::validator(trim($v));
			}
		}

		return $varInput;
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$arrUnits = array();

		foreach ($this->arrUnits as $arrUnit)
		{
			$arrUnits[] = sprintf('<option value="%s"%s>%s</option>',
								   specialchars($arrUnit['value']),
								   $this->isSelected($arrUnit),
								   $arrUnit['label']);
		}

		$arrFields = array();
		$arrKeys = array('top', 'right', 'bottom', 'left');

		if (!is_array($this->varValue))
		{
			$this->varValue = array();
		}

		foreach ($arrKeys as $strKey)
		{
			$arrFields[] = sprintf('<input type="text" name="%s[%s]" id="ctrl_%s" class="tl_text_trbl trbl_%s%s" value="%s"%s onfocus="Backend.getScrollOffset()">',
									$this->strName,
									$strKey,
									$this->strId.'_'.$strKey,
									$strKey,
									(($this->strClass != '') ? ' ' . $this->strClass : ''),
									specialchars(@$this->varValue[$strKey]), // see #4979
									$this->getAttributes());
		}

		return sprintf('%s <select name="%s[unit]" class="tl_select_unit" onfocus="Backend.getScrollOffset()">%s</select>%s',
						implode(' ', $arrFields),
						$this->strName,
						implode('', $arrUnits),
						$this->wizard);
	}
}
