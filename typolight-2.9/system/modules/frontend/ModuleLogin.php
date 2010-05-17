<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleLogin
 *
 * Front end module "login".
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Controller
 */
class ModuleLogin extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_login_1cl';


	/**
	 * Display a login form
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### FRONT END LOGIN ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Set last page visited
		if ($this->redirectBack)
		{
			$_SESSION['LAST_PAGE_VISITED'] = $this->getReferer();
		}

		// Login
		if ($this->Input->post('FORM_SUBMIT') == 'tl_login')
		{
			// Check whether username and password are set
			if (!$this->Input->post('username') || !strlen(trim($this->Input->post('password'))))
			{
				$_SESSION['LOGIN_ERROR'] = $GLOBALS['TL_LANG']['MSC']['emptyField'];
				$this->reload();
			}

			$this->import('FrontendUser', 'User');
			$strRedirect = $this->Environment->request;

			// Redirect to last page visited
			if ($this->redirectBack && strlen($_SESSION['LAST_PAGE_VISITED']))
			{
				$strRedirect = $_SESSION['LAST_PAGE_VISITED'];
			}

			else
			{
				// Redirect to jumpTo page
				if (strlen($this->jumpTo))
				{
					$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
												  ->limit(1)
												  ->execute($this->jumpTo);

					if ($objNextPage->numRows)
					{
						$strRedirect = $this->generateFrontendUrl($objNextPage->fetchAssoc());
					}
				}

				// Overwrite jumpTo page with an individual group setting
				$objGroup = $this->Database->prepare("SELECT groups FROM tl_member WHERE username=?")
										   ->limit(1)
										   ->execute($this->Input->post('username'));

				if ($objGroup->numRows)
				{
					$arrGroups = deserialize($objGroup->groups);

					if (is_array($arrGroups) && count($arrGroups) > 0)
					{
						$time = time();

						// Get jumpTo page IDs
						$arrGroupPage = array();
						$objGroupPage = $this->Database->execute("SELECT id, jumpTo FROM tl_member_group WHERE id IN(" . implode(',', array_map('intval', $arrGroups)) . ") AND redirect=1 AND disable!=1 AND (start='' OR start<$time) AND (stop='' OR stop>$time)");

						// Simulate FIND_IN_SET()
						while ($objGroupPage->next())
						{
							$arrGroupPage[$objGroupPage->id] = $objGroupPage->jumpTo;
						}

						foreach ($arrGroups as $gid)
						{
							if (isset($arrGroupPage[$gid]))
							{
								// Get jumpTo page
								$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
															  ->limit(1)
															  ->execute($arrGroupPage[$gid]);

								if ($objNextPage->numRows)
								{
									$strRedirect = $this->generateFrontendUrl($objNextPage->fetchAssoc());
								}

								break;
							}
						}
					}
				}
			}

			// Login and redirect
			if ($this->User->login())
			{
				// HOOK: post login callback
				if (isset($GLOBALS['TL_HOOKS']['postLogin']) && is_array($GLOBALS['TL_HOOKS']['postLogin']))
				{
					foreach ($GLOBALS['TL_HOOKS']['postLogin'] as $callback)
					{
						$this->import($callback[0]);
						$this->$callback[0]->$callback[1]($this->User);
					}
				}

				$this->redirect($strRedirect);
			}

			$this->reload();
		}

		// Logout and redirect to the website root if the current page is protected
		if ($this->Input->post('FORM_SUBMIT') == 'tl_logout')
		{
			global $objPage;

			$this->import('FrontendUser', 'User');
			$strRedirect = $this->Environment->request;

			// Redirect to last page visited
			if ($this->redirectBack && strlen($_SESSION['LAST_PAGE_VISITED']))
			{
				$strRedirect = $_SESSION['LAST_PAGE_VISITED'];
			}

			// Redirect home if the page is protected
			elseif ($objPage->protected)
			{
				$strRedirect = $this->Environment->base;
			}

			// Logout and redirect
			if ($this->User->logout())
			{
				// HOOK: post logout callback
				if (isset($GLOBALS['TL_HOOKS']['postLogout']) && is_array($GLOBALS['TL_HOOKS']['postLogout']))
				{
					foreach ($GLOBALS['TL_HOOKS']['postLogout'] as $callback)
					{
						$this->import($callback[0]);
						$this->$callback[0]->$callback[1]($this->User);
					}
				}

				$this->redirect($strRedirect);
			}

			$this->reload();
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		// Show logout form
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			$this->strTemplate = ($this->cols > 1) ? 'mod_logout_2cl' : 'mod_logout_1cl';

			$this->Template = new FrontendTemplate($this->strTemplate);
			$this->Template->setData($this->arrData);

			$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['logout']);
			$this->Template->loggedInAs = sprintf($GLOBALS['TL_LANG']['MSC']['loggedInAs'], $this->User->username);
			$this->Template->action = ampersand($this->Environment->request, true);

			if ($this->User->lastLogin > 0)
			{
				$this->Template->lastLogin = sprintf($GLOBALS['TL_LANG']['MSC']['lastLogin'][1], $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $this->User->lastLogin));
			}

			return;
		}

		$this->strTemplate = ($this->cols > 1) ? 'mod_login_2cl' : 'mod_login_1cl';

		$this->Template = new FrontendTemplate($this->strTemplate);
		$this->Template->setData($this->arrData);

		$blnHasError = false;

		// Show login form
		if (count($_SESSION['TL_ERROR']))
		{
			$blnHasError = true;
			$_SESSION['LOGIN_ERROR'] = $_SESSION['TL_ERROR'][0];
			$_SESSION['TL_ERROR'] = array();
		}

		if (strlen($_SESSION['LOGIN_ERROR']))
		{
			$blnHasError = true;
			$this->Template->message = $_SESSION['LOGIN_ERROR'];
			$_SESSION['LOGIN_ERROR'] = '';
		}

		$this->Template->username = $GLOBALS['TL_LANG']['MSC']['username'];
		$this->Template->password = $GLOBALS['TL_LANG']['MSC']['password'][0];
		$this->Template->action = ampersand($this->Environment->request, true);
		$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['login']);
		$this->Template->value = specialchars($this->Input->post('username'));
		$this->Template->hasError = $blnHasError;
	}
}

?>