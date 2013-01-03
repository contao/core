<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
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
 * Reads and writes calendar feeds
 * 
 * @package   Models
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
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
	 * @return \Model|null The model or null if the calendar is not part of a feed
	 */
	public static function findByCalendar($intId, array $arrOptions=array())
	{
		$t = static::$strTable;
		return static::findOneBy(array("$t.calendars LIKE '%\"" . intval($intId) . "\"%'"), null, $arrOptions);
	}


	/**
	 * Find calendar feeds by their IDs
	 * 
	 * @param array $arrIds     An array of calendar feed IDs
	 * @param array $arrOptions An optional options array
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no feeds
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
