<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['calendar'] = array('Calendar/Events', 'This module allows you to manage events of different calendars.');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['events']          = 'Calendar/Events';
$GLOBALS['TL_LANG']['FMD']['calendar']        = array('Calendar', 'This module adds a calendar to your website.');
$GLOBALS['TL_LANG']['FMD']['minicalendar']    = array('Mini-calendar', 'This module adds a mini-calendar to your website that can be used to trigger the event list module.');
$GLOBALS['TL_LANG']['FMD']['eventreader']     = array('Event reader', 'This module shows the details of a single event.');
$GLOBALS['TL_LANG']['FMD']['eventlist']       = array('Event list', 'This module lists all events of a month, week or day.');
$GLOBALS['TL_LANG']['FMD']['upcoming_events'] = array('Upcoming events', 'This module lists the a particular number of upcoming events.');

?>