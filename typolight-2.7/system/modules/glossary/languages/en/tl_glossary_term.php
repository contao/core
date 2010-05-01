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
$GLOBALS['TL_LANG']['tl_glossary_term']['term']         = array('Term', 'Please enter the term.');
$GLOBALS['TL_LANG']['tl_glossary_term']['author']       = array('Author', 'Here you can change the author of the definition.');
$GLOBALS['TL_LANG']['tl_glossary_term']['definition']   = array('Definition', 'Please enter the definition.');
$GLOBALS['TL_LANG']['tl_glossary_term']['addImage']     = array('Add an image', 'Add an image to the definition.');
$GLOBALS['TL_LANG']['tl_glossary_term']['addEnclosure'] = array('Add enclosures', 'Add one or more downloadable files to the definition.');
$GLOBALS['TL_LANG']['tl_glossary_term']['enclosure']    = array('Enclosures', 'Please choose the files you want to attach.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_glossary_term']['title_legend']      = 'Term and author';
$GLOBALS['TL_LANG']['tl_glossary_term']['definition_legend'] = 'Definition';
$GLOBALS['TL_LANG']['tl_glossary_term']['image_legend']      = 'Article image';
$GLOBALS['TL_LANG']['tl_glossary_term']['enclosure_legend']  = 'Enclosures';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_glossary_term']['new']    = array('New term', 'Create a new term');
$GLOBALS['TL_LANG']['tl_glossary_term']['show']   = array('Term details', 'Show the details of term ID %s');
$GLOBALS['TL_LANG']['tl_glossary_term']['edit']   = array('Edit term', 'Edit term ID %s');
$GLOBALS['TL_LANG']['tl_glossary_term']['cut']    = array('Move term', 'Move term ID %s');
$GLOBALS['TL_LANG']['tl_glossary_term']['copy']   = array('Copy term', 'Copy term ID %s');
$GLOBALS['TL_LANG']['tl_glossary_term']['delete'] = array('Delete term', 'Delete term ID %s');

?>