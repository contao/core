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
 * @package    PunBridge
 * @license    LGPL
 * @filesource
 */


/**
 * Uncomment this line to use the forum bridge
 */
//define('PUN', 1);


/**
 * PunBB configuration
 * 
 * Enter the configuration values of your punBB installation. They
 * are stored in the punBB configuration file (config.php).
 */
$GLOBALS['TL_CONFIG']['PUN']['db_name']       = 'typoforum';
$GLOBALS['TL_CONFIG']['PUN']['db_prefix']     = 'punbb_';
$GLOBALS['TL_CONFIG']['PUN']['cookie_name']   = 'punbb_cookie';
$GLOBALS['TL_CONFIG']['PUN']['cookie_domain'] = '';
$GLOBALS['TL_CONFIG']['PUN']['cookie_path']   = '/';
$GLOBALS['TL_CONFIG']['PUN']['cookie_seed']   = 'bb0e5fc8';
$GLOBALS['TL_CONFIG']['PUN']['cookie_secure'] = 0;


/**
 * Register some hooks (DO NOT CHANGE!)
 */
if (defined('PUN'))
{
	$GLOBALS['TL_HOOKS']['postLogin'][] = array('PunBridge', 'login');
	$GLOBALS['TL_HOOKS']['postLogout'][] = array('PunBridge', 'logout');
	$GLOBALS['TL_HOOKS']['setNewPassword'][] = array('PunBridge', 'password');
	$GLOBALS['TL_HOOKS']['activateAccount'][] = array('PunBridge', 'activate');
}

?>