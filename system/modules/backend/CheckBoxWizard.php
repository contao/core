<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class CheckBoxWizard
 *
 * Provide methods to handle sortable checkboxes.
 * @copyright  Leo Feyer 2005-2013
 * @author     John Brand <http://www.thyon.com>
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class CheckBoxWizard extends Widget
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
	protected $strTemplate = 'be_widget_chk';

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
		$arrButtons = array('up', 'down');
		$strCommand = 'cmd_' . $this->strField;

		if (!is_array($this->varValue))
		{
			$this->varValue = array($this->varValue);
		}

		// Change the order
		if ($this->Input->get($strCommand) && is_numeric($this->Input->get('cid')) && $this->Input->get('id') == $this->currentRecord)
		{
			$this->import('Database');

			switch ($this->Input->get($strCommand))
			{
				case 'up':
					$this->varValue = array_move_up($this->varValue, $this->Input->get('cid'));
					break;

				case 'down':
					$this->varValue = array_move_down($this->varValue, $this->Input->get('cid'));
					break;
			}

			$this->Database->prepare("UPDATE " . $this->strTable . " SET " . $this->strField . "=? WHERE id=?")
						   ->execute(serialize($this->varValue), $this->currentRecord);

			$this->redirect(preg_replace('/&(amp;)?cid=[^&]*/i', '', preg_replace('/&(amp;)?' . preg_quote($strCommand, '/') . '=[^&]*/i', '', $this->Environment->request)));
		}

		// Sort options
		if ($this->varValue)
		{
			$arrOptions = array();
			$arrTemp = $this->arrOptions;

			// Move selected and sorted options to the top
			foreach ($this->arrOptions as $i=>$arrOption)
			{
				if (($intPos = array_search($arrOption['value'], $this->varValue)) !== false)
				{
					$arrOptions[$intPos] = $arrOption;
					unset($arrTemp[$i]);
				}
			}

			ksort($arrOptions);
			$this->arrOptions = array_merge($arrOptions, $arrTemp);
		}

		$blnCheckAll = true;
		$arrOptions = array();

		// Generate options and add buttons
		foreach ($this->arrOptions as $i=>$arrOption)
		{
			$strButtons = '';

			foreach ($arrButtons as $strButton)
			{
				$strButtons .= '<a href="'.$this->addToUrl('&amp;'.$strCommand.'='.$strButton.'&amp;cid='.$i.'&amp;id='.$this->currentRecord).'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['move_'.$strButton][1]).'" onclick="Backend.checkboxWizard(this,\''.$strButton.'\',\'ctrl_'.$this->strId.'\');return false">'.$this->generateImage($strButton.'.gif', $GLOBALS['TL_LANG']['MSC']['move_'.$strButton][0], 'class="tl_checkbox_wizard_img"').'</a> ';
			}

			$arrOptions[] = $this->generateCheckbox($arrOption, $i, $strButtons);
		}

		// Add a "no entries found" message if there are no options
		if (empty($arrOptions))
		{
			$arrOptions[]= '<p class="tl_noopt">'.$GLOBALS['TL_LANG']['MSC']['noResult'].'</p>';
			$blnCheckAll = false;
		}

        return sprintf('<fieldset id="ctrl_%s" class="tl_checkbox_container tl_checkbox_wizard%s"><legend>%s%s%s%s</legend><input type="hidden" name="%s" value="">%s%s</fieldset>%s',
        				$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						($this->required ? '<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].'</span> ' : ''),
						$this->strLabel,
						($this->required ? '<span class="mandatory">*</span>' : ''),
						$this->xlabel,
						$this->strName,
						($blnCheckAll ? '<span class="fixed"><input type="checkbox" id="check_all_' . $this->strId . '" class="tl_checkbox" onclick="Backend.toggleCheckboxGroup(this,\'ctrl_' . $this->strId . '\')"> <label for="check_all_' . $this->strId . '" style="color:#a6a6a6"><em>' . $GLOBALS['TL_LANG']['MSC']['selectAll'] . '</em></label></span>' : ''),
						implode('', $arrOptions),
						$this->wizard);
	}


	/**
	 * Generate a checkbox and return it as string
	 * @param array
	 * @param integer
	 * @param string
	 * @return string
	 */
	protected function generateCheckbox($arrOption, $i, $strButtons)
	{
		return sprintf('<span><input type="checkbox" name="%s" id="opt_%s" class="tl_checkbox" value="%s"%s%s onfocus="Backend.getScrollOffset()"> %s<label for="opt_%s">%s</label></span>',
						$this->strName . ($this->multiple ? '[]' : ''),
						$this->strId.'_'.$i,
						($this->multiple ? specialchars($arrOption['value']) : 1),
						((is_array($this->varValue) && in_array($arrOption['value'], $this->varValue) || $this->varValue == $arrOption['value']) ? ' checked="checked"' : ''),
						$this->getAttributes(),
						$strButtons,
						$this->strId.'_'.$i,
						$arrOption['label']);
	}
}

?>