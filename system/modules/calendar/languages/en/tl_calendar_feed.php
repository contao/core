<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Calendar
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_calendar_feed']['title']       = array('Title', 'Please enter a feed title.');
$GLOBALS['TL_LANG']['tl_calendar_feed']['alias']       = array('Feed alias', 'Here you can enter a unique filename (without extension). The XML feed file will be auto-generated in the <em>share</em> directory of your Contao installation, e.g. as <em>share/alias.xml</em>.');
$GLOBALS['TL_LANG']['tl_calendar_feed']['language']    = array('Feed language', 'Please enter the feed language according to the ISO-639 standard (e.g. <em>en</em> or <em>en-us</em>).');
$GLOBALS['TL_LANG']['tl_calendar_feed']['calendars']   = array('Calendars', 'Here you can choose the calendars to be included in the feed.');
$GLOBALS['TL_LANG']['tl_calendar_feed']['format']      = array('Feed format', 'Please choose a feed format.');
$GLOBALS['TL_LANG']['tl_calendar_feed']['source']      = array('Export settings', 'Here you can choose what will be exported.');
$GLOBALS['TL_LANG']['tl_calendar_feed']['maxItems']    = array('Maximum number of items', 'Here you can limit the number of feed items. Set to 0 to export all.');
$GLOBALS['TL_LANG']['tl_calendar_feed']['feedBase']    = array('Base URL', 'Please enter the base URL with protocol (e.g. <em>http://</em>).');
$GLOBALS['TL_LANG']['tl_calendar_feed']['description'] = array('Feed description', 'Here you can enter a short description of the calendar feed.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_calendar_feed']['title_legend']     = 'Title and language';
$GLOBALS['TL_LANG']['tl_calendar_feed']['calendars_legend'] = 'Calendars';
$GLOBALS['TL_LANG']['tl_calendar_feed']['config_legend']    = 'Feed settings';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_calendar_feed']['source_teaser'] = 'Event teasers';
$GLOBALS['TL_LANG']['tl_calendar_feed']['source_text']   = 'Full articles';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_calendar_feed']['new']    = array('New feed', 'Create a new feed');
$GLOBALS['TL_LANG']['tl_calendar_feed']['show']   = array('Feed details', 'Show the details of feed ID %s');
$GLOBALS['TL_LANG']['tl_calendar_feed']['edit']   = array('Edit feed', 'Edit feed ID %s');
$GLOBALS['TL_LANG']['tl_calendar_feed']['copy']   = array('Duplicate feed', 'Duplicate feed ID %s');
$GLOBALS['TL_LANG']['tl_calendar_feed']['delete'] = array('Delete feed', 'Delete feed ID %s');
