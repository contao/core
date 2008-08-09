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
$GLOBALS['TL_LANG']['tl_news']['headline']     = array('Headline', 'Please enter the headline of the news article.');
$GLOBALS['TL_LANG']['tl_news']['alias']        = array('News alias', 'A news alias is a unique reference to the news article which can be called instead of the news ID.');
$GLOBALS['TL_LANG']['tl_news']['date']         = array('Date', 'Please enter the date of the news article.');
$GLOBALS['TL_LANG']['tl_news']['time']         = array('Time', 'Please enter the time of the news article.');
$GLOBALS['TL_LANG']['tl_news']['subheadline']  = array('Subheadline', 'Here you can enter a subheadline here.');
$GLOBALS['TL_LANG']['tl_news']['teaser']       = array('Teaser text', 'The teaser text is usually shown instead of the actual news text followed by a "read more..." link.');
$GLOBALS['TL_LANG']['tl_news']['text']         = array('News text', 'Please enter the news text.');
$GLOBALS['TL_LANG']['tl_news']['addImage']     = array('Add an image', 'If you choose this option, an image will be added to the news article.');
$GLOBALS['TL_LANG']['tl_news']['author']       = array('Author', 'You can change the author of the news article.');
$GLOBALS['TL_LANG']['tl_news']['noComments']   = array('Disable comments', 'Disable comments for this particular news item.');
$GLOBALS['TL_LANG']['tl_news']['addEnclosure'] = array('Add enclosure', 'Add one or more downloadable files to the news item.');
$GLOBALS['TL_LANG']['tl_news']['enclosure']    = array('Enclosure', 'Please choose the files you want to attach.');
$GLOBALS['TL_LANG']['tl_news']['source']       = array('Target page', 'Link to an internal or external page instead of the default page.');
$GLOBALS['TL_LANG']['tl_news']['jumpTo']       = array('Jump to page', 'Please select the page to which visitors will be redirected.');
$GLOBALS['TL_LANG']['tl_news']['published']    = array('Published', 'The news article will not be visible on your website until it is published.');
$GLOBALS['TL_LANG']['tl_news']['start']        = array('Show from', 'If you enter a date here the current news item will not be shown on the website before this day.');
$GLOBALS['TL_LANG']['tl_news']['stop']         = array('Show until', 'If you enter a date here the current news item will not be shown on the website after this day.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_news']['default']  = array('Use default', 'By clicking the "read more..." button visitors will be redirected to the default page of the news archive.');
$GLOBALS['TL_LANG']['tl_news']['internal'] = array('Internal page', 'By clicking the "read more..." button visitors will be redirected to an internal page.');
$GLOBALS['TL_LANG']['tl_news']['external'] = array('External website', 'By clicking the "read more..." button visitors will be redirected to an external website.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news']['new']        = array('New article', 'Create a new news article');
$GLOBALS['TL_LANG']['tl_news']['edit']       = array('Edit article', 'Edit news article ID %s');
$GLOBALS['TL_LANG']['tl_news']['copy']       = array('Copy article', 'Copy news article ID %s');
$GLOBALS['TL_LANG']['tl_news']['delete']     = array('Delete article', 'Delete news article ID %s');
$GLOBALS['TL_LANG']['tl_news']['show']       = array('Article details', 'Show details of news article ID %s');
$GLOBALS['TL_LANG']['tl_news']['editheader'] = array('Edit archive header', 'Edit the header of this archive');

?>