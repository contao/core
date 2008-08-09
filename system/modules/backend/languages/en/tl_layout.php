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
$GLOBALS['TL_LANG']['tl_layout']['name']          = array('Name of the layout', 'Please enter a unique layout name.');
$GLOBALS['TL_LANG']['tl_layout']['fallback']      = array('Default layout', 'If you choose this option, the layout will be used as fallback.');
$GLOBALS['TL_LANG']['tl_layout']['template']      = array('Layout template', 'Please choose a layout template. The default template is called <em>fe_page</em>. If you want to add your own templates, upload them to the <em>templates</em> directory (file extension has to be <em>tpl</em>).');
$GLOBALS['TL_LANG']['tl_layout']['mootools']      = array('Mootools JavaScript', 'If you want to run a mootools accordion on your website, you have to add a JavaScript template that initializes the effect. You can choose this JavaScript template here. Mootools templates start with <em>moo_</em> (file extension <em>tpl</em>).');
$GLOBALS['TL_LANG']['tl_layout']['doctype']       = array('Document Type Definition', 'Please choose a DTD.');
$GLOBALS['TL_LANG']['tl_layout']['urchinId']      = array('Google Analytics ID', 'If you have a Google Analytics ID, you can enter it here.');
$GLOBALS['TL_LANG']['tl_layout']['stylesheet']    = array('Style sheets', 'Please select the style sheets you want to include in the current layout.');
$GLOBALS['TL_LANG']['tl_layout']['newsfeeds']     = array('RSS news feeds', 'Please select the RSS news feeds you want to include in the current layout.');
$GLOBALS['TL_LANG']['tl_layout']['calendarfeeds'] = array('RSS calendar feeds', 'Please select the RSS calendar feeds you want to include in the current layout.');
$GLOBALS['TL_LANG']['tl_layout']['onload']        = array('Body onload', 'In case one of your custom scripts requires a <em>body onload</em> event, you can add the code here.');
$GLOBALS['TL_LANG']['tl_layout']['head']          = array('Additional &lt;head&gt; tags', 'Here you can add additional meta tags and scripts that will be included at the end of the page head. Please note that default meta tags, script tags and style sheet tags are generated automatically.');
$GLOBALS['TL_LANG']['tl_layout']['cols']          = array('Columns', 'Please choose the number of columns.');
$GLOBALS['TL_LANG']['tl_layout']['1cl']           = array('One column', 'only the main column will be shown.');
$GLOBALS['TL_LANG']['tl_layout']['2cll']          = array('Two columns with main column on the right side', 'the main column will be shown on the right side next to the left column.');
$GLOBALS['TL_LANG']['tl_layout']['2clr']          = array('Two columns with main column on the left side', 'the main column will be shown on the left side next to the right column.');
$GLOBALS['TL_LANG']['tl_layout']['3cl']           = array('Three columns with main column in the middle', 'the main column will be shown in the middle of the left and right column.');
$GLOBALS['TL_LANG']['tl_layout']['widthLeft']     = array('Left column width', 'Please enter the width of the left column.');
$GLOBALS['TL_LANG']['tl_layout']['widthRight']    = array('Right column width', 'Please enter the width of the right column.');
$GLOBALS['TL_LANG']['tl_layout']['header']        = array('Include header', 'Include a page header.');
$GLOBALS['TL_LANG']['tl_layout']['headerHeight']  = array('Header height', 'Please enter the height of the header element.');
$GLOBALS['TL_LANG']['tl_layout']['footer']        = array('Include footer', 'Include a page footer.');
$GLOBALS['TL_LANG']['tl_layout']['footerHeight']  = array('Footer height', 'Please enter the height of the footer element.');
$GLOBALS['TL_LANG']['tl_layout']['static']        = array('Static layout', 'Create a static page layout with a fixed width and alignment.');
$GLOBALS['TL_LANG']['tl_layout']['width']         = array('Overall width', 'Please enter the overall width that will be assigned to the wrapper element.');
$GLOBALS['TL_LANG']['tl_layout']['align']         = array('Website alignment', 'Please select the alignment of the website.');
$GLOBALS['TL_LANG']['tl_layout']['sections']      = array('Custom layout sections', 'This is a list of all custom layout sections that have been defined in the settings module. Please select the sections that you want to use in this layout.');
$GLOBALS['TL_LANG']['tl_layout']['sPosition']     = array('Custom sections position', 'Please select the position of your custom layout sections.');
$GLOBALS['TL_LANG']['tl_layout']['modules']       = array('Included modules', 'Please assign each module to a page element. Use the buttons to add, move or remove a module. If you are working without JavaScript assistance, you should save your changes before you modify the order!');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_layout']['module']       = 'Module';
$GLOBALS['TL_LANG']['tl_layout']['column']       = 'Column';
$GLOBALS['TL_LANG']['tl_layout']['xhtml_strict'] = 'XHTML Strict';
$GLOBALS['TL_LANG']['tl_layout']['xhtml_trans']  = 'XHTML Transitional';
$GLOBALS['TL_LANG']['tl_layout']['before']       = 'After header element';
$GLOBALS['TL_LANG']['tl_layout']['main']         = 'In main column';
$GLOBALS['TL_LANG']['tl_layout']['after']        = 'Before footer element';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_layout']['new']    = array('New layout', 'Create a new layout');
$GLOBALS['TL_LANG']['tl_layout']['show']   = array('Layout details', 'Show details of layout ID %s');
$GLOBALS['TL_LANG']['tl_layout']['copy']   = array('Duplicate layout', 'Duplicate layout ID %s');
$GLOBALS['TL_LANG']['tl_layout']['delete'] = array('Delete layout', 'Delete layout ID %s');
$GLOBALS['TL_LANG']['tl_layout']['edit']   = array('Edit layout', 'Edit layout ID %s');


/**
 * Wizard buttons
 */
$GLOBALS['TL_LANG']['tl_layout']['wz_copy']   = 'Duplicate this entry';
$GLOBALS['TL_LANG']['tl_layout']['wz_up']     = 'Move this entry one position up';
$GLOBALS['TL_LANG']['tl_layout']['wz_down']   = 'Move this entry one position down';
$GLOBALS['TL_LANG']['tl_layout']['wz_delete'] = 'Delete this entry';

?>