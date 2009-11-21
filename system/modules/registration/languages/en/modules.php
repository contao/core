<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Registration
 * @license    LGPL
 * @filesource
 */


/**
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['registration'] = array('User registration', 'Allow users to register on the website.');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['registration'] = array('Registration', 'generates a user registration form.');
$GLOBALS['TL_LANG']['FMD']['lostPassword'] = array('Lost password', 'generates a form to request a new password.');
$GLOBALS['TL_LANG']['FMD']['closeAccount'] = array('Close account', 'generates a form to delete a member account.');

?>