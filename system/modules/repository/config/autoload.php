<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @license    LGPL
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
	'RepositorySettings'      => 'system/modules/repository/classes/RepositorySettings.php',
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
