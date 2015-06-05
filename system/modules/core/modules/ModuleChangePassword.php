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
 * Front end module "change password".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleChangePassword extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_change_password';


	/**
	 * Display a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			/** @var \BackendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['changePassword'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Return if there is no logged in user
		if (!FE_USER_LOGGED_IN)
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
		/** @var \PageModel $objPage */
		global $objPage;

		$this->import('FrontendUser', 'User');

		$GLOBALS['TL_LANGUAGE'] = $objPage->language;

		\System::loadLanguageFile('tl_member');
		$this->loadDataContainer('tl_member');

		// Old password widget
		$arrFields['oldPassword'] = array
		(
			'name'      => 'oldpassword',
			'label'     => &$GLOBALS['TL_LANG']['MSC']['oldPassword'],
			'inputType' => 'text',
			'eval'      => array('mandatory'=>true, 'preserveTags'=>true, 'hideInput'=>true),
		);

		// New password widget
		$arrFields['newPassword'] = $GLOBALS['TL_DCA']['tl_member']['fields']['password'];
		$arrFields['newPassword']['name'] = 'password';
		$arrFields['newPassword']['label'] = &$GLOBALS['TL_LANG']['MSC']['newPassword'];

		$row = 0;
		$strFields = '';
		$doNotSubmit = false;
		$objMember = \MemberModel::findByPk($this->User->id);

		/** @var \FormTextField $objOldPassword */
		$objOldPassword = null;

		/** @var \FormPassword $objNewPassword */
		$objNewPassword = null;

		// Initialize the widgets
		foreach ($arrFields as $strKey=>$arrField)
		{
			/** @var \Widget $strClass */
			$strClass = $GLOBALS['TL_FFL'][$arrField['inputType']];

			// Continue if the class is not defined
			if (!class_exists($strClass))
			{
				continue;
			}

			$arrField['eval']['tableless'] = $this->tableless;
			$arrField['eval']['required'] = $arrField['eval']['mandatory'];

			/** @var \Widget $objWidget */
			$objWidget = new $strClass($strClass::getAttributesFromDca($arrField, $arrField['name']));

			$objWidget->storeValues = true;
			$objWidget->rowClass = 'row_' . $row . (($row == 0) ? ' row_first' : '') . ((($row % 2) == 0) ? ' even' : ' odd');

			// Increase the row count if it is a password field
			if ($objWidget instanceof \FormPassword)
			{
				$objWidget->rowClassConfirm = 'row_' . ++$row . ((($row % 2) == 0) ? ' even' : ' odd');
			}

			++$row;

			// Store the widget objects
			$strVar  = 'obj' . ucfirst($strKey);
			$$strVar = $objWidget;

			// Validate the widget
			if (\Input::post('FORM_SUBMIT') == 'tl_change_password')
			{
				$objWidget->validate();

				// Validate the old password
				if ($strKey == 'oldPassword')
				{
					if (\Encryption::test($objMember->password))
					{
						$blnAuthenticated = \Encryption::verify($objWidget->value, $objMember->password);
					}
					else
					{
						list($strPassword, $strSalt) = explode(':', $objMember->password);
						$blnAuthenticated = ($strSalt == '') ? ($strPassword === sha1($objWidget->value)) : ($strPassword === sha1($strSalt . $objWidget->value));
					}

					if (!$blnAuthenticated)
					{
						$objWidget->value = '';
						$objWidget->addError($GLOBALS['TL_LANG']['MSC']['oldPasswordWrong']);
						sleep(2); // Wait 2 seconds while brute forcing :)
					}
				}

				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}
			}

			$strFields .= $objWidget->parse();
		}

		$this->Template->fields = $strFields;
		$this->Template->hasError = $doNotSubmit;

		// Store the new password
		if (\Input::post('FORM_SUBMIT') == 'tl_change_password' && !$doNotSubmit)
        {
			$objMember->tstamp = time();
			$objMember->password = $objNewPassword->value;
			$objMember->save();

			// HOOK: set new password callback
			if (isset($GLOBALS['TL_HOOKS']['setNewPassword']) && is_array($GLOBALS['TL_HOOKS']['setNewPassword']))
			{
				foreach ($GLOBALS['TL_HOOKS']['setNewPassword'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($objMember, $objNewPassword->value, $this);
				}
			}

			// Check whether there is a jumpTo page
			if (($objJumpTo = $this->objModel->getRelated('jumpTo')) !== null)
			{
				$this->jumpToOrReload($objJumpTo->row());
			}

			$this->reload();
		}

		$this->Template->action = \Environment::get('indexFreeRequest');
		$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['changePassword']);
		$this->Template->rowLast = 'row_' . $row . ' row_last' . ((($row % 2) == 0) ? ' even' : ' odd');
		$this->Template->tableless = $this->tableless;
		$this->Template->message = \Message::generate(false, true);
	}
}
