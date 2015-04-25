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
 * Provide methods to handle image size fields.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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

		$arrFields[] = sprintf('<select name="%s[]" id="ctrl_%s" class="tl_select_interval" onfocus="Backend.getScrollOffset()"%s>%s</select>',
								$this->strName,
								$this->strId.'_3',
								$this->getAttribute('disabled'),
								implode(' ', $arrOptions));

		return sprintf('<div id="ctrl_%s" class="tl_image_size%s">%s</div>%s',
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						implode(' ', $arrFields),
						$this->wizard);
	}
}
