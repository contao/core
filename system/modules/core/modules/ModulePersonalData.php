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
 * Class ModulePersonalData
 *
 * Front end module "personal data".
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ModulePersonalData extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'member_default';


	/**
	 * Return a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['personalData'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->editable = deserialize($this->editable);

		// Return if there are not editable fields or if there is no logged in user
		if (!is_array($this->editable) || empty($this->editable) || !FE_USER_LOGGED_IN)
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;
		$this->import('FrontendUser', 'User');

		$GLOBALS['TL_LANGUAGE'] = $objPage->language;

		\System::loadLanguageFile('tl_member');
		$this->loadDataContainer('tl_member');

		// Call onload_callback (e.g. to check permissions)
		if (is_array($GLOBALS['TL_DCA']['tl_member']['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_member']['config']['onload_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]();
				}
				elseif (is_callable($callback))
				{
					$callback();
				}
			}
		}

		// Set the template
		if ($this->memberTpl != '')
		{
			$this->Template = new \FrontendTemplate($this->memberTpl);
			$this->Template->setData($this->arrData);
		}

		$this->Template->fields = '';
		$this->Template->tableless = $this->tableless;

		$arrFields = array();
		$doNotSubmit = false;
		$hasUpload = false;
		$row = 0;

		// Predefine the group order (other groups will be appended automatically)
		$arrGroups = array
		(
			'personal' => array(),
			'address'  => array(),
			'contact'  => array(),
			'login'    => array(),
			'profile'  => array()
		);

		$blnModified = false;
		$objMember = \MemberModel::findByPk($this->User->id);

		// Build the form
		foreach ($this->editable as $field)
		{
			$arrData = &$GLOBALS['TL_DCA']['tl_member']['fields'][$field];

			// Map checkboxWizards to regular checkbox widgets
			if ($arrData['inputType'] == 'checkboxWizard')
			{
				$arrData['inputType'] = 'checkbox';
			}

			$strClass = $GLOBALS['TL_FFL'][$arrData['inputType']];

			// Continue if the class does not exist
			if (!$arrData['eval']['feEditable'] || !class_exists($strClass))
			{
				continue;
			}

			$strGroup = $arrData['eval']['feGroup'];

			$arrData['eval']['required'] = false;
			$arrData['eval']['tableless'] = $this->tableless;

			// Use strlen() here (see #3277)
			if ($arrData['eval']['mandatory'])
			{
				if (is_array($this->User->$field))
				{
					 if (empty($this->User->$field))
					 {
					 	$arrData['eval']['required'] = true;
					 }
				}
				else
				{
					if (!strlen($this->User->$field))
					{
						$arrData['eval']['required'] = true;
					}
				}
			}

			$varValue = $this->User->$field;

			// Call the load_callback
			if (isset($arrData['load_callback']) && is_array($arrData['load_callback']))
			{
				foreach ($arrData['load_callback'] as $callback)
				{
					if (is_array($callback))
					{
						$this->import($callback[0]);
						$varValue = $this->$callback[0]->$callback[1]($varValue, $this->User, $this);
					}
					elseif (is_callable($callback))
					{
						$varValue = $callback($varValue, $this->User, $this);
					}
				}
			}

			$objWidget = new $strClass($strClass::getAttributesFromDca($arrData, $field, $varValue, '', '', $this));

			$objWidget->storeValues = true;
			$objWidget->rowClass = 'row_'.$row . (($row == 0) ? ' row_first' : '') . ((($row % 2) == 0) ? ' even' : ' odd');

			// Increase the row count if it is a password field
			if ($objWidget instanceof \FormPassword)
			{
				++$row;
				$objWidget->rowClassConfirm = 'row_'.$row . ((($row % 2) == 0) ? ' even' : ' odd');
			}

			// Validate the form data
			if (\Input::post('FORM_SUBMIT') == 'tl_member_' . $this->id)
			{
				$objWidget->validate();
				$varValue = $objWidget->value;

				$rgxp = $arrData['eval']['rgxp'];

				// Convert date formats into timestamps (check the eval setting first -> #3063)
				if (($rgxp == 'date' || $rgxp == 'time' || $rgxp == 'datim') && $varValue != '')
				{
					try
					{
						$objDate = new \Date($varValue);
						$varValue = $objDate->tstamp;
					}
					catch (\OutOfBoundsException $e)
					{
						$objWidget->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalidDate'], $varValue));
					}
				}

				// Make sure that unique fields are unique (check the eval setting first -> #3063)
				if ($arrData['eval']['unique'] && $varValue != '' && !$this->Database->isUniqueValue('tl_member', $field, $varValue, $this->User->id))
				{
					$objWidget->addError(sprintf($GLOBALS['TL_LANG']['ERR']['unique'], $arrData['label'][0] ?: $field));
				}

				// Trigger the save_callback (see #5247)
				if ($objWidget->submitInput() && !$objWidget->hasErrors() && is_array($arrData['save_callback']))
				{
					foreach ($arrData['save_callback'] as $callback)
					{
						try
						{
							if (is_array($callback))
							{
								$this->import($callback[0]);
								$varValue = $this->$callback[0]->$callback[1]($varValue, $this->User, $this);
							}
							elseif (is_callable($callback))
							{
								$varValue = $callback($varValue, $this->User, $this);
							}
						}
						catch (\Exception $e)
						{
							$objWidget->class = 'error';
							$objWidget->addError($e->getMessage());
						}
					}
				}

				// Do not submit the field if there are errors
				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}
				elseif ($objWidget->submitInput())
				{
					// Store the form data
					$_SESSION['FORM_DATA'][$field] = $varValue;

					// Set the correct empty value (see #6284, #6373)
					if ($varValue === '')
					{
						$varValue = $objWidget->getEmptyValue();
					}

					// Set the new value
					$this->User->$field = $varValue;

					// Set the new field in the member model
					$blnModified = true;
					$objMember->$field = $varValue;
				}
			}

			if ($objWidget instanceof \uploadable)
			{
				$hasUpload = true;
			}

			$temp = $objWidget->parse();

			$this->Template->fields .= $temp;
			$arrFields[$strGroup][$field] .= $temp;
			++$row;
		}

		// Save the model
		if ($blnModified)
		{
			$objMember->save();

			$strTable = $objMember->getTable();

			// Create a new version
			if ($GLOBALS['TL_DCA'][$strTable]['config']['enableVersioning'])
			{
				$intVersion = 1;

				$objVersion = $this->Database->prepare("SELECT MAX(version) AS version FROM tl_version WHERE pid=? AND fromTable=?")
											 ->execute($objMember->id, $strTable);

				if ($objVersion->version !== null)
				{
					$intVersion = $objVersion->version + 1;
				}

				$strUrl = 'contao/main.php?do=member&act=edit&id=' . $objMember->id . '&rt=1';

				$this->Database->prepare("UPDATE tl_version SET active='' WHERE pid=? AND fromTable=?")
							   ->execute($objMember->id, $strTable);

				$this->Database->prepare("INSERT INTO tl_version (pid, tstamp, version, fromTable, username, userid, description, editUrl, active, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?)")
							   ->execute($objMember->id, time(), $intVersion, $strTable, $objMember->username, 0, $objMember->firstname . ' ' . $objMember->lastname, $strUrl, serialize($objMember->row()));

				$this->log('A new version of record "'.$strTable.'.id='.$objMember->id.'" has been created'.$this->getParentEntries($strTable, $objMember->id), __METHOD__, TL_GENERAL);
			}
		}

		$this->Template->hasError = $doNotSubmit;

		// Redirect or reload if there was no error
		if (\Input::post('FORM_SUBMIT') == 'tl_member_' . $this->id && !$doNotSubmit)
		{
			// HOOK: updated personal data
			if (isset($GLOBALS['TL_HOOKS']['updatePersonalData']) && is_array($GLOBALS['TL_HOOKS']['updatePersonalData']))
			{
				foreach ($GLOBALS['TL_HOOKS']['updatePersonalData'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($this->User, $_SESSION['FORM_DATA'], $this);
				}
			}

			// Call the onsubmit_callback
			if (is_array($GLOBALS['TL_DCA']['tl_member']['config']['onsubmit_callback']))
			{
				foreach ($GLOBALS['TL_DCA']['tl_member']['config']['onsubmit_callback'] as $callback)
				{
					if (is_array($callback))
					{
						$this->import($callback[0]);
						$this->$callback[0]->$callback[1]($this->User, $this);
					}
					elseif (is_callable($callback))
					{
						$callback($this->User, $this);
					}
				}
			}

			// Check whether there is a jumpTo page
			if (($objJumpTo = $this->objModel->getRelated('jumpTo')) !== null)
			{
				$this->jumpToOrReload($objJumpTo->row());
			}

			$this->reload();
		}

		$this->Template->loginDetails = $GLOBALS['TL_LANG']['tl_member']['loginDetails'];
		$this->Template->addressDetails = $GLOBALS['TL_LANG']['tl_member']['addressDetails'];
		$this->Template->contactDetails = $GLOBALS['TL_LANG']['tl_member']['contactDetails'];
		$this->Template->personalData = $GLOBALS['TL_LANG']['tl_member']['personalData'];

		// Add the groups
		foreach ($arrFields as $k=>$v)
		{
			$this->Template->$k = $v; // backwards compatibility

			$key = $k . (($k == 'personal') ? 'Data' : 'Details');
			$arrGroups[$GLOBALS['TL_LANG']['tl_member'][$key]] = $v;
		}

		$this->Template->categories = $arrGroups;
		$this->Template->formId = 'tl_member_' . $this->id;
		$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['saveData']);
		$this->Template->action = \Environment::get('indexFreeRequest');
		$this->Template->enctype = $hasUpload ? 'multipart/form-data' : 'application/x-www-form-urlencoded';
		$this->Template->rowLast = 'row_' . $row . ((($row % 2) == 0) ? ' even' : ' odd');
	}
}
