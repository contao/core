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
$GLOBALS['TL_LANG']['tl_content']['type']         = array('Element type', 'Please choose the content element type.');
$GLOBALS['TL_LANG']['tl_content']['headline']     = array('Headline', 'If you enter a headline, it will be shown on top of the content element.');
$GLOBALS['TL_LANG']['tl_content']['text']         = array('Text', 'Please enter the text (you can use HTML tags).');
$GLOBALS['TL_LANG']['tl_content']['html']         = array('HTML', 'Please enter the HTML code.');
$GLOBALS['TL_LANG']['tl_content']['code']         = array('Code', 'Please enter the code.');
$GLOBALS['TL_LANG']['tl_content']['highlight']    = array('Syntax highlighting', 'Please choose a scripting language.');
$GLOBALS['TL_LANG']['tl_content']['addImage']     = array('Add an image', 'If you choose this option, an image will be added to the element.');
$GLOBALS['TL_LANG']['tl_content']['floating']     = array('Image alignment', 'Please choose the image alignment. An image can be displayed above or on the top left or top right side of the text.');
$GLOBALS['TL_LANG']['tl_content']['imagemargin']  = array('Image margin', 'Please enter the top, right, bottom and left margin and the unit. Image margin is the space inbetween an image and its neighbour elements.');
$GLOBALS['TL_LANG']['tl_content']['singleSRC']    = array('Source file', 'Please select a file or a folder from the files directory.');
$GLOBALS['TL_LANG']['tl_content']['alt']          = array('Alternative text', 'In order to make images or movies accessible, you should always provide an alternative text including a short description of their content.');
$GLOBALS['TL_LANG']['tl_content']['caption']      = array('Image caption', 'If you enter a short text here, it will be displayed below the image. Leave this field blank to disable the feature.');
$GLOBALS['TL_LANG']['tl_content']['size']         = array('Image width and height', 'Please enter either the image width, the image height or both measures to resize the image. If you leave both fields blank, the original image size will be displayed.');
$GLOBALS['TL_LANG']['tl_content']['fullsize']     = array('Fullsize view', 'If you choose this option, the image can be viewed fullsize by clicking it.');
$GLOBALS['TL_LANG']['tl_content']['linkTitle']    = array('Link title', 'The title of a link will be shown to the visitors of your website instead of the URL or the source file. If you generate an image link, the image will be shown instead of the link title.');
$GLOBALS['TL_LANG']['tl_content']['useImage']     = array('Image link', 'Use an image instead of the link title.');
$GLOBALS['TL_LANG']['tl_content']['embed']        = array('Embed the link', 'If you enter a phrase including the wildcard <em>%s</em>, the hyperlink will be embedded into this phrase. E.g. <em>visit our %s!</em> will be transformed into <em>visit our <u>company\'s website</u>!</em> (provided that <em>company\'s website</em> is the title of the link).');
$GLOBALS['TL_LANG']['tl_content']['listitems']    = array('List items', 'Please enter the list items. Use the buttons to add, move or delete a list item. If you are working without JavaScript assistance, you should save your changes before you modify the order!');
$GLOBALS['TL_LANG']['tl_content']['listtype']     = array('List type', 'Please choose a list type.');
$GLOBALS['TL_LANG']['tl_content']['tableitems']   = array('Table items', 'Please enter the table items. Use the buttons to add, move or delete a column or row. If you are working without JavaScript assistance, you should save your changes before you modify the order!');
$GLOBALS['TL_LANG']['tl_content']['summary']      = array('Table summary', 'In order to make tables accessible, you should always provide a short summary of its content.');
$GLOBALS['TL_LANG']['tl_content']['thead']        = array('Table header', 'If you choose this option, the first row of the table will be used as table header.');
$GLOBALS['TL_LANG']['tl_content']['tfoot']        = array('Table footer', 'If you choose this option, the last row of the table will be used as table footer.');
$GLOBALS['TL_LANG']['tl_content']['sortable']     = array('Sortable', 'This option allows to sort the table by clicking its column headers (requires option <em>table header</em>).');
$GLOBALS['TL_LANG']['tl_content']['sortIndex']    = array('Sort index', 'Please enter the number of the default column you want to sort by (first column = 0!).');
$GLOBALS['TL_LANG']['tl_content']['sortOrder']    = array('Sort order', 'Please choose the default sort order.');
$GLOBALS['TL_LANG']['tl_content']['multiSRC']     = array('Source files', 'Please select one or more files or folders (files in a folder will be included automatically).');
$GLOBALS['TL_LANG']['tl_content']['useHomeDir']   = array('Use home directory', 'If a front end user has logged in, use his home directory as file source.');
$GLOBALS['TL_LANG']['tl_content']['sortBy']       = array('Order by', 'Please select a sort order.');
$GLOBALS['TL_LANG']['tl_content']['perRow']       = array('Thumbnails per row', 'Please enter the number of thumbnails per row.');
$GLOBALS['TL_LANG']['tl_content']['perPage']      = array('Items per page', 'Please enter the number of items per page (0 = disable pagination).');
$GLOBALS['TL_LANG']['tl_content']['imageUrl']     = array('Use image as link', 'Please enter a complete target URL including the network protocol (e.g. <em>http://www.domain.com</em>) to use the image as link. Note that in this case it will not be possible to view the image fullsize anymore.');
$GLOBALS['TL_LANG']['tl_content']['mooType']      = array('Operation mode', 'Please select the operation mode of the element.');
$GLOBALS['TL_LANG']['tl_content']['mooHeadline']  = array('Headline', 'Please enter the headline of the accordion content pane (HTML allowed).');
$GLOBALS['TL_LANG']['tl_content']['mooStyle']     = array('CSS format', 'Here you can enter an optional CSS string to format the headline.');
$GLOBALS['TL_LANG']['tl_content']['mooClasses']   = array('Class names', 'If you want to run more than one accordion instance per page, you will have to change the class names of your toggler and accordion elements. By default, they use class <em>toggler</em> and <em>accordion</em> (leave blank to use default classes).');
$GLOBALS['TL_LANG']['tl_content']['cteAlias']     = array('Element ID', 'Please choose the ID of the content element you want to insert.');
$GLOBALS['TL_LANG']['tl_content']['articleAlias'] = array('Article ID', 'Please choose the ID of the article you want to insert.');
$GLOBALS['TL_LANG']['tl_content']['article']      = array('Article ID', 'Please choose the ID of the article you want to display.');
$GLOBALS['TL_LANG']['tl_content']['form']         = array('Form', 'Please select a form.');
$GLOBALS['TL_LANG']['tl_content']['module']       = array('Module', 'Please select the module you want to include in the article.');
$GLOBALS['TL_LANG']['tl_content']['invisible']    = array('Invisible', 'The current element is not visible on your website.');
$GLOBALS['TL_LANG']['tl_content']['protected']    = array('Protect element', 'Show content element to certain member groups only.');
$GLOBALS['TL_LANG']['tl_content']['guests']       = array('Show to guests only', 'Hide the content element if a member is logged in.');
$GLOBALS['TL_LANG']['tl_content']['groups']       = array('Allowed member groups', 'Here you can choose which groups will be allowed to see the content element.');
$GLOBALS['TL_LANG']['tl_content']['align']        = array('Element alignment', 'Here you can change the alignment of the content element within the article.');
$GLOBALS['TL_LANG']['tl_content']['space']        = array('Space in front and after', 'Please enter the spacing in front of and after the content element in pixel.');
$GLOBALS['TL_LANG']['tl_content']['cssID']        = array('Style sheet ID and class', 'Here you can enter a style sheet ID (id attribute) and one or more style sheet classes (class attributes) to be able to format the content element using CSS.');
$GLOBALS['TL_LANG']['tl_content']['source']       = array('File source', 'Please choose the CSV file you want to import from the files directory.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_content']['single']    = array('Single element', 'In this operation mode, the element will be converted into an accordion pane. You can set up content using the rich text editor.');
$GLOBALS['TL_LANG']['tl_content']['start']     = array('Wrapper start', 'This operation mode allows to display multiple content elements in one accordion pane by inserting them between element <em>wrapper start</em> and <em>wrapper stop</em>.');
$GLOBALS['TL_LANG']['tl_content']['stop']      = array('Wrapper stop', 'Indicates the end of a wrapper element.');
$GLOBALS['TL_LANG']['tl_content']['ordered']   = 'ordered list';
$GLOBALS['TL_LANG']['tl_content']['unordered'] = 'unordered list';
$GLOBALS['TL_LANG']['tl_content']['name_asc']  = 'File name (ascending)';
$GLOBALS['TL_LANG']['tl_content']['name_desc'] = 'File name (descending)';
$GLOBALS['TL_LANG']['tl_content']['date_asc']  = 'Date (ascending)';
$GLOBALS['TL_LANG']['tl_content']['date_desc'] = 'Date (descending)';
$GLOBALS['TL_LANG']['tl_content']['meta']      = 'Meta file (meta.txt)';


