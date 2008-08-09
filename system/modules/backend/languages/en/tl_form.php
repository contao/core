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
$GLOBALS['TL_LANG']['tl_form']['title']        = array('Title', 'Please enter a form title.');
$GLOBALS['TL_LANG']['tl_form']['formID']       = array('Form ID', 'Here you can enter an optional form ID (required to trigger TYPOlight modules).');
$GLOBALS['TL_LANG']['tl_form']['method']       = array('Form submission method', 'Please select a form submission method (default: POST).');
$GLOBALS['TL_LANG']['tl_form']['allowTags']    = array('Allow HTML tags', 'If you choose this option, certain HTML tags will not be removed (see <em>allowedTags</em>).');
$GLOBALS['TL_LANG']['tl_form']['storeValues']  = array('Store values', 'Store submitted form values in the database.');
$GLOBALS['TL_LANG']['tl_form']['targetTable']  = array('Target table', 'Please choose the table you want to store the form values in.');
$GLOBALS['TL_LANG']['tl_form']['tableless']    = array('Tableless layout', 'If you choose this option, the form will be rendered without tables.');
$GLOBALS['TL_LANG']['tl_form']['sendViaEmail'] = array('Send form data via e-mail', 'If you choose this option, the form data will be sent via e-mail.');
$GLOBALS['TL_LANG']['tl_form']['recipient']    = array('Recipient', 'Please enter one or more recipient e-mail addresses. Separate multiple addresses with comma. You can also use the "friendly name format" (e.g. "Name [name@domain.com]").');
$GLOBALS['TL_LANG']['tl_form']['subject']      = array('Subject', 'Please enter the subject. If you do not enter a subject, the probability increases that the e-mail is identified as SPAM.');
$GLOBALS['TL_LANG']['tl_form']['format']       = array('Data format', 'Please choose a data format.');
$GLOBALS['TL_LANG']['tl_form']['raw']          = array('Raw data', 'The form data is sent as plain text message with each field in a new line.');
$GLOBALS['TL_LANG']['tl_form']['email']        = array('E-mail format', 'This format expects the fields <em>email</em>, <em>subject</em>, <em>message</em> and <em>cc</em> (send a copy of the e-mail to the sender). Other field names will be ignored. File uploads are allowed.');
$GLOBALS['TL_LANG']['tl_form']['xml']          = array('XML file', 'The form data is attached to the e-mail as XML file.');
$GLOBALS['TL_LANG']['tl_form']['csv']          = array('CSV file', 'The form data is attached to the e-mail as CSV (comma separated values) file.');
$GLOBALS['TL_LANG']['tl_form']['skipEmtpy']    = array('Skip empty fields', 'Do not include empty fields in the e-mail.');
$GLOBALS['TL_LANG']['tl_form']['jumpTo']       = array('Jump to page', 'A form is usually submitted to another page, which processes the form data or displays a "thank you" message. You can choose this page here.');
$GLOBALS['TL_LANG']['tl_form']['tstamp']       = array('Revision date', 'Date and time of latest revision');
$GLOBALS['TL_LANG']['tl_form']['attributes']   = array('Style sheet ID and class', 'Here you can enter a style sheet ID (id attribute) and one or more style sheet classes (class attributes) to be able to format the form using CSS.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_form']['new']    = array('New form', 'Create a new form');
$GLOBALS['TL_LANG']['tl_form']['show']   = array('Form details', 'Show details of form ID %s');
$GLOBALS['TL_LANG']['tl_form']['edit']   = array('Edit form', 'Edit form ID %s');
$GLOBALS['TL_LANG']['tl_form']['copy']   = array('Duplicate form', 'Duplicate form ID %s');
$GLOBALS['TL_LANG']['tl_form']['delete'] = array('Delete form', 'Delete form ID %s');

?>