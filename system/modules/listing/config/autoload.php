<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Listing
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Modules
	'Contao\ModuleListing' => 'system/modules/listing/modules/ModuleListing.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'info_default' => 'system/modules/listing/templates/info',
	'list_default' => 'system/modules/listing/templates/listing',
));
