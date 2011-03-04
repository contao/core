<?php

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
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Define root path to Contao installation
 */
define('TL_ROOT', dirname(dirname(__FILE__)));


/**
 * Include functions, constants and interfaces
 */
require(TL_ROOT . '/system/functions.php');
require(TL_ROOT . '/system/constants.php');
require(TL_ROOT . '/system/interface.php');


/**
 * Try to disable PHPSESSID
 */
@ini_set('session.use_trans_sid', 0);


/**
 * Set error and exception handler
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
 * Load basic classes
 */
$objConfig = Config::getInstance();
$objEnvironment = Environment::getInstance();
$objInput = Input::getInstance();


/**
 * Set error_reporting
 */
@ini_set('display_errors', ($GLOBALS['TL_CONFIG']['displayErrors'] ? 1 : 0));
error_reporting(($GLOBALS['TL_CONFIG']['displayErrors'] ? E_ALL|E_STRICT : 0));


/**
 * Set timezone
 */
@ini_set('date.timezone', $GLOBALS['TL_CONFIG']['timeZone']);
@date_default_timezone_set($GLOBALS['TL_CONFIG']['timeZone']);


/**
 * Define relativ path to Contao installation
 */
if (is_null($GLOBALS['TL_CONFIG']['websitePath']))
{
	$path = preg_replace('/\/contao\/[^\/]*$/i', '', $objEnvironment->requestUri);
	$path = preg_replace('/\/$/i', '', $path);

	try
	{
		$GLOBALS['TL_CONFIG']['websitePath'] = $path;
		$objConfig->update("\$GLOBALS['TL_CONFIG']['websitePath']", $path);
	}
	catch (Exception $e)
	{
		log_message($e->getMessage());
	}
}

define('TL_PATH', $GLOBALS['TL_CONFIG']['websitePath']);


/**
 * Set mbstring encoding
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
@include(TL_ROOT . '/system/config/initconfig.php');


/**
 * Check referer address if there are $_POST variables
 */
if ($_POST && !$GLOBALS['TL_CONFIG']['disableRefererCheck'])
{
	$self = parse_url($objEnvironment->url);
	$referer = parse_url($objEnvironment->httpReferer);

	if (!strlen($referer['host']) || $referer['host'] != $self['host'])
	{
		header('HTTP/1.1 400 Bad Request');

		if (file_exists(TL_ROOT . '/system/modules/backend/templates/be_referer.tpl'))
		{
			include(TL_ROOT . '/system/modules/backend/templates/be_referer.tpl');
			exit;
		}

		echo sprintf('The current host address (%s) does not match the current referer host address (%s).', $self['host'], $referer['host']);
		exit;
	}
}


/**
 * Include file runonce.php if it exists
 */
if (file_exists(TL_ROOT . '/system/runonce.php'))
{
	try
	{
		include(TL_ROOT . '/system/runonce.php');
	}
	catch (Exception $e) {}

	$objFiles = Files::getInstance();

	if (!$objFiles->delete('system/runonce.php'))
	{
		throw new Exception('The system/runonce.php file cannot be deleted. Please remove the file manually and correct the file permission settings on your server.');
	}
}

?>