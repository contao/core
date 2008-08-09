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
 * @package    Registration
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['reg_groups']     = array('Member groups', 'Please assign the user to one or more groups.');
$GLOBALS['TL_LANG']['tl_module']['reg_allowLogin'] = array('Allow login', 'If you choose this option, the new user will be able to login with his username and password.');
$GLOBALS['TL_LANG']['tl_module']['reg_assignDir']  = array('Create home directory', 'Choose this option to automatically create a home directory from the username.');
$GLOBALS['TL_LANG']['tl_module']['reg_homeDir']    = array('Home directory', 'Please select the parent folder of the user\'s home directory.');
$GLOBALS['TL_LANG']['tl_module']['reg_activate']   = array('Send activation e-mail', 'Choose this option to send an account activation e-mail to the registered user.');
$GLOBALS['TL_LANG']['tl_module']['reg_text']       = array('Activation e-mail', 'Please enter the text of the activation e-mail. You can use any input field as wildcard (e.g. <em>##lastname##</em>) as well as <em>##domain##</em> (current domain name) and <em>##link##</em> (activation link).');
$GLOBALS['TL_LANG']['tl_module']['reg_password']   = array('Confirmation e-mail', 'Please enter the text of the confirmation e-mail. You can use any user property as wildcard (e.g. <em>##lastname##</em>) as well as <em>##domain##</em> (current domain name) and <em>##link##</em> (activation link).');
$GLOBALS['TL_LANG']['tl_module']['reg_skipName']   = array('Skip username', 'Do not require the username to request a new password.');
$GLOBALS['TL_LANG']['tl_module']['reg_jumpTo']     = array('Activation jump to page', 'Please select the page to which users will be redirected after their account has been activated.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['emailText']    = array('Your registration on %s', "Thank you for your registration on ##domain##.\n\nPlease click ##link## to complete your registration and to activate your account. If you did not request an account, please ignore this e-mail.\n");
$GLOBALS['TL_LANG']['tl_module']['passwordText'] = array('Your password request on %s', "You have requested a new password for ##domain##.\n\nPlease click ##link## to enter the new password. If you did not request this e-mail, please contact the administrator of the website.\n");

?>