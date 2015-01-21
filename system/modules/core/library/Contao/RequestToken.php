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
 * Generates and validates request tokens
 *
 * The class tries to read and validate the request token from the user session
 * and creates a new token if there is none.
 *
 * Usage:
 *
 *     echo RequestToken::get();
 *
 *     if (!RequestToken::validate('TOKEN'))
 *     {
 *         throw new Exception("Invalid request token");
 *     }
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class RequestToken
{

	/**
	 * Object instance (Singleton)
	 * @var \RequestToken
	 */
	protected static $objInstance;

	/**
	 * Token
	 * @var string
	 */
	protected static $strToken;


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
	 *
	 * @return string The request token
	 */
	public static function get()
	{
		return static::$strToken;
	}


	/**
	 * Validate a token
	 *
	 * @param string $strToken The request token
	 *
	 * @return boolean True if the token matches the stored one
	 */
	public static function validate($strToken)
	{
		// The feature has been disabled
		if (\Config::get('disableRefererCheck') || defined('BYPASS_TOKEN_CHECK'))
		{
			return true;
		}

		// Validate the token
		if ($strToken != '' && static::$strToken != '' && $strToken == static::$strToken)
		{
			return true;
		}

		// Check against the whitelist (thanks to Tristan Lins) (see #3164)
		if (\Config::get('requestTokenWhitelist'))
		{
			$strHostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

			foreach (\Config::get('requestTokenWhitelist') as $strDomain)
			{
				if ($strDomain == $strHostname || preg_match('/\.' . preg_quote($strDomain, '/') . '$/', $strHostname))
				{
					return true;
				}
			}
		}

		return false;
	}


	/**
	 * Load the token or generate a new one
	 *
	 * @deprecated RequestToken is now a static class
	 */
	protected function __construct()
	{
		static::initialize();
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 *
	 * @deprecated RequestToken is now a static class
	 */
	final public function __clone() {}


	/**
	 * Return the object instance (Singleton)
	 *
	 * @return \RequestToken The object instance
	 *
	 * @deprecated RequestToken is now a static class
	 */
	public static function getInstance()
	{
		if (static::$objInstance === null)
		{
			static::$objInstance = new static();
		}

		return static::$objInstance;
	}
}
