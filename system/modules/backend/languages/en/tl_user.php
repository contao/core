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
$GLOBALS['TL_LANG']['tl_user']['username']     = array('Username', 'Please enter a unique username.');
$GLOBALS['TL_LANG']['tl_user']['name']         = array('Name', 'Please enter the first and last name.');
$GLOBALS['TL_LANG']['tl_user']['email']        = array('E-mail address', 'Please enter a valid e-mail address.');
$GLOBALS['TL_LANG']['tl_user']['language']     = array('Back end language', 'Here you can choose the back end language.');
$GLOBALS['TL_LANG']['tl_user']['backendTheme'] = array('Back end theme', 'Here you can override the global back end theme.');
$GLOBALS['TL_LANG']['tl_user']['showHelp']     = array('Show explanation', 'Show a short explanation text below each input field.');
$GLOBALS['TL_LANG']['tl_user']['thumbnails']   = array('Show thumbnail images', 'Show thumbnail images in the file manager.');
$GLOBALS['TL_LANG']['tl_user']['useRTE']       = array('Enable the rich text editor', 'Use the rich text editor to format text elements.');
$GLOBALS['TL_LANG']['tl_user']['useCE']        = array('Enable the code editor', 'Use the code editor to modify code elements.');
$GLOBALS['TL_LANG']['tl_user']['fancyUpload']  = array('Use FancyUpload', 'If FancyUpload does not work properly in your web browser, you can deactivate the script here.');
$GLOBALS['TL_LANG']['tl_user']['oldBeTheme']   = array('Use the old form layout', 'Do not use the new collapsible two-column forms.');
$GLOBALS['TL_LANG']['tl_user']['admin']        = array('Make the user an administrator', 'Administrators have unlimited access to all modules and elements!');
$GLOBALS['TL_LANG']['tl_user']['groups']       = array('User groups', 'Here you can assign the user to one or more groups.');
$GLOBALS['TL_LANG']['tl_user']['inherit']      = array('Permission inheritance', 'Here you can define which group permissions the user inherits.');
$GLOBALS['TL_LANG']['tl_user']['group']        = array('Use group settings only', 'The user inherits only group permissions.');
$GLOBALS['TL_LANG']['tl_user']['extend']       = array('Extend group settings', 'The group permissions are extended by individual ones.');
$GLOBALS['TL_LANG']['tl_user']['custom']       = array('Use individual settings only', 'Only individual permissions are applied.');
$GLOBALS['TL_LANG']['tl_user']['modules']      = array('Back end modules', 'Here you can grant access to one or more back end modules.');
$GLOBALS['TL_LANG']['tl_user']['themes']       = array('Theme modules', 'Here you can grant access to the theme modules.');
$GLOBALS['TL_LANG']['tl_user']['pagemounts']   = array('Pagemounts', 'Here you can grant access to one or more pages (subpages are included automatically).');
$GLOBALS['TL_LANG']['tl_user']['alpty']        = array('Allowed page types', 'Here you can select the page types you want to allow.');
$GLOBALS['TL_LANG']['tl_user']['filemounts']   = array('Filemounts', 'Here you can grant access to one or more folders (subfolders will be included automatically).');
$GLOBALS['TL_LANG']['tl_user']['forms']        = array('Allowed forms', 'Here you can grant access to one or more forms.');
$GLOBALS['TL_LANG']['tl_user']['formp']        = array('Form permissions', 'Here you can define the form permissions.');
$GLOBALS['TL_LANG']['tl_user']['disable']      = array('Deactivate', 'Temporarily disable the account.');
$GLOBALS['TL_LANG']['tl_user']['start']        = array('Activate on', 'Automatically activate the account on this day.');
$GLOBALS['TL_LANG']['tl_user']['stop']         = array('Deactivate on', 'Automatically deactivate the account on this day.');
$GLOBALS['TL_LANG']['tl_user']['session']      = array('Purge data', 'Please select the data you want to purge.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_user']['name_legend']       = 'Name and e-mail';
$GLOBALS['TL_LANG']['tl_user']['backend_legend']    = 'Back end settings';
$GLOBALS['TL_LANG']['tl_user']['password_legend']   = 'Password settings';
$GLOBALS['TL_LANG']['tl_user']['admin_legend']      = 'Administrator';
$GLOBALS['TL_LANG']['tl_user']['groups_legend']     = 'User groups';
$GLOBALS['TL_LANG']['tl_user']['modules_legend']    = 'Allowed modules';
$GLOBALS['TL_LANG']['tl_user']['pagemounts_legend'] = 'Pagemounts';
$GLOBALS['TL_LANG']['tl_user']['filemounts_legend'] = 'Filemounts';
$GLOBALS['TL_LANG']['tl_user']['forms_legend']      = 'Form permissions';
$GLOBALS['TL_LANG']['tl_user']['account_legend']    = 'Account settings';
$GLOBALS['TL_LANG']['tl_user']['session_legend']    = 'Clear cache';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_user']['sessionLabel']  = 'Session data';
$GLOBALS['TL_LANG']['tl_user']['htmlLabel']     = 'Image cache';
$GLOBALS['TL_LANG']['tl_user']['tempLabel']     = 'Temporary folder';
$GLOBALS['TL_LANG']['tl_user']['sessionPurged'] = 'The session data has been purged';
$GLOBALS['TL_LANG']['tl_user']['htmlPurged']    = 'The image cache has been purged';
$GLOBALS['TL_LANG']['tl_user']['tempPurged']    = 'The temporary folder has been purged';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_user']['new']    = array('New user', 'Create a new user');
$GLOBALS['TL_LANG']['tl_user']['show']   = array('User details', 'Show the details of user ID %s');
$GLOBALS['TL_LANG']['tl_user']['edit']   = array('Edit user', 'Edit user ID %s');
$GLOBALS['TL_LANG']['tl_user']['copy']   = array('Duplicate user', 'Duplicate user ID %s');
$GLOBALS['TL_LANG']['tl_user']['delete'] = array('Delete user', 'Delete user ID %s');
$GLOBALS['TL_LANG']['tl_user']['toggle'] = array('Activate/deactivate user', 'Activate/deactivate user ID %s');
$GLOBALS['TL_LANG']['tl_user']['su']     = array('Switch user', 'Switch to user ID %s');

?>