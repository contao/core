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
 * Validates arbitrary data
 *
 * Usage:
 *
 *     if (Validator::isEmail($recipient))
 *     {
 *         $email->sendTo($recipient);
 *     }
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Validator
{

	/**
	 * Numeric characters (including full stop [.] and minus [-])
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is numeric
	 */
	public static function isNumeric($varValue)
	{
		return preg_match('/^-?\d+(\.\d+)?$/', $varValue);
	}


	/**
	 * Natural numbers (nonnegative integers)
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is a natural number
	 */
	public static function isNatural($varValue)
	{
		return preg_match('/^\d+$/', $varValue);
	}


	/**
	 * Alphabetic characters (including full stop [.] minus [-] and space [ ])
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is alphabetic
	 */
	public static function isAlphabetic($varValue)
	{
		if (function_exists('mb_eregi'))
		{
			return mb_eregi('^[[:alpha:] \.-]+$', $varValue);
		}
		else
		{
			return preg_match('/^[\pL .-]+$/u', $varValue);
		}
	}


	/**
	 * Alphanumeric characters (including full stop [.] minus [-], underscore [_] and space [ ])
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is alphanumeric
	 */
	public static function isAlphanumeric($varValue)
	{
		if (function_exists('mb_eregi'))
		{
			return mb_eregi('^[[:alnum:] \._-]+$', $varValue);
		}
		else
		{
			return preg_match('/^[\w .-]+$/u', $varValue);
		}
	}


	/**
	 * Characters that are usually encoded by class Input: #<>()\=
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value does not match the characters
	 */
	public static function isExtendedAlphanumeric($varValue)
	{
		return !preg_match('/[#<>()\\\\=]/', $varValue);
	}


	/**
	 * Valid date formats
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is a valid date format
	 */
	public static function isDate($varValue)
	{
		return preg_match('~^'. \Date::getRegexp(\Date::getNumericDateFormat()) .'$~i', $varValue);
	}


	/**
	 * Valid time formats
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is a valid time format
	 */
	public static function isTime($varValue)
	{
		return preg_match('~^'. \Date::getRegexp(\Date::getNumericTimeFormat()) .'$~i', $varValue);
	}


	/**
	 * Valid date and time formats
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is a valid date and time format
	 */
	public static function isDatim($varValue)
	{
		return preg_match('~^'. \Date::getRegexp(\Date::getNumericDatimFormat()) .'$~i', $varValue);
	}


	/**
	 * Valid e-mail address
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is a valid e-mail address
	 */
	public static function isEmail($varValue)
	{
		/*
		 * The regex below is based on a regex by Michael Rushton adjusted by
		 * Rasmus Lerdorf to to only consider routeable addresses as valid. We
		 * have also added Unicode support for the local part.
		 *
		 * Michael's regex carries this copyright:
		 *
		 * Copyright © Michael Rushton 2009-10
		 * http://squiloople.com/
		 * Feel free to use and redistribute this code. But please keep this copyright notice.
		 *
		 * @see https://github.com/php/php-src/blob/master/ext/filter/logical_filters.c#L601
		 */
		return preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E\pL\pN]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F\pL\pN]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E\pL\pN]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F\pL\pN]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iDu', \Idna::encodeEmail($varValue));
	}


	/**
	 * Valid URL with special characters allowed (see #6402)
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is a valid URL
	 */
	public static function isUrl($varValue)
	{
		if (function_exists('mb_eregi'))
		{
			return mb_eregi('^[[:alnum:]\.\*\+\/\?\$#%:,;\{\}\(\)\[\]@&!=~|_-]+$', \Idna::encodeUrl($varValue));
		}
		else
		{
			return preg_match('/^[\w\/.*+?$#%:,;{}()[\]@&!=~|-]+$/u', \Idna::encodeUrl($varValue));
		}
	}


	/**
	 * Valid alias name
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is a valid alias name
	 */
	public static function isAlias($varValue)
	{
		if (function_exists('mb_eregi'))
		{
			return mb_eregi('^[[:alnum:]\._-]+$', $varValue);
		}
		else
		{
			return preg_match('/^[\w.-]+$/u', $varValue);
		}
	}


	/**
	 * Valid folder alias name
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is a valid folder alias name
	 */
	public static function isFolderAlias($varValue)
	{
		if (function_exists('mb_eregi'))
		{
			return mb_eregi('^[[:alnum:]\/\._-]+$', $varValue);
		}
		else
		{
			return preg_match('/^[\w\/.-]+$/u', $varValue);
		}
	}


	/**
	 * Valid phone number
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is a valid phone number
	 */
	public static function isPhone($varValue)
	{
		return preg_match('/^(\+|\()?(\d+[ \+\(\)\/-]*)+$/', $varValue);
	}


	/**
	 * Valid percentage
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is a valid percentage
	 */
	public static function isPercent($varValue)
	{
		return (is_numeric($varValue) && $varValue >= 0 && $varValue <= 100);
	}


	/**
	 * Valid locale
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is a valid locale
	 */
	public static function isLocale($varValue)
	{
		return preg_match('/^[a-z]{2}(_[A-Z]{2})?$/', $varValue);
	}


	/**
	 * Valid language code
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is a valid language code
	 */
	public static function isLanguage($varValue)
	{
		return preg_match('/^[a-z]{2}(-[A-Z]{2})?$/', $varValue);
	}


	/**
	 * Valid UUID (version 1)
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is a UUID
	 */
	public static function isUuid($varValue)
	{
		return static::isBinaryUuid($varValue) || static::isStringUuid($varValue);
	}


	/**
	 * Valid binary UUID (version 1)
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is a binary UUID
	 *
	 * @author Martin Auswöger <https://github.com/ausi>
	 * @author Tristan Lins <https://github.com/tristanlins>
	 */
	public static function isBinaryUuid($varValue)
	{
		if (strlen($varValue) == 16)
		{
			return ($varValue & hex2bin('000000000000F000C000000000000000')) === hex2bin('00000000000010008000000000000000');
		}

		return false;
	}


	/**
	 * Valid string UUID (version 1)
	 *
	 * @param mixed $varValue The value to be validated
	 *
	 * @return boolean True if the value is a string UUID
	 *
	 * @author Martin Auswöger <https://github.com/ausi>
	 * @author Tristan Lins <https://github.com/tristanlins>
	 */
	public static function isStringUuid($varValue)
	{
		if (strlen($varValue) == 36)
		{
			return preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-1[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$/', $varValue);
		}

		return false;
	}


	/**
	 * Valid Google+ ID or vanity name
	 *
	 * @param mixed $varValue The numeric ID or vanity name
	 *
	 * @return boolean True if the value is a Google+ ID
	 */
	public static function isGooglePlusId($varValue)
	{
		return preg_match('/^([0-9]{21}|\+[\w-]+)$/u', $varValue);
	}


	/**
	 * Insecure path potentially containing directory traversal
	 *
	 * @param string $strPath The file path
	 *
	 * @return boolean True if the file path is insecure
	 */
	public static function isInsecurePath($strPath)
	{
		// Normalize backslashes
		$strPath = str_replace('\\', '/', $strPath);
		$strPath = preg_replace('#//+#', '/', $strPath);

		// Equals ..
		if ($strPath == '..')
		{
			return true;
		}

		// Begins with ./
		if (substr($strPath, 0, 2) == './')
		{
			return true;
		}

		// Begins with ../
		if (substr($strPath, 0, 3) == '../')
		{
			return true;
		}

		// Ends with /.
		if (substr($strPath, -2) == '/.')
		{
			return true;
		}

		// Ends with /..
		if (substr($strPath, -3) == '/..')
		{
			return true;
		}

		// Contains /../
		if (strpos($strPath, '/../') !== false)
		{
			return true;
		}

		return false;
	}


	/**
	 * Valid file name
	 *
	 * @param mixed $strName The file name
	 *
	 * @return boolean True if the file name is valid
	 */
	public static function isValidFileName($strName)
	{
		if ($strName == '')
		{
			return false;
		}

		// Special characters not supported on e.g. Windows
		if (preg_match('@[\\\\/:*?"<>|]@', $strName))
		{
			return false;
		}

		// Invisible control characters or unused code points
		if (preg_match('/[\pC]/u', $strName) !== 0)
		{
			return false;
		}

		// Must not be longer than 255 characters
		if (utf8_strlen($strName) > 255)
		{
			return false;
		}

		return true;
	}


	/**
	 * Valid form field name
	 *
	 * @param mixed $strName The form field name
	 *
	 * @return boolean True if the form field name is valid
	 */
	public static function isFieldName($strName)
	{
		return preg_match('/^[A-Za-z0-9[\]_-]+$/', $strName);
	}
}
