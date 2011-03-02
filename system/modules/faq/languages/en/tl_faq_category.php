<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Faq
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_faq_category']['title']          = array('Title', 'Please enter the category title.');
$GLOBALS['TL_LANG']['tl_faq_category']['headline']       = array('Headline', 'Please enter the category headline.');
$GLOBALS['TL_LANG']['tl_faq_category']['jumpTo']         = array('Redirect page', 'Please choose the FAQ reader page to which visitors will be redirected when clicking a FAQ.');
$GLOBALS['TL_LANG']['tl_faq_category']['allowComments']  = array('Enable comments', 'Allow visitors to comment FAQs.');
$GLOBALS['TL_LANG']['tl_faq_category']['notify']         = array('Notify', 'Please choose who to notify when comments are added.');
$GLOBALS['TL_LANG']['tl_faq_category']['sortOrder']      = array('Sort order', 'By default, comments are sorted ascending, starting with the oldest one.');
$GLOBALS['TL_LANG']['tl_faq_category']['perPage']        = array('Comments per page', 'Number of comments per page. Set to 0 to disable pagination.');
$GLOBALS['TL_LANG']['tl_faq_category']['moderate']       = array('Moderate comments', 'Approve comments before they are published on the website.');
$GLOBALS['TL_LANG']['tl_faq_category']['bbcode']         = array('Allow BBCode', 'Allow visitors to format their comments with BBCode.');
$GLOBALS['TL_LANG']['tl_faq_category']['requireLogin']   = array('Require login to comment', 'Allow only authenticated users to create comments.');
$GLOBALS['TL_LANG']['tl_faq_category']['disableCaptcha'] = array('Disable the security question', 'Use this option only if you have limited comments to authenticated users.');
$GLOBALS['TL_LANG']['tl_faq_category']['tstamp']         = array('Revision date', 'Date and time of the latest revision');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_faq_category']['title_legend']    = 'Title and redirect';
$GLOBALS['TL_LANG']['tl_faq_category']['comments_legend'] = 'Comments';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_faq_category']['notify_admin']  = 'System administrator';
$GLOBALS['TL_LANG']['tl_faq_category']['notify_author'] = 'Author of the FAQ';
$GLOBALS['TL_LANG']['tl_faq_category']['notify_both']   = 'Author and system administrator';
$GLOBALS['TL_LANG']['tl_faq_category']['deleteConfirm'] = 'Deleting category ID %s will also delete all its FAQs! Continue?';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_faq_category']['new']        = array('New category', 'Create a new category');
$GLOBALS['TL_LANG']['tl_faq_category']['show']       = array('Category details', 'Show the details of category ID %s');
$GLOBALS['TL_LANG']['tl_faq_category']['edit']       = array('Edit category', 'Edit category ID %s');
$GLOBALS['TL_LANG']['tl_faq_category']['editheader'] = array('Edit category settings', 'Edit the settings of category ID %s');
$GLOBALS['TL_LANG']['tl_faq_category']['copy']       = array('Duplicate category', 'Duplicate category ID %s');
$GLOBALS['TL_LANG']['tl_faq_category']['delete']     = array('Delete category', 'Delete category ID %s');

?>