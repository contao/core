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
 * Provide methods to handle data container arrays.
 *
 * @property integer $id
 * @property string  $table
 * @property mixed   $value
 * @property string  $field
 * @property string  $inputName
 * @property string  $palette
 * @property object  $activeRecord
 * @property boolean $blnUploadable
 * @property array   $root
 * @property array   $rootIds
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
abstract class DataContainer extends \Backend
{

	/**
	 * Current ID
	 * @var integer
	 */
	protected $intId;

	/**
	 * Name of the current table
	 * @var string
	 */
	protected $strTable;

	/**
	 * Name of the current field
	 * @var string
	 */
	protected $strField;

	/**
	 * Name attribute of the current input field
	 * @var string
	 */
	protected $strInputName;

	/**
	 * Value of the current field
	 * @var mixed
	 */
	protected $varValue;

	/**
	 * Name of the current palette
	 * @var string
	 */
	protected $strPalette;

	/**
	 * WHERE clause of the database query
	 * @var array
	 */
	protected $procedure = array();

	/**
	 * Values for the WHERE clause of the database query
	 * @var array
	 */
	protected $values = array();

	/**
	 * Form attribute "onsubmit"
	 * @var array
	 */
	protected $onsubmit = array();

	/**
	 * Reload the page after the form has been submitted
	 * @var boolean
	 */
	protected $noReload = false;

	/**
	 * Active record
	 * @var \Model|\FilesModel
	 */
	protected $objActiveRecord;


	/**
	 * Set an object property
	 *
	 * @param string $strKey
	 * @param mixed  $varValue
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'activeRecord':
				$this->objActiveRecord = $varValue;
				break;

			default;
				$this->$strKey = $varValue; // backwards compatibility
				break;
		}
	}


	/**
	 * Return an object property
	 *
	 * @param string $strKey
	 *
	 * @return mixed
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'id':
				return $this->intId;
				break;

			case 'table':
				return $this->strTable;
				break;

			case 'value':
				return $this->varValue;
				break;

			case 'field':
				return $this->strField;
				break;

			case 'inputName':
				return $this->strInputName;
				break;

			case 'palette':
				return $this->strPalette;
				break;

			case 'activeRecord':
				return $this->objActiveRecord;
				break;
		}

		return parent::__get($strKey);
	}


	/**
	 * Render a row of a box and return it as HTML string
	 *
	 * @param string $strPalette
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	protected function row($strPalette=null)
	{
		$arrData = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField];

		// Redirect if the field is excluded
		if ($arrData['exclude'])
		{
			$this->log('Field "'.$this->strField.'" of table "'.$this->strTable.'" was excluded from being edited', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$xlabel = '';

		// Toggle line wrap (textarea)
		if ($arrData['inputType'] == 'textarea' && !isset($arrData['eval']['rte']))
		{
			$xlabel .= ' ' . \Image::getHtml('wrap.gif', $GLOBALS['TL_LANG']['MSC']['wordWrap'], 'title="' . specialchars($GLOBALS['TL_LANG']['MSC']['wordWrap']) . '" class="toggleWrap" onclick="Backend.toggleWrap(\'ctrl_'.$this->strInputName.'\')"');
		}

		// Add the help wizard
		if ($arrData['eval']['helpwizard'])
		{
			$xlabel .= ' <a href="contao/help.php?table='.$this->strTable.'&amp;field='.$this->strField.'" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['helpWizard']) . '" onclick="Backend.openModalIframe({\'width\':735,\'height\':405,\'title\':\''.specialchars(str_replace("'", "\\'", $arrData['label'][0])).'\',\'url\':this.href});return false">'.\Image::getHtml('about.gif', $GLOBALS['TL_LANG']['MSC']['helpWizard'], 'style="vertical-align:text-bottom"').'</a>';
		}

		// Add a custom xlabel
		if (is_array($arrData['xlabel']))
		{
			foreach ($arrData['xlabel'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$xlabel .= $this->$callback[0]->$callback[1]($this);
				}
				elseif (is_callable($callback))
				{
					$xlabel .= $callback($this);
				}
			}
		}

		// Input field callback
		if (is_array($arrData['input_field_callback']))
		{
			$this->import($arrData['input_field_callback'][0]);

			return $this->$arrData['input_field_callback'][0]->$arrData['input_field_callback'][1]($this, $xlabel);
		}
		elseif (is_callable($arrData['input_field_callback']))
		{
			return $arrData['input_field_callback']($this, $xlabel);
		}

		/** @var \Widget $strClass */
		$strClass = $GLOBALS['BE_FFL'][$arrData['inputType']];

