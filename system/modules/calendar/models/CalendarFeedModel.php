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
 * @method static \CalendarFeedModel[]|\Model\Collection findByTstamp()
 * @method static \CalendarFeedModel[]|\Model\Collection findByTitle()
 * @method static \CalendarFeedModel[]|\Model\Collection findByAlias()
 * @method static \CalendarFeedModel[]|\Model\Collection findByLanguage()
 * @method static \CalendarFeedModel[]|\Model\Collection findByCalendars()
 * @method static \CalendarFeedModel[]|\Model\Collection findByFormat()
 * @method static \CalendarFeedModel[]|\Model\Collection findBySource()
 * @method static \CalendarFeedModel[]|\Model\Collection findByMaxItems()
 * @method static \CalendarFeedModel[]|\Model\Collection findByFeedBase()
 * @method static \CalendarFeedModel[]|\Model\Collection findByDescription()
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
	 * @return static[]|\Model\Collection|null A collection of models or null if the calendar is not part of a feed
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
	 * @return static[]|\Model\Collection|null A collection of models or null if there are no feeds
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
