<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Newsletter
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Contao\Newsletter'                => 'system/modules/newsletter/classes/Newsletter.php',

	// Models
	'Contao\NewsletterChannelModel'    => 'system/modules/newsletter/models/NewsletterChannelModel.php',
	'Contao\NewsletterModel'           => 'system/modules/newsletter/models/NewsletterModel.php',
	'Contao\NewsletterRecipientsModel' => 'system/modules/newsletter/models/NewsletterRecipientsModel.php',

	// Modules
	'Contao\ModuleNewsletterList'      => 'system/modules/newsletter/modules/ModuleNewsletterList.php',
	'Contao\ModuleNewsletterReader'    => 'system/modules/newsletter/modules/ModuleNewsletterReader.php',
	'Contao\ModuleSubscribe'           => 'system/modules/newsletter/modules/ModuleSubscribe.php',
	'Contao\ModuleUnsubscribe'         => 'system/modules/newsletter/modules/ModuleUnsubscribe.php',
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
