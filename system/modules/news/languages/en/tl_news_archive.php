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
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_news_archive']['title']          = array('Title', 'Please enter a news archive title.');
$GLOBALS['TL_LANG']['tl_news_archive']['tstamp']         = array('Revision date', 'Date and time of latest revision');
$GLOBALS['TL_LANG']['tl_news_archive']['language']       = array('Language', 'Please enter the language according to the RFC3066 format (e.g. <em>en</em>, <em>en-us</em> or <em>en-cockney</em>).');
$GLOBALS['TL_LANG']['tl_news_archive']['jumpTo']         = array('Jump to page', 'Please select the page to which visitors will be redirected when clicking a news article.');
$GLOBALS['TL_LANG']['tl_news_archive']['protected']      = array('Protect archive', 'Show news items to certain member groups only.');
$GLOBALS['TL_LANG']['tl_news_archive']['groups']         = array('Allowed member groups', 'Here you can choose which groups will be allowed to see the news items.');
$GLOBALS['TL_LANG']['tl_news_archive']['allowComments']  = array('Allow comments', 'Allow your visitors to comment news items.');
$GLOBALS['TL_LANG']['tl_news_archive']['notify']         = array('Notify', 'Please choose who to notify when new comments are added.');
$GLOBALS['TL_LANG']['tl_news_archive']['template']       = array('Comments layout', 'Please choose a comment layout. Comment template files start with <em>com_</em>.');
$GLOBALS['TL_LANG']['tl_news_archive']['sortOrder']      = array('Sort order', 'Please choose the sort order.');
$GLOBALS['TL_LANG']['tl_news_archive']['perPage']        = array('Items per page', 'Please enter the number of comments per page (0 = disable pagination).');
$GLOBALS['TL_LANG']['tl_news_archive']['moderate']       = array('Moderate', 'Approve comments before they are shown on the website.');
$GLOBALS['TL_LANG']['tl_news_archive']['bbcode']         = array('Allow BBCode', 'Allow visitors to use BBCode to format their comments.');
$GLOBALS['TL_LANG']['tl_news_archive']['requireLogin']   = array('Require login', 'Do not allow guests to create comments.');
$GLOBALS['TL_LANG']['tl_news_archive']['disableCaptcha'] = array('Disable security question', 'Choose this option to disable the security question (not recommended).');
$GLOBALS['TL_LANG']['tl_news_archive']['makeFeed']       = array('Generate feed', 'Generate an RSS/Atom feed from the news archive.');
$GLOBALS['TL_LANG']['tl_news_archive']['format']         = array('Feed format', 'Please choose a feed format.');
$GLOBALS['TL_LANG']['tl_news_archive']['description']    = array('Description', 'Please enter a short description of the news archive.');
$GLOBALS['TL_LANG']['tl_news_archive']['alias']          = array('Feed alias', 'Here you can enter a unique feed name. An XML file will be auto-generated in the root directory of your TYPOlight installation (<em>name.xml</em>).');
$GLOBALS['TL_LANG']['tl_news_archive']['feedBase']       = array('Base URL', 'Please enter the base URL including the protocol (e.g. <em>http://</em>).');
$GLOBALS['TL_LANG']['tl_news_archive']['maxItems']       = array('Maximum number of items', 'Limit the number of exported items. Leave blank or enter 0 to include all.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_news_archive']['ascending']     = 'ascending';
$GLOBALS['TL_LANG']['tl_news_archive']['descending']    = 'descending';
$GLOBALS['TL_LANG']['tl_news_archive']['notify_author'] = 'Author of the news item';
$GLOBALS['TL_LANG']['tl_news_archive']['notify_admin']  = 'System administrator';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news_archive']['new']      = array('New archive', 'Create a new archive');
$GLOBALS['TL_LANG']['tl_news_archive']['edit']     = array('Edit archive', 'Edit archive ID %s');
$GLOBALS['TL_LANG']['tl_news_archive']['copy']     = array('Copy archive', 'Copy archive ID %s');
$GLOBALS['TL_LANG']['tl_news_archive']['delete']   = array('Delete archive', 'Delete archive ID %s');
$GLOBALS['TL_LANG']['tl_news_archive']['show']     = array('Archive details', 'Show details of archive ID %s');
$GLOBALS['TL_LANG']['tl_news_archive']['comments'] = array('Comments', 'Show comments of archive ID %s');

?>