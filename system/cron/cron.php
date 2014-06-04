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
define('TL_SCRIPT', 'system/cron/cron.php');


/**
 * Initialize the system
 */
define('TL_MODE', 'FE');
require dirname(__DIR__) . '/initialize.php';


/**
 * Instantiate the controller
 */
$objIndex = new FrontendCron();
$objIndex->run();
