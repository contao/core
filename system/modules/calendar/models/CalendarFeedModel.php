<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Calendar
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;

/**
 * Class CalendarFeedModel
 *
 * Provide methods to find and save calendar feeds.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Calendar
 */
class CalendarFeedModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_calendar_feed';


	/**
	 * Find all feeds which include a certain calendar
	 * @param integer
	 * @return \Model|null
	 */
	public static function findByCalendar($intId)
	{
		$t = static::$strTable;
		return static::findOneBy(array("$t.calendars LIKE '%\"" . intval($intId) . "\"%'"), null);
	}


	/**
	 * Find calendar feeds by their IDs
	 * @param array
	 * @return \Model_Collection|null
	 */
	public static function findByIds($arrIds)
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;
		return static::findBy(array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ")"), null);
	}
}
