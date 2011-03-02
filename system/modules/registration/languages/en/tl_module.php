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
 * @package    Registration
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['disableCaptcha'] = array('Disable the security question', 'Here you can disable the security question (not recommended).');
$GLOBALS['TL_LANG']['tl_module']['reg_groups']     = array('Member groups', 'Here you can assign the user to one or more groups.');
$GLOBALS['TL_LANG']['tl_module']['reg_allowLogin'] = array('Allow login', 'Allow the new user to log into the front end.');
$GLOBALS['TL_LANG']['tl_module']['reg_skipName']   = array('Skip username', 'Do not require the username to request a new password.');
$GLOBALS['TL_LANG']['tl_module']['reg_close']      = array('Mode', 'Here you can define how to handle the deletion.');
$GLOBALS['TL_LANG']['tl_module']['reg_assignDir']  = array('Create a home directory', 'Create a home directory from the registered username.');
$GLOBALS['TL_LANG']['tl_module']['reg_homeDir']    = array('Home directory path', 'Please select the parent folder from the files directory.');
$GLOBALS['TL_LANG']['tl_module']['reg_activate']   = array('Send activation e-mail', 'Send an activation e-mail to the registered e-mail address.');
$GLOBALS['TL_LANG']['tl_module']['reg_jumpTo']     = array('Confirmation page', 'Please choose the page to which users will be redirected after the request has been completed.');
$GLOBALS['TL_LANG']['tl_module']['reg_text']       = array('Activation message', 'You can use the wildcards <em>##domain##</em> (domain name), <em>##link##</em> (activation link) and any input field (e.g. <em>##lastname##</em>).');
$GLOBALS['TL_LANG']['tl_module']['reg_password']   = array('Password message', 'You can use the wildcards <em>##domain##</em> (domain name), <em>##link##</em> (activation link) and any user property (e.g. <em>##lastname##</em>).');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_module']['account_legend'] = 'Account settings';


/**
 * Default messages
 */
$GLOBALS['TL_LANG']['tl_module']['emailText']    = array('Your registration on %s', "Thank you for your registration on ##domain##.\n\nPlease click ##link## to complete your registration and to activate your account. If you did not request an account, please ignore this e-mail.\n");
$GLOBALS['TL_LANG']['tl_module']['passwordText'] = array('Your password request on %s', "You have requested a new password for ##domain##.\n\nPlease click ##link## to set the new password. If you did not request this e-mail, please contact the website administrator.\n");


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['close_deactivate'] = 'Deactivate account';
$GLOBALS['TL_LANG']['tl_module']['close_delete']     = 'Irrevocably delete account';

?>