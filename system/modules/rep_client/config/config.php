<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');
/**
 * TYPOlight Repository :: Configuration file
 *
 * NOTE: this file was edited with tabs set to 4.
 * @package Repository
 * @copyright Copyright (C) 2008 by Peter Koch, IBK Software AG
 * @license See accompaning file LICENSE.txt
 */

/**
 * BACK END MODULES
 */
if (extension_loaded('soap')) {

	$GLOBALS['BE_MOD']['system']['repository_catalog'] = array(
		'callback'		=>	'RepositoryCatalog',
		'icon'			=>	RepositoryBackendTheme::image('catalog16'),
		'stylesheet'	=>	RepositoryBackendTheme::file('backend.css')
	);

	$GLOBALS['BE_MOD']['system']['repository_manager'] = array(
		'callback'		=>	'RepositoryManager',
		'icon'			=>	RepositoryBackendTheme::image('install16'),
		'stylesheet'	=>	RepositoryBackendTheme::file('backend.css')
	);

} // if
?>