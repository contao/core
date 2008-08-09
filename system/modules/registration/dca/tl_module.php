<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Registration
 * @license    LGPL
 * @filesource
 */


/**
 * Add selectors to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'reg_assignDir';
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'reg_activate';


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['registration'] = 'name,type,headline;editable,newsletters;jumpTo;memberTpl,disableCaptcha;reg_groups,reg_allowLogin;reg_assignDir;reg_activate;guests,protected;align,space,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['lostPassword'] = 'name,type,headline;jumpTo;reg_skipName,disableCaptcha;reg_password;reg_jumpTo;guests,protected;align,space,cssID';


/**
 * Add subpalettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['reg_assignDir'] = 'reg_homeDir';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['reg_activate'] = 'reg_text,reg_jumpTo';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['reg_groups'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['reg_groups'],
	'exclude'       => true,
	'inputType'     => 'checkbox',
	'foreignKey'    => 'tl_member_group.name',
	'eval'          => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['reg_allowLogin'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['reg_allowLogin'],
	'exclude'       => true,
	'inputType'     => 'checkbox'
);

$GLOBALS['TL_DCA']['tl_module']['fields']['reg_assignDir'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['reg_assignDir'],
	'exclude'       => true,
	'inputType'     => 'checkbox',
	'eval'          => array('submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['reg_homeDir'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['reg_homeDir'],
	'exclude'       => true,
	'inputType'     => 'fileTree',
	'eval'          => array('fieldType'=>'radio')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['reg_activate'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['reg_activate'],
	'exclude'       => true,
	'inputType'     => 'checkbox',
	'eval'          => array('submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['reg_text'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['reg_text'],
	'default'       => (is_array($GLOBALS['TL_LANG']['tl_module']['emailText']) ? $GLOBALS['TL_LANG']['tl_module']['emailText'][1] : $GLOBALS['TL_LANG']['tl_module']['emailText']),
	'exclude'       => true,
	'inputType'     => 'textarea',
	'eval'          => array('style'=>'height:120px;', 'decodeEntities'=>true),
	'save_callback' => array
	(
		array('tl_module_registration', 'getDefaultValue')
	)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['reg_password'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['reg_password'],
	'default'       => (is_array($GLOBALS['TL_LANG']['tl_module']['passwordText']) ? $GLOBALS['TL_LANG']['tl_module']['passwordText'][1] : $GLOBALS['TL_LANG']['tl_module']['passwordText']),
	'exclude'       => true,
	'inputType'     => 'textarea',
	'eval'          => array('style'=>'height:120px;', 'decodeEntities'=>true),
	'save_callback' => array
	(
		array('tl_module_registration', 'getDefaultValue')
	)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['reg_skipName'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['reg_skipName'],
	'exclude'       => true,
	'inputType'     => 'checkbox'
);

$GLOBALS['TL_DCA']['tl_module']['fields']['reg_jumpTo'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['reg_jumpTo'],
	'exclude'       => true,
	'inputType'     => 'pageTree',
	'eval'          => array('fieldType'=>'radio')
);


/**
 * Class tl_module_registration
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_module_registration extends Backend
{

	/**
	 * Load the default value if the text is empty
	 * @param string
	 * @param object
	 * @return string
	 */
	public function getDefaultValue($varValue, DataContainer $dc)
	{
		if (!strlen(trim($varValue)))
		{
			$varValue = $GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['default'];
		}

		return $varValue;
	}
}

?>