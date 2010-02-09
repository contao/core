<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['name']   = array('Name', 'Please enter the style sheet name.');
$GLOBALS['TL_LANG']['tl_style_sheet']['cc']     = array('Conditional comment', 'Conditional comments allow you to create Internet Explorer specific style sheets.');
$GLOBALS['TL_LANG']['tl_style_sheet']['media']  = array('Media types', 'Here you can choose the media types the style sheet applies to.');
$GLOBALS['TL_LANG']['tl_style_sheet']['source'] = array('Source files', 'Please choose one or more files from the files directory.');
$GLOBALS['TL_LANG']['tl_style_sheet']['tstamp'] = array('Revision date', 'Date and time of the latest revision');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['title_legend'] = 'Name and media types';


/**
 * References
 */
$GLOBALS['TL_LANG']['ERROR']['css_exists']   = 'ATTENTION! Style sheets of the same name will be overwritten. Continue?';
$GLOBALS['TL_LANG']['CONFIRM']['css_exists'] = 'Style sheet "%s" has been imported.';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['new']        = array('New style sheet', 'Create a new style sheet');
$GLOBALS['TL_LANG']['tl_style_sheet']['show']       = array('Style sheet details', 'Show the details of style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['edit']       = array('Edit style sheet', 'Edit style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['editheader'] = array('Edit style sheet settings', 'Edit the settings of style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['copy']       = array('Duplicate style sheet', 'Duplicate style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['delete']     = array('Delete style sheet', 'Delete style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['import']     = array('CSS import', 'Import existing CSS files');

?>