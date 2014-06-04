<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Set the script name
 */
define('TL_SCRIPT', 'index.php');


/**
 * Initialize the system
 */
define('TL_MODE', 'FE');
require __DIR__ . '/system/initialize.php';


/**
 * Instantiate the controller
 */
$objIndex = new FrontendIndex();
$objIndex->run();
