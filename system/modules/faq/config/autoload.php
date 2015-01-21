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
	// Models
	'Contao\FaqCategoryModel' => 'system/modules/faq/models/FaqCategoryModel.php',
	'Contao\FaqModel'         => 'system/modules/faq/models/FaqModel.php',

	// Modules
	'Contao\ModuleFaq'        => 'system/modules/faq/modules/ModuleFaq.php',
	'Contao\ModuleFaqList'    => 'system/modules/faq/modules/ModuleFaqList.php',
	'Contao\ModuleFaqPage'    => 'system/modules/faq/modules/ModuleFaqPage.php',
	'Contao\ModuleFaqReader'  => 'system/modules/faq/modules/ModuleFaqReader.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_faqlist'   => 'system/modules/faq/templates/modules',
	'mod_faqpage'   => 'system/modules/faq/templates/modules',
	'mod_faqreader' => 'system/modules/faq/templates/modules',
));
