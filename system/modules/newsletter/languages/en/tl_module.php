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
$GLOBALS['TL_LANG']['tl_module']['nl_channels']    = array('Channels', 'Please select one or more channels.');
$GLOBALS['TL_LANG']['tl_module']['nl_template']    = array('Module layout', 'Please choose a module layout. You can add custom news layouts to folder <em>templates</em>. Newsletter template files start with <em>nl_</em> and require file extension <em>.tpl</em>.');
$GLOBALS['TL_LANG']['tl_module']['nl_subscribe']   = array('Subscription e-mail', 'Please enter the text of the subscription confirmation e-mail. You can use the wildcards <em>##channels##</em> (channel names), <em>##domain##</em> (current domain name) and <em>##link##</em> (activation link).');
$GLOBALS['TL_LANG']['tl_module']['nl_unsubscribe'] = array('Unsubscription e-mail', 'Please enter the text of the unsubscription confirmation e-mail. You can use the wildcards <em>##channels##</em> (channel names) and <em>##domain##</em> (current domain name).');
$GLOBALS['TL_LANG']['tl_module']['nl_includeCss']  = array('Include style sheet', 'If there is a style sheet called <em>newsletter.css</em>, include it in the front end.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['text_subscribe']   = array('Your subscription on %s', "You have subscribed to the following channels on ##domain##:\n\n##channels##\n\nPlease click ##link## to complete your subscription. If you did not subscribe yourself, please ignore this e-mail.\n");
$GLOBALS['TL_LANG']['tl_module']['text_unsubscribe'] = array('Your subscription on %s', "You have unsubscribed from the following channels on ##domain##:\n\n##channels##\n");

?>