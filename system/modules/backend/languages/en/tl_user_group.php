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
$GLOBALS['TL_LANG']['tl_user_group']['name']       = array('Group name', 'Please enter a unique group name.');
$GLOBALS['TL_LANG']['tl_user_group']['modules']    = array('Back end modules', 'Please select the modules you want to enable for the group.');
$GLOBALS['TL_LANG']['tl_user_group']['pagemounts'] = array('Pagemounts', 'Please select the pages you want to enable for the current group. Subpages will be included automatically.');
$GLOBALS['TL_LANG']['tl_user_group']['alpty']      = array('Allowed page types', 'Please select the page types you want to enable for the group.');
$GLOBALS['TL_LANG']['tl_user_group']['filemounts'] = array('Filemounts', 'Please select the folders you want to enable for the group. Subfolders will be included automatically.');
$GLOBALS['TL_LANG']['tl_user_group']['forms']      = array('Forms', 'Please select the forms you want to enable for the group.');
$GLOBALS['TL_LANG']['tl_user_group']['alexf']      = array('Allowed fields', 'Select the fields you want to enable for the group.');
$GLOBALS['TL_LANG']['tl_user_group']['disable']    = array('Deactivate', 'If you choose this option, the current group will be deactivated. Permissions of a deactivated group can not be inherited by group members anymore.');
$GLOBALS['TL_LANG']['tl_user_group']['start']      = array('Activate on', 'If you enter a date here, the current group will be activated on this day.');
$GLOBALS['TL_LANG']['tl_user_group']['stop']       = array('Deactivate on', 'If you enter a date here, the current group will be deactivated on this day.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_user_group']['new']    = array('New user group', 'Create a new user group');
$GLOBALS['TL_LANG']['tl_user_group']['show']   = array('Group details', 'Show details of group ID %s');
$GLOBALS['TL_LANG']['tl_user_group']['copy']   = array('Duplicate group', 'Duplicate group ID %s');
$GLOBALS['TL_LANG']['tl_user_group']['delete'] = array('Delete group', 'Delete group ID %s');
$GLOBALS['TL_LANG']['tl_user_group']['edit']   = array('Edit group', 'Edit group ID %s');

?>