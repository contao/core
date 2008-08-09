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
 * Error messages
 */
$GLOBALS['TL_LANG']['ERR']['subscribed']   = 'You are subscribed to the selected channels already.';
$GLOBALS['TL_LANG']['ERR']['unsubscribed'] = 'You are not subscribed to the selected channels.';
$GLOBALS['TL_LANG']['ERR']['invalidToken'] = 'This activation link is invalid or outdated.';
$GLOBALS['TL_LANG']['ERR']['noChannels']   = 'Please select at least one channel.';


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['subscribe']   = 'Subscribe';
$GLOBALS['TL_LANG']['MSC']['unsubscribe'] = 'Unsubscribe';
$GLOBALS['TL_LANG']['MSC']['nl_subject']  = 'Your subscription on %s';
$GLOBALS['TL_LANG']['MSC']['nl_confirm']  = 'Thank you for your subscription. You will receive a confirmation e-mail.';
$GLOBALS['TL_LANG']['MSC']['nl_activate'] = 'Your subscription has been activated.';
$GLOBALS['TL_LANG']['MSC']['nl_removed']  = 'You subscription has been removed.';

?>