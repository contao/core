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
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_newsletter']['subject']       = array('Subject', 'Please enter the subject of the newsletter.');
$GLOBALS['TL_LANG']['tl_newsletter']['alias']         = array('Newsletter alias', 'A newsletter alias is a unique reference to the newsletter which can be called instead of its ID.');
$GLOBALS['TL_LANG']['tl_newsletter']['sender']        = array('Sender address', 'If you do not enter a sender e-mail address, the administrator e-mail address will be used.');
$GLOBALS['TL_LANG']['tl_newsletter']['senderName']    = array('Sender name', 'Here you can enter the sender\'s name.');
$GLOBALS['TL_LANG']['tl_newsletter']['content']       = array('HTML content', 'Please enter the HTML content of the newsletter. Use the wildcard <em>##email##</em> to insert the recipient\'s e-mail address. Generate unsubscribe links as <em>http://www.domain.com/unsubscribe-page.html?email=##email##</em>.');
$GLOBALS['TL_LANG']['tl_newsletter']['text']          = array('Text content', 'Please enter the text content of the newsletter. Use the wildcard <em>##email##</em> to insert the recipient\'s e-mail address. Generate unsubscribe links as <em>http://www.domain.com/unsubscribe-page.html?email=##email##</em>.');
$GLOBALS['TL_LANG']['tl_newsletter']['template']      = array('E-mail template', 'Please choose an e-mail template (template group <em>mail_</em>).');
$GLOBALS['TL_LANG']['tl_newsletter']['sendText']      = array('Send as text', 'If you choose this option, the newsletter will be sent as plain text without the HTML content.');
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'] = array('Mails per cycle', 'To prevent the script from timing out, the sending process is split into several cycles. Here you can define the number of mails per cycle depending on the maximum execution time defined in your php.ini.');
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'] = array('Send preview to', 'Send a preview of the newsletter to this e-mail address.');
$GLOBALS['TL_LANG']['tl_newsletter']['timeout']       = array('Timeout in seconds', 'Some mail servers limit the number of e-mails that can be sent per minute. Here you can modify the timeout between each cycle in seconds to get more control over the time frame.');
$GLOBALS['TL_LANG']['tl_newsletter']['addFile']       = array('Add attachment', 'Attach one or more files to the newsletter.');
$GLOBALS['TL_LANG']['tl_newsletter']['files']         = array('Attachments', 'Please select the files you want to attach to the newsletter.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_newsletter']['sent']        = 'Sent';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn']      = 'Sent on %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent']     = 'Not sent yet';
$GLOBALS['TL_LANG']['tl_newsletter']['headline']    = 'Send newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm']     = 'The newsletter has been sent to %s recipients.';
$GLOBALS['TL_LANG']['tl_newsletter']['error']       = 'There are no active subscribers to this channel.';
$GLOBALS['TL_LANG']['tl_newsletter']['from']        = 'From';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Attachments';
$GLOBALS['TL_LANG']['tl_newsletter']['preview']     = 'Send preview';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_newsletter']['new']        = array('New newsletter', 'Create a new newsletter');
$GLOBALS['TL_LANG']['tl_newsletter']['edit']       = array('Edit newsletter', 'Edit newsletter ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['copy']       = array('Copy newsletter', 'Copy newsletter ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['delete']     = array('Delete newsletter', 'Delete newsletter ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['show']       = array('Newsletter details', 'Show details of newsletter ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'] = array('Edit channel', 'Edit the channel');
$GLOBALS['TL_LANG']['tl_newsletter']['send']       = array('Send newsletter', 'Send newsletter ID %s');

?>