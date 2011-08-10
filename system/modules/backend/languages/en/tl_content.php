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
$GLOBALS['TL_LANG']['tl_content']['invisible']    = array('Invisible', 'Hide the element on the website.');
$GLOBALS['TL_LANG']['tl_content']['type']         = array('Element type', 'Please choose the type of content element.');
$GLOBALS['TL_LANG']['tl_content']['headline']     = array('Headline', 'Here you can add a headline to the content element.');
$GLOBALS['TL_LANG']['tl_content']['text']         = array('Text', 'You can use HTML tags to format the text.');
$GLOBALS['TL_LANG']['tl_content']['addImage']     = array('Add an image', 'Add an image to the content element.');
$GLOBALS['TL_LANG']['tl_content']['singleSRC']    = array('Source file', 'Please select a file or folder from the files directory.');
$GLOBALS['TL_LANG']['tl_content']['alt']          = array('Alternate text', 'An accessible website should always provide an alternate text for images and movies with a short description of their content.');
$GLOBALS['TL_LANG']['tl_content']['size']         = array('Image width and height', 'Here you can set the image dimensions and the resize mode.');
$GLOBALS['TL_LANG']['tl_content']['imagemargin']  = array('Image margin', 'Here you can enter the top, right, bottom and left margin.');
$GLOBALS['TL_LANG']['tl_content']['imageUrl']     = array('Image link target', 'A custom image link target will override the lightbox link, so the image cannot be viewed fullsize anymore.');
$GLOBALS['TL_LANG']['tl_content']['fullsize']     = array('Full-size view/new window', 'Open the full-size image in a lightbox or the link in a new browser window.');
$GLOBALS['TL_LANG']['tl_content']['floating']     = array('Image alignment', 'Please specify how to align the image.');
$GLOBALS['TL_LANG']['tl_content']['caption']      = array('Image caption', 'Here you can enter a short text that will be displayed below the image.');
$GLOBALS['TL_LANG']['tl_content']['html']         = array('HTML code', 'You can modify the list of allowed HTML tags in the back end settings.');
$GLOBALS['TL_LANG']['tl_content']['listtype']     = array('List type', 'Please choose the type of list.');
$GLOBALS['TL_LANG']['tl_content']['listitems']    = array('List items', 'If JavaScript is disabled, make sure to save your changes before modifying the order.');
$GLOBALS['TL_LANG']['tl_content']['tableitems']   = array('Table items', 'If JavaScript is disabled, make sure to save your changes before modifying the order.');
$GLOBALS['TL_LANG']['tl_content']['summary']      = array('Table summary', 'Please enter a short summary of the table and describe its purpose or structure.');
$GLOBALS['TL_LANG']['tl_content']['thead']        = array('Add table header', 'Make the first row of the table the table header.');
$GLOBALS['TL_LANG']['tl_content']['tfoot']        = array('Add table footer', 'Make the last row of the table the table footer.');
$GLOBALS['TL_LANG']['tl_content']['tleft']        = array('Use row headers', 'Define the left column as row header.');
$GLOBALS['TL_LANG']['tl_content']['sortable']     = array('Sortable table', 'Make the table sortable (requires JavaScript and a table header).');
$GLOBALS['TL_LANG']['tl_content']['sortIndex']    = array('Sort index', 'The number of the default sort column.');
$GLOBALS['TL_LANG']['tl_content']['sortOrder']    = array('Sort order', 'Please choose the sort order.');
$GLOBALS['TL_LANG']['tl_content']['mooType']      = array('Operation mode', 'Please select the operation mode of the accordion element.');
$GLOBALS['TL_LANG']['tl_content']['single']       = array('Single element', 'Behaves like a single text element that is inside an accordion pane.');
$GLOBALS['TL_LANG']['tl_content']['start']        = array('Wrapper start', 'Marks the beginning of an accordion pane that spans several content elements.');
$GLOBALS['TL_LANG']['tl_content']['stop']         = array('Wrapper stop', 'Marks the end of an accordion pane that spans several content elements.');
$GLOBALS['TL_LANG']['tl_content']['mooHeadline']  = array('Section headline', 'Please enter the headline of the content pane. HTML tags are allowed.');
$GLOBALS['TL_LANG']['tl_content']['mooStyle']     = array('CSS format', 'Here you can format the section headline using CSS code.');
$GLOBALS['TL_LANG']['tl_content']['mooClasses']   = array('Element classes', 'Leave blank to use the default classes or enter a custom toggler and accordion class.');
$GLOBALS['TL_LANG']['tl_content']['shClass']      = array('Configuration', 'Here you can configure the syntax highlighter (e.g. <em>gutter: false;</em>).');
$GLOBALS['TL_LANG']['tl_content']['highlight']    = array('Syntax highlighting', 'Please choose a scripting language.');
$GLOBALS['TL_LANG']['tl_content']['code']         = array('Code', 'Note that the code will not be executed.');
$GLOBALS['TL_LANG']['tl_content']['linkTitle']    = array('Link title', 'The link title will be displayed instead of the target URL.');
$GLOBALS['TL_LANG']['tl_content']['embed']        = array('Embed the link', 'Use the wildcard "%s" to embed the link in a phrase (e.g. <em>For more information please visit %s</em>).');
$GLOBALS['TL_LANG']['tl_content']['rel']          = array('Lightbox', 'To trigger the lightbox, enter a rel attribute here.');
$GLOBALS['TL_LANG']['tl_content']['useImage']     = array('Create an image link', 'Use an image instead of the link title.');
$GLOBALS['TL_LANG']['tl_content']['multiSRC']     = array('Source files', 'Please select one or more files or folders from the files directory. If you select a folder, its files will be included automatically.');
$GLOBALS['TL_LANG']['tl_content']['useHomeDir']   = array('Use home directory', 'Use the home directory as file source if there is an authenticated user.');
$GLOBALS['TL_LANG']['tl_content']['perRow']       = array('Thumbnails per row', 'The number of image thumbnails per row.');
$GLOBALS['TL_LANG']['tl_content']['perPage']      = array('Items per page', 'The number of items per page. Set to 0 to disable pagination.');
$GLOBALS['TL_LANG']['tl_content']['sortBy']       = array('Order by', 'Please choose the sort order.');
$GLOBALS['TL_LANG']['tl_content']['galleryTpl']   = array('Gallery template', 'Here you can select the gallery template.');
$GLOBALS['TL_LANG']['tl_content']['cteAlias']     = array('Referenced element', 'Please choose the content element you want to insert.');
$GLOBALS['TL_LANG']['tl_content']['articleAlias'] = array('Referenced article', 'Please choose the article you want to insert.');
$GLOBALS['TL_LANG']['tl_content']['article']      = array('Article', 'Please select an article.');
$GLOBALS['TL_LANG']['tl_content']['form']         = array('Form', 'Please select a form.');
$GLOBALS['TL_LANG']['tl_content']['module']       = array('Module', 'Please select a module.');
$GLOBALS['TL_LANG']['tl_content']['protected']    = array('Protect element', 'Show the content element to certain member groups only.');
$GLOBALS['TL_LANG']['tl_content']['groups']       = array('Allowed member groups', 'These groups will be able to see the content element.');
$GLOBALS['TL_LANG']['tl_content']['guests']       = array('Show to guests only', 'Hide the content element if a member is logged in.');
$GLOBALS['TL_LANG']['tl_content']['cssID']        = array('CSS ID/class', 'Here you can set an ID and one or more classes.');
$GLOBALS['TL_LANG']['tl_content']['space']        = array('Space in front and after', 'Here you can enter the spacing in front of and after the content element in pixel. You should try to avoid inline styles and define the spacing in a style sheet, though.');



