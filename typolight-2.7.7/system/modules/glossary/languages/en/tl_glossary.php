<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Glossary
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_glossary']['title']  = array('Title', 'Please enter the glossary title.');
$GLOBALS['TL_LANG']['tl_glossary']['tstamp'] = array('Revision date', 'Date and time of the latest revision');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_glossary']['title_legend'] = 'Title';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_glossary']['deleteConfirm'] = 'Deleting a glossary will also delete all its terms! Do you really want to delete glossary ID %s?';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_glossary']['new']    = array('New glossary', 'Create a new glossary');
$GLOBALS['TL_LANG']['tl_glossary']['show']   = array('Glossary details', 'Show the details of glossary ID %s');
$GLOBALS['TL_LANG']['tl_glossary']['edit']   = array('Edit glossary', 'Edit glossary ID %s');
$GLOBALS['TL_LANG']['tl_glossary']['copy']   = array('Copy glossary', 'Copy glossary ID %s');
$GLOBALS['TL_LANG']['tl_glossary']['delete'] = array('Delete glossary', 'Delete glossary ID %s');

?>