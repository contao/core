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
 * Class FrontendUser
 *
 * Provide methods to manage front end users.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Model
 */
class FrontendUser extends User
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

		$this->strIp = $this->Environment->ip;
		$this->strHash = $this->Input->cookie($this->strCookie);
	}


	/**
	 * Set the current referer and save the session
	 */
	public function __destruct()
	{
		$session = $this->Session->getData();

		if (!isset($_GET['pdf']) && !isset($_GET['file']) && !isset($_GET['id']) && $session['referer']['current'] != $this->Environment->requestUri)
		{
			$session['referer']['last'] = $session['referer']['current'];
			$session['referer']['current'] = $this->Environment->requestUri;
		}

		$this->Session->setData($session);

		if (strlen($this->intId))
		{
			$this->Database->prepare("UPDATE " . $this->strTable . " SET session=? WHERE id=?")
						   ->execute(serialize($session), $this->intId);
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

			default:
				return parent::__get($strKey);
				break;
		}
	}


	/**
	 * Return the current object instance (Singleton)
	 * @return FrontendUser
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new self();
		}

		return self::$objInstance;
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
		if ($GLOBALS['TL_CONFIG']['autologin'] < 1 || $this->Input->cookie('FE_AUTO_LOGIN') == '')
		{
			return false;
		}

		// Try to find the user by his auto login cookie
		if ($this->findBy('autologin', $this->Input->cookie('FE_AUTO_LOGIN')) == false)
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
		if ($GLOBALS['TL_CONFIG']['autologin'] > 0 && $this->Input->post('autologin'))
		{
			$time = time();
			$strToken = md5(uniqid(mt_rand(), true));

			$this->createdOn = $time;
			$this->autologin = $strToken;
			$this->save();

			$this->setCookie('FE_AUTO_LOGIN', $strToken, ($time + $GLOBALS['TL_CONFIG']['autologin']), $GLOBALS['TL_CONFIG']['websitePath']);
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
		$this->setCookie('FE_AUTO_LOGIN', $this->autologin, (time() - 86400), $GLOBALS['TL_CONFIG']['websitePath']);
		return true;
	}


	/**
	 * Save the original group membership
	 * @param string
	 * @param integer
	 * @return boolean
	 */
	public function findBy($strRefField, $varRefId)
	{
		if (parent::findBy($strRefField, $varRefId) === false)
		{
			return false;
		}

		$this->arrGroups = $this->groups;
		return true;
	}


	/**
	 * Restore the original group membership
	 * @param boolean
	 * @return integer
	 */
	public function save($blnForceInsert=false)
	{
		$groups = $this->groups;
		$this->arrData['groups'] = $this->arrGroups;
		$return = parent::save($blnForceInsert);
		$this->groups = $groups;

		return $return;
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
			$this->groups = strlen($this->groups) ? array($this->groups) : array();
		}

		$time = time();

		// Skip inactive groups
		$objGroups = $this->Database->execute("SELECT id FROM tl_member_group WHERE disable!=1 AND (start='' OR start<$time) AND (stop='' OR stop>$time)");
		$this->groups = array_intersect($this->groups, $objGroups->fetchEach('id'));

		// Get group login page
		if ($this->groups[0] > 0)
		{
			$objGroup = $this->Database->prepare("SELECT * FROM tl_member_group WHERE id=? AND disable!=1 AND (start='' OR start<$time) AND (stop='' OR stop>$time)")
									   ->limit(1)
									   ->execute($this->groups[0]);

			if ($objGroup->numRows && $objGroup->redirect && $objGroup->jumpTo)
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

?>