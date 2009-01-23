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
$GLOBALS['TL_LANG']['tl_calendar_events']['title']        = array('Title', 'Please enter the title of the event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['alias']        = array('Event alias', 'The event alias is a unique reference to the event which can be called instead of the event ID.');
$GLOBALS['TL_LANG']['tl_calendar_events']['author']       = array('Author', 'Here you can change the author of the event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['startDate']    = array('Start date', 'Please enter the start date of the event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['endDate']      = array('End date', 'Please enter the end date of the event or leave blank for a single day event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['addTime']      = array('Add time', 'Add a start and end time of the event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['startTime']    = array('Start time', 'Please enter the start time according to the global time format.');
$GLOBALS['TL_LANG']['tl_calendar_events']['endTime']      = array('End time', 'Use the same value for start and end time to make this an open-ended event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['teaser']       = array('Teaser text', 'The teaser text is usually shown instead of the actual event text followed by a "read more …" link.');
$GLOBALS['TL_LANG']['tl_calendar_events']['details']      = array('Event details', 'Please enter the event details.');
$GLOBALS['TL_LANG']['tl_calendar_events']['recurring']    = array('Recurring event', 'Repeat the current event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['repeatEach']   = array('Interval', 'Please set the time interval.');
$GLOBALS['TL_LANG']['tl_calendar_events']['recurrences']  = array('Recurrances', 'Please set the number of recurrances (0 = unlimited).');
$GLOBALS['TL_LANG']['tl_calendar_events']['addImage']     = array('Add an image', 'If you choose this option, an image will be added to the event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['addEnclosure'] = array('Add enclosure', 'Add one or more downloadable files to the event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['enclosure']    = array('Enclosure', 'Please choose the files you want to attach.');
$GLOBALS['TL_LANG']['tl_calendar_events']['source']       = array('Target page', 'Link to an internal or external page instead of the default page.');
$GLOBALS['TL_LANG']['tl_calendar_events']['jumpTo']       = array('Jump to page', 'Please select the page to which visitors will be redirected.');
$GLOBALS['TL_LANG']['tl_calendar_events']['cssClass']     = array('CSS class', 'Here you can enter one or more CSS classes (class attributes).');
$GLOBALS['TL_LANG']['tl_calendar_events']['published']    = array('Published', 'The event will not be visible on your website until it is published.');
$GLOBALS['TL_LANG']['tl_calendar_events']['start']        = array('Show from', 'If you enter a date here the current event will not be shown on the website before this day.');
$GLOBALS['TL_LANG']['tl_calendar_events']['stop']         = array('Show until', 'If you enter a date here the current event will not be shown on the website after this day.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_calendar_events']['default']  = array('Use default', 'By clicking the "read more …" button visitors will be redirected to the default page of the calendar.');
$GLOBALS['TL_LANG']['tl_calendar_events']['internal'] = array('Internal page', 'By clicking the "read more …" button visitors will be redirected to an internal page.');
$GLOBALS['TL_LANG']['tl_calendar_events']['external'] = array('External website', 'By clicking the "read more …" button visitors will be redirected to an external website.');
$GLOBALS['TL_LANG']['tl_calendar_events']['days']     = 'day(s)';
$GLOBALS['TL_LANG']['tl_calendar_events']['weeks']    = 'week(s)';
$GLOBALS['TL_LANG']['tl_calendar_events']['months']   = 'month(s)';
$GLOBALS['TL_LANG']['tl_calendar_events']['years']    = 'year(s)';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_calendar_events']['new']        = array('New event', 'Create a new event');
$GLOBALS['TL_LANG']['tl_calendar_events']['edit']       = array('Edit event', 'Edit event ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['copy']       = array('Copy event', 'Copy event ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['cut']        = array('Move event', 'Move event ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['delete']     = array('Delete event', 'Delete event ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['show']       = array('Event details', 'Show details of event ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['editheader'] = array('Edit calendar', 'Edit the current calendar');

?>