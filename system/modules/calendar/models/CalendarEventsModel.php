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
 * @package    Backend
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class CalendarEventsModel
 *
 * Provide methods to find and save events.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Model
 */
class CalendarEventsModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_calendar_events';


	/**
	 * Find a published event from one or more calendars by its ID or alias
	 * @param integer
	 * @param string
	 * @param array
	 * @return \Contao\Model|null
	 */
	public static function findPublishedByParentAndIdOrAlias($intId, $varAlias, $arrPids)
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?) AND $t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, array($intId, $varAlias));
	}


	/**
	 * Find the first and last event in one or more calendars
	 * @param array
	 * @return \Contao\Model
	 */
	public static function findBoundaries($arrPids)
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$objMinMax = Database::getInstance()->query("SELECT MIN(startTime) AS dateFrom, MAX(endTime) AS dateTo, MAX(repeatEnd) AS repeatUntil FROM tl_calendar_events WHERE pid IN(". implode(',', array_map('intval', $arrPids)) .")");
		return new static($objMinMax);
	}
}
