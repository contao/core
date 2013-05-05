<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


/**
 * Authenticates and initializes user objects
 *
 * The class supports user authentication, login and logout, persisting the
 * session data and initializing the user object from a database row. It
 * functions as abstract parent class for the "BackendUser" and "FrontendUser"
 * classes of the core.
 *
 * Usage:
 *
 *     $user = BackendUser::getInstance();
 *
 *     if ($user->findBy('username', 'leo'))
 *     {
 *         echo $user->name;
 *     }
 *
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
abstract class User extends \System
{

	/**
	 * Object instance (Singleton)
	 * @var \User
	 */
	protected static $objInstance;

	/**
	 * User ID
	 * @var integer
	 */
	protected $intId;

	/**
	 * IP address
	 * @var string
	 */
	protected $strIp;

	/**
	 * Authentication hash
	 * @var string
	 */
	protected $strHash;

	/**
	 * Table
	 * @var string
	 */
	protected $strTable;

	/**
	 * Cookie name
	 * @var string
	 */
	protected $strCookie;

	/**
	 * Data
	 * @var array
	 */
	protected $arrData = array();


	/**
	 * Import the database object
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final public function __clone() {}


	/**
	 * Set an object property
	 *
	 * @param string $strKey   The property name
	 * @param mixed  $varValue The property value
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}


	/**
	 * Return an object property
	 *
	 * @param string $strKey The property name
	 *
	 * @return mixed The property value
	 */
	public function __get($strKey)
	{
		if (isset($this->arrData[$strKey]))
		{
			return $this->arrData[$strKey];
		}

		return parent::__get($strKey);
	}


	/**
	 * Check whether a property is set
	 *
	 * @param string $strKey The property name
	 *
	 * @return boolean True if the property is set
	 */
	public function __isset($strKey)
	{
		return isset($this->arrData[$strKey]);
	}


	/**
	 * Instantiate a new user object (Factory)
	 *
	 * @return \User The object instance
	 */
	public static function getInstance()
	{
		if (static::$objInstance === null)
		{
			static::$objInstance = new static();
		}

		return static::$objInstance;
	}


	/**
	 * Authenticate a user
	 *
	 * @return boolean True if the user could be authenticated
	 */
	public function authenticate()
	{
		// Check the cookie hash
		if ($this->strHash != sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? $this->strIp : '') . $this->strCookie))
		{
			return false;
		}

		$objSession = $this->Database->prepare("SELECT * FROM tl_session WHERE hash=? AND name=?")
									 ->execute($this->strHash, $this->strCookie);

		// Try to find the session in the database
		if ($objSession->numRows < 1)
		{
			$this->log('Could not find the session record', get_class($this) . ' authenticate()', TL_ACCESS);
			return false;
		}

		$time = time();

		// Validate the session
		if ($objSession->sessionID != session_id() || (!$GLOBALS['TL_CONFIG']['disableIpCheck'] && $objSession->ip != $this->strIp) || $objSession->hash != $this->strHash || ($objSession->tstamp + $GLOBALS['TL_CONFIG']['sessionTimeout']) < $time)
		{
			$this->log('Could not verify the session', get_class($this) . ' authenticate()', TL_ACCESS);
			return false;
		}

		$this->intId = $objSession->pid;

		// Load the user object
		if ($this->findBy('id', $this->intId) == false)
		{
			$this->log('Could not find the session user', get_class($this) . ' authenticate()', TL_ACCESS);
			return false;
		}

		$this->setUserFromDb();

		// Update session
		$this->Database->prepare("UPDATE tl_session SET tstamp=$time WHERE sessionID=?")
					   ->execute(session_id());

		$this->setCookie($this->strCookie, $this->strHash, ($time + $GLOBALS['TL_CONFIG']['sessionTimeout']), null, null, false, true);
		return true;
	}


	/**
	 * Try to login the current user
	 *
	 * @return boolean True if the user could be logged in
	 */
	public function login()
	{
		\System::loadLanguageFile('default');

		// Do not continue if username or password are missing
		if (!\Input::post('username', true) || !\Input::post('password', true))
		{
			return false;
		}

		// Load the user object
		if ($this->findBy('username', \Input::post('username', true)) == false)
		{
			$blnLoaded = false;

			// HOOK: pass credentials to callback functions
			if (isset($GLOBALS['TL_HOOKS']['importUser']) && is_array($GLOBALS['TL_HOOKS']['importUser']))
			{
				foreach ($GLOBALS['TL_HOOKS']['importUser'] as $callback)
				{
					$this->import($callback[0], 'objImport', true);
					$blnLoaded = $this->objImport->$callback[1](\Input::post('username', true), \Input::post('password', true), $this->strTable);

					// Load successfull
					if ($blnLoaded === true)
					{
						break;
					}
				}
			}

			// Return if the user still cannot be loaded
			if (!$blnLoaded || $this->findBy('username', \Input::post('username', true)) == false)
			{
				\Message::addError($GLOBALS['TL_LANG']['ERR']['invalidLogin']);
				$this->log('Could not find user "' . \Input::post('username', true) . '"', get_class($this) . ' login()', TL_ACCESS);

				return false;
			}
		}

		$time = time();

		// Set the user language
		if (\Input::post('language'))
		{
			$this->language = \Input::post('language');
		}

		// Lock the account if there are too many login attempts
		if ($this->loginCount < 1)
		{
			$this->locked = $time;
			$this->loginCount = $GLOBALS['TL_CONFIG']['loginCount'];
			$this->save();

			// Add a log entry and the error message, because checkAccountStatus() will not be called (see #4444)
			$this->log('The account has been locked for security reasons', get_class($this) . ' login()', TL_ACCESS);
			\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['accountLocked'], ceil((($this->locked + $GLOBALS['TL_CONFIG']['lockPeriod']) - $time) / 60)));

			// Send admin notification
			if ($GLOBALS['TL_CONFIG']['adminEmail'] != '')
			{
				$objEmail = new \Email();
				$objEmail->subject = $GLOBALS['TL_LANG']['MSC']['lockedAccount'][0];
				$objEmail->text = sprintf($GLOBALS['TL_LANG']['MSC']['lockedAccount'][1], $this->username, ((TL_MODE == 'FE') ? $this->firstname . " " . $this->lastname : $this->name), \Environment::get('base'), ceil($GLOBALS['TL_CONFIG']['lockPeriod'] / 60));
				$objEmail->sendTo($GLOBALS['TL_CONFIG']['adminEmail']);
			}

			return false;
		}

		// Check the account status
		if ($this->checkAccountStatus() == false)
		{
			return false;
		}

		// The password has been generated with crypt()
		if (\Encryption::test($this->password))
		{
			$blnAuthenticated = (crypt(\Input::post('password', true), $this->password) == $this->password);
		}
		else
		{
			list($strPassword, $strSalt) = explode(':', $this->password);
			$blnAuthenticated = ($strSalt == '') ? ($strPassword == sha1(\Input::post('password', true))) : ($strPassword == sha1($strSalt . \Input::post('password', true)));

			// Store a SHA-512 encrpyted version of the password
			if ($blnAuthenticated)
			{
				$this->password = \Encryption::hash(\Input::post('password', true));
			}
		}

		// HOOK: pass credentials to callback functions
		if (!$blnAuthenticated && isset($GLOBALS['TL_HOOKS']['checkCredentials']) && is_array($GLOBALS['TL_HOOKS']['checkCredentials']))
		{
			foreach ($GLOBALS['TL_HOOKS']['checkCredentials'] as $callback)
			{
				$this->import($callback[0], 'objAuth', true);
				$blnAuthenticated = $this->objAuth->$callback[1](\Input::post('username', true), \Input::post('password', true), $this);

				// Authentication successfull
				if ($blnAuthenticated === true)
				{
					break;
				}
			}
		}

		// Redirect if the user could not be authenticated
		if (!$blnAuthenticated)
		{
			--$this->loginCount;
			$this->save();

			\Message::addError($GLOBALS['TL_LANG']['ERR']['invalidLogin']);
			$this->log('Invalid password submitted for username "' . $this->username . '"', get_class($this) . ' login()', TL_ACCESS);

			return false;
		}

		$this->setUserFromDb();

		// Update the record
		$this->lastLogin = $this->currentLogin;
		$this->currentLogin = $time;
		$this->loginCount = $GLOBALS['TL_CONFIG']['loginCount'];
		$this->save();

		// Generate the session
		$this->generateSession();
		$this->log('User "' . $this->username . '" has logged in', get_class($this) . ' login()', TL_ACCESS);

		// HOOK: post login callback
		if (isset($GLOBALS['TL_HOOKS']['postLogin']) && is_array($GLOBALS['TL_HOOKS']['postLogin']))
		{
			foreach ($GLOBALS['TL_HOOKS']['postLogin'] as $callback)
			{
				$this->import($callback[0], 'objLogin', true);
				$this->objLogin->$callback[1]($this);
			}
		}

		return true;
	}


	/**
	 * Check the account status and return true if it is active
	 *
	 * @return boolean True if the account is active
	 */
	protected function checkAccountStatus()
	{
		$time = time();

		// Check whether the account is locked
		if (($this->locked + $GLOBALS['TL_CONFIG']['lockPeriod']) > $time)
		{
			\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['accountLocked'], ceil((($this->locked + $GLOBALS['TL_CONFIG']['lockPeriod']) - $time) / 60)));
			return false;
		}

		// Check whether the account is disabled
		elseif ($this->disable)
		{
			\Message::addError($GLOBALS['TL_LANG']['ERR']['invalidLogin']);
			$this->log('The account has been disabled', get_class($this) . ' login()', TL_ACCESS);
			return false;
		}

		// Check wether login is allowed (front end only)
		elseif ($this instanceof \FrontendUser && !$this->login)
		{
			\Message::addError($GLOBALS['TL_LANG']['ERR']['invalidLogin']);
			$this->log('User "' . $this->username . '" is not allowed to log in', get_class($this) . ' login()', TL_ACCESS);
			return false;
		}

		// Check whether account is not active yet or anymore
		elseif ($this->start != '' || $this->stop != '')
		{
			if ($this->start != '' && $this->start > $time)
			{
				\Message::addError($GLOBALS['TL_LANG']['ERR']['invalidLogin']);
				$this->log('The account was not active yet (activation date: ' . \Date::parse($GLOBALS['TL_CONFIG']['dateFormat'], $this->start) . ')', get_class($this) . ' login()', TL_ACCESS);
				return false;
			}

			if ($this->stop != '' && $this->stop < $time)
			{
				\Message::addError($GLOBALS['TL_LANG']['ERR']['invalidLogin']);
				$this->log('The account was not active anymore (deactivation date: ' . \Date::parse($GLOBALS['TL_CONFIG']['dateFormat'], $this->stop) . ')', get_class($this) . ' login()', TL_ACCESS);
				return false;
			}
		}

		return true;
	}


	/**
	 * Find a user in the database
	 *
	 * @param string $strColumn The field name
	 * @param mixed  $varValue  The field value
	 *
	 * @return boolean True if the user was found
	 */
	public function findBy($strColumn, $varValue)
	{
		$objResult = $this->Database->prepare("SELECT * FROM " . $this->strTable . " WHERE " . $strColumn . "=?")
									->limit(1)
									->executeUncached($varValue);

		if ($objResult->numRows > 0)
		{
			$this->arrData = $objResult->row();
			return true;
		}

		return false;
	}


	/**
	 * Update the current record
	 */
	public function save()
	{
		$this->Database->prepare("UPDATE " . $this->strTable . " %s WHERE id=?")
					   ->set($this->arrData)
					   ->execute($this->id);
	}


	/**
	 * Generate a session
	 */
	protected function generateSession()
	{
		$time = time();

		// Generate the cookie hash
		$this->strHash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? $this->strIp : '') . $this->strCookie);

		// Clean up old sessions
		$this->Database->prepare("DELETE FROM tl_session WHERE tstamp<? OR hash=?")
					   ->execute(($time - $GLOBALS['TL_CONFIG']['sessionTimeout']), $this->strHash);

		// Save the session in the database
		$this->Database->prepare("INSERT INTO tl_session (pid, tstamp, name, sessionID, ip, hash) VALUES (?, ?, ?, ?, ?, ?)")
					   ->execute($this->intId, $time, $this->strCookie, session_id(), $this->strIp, $this->strHash);

		// Set the authentication cookie
		$this->setCookie($this->strCookie, $this->strHash, ($time + $GLOBALS['TL_CONFIG']['sessionTimeout']), null, null, false, true);

		// Save the login status
		$_SESSION['TL_USER_LOGGED_IN'] = true;
	}


	/**
	 * Remove the authentication cookie and destroy the current session
	 *
	 * @return boolean True if the user could be logged out
	 */
	public function logout()
	{
		// Return if the user has been logged out already
		if (!\Input::cookie($this->strCookie))
		{
			return false;
		}

		$objSession = $this->Database->prepare("SELECT * FROM tl_session WHERE hash=? AND name=?")
									 ->limit(1)
									 ->execute($this->strHash, $this->strCookie);

		if ($objSession->numRows)
		{
			$this->strIp = $objSession->ip;
			$this->strHash = $objSession->hash;
			$intUserid = $objSession->pid;
		}

		$time = time();

		// Remove the session from the database
		$this->Database->prepare("DELETE FROM tl_session WHERE hash=?")
					   ->execute($this->strHash);

		// Remove cookie and hash
		$this->setCookie($this->strCookie, $this->strHash, ($time - 86400), null, null, false, true);
		$this->strHash = '';

		// Destroy the current session
		session_destroy();
		session_write_close();

		// Reset the session cookie
		$this->setCookie(session_name(), session_id(), ($time - 86400), '/');

		// Remove the login status
		$_SESSION['TL_USER_LOGGED_IN'] = false;

		// Add a log entry
		if ($this->findBy('id', $intUserid) != false)
		{
			$GLOBALS['TL_USERNAME'] = $this->username;
			$this->log('User "' . $this->username . '" has logged out', $this->strTable . ' logout()', TL_ACCESS);
		}

		// HOOK: post logout callback
		if (isset($GLOBALS['TL_HOOKS']['postLogout']) && is_array($GLOBALS['TL_HOOKS']['postLogout']))
		{
			foreach ($GLOBALS['TL_HOOKS']['postLogout'] as $callback)
			{
				$this->import($callback[0], 'objLogout', true);
				$this->objLogout->$callback[1]($this);
			}
		}

		return true;
	}


	/**
	 * Return true if the user is member of a particular group
	 *
	 * @param integer $id The group ID
	 *
	 * @return boolean True if the user is a member of the group
	 */
	public function isMemberOf($id)
	{
		// ID not numeric
		if (!is_numeric($id))
		{
			return false;
		}

		$groups = deserialize($this->arrData['groups']);

		// No groups assigned
		if (!is_array($groups) || empty($groups))
		{
			return false;
		}

		// Group ID found
		if (in_array($id, $groups))
		{
			return true;
		}

		return false;
	}


	/**
	 * Set all user properties from a database record
	 */
	abstract protected function setUserFromDb();
}
