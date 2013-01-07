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
 * Class ModuleWizard
 *
 * Provide methods to handle modules of a page layout.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ModuleWizard extends \Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = false;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$this->import('Database');

		$arrButtons = array('copy', 'up', 'down', 'delete');
		$strCommand = 'cmd_' . $this->strField;

		// Change the order
		if (\Input::get($strCommand) && is_numeric(\Input::get('cid')) && \Input::get('id') == $this->currentRecord)
		{
			switch (\Input::get($strCommand))
			{
				case 'copy':
					$this->varValue = array_duplicate($this->varValue, \Input::get('cid'));
					break;

				case 'up':
					$this->varValue = array_move_up($this->varValue, \Input::get('cid'));
					break;

				case 'down':
					$this->varValue = array_move_down($this->varValue, \Input::get('cid'));
					break;

				case 'delete':
					$this->varValue = array_delete($this->varValue, \Input::get('cid'));
					break;
			}
		}

		// Get all modules of the current theme
		$objModules = $this->Database->prepare("SELECT id, name, type FROM tl_module WHERE pid=(SELECT pid FROM " . $this->strTable . " WHERE id=?) ORDER BY name")
									 ->execute($this->currentRecord);

		// Add the articles module
		$modules[] = array('id'=>0, 'name'=>$GLOBALS['TL_LANG']['MOD']['article'][0], 'type'=>'article');

		if ($objModules->numRows)
		{
			$modules = array_merge($modules, $objModules->fetchAllAssoc());
		}

		$GLOBALS['TL_LANG']['FMD']['article'] = $GLOBALS['TL_LANG']['MOD']['article'];

		// Add the module type (see #3835)
		foreach ($modules as $k=>$v)
		{
			$v['type'] = $GLOBALS['TL_LANG']['FMD'][$v['type']][0];
			$modules[$k] = $v;
		}

		$objRow = $this->Database->prepare("SELECT * FROM " . $this->strTable . " WHERE id=?")
								 ->limit(1)
								 ->execute($this->currentRecord);

		// Show all columns and filter in PageRegular (see #3273)
		$cols = array('header', 'left', 'right', 'main', 'footer');
		$arrSections = deserialize($objRow->sections);

		// Add custom page sections
		if (is_array($arrSections) && !empty($arrSections))
		{
			$cols = array_merge($cols, $arrSections);
		}

		// Get the new value
		if (\Input::post('FORM_SUBMIT') == $this->strTable)
		{
			$this->varValue = \Input::post($this->strId);
		}

		// Make sure there is at least an empty array
		if (!is_array($this->varValue) || !$this->varValue[0])
		{
			$this->varValue = array('');
		}
		else
		{
			$arrCols = array();

			// Initialize the sorting order
			foreach ($cols as $col)
			{
				$arrCols[$col] = array();
			}

			foreach ($this->varValue as $v)
			{
				$arrCols[$v['col']][] = $v;
			}

			$this->varValue = array();

			foreach ($arrCols as $arrCol)
			{
				$this->varValue = array_merge($this->varValue, $arrCol);
			}
		}

		// Save the value
		if (\Input::get($strCommand) || \Input::post('FORM_SUBMIT') == $this->strTable)
		{
			$this->Database->prepare("UPDATE " . $this->strTable . " SET " . $this->strField . "=? WHERE id=?")
						   ->execute(serialize($this->varValue), $this->currentRecord);

			// Reload the page
			if (is_numeric(\Input::get('cid')) && \Input::get('id') == $this->currentRecord)
			{
				$this->redirect(preg_replace('/&(amp;)?cid=[^&]*/i', '', preg_replace('/&(amp;)?' . preg_quote($strCommand, '/') . '=[^&]*/i', '', \Environment::get('request'))));
			}
		}

		// Add the label and the return wizard
		$return = '<table id="ctrl_'.$this->strId.'" class="tl_modulewizard">
  <thead>
  <tr>
    <th>'.$GLOBALS['TL_LANG']['MSC']['mw_module'].'</th>
    <th>&nbsp;</th>
    <th>'.$GLOBALS['TL_LANG']['MSC']['mw_column'].'</th>
    <th>&nbsp;</th>
  </tr>
  </thead>
  <tbody>';

		// Load the tl_article language file
		$this->loadLanguageFile('tl_article');
		$tabindex = 0;

		// Add the input fields
		for ($i=0; $i<count($this->varValue); $i++)
		{
			$options = '';

			// Add modules
			foreach ($modules as $v)
			{
				$options .= '<option value="'.specialchars($v['id']).'"'.$this->optionSelected($v['id'], $this->varValue[$i]['mod']).'>'.$v['name'].' ['. $v['type'] .']</option>';
			}

			$return .= '
  <tr>
    <td><select name="'.$this->strId.'['.$i.'][mod]" class="tl_select tl_chosen" tabindex="'.++$tabindex.'" onfocus="Backend.getScrollOffset()" onchange="Backend.updateModuleLink(this)">'.$options.'</select></td>
    <td><a href="contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->varValue[$i]['mod'] . '&amp;rt=' . REQUEST_TOKEN . '" title="' . specialchars($GLOBALS['TL_LANG']['tl_layout']['edit_module']) . '" class="module_link" style="display:' . (($this->varValue[$i]['mod'] > 0) ? 'inline' : 'none') . '">'.$this->generateImage('edit.gif').'</a>'.$this->generateImage('edit_.gif', '', 'class="module_image" style="display:' . (($this->varValue[$i]['mod'] > 0) ? 'none' : 'inline') . '"').'</td>';

			$options = '';

			// Add columns
			foreach ($cols as $v)
			{
				$options .= '<option value="'.specialchars($v).'"'.$this->optionSelected($v, $this->varValue[$i]['col']).'>'. ((isset($GLOBALS['TL_LANG']['tl_article'][$v]) && !is_array($GLOBALS['TL_LANG']['tl_article'][$v])) ? $GLOBALS['TL_LANG']['tl_article'][$v] : $v) .'</option>';
			}

			$return .= '
    <td><select name="'.$this->strId.'['.$i.'][col]" class="tl_select_column" tabindex="'.++$tabindex.'" onfocus="Backend.getScrollOffset()">'.$options.'</select></td>
    <td>';

			// Add buttons
			foreach ($arrButtons as $button)
			{
				$return .= '<a href="'.$this->addToUrl('&amp;'.$strCommand.'='.$button.'&amp;cid='.$i.'&amp;id='.$this->currentRecord).'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['mw_'.$button]).'" onclick="Backend.moduleWizard(this,\''.$button.'\',\'ctrl_'.$this->strId.'\');return false">'.$this->generateImage($button.'.gif', $GLOBALS['TL_LANG']['MSC']['mw_'.$button], 'class="tl_listwizard_img"').'</a> ';
			}

			$return .= '</td>
  </tr>';
		}

		return $return.'
  </tbody>
  </table>';
	}
}
