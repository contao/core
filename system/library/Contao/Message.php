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
use \Exception;


/**
 * Class Image
 *
 * Provide methods to resize images.
 * @copyright  Leo Feyer 2011-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Library
 */
class Message
{

	/**
	 * Add an error message
	 * @param string
	 * @return void
	 */
	public static function addError($strMessage)
	{
		static::add($strMessage, 'TL_ERROR');
	}


	/**
	 * Add a confirmation message
	 * @param string
	 * @return void
	 */
	public static function addConfirmation($strMessage)
	{
		static::add($strMessage, 'TL_CONFIRM');
	}


	/**
	 * Add a new message
	 * @param string
	 * @return void
	 */
	public static function addNew($strMessage)
	{
		static::add($strMessage, 'TL_NEW');
	}


	/**
	 * Add an info message
	 * @param string
	 * @return void
	 */
	public static function addInfo($strMessage)
	{
		static::add($strMessage, 'TL_INFO');
	}


	/**
	 * Add a raw message
	 * @param string
	 * @return void
	 */
	public static function addRaw($strMessage)
	{
		static::add($strMessage, 'TL_RAW');
	}


	/**
	 * Add a message
	 * @param string
	 * @param string
	 * @return void
	 * @throws \Exception
	 */
	public static function add($strMessage, $strType)
	{
		if ($strMessage == '')
		{
			return;
		}

		if (!in_array($strType, static::getTypes()))
		{
			throw new Exception("Invalid message type $strType");
		}

		if (!is_array($_SESSION[$strType]))
		{
			$_SESSION[$strType] = array();
		}

		$_SESSION[$strType][] = $strMessage;
	}


	/**
	 * Return all messages as HTML
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	public static function generate($blnDcLayout=false, $blnNoWrapper=false)
	{
		$strMessages = '';

		// Regular messages
		foreach (static::getTypes() as $strType)
		{
			if (!is_array($_SESSION[$strType]))
			{
				continue;
			}

			$strClass = strtolower($strType);
			$_SESSION[$strType] = array_unique($_SESSION[$strType]);

			foreach ($_SESSION[$strType] as $strMessage)
			{
				if ($strType == 'TL_RAW')
				{
					$strMessages .= $strMessage;
				}
				else
				{
					$strMessages .= sprintf('<p class="%s">%s</p>%s', $strClass, $strMessage, "\n");
				}
			}

			if (!$_POST)
			{
				$_SESSION[$strType] = array();
			}
		}

		$strMessages = trim($strMessages);

		// Wrapping container
		if (!$blnNoWrapper && $strMessages != '')
		{
			$strMessages = sprintf('%s<div class="tl_message">%s%s%s</div>%s', ($blnDcLayout ? "\n\n" : "\n"), "\n", $strMessages, "\n", ($blnDcLayout ? '' : "\n"));
		}

		return $strMessages;
	}


	/**
	 * Reset the message system
	 * @return void
	 */
	public static function reset()
	{
		foreach (static::getTypes() as $strType)
		{
			$_SESSION[$strType] = array();
		}
	}


	/**
	 * Return all available message types
	 * @return array
	 */
	public static function getTypes()
	{
		return array('TL_ERROR', 'TL_CONFIRM', 'TL_NEW', 'TL_INFO', 'TL_RAW');
	}
}
