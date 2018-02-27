<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
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
