<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package News
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_news']['headline']     = array('Title', 'Please enter the news title.');
$GLOBALS['TL_LANG']['tl_news']['alias']        = array('News alias', 'The news alias is a unique reference to the article which can be called instead of its numeric ID.');
$GLOBALS['TL_LANG']['tl_news']['author']       = array('Author', 'Here you can change the author of the news item.');
$GLOBALS['TL_LANG']['tl_news']['date']         = array('Date', 'Please enter the date according to the global date format.');
$GLOBALS['TL_LANG']['tl_news']['time']         = array('Time', 'Please enter the time according to the global time format.');
$GLOBALS['TL_LANG']['tl_news']['subheadline']  = array('Subheadline', 'Here you can enter a subheadline.');
$GLOBALS['TL_LANG']['tl_news']['teaser']       = array('News teaser', 'The news teaser can be shown in a news list instead of the full article. A "read more …" link will be added automatically.');
$GLOBALS['TL_LANG']['tl_news']['text']         = array('News text', 'Here you can enter the news text.');
$GLOBALS['TL_LANG']['tl_news']['addImage']     = array('Add an image', 'Add an image to the news item.');
$GLOBALS['TL_LANG']['tl_news']['addEnclosure'] = array('Add enclosures', 'Add one or more downloadable files to the news item.');
$GLOBALS['TL_LANG']['tl_news']['enclosure']    = array('Enclosures', 'Please choose the files you want to attach.');
$GLOBALS['TL_LANG']['tl_news']['source']       = array('Redirect target', 'Here you can override the default redirect target.');
$GLOBALS['TL_LANG']['tl_news']['default']      = array('Use default', 'By clicking the "read more …" button, visitors will be redirected to the default page of the news archive.');
$GLOBALS['TL_LANG']['tl_news']['internal']     = array('Page', 'By clicking the "read more …" button, visitors will be redirected to a page.');
$GLOBALS['TL_LANG']['tl_news']['article']      = array('Article', 'By clicking the "read more …" button, visitors will be redirected to an article.');
$GLOBALS['TL_LANG']['tl_news']['external']     = array('External URL', 'By clicking the "read more …" button, visitors will be redirected to an external website.');
$GLOBALS['TL_LANG']['tl_news']['jumpTo']       = array('Redirect page', 'Please choose the page to which visitors will be redirected when clicking the news item.');
$GLOBALS['TL_LANG']['tl_news']['articleId']    = array('Article', 'Please choose the article to which visitors will be redirected when clicking the news item.');
$GLOBALS['TL_LANG']['tl_news']['cssClass']     = array('CSS class', 'Here you can enter one or more classes.');
$GLOBALS['TL_LANG']['tl_news']['noComments']   = array('Disable comments', 'Do not allow comments for this particular news item.');
$GLOBALS['TL_LANG']['tl_news']['featured']     = array('Feature item', 'Show the news item in a featured news list.');
$GLOBALS['TL_LANG']['tl_news']['published']    = array('Publish item', 'Make the news item publicly visible on the website.');
$GLOBALS['TL_LANG']['tl_news']['start']        = array('Show from', 'Do not show the news item on the website before this day.');
$GLOBALS['TL_LANG']['tl_news']['stop']         = array('Show until', 'Do not show the news item on the website on and after this day.');
$GLOBALS['TL_LANG']['tl_news']['tstamp']       = array('Revision date', 'Date and time of the latest revision');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news']['title_legend']     = 'Title and author';
$GLOBALS['TL_LANG']['tl_news']['date_legend']      = 'Date and time';
$GLOBALS['TL_LANG']['tl_news']['teaser_legend']    = 'Subheadline and teaser';
$GLOBALS['TL_LANG']['tl_news']['text_legend']      = 'News text';
$GLOBALS['TL_LANG']['tl_news']['image_legend']     = 'Image settings';
$GLOBALS['TL_LANG']['tl_news']['enclosure_legend'] = 'Enclosures';
$GLOBALS['TL_LANG']['tl_news']['source_legend']    = 'Redirect target';
$GLOBALS['TL_LANG']['tl_news']['expert_legend']    = 'Expert settings';
$GLOBALS['TL_LANG']['tl_news']['publish_legend']   = 'Publish settings';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news']['new']        = array('New article', 'Create a new news article');
$GLOBALS['TL_LANG']['tl_news']['show']       = array('Article details', 'Show the details of news article ID %s');
$GLOBALS['TL_LANG']['tl_news']['edit']       = array('Edit article', 'Edit news article ID %s');
$GLOBALS['TL_LANG']['tl_news']['copy']       = array('Duplicate article', 'Duplicate news article ID %s');
$GLOBALS['TL_LANG']['tl_news']['cut']        = array('Move article', 'Move news article ID %s');
$GLOBALS['TL_LANG']['tl_news']['delete']     = array('Delete article', 'Delete news article ID %s');
$GLOBALS['TL_LANG']['tl_news']['toggle']     = array('Publish/unpublish article', 'Publish/unpublish news article ID %s');
$GLOBALS['TL_LANG']['tl_news']['feature']    = array('Feature/unfeature article', 'Feature/unfeature news article ID %s');
$GLOBALS['TL_LANG']['tl_news']['editheader'] = array('Edit archive settings', 'Edit the archive settings');
$GLOBALS['TL_LANG']['tl_news']['editmeta']   = array('Edit article settings', 'Edit the article settings');
$GLOBALS['TL_LANG']['tl_news']['pasteafter'] = array('Paste into this archive', 'Paste after news article ID %s');
