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
 * @method static $this findById()
 * @method static $this findByPk()
 * @method static $this findByIdOrAlias()
 * @method static $this findOneBy()
 * @method static $this findOneByTstamp()
 * @method static $this findOneByTitle()
 * @method static $this findOneByAlias()
 * @method static $this findOneByLanguage()
 * @method static $this findOneByCalendars()
 * @method static $this findOneByFormat()
 * @method static $this findOneBySource()
 * @method static $this findOneByMaxItems()
 * @method static $this findOneByFeedBase()
 * @method static $this findOneByDescription()
 *
 * @method static \Model\Collection|\CalendarFeedModel findByTstamp()
 * @method static \Model\Collection|\CalendarFeedModel findByTitle()
 * @method static \Model\Collection|\CalendarFeedModel findByAlias()
 * @method static \Model\Collection|\CalendarFeedModel findByLanguage()
 * @method static \Model\Collection|\CalendarFeedModel findByCalendars()
 * @method static \Model\Collection|\CalendarFeedModel findByFormat()
 * @method static \Model\Collection|\CalendarFeedModel findBySource()
 * @method static \Model\Collection|\CalendarFeedModel findByMaxItems()
 * @method static \Model\Collection|\CalendarFeedModel findByFeedBase()
 * @method static \Model\Collection|\CalendarFeedModel findByDescription()
 * @method static \Model\Collection|\CalendarFeedModel findMultipleByIds()
 * @method static \Model\Collection|\CalendarFeedModel findBy()
 * @method static \Model\Collection|\CalendarFeedModel findAll()
 *
 * @method static integer countById()
 * @method static integer countByTstamp()
 * @method static integer countByTitle()
 * @method static integer countByAlias()
 * @method static integer countByLanguage()
 * @method static integer countByCalendars()
 * @method static integer countByFormat()
 * @method static integer countBySource()
 * @method static integer countByMaxItems()
 * @method static integer countByFeedBase()
 * @method static integer countByDescription()
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
