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
 * Provide methods to handle modules of a page layout.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 *
	 * @return string
	 */
	public function generate()
	{
		$this->import('Database');

		$arrButtons = array('edit', 'copy', 'delete', 'enable', 'drag', 'up', 'down');
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
		$arrSections = trimsplit(',', $objRow->sections);

		// Add custom page sections
		if (!empty($arrSections) && is_array($arrSections))
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

		// Initialize the tab index
		if (!\Cache::has('tabindex'))
		{
			\Cache::set('tabindex', 1);
		}

		$tabindex = \Cache::get('tabindex');

		// Add the label and the return wizard
		$return = '<table id="ctrl_'.$this->strId.'" class="tl_modulewizard">
  <thead>
  <tr>
    <th>'.$GLOBALS['TL_LANG']['MSC']['mw_module'].'</th>
    <th>'.$GLOBALS['TL_LANG']['MSC']['mw_column'].'</th>
    <th>&nbsp;</th>
  </tr>
  </thead>
  <tbody class="sortable" data-tabindex="'.$tabindex.'">';

		// Load the tl_article language file
		\System::loadLanguageFile('tl_article');

		// Add the input fields
		for ($i=0, $c=count($this->varValue); $i<$c; $i++)
		{
			$options = '';

			// Add modules
			foreach ($modules as $v)
			{
				$options .= '<option value="'.specialchars($v['id']).'"'.static::optionSelected($v['id'], $this->varValue[$i]['mod']).'>'.$v['name'].' ['. $v['type'] .']</option>';
			}

			$return .= '
  <tr>
    <td><select name="'.$this->strId.'['.$i.'][mod]" class="tl_select tl_chosen" tabindex="'.$tabindex++.'" onfocus="Backend.getScrollOffset()" onchange="Backend.updateModuleLink(this)">'.$options.'</select></td>';

			$options = '';

			// Add columns
			foreach ($cols as $v)
			{
				$options .= '<option value="'.specialchars($v).'"'.static::optionSelected($v, $this->varValue[$i]['col']).'>'. ((isset($GLOBALS['TL_LANG']['COLS'][$v]) && !is_array($GLOBALS['TL_LANG']['COLS'][$v])) ? $GLOBALS['TL_LANG']['COLS'][$v] : $v) .'</option>';
			}

			$return .= '
    <td><select name="'.$this->strId.'['.$i.'][col]" class="tl_select_column" tabindex="'.$tabindex++.'" onfocus="Backend.getScrollOffset()">'.$options.'</select></td>
    <td>';

			// Add buttons
			foreach ($arrButtons as $button)
			{
				$class = ($button == 'up' || $button == 'down') ? ' class="button-move"' : '';

				if ($button == 'edit')
				{
					$return .= ' <a href="contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->varValue[$i]['mod'] . '&amp;popup=1&amp;rt=' . REQUEST_TOKEN . '&amp;nb=1" title="' . specialchars($GLOBALS['TL_LANG']['tl_layout']['edit_module']) . '" class="module_link" ' . (($this->varValue[$i]['mod'] > 0) ? '' : ' style="display:none"') . ' onclick="Backend.openModalIframe({\'width\':768,\'title\':\'' . specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['tl_layout']['edit_module'])) . '\',\'url\':this.href});return false">'.\Image::getHtml('edit.gif').'</a>' . \Image::getHtml('edit_.gif', '', 'class="module_image"' . (($this->varValue[$i]['mod'] > 0) ? ' style="display:none"' : ''));
				}
				elseif ($button == 'drag')
				{
					$return .= ' ' . \Image::getHtml('drag.gif', '', 'class="drag-handle" title="' . sprintf($GLOBALS['TL_LANG']['MSC']['move']) . '"');
				}
				elseif ($button == 'enable')
				{
					$return .= ' ' . \Image::getHtml((($this->varValue[$i]['enable']) ? 'visible.gif' : 'invisible.gif'), '', 'class="mw_enable" title="' . sprintf($GLOBALS['TL_LANG']['MSC']['mw_enable']) . '"') . '<input name="'.$this->strId.'['.$i.'][enable]" type="checkbox" class="tl_checkbox mw_enable" value="1" tabindex="'.$tabindex++.'" onfocus="Backend.getScrollOffset()"'. (($this->varValue[$i]['enable']) ? ' checked' : '').'>';
				}
				else
				{
					$return .= ' <a href="'.$this->addToUrl('&amp;'.$strCommand.'='.$button.'&amp;cid='.$i.'&amp;id='.$this->currentRecord).'"' . $class . ' title="'.specialchars($GLOBALS['TL_LANG']['MSC']['mw_'.$button]).'" onclick="Backend.moduleWizard(this,\''.$button.'\',\'ctrl_'.$this->strId.'\');return false">'.\Image::getHtml($button.'.gif', $GLOBALS['TL_LANG']['MSC']['mw_'.$button], 'class="tl_listwizard_img"').'</a>';
				}
			}

			$return .= '</td>
  </tr>';
		}

		// Store the tab index
		\Cache::set('tabindex', $tabindex);

		return $return.'
  </tbody>
  </table>';
	}
}
