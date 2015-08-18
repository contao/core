<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Reads and writes calendar feeds
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $title
 * @property string  $alias
 * @property string  $language
 * @property string  $calendars
 * @property string  $format
 * @property string  $source
 * @property integer $maxItems
 * @property string  $feedBase
 * @property string  $description
 * @property string  $feedName
 *
 * @method static \CalendarFeedModel findById($id, $opt=array())
 * @method static \CalendarFeedModel findByPk($id, $opt=array())
 * @method static \CalendarFeedModel findByIdOrAlias($val, $opt=array())
 * @method static \CalendarFeedModel findOneBy($col, $val, $opt=array())
 * @method static \CalendarFeedModel findOneByTstamp($val, $opt=array())
 * @method static \CalendarFeedModel findOneByTitle($val, $opt=array())
 * @method static \CalendarFeedModel findOneByAlias($val, $opt=array())
 * @method static \CalendarFeedModel findOneByLanguage($val, $opt=array())
 * @method static \CalendarFeedModel findOneByCalendars($val, $opt=array())
 * @method static \CalendarFeedModel findOneByFormat($val, $opt=array())
 * @method static \CalendarFeedModel findOneBySource($val, $opt=array())
 * @method static \CalendarFeedModel findOneByMaxItems($val, $opt=array())
 * @method static \CalendarFeedModel findOneByFeedBase($val, $opt=array())
 * @method static \CalendarFeedModel findOneByDescription($val, $opt=array())
 *
 * @method static \Model\Collection|\CalendarFeedModel findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel findByTitle($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel findByAlias($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel findByLanguage($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel findByCalendars($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel findByFormat($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel findBySource($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel findByMaxItems($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel findByFeedBase($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel findByDescription($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel findAll($opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByTitle($val, $opt=array())
 * @method static integer countByAlias($val, $opt=array())
 * @method static integer countByLanguage($val, $opt=array())
 * @method static integer countByCalendars($val, $opt=array())
 * @method static integer countByFormat($val, $opt=array())
 * @method static integer countBySource($val, $opt=array())
 * @method static integer countByMaxItems($val, $opt=array())
 * @method static integer countByFeedBase($val, $opt=array())
 * @method static integer countByDescription($val, $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class CalendarFeedModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_calendar_feed';


	/**
	 * Find all feeds which include a certain calendar
	 *
	 * @param integer $intId      The calendar ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\CalendarFeedModel|null A collection of models or null if the calendar is not part of a feed
	 */
	public static function findByCalendar($intId, array $arrOptions=array())
	{
		$t = static::$strTable;

		return static::findBy(array("$t.calendars LIKE '%\"" . intval($intId) . "\"%'"), null, $arrOptions);
	}


	/**
	 * Find calendar feeds by their IDs
	 *
	 * @param array $arrIds     An array of calendar feed IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model\Collection|\CalendarFeedModel|null A collection of models or null if there are no feeds
	 */
	public static function findByIds($arrIds, array $arrOptions=array())
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;

		return static::findBy(array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ")"), null, $arrOptions);
	}
}
