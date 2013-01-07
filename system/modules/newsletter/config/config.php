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
 * Back end modules
 */
array_insert($GLOBALS['BE_MOD']['content'], 4, array
(
	'newsletter' => array
	(
		'tables'     => array('tl_newsletter_channel', 'tl_newsletter', 'tl_newsletter_recipients'),
		'send'       => array('Newsletter', 'send'),
		'import'     => array('Newsletter', 'importRecipients'),
		'icon'       => 'system/modules/newsletter/assets/icon.gif',
		'stylesheet' => 'system/modules/newsletter/assets/style.css'
	)
));


/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD'], 4, array
(
	'newsletter' => array
	(
		'subscribe'   => 'ModuleSubscribe',
		'unsubscribe' => 'ModuleUnsubscribe',
		'nl_list'     => 'ModuleNewsletterList',
		'nl_reader'   => 'ModuleNewsletterReader'
	)
));


/**
 * Register hooks
 */
$GLOBALS['TL_HOOKS']['createNewUser'][] = array('Newsletter', 'createNewUser');
$GLOBALS['TL_HOOKS']['activateAccount'][] = array('Newsletter', 'activateAccount');
$GLOBALS['TL_HOOKS']['getSearchablePages'][] = array('Newsletter', 'getSearchablePages');
$GLOBALS['TL_HOOKS']['closeAccount'][] = array('Newsletter', 'removeSubscriptions');


/**
 * Add permissions
 */
$GLOBALS['TL_PERMISSIONS'][] = 'newsletters';
$GLOBALS['TL_PERMISSIONS'][] = 'newsletterp';