/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_content']['type_legend']      = 'Element type';
$GLOBALS['TL_LANG']['tl_content']['text_legend']      = 'Text/HTML/Code';
$GLOBALS['TL_LANG']['tl_content']['image_legend']     = 'Image settings';
$GLOBALS['TL_LANG']['tl_content']['list_legend']      = 'List items';
$GLOBALS['TL_LANG']['tl_content']['table_legend']     = 'Table items';
$GLOBALS['TL_LANG']['tl_content']['tconfig_legend']   = 'Table configuration';
$GLOBALS['TL_LANG']['tl_content']['sortable_legend']  = 'Sorting options';
$GLOBALS['TL_LANG']['tl_content']['moo_legend']       = 'Accordion settings';
$GLOBALS['TL_LANG']['tl_content']['link_legend']      = 'Hyperlink settings';
$GLOBALS['TL_LANG']['tl_content']['imglink_legend']   = 'Image link settings';
$GLOBALS['TL_LANG']['tl_content']['source_legend']    = 'Files and folders';
$GLOBALS['TL_LANG']['tl_content']['dwnconfig_legend'] = 'Download settings';
$GLOBALS['TL_LANG']['tl_content']['include_legend']   = 'Include settings';
$GLOBALS['TL_LANG']['tl_content']['protected_legend'] = 'Access protection';
$GLOBALS['TL_LANG']['tl_content']['expert_legend']    = 'Expert settings';
$GLOBALS['TL_LANG']['tl_content']['template_legend']  = 'Template settings';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_content']['ordered']   = 'ordered list';
$GLOBALS['TL_LANG']['tl_content']['unordered'] = 'unordered list';
$GLOBALS['TL_LANG']['tl_content']['name_asc']  = 'File name (ascending)';
$GLOBALS['TL_LANG']['tl_content']['name_desc'] = 'File name (descending)';
$GLOBALS['TL_LANG']['tl_content']['date_asc']  = 'Date (ascending)';
$GLOBALS['TL_LANG']['tl_content']['date_desc'] = 'Date (descending)';
$GLOBALS['TL_LANG']['tl_content']['meta']      = 'Meta file (meta.txt)';
$GLOBALS['TL_LANG']['tl_content']['random']    = 'Random order';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_content']['new']         = array('New element', 'Add a new content element');
$GLOBALS['TL_LANG']['tl_content']['show']        = array('Element details', 'Show the details of content element ID %s');
$GLOBALS['TL_LANG']['tl_content']['cut']         = array('Move element', 'Move content element ID %s');
$GLOBALS['TL_LANG']['tl_content']['copy']        = array('Duplicate element', 'Duplicate content element ID %s');
$GLOBALS['TL_LANG']['tl_content']['delete']      = array('Delete element', 'Delete content element ID %s');
$GLOBALS['TL_LANG']['tl_content']['edit']        = array('Edit element', 'Edit content element ID %s');
$GLOBALS['TL_LANG']['tl_content']['editheader']  = array('Edit article settings', 'Edit the settings of the article');
$GLOBALS['TL_LANG']['tl_content']['pasteafter']  = array('Paste at the top', 'Paste after content element ID %s');
$GLOBALS['TL_LANG']['tl_content']['pastenew']    = array('Add new at the top', 'Add new after content element ID %s');
$GLOBALS['TL_LANG']['tl_content']['toggle']      = array('Toggle visibility', 'Toggle the visibility of element ID %s');
$GLOBALS['TL_LANG']['tl_content']['editalias']   = array('Edit source element', 'Edit the source element ID %s');
$GLOBALS['TL_LANG']['tl_content']['editarticle'] = array('Edit article', 'Edit article ID %s');

?>