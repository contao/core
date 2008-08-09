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
 * @copyright  Leo Feyer 2008 
 * @author     Leo Feyer <leo@typolight.org> 
 * @package    Faq 
 * @license    LGPL 
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_faq']['question']     = array('Question', 'Please enter the question.');
$GLOBALS['TL_LANG']['tl_faq']['alias']        = array('FAQ alias', 'A FAQ alias is a unique reference to the question which can be called instead of the question ID.');
$GLOBALS['TL_LANG']['tl_faq']['answer']       = array('Answer', 'Please enter the answer.');
$GLOBALS['TL_LANG']['tl_faq']['addImage']     = array('Add an image', 'Add an image to the answer.');
$GLOBALS['TL_LANG']['tl_faq']['addEnclosure'] = array('Add enclosure', 'Add one or more downloadable files to the answer.');
$GLOBALS['TL_LANG']['tl_faq']['enclosure']    = array('Enclosure', 'Please choose the files you want to attach.');
$GLOBALS['TL_LANG']['tl_faq']['author']       = array('Author', 'Please choose an author.');
$GLOBALS['TL_LANG']['tl_faq']['published']    = array('Published', 'The FAQ will not be visible on your website until it is published.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_faq']['new']        = array('New question', 'Create a new question');
$GLOBALS['TL_LANG']['tl_faq']['edit']       = array('Edit question', 'Edit question ID %s');
$GLOBALS['TL_LANG']['tl_faq']['copy']       = array('Duplicate question', 'Duplicate question ID %s');
$GLOBALS['TL_LANG']['tl_faq']['cut']        = array('Move question', 'Move question ID %s');
$GLOBALS['TL_LANG']['tl_faq']['delete']     = array('Delete question', 'Delete question ID %s');
$GLOBALS['TL_LANG']['tl_faq']['show']       = array('Show question', 'Show details of question ID %s');
$GLOBALS['TL_LANG']['tl_faq']['editheader'] = array('Edit category', 'Edit this category');
$GLOBALS['TL_LANG']['tl_faq']['pasteafter'] = array('Paste at the beginning', 'Paste after question ID %s');
$GLOBALS['TL_LANG']['tl_faq']['pastenew']   = array('Create a new question at the beginning', 'Create a new question after question ID %s');

?>