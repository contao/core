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
 * Encrypt and decrypt data
 * 
 * The class can be used to encrypt and decrypt data based on the encryption
 * string that is set during the Contao installation.
 * 
 * Usage:
 * 
 *     $encrypted = Encryption::encrypt('Leo Feyer');
 *     $decrypted = Encryption::decrypt($encrypted);
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2011-2012
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
		if (static::$resTd === null)
		{
			static::initialize();
		}

		// Recursively encrypt arrays
		if (is_array($varValue))
		{
			foreach ($varValue as $k=>$v)
			{
				$varValue[$k] = static::encrypt($v);
			}

			return $varValue;
		}

		if ($varValue == '')
		{
			return '';
		}

		if (!$strKey)
		{
			$strKey = $GLOBALS['TL_CONFIG']['encryptionKey'];
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

		if ($varValue == '')
		{
			return '';
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
			$strKey = $GLOBALS['TL_CONFIG']['encryptionKey'];
		}

		mcrypt_generic_init(static::$resTd, md5($strKey), $iv);
		$strDecrypted = mdecrypt_generic(static::$resTd, $varValue);
		mcrypt_generic_deinit(static::$resTd);

		return $strDecrypted;
	}


	/**
	 * Initialize the encryption module
	 * 
	 * @throws \Exception
	 */
	protected static function initialize()
	{
		if ((self::$resTd = mcrypt_module_open($GLOBALS['TL_CONFIG']['encryptionCipher'], '', $GLOBALS['TL_CONFIG']['encryptionMode'], '')) == false)
		{
			throw new Exception('Error initializing encryption module');
		}

		if ($GLOBALS['TL_CONFIG']['encryptionKey'] == '')
		{
			throw new Exception('Encryption key not set');
		}
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
		if (!is_object(static::$objInstance))
		{
			static::$objInstance = new static();
		}

		return static::$objInstance;
	}
}
