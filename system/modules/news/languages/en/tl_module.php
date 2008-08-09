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
$GLOBALS['TL_LANG']['tl_module']['news_archives']      = array('News archives', 'Please select one or more news archives.');
$GLOBALS['TL_LANG']['tl_module']['news_showQuantity']  = array('Show number of entries', 'If you choose this option, the number of news items per month will be displayed in the archive menu.');
$GLOBALS['TL_LANG']['tl_module']['news_numberOfItems'] = array('Number of news', 'Please select the number of news items to be displayed (0 = display all news items).');
$GLOBALS['TL_LANG']['tl_module']['news_template']      = array('News layout', 'Please choose a news layout. You can add custom <em>news_</em> layouts to folder <em>templates</em>.');
$GLOBALS['TL_LANG']['tl_module']['news_metaFields']    = array('Header fields', 'Please choose the fields you want to include in a news article header.');
$GLOBALS['TL_LANG']['tl_module']['news_format']        = array('Format', 'Please choose an archive format.');
$GLOBALS['TL_LANG']['tl_module']['news_dateFormat']    = array('Date format', 'Please enter a date format as used by the PHP <em>date()</em> function.');
$GLOBALS['TL_LANG']['tl_module']['news_jumpToCurrent'] = array('Jump to current month', 'Jump to the current month if no month has been specified in the URL.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['news_month'] = 'Month';
$GLOBALS['TL_LANG']['tl_module']['news_year']  = 'Year';

?>