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
 * Class RadioButton
 *
 * Provide methods to handle radio buttons.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class RadioButton extends Widget
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
			case 'options':
				$this->arrOptions = deserialize($varValue);

				foreach ($this->arrOptions as $arrOptions)
				{
					if ($arrOptions['default'])
					{
						$this->varValue = $arrOptions['value'];
					}
				}
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
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$arrOptions = array();

		foreach ($this->arrOptions as $i=>$arrOption)
		{
			$arrOptions[] = sprintf('<input type="radio" name="%s" id="opt_%s" class="tl_radio" value="%s"%s%s onfocus="Backend.getScrollOffset();" /> <label for="opt_%s">%s</label>',
									 $this->strName,
									 $this->strId.'_'.$i,
									 specialchars($arrOption['value']),
									 ((is_array($this->varValue) && in_array($arrOption['value'] , $this->varValue) || $this->varValue == $arrOption['value']) ? ' checked="checked"' : ''),
									 $this->getAttributes(),
									 $this->strId.'_'.$i,
									 $arrOption['label']);
		}

		// Add a "no entries found" message if there are no options
		if (!count($arrOptions))
		{
			$arrOptions[]= '<p class="tl_noopt">'.$GLOBALS['TL_LANG']['MSC']['noResult'].'</p>';
		}

        return sprintf('<div id="ctrl_%s" class="tl_radio_container%s">%s</div>',
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						implode('<br />', $arrOptions));
	}
}

?>