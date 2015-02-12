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
 * Provide methods to handle sortable checkboxes.
 *
 * @property array   $options
 * @property boolean $multiple
 *
 * @author John Brand <http://www.thyon.com>
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class CheckBoxWizard extends \Widget
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
	 * Add specific attributes
	 *
	 * @param string $strKey
	 * @param mixed  $varValue
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
	 * Check for a valid option (see #4383)
	 */
	public function validate()
	{
		$varValue = $this->getPost($this->strName);

		if (!empty($varValue) && !$this->isValidOption($varValue))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalid'], (is_array($varValue) ? implode(', ', $varValue) : $varValue)));
		}

		parent::validate();
	}


	/**
	 * Generate the widget and return it as string
	 *
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
		if (\Input::get($strCommand) && is_numeric(\Input::get('cid')) && \Input::get('id') == $this->currentRecord)
		{
			$this->import('Database');

			switch (\Input::get($strCommand))
			{
				case 'up':
					$this->varValue = array_move_up($this->varValue, \Input::get('cid'));
					break;

				case 'down':
					$this->varValue = array_move_down($this->varValue, \Input::get('cid'));
					break;
			}

			$this->Database->prepare("UPDATE " . $this->strTable . " SET " . $this->strField . "=? WHERE id=?")
						   ->execute(serialize($this->varValue), $this->currentRecord);

			$this->redirect(preg_replace('/&(amp;)?cid=[^&]*/i', '', preg_replace('/&(amp;)?' . preg_quote($strCommand, '/') . '=[^&]*/i', '', \Environment::get('request'))));
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
			$strButtons = \Image::getHtml('drag.gif', '', 'class="drag-handle" title="' . sprintf($GLOBALS['TL_LANG']['MSC']['move']) . '"');

			foreach ($arrButtons as $strButton)
			{
				$strButtons .= '<a href="'.$this->addToUrl('&amp;'.$strCommand.'='.$strButton.'&amp;cid='.$i.'&amp;id='.$this->currentRecord).'" class="button-move" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['move_'.$strButton][1]).'" onclick="Backend.checkboxWizard(this,\''.$strButton.'\',\'ctrl_'.$this->strId.'\');return false">'.\Image::getHtml($strButton.'.gif', $GLOBALS['TL_LANG']['MSC']['move_'.$strButton][0], 'class="tl_checkbox_wizard_img"').'</a> ';
			}

			$arrOptions[] = $this->generateCheckbox($arrOption, $i, $strButtons);
		}

		// Add a "no entries found" message if there are no options
		if (empty($arrOptions))
		{
			$arrOptions[] = '<p class="tl_noopt">'.$GLOBALS['TL_LANG']['MSC']['noResult'].'</p>';
			$blnCheckAll = false;
		}

		return sprintf('<fieldset id="ctrl_%s" class="tl_checkbox_container tl_checkbox_wizard%s"><legend>%s%s%s%s</legend><input type="hidden" name="%s" value="">%s<div class="sortable">%s</div></fieldset>%s',
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						($this->mandatory ? '<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].'</span> ' : ''),
						$this->strLabel,
						($this->mandatory ? '<span class="mandatory">*</span>' : ''),
						$this->xlabel,
						$this->strName,
						($blnCheckAll ? '<span class="fixed"><input type="checkbox" id="check_all_' . $this->strId . '" class="tl_checkbox" onclick="Backend.toggleCheckboxGroup(this,\'ctrl_' . $this->strId . '\')"> <label for="check_all_' . $this->strId . '" style="color:#a6a6a6"><em>' . $GLOBALS['TL_LANG']['MSC']['selectAll'] . '</em></label></span>' : ''),
						implode('', $arrOptions),
						$this->wizard);
	}


	/**
	 * Generate a checkbox and return it as string
	 *
	 * @param array   $arrOption
	 * @param integer $i
	 * @param string  $strButtons
	 *
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
