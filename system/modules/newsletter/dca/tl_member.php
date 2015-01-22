<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Add palette
 */
$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('assignDir;', 'assignDir;{newsletter_legend:hide},newsletter;', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);


/**
 * Add load callback
 */
$GLOBALS['TL_DCA']['tl_member']['config']['onload_callback'][] = array('Newsletter', 'updateAccount');


/**
 * Add field
 */
$GLOBALS['TL_DCA']['tl_member']['fields']['newsletter'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_member']['newsletter'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options_callback'        => array('Newsletter', 'getNewsletters'),
	'eval'                    => array('multiple'=>true, 'feEditable'=>true, 'feGroup'=>'newsletter'),
	'save_callback' => array
	(
		array('Newsletter', 'synchronize')
	),
	'sql'                     => "blob NULL"
);