		// Return if the widget class does not exists
		if (!class_exists($strClass))
		{
			return '';
		}

		$arrData['eval']['required'] = false;

		// Use strlen() here (see #3277)
		if ($arrData['eval']['mandatory'])
		{
			if (is_array($this->varValue))
			{
				if (empty($this->varValue))
				{
					$arrData['eval']['required'] = true;
				}
			}
			else
			{
				if (!strlen($this->varValue))
				{
					$arrData['eval']['required'] = true;
				}
			}
		}

		// Convert insert tags in src attributes (see #5965)
		if (isset($arrData['eval']['rte']) && strncmp($arrData['eval']['rte'], 'tiny', 4) === 0)
		{
			$this->varValue = \String::insertTagToSrc($this->varValue);
		}

		/** @var \Widget $objWidget */
		$objWidget = new $strClass($strClass::getAttributesFromDca($arrData, $this->strInputName, $this->varValue, $this->strField, $this->strTable, $this));

		$objWidget->xlabel = $xlabel;
		$objWidget->currentRecord = $this->intId;

		// Validate the field
		if (\Input::post('FORM_SUBMIT') == $this->strTable)
		{
			$key = (\Input::get('act') == 'editAll') ? 'FORM_FIELDS_' . $this->intId : 'FORM_FIELDS';

			// Calculate the current palette
			$postPaletteFields = implode(',', \Input::post($key));
			$postPaletteFields = array_unique(trimsplit('[,;]', $postPaletteFields));

			// Compile the palette if there is none
			if ($strPalette === null)
			{
				$newPaletteFields = trimsplit('[,;]', $this->getPalette());
			}
			else
			{
				// Use the given palette ($strPalette is an array in editAll mode)
				$newPaletteFields = is_array($strPalette) ? $strPalette : trimsplit('[,;]', $strPalette);

				// Re-check the palette if the current field is a selector field
				if (isset($GLOBALS['TL_DCA'][$this->strTable]['palettes']['__selector__']) && in_array($this->strField, $GLOBALS['TL_DCA'][$this->strTable]['palettes']['__selector__']))
				{
					// If the field value has changed, recompile the palette
					if ($this->varValue != \Input::post($this->strInputName))
					{
						$newPaletteFields = trimsplit('[,;]', $this->getPalette());
					}
				}
			}

			// Adjust the names in editAll mode
			if (\Input::get('act') == 'editAll')
			{
				foreach ($newPaletteFields as $k=>$v)
				{
					$newPaletteFields[$k] = $v . '_' . $this->intId;
				}

				if ($this->User->isAdmin)
				{
					$newPaletteFields['pid'] = 'pid_' . $this->intId;
					$newPaletteFields['sorting'] = 'sorting_' . $this->intId;
				}
			}

			$paletteFields = array_intersect($postPaletteFields, $newPaletteFields);

			// Validate and save the field
			if (in_array($this->strInputName, $paletteFields) || \Input::get('act') == 'overrideAll')
			{
				$objWidget->validate();

				if ($objWidget->hasErrors())
				{
					// Skip mandatory fields on auto-submit (see #4077)
					if (\Input::post('SUBMIT_TYPE') != 'auto' || !$objWidget->mandatory || $objWidget->value != '')
					{
						$this->noReload = true;
					}
				}
				elseif ($objWidget->submitInput())
				{
					$varValue = $objWidget->value;

					// Sort array by key (fix for JavaScript wizards)
					if (is_array($varValue))
					{
						ksort($varValue);
						$varValue = serialize($varValue);
					}

					// Convert file paths in src attributes (see #5965)
					if ($varValue && isset($arrData['eval']['rte']) && strncmp($arrData['eval']['rte'], 'tiny', 4) === 0)
					{
						$varValue = \String::srcToInsertTag($varValue);
					}

					// Save the current value
					try
					{
						$this->save($varValue);
					}
					catch (\Exception $e)
					{
						$this->noReload = true;
						$objWidget->addError($e->getMessage());
					}
				}
			}
		}

