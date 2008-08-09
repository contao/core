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
 * Form fields
 */
$GLOBALS['TL_LANG']['FFL']['headline']    = array('Headline', 'A headline field can be used to structure a form by separating several fields with a headline. A headline field can contain HTML code.');
$GLOBALS['TL_LANG']['FFL']['explanation'] = array('Explanation', 'An explanation field can be used to insert an explanation or question in front of a field. An explanation field can contain HTML code.');
$GLOBALS['TL_LANG']['FFL']['html']        = array('HTML', 'Use this field to insert HTML code into the form.');
$GLOBALS['TL_LANG']['FFL']['text']        = array('Text field', 'A text field is a single-line input field for a short or medium text.');
$GLOBALS['TL_LANG']['FFL']['password']    = array('Password field', 'A password field is a single-line input field for a password.');
$GLOBALS['TL_LANG']['FFL']['textarea']    = array('Textarea', 'A textarea is a multi-line input field for a medium or long text.');
$GLOBALS['TL_LANG']['FFL']['select']      = array('Select menu', 'A select menu is a single-line drop down menu containing several options from which one can be selected.');
$GLOBALS['TL_LANG']['FFL']['radio']       = array('Radio button menu', 'A radio button menu is a multi-line menu containing several options from which one can be selected.');
$GLOBALS['TL_LANG']['FFL']['checkbox']    = array('Checkbox menu', 'A checkbox menu is a multi-line menu containing one or more options from which any can be selected.');
$GLOBALS['TL_LANG']['FFL']['upload']      = array('File upload', 'A file upload field contains a button to search a local harddisk and upload a local file.');
$GLOBALS['TL_LANG']['FFL']['submit']      = array('Submit field', 'A submit field contains the submit button and the form header data (e.g. recipient and subject of the e-mail).');
$GLOBALS['TL_LANG']['FFL']['captcha']     = array('Security question', 'This field adds a simple math question that has to be answered in order to submit the form.');
$GLOBALS['TL_LANG']['FFL']['hidden']      = array('Hidden field', 'A hidden field is a single-line input field that is not visible in the form.');


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_form_field']['type']           = array('Field type', 'Please choose the current field type.');
$GLOBALS['TL_LANG']['tl_form_field']['text']           = array('Text', 'Please enter the headline or explanation. HTML tags are allowed.');
$GLOBALS['TL_LANG']['tl_form_field']['html']           = array('HTML', 'Please enter the HTML code.');
$GLOBALS['TL_LANG']['tl_form_field']['name']           = array('Field name', 'Please enter a field name. The field name is not shown to the visitors of your website.');
$GLOBALS['TL_LANG']['tl_form_field']['label']          = array('Field label', 'Please enter the field label. The label will be visible to the visitors of your website. If you are creating a multi-lingual website, the label should be translated into the respective language.');
$GLOBALS['TL_LANG']['tl_form_field']['mandatory']      = array('Mandatory field', 'If you choose this option, the current field has to be filled out.');
$GLOBALS['TL_LANG']['tl_form_field']['rgxp']           = array('Input validation', 'If you choose a regular expression here, any input to the field will be validated against it.');
$GLOBALS['TL_LANG']['tl_form_field']['digit']          = array('Numeric characters', 'allows numeric characters, minus (-), full stop (.) and space');
$GLOBALS['TL_LANG']['tl_form_field']['alpha']          = array('Alphabetic characters', 'allows alphabetic characters, minus (-), full stop (.) and space');
$GLOBALS['TL_LANG']['tl_form_field']['alnum']          = array('Alphanumeric characters', 'allows alphabetic and numeric characters, minus (-), full stop (.) and space');
$GLOBALS['TL_LANG']['tl_form_field']['extnd']          = array('Extended alphanumeric characters', 'disallows all characters which are encoded by default (#&()/<=>), e.g. to avoid problems with password fields.');
$GLOBALS['TL_LANG']['tl_form_field']['url']            = array('URL format', 'checks whether the current value is a valid URL. A valid URL contains the characters A-Za-z and these special characters (-_.:;/?&=+#). All other special characters have to be encoded!');
$GLOBALS['TL_LANG']['tl_form_field']['date']           = array('Date', 'checks whether the current input matches the global date format.');
$GLOBALS['TL_LANG']['tl_form_field']['datim']          = array('Date and time', 'checks whether the current input matches the global date and time format.');
$GLOBALS['TL_LANG']['tl_form_field']['email']          = array('E-mail address', 'checks whether the current value is a valid e-mail address.');
$GLOBALS['TL_LANG']['tl_form_field']['phone']          = array('Phone number', 'allows numeric characters, plus (+), minus (-), slash (/), parentheses () and space.');
$GLOBALS['TL_LANG']['tl_form_field']['maxlength']      = array('Maximum length', 'If you enter a numeric value here, the field length will be limited to this number of characters. If you are setting up a file upload field, you can enter the maximum file size in Bytes here (1 MB = 1024 kB = 1024000 Bytes).');
$GLOBALS['TL_LANG']['tl_form_field']['value']          = array('Value', 'If you enter a value here, it will be used as default value for the current field.');
$GLOBALS['TL_LANG']['tl_form_field']['size']           = array('Rows and columns', 'Please enter the number of rows and the number of columns.');
$GLOBALS['TL_LANG']['tl_form_field']['options']        = array('Options', 'Please enter one or more options. Use the buttons to add, move or delete an option. If you are working without JavaScript assistance, you should save your changes before you modify the order!');
$GLOBALS['TL_LANG']['tl_form_field']['multiple']       = array('Multiple selection', 'Allows to select more than one option.');
$GLOBALS['TL_LANG']['tl_form_field']['mSize']          = array('List size', 'Please enter the size of the select box.');
$GLOBALS['TL_LANG']['tl_form_field']['extensions']     = array('Allowed file types', 'Please enter a comma separated list of valid file extensions. Only those files can upload to the server.');
$GLOBALS['TL_LANG']['tl_form_field']['accesskey']      = array('Access key', 'An access key is a single character which can be assigned to a form field. If a visitor simultaneously presses the [ALT] key and the access key, the corresponding form field is focused.');
$GLOBALS['TL_LANG']['tl_form_field']['class']          = array('CSS class', 'Here you can enter one or more CSS classes.');
$GLOBALS['TL_LANG']['tl_form_field']['storeFile']      = array('Store uploaded file', 'If you choose this option, uploaded files will be moved to a folder on your server. Do not choose this option if uploaded files are attached to an e-mail and you do not want to store them.');
$GLOBALS['TL_LANG']['tl_form_field']['uploadFolder']   = array('Upload folder', 'Please choose a folder to store uploaded files.');
$GLOBALS['TL_LANG']['tl_form_field']['useHomeDir']     = array('Use home directory', 'If there is a logged in user, store the file in his home directory instead of the upload folder.');
$GLOBALS['TL_LANG']['tl_form_field']['doNotOverwrite'] = array('Do not overwrite existing files', 'Add a numeric ID to the filename if the file exists already.');
$GLOBALS['TL_LANG']['tl_form_field']['addSubmit']      = array('Add submit button', 'By default the submit button is added as extra field at the end of the form. However, if you choose this option, a submit button will be added next to the current field. Use this option to create single line forms (e.g. a search form).');
$GLOBALS['TL_LANG']['tl_form_field']['imageSubmit']    = array('Image submit button', 'Use an image submit button instead of a text submit button.');
$GLOBALS['TL_LANG']['tl_form_field']['singleSRC']      = array('Source file', 'Please select an image from the files directory.');
$GLOBALS['TL_LANG']['tl_form_field']['slabel']         = array('Submit button label', 'Please enter the label of the submit button.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_form_field']['opValue']   = 'Value';
$GLOBALS['TL_LANG']['tl_form_field']['opLabel']   = 'Label';
$GLOBALS['TL_LANG']['tl_form_field']['opDefault'] = 'Default';
$GLOBALS['TL_LANG']['tl_form_field']['opGroup']   = 'Group';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_form_field']['new']        = array('New field', 'Create a new field');
$GLOBALS['TL_LANG']['tl_form_field']['show']       = array('Field details', 'Show details of field ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['edit']       = array('Edit field', 'Edit field ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['cut']        = array('Move field', 'Move field ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['copy']       = array('Duplicate field', 'Duplicate field ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['delete']     = array('Delete field', 'Delete field ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['editheader'] = array('Edit form header', 'Edit the header of this form');
$GLOBALS['TL_LANG']['tl_form_field']['pasteafter'] = array('Paste at the beginning', 'Paste after field ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['pastenew']   = array('Create a new field at the beginning', 'Create a new field after field ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['up']         = array('Move item up', 'Move this item one position up');
$GLOBALS['TL_LANG']['tl_form_field']['down']       = array('Move item down', 'Move this item one position down');

?>