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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_article']['title']      = array('Title', 'Please enter the title of the article.');
$GLOBALS['TL_LANG']['tl_article']['alias']      = array('Article alias', 'The article alias is a unique reference to the article which can be called instead of the article ID.');
$GLOBALS['TL_LANG']['tl_article']['author']     = array('Author', 'Please enter the author\'s name.');
$GLOBALS['TL_LANG']['tl_article']['inColumn']   = array('Display in', 'Please assign the article to a column or the page header or footer.');
$GLOBALS['TL_LANG']['tl_article']['teaser']     = array('Teaser', 'The teaser text can be shown automatically or with the "article teaser" content element.');
$GLOBALS['TL_LANG']['tl_article']['showTeaser'] = array('Show teaser', 'Show the teaser text instead of the article if there are multiple articles.');
$GLOBALS['TL_LANG']['tl_article']['space']      = array('Space in front and after', 'Please enter the spacing in front of and after the article in pixel.');
$GLOBALS['TL_LANG']['tl_article']['cssID']      = array('Style sheet ID and class', 'Here you can enter a style sheet ID (id attribute) and one or more style sheet classes (class attributes) to format the article using CSS.');
$GLOBALS['TL_LANG']['tl_article']['keywords']   = array('Keywords', 'You can enter several comma separated keywords which will be used by search engines to find the page. A search engine usually indicates up to 800 characters.');
$GLOBALS['TL_LANG']['tl_article']['printable']  = array('Printable', 'Print article as PDF (style sheets with media type "print" or "all" will be recognized).');
$GLOBALS['TL_LANG']['tl_article']['label']      = array('Link title', 'You can insert a custom label or an HTML image tag here. Leave blank to use the default label.');
$GLOBALS['TL_LANG']['tl_article']['published']  = array('Published', 'Unless you choose this option the article is not visible to the visitors of your website.');
$GLOBALS['TL_LANG']['tl_article']['start']      = array('Show from', 'If you enter a date here the current article will not be shown on the website before this day.');
$GLOBALS['TL_LANG']['tl_article']['stop']       = array('Show until', 'If you enter a date here the current article will not be shown on the website after this day.');
$GLOBALS['TL_LANG']['tl_article']['tstamp']     = array('Revision date', 'Date and time of latest revision');


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
$GLOBALS['TL_LANG']['tl_article']['show']       = array('Article details', 'Show details of article ID %s');
$GLOBALS['TL_LANG']['tl_article']['edit']       = array('Edit article', 'Edit article ID %s');
$GLOBALS['TL_LANG']['tl_article']['copy']       = array('Duplicate article', 'Duplicate article ID %s');
$GLOBALS['TL_LANG']['tl_article']['cut']        = array('Move article', 'Move article ID %s');
$GLOBALS['TL_LANG']['tl_article']['delete']     = array('Delete article', 'Delete article ID %s');
$GLOBALS['TL_LANG']['tl_article']['pasteafter'] = array('Paste after', 'Paste after article ID %s');
$GLOBALS['TL_LANG']['tl_article']['pasteinto']  = array('Paste into', 'Paste into page ID %s');

?>