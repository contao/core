<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
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
	'icon'       => RepositoryBackendTheme::image('catalog16'),
	'stylesheet' => RepositoryBackendTheme::file('backend.css')
);

$GLOBALS['BE_MOD']['system']['repository_manager'] = array
(
	'callback'   => 'RepositoryManager',
	'icon'       => RepositoryBackendTheme::image('install16'),
	'stylesheet' => RepositoryBackendTheme::file('backend.css')
);
