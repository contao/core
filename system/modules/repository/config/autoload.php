<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Repository
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Repository'              => 'system/modules/repository/classes/Repository.php',
	'RepositoryBackendModule' => 'system/modules/repository/classes/RepositoryBackendModule.php',
	'RepositoryBackendTheme'  => 'system/modules/repository/classes/RepositoryBackendTheme.php',
	'RepositoryCatalog'       => 'system/modules/repository/classes/RepositoryCatalog.php',
	'RepositoryManager'       => 'system/modules/repository/classes/RepositoryManager.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'repository_catlist' => 'system/modules/repository/templates',
	'repository_catview' => 'system/modules/repository/templates',
	'repository_mgredit' => 'system/modules/repository/templates',
	'repository_mgrinst' => 'system/modules/repository/templates',
	'repository_mgrlist' => 'system/modules/repository/templates',
	'repository_mgruist' => 'system/modules/repository/templates',
	'repository_mgrupdt' => 'system/modules/repository/templates',
	'repository_mgrupgd' => 'system/modules/repository/templates',
));
