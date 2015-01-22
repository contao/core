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
	'mod_newsletter'        => 'system/modules/newsletter/templates/modules',
	'mod_newsletter_list'   => 'system/modules/newsletter/templates/modules',
	'mod_newsletter_reader' => 'system/modules/newsletter/templates/modules',
	'nl_default'            => 'system/modules/newsletter/templates/newsletter',
));
