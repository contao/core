<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Store the microtime
 */
define('TL_START', microtime(true));


/**
 * Define the root path to the Contao installation
 */
define('TL_ROOT', dirname(__DIR__));


/**
 * Define the login status constants in the back end (see #4099, #5279)
 */
if (TL_MODE == 'BE')
{
	define('BE_USER_LOGGED_IN', false);
	define('FE_USER_LOGGED_IN', false);
}


/**
 * Include the helpers
 */
require TL_ROOT . '/system/helper/functions.php';
require TL_ROOT . '/system/config/constants.php';
require TL_ROOT . '/system/helper/interface.php';
require TL_ROOT . '/system/helper/exception.php';


/**
 * Try to disable the PHPSESSID
 */
@ini_set('session.use_trans_sid', 0);
@ini_set('session.cookie_httponly', true);


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
 * Register the class and template loader
 */
require TL_ROOT . '/system/modules/core/library/Contao/ClassLoader.php';
class_alias('Contao\\ClassLoader', 'ClassLoader');

require TL_ROOT . '/system/modules/core/library/Contao/TemplateLoader.php';
class_alias('Contao\\TemplateLoader', 'TemplateLoader');

ClassLoader::scanAndRegister(); // config/autoload.php


/**
 * Register the SwiftMailer and SimplePie autoloaders
 */
require_once TL_ROOT . '/system/vendor/swiftmailer/classes/Swift.php';

Swift::registerAutoload(function() {
	require TL_ROOT . '/system/vendor/swiftmailer/swift_init.php';
});

require_once TL_ROOT . '/system/vendor/simplepie/autoloader.php';


/**
 * Define the relative path to the installation (see #5339)
 */
if (file_exists(TL_ROOT . '/system/config/pathconfig.php'))
{
	define('TL_PATH', include TL_ROOT . '/system/config/pathconfig.php');
}
elseif (TL_MODE == 'BE')
{
	define('TL_PATH', preg_replace('/\/contao\/[^\/]*$/i', '', Environment::get('requestUri')));
}
else
{
	define('TL_PATH', null); // cannot be reliably determined
}

$GLOBALS['TL_CONFIG']['websitePath'] = TL_PATH; // backwards compatibility


/**
 * Start the session
 */
@session_set_cookie_params(0, (TL_PATH ?: '/')); // see #5339
@session_start();


/**
 * Get the Config instance
 */
$objConfig = Config::getInstance();


/**
 * Initialize the Input and RequestToken class
 */
Input::initialize();
RequestToken::initialize();


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
 * Store the relative path
 *
 * Only store this value if the temp directory is writable and the local
 * configuration file exists, otherwise it will initialize a Files object and
 * prevent the install tool from loading the Safe Mode Hack (see #3215).
 */
if (TL_PATH !== null && !file_exists(TL_ROOT . '/system/config/pathconfig.php'))
{
	if (is_writable(TL_ROOT . '/system/tmp') && file_exists(TL_ROOT . '/system/config/localconfig.php'))
	{
		try
		{
			$objFile = new File('system/config/pathconfig.php', true);
			$objFile->write("<?php\n\n// Relative path to the installation\nreturn '" . TL_PATH . "';\n");
			$objFile->close();
		}
		catch (Exception $e)
		{
			log_message($e->getMessage());
		}
	}
}


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
if (Input::post('language'))
{
	$GLOBALS['TL_LANGUAGE'] = Input::post('language');
}
elseif (isset($_SESSION['TL_LANGUAGE']))
{
	$GLOBALS['TL_LANGUAGE'] = $_SESSION['TL_LANGUAGE'];
}
else
{
	foreach (Environment::get('httpAcceptLanguage') as $v)
	{
		if (is_dir(TL_ROOT . '/system/modules/core/languages/' . $v))
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
if ($_POST && !RequestToken::validate(Input::post('REQUEST_TOKEN')))
{
	// Force JavaScript redirect upon Ajax requests (IE requires absolute link)
	if (Environment::get('isAjaxRequest'))
	{
		echo '<script>location.replace("' . Environment::get('base') . 'contao/")</script>';
	}
	else
	{
		// Send an error 400 header if it is not an Ajax request
		header('HTTP/1.1 400 Bad Request');

		if (file_exists(TL_ROOT . '/templates/be_referer.html5'))
		{
			include TL_ROOT . '/templates/be_referer.html5';
		}
		elseif (file_exists(TL_ROOT . '/system/modules/core/templates/be_referer.html5'))
		{
			include TL_ROOT . '/system/modules/core/templates/be_referer.html5';
		}
		else
		{
			echo 'Invalid request token. Please <a href="javascript:window.location.href=window.location.href">go back</a> and try again.';
		}
	}

	exit;
}
