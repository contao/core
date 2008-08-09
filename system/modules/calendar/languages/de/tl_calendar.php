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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_calendar']['title']       = array('Titel', 'Bitte geben Sie den Titel des Kalenders ein.');
$GLOBALS['TL_LANG']['tl_calendar']['tstamp']      = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');
$GLOBALS['TL_LANG']['tl_calendar']['language']    = array('Sprache', 'Bitte geben Sie die Sprache im RFC3066 Format ein (z.B. <em>en</em>, <em>en-us</em> oder <em>en-cockney</em>).');
$GLOBALS['TL_LANG']['tl_calendar']['jumpTo']      = array('Weiterleitung zu', 'Bitte wählen Sie zu welcher Seite Ihre Besucher beim Anklicken eines Events weitergeleitet werden sollen.');
$GLOBALS['TL_LANG']['tl_calendar']['protected']   = array('Kalender schützen', 'Events nur für bestimmte Frontend Gruppen anzeigen.');
$GLOBALS['TL_LANG']['tl_calendar']['groups']      = array('Erlaubte Mitgliedergruppen', 'Hier können Sie festlegen, welche Mitgliedergruppen die Events sehen können.');
$GLOBALS['TL_LANG']['tl_calendar']['makeFeed']    = array('Feed erstellen', 'Einen RSS/Atom-Feed aus dem Kalender erstellen.');
$GLOBALS['TL_LANG']['tl_calendar']['format']      = array('Feed Format', 'Bitte wählen Sie ein Format.');
$GLOBALS['TL_LANG']['tl_calendar']['description'] = array('Beschreibung', 'Bitte geben Sie eine kurze Beschreibung des Kalenders ein.');
$GLOBALS['TL_LANG']['tl_calendar']['alias']       = array('Feed-Alias', 'Hier können Sie einen eindeutigen Feednamen eingeben. Es wird automatisch eine XML-Datei im Wurzelverzeichnis Ihrer TYPOlight-Installation erstellt (<em>name.xml</em>).');
$GLOBALS['TL_LANG']['tl_calendar']['feedBase']    = array('Basis-URL', 'Bitte geben Sie den Basis-URL inklusive Protokoll (z.B. <em>http://</em>) ein.');
$GLOBALS['TL_LANG']['tl_calendar']['maxItems']    = array('Maximale Anzahl an Beiträgen', 'Limitiert die Anzahl der Beiträge. Lassen Sie das Feld leer oder geben Sie 0 ein, um alle Beiträge zu exportieren.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_calendar']['new']    = array('Neuer Kalender', 'Einen neuen Kalender erstellen');
$GLOBALS['TL_LANG']['tl_calendar']['edit']   = array('Kalender bearbeiten', 'Kalender ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_calendar']['copy']   = array('Kalender duplizieren', 'Kalender ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_calendar']['delete'] = array('Kalender löschen', 'Kalender ID %s löschen');
$GLOBALS['TL_LANG']['tl_calendar']['show']   = array('Kalenderdetails', 'Details des Kalenders ID %s anzeigen');

?>