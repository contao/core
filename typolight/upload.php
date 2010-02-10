<?php

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
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Check parameters
 */
if (count($_GET) != 6 || empty($_POST))
{
	header('HTTP/1.1 400 Bad Request');
	die('Illegal request');
}

if (!array_key_exists('pid', $_GET) || !array_key_exists('id', $_GET))
{
	header('HTTP/1.1 400 Bad Request');
	die('Required parameters missing');
}

if ($_GET['do'] != 'files' || $_GET['act'] != 'move' || $_GET['mode'] != 2)
{
	header('HTTP/1.1 400 Bad Request');
	die('Unexpected arguments');
}


/**
 * Set the session ID
 */
session_id(filter_input(INPUT_GET, session_name(), FILTER_SANITIZE_STRING));


/**
 * Set the referer host
 */
$_SERVER['HTTP_REFERER'] = 'http://' . $_SERVER['SERVER_NAME'];


/**
 * FancyUpload sends the cookie data via POST
 */
$_COOKIE['BE_USER_AUTH'] = filter_input(INPUT_POST, 'BE_USER_AUTH', FILTER_SANITIZE_STRING);


/**
 * Load TYPOlight
 */
require('main.php');

?>