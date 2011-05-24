<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Session
 *
 * Provide methods to set/get session data.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Session
{

	/**
	 * Current object instance (Singleton)
	 * @var object
	 */
	protected static $objInstance;

	/**
	 * Session array
	 * @var array
	 */
	protected $arrSession;


	/**
	 * Get session
	 */
	protected function __construct()
	{
		switch (TL_MODE)
		{
			case 'BE':
				$this->arrSession = $_SESSION['BE_DATA'];
				break;

			case 'FE':
				$this->arrSession = $_SESSION['FE_DATA'];
				break;

			default:
				$this->arrSession = $_SESSION;
				break;
		}
	}


	/**
	 * Save session
	 */
	public function __destruct()
	{
		switch (TL_MODE)
		{
			case 'BE':
				$_SESSION['BE_DATA'] = $this->arrSession;
				break;

			case 'FE':
				$_SESSION['FE_DATA'] = $this->arrSession;
				break;

			default:
				$_SESSION = $this->arrSession;
				break;
		}
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}


	/**
	 * Return the current object instance (Singleton)
	 * @return object
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new Session();
		}

		return self::$objInstance;
	}


	/**
	 * Return a particular session parameter
	 * @param string
	 * @return array
	 */
	public function get($strKey)
	{
		return $this->arrSession[$strKey];
	}


	/**
	 * Set a particular session parameter
	 * @param string
	 * @param mixed
	 */
	public function set($strKey, $varValue)
	{
		$this->arrSession[$strKey] = $varValue;
	}


	/**
	 * Remove a particular session parameter
	 * @param string
	 * @param mixed
	 */
	public function remove($strKey)
	{
		unset($this->arrSession[$strKey]);
	}


	/**
	 * Return the current session array
	 * @return array
	 */
	public function getData()
	{
		return (array) $this->arrSession;
	}


	/**
	 * Set the current session data from an array
	 * @param array
	 * @throws Exception
	 */
	public function setData($arrData)
	{
		if (!is_array($arrData))
		{
			throw new Exception('Array required to set session data');
		}

		$this->arrSession = $arrData;
	}


	/**
	 * Append data to the current session
	 * @param mixed
	 * @throws Exception
	 */
	public function appendData($varData)
	{
		if (is_object($varData))
		{
			$varData = get_object_vars($varData);
		}

		if (!is_array($varData))
		{
			throw new Exception('Array or object required to append session data');
		}

		$this->arrSession = array_merge($this->arrSession, $varData);
	}
}

?>