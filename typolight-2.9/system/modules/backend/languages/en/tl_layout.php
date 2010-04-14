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
$GLOBALS['TL_LANG']['tl_layout']['name']          = array('Title', 'Please enter the layout title.');
$GLOBALS['TL_LANG']['tl_layout']['fallback']      = array('Default layout', 'Make the layout the default layout.');
$GLOBALS['TL_LANG']['tl_layout']['header']        = array('Add page header', 'Add a header section to the page layout.');
$GLOBALS['TL_LANG']['tl_layout']['headerHeight']  = array('Header height', 'Please enter the height of the page header.');
$GLOBALS['TL_LANG']['tl_layout']['footer']        = array('Add page footer', 'Add a footer section to the page layout.');
$GLOBALS['TL_LANG']['tl_layout']['footerHeight']  = array('Footer height', 'Please enter the height of the page footer.');
$GLOBALS['TL_LANG']['tl_layout']['cols']          = array('Columns', 'Please choose the number of columns.');
$GLOBALS['TL_LANG']['tl_layout']['1cl']           = array('Main column only', 'show only one column.');
$GLOBALS['TL_LANG']['tl_layout']['2cll']          = array('Left and main column', 'show two columns with the main column on the right side.');
$GLOBALS['TL_LANG']['tl_layout']['2clr']          = array('Main and right column', 'show two columns with the main column on the left side.');
$GLOBALS['TL_LANG']['tl_layout']['3cl']           = array('Main, left and right column', 'show three columns with the main column in the middle.');
$GLOBALS['TL_LANG']['tl_layout']['widthLeft']     = array('Left column width', 'Please enter the width of the left column.');
$GLOBALS['TL_LANG']['tl_layout']['widthRight']    = array('Right column width', 'Please enter the width of the right column.');
$GLOBALS['TL_LANG']['tl_layout']['sections']      = array('Custom layout sections', 'Custom layout sections can be defined in the back end settings.');
$GLOBALS['TL_LANG']['tl_layout']['sPosition']     = array('Custom sections position', 'Please select the position of the custom layout sections.');
$GLOBALS['TL_LANG']['tl_layout']['stylesheet']    = array('Style sheets', 'Please select the style sheets you want to add to the layout.');
$GLOBALS['TL_LANG']['tl_layout']['newsfeeds']     = array('News feeds', 'Please select the news feeds you want to add to the layout.');
$GLOBALS['TL_LANG']['tl_layout']['calendarfeeds'] = array('Calendar feeds', 'Please select the calendar feeds you want to add to the layout.');
$GLOBALS['TL_LANG']['tl_layout']['modules']       = array('Included modules', 'If JavaScript is disabled, make sure to save your changes before modifying the order.');
$GLOBALS['TL_LANG']['tl_layout']['template']      = array('Page template', 'Here you can select the page template.');
$GLOBALS['TL_LANG']['tl_layout']['doctype']       = array('Document Type Definition', 'Please choose a Document Type Definition.');
$GLOBALS['TL_LANG']['tl_layout']['urchinId']      = array('Google Analytics Id', 'Note that the Google Analytics Id will not be displayed while you are logged in to the TYPOlight back end.');
$GLOBALS['TL_LANG']['tl_layout']['cssClass']      = array('Body class', 'Here you can add custom classes to the body tag.');
$GLOBALS['TL_LANG']['tl_layout']['onload']        = array('Body onload', 'Here you can add a body onload attribute.');
$GLOBALS['TL_LANG']['tl_layout']['head']          = array('Additional &lt;head&gt; tags', 'Here you can add individual tags to the head section of the page.');
$GLOBALS['TL_LANG']['tl_layout']['mootools']      = array('MooTools templates', 'Here you can select one or more MooTools templates.');
$GLOBALS['TL_LANG']['tl_layout']['script']        = array('Custom JavaScript code', 'The JavaScript code which will be inserted at the bottom of the page.');
$GLOBALS['TL_LANG']['tl_layout']['static']        = array('Static layout', 'Create a static layout with a fixed width and alignment.');
$GLOBALS['TL_LANG']['tl_layout']['width']         = array('Overall width', 'The overall width will be applied to the wrapper element.');
$GLOBALS['TL_LANG']['tl_layout']['align']         = array('Alignment', 'Please select the alignment of the page.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_layout']['title_legend']    = 'Title and default';
$GLOBALS['TL_LANG']['tl_layout']['header_legend']   = 'Header and footer';
$GLOBALS['TL_LANG']['tl_layout']['column_legend']   = 'Column settings';
$GLOBALS['TL_LANG']['tl_layout']['sections_legend'] = 'Custom sections';
$GLOBALS['TL_LANG']['tl_layout']['head_legend']     = 'Head section';
$GLOBALS['TL_LANG']['tl_layout']['modules_legend']  = 'Front end modules';
$GLOBALS['TL_LANG']['tl_layout']['expert_legend']   = 'Expert settings';
$GLOBALS['TL_LANG']['tl_layout']['script_legend']   = 'Script settings';
$GLOBALS['TL_LANG']['tl_layout']['static_legend']   = 'Static layout';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_layout']['module']       = 'Module';
$GLOBALS['TL_LANG']['tl_layout']['column']       = 'Column';
$GLOBALS['TL_LANG']['tl_layout']['xhtml_strict'] = 'XHTML Strict';
$GLOBALS['TL_LANG']['tl_layout']['xhtml_trans']  = 'XHTML Transitional';
$GLOBALS['TL_LANG']['tl_layout']['before']       = 'After the page header';
$GLOBALS['TL_LANG']['tl_layout']['main']         = 'Inside the main column';
$GLOBALS['TL_LANG']['tl_layout']['after']        = 'Before the page footer';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_layout']['new']    = array('New layout', 'Create a new layout');
$GLOBALS['TL_LANG']['tl_layout']['show']   = array('Layout details', 'Show the details of layout ID %s');
$GLOBALS['TL_LANG']['tl_layout']['edit']   = array('Edit layout', 'Edit layout ID %s');
$GLOBALS['TL_LANG']['tl_layout']['copy']   = array('Duplicate layout', 'Duplicate layout ID %s');
$GLOBALS['TL_LANG']['tl_layout']['delete'] = array('Delete layout', 'Delete layout ID %s');


/**
 * Wizard buttons
 */
$GLOBALS['TL_LANG']['tl_layout']['wz_copy']   = 'Duplicate the row';
$GLOBALS['TL_LANG']['tl_layout']['wz_up']     = 'Move the row one position up';
$GLOBALS['TL_LANG']['tl_layout']['wz_down']   = 'Move the row one position down';
$GLOBALS['TL_LANG']['tl_layout']['wz_delete'] = 'Delete the row';

?>