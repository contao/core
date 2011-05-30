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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['name']       = array('Name', 'Please enter the style sheet name.');
$GLOBALS['TL_LANG']['tl_style_sheet']['cc']         = array('Conditional comment', 'Conditional comments allow you to create Internet Explorer specific style sheets (e.g. <em>if lt IE 9</em>).');
$GLOBALS['TL_LANG']['tl_style_sheet']['media']      = array('Media types', 'Here you can choose the media types the style sheet applies to.');
$GLOBALS['TL_LANG']['tl_style_sheet']['mediaQuery'] = array('Media query', 'Here you can define the media type using a media query like <em>screen and (min-width: 800px)</em>. The media types defined above will then be overwritten.');
$GLOBALS['TL_LANG']['tl_style_sheet']['vars']       = array('Global variables', 'Here you can define global variables for the style sheet (e.g. <em>$red</em> -> <em>c00</em> or <em>$margin</em> -> <em>12px</em>).');
$GLOBALS['TL_LANG']['tl_style_sheet']['source']     = array('Source files', 'Please choose one or more files from the files directory.');
$GLOBALS['TL_LANG']['tl_style_sheet']['tstamp']     = array('Revision date', 'Date and time of the latest revision');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['title_legend'] = 'Name and media types';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['css_imported'] = 'Style sheet "%s" has been imported.';
$GLOBALS['TL_LANG']['tl_style_sheet']['css_renamed']  = 'Style sheet "%s" has been imported as "%s".';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['new']        = array('New style sheet', 'Create a new style sheet');
$GLOBALS['TL_LANG']['tl_style_sheet']['show']       = array('Style sheet details', 'Show the details of style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['edit']       = array('Edit style sheet', 'Edit style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['editheader'] = array('Edit style sheet settings', 'Edit the settings of style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['cut']        = array('Move style sheet', 'Move style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['copy']       = array('Duplicate style sheet', 'Duplicate style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['delete']     = array('Delete style sheet', 'Delete style sheet ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['import']     = array('CSS import', 'Import existing CSS files');

?>