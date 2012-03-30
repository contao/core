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
 * Class Validator
 *
 * Provide methods to validate values
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @author     Yanick Witschi <yanick.witschi@certo-net.ch>
 * @package    Library
 */
class Validator extends \System
{
	
	/**
	 * Validates for digits
	 * @param mixed input
	 * @return boolean
	 */
	public static function isDigits($varValue)
	{
		return preg_match('/^[\d \.-]*$/', $varValue);
	}


	/**
	 * Validates for alphabetical values
	 * @param mixed input
	 * @return boolean
	 */
	public static function isAlpha($varValue)
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
	 * Validates for alphanumerical values
	 * @param mixed input
	 * @return boolean
	 */
	public static function isAlnum($varValue)
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
	 * Validates for characters that are usually encoded by class Input [=<>()#/])
	 * @param mixed input
	 * @return boolean
	 */
	public static function isExtnd($varValue)
	{
		return preg_match('/[#\(\)\/<=>]/', html_entity_decode($varValue));
	}


	/**
	 * Validates for a date format
	 * @param mixed input
	 * @return boolean
	 */
	public static function isDate($varValue)
	{
		$objDate = new \Date();
		return preg_match('~^'. $objDate->getRegexp($GLOBALS['TL_CONFIG']['dateFormat']) .'$~i', $varValue);
	}


	/**
	 * Validates for a time format
	 * @param mixed input
	 * @return boolean
	 */
	public static function isTime($varValue)
	{
		$objDate = new \Date();
		return preg_match('~^'. $objDate->getRegexp($GLOBALS['TL_CONFIG']['timeFormat']) .'$~i', $varValue);
	}


	/**
	 * Validates for a datetime format
	 * @param mixed input
	 * @return boolean
	 */
	public static function isDatim($varValue)
	{
		$objDate = new \Date();
		return preg_match('~^'. $objDate->getRegexp($GLOBALS['TL_CONFIG']['datimFormat']) .'$~i', $varValue);
	}


	/**
	 * Validates for an email
	 * @param mixed input
	 * @return boolean
	 */
	public static function isEmail($varValue)
	{
		$varValue = $this->idnaEncodeEmail($varValue);
		return preg_match('/^(\w+[!#\$%&\'\*\+\-\/=\?^_`\.\{\|\}~]*)+(?<!\.)@\w+([_\.-]*\w+)*\.[a-z]{2,6}$/i', $varValue);
	}


	/**
	 * Validates for an url
	 * @param mixed input
	 * @return boolean
	 */
	public static function isUrl($varValue)
	{
		$varValue = $this->idnaEncodeUrl($varValue);
		return preg_match('/^[a-zA-Z0-9\.\+\/\?#%:,;\{\}\(\)\[\]@&=~_-]*$/', $varValue);
	}


	/**
	 * Validates for an alias
	 * @param mixed input
	 * @return boolean
	 */
	public static function isAlias($varValue)
	{
		return preg_match('/^[\pN\pL\._-]*$/', $varValue);
	}


	/**
	 * Validates for a folder alias
	 * @param mixed input
	 * @return boolean
	 */
	public static function isFolderAlias($varValue)
	{
		return preg_match('/^[\pN\pL\/\._-]*$/', $varValue);
	}


	/**
	 * Validates for a phone number
	 * @param mixed input
	 * @return boolean
	 */
	public static function isPhone($varValue)
	{
		return preg_match('/^(\+|\()?(\d+[ \+\(\)\/-]*)+$/', html_entity_decode($varValue));
	}


	/**
	 * Validates for a per cent value
	 * @param mixed input
	 * @return boolean
	 */
	public static function isPercent($varValue)
	{
		return (is_numeric($varValue) && $varValue >= 0 && $varValue <= 100);
	}
}