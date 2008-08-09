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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['cal_calendar'] = array('Calendars', 'Please choose one or more calendars.');
$GLOBALS['TL_LANG']['tl_module']['cal_template'] = array('Event template', 'Please choose an event layout. You can add custom event layouts to folder <em>templates</em>. Event template files start with <em>event_</em> and require file extension <em>.tpl</em>.');
$GLOBALS['TL_LANG']['tl_module']['cal_startDay'] = array('Week start day', 'Please choose the week start day.');
$GLOBALS['TL_LANG']['tl_module']['cal_previous'] = array('Previous button label', 'Leave the field empty to use the default label or enter an image tag to use an image.');
$GLOBALS['TL_LANG']['tl_module']['cal_next']     = array('Next button label', 'Leave the field empty to use the default label or enter an image tag to use an image.');
$GLOBALS['TL_LANG']['tl_module']['cal_format']   = array('Format', 'Please choose an eventlist format.');
$GLOBALS['TL_LANG']['tl_module']['cal_limit']    = array('Number of events', 'Please enter the maximum number of events. Enter 0 to show all events.');
$GLOBALS['TL_LANG']['tl_module']['cal_noSpan']   = array('Shortened view', 'Show events only once even if they span over multiple days.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_module']['cal_day']   = 'Day';
$GLOBALS['TL_LANG']['tl_module']['cal_week']  = 'Week';
$GLOBALS['TL_LANG']['tl_module']['cal_month'] = 'Month';
$GLOBALS['TL_LANG']['tl_module']['cal_year']  = 'Year';
$GLOBALS['TL_LANG']['tl_module']['cal_two']   = '2 years';
$GLOBALS['TL_LANG']['tl_module']['next_7']    = '+ 1 week';
$GLOBALS['TL_LANG']['tl_module']['next_14']   = '+ 2 weeks';
$GLOBALS['TL_LANG']['tl_module']['next_30']   = '+ 1 month';
$GLOBALS['TL_LANG']['tl_module']['next_90']   = '+ 3 months';
$GLOBALS['TL_LANG']['tl_module']['next_180']  = '+ 6 months';
$GLOBALS['TL_LANG']['tl_module']['next_365']  = '+ 1 year';

?>