		$wizard = '';
		$strHelpClass = '';

		// Date picker
		if ($arrData['eval']['datepicker'])
		{
			$rgxp = $arrData['eval']['rgxp'];
			$format = \Date::formatToJs(\Config::get($rgxp.'Format'));

			switch ($rgxp)
			{
				case 'datim':
					$time = ",\n      timePicker:true";
					break;

				case 'time':
					$time = ",\n      pickOnly:\"time\"";
					break;

				default:
					$time = '';
					break;
			}

			$wizard .= ' <img src="assets/mootools/datepicker/' . $GLOBALS['TL_ASSETS']['DATEPICKER'] . '/icon.gif" width="20" height="20" alt="" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['datepicker']).'" id="toggle_' . $objWidget->id . '" style="vertical-align:-6px;cursor:pointer">
  <script>
    window.addEvent("domready", function() {
      new Picker.Date($("ctrl_' . $objWidget->id . '"), {
        draggable: false,
        toggle: $("toggle_' . $objWidget->id . '"),
        format: "' . $format . '",
        positionOffset: {x:-211,y:-209}' . $time . ',
        pickerClass: "datepicker_bootstrap",
        useFadeInOut: !Browser.ie,
        startDay: ' . $GLOBALS['TL_LANG']['MSC']['weekOffset'] . ',
        titleFormat: "' . $GLOBALS['TL_LANG']['MSC']['titleFormat'] . '"
      });
    });
  </script>';
		}

