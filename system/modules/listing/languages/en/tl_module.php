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
 * @package    Listing
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['list_table']       = array('Table', 'Please choose the source table.');
$GLOBALS['TL_LANG']['tl_module']['list_fields']      = array('Fields', 'Please enter a comma separated list of the fields you want to list.');
$GLOBALS['TL_LANG']['tl_module']['list_where']       = array('Condition', 'Here you can enter a condition to filter the results (e.g. <em>published=1</em> or <em>type!="admin"</em>).');
$GLOBALS['TL_LANG']['tl_module']['list_search']      = array('Searchable fields', 'Here you can enter a comma seperated list of fields that you want to be searchable.');
$GLOBALS['TL_LANG']['tl_module']['list_sort']        = array('Order by', 'Here you can enter a comma seperated list of fields to sort the results by.');
$GLOBALS['TL_LANG']['tl_module']['list_info']        = array('Details page fields', 'Enter a comma separated list of the fields you want to show on the details page. Leave blank to disable the feature.');
$GLOBALS['TL_LANG']['tl_module']['list_info_where']  = array('Details page condition', 'Here you can enter a condition to filter the results (e.g. <em>published=1</em> or <em>type!="admin"</em>).');
$GLOBALS['TL_LANG']['tl_module']['list_layout']      = array('List template', 'Here you can select the list template.');
$GLOBALS['TL_LANG']['tl_module']['list_info_layout'] = array('Details page template', 'Here you can select the details page template.');

?>