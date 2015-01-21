<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
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
