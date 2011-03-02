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
$GLOBALS['TL_LANG']['tl_member_group']['name']     = array('Title', 'Please enter the group title.');
$GLOBALS['TL_LANG']['tl_member_group']['redirect'] = array('Redirect on login', 'Redirect group members to a custom page when they log in.');
$GLOBALS['TL_LANG']['tl_member_group']['jumpTo']   = array('Redirect page', 'Please choose the page to which the group members will be redirected.');
$GLOBALS['TL_LANG']['tl_member_group']['disable']  = array('Deactivate', 'Temporarily disable the group.');
$GLOBALS['TL_LANG']['tl_member_group']['start']    = array('Activate on', 'Automatically activate the group on this day.');
$GLOBALS['TL_LANG']['tl_member_group']['stop']     = array('Deactivate on', 'Automatically deactivate the group on this day.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_member_group']['title_legend']    = 'Title';
$GLOBALS['TL_LANG']['tl_member_group']['redirect_legend'] = 'Auto-redirect';
$GLOBALS['TL_LANG']['tl_member_group']['disable_legend']  = 'Account settings';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_member_group']['new']    = array('New group', 'Create a new group');
$GLOBALS['TL_LANG']['tl_member_group']['show']   = array('Group details', 'Show the details of group ID %s');
$GLOBALS['TL_LANG']['tl_member_group']['edit']   = array('Edit group', 'Edit group ID %s');
$GLOBALS['TL_LANG']['tl_member_group']['copy']   = array('Duplicate group', 'Duplicate group ID %s');
$GLOBALS['TL_LANG']['tl_member_group']['delete'] = array('Delete group', 'Delete group ID %s');
$GLOBALS['TL_LANG']['tl_member_group']['toggle'] = array('Activate/deactivate group', 'Activate/deactivate group ID %s');

?>