<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Encryption
 *
 * Provide methods to encrypt and decrypt data.
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Library
 */
class Encryption
{

	/**
	 * Current object instance (Singleton)
	 * @var object
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
	 * @return object
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new Encryption();
		}

		return self::$objInstance;
	}


	/**
	 * Encrypt a value
	 * @param  mixed
	 * @return string
	 */
	public function encrypt($strValue)
	{
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($this->resTd), MCRYPT_RAND);
		mcrypt_generic_init($this->resTd, md5($GLOBALS['TL_CONFIG']['encryptionKey']), $iv);

		$strEncrypted = mcrypt_generic($this->resTd, $strValue);
		$strEncrypted = base64_encode($iv.$strEncrypted);

		mcrypt_generic_deinit($this->resTd);
		return $strEncrypted;
	}


	/**
	 * Decrypt a value
	 * @param  mixed
	 * @return string
	 */
	public function decrypt($strValue)
	{
		$strValue = base64_decode($strValue);

		$ivsize = mcrypt_enc_get_iv_size($this->resTd);
		$iv = substr($strValue, 0, $ivsize);
		$strValue = substr($strValue, $ivsize);

		mcrypt_generic_init($this->resTd, md5($GLOBALS['TL_CONFIG']['encryptionKey']), $iv);
		$strDecrypted = mdecrypt_generic($this->resTd, $strValue);

		mcrypt_generic_deinit($this->resTd);
		return $strDecrypted;
	}
}

?>