<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
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
