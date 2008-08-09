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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['name']    = array('Name', 'Please enter a unique style sheet name.');
$GLOBALS['TL_LANG']['tl_style_sheet']['tstamp']  = array('Revision date', 'Date and time of latest revision');
$GLOBALS['TL_LANG']['tl_style_sheet']['media']   = array('Media types', 'If the style sheet applies to certain media types only, you can restrict its usage by selecting one or more media types.');
$GLOBALS['TL_LANG']['tl_style_sheet']['source']  = array('File source', 'Please choose the CSS file you want to import from the files directory.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['new']    = array('New style sheet', 'Create a new style sheet');
$GLOBALS['TL_LANG']['tl_style_sheet']['show']   = array('Style sheet details', 'Show details of style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['edit']   = array('Edit style sheet', 'Edit style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['copy']   = array('Duplicate style sheet', 'Duplicate style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['delete'] = array('Delete style sheet', 'Delete style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['import'] = array('CSS import', 'Import an existing CSS file');


/**
 * Messages
 */
$GLOBALS['TL_LANG']['ERROR']['css_exists']   = 'ATTENTION! Style sheets of the same name will be overwritten. Continue?';
$GLOBALS['TL_LANG']['CONFIRM']['css_exists'] = 'Style sheet "%s" has been imported.';

?>