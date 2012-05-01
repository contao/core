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
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \System;


/**
 * Class RequestToken
 *
 * This class provides methods to set and validate request tokens.
 * @copyright  Leo Feyer 2011-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class RequestToken extends System
{

	/**
	 * Current object instance (Singleton)
	 * @var RequestToken
	 */
	protected static $objInstance;

	/**
	 * Token
	 * @var string
	 */
	protected static $strToken;


	/**
	 * Load the token or generate a new one
	 */
	protected function __construct()
	{
		static::setup();
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final public function __clone() {}


	/**
	 * Return the current object instance (Singleton)
	 * @return \RequestToken
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
	 * Read the token from the session or generate a new one
	 */
	public static function initialize()
	{
		static::$strToken = @$_SESSION['REQUEST_TOKEN'];

		// Backwards compatibility
		if (is_array(static::$strToken))
		{
			static::$strToken = null;
			unset($_SESSION['REQUEST_TOKEN']);
		}

		// Generate a new token
		if (static::$strToken == '')
		{
			static::$strToken = md5(uniqid(mt_rand(), true));
			$_SESSION['REQUEST_TOKEN'] = static::$strToken;
		}

		// Set the REQUEST_TOKEN constant
		if (!defined('REQUEST_TOKEN'))
		{
			define('REQUEST_TOKEN', static::$strToken);
		}
	}


	/**
	 * Return the token
	 * @return string
	 */
	public static function get()
	{
		return static::$strToken;
	}


	/**
	 * Validate a token
	 * @param string
	 * @return boolean
	 */
	public static function validate($strToken)
	{
		return ($strToken != '' && static::$strToken != '' && $strToken == static::$strToken);
	}
}
