<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \Widget;


/**
 * Class InputUnit
 *
 * Provide methods to handle text fields with unit drop down menu.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class InputUnit extends Widget
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
	 * @return void
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

		if (!is_array($this->varValue))
		{
			$this->varValue = array('value'=>$this->varValue);
		}

		return sprintf('<input type="text" name="%s[value]" id="ctrl_%s" class="tl_text_unit%s" value="%s"%s onfocus="Backend.getScrollOffset()"> <select name="%s[unit]" class="tl_select_unit"%s onfocus="Backend.getScrollOffset()">%s</select>%s',
						$this->strName,
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						specialchars($this->varValue['value']),
						$this->getAttributes(),
						$this->strName,
						$this->getAttributes(),
						implode('', $arrUnits),
						$this->wizard);
	}
}
