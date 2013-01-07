<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Devtools
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['devtools'] = array
(
	'autoload' => array
	(
		'callback'   => 'ModuleAutoload',
		'icon'       => 'system/modules/devtools/assets/autoload.gif'
	),
	'extension' => array
	(
		'tables'     => array('tl_extension'),
		'create'     => array('ModuleExtension', 'generate'),
		'icon'       => 'system/modules/devtools/assets/extension.gif'
	),
	'labels' => array
	(
		'callback'   => 'ModuleLabels',
		'icon'       => 'system/modules/devtools/assets/labels.gif',
		'stylesheet' => 'system/modules/devtools/assets/labels.css'
	)
);
