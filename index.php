<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

// Set the script name
define('TL_SCRIPT', 'index.php');

// Initialize the system
define('TL_MODE', 'FE');
require __DIR__ . '/system/initialize.php';

// Run the controller
$controller = new FrontendIndex;
$controller->run();
