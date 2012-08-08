<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_layout']['name']           = array('Title', 'Please enter the layout title.');
$GLOBALS['TL_LANG']['tl_layout']['fallback']       = array('Default layout', 'Make the layout the default layout.');
$GLOBALS['TL_LANG']['tl_layout']['rows']           = array('Rows', 'Please choose the number of rows.');
$GLOBALS['TL_LANG']['tl_layout']['1rw']            = array('Main row only', 'Show only one row.');
$GLOBALS['TL_LANG']['tl_layout']['2rwh']           = array('Header and main row', 'Show a header above the main row.');
$GLOBALS['TL_LANG']['tl_layout']['2rwf']           = array('Main row and footer', 'Show a footer below the main row.');
$GLOBALS['TL_LANG']['tl_layout']['3rw']            = array('Header, main row and footer', 'Show a header above and a footer below the main row.');
$GLOBALS['TL_LANG']['tl_layout']['headerHeight']   = array('Header height', 'Please enter the height of the page header.');
$GLOBALS['TL_LANG']['tl_layout']['footerHeight']   = array('Footer height', 'Please enter the height of the page footer.');
$GLOBALS['TL_LANG']['tl_layout']['cols']           = array('Columns', 'Please choose the number of columns.');
$GLOBALS['TL_LANG']['tl_layout']['1cl']            = array('Main column only', 'Show only one column.');
$GLOBALS['TL_LANG']['tl_layout']['2cll']           = array('Left and main column', 'Show two columns with the main column on the right side.');
$GLOBALS['TL_LANG']['tl_layout']['2clr']           = array('Main and right column', 'Show two columns with the main column on the left side.');
$GLOBALS['TL_LANG']['tl_layout']['3cl']            = array('Main, left and right column', 'Show three columns with the main column in the middle.');
$GLOBALS['TL_LANG']['tl_layout']['widthLeft']      = array('Left column width', 'Please enter the width of the left column.');
$GLOBALS['TL_LANG']['tl_layout']['widthRight']     = array('Right column width', 'Please enter the width of the right column.');
$GLOBALS['TL_LANG']['tl_layout']['sections']       = array('Custom layout sections', 'Custom layout sections can be defined in the back end settings.');
$GLOBALS['TL_LANG']['tl_layout']['sPosition']      = array('Custom sections position', 'Please select the position of the custom layout sections.');
$GLOBALS['TL_LANG']['tl_layout']['stylesheet']     = array('Style sheets', 'Please select the style sheets you want to add to the layout.');
$GLOBALS['TL_LANG']['tl_layout']['framework']      = array('CSS framework', 'Here you can activate the components of the Contao CSS framework.');
$GLOBALS['TL_LANG']['tl_layout']['reset.css']      = array('CSS reset', 'Removes the inconsistent default styling of HTML elements in different browsers.');
$GLOBALS['TL_LANG']['tl_layout']['layout.css']     = array('Layout builder', 'Generates the CSS layout based on the page layout settings. This component is required for the page layout generator to work!');
$GLOBALS['TL_LANG']['tl_layout']['responsive.css'] = array('Responsive grid', 'Adds a responsive 12-column grid that is triggered by the CSS classes "grid1" to "grid12" and "offset1" to "offset12".');
$GLOBALS['TL_LANG']['tl_layout']['tinymce.css']    = array('TinyMCE style sheet', 'Includes the <em>tinymce.css</em> style sheet from your upload directory.');
$GLOBALS['TL_LANG']['tl_layout']['external']       = array('Additional style sheets', 'Here you can add style sheets from the file system (e.g. <em>files/css/style.css|screen|static</em>).');
$GLOBALS['TL_LANG']['tl_layout']['orderExt']       = array('Sort order', 'The sort order of the style sheets.');
$GLOBALS['TL_LANG']['tl_layout']['webfonts']       = array('Google web fonts', 'Here you can add Google web fonts to your website. Specify the font families without the base URL (e.g. <em>Ubuntu|Ubuntu+Mono</em>).');
$GLOBALS['TL_LANG']['tl_layout']['newsfeeds']      = array('News feeds', 'Please select the news feeds you want to add to the layout.');
$GLOBALS['TL_LANG']['tl_layout']['calendarfeeds']  = array('Calendar feeds', 'Please select the calendar feeds you want to add to the layout.');
$GLOBALS['TL_LANG']['tl_layout']['modules']        = array('Included modules', 'If JavaScript is disabled, make sure to save your changes before modifying the order.');
$GLOBALS['TL_LANG']['tl_layout']['template']       = array('Page template', 'Here you can select the page template.');
$GLOBALS['TL_LANG']['tl_layout']['doctype']        = array('Output format', 'Here you can set the output format.');
$GLOBALS['TL_LANG']['tl_layout']['cssClass']       = array('Body class', 'Here you can add custom classes to the body tag.');
$GLOBALS['TL_LANG']['tl_layout']['onload']         = array('Body onload', 'Here you can add a body onload attribute.');
$GLOBALS['TL_LANG']['tl_layout']['head']           = array('Additional &lt;head&gt; tags', 'Here you can add individual tags to the head section of the page.');
$GLOBALS['TL_LANG']['tl_layout']['addJQuery']      = array('Include jQuery', 'Include the jQuery library in the layout.');
$GLOBALS['TL_LANG']['tl_layout']['jSource']        = array('jQuery source', 'Here you can select from where to load the jQuery script.');
$GLOBALS['TL_LANG']['tl_layout']['jquery']         = array('jQuery templates', 'Here you can select one or more jQuery templates.');
$GLOBALS['TL_LANG']['tl_layout']['addMooTools']    = array('Include MooTools', 'Include the MooTools library in the layout.');
$GLOBALS['TL_LANG']['tl_layout']['mooSource']      = array('MooTools source', 'Here you can select from where to load the MooTools scripts.');
$GLOBALS['TL_LANG']['tl_layout']['mootools']       = array('MooTools templates', 'Here you can select one or more MooTools templates.');
$GLOBALS['TL_LANG']['tl_layout']['analytics']      = array('Analytics templates', 'Here you can activate the analytics templates (e.g. for Google Analytics or Piwik). Note that you have to adjust those templates and add your analytics ID before you can use them!');
$GLOBALS['TL_LANG']['tl_layout']['script']         = array('Custom JavaScript code', 'The JavaScript code which will be inserted at the bottom of the page.');
$GLOBALS['TL_LANG']['tl_layout']['static']         = array('Static layout', 'Create a static layout with a fixed width and alignment.');
$GLOBALS['TL_LANG']['tl_layout']['width']          = array('Overall width', 'The overall width will be applied to the wrapper element.');
$GLOBALS['TL_LANG']['tl_layout']['align']          = array('Alignment', 'Please select the alignment of the page.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_layout']['title_legend']    = 'Title';
$GLOBALS['TL_LANG']['tl_layout']['header_legend']   = 'Rows';
$GLOBALS['TL_LANG']['tl_layout']['column_legend']   = 'Columns';
$GLOBALS['TL_LANG']['tl_layout']['sections_legend'] = 'Custom sections';
$GLOBALS['TL_LANG']['tl_layout']['style_legend']    = 'Style sheets';
$GLOBALS['TL_LANG']['tl_layout']['feed_legend']     = 'RSS/Atom feeds';
$GLOBALS['TL_LANG']['tl_layout']['modules_legend']  = 'Front end modules';
$GLOBALS['TL_LANG']['tl_layout']['expert_legend']   = 'Expert settings';
$GLOBALS['TL_LANG']['tl_layout']['script_legend']   = 'Script settings';
$GLOBALS['TL_LANG']['tl_layout']['static_legend']   = 'Static layout';
$GLOBALS['TL_LANG']['tl_layout']['jquery_legend']   = 'jQuery';
$GLOBALS['TL_LANG']['tl_layout']['mootools_legend'] = 'MooTools';
$GLOBALS['TL_LANG']['tl_layout']['j_local']         = 'jQuery - local file';
$GLOBALS['TL_LANG']['tl_layout']['j_googleapis']    = 'jQuery - googleapis.com';
$GLOBALS['TL_LANG']['tl_layout']['j_fallback']      = 'jQuery - googleapis.com with local fallback';
$GLOBALS['TL_LANG']['tl_layout']['moo_local']       = 'MooTools - local file';
$GLOBALS['TL_LANG']['tl_layout']['moo_googleapis']  = 'MooTools - googleapis.com';
$GLOBALS['TL_LANG']['tl_layout']['moo_fallback']    = 'MooTools - googleapis.com with local fallback';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_layout']['html5']            = 'HTML';
$GLOBALS['TL_LANG']['tl_layout']['xhtml_strict']     = 'XHTML Strict';
$GLOBALS['TL_LANG']['tl_layout']['xhtml_trans']      = 'XHTML Transitional';
$GLOBALS['TL_LANG']['tl_layout']['before']           = 'After the page header';
$GLOBALS['TL_LANG']['tl_layout']['main']             = 'Inside the main column';
$GLOBALS['TL_LANG']['tl_layout']['after']            = 'Before the page footer';
$GLOBALS['TL_LANG']['tl_layout']['edit_styles']      = 'Edit the style sheets';
$GLOBALS['TL_LANG']['tl_layout']['edit_module']      = 'Edit the module';
$GLOBALS['TL_LANG']['tl_layout']['analytics_google'] = 'Google';
$GLOBALS['TL_LANG']['tl_layout']['analytics_piwik']  = 'Piwik';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_layout']['new']        = array('New layout', 'Create a new layout');
$GLOBALS['TL_LANG']['tl_layout']['show']       = array('Layout details', 'Show the details of layout ID %s');
$GLOBALS['TL_LANG']['tl_layout']['edit']       = array('Edit layout', 'Edit layout ID %s');
$GLOBALS['TL_LANG']['tl_layout']['cut']        = array('Move layout', 'Move layout ID %s');
$GLOBALS['TL_LANG']['tl_layout']['copy']       = array('Duplicate layout', 'Duplicate layout ID %s');
$GLOBALS['TL_LANG']['tl_layout']['delete']     = array('Delete layout', 'Delete layout ID %s');
$GLOBALS['TL_LANG']['tl_layout']['editheader'] = array('Edit theme', 'Edit the theme settings');
