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
 * Reads and writes events
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $tstamp
 * @property string  $title
 * @property string  $alias
 * @property integer $author
 * @property boolean $addTime
 * @property integer $startTime
 * @property integer $endTime
 * @property integer $startDate
 * @property integer $endDate
 * @property string  $location
 * @property string  $teaser
 * @property boolean $addImage
 * @property string  $singleSRC
 * @property string  $alt
 * @property string  $size
 * @property string  $imagemargin
 * @property string  $imageUrl
 * @property boolean $fullsize
 * @property string  $caption
 * @property string  $floating
 * @property boolean $recurring
 * @property string  $repeatEach
 * @property integer $repeatEnd
 * @property integer $recurrences
 * @property boolean $addEnclosure
 * @property string  $enclosure
 * @property string  $source
 * @property integer $jumpTo
 * @property integer $articleId
 * @property string  $url
 * @property boolean $target
 * @property string  $cssClass
 * @property boolean $noComments
 * @property boolean $published
 * @property string  $start
 * @property string  $stop
 *
 * @method static \CalendarEventsModel|null findById($id, $opt=array())
 * @method static \CalendarEventsModel|null findByPk($id, $opt=array())
 * @method static \CalendarEventsModel|null findByIdOrAlias($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneBy($col, $val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByPid($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByTstamp($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByTitle($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByAlias($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByAuthor($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByAddTime($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByStartTime($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByEndTime($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByStartDate($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByEndDate($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByLocation($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByTeaser($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByAddImage($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneBySingleSRC($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByAlt($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneBySize($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByImagemargin($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByImageUrl($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByFullsize($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByCaption($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByFloating($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByRecurring($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByRepeatEach($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByRepeatEnd($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByRecurrences($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByAddEnclosure($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByEnclosure($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneBySource($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByJumpTo($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByArticleId($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByUrl($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByTarget($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByCssClass($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByNoComments($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByPublished($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByStart($val, $opt=array())
 * @method static \CalendarEventsModel|null findOneByStop($val, $opt=array())
 *
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByPid($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByTitle($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByAlias($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByAuthor($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByAddTime($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByStartTime($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByEndTime($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByStartDate($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByEndDate($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByLocation($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByTeaser($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByAddImage($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findBySingleSRC($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByAlt($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findBySize($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByImagemargin($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByImageUrl($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByFullsize($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByCaption($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByFloating($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByRecurring($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByRepeatEach($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByRepeatEnd($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByRecurrences($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByAddEnclosure($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByEnclosure($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findBySource($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByJumpTo($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByArticleId($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByUrl($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByTarget($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByCssClass($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByNoComments($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByPublished($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByStart($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findByStop($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByTitle($val, $opt=array())
 * @method static integer countByAlias($val, $opt=array())
 * @method static integer countByAuthor($val, $opt=array())
 * @method static integer countByAddTime($val, $opt=array())
 * @method static integer countByStartTime($val, $opt=array())
 * @method static integer countByEndTime($val, $opt=array())
 * @method static integer countByStartDate($val, $opt=array())
 * @method static integer countByEndDate($val, $opt=array())
 * @method static integer countByLocation($val, $opt=array())
 * @method static integer countByTeaser($val, $opt=array())
 * @method static integer countByAddImage($val, $opt=array())
 * @method static integer countBySingleSRC($val, $opt=array())
 * @method static integer countByAlt($val, $opt=array())
 * @method static integer countBySize($val, $opt=array())
 * @method static integer countByImagemargin($val, $opt=array())
 * @method static integer countByImageUrl($val, $opt=array())
 * @method static integer countByFullsize($val, $opt=array())
 * @method static integer countByCaption($val, $opt=array())
 * @method static integer countByFloating($val, $opt=array())
 * @method static integer countByRecurring($val, $opt=array())
 * @method static integer countByRepeatEach($val, $opt=array())
 * @method static integer countByRepeatEnd($val, $opt=array())
 * @method static integer countByRecurrences($val, $opt=array())
 * @method static integer countByAddEnclosure($val, $opt=array())
 * @method static integer countByEnclosure($val, $opt=array())
 * @method static integer countBySource($val, $opt=array())
 * @method static integer countByJumpTo($val, $opt=array())
 * @method static integer countByArticleId($val, $opt=array())
 * @method static integer countByUrl($val, $opt=array())
 * @method static integer countByTarget($val, $opt=array())
 * @method static integer countByCssClass($val, $opt=array())
 * @method static integer countByNoComments($val, $opt=array())
 * @method static integer countByPublished($val, $opt=array())
 * @method static integer countByStart($val, $opt=array())
 * @method static integer countByStop($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 * @return \CalendarEventsModel|null The model or null if there is no event
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
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
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
	 * @return \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null A collection of models or null if there are no events
	 */
	public static function findCurrentByPid($intPid, $intStart, $intEnd, array $arrOptions=array())
	{
		$t = static::$strTable;
		$intStart = intval($intStart);
		$intEnd = intval($intEnd);

		$arrColumns = array("$t.pid=? AND (($t.startTime>=$intStart AND $t.startTime<=$intEnd) OR ($t.endTime>=$intStart AND $t.endTime<=$intEnd) OR ($t.startTime<=$intStart AND $t.endTime>=$intEnd) OR ($t.recurring='1' AND ($t.recurrences=0 OR $t.repeatEnd>=$intStart) AND $t.startTime<=$intEnd))");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
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
	 * @return \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null A collection of models or null if there are no events
	 */
	public static function findPublishedDefaultByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.source='default'");

		if (!BE_USER_LOGGED_IN)
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
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
	 * @return \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null A collection of models or null if there are no events
	 */
	public static function findUpcomingByPids($arrIds, $intLimit=0, array $arrOptions=array())
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;
		$time = \Date::floorToMinute();

		// Get upcoming events using endTime instead of startTime (see #3917)
		$arrColumns = array("($t.endTime>=$time OR ($t.recurring='1' AND ($t.recurrences=0 OR $t.repeatEnd>=$time))) AND $t.pid IN(" . implode(',', array_map('intval', $arrIds)) . ") AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'");

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
