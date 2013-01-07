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
 * Class ModuleRegistration
 *
 * Front end module "registration".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ModuleRegistration extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'member_default';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### USER REGISTRATION ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->editable = deserialize($this->editable);

		// Return if there are no editable fields
		if (!is_array($this->editable) || empty($this->editable))
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

		// Activate account
		if (\Input::get('token') != '')
		{
			$this->activateAcount();
			return;
		}

		if ($this->memberTpl != '')
		{
			$this->Template = new \FrontendTemplate($this->memberTpl);
			$this->Template->setData($this->arrData);
		}

		$this->Template->fields = '';
		$this->Template->tableless = $this->tableless;
		$objCaptcha = null;
		$doNotSubmit = false;

		// Captcha
		if (!$this->disableCaptcha)
		{
			$arrCaptcha = array
			(
				'id' => 'registration',
				'label' => $GLOBALS['TL_LANG']['MSC']['securityQuestion'],
				'type' => 'captcha',
				'mandatory' => true,
				'required' => true,
				'tableless' => $this->tableless
			);

			$strClass = $GLOBALS['TL_FFL']['captcha'];

			// Fallback to default if the class is not defined
			if (!class_exists($strClass))
			{
				$strClass = 'FormCaptcha';
			}

			$objCaptcha = new $strClass($arrCaptcha);

			if (\Input::post('FORM_SUBMIT') == 'tl_registration')
			{
				$objCaptcha->validate();

				if ($objCaptcha->hasErrors())
				{
					$doNotSubmit = true;
				}
			}
		}

		$arrUser = array();
		$arrFields = array();
		$hasUpload = false;
		$i = 0;

		// Build form
		foreach ($this->editable as $field)
		{
			$arrData = $GLOBALS['TL_DCA']['tl_member']['fields'][$field];

			// Map checkboxWizard to regular checkbox widget
			if ($arrData['inputType'] == 'checkboxWizard')
			{
				$arrData['inputType'] = 'checkbox';
			}

			$strClass = $GLOBALS['TL_FFL'][$arrData['inputType']];

			// Continue if the class is not defined
			if (!class_exists($strClass))
			{
				continue;
			}

			$arrData['eval']['tableless'] = $this->tableless;
			$arrData['eval']['required'] = $arrData['eval']['mandatory'];

			$objWidget = new $strClass($this->prepareForWidget($arrData, $field, $arrData['default']));
			$objWidget->storeValues = true;
			$objWidget->rowClass = 'row_' . $i . (($i == 0) ? ' row_first' : '') . ((($i % 2) == 0) ? ' even' : ' odd');

			// Increase the row count if its a password field
			if ($objWidget instanceof \FormPassword)
			{
				$objWidget->rowClassConfirm = 'row_' . ++$i . ((($i % 2) == 0) ? ' even' : ' odd');
			}

			// Validate input
			if (\Input::post('FORM_SUBMIT') == 'tl_registration')
			{
				$objWidget->validate();
				$varValue = $objWidget->value;

				// Check whether the password matches the username
				if ($objWidget instanceof \FormPassword && $varValue == \Input::post('username'))
				{
					$objWidget->addError($GLOBALS['TL_LANG']['ERR']['passwordName']);
				}

				$rgxp = $arrData['eval']['rgxp'];

				// Convert date formats into timestamps (check the eval setting first -> #3063)
				if (($rgxp == 'date' || $rgxp == 'time' || $rgxp == 'datim') && $varValue != '')
				{
					// Use the numeric back end format here!
					$objDate = new \Date($varValue, $GLOBALS['TL_CONFIG'][$rgxp.'Format']);
					$varValue = $objDate->tstamp;
				}

				// Make sure that unique fields are unique (check the eval setting first -> #3063)
				if ($arrData['eval']['unique'] && $varValue != '' && !$this->Database->isUniqueValue('tl_member', $field, $varValue))
				{
					$objWidget->addError(sprintf($GLOBALS['TL_LANG']['ERR']['unique'], $arrData['label'][0] ?: $field));
				}

				// Save callback
				if (is_array($arrData['save_callback']))
				{
					foreach ($arrData['save_callback'] as $callback)
					{
						$this->import($callback[0]);

						try
						{
							$varValue = $this->$callback[0]->$callback[1]($varValue, $this->User);
						}
						catch (\Exception $e)
						{
							$objWidget->class = 'error';
							$objWidget->addError($e->getMessage());
						}
					}
				}

				// Store the current value
				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}
				elseif ($objWidget->submitInput())
				{
					$arrUser[$field] = $varValue;
				}
			}

			if ($objWidget instanceof \uploadable)
			{
				$hasUpload = true;
			}

			$temp = $objWidget->parse();

			$this->Template->fields .= $temp;
			$arrFields[$arrData['eval']['feGroup']][$field] .= $temp;

			++$i;
		}

		// Captcha
		if (!$this->disableCaptcha)
		{
			$objCaptcha->rowClass = 'row_'.$i . (($i == 0) ? ' row_first' : '') . ((($i % 2) == 0) ? ' even' : ' odd');
			$strCaptcha = $objCaptcha->parse();

			$this->Template->fields .= $strCaptcha;
			$arrFields['captcha'] .= $strCaptcha;
		}

		$this->Template->rowLast = 'row_' . ++$i . ((($i % 2) == 0) ? ' even' : ' odd');
		$this->Template->enctype = $hasUpload ? 'multipart/form-data' : 'application/x-www-form-urlencoded';
		$this->Template->hasError = $doNotSubmit;

		// Create new user if there are no errors
		if (\Input::post('FORM_SUBMIT') == 'tl_registration' && !$doNotSubmit)
		{
			$this->createNewUser($arrUser);
		}

		$this->Template->loginDetails = $GLOBALS['TL_LANG']['tl_member']['loginDetails'];
		$this->Template->addressDetails = $GLOBALS['TL_LANG']['tl_member']['addressDetails'];
		$this->Template->contactDetails = $GLOBALS['TL_LANG']['tl_member']['contactDetails'];
		$this->Template->personalData = $GLOBALS['TL_LANG']['tl_member']['personalData'];
		$this->Template->captchaDetails = $GLOBALS['TL_LANG']['MSC']['securityQuestion'];

		// Add groups
		foreach ($arrFields as $k=>$v)
		{
			$this->Template->$k = $v;
		}

		$this->Template->captcha = $arrFields['captcha'];
		$this->Template->formId = 'tl_registration';
		$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['register']);
		$this->Template->action = $this->getIndexFreeRequest();

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


	/**
	 * Create a new user and redirect
	 * @param array
	 */
	protected function createNewUser($arrData)
	{
		$arrData['tstamp'] = time();
		$arrData['login'] = $this->reg_allowLogin;
		$arrData['activation'] = md5(uniqid(mt_rand(), true));
		$arrData['dateAdded'] = $arrData['tstamp'];

		// Set default groups
		if (!array_key_exists('groups', $arrData))
		{
			$arrData['groups'] = $this->reg_groups;
		}

		// Disable account
		$arrData['disable'] = 1;

		// Send activation e-mail
		if ($this->reg_activate)
		{
			$arrChunks = array();

			$strConfirmation = $this->reg_text;
			preg_match_all('/##[^#]+##/', $strConfirmation, $arrChunks);

			foreach ($arrChunks[0] as $strChunk)
			{
				$strKey = substr($strChunk, 2, -2);

				switch ($strKey)
				{
					case 'domain':
						$strConfirmation = str_replace($strChunk, \Environment::get('host'), $strConfirmation);
						break;

					case 'link':
						$strConfirmation = str_replace($strChunk, \Environment::get('base') . \Environment::get('request') . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos(\Environment::get('request'), '?') !== false) ? '&' : '?') . 'token=' . $arrData['activation'], $strConfirmation);
						break;

					// HOOK: support newsletter subscriptions
					case 'channel':
					case 'channels':
						if (!in_array('newsletter', $this->Config->getActiveModules()))
						{
							break;
						}

						// Make sure newsletter is an array
						if (!is_array($arrData['newsletter']))
						{
							if ($arrData['newsletter'] != '')
							{
								$arrData['newsletter'] = array($arrData['newsletter']);
							}
							else
							{
								$arrData['newsletter'] = array();
							}
						}

						// Replace the wildcard
						if (!empty($arrData['newsletter']))
						{
							$objChannels = \NewsletterChannelModel::findByIds($arrData['newsletter']);

							if ($objChannels !== null)
							{
								$strConfirmation = str_replace($strChunk, implode("\n", $objChannels->fetchEach('title')), $strConfirmation);
							}
						}
						else
						{
							$strConfirmation = str_replace($strChunk, '', $strConfirmation);
						}
						break;

					default:
						$strConfirmation = str_replace($strChunk, $arrData[$strKey], $strConfirmation);
						break;
				}
			}

			$objEmail = new \Email();

			$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
			$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
			$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['emailSubject'], \Environment::get('host'));
			$objEmail->text = $strConfirmation;
			$objEmail->sendTo($arrData['email']);
		}

		// Make sure newsletter is an array
		if (isset($arrData['newsletter']) && !is_array($arrData['newsletter']))
		{
			$arrData['newsletter'] = array($arrData['newsletter']);
		}

		// Create the user
		$objNewUser = new \MemberModel();
		$objNewUser->setRow($arrData);
		$objNewUser->save();
		$insertId = $objNewUser->id;

		// Assign home directory
		if ($this->reg_assignDir && is_dir(TL_ROOT . '/' . $this->reg_homeDir))
		{
			$this->import('Files');
			$strUserDir = $arrData['username'] ?: 'user_' . $insertId;

			// Add the user ID if the directory exists
			if (is_dir(TL_ROOT . '/' . $this->reg_homeDir . '/' . $strUserDir))
			{
				$strUserDir .= '_' . $insertId;
			}

			new \Folder($this->reg_homeDir . '/' . $strUserDir);

			$objNewUser->assignDir = 1;
			$objNewUser->homeDir = $this->reg_homeDir . '/' . $strUserDir;
			$objNewUser->save();
		}

		// HOOK: send insert ID and user data
		if (isset($GLOBALS['TL_HOOKS']['createNewUser']) && is_array($GLOBALS['TL_HOOKS']['createNewUser']))
		{
			foreach ($GLOBALS['TL_HOOKS']['createNewUser'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($insertId, $arrData, $this);
			}
		}

		// Inform admin if no activation link is sent
		if (!$this->reg_activate)
		{
			$this->sendAdminNotification($insertId, $arrData);
		}

		$this->jumpToOrReload($this->objModel->getRelated('jumpTo')->row());
	}


	/**
	 * Activate an account
	 */
	protected function activateAcount()
	{
		$this->strTemplate = 'mod_message';
		$this->Template = new \FrontendTemplate($this->strTemplate);

		// Check the token
		$objMember = \MemberModel::findByActivation(\Input::get('token'));

		if ($objMember === null)
		{
			$this->Template->type = 'error';
			$this->Template->message = $GLOBALS['TL_LANG']['MSC']['accountError'];
			return;
		}

		// Update the account
		$objMember->disable = '';
		$objMember->activation = '';
		$objMember->save();

		// HOOK: post activation callback
		if (isset($GLOBALS['TL_HOOKS']['activateAccount']) && is_array($GLOBALS['TL_HOOKS']['activateAccount']))
		{
			foreach ($GLOBALS['TL_HOOKS']['activateAccount'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($objMember, $this);
			}
		}

		$arrData = array();

		// Get the editable fields
		foreach ($this->editable as $key)
		{
			$arrData[$key] = $objMember->$key;
		}

		// Add the login details
		$arrData['login'] = $objMember->login;
		$arrData['groups'] = $objMember->groups;
		$arrData['disable'] = '';

		// Log activity
		$this->log('User account ID ' . $objMember->id . ' (' . $objMember->email . ') has been activated', 'ModuleRegistration activateAccount()', TL_ACCESS);

		// Redirect to the jumpTo page
		if (($objTarget = $this->objModel->getRelated('reg_jumpTo')) !== null)
		{
			$this->redirect($this->generateFrontendUrl($objTarget->row()));
		}

		// Confirm activation
		$this->Template->type = 'confirm';
		$this->Template->message = $GLOBALS['TL_LANG']['MSC']['accountActivated'];
	}


	/**
	 * Send an admin notification e-mail
	 * @param integer
	 * @param array
	 */
	protected function sendAdminNotification($intId, $arrData)
	{
		$objEmail = new \Email();

		$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
		$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['adminSubject'], \Environment::get('host'));

		$strData = "\n\n";

		// Add user details
		foreach ($arrData as $k=>$v)
		{
			if ($k == 'password' || $k == 'tstamp' || $k == 'activation')
			{
				continue;
			}

			$v = deserialize($v);

			if ($k == 'dateOfBirth' && strlen($v))
			{
				$v = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $v);
			}

			$strData .= $GLOBALS['TL_LANG']['tl_member'][$k][0] . ': ' . (is_array($v) ? implode(', ', $v) : $v) . "\n";
		}

		$objEmail->text = sprintf($GLOBALS['TL_LANG']['MSC']['adminText'], $intId, $strData . "\n") . "\n";
		$objEmail->sendTo($GLOBALS['TL_ADMIN_EMAIL']);

		$this->log('A new user (ID ' . $intId . ') has registered on the website', 'ModuleRegistration sendAdminNotification()', TL_ACCESS);
	}
}
