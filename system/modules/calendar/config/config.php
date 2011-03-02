<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Back end modules
 */
array_insert($GLOBALS['BE_MOD']['content'], 1, array
(
	'calendar' => array
	(
		'tables'     => array('tl_calendar', 'tl_calendar_events'),
		'icon'       => 'system/modules/calendar/html/icon.gif'
	)
));


/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD'], 2, array
(
	'events' => array
	(
		'calendar'    => 'ModuleCalendar',
		'eventreader' => 'ModuleEventReader',
		'eventlist'   => 'ModuleEventlist',
		'eventmenu'   => 'ModuleEventMenu'
	)
));


/**
 * Cron jobs
 */
$GLOBALS['TL_CRON']['daily'][] = array('Calendar', 'generateFeeds');


/**
 * Register hook to add news items to the indexer
 */
$GLOBALS['TL_HOOKS']['getSearchablePages'][] = array('Calendar', 'getSearchablePages');

?>