		// Color picker
		if ($arrData['eval']['colorpicker'])
		{
			// Support single fields as well (see #5240)
			$strKey = $arrData['eval']['multiple'] ? $this->strField . '_0' : $this->strField;

			$wizard .= ' ' . \Image::getHtml('pickcolor.gif', $GLOBALS['TL_LANG']['MSC']['colorpicker'], 'style="vertical-align:top;cursor:pointer" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['colorpicker']).'" id="moo_' . $this->strField . '"') . '
  <script>
    window.addEvent("domready", function() {
      new MooRainbow("moo_' . $this->strField . '", {
        id: "ctrl_' . $strKey . '",
        startColor: ((cl = $("ctrl_' . $strKey . '").value.hexToRgb(true)) ? cl : [255, 0, 0]),
        imgPath: "assets/mootools/colorpicker/' . $GLOBALS['TL_ASSETS']['COLORPICKER'] . '/images/",
        onComplete: function(color) {
          $("ctrl_' . $strKey . '").value = color.hex.replace("#", "");
        }
      });
    });
  </script>';
		}

		// Add a custom wizard
		if (is_array($arrData['wizard']))
		{
			foreach ($arrData['wizard'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$wizard .= $this->$callback[0]->$callback[1]($this);
				}
				elseif (is_callable($callback))
				{
					$wizard .= $callback($this);
				}
			}
		}

		$objWidget->wizard = $wizard;

		// Set correct form enctype
		if ($objWidget instanceof \uploadable)
		{
			$this->blnUploadable = true;
		}

		// Mark floated single checkboxes
		if ($arrData['inputType'] == 'checkbox' && !$arrData['eval']['multiple'] && strpos($arrData['eval']['tl_class'], 'w50') !== false)
		{
			$arrData['eval']['tl_class'] .= ' cbx';
		}
		elseif ($arrData['inputType'] == 'text' && $arrData['eval']['multiple'] && strpos($arrData['eval']['tl_class'], 'wizard') !== false)
		{
			$arrData['eval']['tl_class'] .= ' inline';
		}

		// No 2-column layout in "edit all" mode
		if (\Input::get('act') == 'editAll' || \Input::get('act') == 'overrideAll')
		{
			$arrData['eval']['tl_class'] = str_replace(array('w50', 'clr', 'wizard', 'long', 'm12', 'cbx'), '', $arrData['eval']['tl_class']);
		}

		$updateMode = '';

		// Replace the textarea with an RTE instance
		if (!empty($arrData['eval']['rte']))
		{
			list ($file, $type) = explode('|', $arrData['eval']['rte'], 2);

			if (!file_exists(TL_ROOT . '/system/config/' . $file . '.php'))
			{
				throw new \Exception(sprintf('Cannot find editor configuration file "%s.php"', $file));
			}

			$selector = 'ctrl_' . $this->strInputName;
			$language = \Backend::getTinyMceLanguage(); // backwards compatibility

			ob_start();
			include TL_ROOT . '/system/config/' . $file . '.php';
			$updateMode = ob_get_contents();
			ob_end_clean();

			unset($file, $type, $language, $selector);
		}

		// Handle multi-select fields in "override all" mode
		elseif (\Input::get('act') == 'overrideAll' && ($arrData['inputType'] == 'checkbox' || $arrData['inputType'] == 'checkboxWizard') && $arrData['eval']['multiple'])
		{
			$updateMode = '
</div>
<div>
  <fieldset class="tl_radio_container">
  <legend>' . $GLOBALS['TL_LANG']['MSC']['updateMode'] . '</legend>
    <input type="radio" name="'.$this->strInputName.'_update" id="opt_'.$this->strInputName.'_update_1" class="tl_radio" value="add" onfocus="Backend.getScrollOffset()"> <label for="opt_'.$this->strInputName.'_update_1">' . $GLOBALS['TL_LANG']['MSC']['updateAdd'] . '</label><br>
    <input type="radio" name="'.$this->strInputName.'_update" id="opt_'.$this->strInputName.'_update_2" class="tl_radio" value="remove" onfocus="Backend.getScrollOffset()"> <label for="opt_'.$this->strInputName.'_update_2">' . $GLOBALS['TL_LANG']['MSC']['updateRemove'] . '</label><br>
    <input type="radio" name="'.$this->strInputName.'_update" id="opt_'.$this->strInputName.'_update_0" class="tl_radio" value="replace" checked="checked" onfocus="Backend.getScrollOffset()"> <label for="opt_'.$this->strInputName.'_update_0">' . $GLOBALS['TL_LANG']['MSC']['updateReplace'] . '</label>
  </fieldset>';
		}

		$strPreview = '';

		// Show a preview image (see #4948)
		if ($this->strTable == 'tl_files' && $this->strField == 'name' && $this->objActiveRecord !== null && $this->objActiveRecord->type == 'file')
		{
			$objFile = new \File($this->objActiveRecord->path, true);

			if ($objFile->isImage)
			{
				$image = 'placeholder.png';

				if ($objFile->isSvgImage || $objFile->height <= \Config::get('gdMaxImgHeight') && $objFile->width <= \Config::get('gdMaxImgWidth'))
				{
					if ($objFile->width > 699 || $objFile->height > 524)
					{
						$image = \Image::get($objFile->path, 699, 524, 'box');
					}
					else
					{
						$image = $objFile->path;
					}
				}

				$ctrl = 'ctrl_preview_' . substr(md5($image), 0, 8);

				$strPreview = '

<div id="' . $ctrl . '" class="tl_edit_preview" data-original-width="' . $objFile->width . '" data-original-height="' . $objFile->height . '">
' . \Image::getHtml($image) . '
</div>';

				// Add the script to mark the important part
				if ($image !== 'placeholder.png')
				{
					$strPreview .= '<script>Backend.editPreviewWizard($(\'' . $ctrl . '\'));</script>';

					if (\Config::get('showHelp'))
					{
						$strPreview .= '<p class="tl_help tl_tip">' . $GLOBALS['TL_LANG'][$this->strTable]['edit_preview_help'] . '</p>';
					}
				}
			}
		}

		return $strPreview . '
<div' . ($arrData['eval']['tl_class'] ? ' class="' . $arrData['eval']['tl_class'] . '"' : '') . '>' . $objWidget->parse() . $updateMode . (!$objWidget->hasErrors() ? $this->help($strHelpClass) : '') . '
</div>';
	}


	/**
	 * Return the field explanation as HTML string
	 *
	 * @param string $strClass
	 *
	 * @return string
	 */
	public function help($strClass='')
	{
		$return = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['label'][1];

		if (!\Config::get('showHelp') || $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] == 'password' || $return == '')
		{
			return '';
		}

