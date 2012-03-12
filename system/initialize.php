<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    System
 * @license    LGPL
 */


/**
 * Store the microtime
 */
define('TL_START', microtime(true));


/**
 * Define the root path to the Contao installation
 */
define('TL_ROOT', dirname(dirname(__FILE__)));


/**
 * Include functions, constants and interfaces
 */
require TL_ROOT . '/system/helper/functions.php';
require TL_ROOT . '/system/config/constants.php';
require TL_ROOT . '/system/helper/interface.php';


/**
 * Try to disable PHPSESSID
 */
@ini_set('session.use_trans_sid', 0);


/**
 * Set the error and exception handler
 */
@set_error_handler('__error');
@set_exception_handler('__exception');


/**
 * Log PHP errors
 */
@ini_set('error_log', TL_ROOT . '/system/logs/error.log');


/**
 * Start the session
 */
@session_start();


/**
 * Register the class and template loader
 */
require TL_ROOT . '/system/library/Contao/ClassLoader.php';
class_alias('Contao\\ClassLoader', 'ClassLoader');

require TL_ROOT . '/system/library/Contao/TemplateLoader.php';
class_alias('Contao\\TemplateLoader', 'TemplateLoader');

ClassLoader::scanAndRegister(); // config/autoload.php


/**
 * Register the SwiftMailer autoloader
 */
require_once TL_ROOT . '/system/library/Swiftmailer/classes/Swift.php';
\Swift::registerAutoload(TL_ROOT . '/system/library/Swiftmailer/swift_init.php');


/**
 * Load the basic classes
 */
$objConfig = Config::getInstance();
$objEnvironment = Environment::getInstance();
$objInput = Input::getInstance();
$objToken = RequestToken::getInstance();


/**
 * Set error_reporting
 */
@ini_set('display_errors', ($GLOBALS['TL_CONFIG']['displayErrors'] ? 1 : 0));
error_reporting(($GLOBALS['TL_CONFIG']['displayErrors'] || $GLOBALS['TL_CONFIG']['logErrors']) ? E_ALL|E_STRICT : 0);


/**
 * Set the timezone
 */
@ini_set('date.timezone', $GLOBALS['TL_CONFIG']['timeZone']);
@date_default_timezone_set($GLOBALS['TL_CONFIG']['timeZone']);


/**
 * Define the relativ path to the Contao installation
 */
if ($GLOBALS['TL_CONFIG']['websitePath'] === null)
{
	$path = preg_replace('/\/contao\/[^\/]*$/i', '', $objEnvironment->requestUri);
	$path = preg_replace('/\/$/i', '', $path);

	try
	{
		$GLOBALS['TL_CONFIG']['websitePath'] = $path;

		// Only store this value if the temp directory is writable and the local configuration
		// file exists, otherwise it will initialize a Files object and prevent the install tool
		// from loading the Safe Mode Hack (see #3215).
		if (is_writable(TL_ROOT . '/system/tmp') && file_exists(TL_ROOT . '/system/config/localconfig.php'))
		{
			$objConfig->update("\$GLOBALS['TL_CONFIG']['websitePath']", $path);
		}
	}
	catch (Exception $e)
	{
		log_message($e->getMessage());
	}
}

define('TL_PATH', $GLOBALS['TL_CONFIG']['websitePath']);


/**
 * Set the mbstring encoding
 */
if (USE_MBSTRING && function_exists('mb_regex_encoding'))
{
	mb_regex_encoding($GLOBALS['TL_CONFIG']['characterSet']);
}


/**
 * Set the default language
 */
if ($objInput->post('language'))
{
	$GLOBALS['TL_LANGUAGE'] = $objInput->post('language');
}
elseif (isset($_SESSION['TL_LANGUAGE']))
{
	$GLOBALS['TL_LANGUAGE'] = $_SESSION['TL_LANGUAGE'];
}
else
{
	foreach ($objEnvironment->httpAcceptLanguage as $v)
	{
		if (is_dir(TL_ROOT . '/system/modules/backend/languages/' . $v))
		{
			$GLOBALS['TL_LANGUAGE'] = $v;
			$_SESSION['TL_LANGUAGE'] = $v;
			break;
		}
	}

	unset($v);
}


/**
 * Include the custom initialization file
 */
if (file_exists(TL_ROOT . '/system/config/initconfig.php'))
{
	include TL_ROOT . '/system/config/initconfig.php';
}


/**
 * Check the request token upon POST requests
 */
if ($_POST && !$GLOBALS['TL_CONFIG']['disableRefererCheck'] && !defined('BYPASS_TOKEN_CHECK'))
{
	// Exit if the token cannot be validated
	if (!$objToken->validate($objInput->post('REQUEST_TOKEN')))
	{
		// Force JavaScript redirect upon Ajax requests (IE requires absolute link)
		if ($objEnvironment->isAjaxRequest)
		{
			echo '<script>location.replace("' . $objEnvironment->base . 'contao/index.php")</script>';
		}
		else
		{
			// Send an error 400 header if it is not an Ajax request
			header('HTTP/1.1 400 Bad Request');

			if (file_exists(TL_ROOT . '/templates/be_referer.html5'))
			{
				include TL_ROOT . '/templates/be_referer.html5';
			}
			elseif (file_exists(TL_ROOT . '/system/modules/backend/templates/be_referer.html5'))
			{
				include TL_ROOT . '/system/modules/backend/templates/be_referer.html5';
			}
			else
			{
				echo 'Invalid request token. Please <a href="javascript:window.location.href=window.location.href">go back</a> and try again.';
			}
		}

		exit;
	}
}
