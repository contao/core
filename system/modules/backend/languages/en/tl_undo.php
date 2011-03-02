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
$GLOBALS['TL_LANG']['tl_undo']['pid']          = array('User', 'Name of the associated user');
$GLOBALS['TL_LANG']['tl_undo']['fromTable']    = array('Source table', 'Name of the original table');
$GLOBALS['TL_LANG']['tl_undo']['affectedRows'] = array('Affected rows', 'Number of records included in the undo step');
$GLOBALS['TL_LANG']['tl_undo']['query']        = array('Details', 'Details of the undo step');
$GLOBALS['TL_LANG']['tl_undo']['data']         = array('Data', 'Raw data of the undo step');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_undo']['show'] = array('Show details', 'Show the details of entry ID %s');
$GLOBALS['TL_LANG']['tl_undo']['undo'] = array('Restore', 'Restore entry ID %s');

?>