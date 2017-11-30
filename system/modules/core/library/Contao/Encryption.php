<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Encrypts and decrypts data
 *
 * The class can be used to encrypt and decrypt data based on the encryption
 * string that is set during the Contao installation.
 *
 * Usage:
 *
 *     $encrypted = Encryption::encrypt('Leo Feyer');
 *     $decrypted = Encryption::decrypt($encrypted);
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Encryption
{

	/**
	 * Object instance (Singleton)
	 * @var \Encryption
	 */
	protected static $objInstance;

	/**
	 * Mcrypt resource
	 * @var resource
	 */
	protected static $resTd;


	/**
	 * Encrypt a value
	 *
	 * @param mixed  $varValue The value to encrypt
	 * @param string $strKey   An optional encryption key
	 *
	 * @return string The encrypted value
	 *
	 * @deprecated Deprecated since Contao 3.5, to be removed in Contao 5.
	 *             Use a third-party library such as OpenSSL or phpseclib instead.
	 */
	public static function encrypt($varValue, $strKey=null)
	{
		@trigger_error('Using Encryption::encrypt() has been deprecated and will no longer work in Contao 5.0. Use a third-party library such as OpenSSL or phpseclib instead.', E_USER_DEPRECATED);

		// Recursively encrypt arrays
		if (is_array($varValue))
		{
			foreach ($varValue as $k=>$v)
			{
				$varValue[$k] = static::encrypt($v);
			}

			return $varValue;
		}
		elseif ($varValue == '')
		{
			return '';
		}

		// Initialize the module
		if (static::$resTd === null)
		{
			static::initialize();
		}

		if (!$strKey)
		{
			$strKey = \Config::get('encryptionKey');
		}

		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size(static::$resTd));
		mcrypt_generic_init(static::$resTd, md5($strKey), $iv);
		$strEncrypted = mcrypt_generic(static::$resTd, $varValue);
		$strEncrypted = base64_encode($iv.$strEncrypted);
		mcrypt_generic_deinit(static::$resTd);

		return $strEncrypted;
	}


	/**
	 * Decrypt a value
	 *
	 * @param mixed  $varValue The value to decrypt
	 * @param string $strKey   An optional encryption key
	 *
	 * @return string The decrypted value
	 *
	 * @deprecated Deprecated since Contao 3.5, to be removed in Contao 5.
	 *             Use a third-party library such as OpenSSL or phpseclib instead.
	 */
	public static function decrypt($varValue, $strKey=null)
	{
		@trigger_error('Using Encryption::decrypt() has been deprecated and will no longer work in Contao 5.0. Use a third-party library such as OpenSSL or phpseclib instead.', E_USER_DEPRECATED);

		// Recursively decrypt arrays
		if (is_array($varValue))
		{
			foreach ($varValue as $k=>$v)
			{
				$varValue[$k] = static::decrypt($v);
			}

			return $varValue;
		}
		elseif ($varValue == '')
		{
			return '';
		}

		// Initialize the module
		if (static::$resTd === null)
		{
			static::initialize();
		}

		$varValue = base64_decode($varValue);
		$ivsize = mcrypt_enc_get_iv_size(static::$resTd);
		$iv = substr($varValue, 0, $ivsize);
		$varValue = substr($varValue, $ivsize);

		if ($varValue == '')
		{
			return '';
		}

		if (!$strKey)
		{
			$strKey = \Config::get('encryptionKey');
		}

		mcrypt_generic_init(static::$resTd, md5($strKey), $iv);
		$strDecrypted = mdecrypt_generic(static::$resTd, $varValue);
		mcrypt_generic_deinit(static::$resTd);

		return $strDecrypted;
	}


	/**
	 * Initialize the encryption module
	 *
	 * @throws \Exception If the encryption module cannot be initialized
	 */
	protected static function initialize()
	{
		if (!in_array('mcrypt', get_loaded_extensions()))
		{
			throw new \Exception('The PHP mcrypt extension is not installed');
		}

		if ((self::$resTd = mcrypt_module_open(\Config::get('encryptionCipher'), '', \Config::get('encryptionMode'), '')) == false)
		{
			throw new \Exception('Error initializing encryption module');
		}

		if (\Config::get('encryptionKey') == '')
		{
			throw new \Exception('Encryption key not set');
		}
	}


	/**
	 * Generate a password hash
	 *
	 * @param string $strPassword The unencrypted password
	 *
	 * @return string The encrypted password
	 *
	 * @throws \Exception If none of the algorithms is available
	 */
	public static function hash($strPassword)
	{
		@trigger_error('Using Encryption::hash() has been deprecated and will no longer work in Contao 5.0. Use password_hash() instead.', E_USER_DEPRECATED);

		return password_hash($strPassword, PASSWORD_DEFAULT);
	}


	/**
	 * Test whether a password hash has been generated with a password API compatible
	 * algorithm.
	 *
	 * @param string $strHash The password hash
	 *
	 * @return boolean True if compatible
	 */
	public static function test($strHash)
	{
		// We had SHA1, SHA256 and SHA512 before switching to password API compatible
		// algorithms (first crypt() and later password_*()) so if it is was not one
		// of the SHA algorithms, it is compatible.
		return !(\in_array(\strlen($strHash), [40, 64, 128], true) && ctype_xdigit($strHash));
	}


	/**
	 * Verify a readable password against a password hash
	 *
	 * @param string $strPassword The readable password
	 * @param string $strHash     The password hash
	 *
	 * @return boolean True if the password could be verified
	 *
	 * @see https://github.com/ircmaxell/password_compat
	 */
	public static function verify($strPassword, $strHash)
	{
		@trigger_error('Using Encryption::verify() has been deprecated and will no longer work in Contao 5.0. Use password_verify() instead.', E_USER_DEPRECATED);

		return password_verify($strPassword, $strHash);
	}


	/**
	 * Initialize the encryption module
	 *
	 * @deprecated Encryption is now a static class
	 */
	protected function __construct()
	{
		static::initialize();
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 *
	 * @deprecated Encryption is now a static class
	 */
	final public function __clone() {}


	/**
	 * Return the object instance (Singleton)
	 *
	 * @return \Encryption
	 *
	 * @deprecated Encryption is now a static class
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
