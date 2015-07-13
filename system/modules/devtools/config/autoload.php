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
	'Contao\ModuleAutoload'  => 'system/modules/devtools/modules/ModuleAutoload.php',
	'Contao\ModuleExtension' => 'system/modules/devtools/modules/ModuleExtension.php',
	'Contao\ModuleLabels'    => 'system/modules/devtools/modules/ModuleLabels.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'dev_autoload'   => 'system/modules/devtools/templates',
	'dev_beClass'    => 'system/modules/devtools/templates',
	'dev_beTemplate' => 'system/modules/devtools/templates',
	'dev_config'     => 'system/modules/devtools/templates',
	'dev_dca'        => 'system/modules/devtools/templates',
	'dev_default'    => 'system/modules/devtools/templates',
	'dev_extension'  => 'system/modules/devtools/templates',
	'dev_feClass'    => 'system/modules/devtools/templates',
	'dev_feDca'      => 'system/modules/devtools/templates',
	'dev_feTemplate' => 'system/modules/devtools/templates',
	'dev_htaccess'   => 'system/modules/devtools/templates',
	'dev_ini'        => 'system/modules/devtools/templates',
	'dev_labels'     => 'system/modules/devtools/templates',
	'dev_model'      => 'system/modules/devtools/templates',
	'dev_modules'    => 'system/modules/devtools/templates',
	'dev_table'      => 'system/modules/devtools/templates',
));
