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
$GLOBALS['TL_LANG']['tl_calendar']['title']          = array('Title', 'Please enter the calendar title.');
$GLOBALS['TL_LANG']['tl_calendar']['jumpTo']         = array('Redirect page', 'Please choose the event reader page to which visitors will be redirected when clicking an event.');
$GLOBALS['TL_LANG']['tl_calendar']['allowComments']  = array('Enable comments', 'Allow visitors to comment events.');
$GLOBALS['TL_LANG']['tl_calendar']['notify']         = array('Notify', 'Please choose who to notify when comments are added.');
$GLOBALS['TL_LANG']['tl_calendar']['sortOrder']      = array('Sort order', 'By default, comments are sorted ascending, starting with the oldest one.');
$GLOBALS['TL_LANG']['tl_calendar']['perPage']        = array('Comments per page', 'Number of comments per page. Set to 0 to disable pagination.');
$GLOBALS['TL_LANG']['tl_calendar']['moderate']       = array('Moderate comments', 'Approve comments before they are published on the website.');
$GLOBALS['TL_LANG']['tl_calendar']['bbcode']         = array('Allow BBCode', 'Allow visitors to format their comments with BBCode.');
$GLOBALS['TL_LANG']['tl_calendar']['requireLogin']   = array('Require login to comment', 'Allow only authenticated users to create comments.');
$GLOBALS['TL_LANG']['tl_calendar']['disableCaptcha'] = array('Disable the security question', 'Use this option only if you have limited comments to authenticated users.');
$GLOBALS['TL_LANG']['tl_calendar']['protected']      = array('Protect calendar', 'Show events to certain member groups only.');
$GLOBALS['TL_LANG']['tl_calendar']['groups']         = array('Allowed member groups', 'These groups will be able to see the events in this calendar.');
$GLOBALS['TL_LANG']['tl_calendar']['makeFeed']       = array('Generate feed', 'Generate an RSS or Atom feed from the calendar.');
$GLOBALS['TL_LANG']['tl_calendar']['format']         = array('Feed format', 'Please choose a feed format.');
$GLOBALS['TL_LANG']['tl_calendar']['language']       = array('Feed language', 'Please enter the feed language according to the ISO-639 standard (e.g. <em>en</em> or <em>en-us</em>).');
$GLOBALS['TL_LANG']['tl_calendar']['source']         = array('Export settings', 'Here you can choose what will be exported.');
$GLOBALS['TL_LANG']['tl_calendar']['maxItems']       = array('Maximum number of items', 'Here you can limit the number of events. Set to 0 to export all.');
$GLOBALS['TL_LANG']['tl_calendar']['feedBase']       = array('Base URL', 'Please enter the base URL with protocol (e.g. <em>http://</em>).');
$GLOBALS['TL_LANG']['tl_calendar']['alias']          = array('Feed alias', 'Here you can enter a unique filename (without extension). The XML feed file will be auto-generated in the root directory of your Contao installation, e.g. as <em>name.xml</em>.');
$GLOBALS['TL_LANG']['tl_calendar']['description']    = array('Feed description', 'Please enter a short description of the calendar feed.');
$GLOBALS['TL_LANG']['tl_calendar']['tstamp']         = array('Revision date', 'Date and time of the latest revision');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_calendar']['title_legend']     = 'Title and redirect page';
$GLOBALS['TL_LANG']['tl_calendar']['comments_legend']  = 'Comments';
$GLOBALS['TL_LANG']['tl_calendar']['protected_legend'] = 'Access protection';
$GLOBALS['TL_LANG']['tl_calendar']['feed_legend']      = 'RSS/Atom feed';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_calendar']['notify_admin']  = 'System administrator';
$GLOBALS['TL_LANG']['tl_calendar']['notify_author'] = 'Author of the event';
$GLOBALS['TL_LANG']['tl_calendar']['notify_both']   = 'Author and system administrator';
$GLOBALS['TL_LANG']['tl_calendar']['source_teaser'] = 'Event teasers';
$GLOBALS['TL_LANG']['tl_calendar']['source_text']   = 'Full articles';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_calendar']['new']        = array('New calendar', 'Create a new calendar');
$GLOBALS['TL_LANG']['tl_calendar']['show']       = array('Calendar details', 'Show the details of calendar ID %s');
$GLOBALS['TL_LANG']['tl_calendar']['edit']       = array('Edit calendar', 'Edit calendar ID %s');
$GLOBALS['TL_LANG']['tl_calendar']['editheader'] = array('Edit calendar settings', 'Edit the settings of calendar ID %s');
$GLOBALS['TL_LANG']['tl_calendar']['copy']       = array('Duplicate calendar', 'Duplicate calendar ID %s');
$GLOBALS['TL_LANG']['tl_calendar']['delete']     = array('Delete calendar', 'Delete calendar ID %s');

?>