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
$GLOBALS['TL_LANG']['tl_module']['news_archives']      = array('News archives', 'Please select one or more news archives.');
$GLOBALS['TL_LANG']['tl_module']['news_featured']      = array('Featured items', 'Here you can choose how featured items are handled.');
$GLOBALS['TL_LANG']['tl_module']['news_numberOfItems'] = array('Total number of items', 'Here you can limit the total number of news items. Set to 0 to show all.');
$GLOBALS['TL_LANG']['tl_module']['news_jumpToCurrent'] = array('No period selected', 'Here you can define what to display if no period is selected.');
$GLOBALS['TL_LANG']['tl_module']['news_readerModule']  = array('News reader module', 'Automatically switch to the news reader if an item has been selected.');
$GLOBALS['TL_LANG']['tl_module']['news_metaFields']    = array('Meta fields', 'Here you can select the meta fields.');
$GLOBALS['TL_LANG']['tl_module']['news_template']      = array('News template', 'Here you can select the news template.');
$GLOBALS['TL_LANG']['tl_module']['news_format']        = array('Archive format', 'Here you can choose the news archive format.');
$GLOBALS['TL_LANG']['tl_module']['news_startDay']      = array('Week start day', 'Here you can choose the week start day.');
$GLOBALS['TL_LANG']['tl_module']['news_order']         = array('Sort order', 'Here you can choose the sort order.');
$GLOBALS['TL_LANG']['tl_module']['news_showQuantity']  = array('Show number of items', 'Show the number of news items of each month.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['news_day']     = 'Day';
$GLOBALS['TL_LANG']['tl_module']['news_month']   = 'Month';
$GLOBALS['TL_LANG']['tl_module']['news_year']    = 'Year';
$GLOBALS['TL_LANG']['tl_module']['hide_module']  = 'Hide the module';
$GLOBALS['TL_LANG']['tl_module']['show_current'] = 'Jump to the current period';
$GLOBALS['TL_LANG']['tl_module']['all_items']    = 'Show all news items';
$GLOBALS['TL_LANG']['tl_module']['featured']     = 'Show featured news items only';
$GLOBALS['TL_LANG']['tl_module']['unfeatured']   = 'Skip featured news items';

?>