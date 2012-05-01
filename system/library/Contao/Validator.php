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
use \Date, \Idna, \System;


/**
 * Class Validator
 *
 * Provide methods to validate data.
 * @copyright  Leo Feyer 2011-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Validator extends System
{

	/**
	 * Numeric characters (including full stop [.] minus [-] and space [ ])
	 * @param mixed
	 * @return boolean
	 */
	public static function isNumeric($varValue)
	{
		return preg_match('/^[\d \.-]*$/', $varValue);
	}


	/**
	 * Alphabetic characters (including full stop [.] minus [-] and space [ ])
	 * @param mixed
	 * @return boolean
	 */
	public static function isAlphabetic($varValue)
	{
		if (function_exists('mb_eregi'))
		{
			return mb_eregi('^[[:alpha:] \.-]*$', $varValue);
		}
		else
		{
			return preg_match('/^[\pL \.-]*$/u', $varValue);
		}
	}


	/**
	 * Alphanumeric characters (including full stop [.] minus [-], underscore [_] and space [ ])
	 * @param mixed
	 * @return boolean
	 */
	public static function isAlphanumeric($varValue)
	{
		if (function_exists('mb_eregi'))
		{
			return mb_eregi('^[[:alnum:] \._-]*$', $varValue);
		}
		else
		{
			return preg_match('/^[\pN\pL \._-]*$/u', $varValue);
		}
	}


	/**
	 * Characters that are usually encoded by class Input [=<>()#/])
	 * @param mixed
	 * @return boolean
	 */
	public static function isExtendedAlphanumeric($varValue)
	{
		return !preg_match('/[#\(\)\/<=>]/', $varValue);
	}


	/**
	 * Valid date formats
	 * @param mixed
	 * @return boolean
	 */
	public static function isDate($varValue)
	{
		$objDate = new Date();
		return preg_match('~^'. $objDate->getRegexp($GLOBALS['TL_CONFIG']['dateFormat']) .'$~i', $varValue);
	}


	/**
	 * Valid time formats
	 * @param mixed
	 * @return boolean
	 */
	public static function isTime($varValue)
	{
		$objDate = new Date();
		return preg_match('~^'. $objDate->getRegexp($GLOBALS['TL_CONFIG']['timeFormat']) .'$~i', $varValue);
	}


	/**
	 * Valid date and time formats
	 * @param mixed
	 * @return boolean
	 */
	public static function isDatim($varValue)
	{
		$objDate = new Date();
		return preg_match('~^'. $objDate->getRegexp($GLOBALS['TL_CONFIG']['datimFormat']) .'$~i', $varValue);
	}


	/**
	 * Valid e-mail address
	 * @param mixed
	 * @return boolean
	 */
	public static function isEmail($varValue)
	{
		return preg_match('/^(\w+[!#\$%&\'\*\+\-\/=\?^_`\.\{\|\}~]*)+(?<!\.)@\w+([_\.-]*\w+)*\.[a-z]{2,6}$/i', Idna::encodeEmail($varValue));
	}


	/**
	 * Valid URL
	 * @param mixed
	 * @return boolean
	 */
	public static function isUrl($varValue)
	{
		return preg_match('/^[a-zA-Z0-9\.\+\/\?#%:,;\{\}\(\)\[\]@&=~_-]*$/', Idna::encodeUrl($varValue));
	}


	/**
	 * Valid alias name
	 * @param mixed
	 * @return boolean
	 */
	public static function isAlias($varValue)
	{
		return preg_match('/^[\pN\pL\._-]*$/', $varValue);
	}


	/**
	 * Valid folder alias name
	 * @param mixed
	 * @return boolean
	 */
	public static function isFolderAlias($varValue)
	{
		return preg_match('/^[\pN\pL\/\._-]*$/', $varValue);
	}


	/**
	 * Valid phone number
	 * @param mixed
	 * @return boolean
	 */
	public static function isPhone($varValue)
	{
		return preg_match('/^(\+|\()?(\d+[ \+\(\)\/-]*)+$/', $varValue);
	}


	/**
	 * Valid percentage
	 * @param mixed
	 * @return boolean
	 */
	public static function isPercent($varValue)
	{
		return (!is_numeric($varValue) || $varValue < 0 || $varValue > 100);
	}
}
