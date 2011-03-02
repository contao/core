<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_log']['tstamp']   = array('Date', 'Date and time of the log entry');
$GLOBALS['TL_LANG']['tl_log']['source']   = array('Origin', 'Back end or front end');
$GLOBALS['TL_LANG']['tl_log']['action']   = array('Category', 'Category of the action');
$GLOBALS['TL_LANG']['tl_log']['username'] = array('User', 'Name of the authenticated user');
$GLOBALS['TL_LANG']['tl_log']['text']     = array('Details', 'Details of the log entry');
$GLOBALS['TL_LANG']['tl_log']['func']     = array('Function', 'Name of the initiating function');
$GLOBALS['TL_LANG']['tl_log']['ip']       = array('IP address', 'IP address of the user');
$GLOBALS['TL_LANG']['tl_log']['browser']  = array('Browser', 'Name of the user agent');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_log']['BE'] = 'Back end';
$GLOBALS['TL_LANG']['tl_log']['FE'] = 'Front end';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_log']['show']   = array('Show details', 'Show the details of entry ID %s');
$GLOBALS['TL_LANG']['tl_log']['delete'] = array('Delete', 'Delete entry ID %s');

?>