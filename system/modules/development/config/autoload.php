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
 * @package    Development
 * @license    LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'Contao\\ModuleAutoload'  => 'system/modules/development/ModuleAutoload.php',
	'Contao\\ModuleExtension' => 'system/modules/development/ModuleExtension.php',
	'Contao\\ModuleLabels'    => 'system/modules/development/ModuleLabels.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'dev_autoload'   => 'system/modules/development/templates',
	'dev_beClass'    => 'system/modules/development/templates',
	'dev_beTemplate' => 'system/modules/development/templates',
	'dev_config'     => 'system/modules/development/templates',
	'dev_dca'        => 'system/modules/development/templates',
	'dev_default'    => 'system/modules/development/templates',
	'dev_extension'  => 'system/modules/development/templates',
	'dev_feClass'    => 'system/modules/development/templates',
	'dev_feTemplate' => 'system/modules/development/templates',
	'dev_htaccess'   => 'system/modules/development/templates',
	'dev_labels'     => 'system/modules/development/templates',
	'dev_model'      => 'system/modules/development/templates',
	'dev_modules'    => 'system/modules/development/templates',
	'dev_table'      => 'system/modules/development/templates',
));
