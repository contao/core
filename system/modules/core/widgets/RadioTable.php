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
 * Provide methods to handle radio button tables.
 *
 * @property integer $cols
 * @property array   $options
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class RadioTable extends \Widget
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
	 * Add specific attributes
	 *
	 * @param string $strKey
	 * @param mixed  $varValue
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
		if (!is_array($this->arrOptions) || empty($this->arrOptions))
		{
			return '';
		}

		$rows = ceil(count($this->arrOptions) / $this->intCols);
		$return = '<table id="ctrl_'.$this->strName.'" class="tl_radio_table'.(($this->strClass != '') ? ' ' . $this->strClass : '').'">';

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
					$label = \Image::getHtml($value.'.gif', $label, 'title="'.specialchars($label).'"');
					$return .= '
      <td><input type="radio" name="'.$this->strName.'" id="'.$this->strName.'_'.$i.'_'.$j.'" class="tl_radio" value="'.specialchars($value).'" onfocus="Backend.getScrollOffset()"'.$this->isChecked($this->arrOptions[$j]).$this->getAttributes().'> <label for="'.$this->strName.'_'.$i.'_'.$j.'">'.$label.'</label></td>';
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
