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
$GLOBALS['TL_LANG']['tl_module']['name']           = array('Name of the module', 'Please enter a unique module name.');
$GLOBALS['TL_LANG']['tl_module']['type']           = array('Module type', 'Please select the current module type.');
$GLOBALS['TL_LANG']['tl_module']['headline']       = array('Headline', 'If you enter a headline, it will be shown on top of the module.');
$GLOBALS['TL_LANG']['tl_module']['singleSRC']      = array('Source file', 'Please select a file from the files directory.');
$GLOBALS['TL_LANG']['tl_module']['multiSRC']       = array('Source files', 'Please select one or more file from the files directory.');
$GLOBALS['TL_LANG']['tl_module']['levelOffset']    = array('Start level', 'Please define from which level the menu items shall be visible. Choose 0 or leave blank to start from the top level.');
$GLOBALS['TL_LANG']['tl_module']['showLevel']      = array('Stop level', 'Please define to which level menu items shall be visible. Choose 0 or leave blank to display all levels.');
$GLOBALS['TL_LANG']['tl_module']['hardLimit']      = array('Hard limit', 'Do not show any items (not even active ones) beyond the stop level.');
$GLOBALS['TL_LANG']['tl_module']['showProtected']  = array('Show protected items', 'Include items that are usually only visible after a front end user has logged in.');
$GLOBALS['TL_LANG']['tl_module']['showHidden']     = array('Show hidden items', 'Include items that are hidden from the navigation menu.');
$GLOBALS['TL_LANG']['tl_module']['navigationTpl']  = array('Navigation template', 'Please choose a navigation template (navigation templates start with <em>nav_</em>).');
$GLOBALS['TL_LANG']['tl_module']['includeRoot']    = array('Start from website root', 'Use the website root page as root node of the module.');
$GLOBALS['TL_LANG']['tl_module']['defineRoot']     = array('Set reference page', 'Set a reference page to be used as source or target page of the module.');
$GLOBALS['TL_LANG']['tl_module']['rootPage']       = array('Reference page', 'Here you can choose a reference page that will be used as source or target page (e.g. an alternative start page).');
$GLOBALS['TL_LANG']['tl_module']['customLabel']    = array('Custom label', 'Here you can enter a custom label to be used instead of <em>quicklink</em> or <em>quick navigation</em>.');
$GLOBALS['TL_LANG']['tl_module']['pages']          = array('Pages', 'Please choose the pages for the quick link module.');
$GLOBALS['TL_LANG']['tl_module']['queryType']      = array('Default query type', 'Please select the default query type.');
$GLOBALS['TL_LANG']['tl_module']['searchType']     = array('Search form', 'Please choose a search form.');
$GLOBALS['TL_LANG']['tl_module']['perPage']        = array('Records per page', 'Please enter the number of records per page (0 = disable pagination).');
$GLOBALS['TL_LANG']['tl_module']['searchTpl']      = array('Search results template', 'Please choose a search results template. Store your own <em>search_</em> templates in the <em>templates</em> directory.');
$GLOBALS['TL_LANG']['tl_module']['contextLength']  = array('Context range', 'The number of characters on the left and right side of each keyword that is used as context.');
$GLOBALS['TL_LANG']['tl_module']['totalLength']    = array('Maximum context length', 'Please enter the maximum context length of each result.');
$GLOBALS['TL_LANG']['tl_module']['editable']       = array('Editable fields', 'Please select one or more fields to be editable in the front end.');
$GLOBALS['TL_LANG']['tl_module']['newsletters']    = array('Newsletter channels', 'Please choose the channels the user can subscribe to during the registration progress.');
$GLOBALS['TL_LANG']['tl_module']['memberTpl']      = array('Form template', 'Please choose a form template. The default template is called <em>member_default</em>. If you want to add your own templates, upload them to the <em>templates</em> directory (file extension has to be <em>tpl</em>.');
$GLOBALS['TL_LANG']['tl_module']['cols']           = array('Number of columns', 'Please choose the number of columns of the form.');
$GLOBALS['TL_LANG']['tl_module']['1cl']            = array('One columns', 'the label of each input field will be shown above the input field.');
$GLOBALS['TL_LANG']['tl_module']['2cl']            = array('Two columns', 'the label of each input field will be shown on the left side of the input field.');
$GLOBALS['TL_LANG']['tl_module']['redirectBack']   = array('Redirect to last page visited', 'Redirect the user back to the last page visited after login/logout.');
$GLOBALS['TL_LANG']['tl_module']['jumpTo']         = array('Jump to page', 'This setting defines to which page a user will be redirected on a certain action (e.g. clicking a link or submitting a form).');
$GLOBALS['TL_LANG']['tl_module']['form']           = array('Form', 'Please select a form.');
$GLOBALS['TL_LANG']['tl_module']['html']           = array('HTML code', 'Please enter your HTML code.');
$GLOBALS['TL_LANG']['tl_module']['size']           = array('Width and height', 'Please enter the width and height in pixel.');
$GLOBALS['TL_LANG']['tl_module']['imgSize']        = array('Image width and height', 'If you enter only width or only height, the image will be resized proportionally. If you enter both measures, the image will be cropped if necessary. If you leave both fields blank, the original size will be displayed.');
$GLOBALS['TL_LANG']['tl_module']['alt']            = array('Alternative text', 'In order to make images or movies accessible, you should always provide an alternative text including a short description of their content.');
$GLOBALS['TL_LANG']['tl_module']['useCaption']     = array('Show caption', 'If you choose this option, the image name will be displayed below the image.');
$GLOBALS['TL_LANG']['tl_module']['source']         = array('Source', 'Please choose the source of the element.');
$GLOBALS['TL_LANG']['tl_module']['url']            = array('URL', 'Please enter the complete URL starting with http://.');
$GLOBALS['TL_LANG']['tl_module']['flashvars']      = array('FlashVars', 'Here you can enter some variables that will be passed to the Flash movie (<em>var1=value1&amp;var2=value2</em>).');
$GLOBALS['TL_LANG']['tl_module']['altContent']     = array('Alternative content', 'Please provide an alternative content that will be shown in case the Flash movie cannot be loaded. HTML is allowed.');
$GLOBALS['TL_LANG']['tl_module']['transparent']    = array('Transparent Flash movie', 'Choose this option to make the Flash movie transparent (wmode = transparent). Please note that buttons and text fields in a transparent Flash movie might not work correctly in some web browsers.');
$GLOBALS['TL_LANG']['tl_module']['interactive']    = array('Interactive Flash movie', 'Choose this option if your Flash movie interacts with the browser using JavaScript and the Flash function <em>fscommand()</em>.');
$GLOBALS['TL_LANG']['tl_module']['flashID']        = array('Flash movie ID', 'Please enter a unique Flash movie ID.');
$GLOBALS['TL_LANG']['tl_module']['version']        = array('Flash player version and build', 'Please enter the required Flash player version and build number (e.g. if your Flash movie requires at least Flash player 6.0.12.0, enter 6 and 12).');
$GLOBALS['TL_LANG']['tl_module']['flashJS']        = array('JavaScript _DoFSCommand(command, args) {', 'Please enter the content of the JavaScript <em>_DoFSCommand()</em> function. The variable containing the command is named <em>command</em>, the variable containing the arguments is named <em>args</em>.');
$GLOBALS['TL_LANG']['tl_module']['inColumn']       = array('Column', 'Please choose the column whose articles you want to list.');
$GLOBALS['TL_LANG']['tl_module']['skipFirst']      = array('Skip first article', 'Choose this option to exclude the first article from the article list.');
$GLOBALS['TL_LANG']['tl_module']['searchable']     = array('Searchable', 'Allow search indexing of the module (not available for protected modules).');
$GLOBALS['TL_LANG']['tl_module']['disableCaptcha'] = array('Disable security question', 'Choose this option to disable the security question (not recommended).');
$GLOBALS['TL_LANG']['tl_module']['protected']      = array('Protect module', 'Show module to certain member groups only.');
$GLOBALS['TL_LANG']['tl_module']['guests']         = array('Show to guests only', 'Hide the module if a member is logged in.');
$GLOBALS['TL_LANG']['tl_module']['groups']         = array('Allowed member groups', 'Here you can choose which groups will be allowed to see the module.');
$GLOBALS['TL_LANG']['tl_module']['space']          = array('Space in front and after', 'Please enter the spacing in front of and after the module in pixel.');
$GLOBALS['TL_LANG']['tl_module']['align']          = array('Module alignment', 'Here you can change the alignment of the module within its column.');
$GLOBALS['TL_LANG']['tl_module']['cssID']          = array('Style sheet ID and class', 'Here you can enter a style sheet ID (id attribute) and one or more style sheet classes (class attributes) to format the module element using CSS.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['and']      = array('Find all words', 'The search engine returns only pages that contain all keywords.');
$GLOBALS['TL_LANG']['tl_module']['or']       = array('Find any word', 'The search engine returns all pages that contain at least one of the keywords.');
$GLOBALS['TL_LANG']['tl_module']['simple']   = array('Simple', 'Simple search form containing a single input field only.');
$GLOBALS['TL_LANG']['tl_module']['advanced'] = array('Advanced', 'Advanced search form allowing to choose the query type dynamically.');
$GLOBALS['TL_LANG']['tl_module']['header']   = 'Header';
$GLOBALS['TL_LANG']['tl_module']['left']     = 'Left column';
$GLOBALS['TL_LANG']['tl_module']['main']     = 'Main column';
$GLOBALS['TL_LANG']['tl_module']['right']    = 'Right column';
$GLOBALS['TL_LANG']['tl_module']['footer']   = 'Footer';
$GLOBALS['TL_LANG']['tl_module']['internal'] = 'internal file';
$GLOBALS['TL_LANG']['tl_module']['external'] = 'external URL';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_module']['new']    = array('Add module', 'Add a module');
$GLOBALS['TL_LANG']['tl_module']['show']   = array('Module details', 'Show details of module ID %s');
$GLOBALS['TL_LANG']['tl_module']['copy']   = array('Duplicate module', 'Duplicate module ID %s');
$GLOBALS['TL_LANG']['tl_module']['delete'] = array('Delete module', 'Delete module ID %s');
$GLOBALS['TL_LANG']['tl_module']['edit']   = array('Edit module', 'Edit module ID %s');
$GLOBALS['TL_LANG']['tl_module']['up']     = array('Move item up', 'Move this item one position up');
$GLOBALS['TL_LANG']['tl_module']['down']   = array('Move item down', 'Move this item one position down');

?>