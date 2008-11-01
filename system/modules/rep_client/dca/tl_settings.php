<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');
/**
 * TYPOlight Repository :: Data container array for tl_settings
 *
 * NOTE: this file was edited with tabs set to 4.
 * @package Repository
 * @copyright Copyright (C) 2008 by Peter Koch, IBK Software AG
 * @license See accompaning file LICENSE.txt
 */

if (extension_loaded('soap')) {

	/**
	 * Add to palette
	 */
	$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';repository_languages,repository_listsize';

	/**
	 * Add fields
	 */
	$GLOBALS['TL_DCA']['tl_settings']['fields']['repository_languages'] = array(
		'label'		=>	&$GLOBALS['TL_LANG']['tl_settings']['repository_languages'],
		'exclude'	=>	true,
		'inputType'	=>	'text',
		'eval'		=>	array('maxlength'=>255, 'nospace'=>true)
	);

	$GLOBALS['TL_DCA']['tl_settings']['fields']['repository_listsize'] = array(
		'label'		=>	&$GLOBALS['TL_LANG']['tl_settings']['repository_listsize'],
		'default'	=>	'10',
		'exclude'	=>	true,
		'inputType'	=>	'text',
		'eval'		=>	array('rgxp'=>'digit', 'maxlength'=>6)
	);

} // if
?>