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
 * @package    Comments
 * @license    LGPL
 * @filesource
 */


/**
 * Add palette to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['comments'] = 'type,headline;com_moderate,com_bbcode,com_disableCaptcha;com_perPage,com_order,com_template;guests,protected;align,space,cssID';


/**
 * Add fields to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['com_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['com_template'],
	'default'                 => 'com_default',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => $this->getTemplateGroup('com_')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['com_order'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['com_order'],
	'default'                 => 'ascending',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('ascending', 'descending'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_content']
);

$GLOBALS['TL_DCA']['tl_content']['fields']['com_perPage'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['com_perPage'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('rgxp'=>'digit')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['com_moderate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['com_moderate'],
	'exclude'                 => true,
	'inputType'               => 'checkbox'
);

$GLOBALS['TL_DCA']['tl_content']['fields']['com_bbcode'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['com_bbcode'],
	'exclude'                 => true,
	'inputType'               => 'checkbox'
);

$GLOBALS['TL_DCA']['tl_content']['fields']['com_disableCaptcha'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['com_disableCaptcha'],
	'exclude'                 => true,
	'inputType'               => 'checkbox'
);

?>