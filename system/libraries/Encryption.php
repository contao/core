<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Encryption
 *
 * Provide methods to encrypt and decrypt data.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
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
	protected $resTd;


	/**
	 * Initialize the encryption module
	 * @throws Exception
	 */
	protected function __construct()
	{
		if (!in_array('mcrypt', get_loaded_extensions()))
		{
			throw new Exception('The PHP mcrypt extension is not installed');
		}

		if (($this->resTd = mcrypt_module_open($GLOBALS['TL_CONFIG']['encryptionCipher'], '', $GLOBALS['TL_CONFIG']['encryptionMode'], '')) == false)
		{
			throw new Exception('Error initializing encryption module');
		}

		if (!strlen($GLOBALS['TL_CONFIG']['encryptionKey']))
		{
			throw new Exception('Encryption key not set');
		}
	}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}


	/**
	 * Return the current object instance (Singleton)
	 * @return Encryption
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new self();
		}

		return self::$objInstance;
	}


	/**
	 * Encrypt a value
	 * @param mixed
	 * @param string
	 * @return string
	 */
	public function encrypt($varValue, $strKey=null)
	{
		// Recursively encrypt arrays
		if (is_array($varValue))
		{
			foreach ($varValue as $k=>$v)
			{
				$varValue[$k] = $this->encrypt($v);
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

		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($this->resTd), MCRYPT_RAND);
		mcrypt_generic_init($this->resTd, md5($strKey), $iv);
		$strEncrypted = mcrypt_generic($this->resTd, $varValue);
		$strEncrypted = base64_encode($iv.$strEncrypted);
		mcrypt_generic_deinit($this->resTd);

		return $strEncrypted;
	}


	/**
	 * Decrypt a value
	 * @param mixed
	 * @param string
	 * @return string
	 */
	public function decrypt($varValue, $strKey=null)
	{
		// Recursively decrypt arrays
		if (is_array($varValue))
		{
			foreach ($varValue as $k=>$v)
			{
				$varValue[$k] = $this->decrypt($v);
			}

			return $varValue;
		}

		if ($varValue == '')
		{
			return '';
		}

		$varValue = base64_decode($varValue);
		$ivsize = mcrypt_enc_get_iv_size($this->resTd);
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

		mcrypt_generic_init($this->resTd, md5($strKey), $iv);
		$strDecrypted = mdecrypt_generic($this->resTd, $varValue);
		mcrypt_generic_deinit($this->resTd);

		return $strDecrypted;
	}
}

?>