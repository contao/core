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
 * @package    DfGallery
 * @license    LGPL
 * @filesource
 */


/**
 * Add palette
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['dfGallery'] = 'type,headline;singleSRC;dfTitle,dfTemplate,alt;dfSize,dfInterval,dfPause;guests,protected;align,space,cssID';


/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['dfTitle'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['dfTitle'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>64)
);

$GLOBALS['TL_DCA']['tl_content']['fields']['dfSize'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['dfSize'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true)
);

$GLOBALS['TL_DCA']['tl_content']['fields']['dfInterval'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['dfInterval'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'nospace'=>true)
);

$GLOBALS['TL_DCA']['tl_content']['fields']['dfTemplate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['dfTemplate'],
	'default'                 => 'df_default',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => $this->getTemplateGroup('df_')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['dfPause'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['dfPause'],
	'exclude'                 => true,
	'inputType'               => 'checkbox'
);

?>