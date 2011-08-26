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
$GLOBALS['TL_LANG']['MOD']['calendar'] = array('Events', 'Manage events and display them in a calendar or event list.');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['events']      = 'Events';
$GLOBALS['TL_LANG']['FMD']['calendar']    = array('Calendar', 'Adds a calendar to the page.');
$GLOBALS['TL_LANG']['FMD']['eventlist']   = array('Event list', 'Adds a list of events to the page.');
$GLOBALS['TL_LANG']['FMD']['eventreader'] = array('Event reader', 'Shows the details of an event.');
$GLOBALS['TL_LANG']['FMD']['eventmenu']   = array('Event list menu', 'Generates a navigation menu to browse the event list.');

?>