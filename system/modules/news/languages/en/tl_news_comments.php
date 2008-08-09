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
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_news_comments']['name']      = array('Name', 'Please enter the real name of the author.');
$GLOBALS['TL_LANG']['tl_news_comments']['email']     = array('E-mail address', 'Please enter the author\'s e-mail address (will not be published).');
$GLOBALS['TL_LANG']['tl_news_comments']['website']   = array('Website', 'Please enter an optional website address.');
$GLOBALS['TL_LANG']['tl_news_comments']['comment']   = array('Comment', 'Please enter the comment.');
$GLOBALS['TL_LANG']['tl_news_comments']['published'] = array('Published', 'Only published comments will be shown on the website.');
$GLOBALS['TL_LANG']['tl_news_comments']['date']      = array('Date', 'Please enter the comment date.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_news_comments']['approved'] = 'Approved';
$GLOBALS['TL_LANG']['tl_news_comments']['pending']  = 'Awaiting moderation';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news_comments']['edit']       = array('Edit comment', 'Edit comment ID %s');
$GLOBALS['TL_LANG']['tl_news_comments']['delete']     = array('Delete comment', 'Delete comment ID %s');
$GLOBALS['TL_LANG']['tl_news_comments']['show']       = array('Comment details', 'Show details of comment ID %s');
$GLOBALS['TL_LANG']['tl_news_comments']['editheader'] = array('Edit archive', 'Edit the current archive');

?>