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
 * @package    Faq
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_faq_category']['title']    = array('Title', 'Please enter the category title.');
$GLOBALS['TL_LANG']['tl_faq_category']['headline'] = array('Headline', 'Please enter the category headline.');
$GLOBALS['TL_LANG']['tl_faq_category']['jumpTo']   = array('Redirect page', 'Please choose the FAQ reader page to which visitors will be redirected when clicking a FAQ.');
$GLOBALS['TL_LANG']['tl_faq_category']['tstamp']   = array('Revision date', 'Date and time of the latest revision');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_faq_category']['title_legend'] = 'Title and redirect';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_faq_category']['deleteConfirm'] = 'Deleting category ID %s will also delete all its FAQs! Continue?';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_faq_category']['new']    = array('New category', 'Create a new category');
$GLOBALS['TL_LANG']['tl_faq_category']['show']   = array('Category details', 'Show the details of category ID %s');
$GLOBALS['TL_LANG']['tl_faq_category']['edit']   = array('Edit category', 'Edit category ID %s');
$GLOBALS['TL_LANG']['tl_faq_category']['copy']   = array('Copy category', 'Copy category ID %s');
$GLOBALS['TL_LANG']['tl_faq_category']['delete'] = array('Delete category', 'Delete category ID %s');

?>