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
$GLOBALS['TL_LANG']['tl_newsletter_channel']['title']    = array('Title', 'Please enter the title of the channel.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['jumpTo']   = array('Redirect page', 'Please choose the newsletter reader page to which visitors will be redirected when clicking a newsletter.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['useSMTP']  = array('Custom SMTP server', 'Use a custom SMTP server for sending newsletters.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtpHost'] = array('SMTP hostname', 'Please enter the host name of the SMTP server.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtpUser'] = array('SMTP username', 'Here you can enter the SMTP username.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtpPass'] = array('SMTP password', 'Here you can enter the SMTP password.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtpEnc']  = array('SMTP encryption', 'Here you can choose an encryption method (SSL or TLS).');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtpPort'] = array('SMTP port number', 'Please enter the port number of the SMTP server.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['tstamp']   = array('Revision date', 'Date and time of the latest revision');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_newsletter_channel']['title_legend'] = 'Title and redirect';
$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtp_legend']  = 'SMTP configuration';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_newsletter_channel']['new']        = array('New channel', 'Create a new channel');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['show']       = array('Channel details', 'Show the details of channel ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['edit']       = array('Edit channel', 'Edit channel ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['editheader'] = array('Edit channel settings', 'Edit the settings of channel ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['copy']       = array('Duplicate channel', 'Duplicate channel ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['delete']     = array('Delete channel', 'Delete channel ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['recipients'] = array('Edit recipients', 'Edit the recipients of channel ID %s');

?>