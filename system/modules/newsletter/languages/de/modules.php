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
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['newsletter'] = array('Newsletter', 'Mit diesem Modul können Sie Newsletter verwalten.');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['newsletter']  = 'Newsletter';
$GLOBALS['TL_LANG']['FMD']['subscribe']   = array('Abonnieren', 'Mit diesem Modul können Frontend Benutzer bestimmte Newsletter abonnieren.');
$GLOBALS['TL_LANG']['FMD']['unsubscribe'] = array('Kündigen', 'Mit diesem Modul können Frontend Benutzer ihre Newsletter-Abonnements kündigen.');
$GLOBALS['TL_LANG']['FMD']['nl_reader']   = array('Newsletter-Leser', 'Mit diesem Modul können Sie einen einzelnen Newsletter im Frontend darstellen.');
$GLOBALS['TL_LANG']['FMD']['nl_list']     = array('Newsletter-Liste', 'Mit diesem Modul können Sie die Newsletter eines oder mehrerer Verteiler auflisten.');

?>