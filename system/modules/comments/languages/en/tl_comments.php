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
 * @package    Comments
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_comments']['source']    = array('Origin', 'The associated table.');
$GLOBALS['TL_LANG']['tl_comments']['parent']    = array('Parent ID', 'The associated record.');
$GLOBALS['TL_LANG']['tl_comments']['date']      = array('Date', 'Please enter the comment date.');
$GLOBALS['TL_LANG']['tl_comments']['name']      = array('Author', 'Please enter the author\'s name.');
$GLOBALS['TL_LANG']['tl_comments']['email']     = array('E-mail address', 'The e-mail address will not be published.');
$GLOBALS['TL_LANG']['tl_comments']['website']   = array('Website', 'Here you can enter a website address.');
$GLOBALS['TL_LANG']['tl_comments']['comment']   = array('Comment', 'Please enter the comment.');
$GLOBALS['TL_LANG']['tl_comments']['addReply']  = array('Add a reply', 'Here you can reply to the comment.');
$GLOBALS['TL_LANG']['tl_comments']['author']    = array('Author', 'Here you can change the author of the reply.');
$GLOBALS['TL_LANG']['tl_comments']['reply']     = array('Reply', 'Here you can enter the reply.');
$GLOBALS['TL_LANG']['tl_comments']['published'] = array('Publish comment', 'Make the comment publicly visible on the website.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_comments']['author_legend']  = 'Author';
$GLOBALS['TL_LANG']['tl_comments']['comment_legend'] = 'Comment';
$GLOBALS['TL_LANG']['tl_comments']['reply_legend']   = 'Reply';
$GLOBALS['TL_LANG']['tl_comments']['publish_legend'] = 'Publish settings';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_comments']['approved']           = 'Approved';
$GLOBALS['TL_LANG']['tl_comments']['pending']            = 'Awaiting moderation';
$GLOBALS['TL_LANG']['tl_comments']['tl_content']         = 'Article';
$GLOBALS['TL_LANG']['tl_comments']['tl_page']            = 'Page';
$GLOBALS['TL_LANG']['tl_comments']['tl_news']            = 'News item';
$GLOBALS['TL_LANG']['tl_comments']['tl_faq']             = 'FAQ';
$GLOBALS['TL_LANG']['tl_comments']['tl_calendar_events'] = 'Event';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_comments']['show']   = array('Comment details', 'Show the details of comment ID %s');
$GLOBALS['TL_LANG']['tl_comments']['edit']   = array('Edit comment', 'Edit comment ID %s');
$GLOBALS['TL_LANG']['tl_comments']['delete'] = array('Delete comment', 'Delete comment ID %s');
$GLOBALS['TL_LANG']['tl_comments']['toggle'] = array('Publish/unpublish comment', 'Publish/unpublish comment ID %s');

?>