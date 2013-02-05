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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModulePersonalData
 *
 * Front end module "personal data".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class ModulePersonalData extends Module
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
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### PERSONAL DATA ###';
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

		$this->loadLanguageFile('tl_member');
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
			}
		}

		// Set template
		if (strlen($this->memberTpl))
		{
			$this->Template = new FrontendTemplate($this->memberTpl);
			$this->Template->setData($this->arrData);
		}

		$this->Template->fields = '';
		$this->Template->tableless = $this->tableless;

		$arrFields = array();
		$doNotSubmit = false;
		$hasUpload = false;
		$row = 0;

		// Build form
		foreach ($this->editable as $field)
		{
			$arrData = &$GLOBALS['TL_DCA']['tl_member']['fields'][$field];

			// Map checkboxWizard to regular checkbox widget
			if ($arrData['inputType'] == 'checkboxWizard')
			{
				$arrData['inputType'] = 'checkbox';
			}

			$strClass = $GLOBALS['TL_FFL'][$arrData['inputType']];

			// Continue if the class is not defined
			if (!$this->classFileExists($strClass) || !$arrData['eval']['feEditable'])
			{
				continue;
			}

			$strGroup = $arrData['eval']['feGroup'];

			$arrData['eval']['tableless'] = $this->tableless;
			$arrData['eval']['required'] = ($this->User->$field == '' && $arrData['eval']['mandatory']) ? true : false;

			$objWidget = new $strClass($this->prepareForWidget($arrData, $field, $this->User->$field));

			$objWidget->storeValues = true;
			$objWidget->rowClass = 'row_'.$row . (($row == 0) ? ' row_first' : '') . ((($row % 2) == 0) ? ' even' : ' odd');

			// Increase the row count if its a password field
			if ($objWidget instanceof FormPassword)
			{
				++$row;
				$objWidget->rowClassConfirm = 'row_'.$row . ((($row % 2) == 0) ? ' even' : ' odd');
			}

			// Validate input
			if ($this->Input->post('FORM_SUBMIT') == 'tl_member_' . $this->id)
			{
				$objWidget->validate();
				$varValue = $objWidget->value;

				$rgxp = $arrData['eval']['rgxp'];

				// Convert date formats into timestamps (check the eval setting first -> #3063)
				if (($rgxp == 'date' || $rgxp == 'time' || $rgxp == 'datim') && $varValue != '')
				{
					try
					{
						$objDate = new Date($varValue);
						$varValue = $objDate->tstamp;
					}
					catch (Exception $e)
					{
						$objWidget->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalidDate'], $varValue));
					}
				}

				// Make sure that unique fields are unique (check the eval setting first -> #3063)
				if ($arrData['eval']['unique'] && $varValue != '')
				{
					$objUnique = $this->Database->prepare("SELECT * FROM tl_member WHERE " . $field . "=? AND id!=?")
												->limit(1)
												->execute($varValue, $this->User->id);

					if ($objUnique->numRows)
					{
						$objWidget->addError(sprintf($GLOBALS['TL_LANG']['ERR']['unique'], (strlen($arrData['label'][0]) ? $arrData['label'][0] : $field)));
					}
				}

				// Save callback (see #5247)
				if (!$objWidget->hasErrors() && is_array($arrData['save_callback']))
				{
					foreach ($arrData['save_callback'] as $callback)
					{
						$this->import($callback[0]);

						try
						{
							$varValue = $this->$callback[0]->$callback[1]($varValue, $this->User, $this);
						}
						catch (Exception $e)
						{
							$objWidget->class = 'error';
							$objWidget->addError($e->getMessage());
						}
					}
				}

				// Do not submit if there are errors
				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}

				// Store current value
				elseif ($objWidget->submitInput())
				{
					// Set new value
					$this->User->$field = $varValue;
					$_SESSION['FORM_DATA'][$field] = $varValue;
					$varSave = is_array($varValue) ? serialize($varValue) : $varValue;

					// Save field
					$this->Database->prepare("UPDATE tl_member SET " . $field . "=? WHERE id=?")
								   ->execute($varSave, $this->User->id);

					// HOOK: set new password callback
					if ($objWidget instanceof FormPassword && isset($GLOBALS['TL_HOOKS']['setNewPassword']) && is_array($GLOBALS['TL_HOOKS']['setNewPassword']))
					{
						foreach ($GLOBALS['TL_HOOKS']['setNewPassword'] as $callback)
						{
							$this->import($callback[0]);
							$this->$callback[0]->$callback[1]($this->User, $varValue, $this);
						}
					}
				}
			}

			if ($objWidget instanceof uploadable)
			{
				$hasUpload = true;
			}

			$temp = $objWidget->parse();

			$this->Template->fields .= $temp;
			$arrFields[$strGroup][$field] .= $temp;
			++$row;
		}

		$this->Template->hasError = $doNotSubmit;

		// Redirect or reload if there was no error
		if ($this->Input->post('FORM_SUBMIT') == 'tl_member_' . $this->id && !$doNotSubmit)
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
			
			$this->jumpToOrReload($this->jumpTo);
		}

		$this->Template->loginDetails = $GLOBALS['TL_LANG']['tl_member']['loginDetails'];
		$this->Template->addressDetails = $GLOBALS['TL_LANG']['tl_member']['addressDetails'];
		$this->Template->contactDetails = $GLOBALS['TL_LANG']['tl_member']['contactDetails'];
		$this->Template->personalData = $GLOBALS['TL_LANG']['tl_member']['personalData'];

		// Add groups
		foreach ($arrFields as $k=>$v)
		{
			$this->Template->$k = $v;
		}

		$this->Template->formId = 'tl_member_' . $this->id;
		$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['saveData']);
		$this->Template->action = $this->getIndexFreeRequest();
		$this->Template->enctype = $hasUpload ? 'multipart/form-data' : 'application/x-www-form-urlencoded';
		$this->Template->rowLast = 'row_' . $row . ((($row % 2) == 0) ? ' even' : ' odd');

		// HOOK: add memberlist fields
		if (in_array('memberlist', $this->Config->getActiveModules()))
		{
			$this->Template->profile = $arrFields['profile'];
			$this->Template->profileDetails = $GLOBALS['TL_LANG']['tl_member']['profileDetails'];
		}

		// HOOK: add newsletter fields
		if (in_array('newsletter', $this->Config->getActiveModules()))
		{
			$this->Template->newsletter = $arrFields['newsletter'];
			$this->Template->newsletterDetails = $GLOBALS['TL_LANG']['tl_member']['newsletterDetails'];
		}

		// HOOK: add helpdesk fields
		if (in_array('helpdesk', $this->Config->getActiveModules()))
		{
			$this->Template->helpdesk = $arrFields['helpdesk'];
			$this->Template->helpdeskDetails = $GLOBALS['TL_LANG']['tl_member']['helpdeskDetails'];
		}
	}
}

?>