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
 * @package    Newsletter
 * @license    LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Contao\\Newsletter'                => 'system/modules/newsletter/classes/Newsletter.php',

	// Models
	'Contao\\NewsletterChannelModel'    => 'system/modules/newsletter/models/NewsletterChannelModel.php',
	'Contao\\NewsletterModel'           => 'system/modules/newsletter/models/NewsletterModel.php',
	'Contao\\NewsletterRecipientsModel' => 'system/modules/newsletter/models/NewsletterRecipientsModel.php',

	// Modules
	'Contao\\ModuleNewsletterList'      => 'system/modules/newsletter/modules/ModuleNewsletterList.php',
	'Contao\\ModuleNewsletterReader'    => 'system/modules/newsletter/modules/ModuleNewsletterReader.php',
	'Contao\\ModuleSubscribe'           => 'system/modules/newsletter/modules/ModuleSubscribe.php',
	'Contao\\ModuleUnsubscribe'         => 'system/modules/newsletter/modules/ModuleUnsubscribe.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_newsletter'        => 'system/modules/newsletter/templates',
	'mod_newsletter_list'   => 'system/modules/newsletter/templates',
	'mod_newsletter_reader' => 'system/modules/newsletter/templates',
	'nl_default'            => 'system/modules/newsletter/templates',
));
