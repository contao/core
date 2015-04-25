<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   Repository
 * @author    Peter Koch, IBK Software AG
 * @license   See accompaning file LICENSE.txt
 * @copyright Peter Koch 2008-2010
 */


/**
 * Add to palette
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{repository_legend:hide},repository_wsdl,repository_languages,repository_listsize,repository_unsafe_catalog';


/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['repository_wsdl'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['repository_wsdl'],
	'inputType'	=> 'text',
	'eval'		=> array('maxlength'=>255, 'rgxp'=>'url', 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['repository_languages'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['repository_languages'],
	'inputType'	=> 'text',
	'eval'		=> array('maxlength'=>255, 'nospace'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['repository_listsize'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['repository_listsize'],
	'default'	=> '10',
	'inputType'	=> 'text',
	'eval'		=> array('rgxp'=>'natural', 'maxlength'=>6, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['repository_unsafe_catalog'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['repository_unsafe_catalog'],
	'inputType'	=> 'checkbox',
	'eval'      => array('tl_class'=>'w50 m12')
);