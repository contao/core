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
 * Class FrontendUser
 *
 * Provide methods to manage front end users.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class FrontendUser extends \User
{

	/**
	 * Current object instance (do not remove)
	 * @var FrontendUser
	 */
	protected static $objInstance;

	/**
	 * Name of the corresponding table
	 * @var string
	 */
	protected $strTable = 'tl_member';

	/**
	 * Name of the current cookie
	 * @var string
	 */
	protected $strCookie = 'FE_USER_AUTH';

	/**
	 * Path to the login script
	 * @var string
	 */
	protected $strLoginScript = 'index.php';

	/**
	 * Path to the protected file
	 * @var string
	 */
	protected $strRedirect = 'index.php';

	/**
	 * Group login page
	 * @var string
	 */
	protected $strLoginPage;

	/**
	 * Groups
	 * @var array
	 */
	protected $arrGroups;


	/**
	 * Initialize the object
	 */
	protected function __construct()
	{
		parent::__construct();

		$this->strIp = \Environment::get('ip');
		$this->strHash = \Input::cookie($this->strCookie);
	}


	/**
	 * Set the current referer and save the session
	 */
	public function __destruct()
	{
		$session = $this->Session->getData();

		if (!isset($_GET['pdf']) && !isset($_GET['file']) && !isset($_GET['id']) && $session['referer']['current'] != \Environment::get('requestUri'))
		{
			$session['referer']['last'] = $session['referer']['current'];
			$session['referer']['current'] = \Environment::get('requestUri');
		}

		$this->Session->setData($session);

		if ($this->intId)
		{
			$this->Database->prepare("UPDATE " . $this->strTable . " SET session=? WHERE id=?")->execute(serialize($session), $this->intId);
		}
	}


	/**
	 * Extend parent setter class and modify some parameters
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'allGroups':
				$this->arrGroups = $varValue;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Extend parent getter class and modify some parameters
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'allGroups':
				return $this->arrGroups;
				break;

			case 'loginPage':
				return $this->strLoginPage;
				break;
		}

		return parent::__get($strKey);
	}


	/**
	 * Authenticate a user
	 * @return boolean
	 */
	public function authenticate()
	{
		// Default authentication
		if (parent::authenticate())
		{
			return true;
		}

		// Check whether auto login is active
		if ($GLOBALS['TL_CONFIG']['autologin'] < 1 || \Input::cookie('FE_AUTO_LOGIN') == '')
		{
			return false;
		}

		// Try to find the user by his auto login cookie
		if ($this->findBy('autologin', \Input::cookie('FE_AUTO_LOGIN')) == false)
		{
			return false;
		}

		// Check the auto login period
		if ($this->createdOn < (time() - $GLOBALS['TL_CONFIG']['autologin']))
		{
			return false;
		}

		// Validate the account status
		if ($this->checkAccountStatus() == false)
		{
			return false;
		}

		$this->setUserFromDb();

		// Last login date
		$this->lastLogin = $this->currentLogin;
		$this->currentLogin = time();
		$this->save();

		// Generate the session
		$this->generateSession();
		$this->log('User "' . $this->username . '" was logged in automatically', get_class($this) . ' authenticate()', TL_ACCESS);

		// Reload the page
		$this->reload();
		return true;
	}


	/**
	 * Add the auto login resources
	 * @return boolean
	 */
	public function login()
	{
		// Default routine
		if (parent::login() == false)
		{
			return false;
		}

		// Set the auto login data
		if ($GLOBALS['TL_CONFIG']['autologin'] > 0 && \Input::post('autologin'))
		{
			$time = time();
			$strToken = md5(uniqid(mt_rand(), true));

			$this->createdOn = $time;
			$this->autologin = $strToken;
			$this->save();

			$this->setCookie('FE_AUTO_LOGIN', $strToken, ($time + $GLOBALS['TL_CONFIG']['autologin']), null, null, false, true);
		}

		return true;
	}


	/**
	 * Remove the auto login resources
	 * @return boolean
	 */
	public function logout()
	{
		// Default routine
		if (parent::logout() == false)
		{
			return false;
		}

		// Reset the auto login data
		if ($this->blnRecordExists)
		{
			$this->autologin = null;
			$this->createdOn = 0;
			$this->save();
		}

		// Remove the auto login cookie
		$this->setCookie('FE_AUTO_LOGIN', $this->autologin, (time() - 86400), null, null, false, true);
		return true;
	}


	/**
	 * Save the original group membership
	 * @param string
	 * @param integer
	 * @return boolean
	 */
	public function findBy($strColumn, $varValue)
	{
		if (parent::findBy($strColumn, $varValue) === false)
		{
			return false;
		}

		$this->arrGroups = $this->groups;
		return true;
	}


	/**
	 * Restore the original group membership
	 */
	public function save()
	{
		$groups = $this->groups;
		$this->arrData['groups'] = $this->arrGroups;
		parent::save();
		$this->groups = $groups;
	}


	/**
	 * Set all user properties from a database record
	 */
	protected function setUserFromDb()
	{
		$this->intId = $this->id;

		// Unserialize values
		foreach ($this->arrData as $k=>$v)
		{
			if (!is_numeric($v))
			{
				$this->$k = deserialize($v);
			}
		}

		// Set language
		if ($this->language != '')
		{
			$GLOBALS['TL_LANGUAGE'] = $this->language;
		}

		$GLOBALS['TL_USERNAME'] = $this->username;

		// Make sure that groups is an array
		if (!is_array($this->groups))
		{
			$this->groups = ($this->groups != '') ? array($this->groups) : array();
		}

		// Skip inactive groups
		if (($objGroups = \MemberGroupModel::findAllActive()) !== null)
		{
			$this->groups = array_intersect($this->groups, $objGroups->fetchEach('id'));
		}

		// Get the group login page
		if ($this->groups[0] > 0)
		{
			$objGroup = \MemberGroupModel::findPublishedById($this->groups[0]);

			if ($objGroup !== null && $objGroup->redirect && $objGroup->jumpTo)
			{
				$this->strLoginPage = $objGroup->jumpTo;
			}
		}

		// Restore session
		if (is_array($this->session))
		{
			$this->Session->setData($this->session);
		}
		else
		{
			$this->session = array();
		}
	}
}
