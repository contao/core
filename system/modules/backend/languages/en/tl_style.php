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
$GLOBALS['TL_LANG']['tl_style']['comment']        = array('Comment', 'Comments will be shown above the corresponding format definition.');
$GLOBALS['TL_LANG']['tl_style']['selector']       = array('Selector', 'A selector defines to which HTML element or element group a format definition applies. You can enter one or more comma separated selectors.');
$GLOBALS['TL_LANG']['tl_style']['category']       = array('Category', 'Use categories to group your format definitions and to keep track of them.');
$GLOBALS['TL_LANG']['tl_style']['size']           = array('Size and position', 'Edit size and position (width, height, position, overflow, float, clear, display).');
$GLOBALS['TL_LANG']['tl_style']['width']          = array('Width', 'Please enter the width of the element and the unit.');
$GLOBALS['TL_LANG']['tl_style']['height']         = array('Height', 'Please enter the height of the element and the unit.');
$GLOBALS['TL_LANG']['tl_style']['trbl']           = array('Position', 'Please enter the top, right, bottom and left position and the unit.');
$GLOBALS['TL_LANG']['tl_style']['position']       = array('Position type', 'Specifies the position type.');
$GLOBALS['TL_LANG']['tl_style']['overflow']       = array('Overflow', 'Defines how an element is displayed when its content exceeds its size.');
$GLOBALS['TL_LANG']['tl_style']['floating']       = array('Float', 'Allows to float an element.');
$GLOBALS['TL_LANG']['tl_style']['clear']          = array('Clear', 'Continue normal text flow below a floating element.');
$GLOBALS['TL_LANG']['tl_style']['display']        = array('Display', 'Defines the way an element is displayed.');
$GLOBALS['TL_LANG']['tl_style']['alignment']      = array('Margin, padding and alignment', 'Edit margin, padding and alignment (margin, padding, align, text-align, vertical-align).');
$GLOBALS['TL_LANG']['tl_style']['margin']         = array('Margin', 'Please enter the top, right, bottom, left margin and the unit. Margin is the distance between an element and its surrounding elements.');
$GLOBALS['TL_LANG']['tl_style']['padding']        = array('Padding', 'Please enter the top, right, bottom, left padding and the unit. Padding is the distance between the content of an element and its own borders.');
$GLOBALS['TL_LANG']['tl_style']['align']          = array('Element alignment', 'To align an element its left and right margin value will be overwritten.');
$GLOBALS['TL_LANG']['tl_style']['textalign']      = array('Text alignment', 'Defines the horizontal text alignment.');
$GLOBALS['TL_LANG']['tl_style']['verticalalign']  = array('Vertical alignment', 'Defines the vertical alignment.');
$GLOBALS['TL_LANG']['tl_style']['background']     = array('Background', 'Edit background properties (color, image, position, repeat).');
$GLOBALS['TL_LANG']['tl_style']['bgcolor']        = array('Background color', 'Please enter a hexadecimal background color (e.g. ff0000 for red).');
$GLOBALS['TL_LANG']['tl_style']['bgimage']        = array('Background image', 'You can enter the path to a background image.');
$GLOBALS['TL_LANG']['tl_style']['bgposition']     = array('Background position', 'Please select the position of the background image.');
$GLOBALS['TL_LANG']['tl_style']['bgrepeat']       = array('Background repeat', 'Please select the repeating mode.');
$GLOBALS['TL_LANG']['tl_style']['border']         = array('Border', 'Edit border properties (width, style, color, collapse).');
$GLOBALS['TL_LANG']['tl_style']['borderwidth']    = array('Border width', 'Please enter the top, right, bottom and left border width and the unit.');
$GLOBALS['TL_LANG']['tl_style']['borderstyle']    = array('Border style', 'Please select the border style.');
$GLOBALS['TL_LANG']['tl_style']['bordercolor']    = array('Border color', 'Please enter a hexadecimal border color (e.g. ff0000 for red).');
$GLOBALS['TL_LANG']['tl_style']['bordercollapse'] = array('Border handling', 'Please define whether borders of adjacent table cells are displayed as one border or as two separate borders.');
$GLOBALS['TL_LANG']['tl_style']['font']           = array('Font', 'Edit font properties (types, size, style, color, line-height, white-space).');
$GLOBALS['TL_LANG']['tl_style']['fontfamily']     = array('Font types', 'Please enter a comma separated list of font types. Use quotation marks if the font name contains blanks and provide at least one generic font family (e.g. <em>sans-serif</em>).');
$GLOBALS['TL_LANG']['tl_style']['fontstyle']      = array('Font style', 'Please choose one or more font styles.');
$GLOBALS['TL_LANG']['tl_style']['fontsize']       = array('Font size', 'Please enter the font size and the unit.');
$GLOBALS['TL_LANG']['tl_style']['fontcolor']      = array('Font color', 'Please enter a hexadecimal font color (e.g. ff0000 for red).');
$GLOBALS['TL_LANG']['tl_style']['lineheight']     = array('Line height', 'You can enter the line height of the element here.');
$GLOBALS['TL_LANG']['tl_style']['whitespace']     = array('Disable automatic line feed', 'If you choose this option, there will be no automatic word wrapping.');
$GLOBALS['TL_LANG']['tl_style']['list']           = array('List', 'Edit list properties (style, type, image).');
$GLOBALS['TL_LANG']['tl_style']['liststyletype']  = array('List symbol', 'Please choose a list symbol.');
$GLOBALS['TL_LANG']['tl_style']['liststyleimage'] = array('Custom symbol', 'Here you can enter the path to an individual symbol.');
$GLOBALS['TL_LANG']['tl_style']['own']            = array('Custom CSS code', 'Here you can enter custom CSS code.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_style']['normal']        = 'normal';
$GLOBALS['TL_LANG']['tl_style']['bold']          = 'bold';
$GLOBALS['TL_LANG']['tl_style']['italic']        = 'italic';
$GLOBALS['TL_LANG']['tl_style']['underline']     = 'underline';
$GLOBALS['TL_LANG']['tl_style']['notUnderlined'] = 'not underlined';
$GLOBALS['TL_LANG']['tl_style']['line-through']  = 'line-through';
$GLOBALS['TL_LANG']['tl_style']['overline']      = 'overline';
$GLOBALS['TL_LANG']['tl_style']['small-caps']    = 'small-caps';
$GLOBALS['TL_LANG']['tl_style']['disc']          = 'dot';
$GLOBALS['TL_LANG']['tl_style']['circle']        = 'circle';
$GLOBALS['TL_LANG']['tl_style']['square']        = 'square';
$GLOBALS['TL_LANG']['tl_style']['decimal']       = 'figures';
$GLOBALS['TL_LANG']['tl_style']['upper-roman']   = 'upper latin figures';
$GLOBALS['TL_LANG']['tl_style']['lower-roman']   = 'lower latin figures';
$GLOBALS['TL_LANG']['tl_style']['upper-alpha']   = 'upper characters';
$GLOBALS['TL_LANG']['tl_style']['lower-alpha']   = 'lower characters';
$GLOBALS['TL_LANG']['tl_style']['none']          = 'no bullet';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_style']['new']        = array('New format definition', 'Create a new format definition');
$GLOBALS['TL_LANG']['tl_style']['show']       = array('Format definition details', 'Show details of format definition ID %s');
$GLOBALS['TL_LANG']['tl_style']['cut']        = array('Move format definition', 'Move format definition ID %s');
$GLOBALS['TL_LANG']['tl_style']['copy']       = array('Duplicate format definition', 'Duplicate format definition ID %s');
$GLOBALS['TL_LANG']['tl_style']['delete']     = array('Delete format definition', 'Delete format definition ID %s');
$GLOBALS['TL_LANG']['tl_style']['edit']       = array('Edit format definition', 'Edit format definition ID %s');
$GLOBALS['TL_LANG']['tl_style']['editheader'] = array('Edit style sheet header', 'Edit the header of this style sheet');
$GLOBALS['TL_LANG']['tl_style']['pasteafter'] = array('Paste at the beginning', 'Paste after format definition ID %s');
$GLOBALS['TL_LANG']['tl_style']['pastenew']   = array('Create a new format definition at the beginning', 'Create a new format definition after format definition ID %s');

?>