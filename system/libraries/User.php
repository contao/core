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
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class User
 *
 * Provide methods to manage users.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Model
 */
abstract class User extends Model
{

	/**
	 * Current object instance (Singleton)
	 * @var User
	 */
	protected static $objInstance;

	/**
	 * Current user ID
	 * @var integer
	 */
	protected $intId;

	/**
	 * IP address of the current user
	 * @var string
	 */
	protected $strIp;

	/**
	 * Authentication hash value
	 * @var string
	 */
	protected $strHash;

	/**
	 * Table
	 * @var string
	 */
	protected $strTable;

	/**
	 * Name of the current cookie
	 * @var string
	 */
	protected $strCookie;

	/**
	 * Authentication object
	 * @var object
	 */
	protected $objAuth;

	/**
	 * Import object
	 * @var object
	 */
	protected $objImport;

	/**
	 * Login object
	 * @var object
	 */
	protected $objLogin;

	/**
	 * Logout object
	 * @var object
	 */
	protected $objLogout;


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}


	/**
	 * Authenticate a user
	 * @return boolean
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

		$this->setCookie($this->strCookie, $this->strHash, ($time + $GLOBALS['TL_CONFIG']['sessionTimeout']), $GLOBALS['TL_CONFIG']['websitePath']);
		return true;
	}


	/**
	 * Try to login the current user
	 * @return boolean
	 */
	public function login()
	{
		$this->loadLanguageFile('default');

		// Do not continue if username or password are missing
		if (!$this->Input->post('username') || !$this->Input->post('password'))
		{
			return false;
		}

		// Load the user object
		if ($this->findBy('username', $this->Input->post('username')) == false)
		{
			$blnLoaded = false;

			// HOOK: pass credentials to callback functions
			if (isset($GLOBALS['TL_HOOKS']['importUser']) && is_array($GLOBALS['TL_HOOKS']['importUser']))
			{
				foreach ($GLOBALS['TL_HOOKS']['importUser'] as $callback)
				{
					$this->import($callback[0], 'objImport', true);
					$blnLoaded = $this->objImport->$callback[1]($this->Input->post('username'), $this->Input->post('password'), $this->strTable);

					// Load successfull
					if ($blnLoaded === true)
					{
						break;
					}
				}
			}

			// Return if the user still cannot be loaded
			if (!$blnLoaded || $this->findBy('username', $this->Input->post('username')) == false)
			{
				$this->addErrorMessage($GLOBALS['TL_LANG']['ERR']['invalidLogin']);
				$this->log('Could not find user "' . $this->Input->post('username') . '"', get_class($this) . ' login()', TL_ACCESS);

				return false;
			}
		}

		$time = time();

		// Set the user language
		if ($this->Input->post('language'))
		{
			$this->language = $this->Input->post('language');
		}

		// Lock the account if there are too many login attempts
		if ($this->loginCount < 1)
		{
			$this->locked = $time;
			$this->loginCount = 3;
			$this->save();

			// Add a log entry and the error message, because checkAccountStatus() will not be called (see #4444)
			$this->log('The account has been locked for security reasons', get_class($this) . ' login()', TL_ACCESS);
			$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['accountLocked'], ceil((($this->locked + $GLOBALS['TL_CONFIG']['lockPeriod']) - $time) / 60)));

			// Send admin notification
			if ($GLOBALS['TL_CONFIG']['adminEmail'] != '')
			{
				$objEmail = new Email();
				$objEmail->subject = 'A Contao account has been locked!';
				$objEmail->text = "The following Contao account has been locked for security reasons.\n\nUsername: " . $this->username . "\nReal name: " . ((TL_MODE == 'FE') ? $this->firstname . " " . $this->lastname : $this->name) . "\nWebsite: " . $this->Environment->base . "\n\nThe account has been locked for " . ceil($GLOBALS['TL_CONFIG']['lockPeriod'] / 60) . " minutes because a user has entered an invalid password three times in a row. After this period of time the account will be unlocked automatically.\n\nThis e-mail has been generated by Contao. You can not reply to it directly.\n";
				$objEmail->sendTo($GLOBALS['TL_CONFIG']['adminEmail']);
			}

			return false;
		}

		// Check the account status
		if ($this->checkAccountStatus() == false)
		{
			return false;
		}

		$blnAuthenticated = false;
		list($strPassword, $strSalt) = explode(':', $this->password);

		// Password is correct but not yet salted
		if (!strlen($strSalt) && $strPassword == sha1($this->Input->post('password')))
		{
			$strSalt = substr(md5(uniqid(mt_rand(), true)), 0, 23);
			$strPassword = sha1($strSalt . $this->Input->post('password'));
			$this->password = $strPassword . ':' . $strSalt;
		}

		// Check the password against the database
		if (strlen($strSalt) && $strPassword == sha1($strSalt . $this->Input->post('password')))
		{
			$blnAuthenticated = true;
		}

		// HOOK: pass credentials to callback functions
		elseif (isset($GLOBALS['TL_HOOKS']['checkCredentials']) && is_array($GLOBALS['TL_HOOKS']['checkCredentials']))
		{
			foreach ($GLOBALS['TL_HOOKS']['checkCredentials'] as $callback)
			{
				$this->import($callback[0], 'objAuth', true);
				$blnAuthenticated = $this->objAuth->$callback[1]($this->Input->post('username'), $this->Input->post('password'), $this);

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

			$this->addErrorMessage($GLOBALS['TL_LANG']['ERR']['invalidLogin']);
			$this->log('Invalid password submitted for username "' . $this->username . '"', get_class($this) . ' login()', TL_ACCESS);

			return false;
		}

		$this->setUserFromDb();

		// Update the record
		$this->loginCount = 3;
		$this->lastLogin = $this->currentLogin;
		$this->currentLogin = $time;
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
	 * @return boolean
	 */
	protected function checkAccountStatus()
	{
		$time = time();

		// Check whether the account is locked
		if (($this->locked + $GLOBALS['TL_CONFIG']['lockPeriod']) > $time)
		{
			$this->addErrorMessage(sprintf($GLOBALS['TL_LANG']['ERR']['accountLocked'], ceil((($this->locked + $GLOBALS['TL_CONFIG']['lockPeriod']) - $time) / 60)));
			return false;
		}

		// Check whether the account is disabled
		elseif ($this->disable)
		{
			$this->addErrorMessage($GLOBALS['TL_LANG']['ERR']['invalidLogin']);
			$this->log('The account has been disabled', get_class($this) . ' login()', TL_ACCESS);
			return false;
		}

		// Check wether login is allowed (front end only)
		elseif ($this instanceof FrontendUser && !$this->login)
		{
			$this->addErrorMessage($GLOBALS['TL_LANG']['ERR']['invalidLogin']);
			$this->log('User "' . $this->username . '" is not allowed to log in', get_class($this) . ' login()', TL_ACCESS);
			return false;
		}

		// Check whether account is not active yet or anymore
		elseif ($this->start != '' || $this->stop != '')
		{
			if ($this->start != '' && $this->start > $time)
			{
				$this->addErrorMessage($GLOBALS['TL_LANG']['ERR']['invalidLogin']);
				$this->log('The account was not active yet (activation date: ' . $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $this->start) . ')', get_class($this) . ' login()', TL_ACCESS);
				return false;
			}

			if ($this->stop != '' && $this->stop < $time)
			{
				$this->addErrorMessage($GLOBALS['TL_LANG']['ERR']['invalidLogin']);
				$this->log('The account was not active anymore (deactivation date: ' . $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $this->stop) . ')', get_class($this) . ' login()', TL_ACCESS);
				return false;
			}
		}

		return true;
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
		$this->setCookie($this->strCookie, $this->strHash, ($time + $GLOBALS['TL_CONFIG']['sessionTimeout']), $GLOBALS['TL_CONFIG']['websitePath']);

		// Save the login status
		$_SESSION['TL_USER_LOGGED_IN'] = true;
	}


	/**
	 * Remove the authentication cookie and destroy the current session
	 * @return boolean
	 */
	public function logout()
	{
		// Return if the user has been logged out already
		if (!$this->Input->cookie($this->strCookie))
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
		$this->setCookie($this->strCookie, $this->strHash, ($time - 86400), $GLOBALS['TL_CONFIG']['websitePath']);
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
	 * @param integer
	 * @return boolean
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

?>