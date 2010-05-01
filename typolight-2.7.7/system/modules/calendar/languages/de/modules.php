<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['calendar'] = array('Events', 'Events verwalten und als Kalender oder Eventliste ausgeben.');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['events']          = 'Events';
$GLOBALS['TL_LANG']['FMD']['calendar']        = array('Kalender', 'fügt der Seite einen Kalender hinzu.');
$GLOBALS['TL_LANG']['FMD']['eventreader']     = array('Eventleser', 'stellt einen einzelnen Event dar.');
$GLOBALS['TL_LANG']['FMD']['eventlist']       = array('Eventliste', 'listet alle Events eines bestimmten Zeitraums auf.');
$GLOBALS['TL_LANG']['FMD']['upcoming_events'] = array('Nächste Events', 'listet alle demnächst stattfindenden Events auf.');

?>