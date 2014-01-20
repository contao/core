<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Typolinks
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
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