<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    System
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \System;


/**
 * Class Cache
 *
 * Provide methods to create a central cache object.
 * @copyright  Leo Feyer 2011-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Cache extends System
{

	/**
	 * Current object instance (Singleton)
	 * @var Cache
	 */
	protected static $objInstance;

	/**
	 * Data
	 * @var array
	 */
	protected static $arrData = array();


	/**
	 * Prevent direct instantiation (Singleton)
	 */
	protected function __construct() {}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final public function __clone() {}


	/**
	 * Check whether a variable is set
	 * @param string
	 * @return boolean
	 * @deprecated
	 */
	public function __isset($strKey)
	{
		return static::has($strKey);
	}


	/**
	 * Return a variable
	 * @param string
	 * @return mixed
	 * @deprecated
	 */
	public function __get($strKey)
	{
		if (static::has($strKey))
		{
			return static::get($strKey);
		}

		return parent::__get($strKey);
	}


	/**
	 * Set a variable
	 * @param string
	 * @param mixed
	 * @deprecated
	 */
	public function __set($strKey, $varValue)
	{
		static::set($strKey, $varValue);
	}


	/**
	 * Unset an entry
	 * @param string
	 * @deprecated
	 */
	public function __unset($strKey)
	{
		static::remove($strKey);
	}


	/**
	 * Instantiate a new cache object and return it (Factory)
	 * @return \Cache
	 * @deprecated
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
	 * Check whether a variable is set
	 * @param string
	 * @return boolean
	 */
	public static function has($strKey)
	{
		return isset(static::$arrData[$strKey]);
	}


	/**
	 * Return a variable
	 * @param string
	 * @return mixed
	 */
	public static function get($strKey)
	{
		return static::$arrData[$strKey];
	}


	/**
	 * Set a variable
	 * @param string
	 * @param mixed
	 */
	public static function set($strKey, $varValue)
	{
		static::$arrData[$strKey] = $varValue;
	}


	/**
	 * Remove an entry
	 * @param string
	 */
	public function remove($strKey)
	{
		unset(static::$arrData[$strKey]);
	}
}
