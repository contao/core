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
 * @package    Comments
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_comments']['pid']       = array('Content element', 'The associated content element.');
$GLOBALS['TL_LANG']['tl_comments']['date']      = array('Date', 'Please enter the comment date.');
$GLOBALS['TL_LANG']['tl_comments']['name']      = array('Author', 'Please enter the author\'s name.');
$GLOBALS['TL_LANG']['tl_comments']['email']     = array('E-mail address', 'The e-mail address will not be published.');
$GLOBALS['TL_LANG']['tl_comments']['website']   = array('Website', 'Here you can enter a website address.');
$GLOBALS['TL_LANG']['tl_comments']['comment']   = array('Comment', 'Please enter the comment.');
$GLOBALS['TL_LANG']['tl_comments']['published'] = array('Publish comment', 'Make the comment publicly visible on the website.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_comments']['author_legend']  = 'Author';
$GLOBALS['TL_LANG']['tl_comments']['comment_legend'] = 'Comment';
$GLOBALS['TL_LANG']['tl_comments']['publish_legend'] = 'Publish settings';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_comments']['approved'] = 'Approved';
$GLOBALS['TL_LANG']['tl_comments']['pending']  = 'Awaiting moderation';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_comments']['show']       = array('Comment details', 'Show the details of comment ID %s');
$GLOBALS['TL_LANG']['tl_comments']['edit']       = array('Edit comment', 'Edit comment ID %s');
$GLOBALS['TL_LANG']['tl_comments']['delete']     = array('Delete comment', 'Delete comment ID %s');

?>