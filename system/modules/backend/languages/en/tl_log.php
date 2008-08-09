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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_log']['tstamp']   = array('Date', 'Date and time of creation');
$GLOBALS['TL_LANG']['tl_log']['action']   = array('Category', 'Category of the action');
$GLOBALS['TL_LANG']['tl_log']['source']   = array('Source', 'Log entries can be sorted by their source (back end or front end entry).');
$GLOBALS['TL_LANG']['tl_log']['username'] = array('User', 'Name of the logged user');
$GLOBALS['TL_LANG']['tl_log']['text']     = array('Details', 'Details of the current entry');
$GLOBALS['TL_LANG']['tl_log']['ip']       = array('IP address', 'Requesting IP address');
$GLOBALS['TL_LANG']['tl_log']['browser']  = array('Browser', 'Requesting browser');
$GLOBALS['TL_LANG']['tl_log']['func']     = array('Function', 'Function name');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_log']['BE'] = 'Back end';
$GLOBALS['TL_LANG']['tl_log']['FE'] = 'Front end';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_log']['show']   = array('Show details', 'Show details of entry ID %s');
$GLOBALS['TL_LANG']['tl_log']['delete'] = array('Delete', 'Delete entry ID %s');

?>