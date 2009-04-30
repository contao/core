<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_calendar']['title']       = array('Title', 'Please enter the calendar title.');
$GLOBALS['TL_LANG']['tl_calendar']['jumpTo']      = array('Redirect page', 'Please choose the event reader page to which visitors will be redirected when clicking an event.');
$GLOBALS['TL_LANG']['tl_calendar']['protected']   = array('Protect calendar', 'Show events to certain member groups only.');
$GLOBALS['TL_LANG']['tl_calendar']['groups']      = array('Allowed member groups', 'These groups will be able to see the events in this calendar.');
$GLOBALS['TL_LANG']['tl_calendar']['makeFeed']    = array('Generate feed', 'Generate an RSS or Atom feed from the calendar.');
$GLOBALS['TL_LANG']['tl_calendar']['format']      = array('Feed format', 'Please choose a feed format.');
$GLOBALS['TL_LANG']['tl_calendar']['language']    = array('Feed language', 'Please enter the page language according to the ISO-639 standard (e.g. <em>en</em> or <em>en-us</em>).');
$GLOBALS['TL_LANG']['tl_calendar']['source']      = array('Export settings', 'Here you can choose what will be exported.');
$GLOBALS['TL_LANG']['tl_calendar']['maxItems']    = array('Maximum number of items', 'Here you can limit the number of events. Set to 0 to export all.');
$GLOBALS['TL_LANG']['tl_calendar']['feedBase']    = array('Base URL', 'Please enter the base URL with protocol (e.g. <em>http://</em>).');
$GLOBALS['TL_LANG']['tl_calendar']['alias']       = array('Feed alias', 'Here you can enter a unique filename (without extension). The XML feed file will be auto-generated in the root directory of your TYPOlight installation, e.g. as <em>name.xml</em>.');
$GLOBALS['TL_LANG']['tl_calendar']['description'] = array('Feed description', 'Please enter a short description of the calendar feed.');
$GLOBALS['TL_LANG']['tl_calendar']['tstamp']      = array('Revision date', 'Date and time of the latest revision');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_calendar']['title_legend']     = 'Title and redirect page';
$GLOBALS['TL_LANG']['tl_calendar']['protected_legend'] = 'Access protection';
$GLOBALS['TL_LANG']['tl_calendar']['feed_legend']      = 'RSS/Atom feed';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_calendar']['source_teaser'] = 'Event teasers';
$GLOBALS['TL_LANG']['tl_calendar']['source_text']   = 'Full articles';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_calendar']['new']    = array('New calendar', 'Create a new calendar');
$GLOBALS['TL_LANG']['tl_calendar']['show']   = array('Calendar details', 'Show the details of calendar ID %s');
$GLOBALS['TL_LANG']['tl_calendar']['edit']   = array('Edit calendar', 'Edit calendar ID %s');
$GLOBALS['TL_LANG']['tl_calendar']['copy']   = array('Copy calendar', 'Copy calendar ID %s');
$GLOBALS['TL_LANG']['tl_calendar']['delete'] = array('Delete calendar', 'Delete calendar ID %s');

?>