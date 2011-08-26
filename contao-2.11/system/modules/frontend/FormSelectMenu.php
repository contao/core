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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class FormSelectMenu
 *
 * Form field "select menu".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class FormSelectMenu extends Widget
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
	protected $strTemplate = 'form_widget';

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
			case 'mSize':
				if ($this->multiple)
				{
					$this->arrAttributes['size'] = $varValue;
				}
				break;

			case 'options':
				$this->arrOptions = deserialize($varValue);
				break;

			case 'multiple':
				if (strlen($varValue))
				{
					$this->arrAttributes[$strKey] = 'multiple';
				}
				break;

			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;

			case 'rgxp':
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Check options if the field is mandatory
	 */
	public function validate()
	{
		$mandatory = $this->mandatory;
		$options = deserialize($this->getPost($this->strName));

		// Check if there is at least one value
		if ($mandatory && is_array($options))
		{
			foreach ($options as $option)
			{
				if (strlen($option))
				{
					$this->mandatory = false;
					break;
				}
			}
		}

		$varInput = $this->validator($options);

		// Add class "error"
		if ($this->hasErrors())
		{
			$this->class = 'error';
		}
		else
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
	 * Return a parameter
	 * @return string
	 * @throws Exception
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'options':
				return $this->arrOptions;
				break;

			default:
				return parent::__get($strKey);
				break;
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$strOptions = '';
		$strClass = 'select';
		$blnHasGroups = false;

		if ($this->multiple)
		{
			$this->strName .= '[]';
			$strClass = 'multiselect';
		}

		// Make sure there are no multiple options in single mode
		elseif (is_array($this->varValue))
		{
			$this->varValue = $this->varValue[0];
		}

		// Add empty option (XHTML) if there are none
		if (!count($this->arrOptions))
		{
			$this->arrOptions = array(array('value'=>'', 'label'=>'-'));
		}

		foreach ($this->arrOptions as $arrOption)
		{
			if ($arrOption['group'])
			{
				if ($blnHasGroups)
				{
					$strOptions .= '</optgroup>';
				}

				$strOptions .= sprintf('<optgroup label="%s">',
										specialchars($arrOption['label']));

				$blnHasGroups = true;
				continue;
			}

			$strOptions .= sprintf('<option value="%s"%s>%s</option>',
									$arrOption['value'],
									$this->isSelected($arrOption),
									$arrOption['label']);
		}

		if ($blnHasGroups)
		{
			$strOptions .= '</optgroup>';
		}

		return sprintf('<select name="%s" id="ctrl_%s" class="%s%s"%s>%s</select>',
						$this->strName,
						$this->strId,
						$strClass,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->getAttributes(),
						$strOptions) . $this->addSubmit();
	}
}

?>