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
 * Back end modules
 */
$GLOBALS['BE_MOD']['devtools']['extension'] = array
(
		'tables'     => array('tl_extension'),
		'create'     => array('ModuleExtension', 'generate'),
		'icon'       => 'system/modules/devtools/html/extension.gif'
);
$GLOBALS['BE_MOD']['devtools']['labels'] = array
(
		'callback'   => 'ModuleLabels',
		'icon'       => 'system/modules/devtools/html/labels.gif',
		'stylesheet' => 'system/modules/devtools/html/labels.css'
);
$GLOBALS['BE_MOD']['devtools']['autoload'] = array
(
	'callback'   => 'ModuleAutoload',
	'icon'       => 'system/modules/devtools/html/autoload.gif'
);

