<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

// Set the script name
define('TL_SCRIPT', 'system/cron/cron.php');

// Initialize the system
define('TL_MODE', 'FE');
require dirname(__DIR__) . '/initialize.php';

// Run the controller
$controller = new FrontendCron;
$controller->run();
