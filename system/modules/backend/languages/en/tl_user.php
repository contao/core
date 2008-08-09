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
$GLOBALS['TL_LANG']['tl_user']['username']   = array('Username', 'Please enter a unique username.');
$GLOBALS['TL_LANG']['tl_user']['name']       = array('Name', 'Please enter the first and last name.');
$GLOBALS['TL_LANG']['tl_user']['email']      = array('E-mail address', 'Please enter a valid e-mail address.');
$GLOBALS['TL_LANG']['tl_user']['language']   = array('Back end language', 'Please select a back end language.');
$GLOBALS['TL_LANG']['tl_user']['showHelp']   = array('Show explanation', 'If you choose this option, a short explanation will be shown after each input field.');
$GLOBALS['TL_LANG']['tl_user']['useRTE']     = array('Enable rich text editor', 'If you experience problems using the rich text editor, you can disable it here.');
$GLOBALS['TL_LANG']['tl_user']['thumbnails'] = array('Show thumbnail images', 'If you choose this option, thumbnail images will be shown in the file manager.');
$GLOBALS['TL_LANG']['tl_user']['admin']      = array('Administrator', 'An administrator has unlimited access to all pages, modules, extensions and folders!');
$GLOBALS['TL_LANG']['tl_user']['groups']     = array('Groups', 'Each user who is not an administrator inherits all permissions from a user group. Therefore, every user has to be a member of at least one group! If a user is assigned to more than one group, the permissions are added together.');
$GLOBALS['TL_LANG']['tl_user']['inherit']    = array('Permission inheritance', 'Please define to what extent the user inherits permissions.');
$GLOBALS['TL_LANG']['tl_user']['group']      = array('Use group settings only', 'the current user inherits his permissions from the enabled groups exclusively irrespective of individual settings.');
$GLOBALS['TL_LANG']['tl_user']['extend']     = array('Extend group settings', 'inherited permissions are extended by individual settings.');
$GLOBALS['TL_LANG']['tl_user']['custom']     = array('Use individual settings only', 'only the permissions of the current user are applied irrespective of any group permissions.');
$GLOBALS['TL_LANG']['tl_user']['modules']    = array('Back end modules', 'Please select the modules you want to enable for the user.');
$GLOBALS['TL_LANG']['tl_user']['pagemounts'] = array('Pagemounts', 'Please select the pages you want to enable for the current user. Subpages will be included automatically.');
$GLOBALS['TL_LANG']['tl_user']['alpty']      = array('Allowed page types', 'Please select the page types you want to enable for the user.');
$GLOBALS['TL_LANG']['tl_user']['filemounts'] = array('Filemounts', 'Please select the folders you want to enable for the user. Subfolders will be included automatically.');
$GLOBALS['TL_LANG']['tl_user']['forms']      = array('Forms', 'Please select the forms you want to enable for the user.');
$GLOBALS['TL_LANG']['tl_user']['disable']    = array('Deactivate', 'If you choose this option, the current account will be deactivated. The user will not be able to log in to the system anymore.');
$GLOBALS['TL_LANG']['tl_user']['start']      = array('Activate on', 'If you enter a date here, the current account will be activated on this day.');
$GLOBALS['TL_LANG']['tl_user']['stop']       = array('Deactivate on', 'If you enter a date here, the current account will be deactivated on this day.');
$GLOBALS['TL_LANG']['tl_user']['session']    = array('Delete session data', 'If you choose this option, the current session data will be deleted.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_user']['sessionDeleted'] = 'Your session data has been deleted';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_user']['new']    = array('New user', 'Create a new user');
$GLOBALS['TL_LANG']['tl_user']['show']   = array('User details', 'Show details of user ID %s');
$GLOBALS['TL_LANG']['tl_user']['edit']   = array('Edit user', 'Edit user ID %s');
$GLOBALS['TL_LANG']['tl_user']['copy']   = array('Duplicate user', 'Duplicate user ID %s');
$GLOBALS['TL_LANG']['tl_user']['delete'] = array('Delete user', 'Delete user ID %s');
$GLOBALS['TL_LANG']['tl_user']['su']     = array('Switch user', 'Switch to user ID %s');

?>