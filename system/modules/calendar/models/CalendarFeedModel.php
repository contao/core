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
 * @method static \CalendarFeedModel|null findById($id, $opt=array())
 * @method static \CalendarFeedModel|null findByPk($id, $opt=array())
 * @method static \CalendarFeedModel|null findByIdOrAlias($val, $opt=array())
 * @method static \CalendarFeedModel|null findOneBy($col, $val, $opt=array())
 * @method static \CalendarFeedModel|null findOneByTstamp($val, $opt=array())
 * @method static \CalendarFeedModel|null findOneByTitle($val, $opt=array())
 * @method static \CalendarFeedModel|null findOneByAlias($val, $opt=array())
 * @method static \CalendarFeedModel|null findOneByLanguage($val, $opt=array())
 * @method static \CalendarFeedModel|null findOneByCalendars($val, $opt=array())
 * @method static \CalendarFeedModel|null findOneByFormat($val, $opt=array())
 * @method static \CalendarFeedModel|null findOneBySource($val, $opt=array())
 * @method static \CalendarFeedModel|null findOneByMaxItems($val, $opt=array())
 * @method static \CalendarFeedModel|null findOneByFeedBase($val, $opt=array())
 * @method static \CalendarFeedModel|null findOneByDescription($val, $opt=array())
 *
 * @method static \Model\Collection|\CalendarFeedModel[]|\CalendarFeedModel|null findByTstamp($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel[]|\CalendarFeedModel|null findByTitle($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel[]|\CalendarFeedModel|null findByAlias($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel[]|\CalendarFeedModel|null findByLanguage($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel[]|\CalendarFeedModel|null findByCalendars($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel[]|\CalendarFeedModel|null findByFormat($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel[]|\CalendarFeedModel|null findBySource($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel[]|\CalendarFeedModel|null findByMaxItems($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel[]|\CalendarFeedModel|null findByFeedBase($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel[]|\CalendarFeedModel|null findByDescription($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel[]|\CalendarFeedModel|null findMultipleByIds($val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel[]|\CalendarFeedModel|null findBy($col, $val, $opt=array())
 * @method static \Model\Collection|\CalendarFeedModel[]|\CalendarFeedModel|null findAll($opt=array())
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
	 * @return \Model\Collection|\CalendarFeedModel[]|\CalendarFeedModel|null A collection of models or null if the calendar is not part of a feed
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
	 * @return \Model\Collection|\CalendarFeedModel[]|\CalendarFeedModel|null A collection of models or null if there are no feeds
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
