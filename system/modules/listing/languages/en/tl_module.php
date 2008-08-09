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
 * @package    Listing
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['list_table']       = array('Table', 'Please choose the table you want to list.');
$GLOBALS['TL_LANG']['tl_module']['list_fields']      = array('Fields', 'Please enter a comma separated list of the fields you want to list.');
$GLOBALS['TL_LANG']['tl_module']['list_where']       = array('Condition', 'If you want to exclude certain records, you can enter a condition here (e.g. <em>published=1</em> or <em>type!=\'admin\'</em>).');
$GLOBALS['TL_LANG']['tl_module']['list_sort']        = array('Order by', 'Please enter a comma seperated list of fields that will be used to order the results by default. Add <em>DESC</em> after the fieldname to sort descending (e.g. <em>name, date DESC</em>).');
$GLOBALS['TL_LANG']['tl_module']['list_layout']      = array('List layout', 'Please choose a list layout. You can add custom list layouts to folder <em>templates</em>. List template files start with <em>list_</em> and require file extension <em>.tpl</em>.');
$GLOBALS['TL_LANG']['tl_module']['list_search']      = array('Searchable fields', 'Please enter a comma seperated list of fields that you want to be searchable.');
$GLOBALS['TL_LANG']['tl_module']['list_info']        = array('Details page fields', 'Please enter a comma separated list of the fields you want to show on the details page. Leave this field blank to disable the details page feature.');
$GLOBALS['TL_LANG']['tl_module']['list_info_layout'] = array('Details page layout', 'Please choose a details page layout. You can add custom details page layouts to folder <em>templates</em>. Details page template files start with <em>info_</em> and require file extension <em>.tpl</em>.');

?>