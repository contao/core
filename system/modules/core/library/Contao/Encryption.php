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
	 */
	public static function encrypt($varValue, $strKey=null)
	{
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

		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size(static::$resTd), MCRYPT_RAND);
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
	 */
	public static function decrypt($varValue, $strKey=null)
	{
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
		$intCost = \Config::get('bcryptCost') ?: 10;

		if ($intCost < 4 || $intCost > 31)
		{
			throw new \Exception("The bcrypt cost has to be between 4 and 31, $intCost given");
		}

		if (function_exists('password_hash'))
		{
			return password_hash($strPassword, PASSWORD_BCRYPT, array('cost'=>$intCost));
		}
		elseif (CRYPT_BLOWFISH == 1)
		{
			return crypt($strPassword, '$2y$' . sprintf('%02d', $intCost) . '$' . md5(uniqid(mt_rand(), true)) . '$');
		}
		elseif (CRYPT_SHA512 == 1)
		{
			return crypt($strPassword, '$6$' . md5(uniqid(mt_rand(), true)) . '$');
		}
		elseif (CRYPT_SHA256 == 1)
		{
			return crypt($strPassword, '$5$' . md5(uniqid(mt_rand(), true)) . '$');
		}

		throw new \Exception('None of the required crypt() algorithms is available');
	}


	/**
	 * Test whether a password hash has been generated with crypt()
	 *
	 * @param string $strHash The password hash
	 *
	 * @return boolean True if the password hash has been generated with crypt()
	 */
	public static function test($strHash)
	{
		if (strncmp($strHash, '$2y$', 4) === 0)
		{
			return true;
		}
		elseif (strncmp($strHash, '$2a$', 4) === 0)
		{
			return true;
		}
		elseif (strncmp($strHash, '$6$', 3) === 0)
		{
			return true;
		}
		elseif (strncmp($strHash, '$5$', 3) === 0)
		{
			return true;
		}

		return false;
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
		if (function_exists('password_verify'))
		{
			return password_verify($strPassword, $strHash);
		}

		$getLength = function($str) {
			return extension_loaded('mbstring') ? mb_strlen($str, '8bit') : strlen($str);
		};

		$newHash = crypt($strPassword, $strHash);

		if (!is_string($newHash) || $getLength($newHash) != $getLength($strHash) || $getLength($newHash) <= 13)
		{
			return false;
		}

		$intStatus = 0;

		for ($i=0; $i<$getLength($newHash); $i++)
		{
			$intStatus |= (ord($newHash[$i]) ^ ord($strHash[$i]));
		}

		return $intStatus === 0;
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
