<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class SystemMessages
 *
 * Add system messages to the welcome screen.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class SystemMessages extends Backend
{

	/**
	 * Check for the latest Contao version
	 * @return string
	 */
	public function versionCheck()
	{
		if (!empty($GLOBALS['TL_CONFIG']['latestVersion']) && version_compare(VERSION . '.' . BUILD, $GLOBALS['TL_CONFIG']['latestVersion'], '<'))
		{
			return '<p class="tl_info"><a href="contao/main.php?do=maintenance">' . sprintf($GLOBALS['TL_LANG']['MSC']['updateVersion'], $GLOBALS['TL_CONFIG']['latestVersion']) . '</a></p>';
		}

		return '';
	}


	/**
	 * Return the date of the last login
	 * @return string
	 */
	public function lastLogin()
	{
		$this->import('BackendUser', 'User');

		if ($this->User->lastLogin > 0)
		{
			return '<p class="tl_info">' . sprintf($GLOBALS['TL_LANG']['MSC']['lastLogin'][1], $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $this->User->lastLogin)) . '</p>';
		}

		return '';
	}
}

?>