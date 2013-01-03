<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Faq
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_faq']['question']     = array('Question', 'Please enter the question.');
$GLOBALS['TL_LANG']['tl_faq']['alias']        = array('FAQ alias', 'The FAQ alias is a unique reference to the FAQ which can be called instead of its numeric ID.');
$GLOBALS['TL_LANG']['tl_faq']['author']       = array('Author', 'Here you can change the author of the FAQ.');
$GLOBALS['TL_LANG']['tl_faq']['answer']       = array('Answer', 'Please enter the answer.');
$GLOBALS['TL_LANG']['tl_faq']['addImage']     = array('Add an image', 'Add an image to the FAQ.');
$GLOBALS['TL_LANG']['tl_faq']['addEnclosure'] = array('Add enclosures', 'Add one or more downloadable files to the FAQ.');
$GLOBALS['TL_LANG']['tl_faq']['enclosure']    = array('Enclosures', 'Please choose the files you want to attach.');
$GLOBALS['TL_LANG']['tl_faq']['noComments']   = array('Disable comments', 'Do not allow comments for this particular FAQ.');
$GLOBALS['TL_LANG']['tl_faq']['published']    = array('Publish the FAQ', 'Make the FAQ publicly visible on the website.');


/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_faq']['title_legend']     = 'Title and author';
$GLOBALS['TL_LANG']['tl_faq']['answer_legend']    = 'Answer';
$GLOBALS['TL_LANG']['tl_faq']['image_legend']     = 'Image settings';
$GLOBALS['TL_LANG']['tl_faq']['enclosure_legend'] = 'Enclosures';
$GLOBALS['TL_LANG']['tl_faq']['expert_legend']    = 'Expert settings';
$GLOBALS['TL_LANG']['tl_faq']['publish_legend']   = 'Publish settings';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_faq']['new']        = array('New question', 'Create a new question');
$GLOBALS['TL_LANG']['tl_faq']['show']       = array('Show question details', 'Show the details of question ID %s');
$GLOBALS['TL_LANG']['tl_faq']['edit']       = array('Edit question', 'Edit question ID %s');
$GLOBALS['TL_LANG']['tl_faq']['copy']       = array('Duplicate question', 'Duplicate question ID %s');
$GLOBALS['TL_LANG']['tl_faq']['cut']        = array('Move question', 'Move question ID %s');
$GLOBALS['TL_LANG']['tl_faq']['delete']     = array('Delete question', 'Delete question ID %s');
$GLOBALS['TL_LANG']['tl_faq']['toggle']     = array('Publish/unpublish question', 'Publish/unpublish question ID %s');
$GLOBALS['TL_LANG']['tl_faq']['editheader'] = array('Edit category', 'Edit the category settings');
$GLOBALS['TL_LANG']['tl_faq']['pasteafter'] = array('Paste at the top', 'Paste after question ID %s');
$GLOBALS['TL_LANG']['tl_faq']['pastenew']   = array('Add new at the top', 'Add new after question ID %s');

?>