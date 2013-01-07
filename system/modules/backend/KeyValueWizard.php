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
 * Class KeyValueWizard
 *
 * Provide methods to handle key value pairs.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class KeyValueWizard extends Widget
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

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		$mandatory = $this->mandatory;
		$options = deserialize($this->getPost($this->strName));

		// Check keys only (values can be empty)
		if (is_array($options))
		{
			foreach ($options as $key=>$option)
			{
				// Unset empty rows
				if ($option['key'] == '')
				{
					unset($options[$key]);
					continue;
				}

				$options[$key]['key'] = trim($option['key']);
				$options[$key]['value'] = trim($option['value']);

				if ($options[$key]['key'] != '')
				{
					$this->mandatory = false;
				}
			}
		}

		$options = array_values($options);
		$varInput = $this->validator($options);

		if (!$this->hasErrors())
		{
			$this->varValue = $varInput;
		}

		// Reset the property
		if ($mandatory)
		{
			$this->mandatory = true;
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$arrButtons = array('copy', 'up', 'down', 'delete');
		$strCommand = 'cmd_' . $this->strField;

		// Change the order
		if ($this->Input->get($strCommand) && is_numeric($this->Input->get('cid')) && $this->Input->get('id') == $this->currentRecord)
		{
			$this->import('Database');

			switch ($this->Input->get($strCommand))
			{
				case 'copy':
					array_insert($this->varValue, $this->Input->get('cid'), array($this->varValue[$this->Input->get('cid')]));
					break;

				case 'up':
					$this->varValue = array_move_up($this->varValue, $this->Input->get('cid'));
					break;

				case 'down':
					$this->varValue = array_move_down($this->varValue, $this->Input->get('cid'));
					break;

				case 'delete':
					$this->varValue = array_delete($this->varValue, $this->Input->get('cid'));
					break;
			}

			$this->Database->prepare("UPDATE " . $this->strTable . " SET " . $this->strField . "=? WHERE id=?")
						   ->execute(serialize($this->varValue), $this->currentRecord);

			$this->redirect(preg_replace('/&(amp;)?cid=[^&]*/i', '', preg_replace('/&(amp;)?' . preg_quote($strCommand, '/') . '=[^&]*/i', '', $this->Environment->request)));
		}

		// Make sure there is at least an empty array
		if (!is_array($this->varValue) || !$this->varValue[0])
		{
			$this->varValue = array(array(''));
		}

		// Begin table
		$return .= '<table class="tl_optionwizard" id="ctrl_'.$this->strId.'">
  <thead>
    <tr>
      <th>'.$GLOBALS['TL_LANG']['MSC']['ow_key'].'</th>
      <th>'.$GLOBALS['TL_LANG']['MSC']['ow_value'].'</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>';

		$tabindex = 0;

		// Add fields
		for ($i=0; $i<count($this->varValue); $i++)
		{
			$return .= '
    <tr>
      <td><input type="text" name="'.$this->strId.'['.$i.'][key]" id="'.$this->strId.'_key_'.$i.'" class="tl_text_2" tabindex="'.++$tabindex.'" value="'.specialchars($this->varValue[$i]['key']).'"'.$this->getAttributes().'></td>
      <td><input type="text" name="'.$this->strId.'['.$i.'][value]" id="'.$this->strId.'_value_'.$i.'" class="tl_text_2" tabindex="'.++$tabindex.'" value="'.specialchars($this->varValue[$i]['value']).'"'.$this->getAttributes().'></td>';
			
			// Add row buttons
			$return .= '
      <td style="white-space:nowrap;padding-left:3px">';

			foreach ($arrButtons as $button)
			{
				$return .= '<a href="'.$this->addToUrl('&amp;'.$strCommand.'='.$button.'&amp;cid='.$i.'&amp;id='.$this->currentRecord).'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['ow_'.$button]).'" onclick="Backend.keyValueWizard(this,\''.$button.'\',\'ctrl_'.$this->strId.'\');return false">'.$this->generateImage($button.'.gif', $GLOBALS['TL_LANG']['MSC']['ow_'.$button]).'</a> ';
			}

			$return .= '</td>
    </tr>';
		}

		return $return.'
  </tbody>
  </table>';
	}
}

?>