<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    PunBridge
 * @license    LGPL
 * @filesource
 */


/**
 * Class PunBridge
 *
 * Provide methods to handle punBB authentication.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class PunBridge extends Frontend
{

	/**
	 * Login the current user to punBB
	 * @param object
	 */
	public function login($objUser)
	{
		$this->setDatabase();

		$objPunUser = $this->Database->prepare("SELECT id, group_id, password, save_pass FROM " . $GLOBALS['TL_CONFIG']['PUN']['db_prefix'] . "users WHERE username=?")
									 ->limit(1)
									 ->execute($objUser->username);

		// Check account
		if ($objPunUser->numRows < 1)
		{
			$this->restoreDatabase();
			return;
		}

		// Check password
		if ($objPunUser->password != $objUser->password)
		{
			$this->restoreDatabase();
			return;
		}
		
		// Update the status if this is the first time the user logged in
		if ($objPunUser->group_id == 32000)
		{
			$objGroup = $this->Database->prepare("SELECT * FROM " . $GLOBALS['TL_CONFIG']['PUN']['db_prefix'] . "config WHERE conf_name='o_default_user_group'")
									   ->limit(1)
									   ->execute();

			if ($objGroup->numRows)
			{
				$this->Database->prepare("UPDATE " . $GLOBALS['TL_CONFIG']['PUN']['db_prefix'] . "users SET group_id=? WHERE id=?")
							   ->execute($objGroup->conf_value, $objPunUser->id);
			}
		}

		// Remove this users guest entry from the online list
		$this->Database->prepare("DELETE FROM " . $GLOBALS['TL_CONFIG']['PUN']['db_prefix'] . "online WHERE ident=?")
					   ->execute($this->Environment->ip);

		// Set cookie
		$expire = $objPunUser->save_pass ? time() + 31536000 : 0;
		setcookie($GLOBALS['TL_CONFIG']['PUN']['cookie_name'], serialize(array($objPunUser->id, md5($GLOBALS['TL_CONFIG']['PUN']['cookie_seed'] . $objPunUser->password))), $expire, $GLOBALS['TL_CONFIG']['PUN']['cookie_path'], $GLOBALS['TL_CONFIG']['PUN']['cookie_domain'], $GLOBALS['TL_CONFIG']['PUN']['cookie_secure']);

		$this->restoreDatabase();
	}


	/**
	 * Logout the current user from punBB
	 * @param object
	 */
	public function logout($objUser)
	{
		$this->setDatabase();

		$objPunUser = $this->Database->prepare("SELECT id FROM " . $GLOBALS['TL_CONFIG']['PUN']['db_prefix'] . "users WHERE username=?")
									 ->limit(1)
									 ->execute($objUser->username);

		// Check account
		if ($objPunUser->numRows < 1)
		{
			$this->restoreDatabase();
			return;
		}

		// Remove user from "users online" list.
		$this->Database->prepare("DELETE FROM " . $GLOBALS['TL_CONFIG']['PUN']['db_prefix'] . "online WHERE user_id=?")
					   ->execute($objPunUser->id);

		// Update last_visit
		$this->Database->prepare("UPDATE " . $GLOBALS['TL_CONFIG']['PUN']['db_prefix'] . "users SET last_visit=? WHERE id=?")
					   ->execute(time(), $objPunUser->id);

		setcookie($GLOBALS['TL_CONFIG']['PUN']['cookie_name'], serialize(array(1, md5($GLOBALS['TL_CONFIG']['PUN']['cookie_seed'] . uniqid('', true)))), time() + 31536000, $GLOBALS['TL_CONFIG']['PUN']['cookie_path'], $GLOBALS['TL_CONFIG']['PUN']['cookie_domain'], $GLOBALS['TL_CONFIG']['PUN']['cookie_secure']);
		$this->restoreDatabase();
	}


	/**
	 * Create a punBB account as soon as a new TYPOlight account is being activated
	 * @param object
	 */
	public function activate($objUser)
	{
		$this->setDatabase();

		$arrConfig = array();
		$objConfig = $this->Database->execute("SELECT * FROM " . $GLOBALS['TL_CONFIG']['PUN']['db_prefix'] . "config");

		while ($objConfig->next())
		{
			$arrConfig[$objConfig->conf_name] = $objConfig->conf_value;
		}

		// Prepare new user
		$arrSet = array
		(
			'username'        => $objUser->username,
			'group_id'        => $arrConfig['o_default_user_group'],
			'realname'        => trim($objUser->firstname . ' ' . $objUser->lastname),
			'password'        => $objUser->password,
			'email'           => $objUser->email,
			'email_setting'   => 1,
			'save_pass'       => 0,
			'timezone'        => 0,
			'language'        => strlen($objUser->language) ? $objUser->language : $arrConfig['o_default_lang'],
			'style'           => $arrConfig['o_default_style'],
			'registered'      => $objUser->tstamp,
			'registration_ip' => $this->Environment->ip,
			'last_visit'      => time()
		);

		// Create new user
		$this->Database->prepare("INSERT INTO " . $GLOBALS['TL_CONFIG']['PUN']['db_prefix'] . "users %s")
					   ->set($arrSet)
					   ->execute();

		$this->restoreDatabase();
	}


	/**
	 * Update the password of an account
	 * @param object
	 * @param string
	 */
	public function password($objUser, $strPassword)
	{
		$this->setDatabase();

		$objPunUser = $this->Database->prepare("SELECT id FROM " . $GLOBALS['TL_CONFIG']['PUN']['db_prefix'] . "users WHERE username=?")
									 ->limit(1)
									 ->execute($objUser->username);

		// Check account
		if ($objPunUser->numRows < 1)
		{
			$this->restoreDatabase();
			return;
		}

		$this->Database->prepare("UPDATE " . $GLOBALS['TL_CONFIG']['PUN']['db_prefix'] . "users SET password=? WHERE id=?")
					   ->execute($strPassword, $objPunUser->id);

		$this->restoreDatabase();
	}


	/**
	 * Create a new punBB account
	 * @param object
	 */
	public function synchronize(DataContainer $dc)
	{
		$objUser = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")
								  ->limit(1)
								  ->execute($dc->id);

		if ($objUser->numRows < 1)
			return;

		$this->setDatabase();

		$objPunUser = $this->Database->prepare("SELECT id FROM " . $GLOBALS['TL_CONFIG']['PUN']['db_prefix'] . "users WHERE username=?")
									 ->limit(1)
									 ->execute($objUser->username);

		if ($objPunUser->numRows < 1)
		{
			$objUser->tstamp = time();
			$this->activate($objUser);
		}

		$this->restoreDatabase();
	}


	/**
	 * Connect to the punBB database
	 * @throws Exception
	 */
	private function setDatabase()
	{
		if (!$this->Database->setDatabase($GLOBALS['TL_CONFIG']['PUN']['db_name']))
		{
			throw new Exception('Unable to connect to punBB database');
		}
	}


	/**
	 * Connect to the TYPOlight database
	 * @throws Exception
	 */
	private function restoreDatabase()
	{
		if (!$this->Database->setDatabase($GLOBALS['TL_CONFIG']['dbDatabase']))
		{
			throw new Exception('Unable to reconnect to TYPOlight database');
		}
	}
}

?>