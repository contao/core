<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * Core translations are managed using Transifex. To create a new translation
 * or to help to maintain an existing one, please register at transifex.com.
 * 
 * @link http://help.transifex.com/intro/translating.html
 * @link https://www.transifex.com/projects/p/contao/language/en/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Subject';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Please enter the newsletter subject.';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Newsletter alias';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'The newsletter alias is a unique reference to the newsletter which can be called instead of its numeric ID.';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'HTML content';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Here you can enter the HTML content of the newsletter. Use the wildcard <em>##email##</em> to insert the recipient\'s e-mail address.';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Text content';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Here you can enter the text content of the newsletter. Use the wildcard <em>##email##</em> to insert the recipient\'s e-mail address.';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Add attachments';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Attach one or more files to the newsletter.';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Attachments';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Please choose the files to be attached from the files directory.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'E-mail template';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Here you can choose the e-mail template.';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Send as plain text';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Send the newsletter as plain text e-mail without the HTML content.';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'External images';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Do not embed images in HTML newsletters.';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Sender name';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Here you can enter the sender\'s name.';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'Sender address';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Here you can enter a custom sender address.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Mails per cycle';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'The sending process is split into several cycles to prevent the script from timing out.';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Timeout in seconds';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Here you can modify the waiting time between each cycle to control the number of e-mails per minute.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Offset';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'In case the sending process is interrupted, you can enter a numeric offset here to continue with a particular recipient. You can check how many mails have been sent in the <em>system/logs/newsletter_*.log</em> file. E.g., if 120 mails have been sent, enter "120" to continue with the 121st recipient (counting starts at 0).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Send preview to';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Send the preview of the newsletter to this e-mail address.';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Title and subject';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'HTML content';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Text content';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Attachments';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Template settings';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Expert settings';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Sent';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Sent on %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Not sent yet';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Mailing date';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'The newsletter has been sent to %s recipients.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '%s invalid e-mail address(es) has/have been disabled (see system log).';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'There are no active subscribers to the channel.';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'From';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Attachments';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Send preview';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Do you really want to send the newsletter?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'New newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Create a new newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Newsletter details';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Show the details of newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Edit newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Edit newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Duplicate newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Duplicate newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Move newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Move newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Delete newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Delete newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Edit channel';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Edit the channel settings';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'Paste into this channel';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Paste after newsletter ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Send newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Send newsletter ID %s';
