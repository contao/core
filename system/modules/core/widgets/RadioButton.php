<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
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
 * Class RadioButton
 *
 * Provide methods to handle radio buttons.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class RadioButton extends \Widget
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
	protected $strTemplate = 'be_widget_rdo';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'mandatory':
				if ($varValue)
				{
					$this->arrAttributes['required'] = 'required';
				}
				else
				{
					unset($this->arrAttributes['required']);
				}
				parent::__set($strKey, $varValue);
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
	 * @return string
	 */
	public function generate()
	{
		$arrOptions = array();

		foreach ($this->arrOptions as $i=>$arrOption)
		{
			$arrOptions[] = sprintf('<input type="radio" name="%s" id="opt_%s" class="tl_radio" value="%s"%s%s onfocus="Backend.getScrollOffset()"> <label for="opt_%s">%s</label>',
									 $this->strName,
									 $this->strId.'_'.$i,
									 specialchars($arrOption['value']),
									 $this->isChecked($arrOption),
									 $this->getAttributes(),
									 $this->strId.'_'.$i,
									 $arrOption['label']);
		}

		// Add a "no entries found" message if there are no options
		if (empty($arrOptions))
		{
			$arrOptions[]= '<p class="tl_noopt">'.$GLOBALS['TL_LANG']['MSC']['noResult'].'</p>';
		}

		return sprintf('<fieldset id="ctrl_%s" class="tl_radio_container%s"><legend>%s%s%s%s</legend>%s</fieldset>%s',
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						($this->mandatory ? '<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].'</span> ' : ''),
						$this->strLabel,
						($this->mandatory ? '<span class="mandatory">*</span>' : ''),
						$this->xlabel,
						implode('<br>', $arrOptions),
						$this->wizard);
	}
}
