<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_news_feed']['title']       = array('Title', 'Please enter a feed title.');
$GLOBALS['TL_LANG']['tl_news_feed']['alias']       = array('Feed alias', 'Here you can enter a unique filename (without extension). The XML feed file will be auto-generated in the <em>share</em> directory of your Contao installation, e.g. as <em>share/name.xml</em>.');
$GLOBALS['TL_LANG']['tl_news_feed']['language']    = array('Feed language', 'Please enter the feed language according to the ISO-639 standard (e.g. <em>en</em> or <em>en-us</em>).');
$GLOBALS['TL_LANG']['tl_news_feed']['archives']    = array('News archives', 'Here you can choose the news archives to be included in the feed.');
$GLOBALS['TL_LANG']['tl_news_feed']['format']      = array('Feed format', 'Please choose a feed format.');
$GLOBALS['TL_LANG']['tl_news_feed']['source']      = array('Export settings', 'Here you can choose what will be exported.');
$GLOBALS['TL_LANG']['tl_news_feed']['maxItems']    = array('Maximum number of items', 'Here you can limit the number of feed items. Set to 0 to export all.');
$GLOBALS['TL_LANG']['tl_news_feed']['feedBase']    = array('Base URL', 'Please enter the base URL with protocol (e.g. <em>http://</em>).');
$GLOBALS['TL_LANG']['tl_news_feed']['description'] = array('Feed description', 'Here you can enter a short description of the news feed.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news_feed']['title_legend']    = 'Title and language';
$GLOBALS['TL_LANG']['tl_news_feed']['archives_legend'] = 'News archives';
$GLOBALS['TL_LANG']['tl_news_feed']['config_legend']   = 'Feed settings';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_news_feed']['source_teaser'] = 'News teasers';
$GLOBALS['TL_LANG']['tl_news_feed']['source_text']   = 'Full articles';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news_feed']['new']    = array('New feed', 'Create a new feed');
$GLOBALS['TL_LANG']['tl_news_feed']['show']   = array('Feed details', 'Show the details of feed ID %s');
$GLOBALS['TL_LANG']['tl_news_feed']['edit']   = array('Edit feed', 'Edit feed ID %s');
$GLOBALS['TL_LANG']['tl_news_feed']['copy']   = array('Duplicate feed', 'Duplicate feed ID %s');
$GLOBALS['TL_LANG']['tl_news_feed']['delete'] = array('Delete feed', 'Delete feed ID %s');
