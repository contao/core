<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Calendar
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Reads and writes events
 *
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2014
 */
class CalendarEventsModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_calendar_events';


	/**
	 * Find a published event from one or more calendars by its ID or alias
	 *
	 * @param mixed $varId      The numeric ID or alias name
	 * @param array $arrPids    An array of calendar IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null The model or null if there is no event
	 */
	public static function findPublishedByParentAndIdOrAlias($varId, $arrPids, array $arrOptions=array())
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

		return static::findOneBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId), $arrOptions);
	}


	/**
	 * Find events of the current period by their parent ID
	 *
	 * @param integer $intPid     The calendar ID
	 * @param integer $intStart   The start date as Unix timestamp
	 * @param integer $intEnd     The end date as Unix timestamp
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no events
	 */
	public static function findCurrentByPid($intPid, $intStart, $intEnd, array $arrOptions=array())
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

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.startTime";
		}

		return static::findBy($arrColumns, $intPid, $arrOptions);
	}


	/**
	 * Find published events with the default redirect target by their parent ID
	 *
	 * @param integer $intPid     The calendar ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no events
	 */
	public static function findPublishedDefaultByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.source='default'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.startTime DESC";
		}

		return static::findBy($arrColumns, $intPid, $arrOptions);
	}


	/**
	 * Find upcoming events by their parent IDs
	 *
	 * @param array   $arrIds     An array of calendar IDs
	 * @param integer $intLimit   An optional limit
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no events
	 */
	public static function findUpcomingByPids($arrIds, $intLimit=0, array $arrOptions=array())
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$time = time();
		$t = static::$strTable;

		// Get upcoming events using endTime instead of startTime (see #3917)
		$arrColumns = array("($t.endTime>=$time OR ($t.recurring=1 AND ($t.recurrences=0 OR $t.repeatEnd>=$time))) AND $t.pid IN(" . implode(',', array_map('intval', $arrIds)) . ") AND ($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1");

		if ($intLimit > 0)
		{
			$arrOptions['limit'] = $intLimit;
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.startTime";
		}

		return static::findBy($arrColumns, null, $arrOptions);
	}
}
