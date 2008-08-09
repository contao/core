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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_log']['tstamp']   = array('Datum', 'Datum und Uhrzeit des Eintrags');
$GLOBALS['TL_LANG']['tl_log']['action']   = array('Kategorie', 'Kategorie der Aktion');
$GLOBALS['TL_LANG']['tl_log']['source']   = array('Ursprung', 'Log-Einträge können nach ihrem Ursprung sortiert werden (Backend oder Frontend Eintrag).');
$GLOBALS['TL_LANG']['tl_log']['username'] = array('Benutzer', 'Name des angemeldeten Benutzers');
$GLOBALS['TL_LANG']['tl_log']['text']     = array('Details', 'Details des aktuellen Eintrags');
$GLOBALS['TL_LANG']['tl_log']['ip']       = array('IP Adresse', 'Anfragende IP-Adresse');
$GLOBALS['TL_LANG']['tl_log']['browser']  = array('Browser', 'Anfragender Browser');
$GLOBALS['TL_LANG']['tl_log']['func']     = array('Funktion', 'Name der Funktion');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_log']['BE'] = 'Backend';
$GLOBALS['TL_LANG']['tl_log']['FE'] = 'Frontend';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_log']['show']   = array('Anzeigen', 'Eintrag ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_log']['delete'] = array('Löschen', 'Eintrag ID %s löschen');

?>