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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_article']['title']       = array('Title', 'Please enter the article title.');
$GLOBALS['TL_LANG']['tl_article']['alias']       = array('Article alias', 'The article alias is a unique reference to the article which can be called instead of its numeric ID.');
$GLOBALS['TL_LANG']['tl_article']['author']      = array('Author', 'Here you can change the author of the article.');
$GLOBALS['TL_LANG']['tl_article']['inColumn']    = array('Display in', 'Please choose the layout section you want the article to be displayed in.');
$GLOBALS['TL_LANG']['tl_article']['keywords']    = array('Meta keywords', 'Here you can enter a list of comma separated keywords which will be evaluated by search engines like Google or Yahoo. Search engines usually index up to 800 characters.');
$GLOBALS['TL_LANG']['tl_article']['teaserCssID'] = array('Teaser CSS ID/class', 'Here you can set an ID and one or more classes for the teaser element.');
$GLOBALS['TL_LANG']['tl_article']['showTeaser']  = array('Show teaser', 'Show the article teaser if there are multiple articles.');
$GLOBALS['TL_LANG']['tl_article']['teaser']      = array('Article teaser', 'The article teaser can also be displayed with the content element "article teaser".');
$GLOBALS['TL_LANG']['tl_article']['printable']   = array('Syndication', 'Here you can choose which options are available.');
$GLOBALS['TL_LANG']['tl_article']['cssID']       = array('CSS ID/class', 'Here you can set an ID and one or more classes.');
$GLOBALS['TL_LANG']['tl_article']['space']       = array('Space in front and after', 'Here you can enter the spacing in front of and after the article in pixel. You should try to avoid inline styles and define the spacing in a style sheet, though.');
$GLOBALS['TL_LANG']['tl_article']['published']   = array('Publish article', 'Make the article publicly visible on the website.');
$GLOBALS['TL_LANG']['tl_article']['start']       = array('Show from', 'Do not show the article on the website before this day.');
$GLOBALS['TL_LANG']['tl_article']['stop']        = array('Show until', 'Do not show the article on the website on and after this day.');
$GLOBALS['TL_LANG']['tl_article']['tstamp']      = array('Revision date', 'Date and time of the latest revision');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_article']['title_legend']   = 'Title and author';
$GLOBALS['TL_LANG']['tl_article']['layout_legend']  = 'Section and keywords';
$GLOBALS['TL_LANG']['tl_article']['teaser_legend']  = 'Article teaser';
$GLOBALS['TL_LANG']['tl_article']['expert_legend']  = 'Expert settings';
$GLOBALS['TL_LANG']['tl_article']['publish_legend'] = 'Publish settings';
$GLOBALS['TL_LANG']['tl_article']['print']          = 'Print the page';
$GLOBALS['TL_LANG']['tl_article']['pdf']            = 'Export as PDF';
$GLOBALS['TL_LANG']['tl_article']['facebook']       = 'Share on Facebook';
$GLOBALS['TL_LANG']['tl_article']['twitter']        = 'Share on Twitter';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_article']['header'] = 'Header';
$GLOBALS['TL_LANG']['tl_article']['left']   = 'Left column';
$GLOBALS['TL_LANG']['tl_article']['main']   = 'Main column';
$GLOBALS['TL_LANG']['tl_article']['right']  = 'Right column';
$GLOBALS['TL_LANG']['tl_article']['footer'] = 'Footer';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_article']['new']        = array('New article', 'Create a new article');
$GLOBALS['TL_LANG']['tl_article']['show']       = array('Article details', 'Show the details of article ID %s');
$GLOBALS['TL_LANG']['tl_article']['edit']       = array('Edit article', 'Edit article ID %s');
$GLOBALS['TL_LANG']['tl_article']['editheader'] = array('Edit article settings', 'Edit the settings of article ID %s');
$GLOBALS['TL_LANG']['tl_article']['copy']       = array('Duplicate article', 'Duplicate article ID %s');
$GLOBALS['TL_LANG']['tl_article']['cut']        = array('Move article', 'Move article ID %s');
$GLOBALS['TL_LANG']['tl_article']['delete']     = array('Delete article', 'Delete article ID %s');
$GLOBALS['TL_LANG']['tl_article']['toggle']     = array('Publish/unpublish article', 'Publish/unpublish article ID %s');
$GLOBALS['TL_LANG']['tl_article']['pasteafter'] = array('Paste after', 'Paste after article ID %s');
$GLOBALS['TL_LANG']['tl_article']['pasteinto']  = array('Paste into', 'Paste into page ID %s');

?>