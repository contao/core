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
 * @package    RssReader
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['rss_feed']          = array('RSS feed URL', 'Please enter the URL of the RSS feed.');
$GLOBALS['TL_LANG']['tl_module']['rss_template']      = array('Layout template', 'Please choose a layout template. You can add custom RSS templates to folder <em>templates</em>. RSS template files start with <em>rss_</em> and require file extension <em>.tpl</em>.');
$GLOBALS['TL_LANG']['tl_module']['rss_numberOfItems'] = array('Number of items', 'Please select the number of items to be displayed (0 = display all items).');
$GLOBALS['TL_LANG']['tl_module']['rss_cache']         = array('Cache timeout', 'Here you can define the period of time the RSS feed is being cached.');

?>