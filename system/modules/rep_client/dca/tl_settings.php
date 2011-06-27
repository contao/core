<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Repository
 * @license    LGPL
 * @filesource
 */


/**
 * Contao Repository :: Data container array for tl_settings
 *
 * @copyright  Peter Koch 2008-2010
 * @author     Peter Koch, IBK Software AG
 * @license    See accompaning file LICENSE.txt
 */


/**
 * Add to palette
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ',repository_languages,repository_listsize,repository_unsafe_catalog';

/**
 * Add fields
 */
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
	'eval'		=> array('rgxp'=>'digit', 'maxlength'=>6, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['repository_unsafe_catalog'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['repository_unsafe_catalog'],
	'inputType'	=> 'checkbox',
	'eval'      => array('tl_class'=>'w50 m12')
);

?>