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
 * @package    Registration
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModulePassword
 *
 * Front end module "lost password".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class ModulePassword extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_password';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### LOST PASSWORD ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;
		$GLOBALS['TL_LANGUAGE'] = $objPage->language;

		$this->loadLanguageFile('tl_member');
		$this->loadDataContainer('tl_member');

		// Set new password
		if (strlen($this->Input->get('token')))
		{
			$this->setNewPassword();
			return;
		}

		// Username widget
		if (!$this->reg_skipName)
		{
			$arrFields['username'] = $GLOBALS['TL_DCA']['tl_member']['fields']['username'];
			$arrFields['username']['name'] = 'username';
		}

		// E-mail widget
		$arrFields['email'] = $GLOBALS['TL_DCA']['tl_member']['fields']['email'];
		$arrFields['email']['name'] = 'email';

		// Captcha widget
		if (!$this->disableCaptcha)
		{
			$arrFields['captcha'] = array
			(
				'name' => 'lost_password',
				'label' => $GLOBALS['TL_LANG']['MSC']['securityQuestion'],
				'inputType' => 'captcha',
				'eval' => array('mandatory'=>true)
			);
		}

		$row = 0;
		$strFields = '';
		$doNotSubmit = false;

		// Initialize widgets
		foreach ($arrFields as $arrField)
		{
			$strClass = $GLOBALS['TL_FFL'][$arrField['inputType']];

			// Continue if the class is not defined
			if (!$this->classFileExists($strClass))
			{
				continue;
			}

			$arrField['eval']['tableless'] = $this->tableless;
			$arrField['eval']['required'] = $arrField['eval']['mandatory'];

			$objWidget = new $strClass($this->prepareForWidget($arrField, $arrField['name']));
			$objWidget->storeValues = true;
			$objWidget->rowClass = 'row_'.$row . (($row == 0) ? ' row_first' : '') . ((($row % 2) == 0) ? ' even' : ' odd');
			++$row;

			// Validate widget
			if ($this->Input->post('FORM_SUBMIT') == 'tl_lost_password')
			{
				$objWidget->validate();

				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}
			}

			$strFields .= $objWidget->parse();
		}

		$this->Template->fields = $strFields;
		$this->Template->hasError = $doNotSubmit;

		// Look for an account and send password link
		if ($this->Input->post('FORM_SUBMIT') == 'tl_lost_password' && !$doNotSubmit)
		{
			$time = time();

			$objMember = $this->Database->prepare("SELECT * FROM tl_member WHERE email=? AND login=1 AND disable!=1 AND (start='' OR start<$time) AND (stop='' OR stop>$time)" . (!$this->reg_skipName ? " AND username=?" : ""))
										->limit(1)
										->execute($this->Input->post('email', true), $this->Input->post('username'));

			if ($objMember->numRows < 1)
			{
				$this->strTemplate = 'mod_message';

				$this->Template = new FrontendTemplate($this->strTemplate);
				$this->Template->type = 'error';
				$this->Template->message = $GLOBALS['TL_LANG']['MSC']['accountNotFound'];

				return;
			}

			$this->sendPasswordLink($objMember);
		}

		$this->Template->formId = 'tl_lost_password';
		$this->Template->username = specialchars($GLOBALS['TL_LANG']['MSC']['username']);
		$this->Template->email = specialchars($GLOBALS['TL_LANG']['MSC']['emailAddress']);
		$this->Template->action = $this->getIndexFreeRequest();
		$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['requestPassword']);
		$this->Template->rowLast = 'row_' . count($arrFields) . ' row_last' . ((($row % 2) == 0) ? ' even' : ' odd');
		$this->Template->tableless = $this->tableless;
	}


	/**
	 * Set the new password
	 */
	protected function setNewPassword()
	{
		$objMember = $this->Database->prepare("SELECT id, username FROM tl_member WHERE login=1 AND activation=?")
									->limit(1)
									->execute($this->Input->get('token'));

		if ($objMember->numRows < 1)
		{
			$this->strTemplate = 'mod_message';

			$this->Template = new FrontendTemplate($this->strTemplate);
			$this->Template->type = 'error';
			$this->Template->message = $GLOBALS['TL_LANG']['MSC']['accountError'];

			return;
		}

		// Define the form field
		$arrField = $GLOBALS['TL_DCA']['tl_member']['fields']['password'];
		$arrField['eval']['tableless'] = $this->tableless;

		$strClass = $GLOBALS['TL_FFL']['password'];

		// Fallback to default if the class is not defined
		if (!$this->classFileExists($strClass))
		{
			$strClass = 'FormPassword';
		}

		$objWidget = new $strClass($this->prepareForWidget($arrField, 'password'));

		// Set row classes
		$objWidget->rowClass = 'row_0 row_first even';
		$objWidget->rowClassConfirm = 'row_1 odd';
		$this->Template->rowLast = 'row_2 row_last even';

		// Validate the field
		if (strlen($this->Input->post('FORM_SUBMIT')) && $this->Input->post('FORM_SUBMIT') == $this->Session->get('setPasswordToken'))
		{
			$objWidget->validate();

			// Set the new password and redirect
			if (!$objWidget->hasErrors())
			{
				$this->Session->set('setPasswordToken', '');
				array_pop($_SESSION['TL_CONFIRM']);

				$this->Database->prepare("UPDATE tl_member SET password=?, activation='' WHERE id=?")
							   ->execute($objWidget->value, $objMember->id);

				// HOOK: set new password callback
				if (isset($GLOBALS['TL_HOOKS']['setNewPassword']) && is_array($GLOBALS['TL_HOOKS']['setNewPassword']))
				{
					foreach ($GLOBALS['TL_HOOKS']['setNewPassword'] as $callback)
					{
						$this->import($callback[0]);
						$this->$callback[0]->$callback[1]($objMember, $objWidget->value, $this);
					}
				}

				// Redirect
				if (strlen($this->reg_jumpTo))
				{
					$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
												  ->limit(1)
												  ->execute($this->reg_jumpTo);

					if ($objNextPage->numRows)
					{
						$this->redirect($this->generateFrontendUrl($objNextPage->fetchAssoc()));
					}
				}

				// Confirm
				$this->strTemplate = 'mod_message';

				$this->Template = new FrontendTemplate($this->strTemplate);
				$this->Template->type = 'confirm';
				$this->Template->message = $GLOBALS['TL_LANG']['MSC']['newPasswordSet'];

				return;
			}
		}

		$strToken = md5(uniqid(mt_rand(), true));
		$this->Session->set('setPasswordToken', $strToken);

		$this->Template->formId = $strToken;
		$this->Template->fields = $objWidget->parse();
		$this->Template->action = $this->getIndexFreeRequest();
		$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['setNewPassword']);
		$this->Template->tableless = $this->tableless;
	}


	/**
	 * Create a new user and redirect
	 * @param Database_Result
	 */
	protected function sendPasswordLink(Database_Result $objMember)
	{
		$arrChunks = array();
		$confirmationId = md5(uniqid(mt_rand(), true));

		// Store confirmation ID
		$this->Database->prepare("UPDATE tl_member SET activation=? WHERE id=?")
					   ->execute($confirmationId, $objMember->id);

		$strConfirmation = $this->reg_password;
		preg_match_all('/##[^#]+##/i', $strConfirmation, $arrChunks);

		foreach ($arrChunks[0] as $strChunk)
		{
			$strKey = substr($strChunk, 2, -2);

			switch ($strKey)
			{
				case 'domain':
					$strConfirmation = str_replace($strChunk, $this->Environment->host, $strConfirmation);
					break;

				case 'link':
					$strConfirmation = str_replace($strChunk, $this->Environment->base . $this->Environment->request . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($this->Environment->request, '?') !== false) ? '&' : '?') . 'token=' . $confirmationId, $strConfirmation);
					break;

				default:
					try
					{
						$strConfirmation = str_replace($strChunk, $objMember->$strKey, $strConfirmation);
					}
					catch (Exception $e)
					{
						$strConfirmation = str_replace($strChunk, '', $strConfirmation);
						$this->log('Invalid wildcard "' . $strKey . '" used in password request e-mail', 'ModulePassword sendPasswordLink()', TL_GENERAL, $e->getMessage());
					}
					break;
			}
		}

		// Send e-mail
		$objEmail = new Email();

		$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
		$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['passwordSubject'], $this->Environment->host);
		$objEmail->text = $strConfirmation;
		$objEmail->sendTo($objMember->email);

		$this->log('A new password has been requested for user ID ' . $objMember->id . ' (' . $objMember->email . ')', 'ModulePassword sendPasswordLink()', TL_ACCESS);
		$this->jumpToOrReload($this->jumpTo);
	}
}

?>