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
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class TrblField
 *
 * Provide methods to handle text fields with unit drop down menu.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class TrblField extends Widget
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
				$this->arrAttributes[$strKey] = ($varValue > 0) ? $varValue : '';
				break;

			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
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
								   ((is_array($this->varValue) && in_array($arrUnit['value'] , $this->varValue)) ? ' selected="selected"' : ''),
								   $arrUnit['label']);
		}

		$arrFields = array();
		$arrKeys = array('top', 'right', 'bottom', 'left');

		foreach ($arrKeys as $strKey)
		{
			$arrFields[] = sprintf('<input type="text" name="%s[%s]" id="ctrl_%s" class="tl_text_trbl trbl_%s%s" value="%s"%s onfocus="Backend.getScrollOffset();">',
									$this->strName,
									$strKey,
									$this->strId.'_'.$strKey,
									$strKey,
									(strlen($this->strClass) ? ' ' . $this->strClass : ''),
									specialchars($this->varValue[$strKey]),
									$this->getAttributes());
		}

		return sprintf('%s <select name="%s[unit]" class="tl_select_unit" onfocus="Backend.getScrollOffset();">%s</select>%s',
						implode(' ', $arrFields),
						$this->strName,
						implode('', $arrUnits),
						$this->wizard);
	}
}

?>