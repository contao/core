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
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['subscribe']   = 'name,type,headline;nl_channels,nl_template;nl_subscribe;jumpTo;guests,protected;align,space,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['unsubscribe'] = 'name,type,headline;nl_channels,nl_template;nl_unsubscribe;jumpTo;guests,protected;align,space,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['nl_reader']   = 'name,type,headline;nl_channels,nl_includeCss;guests,protected;align,space,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['nl_list']     = 'name,type,headline;nl_channels;guests,protected;align,space,cssID';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['nl_channels'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nl_channels'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_newsletter_channel.title',
	'eval'                    => array('multiple'=>true, 'mandatory'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nl_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nl_template'],
	'default'                 => 'nl_simple',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => $this->getTemplateGroup('nl_')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nl_subscribe'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nl_subscribe'],
	'default'                 => $GLOBALS['TL_LANG']['tl_module']['text_subscribe'][1],
	'exclude'                 => true,
	'inputType'               => 'textarea',
	'eval'                    => array('style'=>'height:120px;', 'decodeEntities'=>true),
	'save_callback' => array
	(
		array('nl_module', 'getDefaultValue')
	)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nl_unsubscribe'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nl_unsubscribe'],
	'default'                 => $GLOBALS['TL_LANG']['tl_module']['text_unsubscribe'][1],
	'exclude'                 => true,
	'inputType'               => 'textarea',
	'eval'                    => array('style'=>'height:120px;', 'decodeEntities'=>true),
	'save_callback' => array
	(
		array('nl_module', 'getDefaultValue')
	)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nl_includeCss'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nl_includeCss'],
	'exclude'                 => true,
	'inputType'               => 'checkbox'
);


/**
 * Class nl_module
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class nl_module extends Backend
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