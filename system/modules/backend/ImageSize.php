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
 * Class ImageSize
 *
 * Provide methods to handle image size fields.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ImageSize extends Widget
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
	 * Options
	 * @var array
	 */
	protected $arrOptions = array();


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

			case 'readonly':
				$this->arrAttributes['readonly'] = 'readonly';
				$this->blnSubmitInput = false;
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
	 * Trim values
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		$varInput[0] = parent::validator($varInput[0]);
		$varInput[1] = parent::validator($varInput[1]);
		$varInput[2] = preg_replace('/[^a-z0-9]+/', '', $varInput[2]);

		return $varInput;
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		if (!is_array($this->varValue))
		{
			$this->varValue = array($this->varValue);
		}

		$arrFields = array();

		for ($i=0; $i<2; $i++)
		{
			$arrFields[] = sprintf('<input type="text" name="%s[]" id="ctrl_%s" class="tl_text_4" value="%s"%s onfocus="Backend.getScrollOffset();">',
									$this->strName,
									$this->strId.'_'.$i,
									specialchars($this->varValue[$i]),
									$this->getAttributes());
		}

		$arrOptions = array();

		foreach ($this->arrOptions as $arrOption)
		{
			$arrOptions[] = sprintf('<option value="%s"%s>%s</option>',
								   specialchars($arrOption['value']),
								   ((is_array($this->varValue) && in_array($arrOption['value'] , $this->varValue)) ? ' selected="selected"' : ''),
								   $arrOption['label']);
		}

		$arrFields[] = sprintf('<select name="%s[]" id="ctrl_%s" class="tl_select_interval" onfocus="Backend.getScrollOffset();">%s</select>',
								$this->strName,
								$this->strId.'_3',
								implode(' ', $arrOptions));

		return sprintf('<div id="ctrl_%s"%s>%s</div>%s',
						$this->strId,
						(strlen($this->strClass) ? ' class="' . $this->strClass . '"' : ''),
						implode(' ', $arrFields),
						$this->wizard);
	}
}

?>