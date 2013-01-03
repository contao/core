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
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_news_archive']['title']          = array('Title', 'Please enter a news archive title.');
$GLOBALS['TL_LANG']['tl_news_archive']['jumpTo']         = array('Redirect page', 'Please choose the news reader page to which visitors will be redirected when clicking a news item.');
$GLOBALS['TL_LANG']['tl_news_archive']['allowComments']  = array('Enable comments', 'Allow visitors to comment news items.');
$GLOBALS['TL_LANG']['tl_news_archive']['notify']         = array('Notify', 'Please choose who to notify when comments are added.');
$GLOBALS['TL_LANG']['tl_news_archive']['sortOrder']      = array('Sort order', 'By default, comments are sorted ascending, starting with the oldest one.');
$GLOBALS['TL_LANG']['tl_news_archive']['perPage']        = array('Comments per page', 'Number of comments per page. Set to 0 to disable pagination.');
$GLOBALS['TL_LANG']['tl_news_archive']['moderate']       = array('Moderate comments', 'Approve comments before they are published on the website.');
$GLOBALS['TL_LANG']['tl_news_archive']['bbcode']         = array('Allow BBCode', 'Allow visitors to format their comments with BBCode.');
$GLOBALS['TL_LANG']['tl_news_archive']['requireLogin']   = array('Require login to comment', 'Allow only authenticated users to create comments.');
$GLOBALS['TL_LANG']['tl_news_archive']['disableCaptcha'] = array('Disable the security question', 'Use this option only if you have limited comments to authenticated users.');
$GLOBALS['TL_LANG']['tl_news_archive']['protected']      = array('Protect archive', 'Show news items to certain member groups only.');
$GLOBALS['TL_LANG']['tl_news_archive']['groups']         = array('Allowed member groups', 'These groups will be able to see the news items in this archive.');
$GLOBALS['TL_LANG']['tl_news_archive']['makeFeed']       = array('Generate feed', 'Generate an RSS or Atom feed from the news archive.');
$GLOBALS['TL_LANG']['tl_news_archive']['format']         = array('Feed format', 'Please choose a feed format.');
$GLOBALS['TL_LANG']['tl_news_archive']['language']       = array('Feed language', 'Please enter the feed language according to the ISO-639 standard (e.g. <em>en</em> or <em>en-us</em>).');
$GLOBALS['TL_LANG']['tl_news_archive']['source']         = array('Export settings', 'Here you can choose what will be exported.');
$GLOBALS['TL_LANG']['tl_news_archive']['maxItems']       = array('Maximum number of items', 'Here you can limit the number of feed items. Set to 0 to export all.');
$GLOBALS['TL_LANG']['tl_news_archive']['feedBase']       = array('Base URL', 'Please enter the base URL with protocol (e.g. <em>http://</em>).');
$GLOBALS['TL_LANG']['tl_news_archive']['alias']          = array('Feed alias', 'Here you can enter a unique filename (without extension). The XML feed file will be auto-generated in the root directory of your Contao installation, e.g. as <em>name.xml</em>.');
$GLOBALS['TL_LANG']['tl_news_archive']['description']    = array('Feed description', 'Please enter a short description of the news feed.');
$GLOBALS['TL_LANG']['tl_news_archive']['tstamp']         = array('Revision date', 'Date and time of the latest revision');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news_archive']['title_legend']     = 'Title and redirect page';
$GLOBALS['TL_LANG']['tl_news_archive']['comments_legend']  = 'Comments';
$GLOBALS['TL_LANG']['tl_news_archive']['protected_legend'] = 'Access protection';
$GLOBALS['TL_LANG']['tl_news_archive']['feed_legend']      = 'RSS/Atom feed';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_news_archive']['notify_admin']  = 'System administrator';
$GLOBALS['TL_LANG']['tl_news_archive']['notify_author'] = 'Author of the news item';
$GLOBALS['TL_LANG']['tl_news_archive']['notify_both']   = 'Author and system administrator';
$GLOBALS['TL_LANG']['tl_news_archive']['source_teaser'] = 'News teasers';
$GLOBALS['TL_LANG']['tl_news_archive']['source_text']   = 'Full articles';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news_archive']['new']        = array('New archive', 'Create a new archive');
$GLOBALS['TL_LANG']['tl_news_archive']['show']       = array('Archive details', 'Show the details of archive ID %s');
$GLOBALS['TL_LANG']['tl_news_archive']['edit']       = array('Edit archive', 'Edit archive ID %s');
$GLOBALS['TL_LANG']['tl_news_archive']['editheader'] = array('Edit archive settings', 'Edit the settings of archive ID %s');
$GLOBALS['TL_LANG']['tl_news_archive']['copy']       = array('Duplicate archive', 'Duplicate archive ID %s');
$GLOBALS['TL_LANG']['tl_news_archive']['delete']     = array('Delete archive', 'Delete archive ID %s');
$GLOBALS['TL_LANG']['tl_news_archive']['comments']   = array('Comments', 'Show comments of archive ID %s');

?>