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
$GLOBALS['TL_LANG']['tl_member_group']['name']     = array('Group name', 'Please enter a unique group name.');
$GLOBALS['TL_LANG']['tl_member_group']['redirect'] = array('Redirect on login', 'If you choose this option, members of the group will be redirected to a particular page when they login.');
$GLOBALS['TL_LANG']['tl_member_group']['jumpTo']   = array('Jump to page', 'Please select the page to which members will be redirected.');
$GLOBALS['TL_LANG']['tl_member_group']['disable']  = array('Deactivate', 'If you choose this option, the current group will be deactivated.');
$GLOBALS['TL_LANG']['tl_member_group']['start']    = array('Activate on', 'If you enter a date here, the current group will be activated on this day.');
$GLOBALS['TL_LANG']['tl_member_group']['stop']     = array('Deactivate on', 'If you enter a date here, the current group will be deactivated on this day.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_member_group']['new']    = array('New group', 'Create a new group');
$GLOBALS['TL_LANG']['tl_member_group']['show']   = array('Group details', 'Show details of member group ID %s');
$GLOBALS['TL_LANG']['tl_member_group']['copy']   = array('Duplicate group', 'Duplicate member group ID %s');
$GLOBALS['TL_LANG']['tl_member_group']['delete'] = array('Delete group', 'Delete member group ID %s');
$GLOBALS['TL_LANG']['tl_member_group']['edit']   = array('Edit group', 'Edit member group ID %s');

?>