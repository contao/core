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
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['news'] = array('Nachrichten', 'Mit diesem Modul können Sie aktuelle Nachrichten auf Ihrer Webseite verwalten.');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['news']        = 'Nachrichten';
$GLOBALS['TL_LANG']['FMD']['newslist']    = array('Nachrichtenliste', 'Dieses Modul zeigt eine bestimmte Anzahl an Beiträgen eines bestimmten Nachrichtenarchivs.');
$GLOBALS['TL_LANG']['FMD']['newsreader']  = array('Nachrichtenleser', 'Dieses Modul zeigt einen einzelnen Nachrichtenbeitrag.');
$GLOBALS['TL_LANG']['FMD']['newsarchive'] = array('Nachrichtenarchiv', 'Dieses Module zeigt alle Beiträge eines bestimmten Nachrichtenarchives. Bitte beachten Sie, dass das Modul "Nachrichtenarchiv-Menü" benötigt wird, um durch das Archiv zu navigieren.');
$GLOBALS['TL_LANG']['FMD']['newsmenu']    = array('Nachrichtenarchiv-Menü', 'Mit diesem Modul können Sie durch ein Nachrichtenarchiv navigieren.');

?>