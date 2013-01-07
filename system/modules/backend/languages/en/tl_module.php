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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['name']           = array('Title', 'Please enter the module title.');
$GLOBALS['TL_LANG']['tl_module']['headline']       = array('Headline', 'Here you can add a headline to the module.');
$GLOBALS['TL_LANG']['tl_module']['type']           = array('Module type', 'Please choose the type of module.');
$GLOBALS['TL_LANG']['tl_module']['levelOffset']    = array('Start level', 'Enter a value greater than 0 to show only submenu items.');
$GLOBALS['TL_LANG']['tl_module']['showLevel']      = array('Stop level', 'Enter a value greater than 0 to limit the nesting level of the menu.');
$GLOBALS['TL_LANG']['tl_module']['hardLimit']      = array('Hard limit', 'Never show any menu items beyond the stop level.');
$GLOBALS['TL_LANG']['tl_module']['showProtected']  = array('Show protected items', 'Show items that are usually only visible to authenticated users.');
$GLOBALS['TL_LANG']['tl_module']['defineRoot']     = array('Set a reference page', 'Define a custom source or target page for the module.');
$GLOBALS['TL_LANG']['tl_module']['rootPage']       = array('Reference page', 'Please choose the reference page from the site structure.');
$GLOBALS['TL_LANG']['tl_module']['navigationTpl']  = array('Navigation template', 'Here you can select the navigation template.');
$GLOBALS['TL_LANG']['tl_module']['pages']          = array('Pages', 'Please choose one or more pages from the site structure.');
$GLOBALS['TL_LANG']['tl_module']['showHidden']     = array('Show hidden items', 'Show items that are usually hidden in the navigation menu.');
$GLOBALS['TL_LANG']['tl_module']['customLabel']    = array('Custom label', 'Here you can enter a custom label for the drop-down menu.');
$GLOBALS['TL_LANG']['tl_module']['autologin']      = array('Allow auto login', 'Allow members to log into the front end automatically.');
$GLOBALS['TL_LANG']['tl_module']['jumpTo']         = array('Redirect page', 'Please choose the page to which visitors will be redirected when clicking a link or submitting a form.');
$GLOBALS['TL_LANG']['tl_module']['redirectBack']   = array('Redirect to last page visited', 'Redirect the user back to the last page visited instead of the redirect page.');
$GLOBALS['TL_LANG']['tl_module']['cols']           = array('Number of columns', 'Please choose the number of columns of the form.');
$GLOBALS['TL_LANG']['tl_module']['1cl']            = array('One column', 'Show the label above the input field.');
$GLOBALS['TL_LANG']['tl_module']['2cl']            = array('Two columns', 'Show the label on the left side of the input field.');
$GLOBALS['TL_LANG']['tl_module']['editable']       = array('Editable fields', 'Show these fields in the front end form.');
$GLOBALS['TL_LANG']['tl_module']['memberTpl']      = array('Form template', 'Here you can select the form template.');
$GLOBALS['TL_LANG']['tl_module']['tableless']      = array('Tableless layout', 'Render the form without HTML tables.');
$GLOBALS['TL_LANG']['tl_module']['form']           = array('Form', 'Please select a form.');
$GLOBALS['TL_LANG']['tl_module']['queryType']      = array('Default query type', 'Please select the default query type.');
$GLOBALS['TL_LANG']['tl_module']['and']            = array('Find all words', 'Returns only pages that contain all keywords.');
$GLOBALS['TL_LANG']['tl_module']['or']             = array('Find any word', 'Returns all pages that contain any of the keywords.');
$GLOBALS['TL_LANG']['tl_module']['fuzzy']          = array('Fuzzy search', 'Will find "Contao" if you search for "con" (equal to a wildcard search).');
$GLOBALS['TL_LANG']['tl_module']['simple']         = array('Simple form', 'Contains a single input field only.');
$GLOBALS['TL_LANG']['tl_module']['advanced']       = array('Advanced form', 'Contains an input field and a radio button menu to choose the query type.');
$GLOBALS['TL_LANG']['tl_module']['contextLength']  = array('Context range', 'The number of characters on the left and right side of each keyword that are used as context.');
$GLOBALS['TL_LANG']['tl_module']['totalLength']    = array('Maximum context length', 'Here you can limit the overall context length per result.');
$GLOBALS['TL_LANG']['tl_module']['perPage']        = array('Items per page', 'The number of items per page. Set to 0 to disable pagination.');
$GLOBALS['TL_LANG']['tl_module']['searchType']     = array('Search form layout', 'Here you can select the search form layout.');
$GLOBALS['TL_LANG']['tl_module']['searchTpl']      = array('Search results template', 'Here you can select the search results template.');
$GLOBALS['TL_LANG']['tl_module']['inColumn']       = array('Column', 'Please choose the column whose articles you want to list.');
$GLOBALS['TL_LANG']['tl_module']['skipFirst']      = array('Skip items', 'Here you can define how many items will be skipped.');
$GLOBALS['TL_LANG']['tl_module']['loadFirst']      = array('Load the first item', 'Automatically redirect to the first item if none is selected.');
$GLOBALS['TL_LANG']['tl_module']['size']           = array('Width and height', 'Please enter the width and height in pixel.');
$GLOBALS['TL_LANG']['tl_module']['transparent']    = array('Transparent movie', 'Make the Flash movie transparent (wmode = transparent).');
$GLOBALS['TL_LANG']['tl_module']['flashvars']      = array('FlashVars', 'Pass variables to the Flash movie (<em>var1=value1&amp;var2=value2</em>).');
$GLOBALS['TL_LANG']['tl_module']['version']        = array('Flash player version', 'Please enter the required Flash player version (e.g. 6.0.12).');
$GLOBALS['TL_LANG']['tl_module']['altContent']     = array('Alternate content', 'The alternate content will be shown if the movie cannot be loaded. HTML tags are allowed.');
$GLOBALS['TL_LANG']['tl_module']['source']         = array('Source', 'Whether to use a file on the server or point to an external URL.');
$GLOBALS['TL_LANG']['tl_module']['singleSRC']      = array('Source file', 'Please select a file from the files directory.');
$GLOBALS['TL_LANG']['tl_module']['url']            = array('URL', 'Please enter the URL (http://…) of the Flash movie.');
$GLOBALS['TL_LANG']['tl_module']['interactive']    = array('Make interactive', 'Make the Flash movie interact with the browser (requires JavaScript).');
$GLOBALS['TL_LANG']['tl_module']['flashID']        = array('Flash movie ID', 'Please enter a unique Flash movie ID.');
$GLOBALS['TL_LANG']['tl_module']['flashJS']        = array('JavaScript _DoFSCommand(command, args) {', 'Please enter the JavaScript code.');
$GLOBALS['TL_LANG']['tl_module']['fullsize']       = array('Full-size view/new window', 'Open the full-size image in a lightbox or the link in a new browser window.');
$GLOBALS['TL_LANG']['tl_module']['imgSize']        = array('Image width and height', 'Here you can set the image dimensions and the resize mode.');
$GLOBALS['TL_LANG']['tl_module']['useCaption']     = array('Show caption', 'Display the image name or caption below the image.');
$GLOBALS['TL_LANG']['tl_module']['multiSRC']       = array('Source files', 'Please select one or more files from the files directory.');
$GLOBALS['TL_LANG']['tl_module']['html']           = array('HTML code', 'You can modify the list of allowed HTML tags in the back end settings.');
$GLOBALS['TL_LANG']['tl_module']['protected']      = array('Protect module', 'Show the module to certain member groups only.');
$GLOBALS['TL_LANG']['tl_module']['groups']         = array('Allowed member groups', 'These groups will be able to see the module.');
$GLOBALS['TL_LANG']['tl_module']['guests']         = array('Show to guests only', 'Hide the module if a member is logged in.');
$GLOBALS['TL_LANG']['tl_module']['cssID']          = array('CSS ID/class', 'Here you can set an ID and one or more classes.');
$GLOBALS['TL_LANG']['tl_module']['space']          = array('Space in front and after', 'Here you can enter the spacing in front of and after the module in pixel. You should try to avoid inline styles and define the spacing in a style sheet, though.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_module']['title_legend']     = 'Title and type';
$GLOBALS['TL_LANG']['tl_module']['nav_legend']       = 'Navigation settings';
$GLOBALS['TL_LANG']['tl_module']['reference_legend'] = 'Reference page';
$GLOBALS['TL_LANG']['tl_module']['redirect_legend']  = 'Redirect settings';
$GLOBALS['TL_LANG']['tl_module']['template_legend']  = 'Template settings';
$GLOBALS['TL_LANG']['tl_module']['config_legend']    = 'Module configuration';
$GLOBALS['TL_LANG']['tl_module']['include_legend']   = 'Include settings';
$GLOBALS['TL_LANG']['tl_module']['source_legend']    = 'Files and folders';
$GLOBALS['TL_LANG']['tl_module']['interact_legend']  = 'Interactive Flash movie';
$GLOBALS['TL_LANG']['tl_module']['html_legend']      = 'Text/HTML';
$GLOBALS['TL_LANG']['tl_module']['protected_legend'] = 'Access protection';
$GLOBALS['TL_LANG']['tl_module']['expert_legend']    = 'Expert settings';
$GLOBALS['TL_LANG']['tl_module']['email_legend']     = 'E-mail settings';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['header']   = 'Header';
$GLOBALS['TL_LANG']['tl_module']['left']     = 'Left column';
$GLOBALS['TL_LANG']['tl_module']['main']     = 'Main column';
$GLOBALS['TL_LANG']['tl_module']['right']    = 'Right column';
$GLOBALS['TL_LANG']['tl_module']['footer']   = 'Footer';
$GLOBALS['TL_LANG']['tl_module']['internal'] = 'Internal file';
$GLOBALS['TL_LANG']['tl_module']['external'] = 'External URL';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_module']['new']        = array('Add module', 'Add a module');
$GLOBALS['TL_LANG']['tl_module']['show']       = array('Module details', 'Show the details of module ID %s');
$GLOBALS['TL_LANG']['tl_module']['edit']       = array('Edit module', 'Edit module ID %s');
$GLOBALS['TL_LANG']['tl_module']['cut']        = array('Move module', 'Move module ID %s');
$GLOBALS['TL_LANG']['tl_module']['copy']       = array('Duplicate module', 'Duplicate module ID %s');
$GLOBALS['TL_LANG']['tl_module']['delete']     = array('Delete module', 'Delete module ID %s');
$GLOBALS['TL_LANG']['tl_module']['editheader'] = array('Edit theme', 'Edit the theme settings');
$GLOBALS['TL_LANG']['tl_module']['pasteafter'] = array('Paste here', 'Paste after module ID %s');

?>