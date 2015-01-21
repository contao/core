<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Initialize system
 */
define('TL_MODE', 'BE');
require '../../../../system/initialize.php';


/**
 * Include the library class
 */
require 'typolib.php';
$objLib = new typolib();


/**
 * Create the image list
 */
header('Content-Type: text/javascript'); ?>
var tinyMCEImageList = [<?php echo substr($objLib->createImageList(), 0, -1); ?>];