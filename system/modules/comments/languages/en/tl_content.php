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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_content']['com_template']       = array('Comments layout', 'Please choose a comment layout. You can add custom comment layouts to folder <em>templates</em>. Comment template files start with <em>com_</em> and require file extension <em>.tpl</em>.');
$GLOBALS['TL_LANG']['tl_content']['com_order']          = array('Sort order', 'Please choose the sort order.');
$GLOBALS['TL_LANG']['tl_content']['com_perPage']        = array('Items per page', 'Please enter the number of comments per page (0 = disable pagination).');
$GLOBALS['TL_LANG']['tl_content']['com_moderate']       = array('Moderate', 'Approve comments before they are shown on the website.');
$GLOBALS['TL_LANG']['tl_content']['com_bbcode']         = array('Allow BBCode', 'Allow visitors to use BBCode to format their comments.');
$GLOBALS['TL_LANG']['tl_content']['com_disableCaptcha'] = array('Disable security question', 'Choose this option to disable the security question (not recommended).');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_content']['ascending']  = 'ascending';
$GLOBALS['TL_LANG']['tl_content']['descending'] = 'descending';

?>