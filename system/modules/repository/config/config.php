<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   Repository
 * @author    Peter Koch, IBK Software AG
 * @license   See accompaning file LICENSE.txt
 * @copyright Peter Koch 2008-2010
 */


/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['system']['repository_catalog'] = array
(
	'callback'   => 'RepositoryCatalog',
	'icon'       => 'system/modules/repository/themes/default/images/catalog16.png',
	'stylesheet' => 'system/modules/repository/themes/default/backend.css'
);

$GLOBALS['BE_MOD']['system']['repository_manager'] = array
(
	'callback'   => 'RepositoryManager',
	'icon'       => 'system/modules/repository/themes/default/images/install16.png',
	'stylesheet' => 'system/modules/repository/themes/default/backend.css'
);
