<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

// Set the script name
define('TL_SCRIPT', 'contao/password.php');

// Initialize the system
define('TL_MODE', 'BE');
require dirname(__DIR__) . '/system/initialize.php';

// Run the controller
$controller = new BackendPassword;
$controller->run();
