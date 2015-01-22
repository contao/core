<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Add content element
 */
$GLOBALS['TL_CTE']['includes']['comments'] = 'ContentComments';


/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['application']['comments'] = 'ModuleComments';


/**
 * Back end modules
 */
array_insert($GLOBALS['BE_MOD']['content'], 5, array
(
	'comments' => array
	(
		'tables'     => array('tl_comments'),
		'icon'       => 'system/modules/comments/assets/icon.gif',
		'stylesheet' => 'system/modules/comments/assets/style.css'
	)
));
