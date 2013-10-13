<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
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
 * Class ModuleLogin
 *
 * Front end module "login".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ModuleLogin extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_login_1cl';


	protected function proceedLogin()
	{
		$objMember = \MemberModel::findByUsername(\Input::post('username'));

		$strRedirect = \Environment::get('request');

		// Redirect to the last page visited
		if ($this->redirectBack && $_SESSION['LAST_PAGE_VISITED'] != '')
		{
			$strRedirect = $_SESSION['LAST_PAGE_VISITED'];
		}
		else
		{
			// Redirect to the jumpTo page
			if ($this->jumpTo && ($objTarget = $this->objModel->getRelated('jumpTo')) !== null)
			{
				$strRedirect = $this->generateFrontendUrl($objTarget->row());
			}

			// Overwrite the jumpTo page with an individual group setting
			if ($objMember !== null)
			{
				$arrGroups = deserialize($objMember->groups);

				if (!empty($arrGroups) && is_array($arrGroups))
				{
					$objGroupPage = \MemberGroupModel::findFirstActiveWithJumpToByIds($arrGroups);

					if ($objGroupPage !== null)
					{
						$strRedirect = $this->generateFrontendUrl($objGroupPage->row());
					}
				}
			}
		}

		// Auto login is not allowed
		if (isset($_POST['autologin']) && !$this->autologin)
		{
			unset($_POST['autologin']);
			\Input::setPost('autologin', null);
		}

		// Login and redirect
		$this->import('FrontendUser', 'User');
		if ($this->User->login())
		{
			if ($this->User->pwChange)
			{
				\Session::getInstance()->set('PASSWORD_CHANGE_REQUIRED', true);
				$this->reload();
			}
			$this->redirect($strRedirect);
		}

		$this->reload();
	}


	/**
	 * Change the password if it is new
	 *
	 * @param integer $minAged min seconds to reuse a old password
	 *
	 * @return boolean
	 */
	protected function changePassword($minAged = 10368000 /* 24*60*60*120 = 120 days */)
	{
		$strPlainPassword = \Input::post($this->formPassword->name);
		$this->import('FrontendUser', 'User');
		$theMember = \MemberModel::findByUsername($this->User->username);

		if (\User::checkCryptPassword($theMember->password, $strPlainPassword))
		{
			return false;
		}

		$arrOldPasswords = deserialize($theMember->oldPasswords, true);
		foreach ($arrOldPasswords as $password)
		{
			if (\User::checkCryptPassword($password['value'], $strPlainPassword))
			{
				if ($password['tstamp'] > time()-$minAged)
				{
					return false;
				}
			}
		}

		$arrOldPasswords[] = array('value' => $theMember->password, 'tstamp' => time());
		$theMember->oldPasswords = serialize($arrOldPasswords);
		$theMember->password = $this->formPassword->value;
		$theMember->pwChange = '';
		$theMember->save();

		return true;
	}


	/**
	 * Display a login form
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['login'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Set the last page visited
		if ($this->redirectBack)
		{
			$_SESSION['LAST_PAGE_VISITED'] = $this->getReferer();
		}

		$this->formPassword = new \FormPassword(array(
				'name'      => 'password',
				'label'     => &$GLOBALS['TL_LANG']['MSC']['pw_change'],
				'mandatory' => true
		) );

		// Login
		if (\Input::post('FORM_SUBMIT') == 'tl_login')
		{
			// Check whether username and password are set
			if (!\Input::post('username', true) || !\Input::post('password', true))
			{
				$_SESSION['LOGIN_ERROR'] = $GLOBALS['TL_LANG']['MSC']['emptyField'];
				$this->reload();
			}

			$this->proceedLogin();
		}

		if (\Input::post('FORM_SUBMIT') == 'tl_pwchange')
		{
			if (\Input::post('tl_logout'))
			{
				\Input::setPost('FORM_SUBMIT', 'tl_logout');
			}
			else
			{
				// Check password
				$this->validate = $this->formPassword->validate();
				if ($this->formPassword->hasErrors())
				{
					$_SESSION['LOGIN_ERROR'] = $this->formPassword->getErrorAsString(0);
					\Session::getInstance()->set('PASSWORD_CHANGE_REQUIRED', true);
					$this->reload();
				}
				if (!$this->changePassword())
				{
					$this->loadLanguageFile('tl_module');
					$_SESSION['LOGIN_ERROR']	= specialchars($GLOBALS['TL_LANG']['tl_module']['old_password_forbidden']);
					\Session::getInstance()->set('PASSWORD_CHANGE_REQUIRED', true);
					$this->reload();
				}

				$this->import('FrontendUser', 'User');
				\Input::setPost('username', $this->User->username);
				$this->proceedLogin();
			}
		}

		// Logout and redirect to the website root if the current page is protected
		if (\Input::post('FORM_SUBMIT') == 'tl_logout')
		{
			global $objPage;

			$this->import('FrontendUser', 'User');
			$strRedirect = \Environment::get('request');

			// Redirect to last page visited
			if ($this->redirectBack && strlen($_SESSION['LAST_PAGE_VISITED']))
			{
				$strRedirect = $_SESSION['LAST_PAGE_VISITED'];
			}

			// Redirect home if the page is protected
			elseif ($objPage->protected)
			{
				$strRedirect = \Environment::get('base');
			}

			// Logout and redirect
			$this->import('FrontendUser', 'User');
			if ($this->User->logout())
			{
				$this->redirect($strRedirect);
			}

			$this->reload();
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		// set Template and may import User
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			if ($this->User->pwChange)
			{
				// Show password change form
				\Session::getInstance()->set('PASSWORD_CHANGE_REQUIRED', true);
				$this->strTemplate = ($this->cols > 1) ? 'mod_pwchange_2cl' : 'mod_pwchange_1cl';
			}
			else
			{
				// Show logout form
				$this->strTemplate = ($this->cols > 1) ? 'mod_logout_2cl' : 'mod_logout_1cl';
			}
		}
		else
		{
			// Show login form
			$this->strTemplate = ($this->cols > 1) ? 'mod_login_2cl' : 'mod_login_1cl';
		}

		$this->Template = new \FrontendTemplate($this->strTemplate);
		$this->Template->setData($this->arrData);

		$blnHasError = false;

		if (!empty($_SESSION['TL_ERROR']))
		{
			$blnHasError = true;
			$_SESSION['LOGIN_ERROR'] = $_SESSION['TL_ERROR'][0];
			$_SESSION['TL_ERROR'] = array();
		}

		if (isset($_SESSION['LOGIN_ERROR']))
		{
			$blnHasError = true;
			$this->Template->message = $_SESSION['LOGIN_ERROR'];
			unset($_SESSION['LOGIN_ERROR']);
		}

		$this->Template->hasError = $blnHasError;
		$this->Template->username = $GLOBALS['TL_LANG']['MSC']['username'];
		$this->Template->password = $GLOBALS['TL_LANG']['MSC']['password'][0];
		$this->Template->action = ampersand(\Environment::get('indexFreeRequest'));
		$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['login']);
		$this->Template->pwclabel = specialchars($GLOBALS['TL_LANG']['MSC']['setNewPassword']);
		$this->Template->llabel = specialchars($GLOBALS['TL_LANG']['MSC']['logout']);
		$this->Template->value = specialchars(\Input::post('username'));
		$this->Template->autologin = ($this->autologin && $GLOBALS['TL_CONFIG']['autologin'] > 0);
		$this->Template->autoLabel = $GLOBALS['TL_LANG']['MSC']['autologin'];

		if (FE_USER_LOGGED_IN)
		{
				$this->Template->loggedInAs = sprintf($GLOBALS['TL_LANG']['MSC']['loggedInAs'], $this->User->username);

			if ($this->User->pwChange)
			{
				$this->Template->formPassword = $this->formPassword->parse();
				$this->loadLanguageFile('tl_module');
				$this->Template->info = specialchars($GLOBALS['TL_LANG']['tl_module']['password_change_info']);
				$this->Template->hasError = $blnHasError;
				$this->Template->password = $GLOBALS['TL_LANG']['MSC']['password'][0];
				$this->Template->password_confirm = "\$GLOBALS['TL_LANG']['MSC']['password_confirm'][0]";
			}
			else {
				$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['logout']);
				$this->Template->loggedInAs = sprintf($GLOBALS['TL_LANG']['MSC']['loggedInAs'], $this->User->username);
				$this->Template->action = ampersand(\Environment::get('indexFreeRequest'));
			}

			if ($this->User->lastLogin > 0)
			{
				global $objPage;
				$this->Template->lastLogin = sprintf($GLOBALS['TL_LANG']['MSC']['lastLogin'][1], \Date::parse($objPage->datimFormat, $this->User->lastLogin));
			}

			return;
		}
	}
}
