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
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['calendar'] = array('Kalender/Events', 'Mit diesem Modul können Sie verschiedene Kalender verwalten.');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['events']          = 'Kalender/Events';
$GLOBALS['TL_LANG']['FMD']['calendar']        = array('Kalender', 'Dieses Modul fügt Ihrer Webseite einen Kalender hinzu.');
$GLOBALS['TL_LANG']['FMD']['minicalendar']    = array('Mini-Kalender', 'Dieses Modul fügt Ihrer Webseite einen Mini-Kalender hinzu, der zur Steuerung des Moduls "Eventliste" verwendet werden kann.');
$GLOBALS['TL_LANG']['FMD']['eventreader']     = array('Eventleser', 'Dieses Modul stellt einen bestimmten Event dar.');
$GLOBALS['TL_LANG']['FMD']['eventlist']       = array('Eventliste', 'Dieses Modul listet alle Events eines Monats, einer Woche oder eines Tages.');
$GLOBALS['TL_LANG']['FMD']['upcoming_events'] = array('Nächste Events', 'Dieses Modul listet eine bestimmte Anzahl demnächst stattfindender Events.');

?>