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
$GLOBALS['TL_LANG']['tl_calendar']['title']       = array('Title', 'Please enter a calendar title.');
$GLOBALS['TL_LANG']['tl_calendar']['tstamp']      = array('Revision date', 'Date and time of latest revision');
$GLOBALS['TL_LANG']['tl_calendar']['language']    = array('Language', 'Please enter the language according to the RFC3066 format (e.g. <em>en</em>, <em>en-us</em> or <em>en-cockney</em>).');
$GLOBALS['TL_LANG']['tl_calendar']['jumpTo']      = array('Jump to page', 'Please select the page to which visitors will be redirected when clicking an event.');
$GLOBALS['TL_LANG']['tl_calendar']['protected']   = array('Protect calendar', 'Show events to certain member groups only.');
$GLOBALS['TL_LANG']['tl_calendar']['groups']      = array('Allowed member groups', 'Here you can choose which groups will be allowed to see the events.');
$GLOBALS['TL_LANG']['tl_calendar']['makeFeed']    = array('Generate feed', 'Generate an RSS/Atom feed from the calendar.');
$GLOBALS['TL_LANG']['tl_calendar']['format']      = array('Feed format', 'Please choose a feed format.');
$GLOBALS['TL_LANG']['tl_calendar']['description'] = array('Description', 'Please enter a short description of the calendar.');
$GLOBALS['TL_LANG']['tl_calendar']['alias']       = array('Feed alias', 'Here you can enter a unique feed name. An XML file will be auto-generated in the root directory of your TYPOlight installation (<em>name.xml</em>).');
$GLOBALS['TL_LANG']['tl_calendar']['feedBase']    = array('Base URL', 'Please enter the base URL including the protocol (e.g. <em>http://</em>).');
$GLOBALS['TL_LANG']['tl_calendar']['maxItems']    = array('Maximum number of items', 'Limit the number of exported items. Leave blank or enter 0 to include all.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_calendar']['new']    = array('New calendar', 'Create a new calendar');
$GLOBALS['TL_LANG']['tl_calendar']['edit']   = array('Edit calendar', 'Edit calendar ID %s');
$GLOBALS['TL_LANG']['tl_calendar']['copy']   = array('Copy calendar', 'Copy calendar ID %s');
$GLOBALS['TL_LANG']['tl_calendar']['delete'] = array('Delete calendar', 'Delete calendar ID %s');
$GLOBALS['TL_LANG']['tl_calendar']['show']   = array('Calendar details', 'Show details of calendar ID %s');

?>