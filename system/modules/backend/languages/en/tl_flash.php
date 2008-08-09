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
$GLOBALS['TL_LANG']['tl_flash']['title']   = array('Title', 'Please enter a title for your article.');
$GLOBALS['TL_LANG']['tl_flash']['flashID'] = array('Flash ID', 'Please enter a unique Flash ID. To load the article into a Flash movie, the Flash ID has to be assigned to a dynamic textfield (e.g. <em>textfield._loadArticle("flashID");</em>).');
$GLOBALS['TL_LANG']['tl_flash']['content'] = array('Text', 'Please enter the article text. HTML tags are allowed but very restrictively supported by Flash.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_flash']['new']    = array('New content element', 'Create a new Flash content element');
$GLOBALS['TL_LANG']['tl_flash']['edit']   = array('Edit content element', 'Edit Flash content element ID %s');
$GLOBALS['TL_LANG']['tl_flash']['copy']   = array('Duplicate content element', 'Duplicate Flash content element ID %s');
$GLOBALS['TL_LANG']['tl_flash']['delete'] = array('Delete content element', 'Delete Flash content element ID %s');
$GLOBALS['TL_LANG']['tl_flash']['show']   = array('Show details', 'Show details of Flash content element ID %s');

?>