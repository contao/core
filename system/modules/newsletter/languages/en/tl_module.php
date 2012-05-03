<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Newsletter
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
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
