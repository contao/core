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
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['newsletters']     = array('Subscribable newsletters', 'Show these channels in the front end form.');
$GLOBALS['TL_LANG']['tl_module']['nl_channels']     = array('Channels', 'Please select one or more channels.');
$GLOBALS['TL_LANG']['tl_module']['nl_hideChannels'] = array('Hide channel menu', 'Do not show the channel selection menu.');
$GLOBALS['TL_LANG']['tl_module']['nl_subscribe']    = array('Subscription message', 'You can use the wildcards <em>##channels##</em> (channel names), <em>##domain##</em> (domain name) and <em>##link##</em> (activation link).');
$GLOBALS['TL_LANG']['tl_module']['nl_unsubscribe']  = array('Unsubscription message', 'You can use the wildcards <em>##channels##</em> (channel names) and <em>##domain##</em> (domain name).');
$GLOBALS['TL_LANG']['tl_module']['nl_template']     = array('Newsletter template', 'Here you can select the newsletter template.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['text_subscribe']   = array('Your subscription on %s', "You have subscribed to the following channels on ##domain##:\n\n##channels##\n\nPlease click ##link## to activate your subscription. If you did not subscribe yourself, please ignore this e-mail.\n");
$GLOBALS['TL_LANG']['tl_module']['text_unsubscribe'] = array('Your subscription on %s', "You have unsubscribed from the following channels on ##domain##:\n\n##channels##\n");

?>