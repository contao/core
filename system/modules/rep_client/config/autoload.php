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
 * @package    Rep_client
 * @license    LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Rep_client
	'RepositoryBackendModule' => 'system/modules/rep_client/RepositoryBackendModule.php',
	'RepositoryBackendTheme'  => 'system/modules/rep_client/RepositoryBackendTheme.php',
	'RepositoryCatalog'       => 'system/modules/rep_client/RepositoryCatalog.php',
	'RepositoryManager'       => 'system/modules/rep_client/RepositoryManager.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'repository_catlist' => 'system/modules/rep_client/templates',
	'repository_catview' => 'system/modules/rep_client/templates',
	'repository_mgredit' => 'system/modules/rep_client/templates',
	'repository_mgrinst' => 'system/modules/rep_client/templates',
	'repository_mgrlist' => 'system/modules/rep_client/templates',
	'repository_mgruist' => 'system/modules/rep_client/templates',
	'repository_mgrupdt' => 'system/modules/rep_client/templates',
	'repository_mgrupgd' => 'system/modules/rep_client/templates',
));
