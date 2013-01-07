<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_calendar_events']['title']        = array('Title', 'Please enter the event title.');
$GLOBALS['TL_LANG']['tl_calendar_events']['alias']        = array('Event alias', 'The event alias is a unique reference to the event which can be called instead of its numeric ID.');
$GLOBALS['TL_LANG']['tl_calendar_events']['author']       = array('Author', 'Here you can change the author of the event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['addTime']      = array('Add time', 'Add a start and end time to the event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['startTime']    = array('Start time', 'Please enter the start time according to the global time format.');
$GLOBALS['TL_LANG']['tl_calendar_events']['endTime']      = array('End time', 'Use the same value for start and end time to create an open-ended event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['startDate']    = array('Start date', 'Please enter the start date according to the global date format.');
$GLOBALS['TL_LANG']['tl_calendar_events']['endDate']      = array('End date', 'Leave blank to create a single day event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['teaser']       = array('Event teaser', 'The event teaser can be shown in an event list instead of the full article. A "read more …" link will be added automatically.');
$GLOBALS['TL_LANG']['tl_calendar_events']['details']      = array('Event text', 'Here you can enter the event text.');
$GLOBALS['TL_LANG']['tl_calendar_events']['addImage']     = array('Add an image', 'Add an image to the event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['recurring']    = array('Repeat event', 'Create a recurring event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['repeatEach']   = array('Interval', 'Here you can set the recurrence interval.');
$GLOBALS['TL_LANG']['tl_calendar_events']['recurrences']  = array('Recurrences', 'Set to 0 for unlimited recurrences.');
$GLOBALS['TL_LANG']['tl_calendar_events']['addEnclosure'] = array('Add enclosures', 'Add one or more downloadable files to the event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['enclosure']    = array('Enclosures', 'Please choose the files you want to attach.');
$GLOBALS['TL_LANG']['tl_calendar_events']['source']       = array('Redirect target', 'Here you can override the default redirect target.');
$GLOBALS['TL_LANG']['tl_calendar_events']['default']      = array('Use default', 'By clicking the "read more …" button, visitors will be redirected to the default page of the calendar.');
$GLOBALS['TL_LANG']['tl_calendar_events']['internal']     = array('Page', 'By clicking the "read more …" button, visitors will be redirected to an internal page.');
$GLOBALS['TL_LANG']['tl_calendar_events']['article']      = array('Article', 'By clicking the "read more …" button, visitors will be redirected to an article.');
$GLOBALS['TL_LANG']['tl_calendar_events']['external']     = array('External URL', 'By clicking the "read more …" button, visitors will be redirected to an external website.');
$GLOBALS['TL_LANG']['tl_calendar_events']['jumpTo']       = array('Redirect page', 'Please choose the page to which visitors will be redirected when clicking the event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['articleId']    = array('Article', 'Please choose the article to which visitors will be redirected when clicking the event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['cssClass']     = array('CSS class', 'Here you can enter one or more classes.');
$GLOBALS['TL_LANG']['tl_calendar_events']['noComments']   = array('Disable comments', 'Do not allow comments for this particular event.');
$GLOBALS['TL_LANG']['tl_calendar_events']['published']    = array('Publish event', 'Make the event publicly visible on the website.');
$GLOBALS['TL_LANG']['tl_calendar_events']['start']        = array('Show from', 'Do not show the event on the website before this day.');
$GLOBALS['TL_LANG']['tl_calendar_events']['stop']         = array('Show until', 'Do not show the event on the website on and after this day.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_calendar_events']['title_legend']     = 'Title and author';
$GLOBALS['TL_LANG']['tl_calendar_events']['date_legend']      = 'Date and time';
$GLOBALS['TL_LANG']['tl_calendar_events']['teaser_legend']    = 'Teaser';
$GLOBALS['TL_LANG']['tl_calendar_events']['text_legend']      = 'Event text';
$GLOBALS['TL_LANG']['tl_calendar_events']['image_legend']     = 'Image settings';
$GLOBALS['TL_LANG']['tl_calendar_events']['recurring_legend'] = 'Recurrence settings';
$GLOBALS['TL_LANG']['tl_calendar_events']['enclosure_legend'] = 'Enclosures';
$GLOBALS['TL_LANG']['tl_calendar_events']['source_legend']    = 'Redirect target';
$GLOBALS['TL_LANG']['tl_calendar_events']['expert_legend']    = 'Expert settings';
$GLOBALS['TL_LANG']['tl_calendar_events']['publish_legend']   = 'Publish settings';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_calendar_events']['days']     = 'day(s)';
$GLOBALS['TL_LANG']['tl_calendar_events']['weeks']    = 'week(s)';
$GLOBALS['TL_LANG']['tl_calendar_events']['months']   = 'month(s)';
$GLOBALS['TL_LANG']['tl_calendar_events']['years']    = 'year(s)';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_calendar_events']['new']        = array('New event', 'Create a new event');
$GLOBALS['TL_LANG']['tl_calendar_events']['show']       = array('Event details', 'Show the details of event ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['edit']       = array('Edit event', 'Edit event ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['copy']       = array('Duplicate event', 'Duplicate event ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['cut']        = array('Move event', 'Move event ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['delete']     = array('Delete event', 'Delete event ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['toggle']     = array('Publish/unpublish event', 'Publish/unpublish event ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['editheader'] = array('Edit calendar', 'Edit the calendar settings');
$GLOBALS['TL_LANG']['tl_calendar_events']['pasteafter'] = array('Paste into this calendar', 'Paste after event ID %s');

?>