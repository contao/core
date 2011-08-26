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
 * Class RadioTable
 *
 * Provide methods to handle radio button tables.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class RadioTable extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Columns
	 * @var integer
	 */
	protected $intCols = 4;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';

	/**
	 * Options
	 * @var integer
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
			case 'cols':
				if ($varValue > 0)
				{
					$this->intCols = $varValue;
				}
				break;

			case 'options':
				$this->arrOptions = deserialize($varValue);
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
		if (!is_array($this->arrOptions) || !count($this->arrOptions))
		{
			return '';
		}

		$rows = ceil(count($this->arrOptions) / $this->intCols);
		$return = '<table id="ctrl_'.$this->strName.'" class="tl_radio_table'.(strlen($this->strClass) ? ' ' . $this->strClass : '').'">';

		for ($i=0; $i<$rows; $i++)
		{
			$return .= '
    <tr>';

			// Add cells
			for ($j=($i*$this->intCols); $j<(($i+1)*$this->intCols); $j++)
			{
				$value = $this->arrOptions[$j]['value'];
				$label = $this->arrOptions[$j]['label'];

				if (strlen($value))
				{
					$label = $this->generateImage($value.'.gif', $label, 'title="'.specialchars($label).'"');
					$return .= '
      <td><input type="radio" name="'.$this->strName.'" id="'.$this->strField.'_'.$i.'_'.$j.'" class="tl_radio" value="'.specialchars($value).'" onfocus="Backend.getScrollOffset();"'.$this->isChecked($this->arrOptions[$j]).$this->getAttributes().'> <label for="'.$this->strField.'_'.$i.'_'.$j.'">'.$label.'</label></td>';
				}

				// Else return an empty cell
				else $return .= '
      <td></td>';
			}

			// Close row
			$return .= '
    </tr>';
		}

		return $return . '
  </table>';
	}
}

?>