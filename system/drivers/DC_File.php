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
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class DC_File
 *
 * Provide methods to edit the local configuration file.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class DC_File extends DataContainer implements editable
{

	/**
	 * Initialize the object
	 * @param string
	 */
	public function __construct($strTable)
	{
		parent::__construct();
		$this->intId = $this->Input->get('id');

		// Check whether the table is defined
		if ($strTable == '' || !isset($GLOBALS['TL_DCA'][$strTable])) 
		{
			$this->log('Could not load data container configuration for "' . $strTable . '"', 'DC_File __construct()', TL_ERROR);
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
			}
		}
	}


	/**
	 * Automatically switch to edit mode
	 * @return string
	 */
	public function create()
	{
		return $this->edit();
	}


	/**
	 * Automatically switch to edit mode
	 * @return string
	 */
	public function cut()
	{
		return $this->edit();
	}


	/**
	 * Automatically switch to edit mode
	 * @return string
	 */
	public function copy()
	{
		return $this->edit();
	}


	/**
	 * Automatically switch to edit mode
	 * @return string
	 */
	public function move()
	{
		return $this->edit();
	}


	/**
	 * Auto-generate a form to edit the local configuration file
	 * @return string
	 */
	public function edit()
	{
		$return = '';
		$ajaxId = null;

		if ($this->Environment->isAjaxRequest)
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
					if (preg_match('/^\[.*\]$/i', $vv))
					{
						continue;
					}

					if (preg_match('/^\{.*\}$/i', $vv))
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
				foreach ($v as $kk=>$vv)
				{
					if ($vv == '[EOF]')
					{
						if ($blnAjax && $this->Environment->isAjaxRequest)
						{
							return $strAjax . '<input type="hidden" name="FORM_FIELDS[]" value="'.specialchars($this->strPalette).'">';
						}

						$blnAjax = false;
						$return .= "\n  " . '</div>';

						continue;
					}

					if (preg_match('/^\[.*\]$/i', $vv))
					{
						$thisId = 'sub_' . substr($vv, 1, -1);
						$blnAjax = ($ajaxId == $thisId && $this->Environment->isAjaxRequest) ? true : false;
						$return .= "\n  " . '<div id="'.$thisId.'">';

						continue;
					}

					$this->strField = $vv;
					$this->strInputName = $vv;
					$this->varValue = $GLOBALS['TL_CONFIG'][$this->strField];

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
			$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['notWriteable'], 'system/config/localconfig.php'));
		}

		// Add some buttons and end the form
		$return .= '
</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['save']).'"> 
<input type="submit" name="saveNclose" id="saveNclose" class="tl_submit" accesskey="c" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['saveNclose']).'">
</div>

</div>
</form>

<script>
window.addEvent(\'domready\', function() {
  if (input = $(\''.$this->strTable.'\').getElement(\'input[type="text"]\')) {
    input.focus();
  }
});
</script>';

		// Begin the form (-> DO NOT CHANGE THIS ORDER -> this way the onsubmit attribute of the form can be changed by a field)
		$return = '
<div id="tl_buttons">
<a href="'.$this->getReferer(true).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG'][$this->strTable]['edit'].'</h2>
'.$this->getMessages().'
<form action="'.ampersand($this->Environment->request, true).'" id="'.$this->strTable.'" class="tl_form" method="post"'.(!empty($this->onsubmit) ? ' onsubmit="'.implode(' ', $this->onsubmit).'"' : '').'>

<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="'.specialchars($this->strTable).'">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
<input type="hidden" name="FORM_FIELDS[]" value="'.specialchars($this->strPalette).'">'.($this->noReload ? '

<p class="tl_error">'.$GLOBALS['TL_LANG']['ERR']['general'].'</p>' : '').$return;

		// Reload the page to prevent _POST variables from being sent twice
		if ($this->Input->post('FORM_SUBMIT') == $this->strTable && !$this->noReload)
		{
			// Call onsubmit_callback
			if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['onsubmit_callback']))
			{
				foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['onsubmit_callback'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($this);
				}
			}

			// Reload
			if ($this->Input->post('saveNclose'))
			{
				$this->resetMessages();
				$this->setCookie('BE_PAGE_OFFSET', 0, 0);
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
	 * @param mixed
	 */
	protected function save($varValue)
	{
		if ($this->Input->post('FORM_SUBMIT') != $this->strTable)
		{
			return;
		}

		$arrData = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField];

		// Make sure that checkbox values are boolean
		if ($arrData['inputType'] == 'checkbox' && !$arrData['eval']['multiple'])
		{
			$varValue = $varValue ? true : false;
		}

		// Convert date formats into timestamps
		if ($varValue != '' && in_array($arrData['eval']['rgxp'], array('date', 'time', 'datim')))
		{
			$objDate = new Date($varValue, $GLOBALS['TL_CONFIG'][$arrData['eval']['rgxp'] . 'Format']);
			$varValue = $objDate->tstamp;
		}

		// Handle entities
		if ($arrData['inputType'] == 'text' || $arrData['inputType'] == 'textarea')
		{
			$varValue = deserialize($varValue);

			if (!is_array($varValue))
			{
				$varValue = $this->restoreBasicEntities($varValue);
			}
			else
			{
				foreach ($varValue as $k=>$v)
				{
					$varValue[$k] = $this->restoreBasicEntities($v);
				}

				$varValue = serialize($varValue);
			}
		}

		// Trigger the save_callback
		if (is_array($arrData['save_callback']))
		{
			foreach ($arrData['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$varValue = $this->$callback[0]->$callback[1]($varValue, $this);
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
			$strCurrent = html_entity_decode($this->varValue, ENT_COMPAT, $GLOBALS['TL_CONFIG']['characterSet']);
		}

		// Save the value if there was no error
		if ((strlen($varValue) || !$arrData['eval']['doNotSaveEmpty']) && $strCurrent !== $varValue)
		{
			$strKey = sprintf("\$GLOBALS['TL_CONFIG']['%s']", $this->strField);
			$this->Config->update($strKey, $varValue);

			$deserialize = deserialize($varValue);
			$prior = is_bool($GLOBALS['TL_CONFIG'][$this->strField]) ? ($GLOBALS['TL_CONFIG'][$this->strField] ? 'true' : 'false') : $GLOBALS['TL_CONFIG'][$this->strField];

			// Add a log entry
			if (!is_array(deserialize($prior)) && !is_array($deserialize))
			{
				if ($arrData['inputType'] == 'password' || $arrData['inputType'] == 'textStore')
				{
					$this->log('The global configuration variable "'.$this->strField.'" has been changed', 'DC_File save()', TL_CONFIGURATION);
				}
				else
				{
					$this->log('The global configuration variable "'.$this->strField.'" has been changed from "'.$prior.'" to "'.$varValue.'"', 'DC_File save()', TL_CONFIGURATION);
				}
			}

			// Set the new value so the input field can show it
			$this->varValue = $deserialize;
			$GLOBALS['TL_CONFIG'][$this->strField] = $deserialize;
		}
	}


	/**
	 * Return the name of the current palette
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
				$trigger = $GLOBALS['TL_CONFIG'][$name];

				// Overwrite the trigger if the page is not reloaded
				if ($this->Input->post('FORM_SUBMIT') == $this->strTable)
				{
					$key = ($this->Input->get('act') == 'editAll') ? $name.'_'.$this->intId : $name;

					if (!$GLOBALS['TL_DCA'][$this->strTable]['fields'][$name]['eval']['submitOnChange'])
					{
						$trigger = $this->Input->post($key);
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
					$palette = $paletteName;
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

?>