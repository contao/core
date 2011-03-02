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
 * Form fields
 */
$GLOBALS['TL_LANG']['FFL']['headline']    = array('Headline', 'A custom field to insert a section headline.');
$GLOBALS['TL_LANG']['FFL']['explanation'] = array('Explanation', 'A custom field to insert an explanation text.');
$GLOBALS['TL_LANG']['FFL']['html']        = array('HTML code', 'A custom field to insert HTML code.');
$GLOBALS['TL_LANG']['FFL']['fieldset']    = array('Fieldset', 'A container for form fields with an optional legend.');
$GLOBALS['TL_LANG']['FFL']['text']        = array('Text field', 'A single-line input field for a short or medium text.');
$GLOBALS['TL_LANG']['FFL']['password']    = array('Password field', 'A single-line input field for a password. Contao automatically adds a confirmation field.');
$GLOBALS['TL_LANG']['FFL']['textarea']    = array('Textarea', 'A multi-line input field for a medium or long text.');
$GLOBALS['TL_LANG']['FFL']['select']      = array('Select menu', 'A single- or multi-line drop-down menu.');
$GLOBALS['TL_LANG']['FFL']['radio']       = array('Radio button menu', 'A list of multiple options from which one can be selected.');
$GLOBALS['TL_LANG']['FFL']['checkbox']    = array('Checkbox menu', 'A list of multiple options from which any can be selected.');
$GLOBALS['TL_LANG']['FFL']['upload']      = array('File upload', 'A single-line input field to upload a local file to the server.');
$GLOBALS['TL_LANG']['FFL']['hidden']      = array('Hidden field', 'A single-line input field that is not visible in the form.');
$GLOBALS['TL_LANG']['FFL']['captcha']     = array('Security question', 'A simple math question to verify that the form is being submitted by a human (CAPTCHA).');
$GLOBALS['TL_LANG']['FFL']['submit']      = array('Submit field', 'A button to submit the form.');


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_form_field']['type']           = array('Field type', 'Please choose the type of field.');
$GLOBALS['TL_LANG']['tl_form_field']['name']           = array('Field name', 'The field name is a unique name to identify the field.');
$GLOBALS['TL_LANG']['tl_form_field']['label']          = array('Field label', 'The field label will be visible on the website, typically in front of or above the field.');
$GLOBALS['TL_LANG']['tl_form_field']['text']           = array('Text', 'You can use HTML tags to format the text.');
$GLOBALS['TL_LANG']['tl_form_field']['html']           = array('HTML', 'You can modify the list of allowed HTML tags in the back end settings.');
$GLOBALS['TL_LANG']['tl_form_field']['options']        = array('Options', 'If JavaScript is disabled, make sure to save your changes before modifying the order.');
$GLOBALS['TL_LANG']['tl_form_field']['mandatory']      = array('Mandatory field', 'The form will not submit if the field is empty.');
$GLOBALS['TL_LANG']['tl_form_field']['rgxp']           = array('Input validation', 'Validate the input against a regular expression.');
$GLOBALS['TL_LANG']['tl_form_field']['digit']          = array('Numeric characters', 'Allows numeric characters, minus (-), full stop (.) and space ( ).');
$GLOBALS['TL_LANG']['tl_form_field']['alpha']          = array('Alphabetic characters', 'Allows alphabetic characters, minus (-), full stop (.) and space ( ).');
$GLOBALS['TL_LANG']['tl_form_field']['alnum']          = array('Alphanumeric characters', 'Allows alphabetic and numeric characters, minus (-), full stop (.), underscore (_) and space ( ).');
$GLOBALS['TL_LANG']['tl_form_field']['extnd']          = array('Extended alphanumeric characters', 'Allows everything except special characters which are usually encoded for security reasons (#/()<=>).');
$GLOBALS['TL_LANG']['tl_form_field']['date']           = array('Date', 'Checks whether the input matches the global date format.');
$GLOBALS['TL_LANG']['tl_form_field']['time']           = array('Time', 'Checks whether the input matches the global time format.');
$GLOBALS['TL_LANG']['tl_form_field']['datim']          = array('Date and time', 'Checks whether the input matches the global date and time format.');
$GLOBALS['TL_LANG']['tl_form_field']['phone']          = array('Phone number', 'Allows numeric characters, plus (+), minus (-), slash (/), parentheses () and space ( ).');
$GLOBALS['TL_LANG']['tl_form_field']['email']          = array('E-mail address', 'Checks whether the input is a valid e-mail address.');
$GLOBALS['TL_LANG']['tl_form_field']['url']            = array('URL format', 'Checks whether the input is a valid URL.');
$GLOBALS['TL_LANG']['tl_form_field']['maxlength']      = array('Maximum length', 'Limit the field length to a certain number of characters (text) or bytes (file uploads).');
$GLOBALS['TL_LANG']['tl_form_field']['size']           = array('Rows and columns', 'The number of rows and columns of the textarea.');
$GLOBALS['TL_LANG']['tl_form_field']['multiple']       = array('Multiple selection', 'Allow visitors to select more than one option.');
$GLOBALS['TL_LANG']['tl_form_field']['mSize']          = array('List size', 'Here you can enter the size of the select box.');
$GLOBALS['TL_LANG']['tl_form_field']['extensions']     = array('Allowed file types', 'A comma separated list of valid file extensions.');
$GLOBALS['TL_LANG']['tl_form_field']['storeFile']      = array('Store uploaded files', 'Move the uploaded files to a folder on the server.');
$GLOBALS['TL_LANG']['tl_form_field']['uploadFolder']   = array('Target folder', 'Please select the target folder from the files directory.');
$GLOBALS['TL_LANG']['tl_form_field']['useHomeDir']     = array('Use home directory', 'Store the file in the home directory if there is an authenticated user.');
$GLOBALS['TL_LANG']['tl_form_field']['doNotOverwrite'] = array('Preserve existing files', 'Add a numeric suffix to the new file if the file name already exists.');
$GLOBALS['TL_LANG']['tl_form_field']['fsType']         = array('Operation mode', 'Please select the operation mode of the fieldset element.');
$GLOBALS['TL_LANG']['tl_form_field']['fsStart']        = array('Wrapper start', 'Marks the beginning of a fieldset and can contain a legend.');
$GLOBALS['TL_LANG']['tl_form_field']['fsStop']         = array('Wrapper stop', 'Marks the end of a fieldset.');
$GLOBALS['TL_LANG']['tl_form_field']['value']          = array('Default value', 'Here you can enter a default value for the field.');
$GLOBALS['TL_LANG']['tl_form_field']['class']          = array('CSS class', 'Here you can enter one or more classes.');
$GLOBALS['TL_LANG']['tl_form_field']['accesskey']      = array('Access key', 'A form field can be focused by pressing the [ALT] or [CTRL] key and the access key simultaneously.');
$GLOBALS['TL_LANG']['tl_form_field']['addSubmit']      = array('Add a submit button', 'Add a submit button next to the field to create a single line form.');
$GLOBALS['TL_LANG']['tl_form_field']['slabel']         = array('Submit button label', 'Please enter the label of the submit button.');
$GLOBALS['TL_LANG']['tl_form_field']['imageSubmit']    = array('Create an image button', 'Use an image submit button instead of the default one.');
$GLOBALS['TL_LANG']['tl_form_field']['singleSRC']      = array('Source file', 'Please select an image from the files directory.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_form_field']['type_legend']    = 'Field type and name';
$GLOBALS['TL_LANG']['tl_form_field']['text_legend']    = 'Text/HTML';
$GLOBALS['TL_LANG']['tl_form_field']['fconfig_legend'] = 'Field configuration';
$GLOBALS['TL_LANG']['tl_form_field']['options_legend'] = 'Options';
$GLOBALS['TL_LANG']['tl_form_field']['store_legend']   = 'Store file';
$GLOBALS['TL_LANG']['tl_form_field']['expert_legend']  = 'Expert settings';
$GLOBALS['TL_LANG']['tl_form_field']['submit_legend']  = 'Submit button';
$GLOBALS['TL_LANG']['tl_form_field']['image_legend']   = 'Image button';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_form_field']['new']        = array('New field', 'Create a new field');
$GLOBALS['TL_LANG']['tl_form_field']['show']       = array('Field details', 'Show the details of field ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['edit']       = array('Edit field', 'Edit field ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['cut']        = array('Move field', 'Move field ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['copy']       = array('Duplicate field', 'Duplicate field ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['delete']     = array('Delete field', 'Delete field ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['editheader'] = array('Edit form', 'Edit the form settings');
$GLOBALS['TL_LANG']['tl_form_field']['pasteafter'] = array('Paste at the top', 'Paste after field ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['pastenew']   = array('Add new at the top', 'Add new after field ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['toggle']     = array('Toggle visibility', 'Toggle the visibility of field ID %s');

?>