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
 * Provide methods to edit the local configuration file.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class DC_File extends \DataContainer implements \editable
{

	/**
	 * Initialize the object
	 *
	 * @param string $strTable
	 */
	public function __construct($strTable)
	{
		parent::__construct();
		$this->intId = \Input::get('id');

		// Check whether the table is defined
		if ($strTable == '' || !isset($GLOBALS['TL_DCA'][$strTable]))
		{
			$this->log('Could not load data container configuration for "' . $strTable . '"', __METHOD__, TL_ERROR);
			trigger_error('Could not load data container configuration', E_USER_ERROR);
		}

		// Build object from global configuration array
		$this->strTable = $strTable;

		// Call onload_callback (e.g. to check permissions)
		if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['onload_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($this);
				}
				elseif (is_callable($callback))
				{
					$callback($this);
				}
			}
		}
	}


	/**
	 * Automatically switch to edit mode
	 *
	 * @return string
	 */
	public function create()
	{
		return $this->edit();
	}


	/**
	 * Automatically switch to edit mode
	 *
	 * @return string
	 */
	public function cut()
	{
		return $this->edit();
	}


	/**
	 * Automatically switch to edit mode
	 *
	 * @return string
	 */
	public function copy()
	{
		return $this->edit();
	}


	/**
	 * Automatically switch to edit mode
	 *
	 * @return string
	 */
	public function move()
	{
		return $this->edit();
	}


	/**
	 * Auto-generate a form to edit the local configuration file
	 *
	 * @return string
	 */
	public function edit()
	{
		$return = '';
		$ajaxId = null;

		if (\Environment::get('isAjaxRequest'))
		{
			$ajaxId = func_get_arg(1);
		}

		// Build an array from boxes and rows
		$this->strPalette = $this->getPalette();
		$boxes = trimsplit(';', $this->strPalette);
		$legends = array();

		if (!empty($boxes))
		{
			foreach ($boxes as $k=>$v)
			{
				$boxes[$k] = trimsplit(',', $v);

				foreach ($boxes[$k] as $kk=>$vv)
				{
					if (preg_match('/^\[.*\]$/', $vv))
					{
						continue;
					}

					if (preg_match('/^\{.*\}$/', $vv))
					{
						$legends[$k] = substr($vv, 1, -1);
						unset($boxes[$k][$kk]);
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$vv]['exclude'] || !is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$vv]))
					{
						unset($boxes[$k][$kk]);
					}
				}

				// Unset a box if it does not contain any fields
				if (empty($boxes[$k]))
				{
					unset($boxes[$k]);
				}
			}

			// Render boxes
			$class = 'tl_tbox';
			$fs = $this->Session->get('fieldset_states');
			$blnIsFirst = true;

			foreach ($boxes as $k=>$v)
			{
				$strAjax = '';
				$blnAjax = false;
				$key = '';
				$cls = '';
				$legend = '';

				if (isset($legends[$k]))
				{
					list($key, $cls) = explode(':', $legends[$k]);
					$legend = "\n" . '<legend onclick="AjaxRequest.toggleFieldset(this, \'' . $key . '\', \'' . $this->strTable . '\')">' . (isset($GLOBALS['TL_LANG'][$this->strTable][$key]) ? $GLOBALS['TL_LANG'][$this->strTable][$key] : $key) . '</legend>';
				}

				if (isset($fs[$this->strTable][$key]))
				{
					$class .= ($fs[$this->strTable][$key] ? '' : ' collapsed');
				}
				else
				{
					$class .= (($cls && $legend) ? ' ' . $cls : '');
				}

				$return .= "\n\n" . '<fieldset' . ($key ? ' id="pal_'.$key.'"' : '') . ' class="' . $class . ($legend ? '' : ' nolegend') . '">' . $legend;

				// Build rows of the current box
				foreach ($v as $vv)
				{
					if ($vv == '[EOF]')
					{
						if ($blnAjax && \Environment::get('isAjaxRequest'))
						{
							return $strAjax . '<input type="hidden" name="FORM_FIELDS[]" value="'.specialchars($this->strPalette).'">';
						}

						$blnAjax = false;
						$return .= "\n  " . '</div>';

						continue;
					}

					if (preg_match('/^\[.*\]$/', $vv))
					{
						$thisId = 'sub_' . substr($vv, 1, -1);
						$blnAjax = ($ajaxId == $thisId && \Environment::get('isAjaxRequest')) ? true : false;
						$return .= "\n  " . '<div id="'.$thisId.'">';

						continue;
					}

					$this->strField = $vv;
					$this->strInputName = $vv;
					$this->varValue = \Config::get($this->strField);

					// Handle entities
					if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] == 'text' || $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] == 'textarea')
					{
						if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['multiple'])
						{
							$this->varValue = deserialize($this->varValue);
						}

						if (!is_array($this->varValue))
						{
							$this->varValue = htmlspecialchars($this->varValue);
						}
						else
						{
							foreach ($this->varValue as $k=>$v)
							{
								$this->varValue[$k] = htmlspecialchars($v);
							}
						}
					}

					// Autofocus the first field
					if ($blnIsFirst && $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] == 'text')
					{
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['autofocus'] = 'autofocus';
						$blnIsFirst = false;
					}

					// Call load_callback
					if (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback']))
					{
						foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback'] as $callback)
						{
							if (is_array($callback))
							{
								$this->import($callback[0]);
								$this->varValue = $this->$callback[0]->$callback[1]($this->varValue, $this);
							}
							elseif (is_callable($callback))
							{
								$this->varValue = $callback($this->varValue, $this);
							}
						}
					}

					// Build row
					$blnAjax ? $strAjax .= $this->row() : $return .= $this->row();
				}

				$class = 'tl_box';
				$return .= "\n" . '</fieldset>';
			}
		}

		$this->import('Files');

		// Check whether the target file is writeable
		if (!$this->Files->is_writeable('system/config/localconfig.php'))
		{
			\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['notWriteable'], 'system/config/localconfig.php'));
		}

		// Submit buttons
		$arrButtons = array();
		$arrButtons['save'] = '<input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['save']).'">';
		$arrButtons['saveNclose'] = '<input type="submit" name="saveNclose" id="saveNclose" class="tl_submit" accesskey="c" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['saveNclose']).'">';

		// Call the buttons_callback (see #4691)
		if (is_array($GLOBALS['TL_DCA'][$this->strTable]['edit']['buttons_callback']))
		{
			foreach ($GLOBALS['TL_DCA'][$this->strTable]['edit']['buttons_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$arrButtons = $this->$callback[0]->$callback[1]($arrButtons, $this);
				}
				elseif (is_callable($callback))
				{
					$arrButtons = $callback($arrButtons, $this);
				}
			}
		}

		// Add the buttons and end the form
		$return .= '
