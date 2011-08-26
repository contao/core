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
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_newsletter']['subject']        = array('Subject', 'Please enter the newsletter subject.');
$GLOBALS['TL_LANG']['tl_newsletter']['alias']          = array('Newsletter alias', 'The newsletter alias is a unique reference to the newsletter which can be called instead of its numeric ID.');
$GLOBALS['TL_LANG']['tl_newsletter']['content']        = array('HTML content', 'Here you can enter the HTML content of the newsletter. Use the wildcard <em>##email##</em> to insert the recipient\'s e-mail address.');
$GLOBALS['TL_LANG']['tl_newsletter']['text']           = array('Text content', 'Here you can enter the text content of the newsletter. Use the wildcard <em>##email##</em> to insert the recipient\'s e-mail address.');
$GLOBALS['TL_LANG']['tl_newsletter']['addFile']        = array('Add attachments', 'Attach one or more files to the newsletter.');
$GLOBALS['TL_LANG']['tl_newsletter']['files']          = array('Attachments', 'Please choose the files to be attached from the files directory.');
$GLOBALS['TL_LANG']['tl_newsletter']['template']       = array('E-mail template', 'Here you can choose the e-mail template.');
$GLOBALS['TL_LANG']['tl_newsletter']['sendText']       = array('Send as plain text', 'Send the newsletter as plain text e-mail without the HTML content.');
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'] = array('External images', 'Do not embed images in HTML newsletters.');
$GLOBALS['TL_LANG']['tl_newsletter']['senderName']     = array('Sender name', 'Here you can enter the sender\'s name.');
$GLOBALS['TL_LANG']['tl_newsletter']['sender']         = array('Sender address', 'Here you can enter a custom sender address.');
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle']  = array('Mails per cycle', 'The sending process is split into several cycles to prevent the script from timing out.');
$GLOBALS['TL_LANG']['tl_newsletter']['timeout']        = array('Timeout in seconds', 'Here you can modify the waiting time between each cycle to control the number of e-mails per minute.');
$GLOBALS['TL_LANG']['tl_newsletter']['start']          = array('Offset', 'In case the sending process is interrupted, you can enter a numeric offset here to continue with a particular recipient. You can check how many mails have been sent in the <em>system/logs/newsletter_*.log</em> file. E.g., if 120 mails have been sent, enter "120" to continue with the 121st recipient (counting starts at 0).');
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo']  = array('Send preview to', 'Send the preview of the newsletter to this e-mail address.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend']      = 'Title and subject';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend']       = 'HTML content';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend']       = 'Text content';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Attachments';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend']   = 'Template settings';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend']     = 'Expert settings';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_newsletter']['sent']        = 'Sent';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn']      = 'Sent on %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent']     = 'Not sent yet';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Mailing date';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm']     = 'The newsletter has been sent to %s recipients.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected']    = '%s invalid e-mail address(es) has/have been disabled (see system log).';
$GLOBALS['TL_LANG']['tl_newsletter']['error']       = 'There are no active subscribers to the channel.';
$GLOBALS['TL_LANG']['tl_newsletter']['from']        = 'From';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Attachments';
$GLOBALS['TL_LANG']['tl_newsletter']['preview']     = 'Send preview';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Do you really want to send the newsletter?';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_newsletter']['new']        = array('New newsletter', 'Create a new newsletter');
$GLOBALS['TL_LANG']['tl_newsletter']['show']       = array('Newsletter details', 'Show the details of newsletter ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['edit']       = array('Edit newsletter', 'Edit newsletter ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['copy']       = array('Duplicate newsletter', 'Duplicate newsletter ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['cut']        = array('Move newsletter', 'Move newsletter ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['delete']     = array('Delete newsletter', 'Delete newsletter ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'] = array('Edit channel', 'Edit the channel settings');
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'] = array('Paste into this channel', 'Paste after newsletter ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['send']       = array('Send newsletter', 'Send newsletter ID %s');

?>