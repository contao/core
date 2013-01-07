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
