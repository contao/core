<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Provide methods to handle image size fields.
 *
 * @property integer $maxlength
 * @property array   $options
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
	 *
	 * @param string $strKey
	 * @param mixed  $varValue
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
	 *
	 * @param mixed $varInput
	 *
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		$varInput[2] = preg_replace('/[^a-z0-9_]+/', '', $varInput[2]);

		if (!is_numeric($varInput[2]))
		{
			switch ($varInput[2])
			{
				// Validate relative dimensions - width or height required
				case 'proportional':
				case 'box':
					$this->mandatory = !$varInput[0] && !$varInput[1];
					break;

				// Validate exact dimensions - width and height required
				case 'crop':
				case 'left_top':
				case 'center_top':
				case 'right_top':
				case 'left_center':
				case 'center_center':
				case 'right_center':
				case 'left_bottom':
				case 'center_bottom':
				case 'right_bottom':
					$this->mandatory = !$varInput[0] || !$varInput[1];
					break;
			}

			$varInput[0] = parent::validator($varInput[0]);
			$varInput[1] = parent::validator($varInput[1]);
		}

		return $varInput;
	}


	/**
	 * Generate the widget and return it as string
	 *
	 * @return string
	 */
	public function generate()
	{
		if (!is_array($this->varValue))
		{
			$this->varValue = array($this->varValue);
		}

		$arrFields = array();
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

		$arrFields[] = sprintf('<select name="%s[2]" id="ctrl_%s" class="tl_select_interval" onfocus="Backend.getScrollOffset()"%s>%s</select>',
								$this->strName,
								$this->strId.'_3',
								$this->getAttribute('disabled'),
								implode(' ', $arrOptions));

		for ($i=0; $i<2; $i++)
		{
			$arrFields[] = sprintf('<input type="text" name="%s[%s]" id="ctrl_%s" class="tl_text_4 tl_imageSize_%s" value="%s"%s onfocus="Backend.getScrollOffset()">',
									$this->strName,
									$i,
									$this->strId.'_'.$i,
									$i,
									specialchars(@$this->varValue[$i]), // see #4979
									$this->getAttributes());
		}

		return sprintf('<div id="ctrl_%s" class="tl_image_size%s">%s</div>%s',
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						implode(' ', $arrFields),
						$this->wizard);
	}
}
