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


/**
 * Class Encryption
 *
 * Provide methods to encrypt and decrypt data.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Encryption
{

	/**
	 * Current object instance (Singleton)
	 * @var Encryption
	 */
	protected static $objInstance;

	/**
	 * Mcrypt resource
	 * @var resource
	 */
	protected static $resTd;


	/**
	 * Initialize the encryption module
	 */
	protected function __construct()
	{
		static::initialize();
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 * @return mixed|void
	 */
	final public function __clone() {}


	/**
	 * Return the current object instance (Singleton)
	 * @return \Contao\Encryption
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
	 * Encrypt a value
	 * @param mixed
	 * @param string
	 * @return string
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
	 * @param mixed
	 * @param string
	 * @return string
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
	 * @return void
	 * @throws \Exception
	 */
	protected static function initialize()
	{
		if ((self::$resTd = mcrypt_module_open($GLOBALS['TL_CONFIG']['encryptionCipher'], '', $GLOBALS['TL_CONFIG']['encryptionMode'], '')) == false)
		{
			throw new \Exception('Error initializing encryption module');
		}

		if ($GLOBALS['TL_CONFIG']['encryptionKey'] == '')
		{
			throw new \Exception('Encryption key not set');
		}
	}
}
