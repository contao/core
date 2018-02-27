<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

// Set the script name
define('TL_SCRIPT', 'share/index.php');

// Initialize the system
define('TL_MODE', 'FE');
require dirname(__DIR__) . '/system/initialize.php';

// Run the controller
$controller = new FrontendShare;
$controller->run();
