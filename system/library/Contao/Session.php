<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Library
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;

use \Exception;


/**
 * Class Session
 *
 * Provide methods to set/get session data.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Session
{

	/**
	 * Current object instance (Singleton)
	 * @var Session
	 */
	protected static $objInstance;

	/**
	 * Session array
	 * @var array
	 */
	protected $arrSession;


	/**
	 * Get the session
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
	 * Save the session
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
	final public function __clone() {}


	/**
	 * Return the current object instance (Singleton)
	 * @return \Session
	 */
	public static function getInstance()
	{
		if (!is_object(static::$objInstance))
		{
			static::$objInstance = new static();
		}

		return static::$objInstance;
	}


	/**
	 * Return a particular session parameter
	 * @param string
	 * @return mixed
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
	 * @throws \Exception
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
	 * @throws \Exception
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
