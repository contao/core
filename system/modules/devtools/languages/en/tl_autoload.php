<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Devtools
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_merge']['headline']       = 'Automatically create the autoload.php files';
$GLOBALS['TL_LANG']['tl_merge']['emptySelection'] = 'Please select at least one module!';
$GLOBALS['TL_LANG']['tl_merge']['autoloadExists'] = 'Module "%s" has an autoload.php file already. Do you want to override?';
$GLOBALS['TL_LANG']['tl_merge']['available']      = 'Available modules';
$GLOBALS['TL_LANG']['tl_merge']['options']        = 'Options';
$GLOBALS['TL_LANG']['tl_merge']['override']       = 'Override existing autoload files';
$GLOBALS['TL_LANG']['tl_merge']['ide_compat']     = 'Update the IDE compatibility file';
$GLOBALS['TL_LANG']['tl_merge']['explain']        = 'Here you can automatically create the <em>config/autoload.php</em> files required by Contao 3, which add all classes and templates to the autoloader. If the <em>namespace</em> command is found in a PHP class, the namespace will be added as well.';