/**
 * Buttons
 */ 
$GLOBALS['TL_LANG']['tl_content']['new']         = array('New element', 'Create a new element');
$GLOBALS['TL_LANG']['tl_content']['show']        = array('Element details', 'Show details of content element ID %s');
$GLOBALS['TL_LANG']['tl_content']['cut']         = array('Move element', 'Move content element ID %s');
$GLOBALS['TL_LANG']['tl_content']['copy']        = array('Copy element', 'Copy content element ID %s');
$GLOBALS['TL_LANG']['tl_content']['delete']      = array('Delete element', 'Delete content element ID %s');
$GLOBALS['TL_LANG']['tl_content']['edit']        = array('Edit element', 'Edit content element ID %s');
$GLOBALS['TL_LANG']['tl_content']['editheader']  = array('Edit article header', 'Edit the header of this article');
$GLOBALS['TL_LANG']['tl_content']['pasteafter']  = array('Paste at the beginning', 'Paste after content element ID %s');
$GLOBALS['TL_LANG']['tl_content']['pastenew']    = array('Create a new content element at the beginning', 'Create a new content element after content element ID %s');
$GLOBALS['TL_LANG']['tl_content']['up']          = array('Move item up', 'Move this item one position up');
$GLOBALS['TL_LANG']['tl_content']['down']        = array('Move item down', 'Move this item one position down');
$GLOBALS['TL_LANG']['tl_content']['toggle']      = array('Toggle visibility', 'Toggle the visibility of element ID %s');
$GLOBALS['TL_LANG']['tl_content']['editalias']   = array('Edit source element', 'Edit the source element ID %s');
$GLOBALS['TL_LANG']['tl_content']['editarticle'] = array('Edit article', 'Edit article ID %s');


/**
 * Table wizard
 */
$GLOBALS['TL_LANG']['tl_content']['importList']  = array('CSV import', 'Import list from a CSV file');
$GLOBALS['TL_LANG']['tl_content']['importTable'] = array('CSV import', 'Import table from a CSV file');
$GLOBALS['TL_LANG']['tl_content']['rcopy']       = array('Duplicate row', 'Duplicate this row');
$GLOBALS['TL_LANG']['tl_content']['rup']         = array('Move row up', 'Move this row one position up');
$GLOBALS['TL_LANG']['tl_content']['rdown']       = array('Move row down', 'Move this row one position down');
$GLOBALS['TL_LANG']['tl_content']['rdelete']     = array('Delete row', 'Delete this row');
$GLOBALS['TL_LANG']['tl_content']['ccopy']       = array('Duplicate column', 'Duplicate this column');
$GLOBALS['TL_LANG']['tl_content']['cmovel']      = array('Move column left', 'Move this column one position left');
$GLOBALS['TL_LANG']['tl_content']['cmover']      = array('Move column right', 'Move this column one position right');
$GLOBALS['TL_LANG']['tl_content']['cdelete']     = array('Delete column', 'Delete this column');

?>