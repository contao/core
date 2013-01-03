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
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_news_comments']['pid']       = array('News item', 'The associated news item.');
$GLOBALS['TL_LANG']['tl_news_comments']['date']      = array('Date', 'Please enter the comment date.');
$GLOBALS['TL_LANG']['tl_news_comments']['name']      = array('Author', 'Please enter the author\'s name.');
$GLOBALS['TL_LANG']['tl_news_comments']['email']     = array('E-mail address', 'The e-mail address will not be published.');
$GLOBALS['TL_LANG']['tl_news_comments']['website']   = array('Website', 'Here you can enter a website address.');
$GLOBALS['TL_LANG']['tl_news_comments']['comment']   = array('Comment', 'Please enter the comment.');
$GLOBALS['TL_LANG']['tl_news_comments']['published'] = array('Publish comment', 'Make the comment publicly visible on the website.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news_comments']['author_legend']  = 'Author';
$GLOBALS['TL_LANG']['tl_news_comments']['comment_legend'] = 'Comment';
$GLOBALS['TL_LANG']['tl_news_comments']['publish_legend'] = 'Publish settings';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_news_comments']['approved'] = 'Approved';
$GLOBALS['TL_LANG']['tl_news_comments']['pending']  = 'Awaiting moderation';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news_comments']['show']       = array('Comment details', 'Show the details of comment ID %s');
$GLOBALS['TL_LANG']['tl_news_comments']['edit']       = array('Edit comment', 'Edit comment ID %s');
$GLOBALS['TL_LANG']['tl_news_comments']['delete']     = array('Delete comment', 'Delete comment ID %s');
$GLOBALS['TL_LANG']['tl_news_comments']['toggle']     = array('Publish/unpublish comment', 'Publish/unpublish comment ID %s');
$GLOBALS['TL_LANG']['tl_news_comments']['editheader'] = array('Edit archive', 'Edit the current archive');

?>