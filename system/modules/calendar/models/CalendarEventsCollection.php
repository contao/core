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
 * Class CalendarEventsCollection
 *
 * Provide methods to handle multiple models.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Model
 */
class CalendarEventsCollection extends \Model_Collection
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_calendar_events';


	/**
	 * Find events of the current period by their parent ID
	 * @param integer
	 * @param integer
	 * @param integer
	 * @return Model|null
	 */
	public static function findCurrentByPid($intPid, $intStart, $intEnd)
	{
		$t = static::$strTable;
		$intStart = intval($intStart);
		$intEnd = intval($intEnd);

		$arrColumns = array("$t.pid=? AND (($t.startTime>=$intStart AND $t.startTime<=$intEnd) OR ($t.endTime>=$intStart AND $t.endTime<=$intEnd) OR ($t.startTime<=$intStart AND $t.endTime>=$intEnd) OR ($t.recurring=1 AND ($t.recurrences=0 OR $t.repeatEnd>=$intStart) AND $t.startTime<=$intEnd))");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, $intPid, array('order'=>"$t.startTime"));
	}


	/**
	 * Find published events with the default redirect target by their parent ID
	 * @param integer
	 * @return Model|null
	 */
	public static function findPublishedDefaultByPid($intPid)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.source='default'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, $intPid, array('order'=>"$t.startTime DESC"));
	}


	/**
	 * Find upcoming events by their parent ID
	 * @param integer
	 * @param integer
	 */
	public static function findUpcomingByPid($intId, $intLimit=0)
	{
		$time = time();
		$t = static::$strTable;

		$arrColumns = array("$t.pid=? AND ($t.startTime>=$time OR ($t.recurring=1 AND ($t.recurrences=0 OR $t.repeatEnd>=$time))) AND ($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1");

		if ($intLimit > 0)
		{
			return static::findBy($arrColumns, $intId, "$t.startTime", $intLimit);
		}
		else
		{
			return static::findBy($arrColumns, $intId, array('order'=>"$t.startTime"));
		}
	}
}

?>