		return '
  <p class="tl_help tl_tip' . $strClass . '">'.$return.'</p>';
	}


	/**
	 * Generate possible palette names from an array by taking the first value and either adding or not adding the following values
	 *
	 * @param array $names
	 *
	 * @return array
	 */
	protected function combiner($names)
	{
		$return = array('');
		$names = array_values($names);

		for ($i=0, $c=count($names); $i<$c; $i++)
		{
			$buffer = array();

			foreach ($return as $k=>$v)
			{
				$buffer[] = ($k%2 == 0) ? $v : $v.$names[$i];
				$buffer[] = ($k%2 == 0) ? $v.$names[$i] : $v;
			}

			$return = $buffer;
		}

		return array_filter($return);
	}


	/**
	 * Return a query string that switches into edit mode
	 *
	 * @param integer $id
	 *
	 * @return string
	 */
	protected function switchToEdit($id)
	{
		$arrKeys = array();
		$arrUnset = array('act', 'id', 'table');

		foreach (array_keys($_GET) as $strKey)
		{
			if (!in_array($strKey, $arrUnset))
			{
				$arrKeys[$strKey] = $strKey . '=' . \Input::get($strKey);
			}
		}

		$strUrl = TL_SCRIPT . '?' . implode('&', $arrKeys);

		return $strUrl . (!empty($arrKeys) ? '&' : '') . (\Input::get('table') ? 'table='.\Input::get('table').'&amp;' : '').'act=edit&amp;id='.$id;
	}


	/**
	 * Compile buttons from the table configuration array and return them as HTML
	 *
	 * @param array   $arrRow
	 * @param string  $strTable
	 * @param array   $arrRootIds
	 * @param boolean $blnCircularReference
	 * @param array   $arrChildRecordIds
	 * @param string  $strPrevious
	 * @param string  $strNext
	 *
	 * @return string
	 */
	protected function generateButtons($arrRow, $strTable, $arrRootIds=array(), $blnCircularReference=false, $arrChildRecordIds=null, $strPrevious=null, $strNext=null)
	{
		if (empty($GLOBALS['TL_DCA'][$strTable]['list']['operations']))
		{
			return '';
		}

		$return = '';

		foreach ($GLOBALS['TL_DCA'][$strTable]['list']['operations'] as $k=>$v)
		{
			$v = is_array($v) ? $v : array($v);
			$id = specialchars(rawurldecode($arrRow['id']));

			$label = $v['label'][0] ?: $k;
			$title = sprintf($v['label'][1] ?: $k, $id);
			$attributes = ($v['attributes'] != '') ? ' ' . ltrim(sprintf($v['attributes'], $id, $id)) : '';

			// Add the key as CSS class
			if (strpos($attributes, 'class="') !== false)
			{
				$attributes = str_replace('class="', 'class="' . $k . ' ', $attributes);
			}
			else
			{
				$attributes = ' class="' . $k . '"' . $attributes;
			}

			// Call a custom function instead of using the default button
			if (is_array($v['button_callback']))
			{
				$this->import($v['button_callback'][0]);
				$return .= $this->$v['button_callback'][0]->$v['button_callback'][1]($arrRow, $v['href'], $label, $title, $v['icon'], $attributes, $strTable, $arrRootIds, $arrChildRecordIds, $blnCircularReference, $strPrevious, $strNext, $this);
				continue;
			}
			elseif (is_callable($v['button_callback']))
			{
				$return .= $v['button_callback']($arrRow, $v['href'], $label, $title, $v['icon'], $attributes, $strTable, $arrRootIds, $arrChildRecordIds, $blnCircularReference, $strPrevious, $strNext, $this);
				continue;
			}

			// Generate all buttons except "move up" and "move down" buttons
			if ($k != 'move' && $v != 'move')
			{
				if ($k == 'show')
				{
					$return .= '<a href="'.$this->addToUrl($v['href'].'&amp;id='.$arrRow['id'].'&amp;popup=1').'" title="'.specialchars($title).'" onclick="Backend.openModalIframe({\'width\':768,\'title\':\''.specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG'][$strTable]['show'][1], $arrRow['id']))).'\',\'url\':this.href});return false"'.$attributes.'>'.\Image::getHtml($v['icon'], $label).'</a> ';
				}
				else
				{
					$return .= '<a href="'.$this->addToUrl($v['href'].'&amp;id='.$arrRow['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.\Image::getHtml($v['icon'], $label).'</a> ';
				}

				continue;
			}

			$arrDirections = array('up', 'down');
			$arrRootIds = is_array($arrRootIds) ? $arrRootIds : array($arrRootIds);

			foreach ($arrDirections as $dir)
			{
				$label = $GLOBALS['TL_LANG'][$strTable][$dir][0] ?: $dir;
				$title = $GLOBALS['TL_LANG'][$strTable][$dir][1] ?: $dir;

				$label = \Image::getHtml($dir.'.gif', $label);
				$href = $v['href'] ?: '&amp;act=move';

				if ($dir == 'up')
				{
					$return .= ((is_numeric($strPrevious) && (!in_array($arrRow['id'], $arrRootIds) || empty($GLOBALS['TL_DCA'][$strTable]['list']['sorting']['root']))) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$arrRow['id']).'&amp;sid='.intval($strPrevious).'" title="'.specialchars($title).'"'.$attributes.'>'.$label.'</a> ' : \Image::getHtml('up_.gif')).' ';
					continue;
				}

				$return .= ((is_numeric($strNext) && (!in_array($arrRow['id'], $arrRootIds) || empty($GLOBALS['TL_DCA'][$strTable]['list']['sorting']['root']))) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$arrRow['id']).'&amp;sid='.intval($strNext).'" title="'.specialchars($title).'"'.$attributes.'>'.$label.'</a> ' : \Image::getHtml('down_.gif')).' ';
			}
		}

		return trim($return);
	}


	/**
	 * Compile global buttons from the table configuration array and return them as HTML
	 *
	 * @return string
	 */
	protected function generateGlobalButtons()
	{
		if (!is_array($GLOBALS['TL_DCA'][$this->strTable]['list']['global_operations']))
		{
			return '';
		}

		$return = '';

		foreach ($GLOBALS['TL_DCA'][$this->strTable]['list']['global_operations'] as $k=>$v)
		{
			$v = is_array($v) ? $v : array($v);
			$label = is_array($v['label']) ? $v['label'][0] : $v['label'];
			$title = is_array($v['label']) ? $v['label'][1] : $v['label'];
			$attributes = ($v['attributes'] != '') ? ' ' . ltrim($v['attributes']) : '';

			// Custom icon (see #5541)
			if ($v['icon'])
			{
				$v['class'] = trim($v['class'] . ' header_icon');

				// Add the theme path if only the file name is given
				if (strpos($v['icon'], '/') === false)
				{
					$v['icon'] = 'system/themes/' . \Backend::getTheme() . '/images/' . $v['icon'];
				}

				$attributes = sprintf('style="background-image:url(\'%s%s\')"', TL_ASSETS_URL, $v['icon']) . $attributes;
			}

			if ($label == '')
			{
				$label = $k;
			}
			if ($title == '')
			{
				$title = $label;
			}

			// Call a custom function instead of using the default button
			if (is_array($v['button_callback']))
			{
				$this->import($v['button_callback'][0]);
				$return .= $this->$v['button_callback'][0]->$v['button_callback'][1]($v['href'], $label, $title, $v['class'], $attributes, $this->strTable, $this->root);
				continue;
			}
			elseif (is_callable($v['button_callback']))
			{
				$return .= $v['button_callback']($v['href'], $label, $title, $v['class'], $attributes, $this->strTable, $this->root);
				continue;
			}

			$return .= '<a href="'.$this->addToUrl($v['href']).'" class="'.$v['class'].'" title="'.specialchars($title).'"'.$attributes.'>'.$label.'</a> ';
		}

		return $return;
	}

	/**
	 * Return the name of the current palette
	 *
	 * @return string
	 */
	abstract public function getPalette();

	/**
	 * Save the current value
	 *
	 * @param mixed $varValue
	 *
	 * @throws \Exception
	 */
	abstract protected function save($varValue);
}
