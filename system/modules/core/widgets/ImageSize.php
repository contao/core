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
 * Class ImageSize
 *
 * Provide methods to handle image size fields.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ImageSize extends \Widget
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
			case 'maxlength':
				if ($varValue > 0)
				{
					$this->arrAttributes['maxlength'] = $varValue;
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
	 * Trim values
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		$varInput[0] = parent::validator($varInput[0]);
		$varInput[1] = parent::validator($varInput[1]);
		$varInput[2] = preg_replace('/[^a-z0-9_]+/', '', $varInput[2]);

		return $varInput;
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		if (!is_array($this->varValue))
		{
			$this->varValue = array($this->varValue);
		}

		// Backwards compatibility (see #3911)
		if (@$this->varValue[2] == 'crop')
		{
			$this->varValue[2] = 'center_center';
		}

		$arrFields = array();

		for ($i=0; $i<2; $i++)
		{
			$arrFields[] = sprintf('<input type="text" name="%s[]" id="ctrl_%s" class="tl_text_4 tl_imageSize_%s" value="%s"%s onfocus="Backend.getScrollOffset()">',
									$this->strName,
									$this->strId.'_'.$i,
									$i,
									specialchars(@$this->varValue[$i]), // see #4979
									$this->getAttributes());
		}

		$arrOptions = array();

		foreach ($this->arrOptions as $strKey=>$arrOption)
		{
			if (isset($arrOption['value']))
			{
				$arrOptions[] = sprintf('<option value="%s"%s>%s</option>',
									   specialchars($arrOption['value']),
									   $this->isSelected($arrOption),
									   $arrOption['label']);
			}
			else
			{
				$arrOptgroups = array();

				foreach ($arrOption as $arrOptgroup)
				{
					$arrOptgroups[] = sprintf('<option value="%s"%s>%s</option>',
											   specialchars($arrOptgroup['value']),
											   $this->isSelected($arrOptgroup),
											   $arrOptgroup['label']);
				}

				$arrOptions[] = sprintf('<optgroup label="&nbsp;%s">%s</optgroup>', specialchars($strKey), implode('', $arrOptgroups));
			}
		}

		$arrFields[] = sprintf('<select name="%s[]" id="ctrl_%s" class="tl_select_interval" onfocus="Backend.getScrollOffset()">%s</select>',
								$this->strName,
								$this->strId.'_3',
								implode(' ', $arrOptions));

		return sprintf('<div id="ctrl_%s"%s>%s</div>%s',
						$this->strId,
						(($this->strClass != '') ? ' class="' . $this->strClass . '"' : ''),
						implode(' ', $arrFields),
						$this->wizard);
	}
}
