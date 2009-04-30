<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');
/**
 * TYPOlight Repository :: Data container array for tl_settings
 *
 * NOTE: this file was edited with tabs set to 4.
 * @package Repository
 * @copyright Copyright (C) 2008 by Peter Koch, IBK Software AG
 * @license See accompaning file LICENSE.txt
 */

/**
 * Add to palette
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{repository_legend:hide},repository_wsdl';

/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['repository_wsdl'] = array(
	'label'		=>	&$GLOBALS['TL_LANG']['tl_settings']['repository_wsdl'],
	'inputType'	=>	'text',
	'eval'		=>	array('maxlength'=>255, 'rgxp'=>'url', 'tl_class'=>'long')
);

?>