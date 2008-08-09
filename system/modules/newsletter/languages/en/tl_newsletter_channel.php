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
$GLOBALS['TL_LANG']['tl_newsletter_channel']['title']  = array('Title', 'Please enter the title of the channel.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['tstamp'] = array('Revision date', 'Date and time of latest revision');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['jumpTo'] = array('Jump to page', 'Please select the page to which visitors will be redirected when clicking a newsletter.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_newsletter_channel']['new']        = array('New channel', 'Create a new channel');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['edit']       = array('Edit channel', 'Edit channel ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['copy']       = array('Copy channel', 'Copy channel ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['delete']     = array('Delete channel', 'Delete channel ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['show']       = array('Channel details', 'Show details of channel ID %s');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['recipients'] = array('Edit recipients', 'Edit the recipients of channel ID %s');

?>