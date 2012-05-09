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
 * Class CalendarModel
 *
 * Provide methods to find and save calendars.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Calendar
 */
class CalendarModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_calendar';


	/**
	 * Find multiple calendars by their IDs
	 * @param array
	 * @return \Model_Collection|null
	 */
	public static function findMultipleByIds($arrIds)
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;
		return static::findBy(array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ")"), null, array('order'=>\Database::getInstance()->findInSet("$t.id", $arrIds)));
	}
}
