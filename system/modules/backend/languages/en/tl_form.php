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
$GLOBALS['TL_LANG']['tl_form']['title']        = array('Title', 'Please enter the form title.');
$GLOBALS['TL_LANG']['tl_form']['alias']        = array('Form alias', 'The form alias is a unique reference to the form which can be called instead of its numeric ID.');
$GLOBALS['TL_LANG']['tl_form']['jumpTo']       = array('Redirect page', 'Please choose the page to which visitors will be redirected after submitting the form.');
$GLOBALS['TL_LANG']['tl_form']['sendViaEmail'] = array('Send form data via e-mail', 'Send the submitted data to an e-mail address.');
$GLOBALS['TL_LANG']['tl_form']['recipient']    = array('Recipient address', 'Separate multiple e-mail addresses with comma.');
$GLOBALS['TL_LANG']['tl_form']['subject']      = array('Subject', 'Please enter the e-mail subject.');
$GLOBALS['TL_LANG']['tl_form']['format']       = array('Data format', 'Defines how the form data will be forwarded.');
$GLOBALS['TL_LANG']['tl_form']['raw']          = array('Raw data', 'The form data will be sent as plain text message with each field in a new line.');
$GLOBALS['TL_LANG']['tl_form']['xml']          = array('XML file', 'The form data will be attached to the e-mail as an XML file.');
$GLOBALS['TL_LANG']['tl_form']['csv']          = array('CSV file', 'The form data will be attached to the e-mail as a CSV file.');
$GLOBALS['TL_LANG']['tl_form']['email']        = array('E-mail', 'Ignores all fields except <em>email</em>, <em>subject</em>, <em>message</em> and <em>cc</em> (carbon copy) and sends the form data like it had been sent from a mail client. File uploads are allowed.');
$GLOBALS['TL_LANG']['tl_form']['skipEmtpy']    = array('Skip empty fields', 'Hide empty fields in the e-mail.');
$GLOBALS['TL_LANG']['tl_form']['storeValues']  = array('Store data', 'Store the submitted data in the database.');
$GLOBALS['TL_LANG']['tl_form']['targetTable']  = array('Target table', 'The target table must contain a column for every form field.');
$GLOBALS['TL_LANG']['tl_form']['method']       = array('Submission method', 'The default form submission method is POST.');
$GLOBALS['TL_LANG']['tl_form']['attributes']   = array('CSS ID/class', 'Here you can set an ID and one or more classes.');
$GLOBALS['TL_LANG']['tl_form']['formID']       = array('Form ID', 'The form ID is required to trigger a Contao module.');
$GLOBALS['TL_LANG']['tl_form']['tableless']    = array('Tableless layout', 'Render the form without HTML tables.');
$GLOBALS['TL_LANG']['tl_form']['allowTags']    = array('Allow HTML tags', 'Allow HTML tags in form fields.');
$GLOBALS['TL_LANG']['tl_form']['tstamp']       = array('Revision date', 'Date and time of the latest revision');


/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_form']['title_legend']  = 'Title and redirect page';
$GLOBALS['TL_LANG']['tl_form']['email_legend']  = 'Send form data';
$GLOBALS['TL_LANG']['tl_form']['store_legend']  = 'Store form data';
$GLOBALS['TL_LANG']['tl_form']['expert_legend'] = 'Expert settings';
$GLOBALS['TL_LANG']['tl_form']['config_legend'] = 'Form configuration';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_form']['new']        = array('New form', 'Create a new form');
$GLOBALS['TL_LANG']['tl_form']['show']       = array('Form details', 'Show the details of form ID %s');
$GLOBALS['TL_LANG']['tl_form']['edit']       = array('Edit form', 'Edit form ID %s');
$GLOBALS['TL_LANG']['tl_form']['editheader'] = array('Edit form settings', 'Edit the settings of form ID %s');
$GLOBALS['TL_LANG']['tl_form']['copy']       = array('Duplicate form', 'Duplicate form ID %s');
$GLOBALS['TL_LANG']['tl_form']['delete']     = array('Delete form', 'Delete form ID %s');

?>