<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Faq
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Add back end modules
 */
array_insert($GLOBALS['BE_MOD']['content'], 2, array
(
	'faq' => array
	(
		'tables' => array('tl_faq_category', 'tl_faq'),
		'icon'   => 'system/modules/faq/assets/icon.gif'
	)
));


/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD'], 3, array
(
	'faq' => array
	(
		'faqlist'   => 'ModuleFaqList',
		'faqreader' => 'ModuleFaqReader',
		'faqpage'   => 'ModuleFaqPage'
	)
));


/**
 * Register hooks
 */
$GLOBALS['TL_HOOKS']['getSearchablePages'][] = array('ModuleFaq', 'getSearchablePages');


/**
 * Add permissions
 */
$GLOBALS['TL_PERMISSIONS'][] = 'faqs';
$GLOBALS['TL_PERMISSIONS'][] = 'faqp';
