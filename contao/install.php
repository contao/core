<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

// Set the script name
define('TL_SCRIPT', 'contao/install.php');

// Initialize the system
define('TL_MODE', 'BE');
require dirname(__DIR__) . '/system/initialize.php';

// Show error messages
@ini_set('display_errors', 1);
error_reporting(Config::get('errorReporting'));

// Run the controller
$controller = new BackendInstall;
$controller->run();
