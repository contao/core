<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Faq
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
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
	'mod_faqlist'   => 'system/modules/faq/templates',
	'mod_faqpage'   => 'system/modules/faq/templates',
	'mod_faqreader' => 'system/modules/faq/templates',
));
