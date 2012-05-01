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
	// Models
	'Contao\\FaqCategoryModel' => 'system/modules/faq/models/FaqCategoryModel.php',
	'Contao\\FaqModel'         => 'system/modules/faq/models/FaqModel.php',

	// Modules
	'Contao\\ModuleFaq'        => 'system/modules/faq/modules/ModuleFaq.php',
	'Contao\\ModuleFaqList'    => 'system/modules/faq/modules/ModuleFaqList.php',
	'Contao\\ModuleFaqPage'    => 'system/modules/faq/modules/ModuleFaqPage.php',
	'Contao\\ModuleFaqReader'  => 'system/modules/faq/modules/ModuleFaqReader.php',
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
