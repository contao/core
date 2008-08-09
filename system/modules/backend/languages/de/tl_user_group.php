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
$GLOBALS['TL_LANG']['tl_user_group']['name']       = array('Name der Gruppe', 'Bitte geben Sie einen eindeutigen Namen für die Gruppe ein.');
$GLOBALS['TL_LANG']['tl_user_group']['modules']    = array('Backend Module', 'Bitte wählen Sie die Module aus, die für die Mitglieder der Gruppe freigeschaltet werden sollen.');
$GLOBALS['TL_LANG']['tl_user_group']['pagemounts'] = array('Pagemounts', 'Bitte wählen Sie die Seiten aus, die für die Mitglieder der Gruppe freigeschaltet werden sollen. Eine Seite wird immer inklusive aller Unterseiten freigeschaltet.');
$GLOBALS['TL_LANG']['tl_user_group']['alpty']      = array('Erlaubte Seitentypen', 'Bitte wählen Sie, welche Seitentypen für die Mitglieder dieser Gruppe verfügbar sein sollen.');
$GLOBALS['TL_LANG']['tl_user_group']['filemounts'] = array('Filemounts', 'Bitte wählen Sie die Verzeichnisse aus, die für die Mitglieder der Gruppe freigeschaltet werden sollen. Ein Verzeichnis wird immer inklusive aller Unterverzeichnisse freigeschaltet!.');
$GLOBALS['TL_LANG']['tl_user_group']['forms']      = array('Formulare', 'Bitte wählen Sie die Formulare, die für die Gruppe sichtbar sein sollen.');
$GLOBALS['TL_LANG']['tl_user_group']['alexf']      = array('Erlaubte Felder', 'Geben Sie hier die Felder frei, die für die Gruppe editierbar sein sollen.');
$GLOBALS['TL_LANG']['tl_user_group']['disable']    = array('Deaktivieren', 'Wenn Sie diese Option wählen, wird die Gruppe deaktiviert. Die Zugriffsrechte der deaktivierten Gruppe stehen den Mitgliedern dann nicht mehr zur Verfügung.');
$GLOBALS['TL_LANG']['tl_user_group']['start']      = array('Aktivieren am', 'Wenn Sie hier ein Datum eingeben, wird die Gruppe an diesem Tag aktiviert.');
$GLOBALS['TL_LANG']['tl_user_group']['stop']       = array('Deaktivieren am', 'Wenn Sie hier ein Datum eingeben, wird die Gruppe an diesem Tag deaktiviert.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_user_group']['new']    = array('Neue Benutzergruppe', 'Eine neue Benutzergruppe anlegen');
$GLOBALS['TL_LANG']['tl_user_group']['show']   = array('Gruppendetails', 'Details der Gruppe ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_user_group']['copy']   = array('Gruppe duplizieren', 'Gruppe ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_user_group']['delete'] = array('Gruppe löschen', 'Gruppe ID %s löschen');
$GLOBALS['TL_LANG']['tl_user_group']['edit']   = array('Gruppe bearbeiten', 'Gruppe ID %s bearbeiten');

?>