<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Handles reading and updating the session data
 *
 * The class functions as an adapter for the PHP $_SESSION array and separates
 * back end from front end session data.
 *
 * Usage:
 *
 *     $session = Session::getInstance();
 *     $session->set('foo', 'bar');
 *     echo $session->get('foo');
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Session
{

	/**
	 * Object instance (Singleton)
	 * @var \Session
	 */
	protected static $objInstance;

	/**
	 * Session data
	 * @var array
	 */
	protected $arrSession;


	/**
	 * Get the session data
	 */
	protected function __construct()
	{
		switch (TL_MODE)
		{
			case 'BE':
				$this->arrSession = (array) $_SESSION['BE_DATA'];
				break;

			case 'FE':
				$this->arrSession = (array) $_SESSION['FE_DATA'];
				break;

			default:
				$this->arrSession = (array) $_SESSION;
				break;
		}
	}


	/**
	 * Save the session data
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
	 * Return the object instance (Singleton)
	 *
	 * @return \Session The object instance
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
	 * Return a session variable
	 *
	 * @param string $strKey The variable name
	 *
	 * @return mixed The variable value
	 */
	public function get($strKey)
	{
		return $this->arrSession[$strKey];
	}


	/**
	 * Set a session variable
	 *
	 * @param string $strKey   The variable name
	 * @param mixed  $varValue The variable value
	 */
	public function set($strKey, $varValue)
	{
		$this->arrSession[$strKey] = $varValue;
	}


	/**
	 * Remove a session variable
	 *
	 * @param string $strKey The variable name
	 */
	public function remove($strKey)
	{
		unset($this->arrSession[$strKey]);
	}


	/**
	 * Return the session data as array
	 *
	 * @return array The session data
	 */
	public function getData()
	{
		return (array) $this->arrSession;
	}


	/**
	 * Set the session data from an array
	 *
	 * @param array $arrData The session data
	 *
	 * @throws \Exception If $arrData is not an array
	 */
	public function setData($arrData)
	{
		if (!is_array($arrData))
		{
			throw new \Exception('Array required to set session data');
		}

		$this->arrSession = $arrData;
	}


	/**
	 * Append data to the session
	 *
	 * @param mixed $varData The data object or array
	 *
	 * @throws \Exception If $varData is not an array or object
	 */
	public function appendData($varData)
	{
		if (is_object($varData))
		{
			$varData = get_object_vars($varData);
		}

		if (!is_array($varData))
		{
			throw new \Exception('Array or object required to append session data');
		}

		$this->arrSession = array_merge($this->arrSession, $varData);
	}
}
