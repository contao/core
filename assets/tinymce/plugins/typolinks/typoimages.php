<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
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
 * Include library class
 */
require 'typolib.php';


/**
 * Create image list
 */
$objLib = new typolib();
header('Content-Type: text/javascript'); ?>

var tinyMCEImageList = new Array(
<?php echo substr($objLib->createImageList(), 0, -2); ?> 
);