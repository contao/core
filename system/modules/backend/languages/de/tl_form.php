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
$GLOBALS['TL_LANG']['tl_form']['title']        = array('Titel', 'Bitte geben Sie einen Titel für das Formular ein.');
$GLOBALS['TL_LANG']['tl_form']['formID']       = array('Formular-ID', 'Hier können Sie eine optionale Formular-ID eingeben (zur Steuerung von TYPOlight Modulen).');
$GLOBALS['TL_LANG']['tl_form']['method']       = array('Übertragungsmethode', 'Bitte wählen Sie eine Übertragungsmethode für das Formular (Standard: POST).');
$GLOBALS['TL_LANG']['tl_form']['allowTags']    = array('HTML-Tags erlauben', 'Wenn Sie diese Option wählen, werden bestimmte HTML-Tags nicht entfernt (siehe <em>allowedTags</em>).');
$GLOBALS['TL_LANG']['tl_form']['storeValues']  = array('Eingaben speichern', 'Übermittelte Formulareingaben in der Datenbank speichern.');
$GLOBALS['TL_LANG']['tl_form']['targetTable']  = array('Zieltabelle', 'Bitte wählen Sie die Tabelle, in der die Eingaben gespeichert werden sollen.');
$GLOBALS['TL_LANG']['tl_form']['tableless']    = array('Tabellenloses Layout', 'Wenn Sie diese Option wählen, wird das Formular ohne Tabellen gerendert.');
$GLOBALS['TL_LANG']['tl_form']['sendViaEmail'] = array('Per E-Mail versenden', 'Wenn Sie diese Option wählen, werden die Formulardaten per E-Mail versendet.');
$GLOBALS['TL_LANG']['tl_form']['recipient']    = array('Empfänger', 'Kommagetrennte Liste von E-Mail-Adressen (z.B. <em>name@domain.com</em> oder <em>Name [name@domain.com]</em>).');
$GLOBALS['TL_LANG']['tl_form']['subject']      = array('Betreff', 'Geben Sie eine Betreffzeile ein, damit die E-Mail nicht als SPAM eingestuft wird.');
$GLOBALS['TL_LANG']['tl_form']['format']       = array('Datenformat', 'Hier legen Sie fest, in welchem Format die Formulardaten übermittelt werden.');
$GLOBALS['TL_LANG']['tl_form']['raw']          = array('Unbearbeitete Daten', 'Die Formulardaten werden als einfache Textnachricht mit einem Zeilenumbruch nach jedem Feld versendet.');
$GLOBALS['TL_LANG']['tl_form']['email']        = array('E-Mail-Format', 'Dieses Format erwartet die Felder <em>email</em>, <em>subject</em>, <em>message</em> und <em>cc</em> (eine Kopie der E-Mail an den Absender schicken). Anders benannte Felder werden ignoriert. Datei-Uploads sind erlaubt.');
$GLOBALS['TL_LANG']['tl_form']['xml']          = array('XML-Datei', 'Die Formulardaten werden der E-Mail als XML-Datei angefügt.');
$GLOBALS['TL_LANG']['tl_form']['csv']          = array('CSV-Datei', 'Die Formulardaten werden der E-Mail als CSV-Datei (comma separated values) angefügt.');
$GLOBALS['TL_LANG']['tl_form']['skipEmtpy']    = array('Leere Felder überspringen', 'Leere Felder nicht per E-Mail versenden.');
$GLOBALS['TL_LANG']['tl_form']['jumpTo']       = array('Weiterleitung zu Seite', 'Die Daten eines Formulars werden normalerweise zu einer anderen Seite weitergeleitet, die diese Daten weiterverarbeitet oder eine "Vielen Dank für Ihre Nachricht" Meldung anzeigt. Sie können diese Seite hier festlegen.');
$GLOBALS['TL_LANG']['tl_form']['tstamp']       = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');
$GLOBALS['TL_LANG']['tl_form']['attributes']   = array('Stylesheet-ID und -Klasse', 'Hier können Sie eine Stylesheet-ID (id attribute) sowie eine odere mehrere Stylesheet-Klassen (class attribute) eingeben, um das Formular mittles CSS formatieren zu können.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_form']['new']    = array('Neues Formular', 'Ein neues Formular anlegen');
$GLOBALS['TL_LANG']['tl_form']['show']   = array('Formulardetails', 'Details des Formulars ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_form']['edit']   = array('Formular bearbeiten', 'Das Formular ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_form']['copy']   = array('Formular duplizieren', 'Das Formular ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_form']['delete'] = array('Formular löschen', 'Das Formular ID %s löschen');

?>