</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  ' . implode(' ', $arrButtons) . '
</div>

</div>
</form>

<script>
  window.addEvent(\'domready\', function() {
    Theme.focusInput("'.$this->strTable.'");
  });
</script>';

		// Begin the form (-> DO NOT CHANGE THIS ORDER -> this way the onsubmit attribute of the form can be changed by a field)
		$return = '
<div id="tl_buttons">
<a href="'.$this->getReferer(true).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>
'.\Message::generate().'
<form action="'.ampersand(\Environment::get('request'), true).'" id="'.$this->strTable.'" class="tl_form" method="post"'.(!empty($this->onsubmit) ? ' onsubmit="'.implode(' ', $this->onsubmit).'"' : '').'>

<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="'.specialchars($this->strTable).'">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
<input type="hidden" name="FORM_FIELDS[]" value="'.specialchars($this->strPalette).'">'.($this->noReload ? '

<p class="tl_error">'.$GLOBALS['TL_LANG']['ERR']['general'].'</p>' : '').$return;

		// Reload the page to prevent _POST variables from being sent twice
		if (\Input::post('FORM_SUBMIT') == $this->strTable && !$this->noReload)
		{
			// Call onsubmit_callback
			if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['onsubmit_callback']))
			{
				foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['onsubmit_callback'] as $callback)
				{
					if (is_array($callback))
					{
						$this->import($callback[0]);
						$this->$callback[0]->$callback[1]($this);
					}
					elseif (is_callable($callback))
					{
						$callback($this);
					}
				}
			}

			// Reload
			if (\Input::post('saveNclose'))
			{
				\Message::reset();
				\System::setCookie('BE_PAGE_OFFSET', 0, 0);
				$this->redirect($this->getReferer());
			}

			$this->reload();
		}

		// Set the focus if there is an error
		if ($this->noReload)
		{
			$return .= '

<script>
  window.addEvent(\'domready\', function() {
    Backend.vScrollTo(($(\'' . $this->strTable . '\').getElement(\'label.error\').getPosition().y - 20));
  });
</script>';
		}

		return $return;
	}


	/**
	 * Save the current value
	 *
	 * @param mixed $varValue
	 */
	protected function save($varValue)
	{
		if (\Input::post('FORM_SUBMIT') != $this->strTable)
		{
			return;
		}

		$arrData = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField];

		// Make sure that checkbox values are boolean
		if ($arrData['inputType'] == 'checkbox' && !$arrData['eval']['multiple'])
		{
			$varValue = $varValue ? true : false;
		}

		if ($varValue != '')
		{
			// Convert binary UUIDs (see #6893)
			if ($arrData['inputType'] == 'fileTree')
			{
				$varValue = deserialize($varValue);

				if (!is_array($varValue))
				{
					$varValue = \String::binToUuid($varValue);
				}
				else
				{
					$varValue = serialize(array_map('String::binToUuid', $varValue));
				}
			}

			// Convert date formats into timestamps
			if ($varValue != '' && in_array($arrData['eval']['rgxp'], array('date', 'time', 'datim')))
			{
				$objDate = new \Date($varValue, \Date::getFormatFromRgxp($arrData['eval']['rgxp']));
				$varValue = $objDate->tstamp;
			}

			// Handle entities
			if ($arrData['inputType'] == 'text' || $arrData['inputType'] == 'textarea')
			{
				$varValue = deserialize($varValue);

				if (!is_array($varValue))
				{
					$varValue = \String::restoreBasicEntities($varValue);
				}
				else
				{
					$varValue = serialize(array_map('String::restoreBasicEntities', $varValue));
				}
			}
		}

		// Trigger the save_callback
		if (is_array($arrData['save_callback']))
		{
			foreach ($arrData['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$varValue = $this->$callback[0]->$callback[1]($varValue, $this);
				}
				elseif (is_callable($callback))
				{
					$varValue = $callback($varValue, $this);
				}
			}
		}

		$strCurrent = $this->varValue;

		// Handle arrays and strings
		if (is_array($strCurrent))
		{
			$strCurrent = serialize($strCurrent);
		}
		elseif (is_string($strCurrent))
		{
			$strCurrent = html_entity_decode($this->varValue, ENT_QUOTES, \Config::get('characterSet'));
		}

		// Save the value if there was no error
		if ((strlen($varValue) || !$arrData['eval']['doNotSaveEmpty']) && $strCurrent != $varValue)
		{
			\Config::persist($this->strField, $varValue);

			$deserialize = deserialize($varValue);
			$prior = is_bool(\Config::get($this->strField)) ? (\Config::get($this->strField) ? 'true' : 'false') : \Config::get($this->strField);

			// Add a log entry
			if (!is_array(deserialize($prior)) && !is_array($deserialize))
			{
				if ($arrData['inputType'] == 'password' || $arrData['inputType'] == 'textStore')
				{
					$this->log('The global configuration variable "'.$this->strField.'" has been changed', __METHOD__, TL_CONFIGURATION);
				}
				else
				{
					$this->log('The global configuration variable "'.$this->strField.'" has been changed from "'.$prior.'" to "'.$varValue.'"', __METHOD__, TL_CONFIGURATION);
				}
			}

			// Set the new value so the input field can show it
			$this->varValue = $deserialize;
			\Config::set($this->strField, $deserialize);
		}
	}


	/**
	 * Return the name of the current palette
	 *
	 * @return string
	 */
	public function getPalette()
	{
		$palette = 'default';
		$strPalette = $GLOBALS['TL_DCA'][$this->strTable]['palettes'][$palette];

		// Check whether there are selector fields
		if (!empty($GLOBALS['TL_DCA'][$this->strTable]['palettes']['__selector__']))
		{
			$sValues = array();
			$subpalettes = array();

			foreach ($GLOBALS['TL_DCA'][$this->strTable]['palettes']['__selector__'] as $name)
			{
				$trigger = \Config::get($name);

				// Overwrite the trigger if the page is not reloaded
				if (\Input::post('FORM_SUBMIT') == $this->strTable)
				{
					$key = (\Input::get('act') == 'editAll') ? $name.'_'.$this->intId : $name;

					if (!$GLOBALS['TL_DCA'][$this->strTable]['fields'][$name]['eval']['submitOnChange'])
					{
						$trigger = \Input::post($key);
					}
				}

				if ($trigger != '')
				{
					if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$name]['inputType'] == 'checkbox' && !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$name]['eval']['multiple'])
					{
						$sValues[] = $name;

						// Look for a subpalette
						if (strlen($GLOBALS['TL_DCA'][$this->strTable]['subpalettes'][$name]))
						{
							$subpalettes[$name] = $GLOBALS['TL_DCA'][$this->strTable]['subpalettes'][$name];
						}
					}
					else
					{
						$sValues[] = $trigger;
						$key = $name .'_'. $trigger;

						// Look for a subpalette
						if (strlen($GLOBALS['TL_DCA'][$this->strTable]['subpalettes'][$key]))
						{
							$subpalettes[$name] = $GLOBALS['TL_DCA'][$this->strTable]['subpalettes'][$key];
						}
					}
				}
			}

			// Build possible palette names from the selector values
			if (!count($sValues))
			{
				$names = array('default');
			}
			elseif (count($sValues) > 1)
			{
				$names = $this->combiner($sValues);
			}
			else
			{
				$names = array($sValues[0]);
			}

			// Get an existing palette
			foreach ($names as $paletteName)
			{
				if (strlen($GLOBALS['TL_DCA'][$this->strTable]['palettes'][$paletteName]))
				{
					$strPalette = $GLOBALS['TL_DCA'][$this->strTable]['palettes'][$paletteName];
					break;
				}
			}

			// Include subpalettes
			foreach ($subpalettes as $k=>$v)
			{
				$strPalette = preg_replace('/\b'. preg_quote($k, '/').'\b/i', $k.',['.$k.'],'.$v.',[EOF]', $strPalette);
			}
		}

		return $strPalette;
	}
}
