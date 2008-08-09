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
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['newsletter'] = array('Newsletters', 'This module allows you to manage newsletters.');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['newsletter']  = 'Newsletter';
$GLOBALS['TL_LANG']['FMD']['subscribe']   = array('Subscribe', 'This module allows front end users to subscribe to certain channels.');
$GLOBALS['TL_LANG']['FMD']['unsubscribe'] = array('Unsubscribe', 'This module allows front end users to unsubscribe from certain channels.');
$GLOBALS['TL_LANG']['FMD']['nl_reader']   = array('Newsletter reader', 'This module shows a single newsletter.');
$GLOBALS['TL_LANG']['FMD']['nl_list']     = array('Newsletter list', 'This module lists the newsletters of one or more channels.');

?>