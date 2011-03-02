<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Form fields
 */
$GLOBALS['TL_LANG']['FFL']['headline']    = array('Überschrift', 'Ein individuelles Feld zum Einfügen einer Bereichsüberschrift.');
$GLOBALS['TL_LANG']['FFL']['explanation'] = array('Erklärung', 'Ein individuelles Feld zum Einfügen eines Erklärungstexts.');
$GLOBALS['TL_LANG']['FFL']['html']        = array('HTML', 'Ein individuelles Feld zum Einfügen von HTML-Code.');
$GLOBALS['TL_LANG']['FFL']['fieldset']    = array('Fieldset', 'Ein Container für Formularfelder mit einer optionalen Legende.');
$GLOBALS['TL_LANG']['FFL']['text']        = array('Textfeld', 'Ein einzeiliges Eingabefeld für einen kurzen oder mittellangen Text.');
$GLOBALS['TL_LANG']['FFL']['password']    = array('Passwortfeld', 'Ein einzeiliges Eingabefeld für ein Passwort. Contao fügt automatisch ein Bestätigungsfeld hinzu.');
$GLOBALS['TL_LANG']['FFL']['textarea']    = array('Textarea', 'Ein mehrzeiliges Eingabefeld für einen mittellangen oder langen Text.');
$GLOBALS['TL_LANG']['FFL']['select']      = array('Select-Menü', 'Ein ein- oder mehrzeiliges Drop-Down-Menü.');
$GLOBALS['TL_LANG']['FFL']['radio']       = array('Radio-Button-Menü', 'Eine Liste mehrerer Optionen, von denen eine ausgewählt werden kann.');
$GLOBALS['TL_LANG']['FFL']['checkbox']    = array('Checkbox-Menü', 'Eine Liste mehrerer Optionen, von denen beliebig viele ausgewählt werden können.');
$GLOBALS['TL_LANG']['FFL']['upload']      = array('Datei-Upload', 'Ein einzeiliges Eingabefeld zur Übertragung lokaler Dateien auf den Server.');
$GLOBALS['TL_LANG']['FFL']['hidden']      = array('Verstecktes Feld', 'Ein einzeiliges Eingabefeld, das im Formular nicht sichtbar ist.');
$GLOBALS['TL_LANG']['FFL']['captcha']     = array('Sicherheitsfrage', 'Eine einfache Rechenaufgabe zur Prüfung, ob das Formular von einem Menschen abgeschickt wurde (CAPTCHA).');
$GLOBALS['TL_LANG']['FFL']['submit']      = array('Absendefeld', 'Eine Absende-Schaltfläche zur Versendung des Formulars.');


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_form_field']['type']           = array('Feldtyp', 'Bitte wählen Sie den Typ des Formularfelds.');
$GLOBALS['TL_LANG']['tl_form_field']['name']           = array('Feldname', 'Der Feldname ist ein eindeutiger Name zur Identifizierung des Feldes.');
$GLOBALS['TL_LANG']['tl_form_field']['label']          = array('Feldbezeichnung', 'Die Feldbezeichnung wird auf der Webseite angezeigt, normalerweise links neben oder oberhalb des Feldes.');
$GLOBALS['TL_LANG']['tl_form_field']['text']           = array('Text', 'Sie können HTML-Tags verwenden, um den Text zu formatieren.');
$GLOBALS['TL_LANG']['tl_form_field']['html']           = array('HTML-Code', 'Sie können die Liste der erlaubten HTML-Tags in den Backend-Einstellungen ändern.');
$GLOBALS['TL_LANG']['tl_form_field']['options']        = array('Optionen', 'Wenn JavaScript deaktiviert ist, speichern Sie unbedingt Ihre Änderungen, bevor Sie die Reihenfolge ändern.');
$GLOBALS['TL_LANG']['tl_form_field']['mandatory']      = array('Pflichtfeld', 'Das Feld muss zum Abschicken des Formulars ausgefüllt werden.');
$GLOBALS['TL_LANG']['tl_form_field']['rgxp']           = array('Eingabeprüfung', 'Die Eingaben anhand eines regulären Ausdrucks prüfen.');
$GLOBALS['TL_LANG']['tl_form_field']['digit']          = array('Numerische Zeichen', 'Erlaubt numerische Zeichen, Minus (-), Punkt (.) und Leerzeichen ( ).');
$GLOBALS['TL_LANG']['tl_form_field']['alpha']          = array('Alphabetische Zeichen', 'Erlaubt alphabetische Zeichen, Minus (-), Punkt (.) und Leerzeichen ( ).');
$GLOBALS['TL_LANG']['tl_form_field']['alnum']          = array('Alphanumerische Zeichen', 'Erlaubt alphabetische und numerische Zeichen, Minus (-), Punkt (.), Unterstrich (_) und Leerzeichen ( ).');
$GLOBALS['TL_LANG']['tl_form_field']['extnd']          = array('Erweiterte alphanumerische Zeichen', 'Erlaubt alle Zeichen außer denen, die normalerweise aus Sicherheitsgründen kodiert werden (#/()<=>).');
$GLOBALS['TL_LANG']['tl_form_field']['date']           = array('Datum', 'Prüft, ob die Eingabe dem globalen Datumsformat entspricht.');
$GLOBALS['TL_LANG']['tl_form_field']['time']           = array('Uhrzeit', 'Prüft, ob die Eingabe dem globalen Uhrzeitformat entspricht.');
$GLOBALS['TL_LANG']['tl_form_field']['datim']          = array('Datum und Uhrzeit', 'Prüft, ob die Eingabe dem globalen Datums- und Uhrzeitformat entspricht.');
$GLOBALS['TL_LANG']['tl_form_field']['phone']          = array('Telefonnummer', 'Erlaubt numerische Zeichen, Plus (+), Minus (-), Schrägstrich (/), Klammern () und Leerzeichen ( ).');
$GLOBALS['TL_LANG']['tl_form_field']['email']          = array('E-Mail-Adresse', 'Prüft, ob die Eingabe eine gültige E-Mail-Adresse ist.');
$GLOBALS['TL_LANG']['tl_form_field']['url']            = array('URL-Format', 'Prüft, ob die Eingabe eine gültige URL ist.');
$GLOBALS['TL_LANG']['tl_form_field']['maxlength']      = array('Maximale Eingabelänge', 'Hier können Sie die maximale Anzahl an Zeichen (Text) bzw. Bytes (Datei-Uploads) festlegen.');
$GLOBALS['TL_LANG']['tl_form_field']['size']           = array('Reihen und Spalten', 'Die Anzahl an Reihen und Spalten der Textarea.');
$GLOBALS['TL_LANG']['tl_form_field']['multiple']       = array('Mehrfachauswahl', 'Erlaubt die Auswahl mehrerer Optionen.');
$GLOBALS['TL_LANG']['tl_form_field']['mSize']          = array('Listengröße', 'Hier können Sie die Größe der Auswahlliste eingeben.');
$GLOBALS['TL_LANG']['tl_form_field']['extensions']     = array('Erlaubte Dateitypen', 'Eine kommagetrennte Liste gültiger Dateiendungen.');
$GLOBALS['TL_LANG']['tl_form_field']['storeFile']      = array('Hochgeladene Dateien speichern', 'Die hochgeladenen Dateien in einen Ordner auf dem Server verschieben.');
$GLOBALS['TL_LANG']['tl_form_field']['uploadFolder']   = array('Zielverzeichnis', 'Bitte wählen Sie das Zielverzeichnis aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['tl_form_field']['useHomeDir']     = array('Benutzerverzeichnis verwenden', 'Die Datei im Benutzerverzeichnis speichern, wenn sich ein Benutzer angemeldet hat.');
$GLOBALS['TL_LANG']['tl_form_field']['doNotOverwrite'] = array('Bestehende Dateien erhalten', 'Der neuen Datei ein numerisches Suffix hinzufügen, wenn der Dateiname bereits existiert.');
$GLOBALS['TL_LANG']['tl_form_field']['fsType']         = array('Betriebsart', 'Bitte wählen Sie die Betriebsart des Fieldset-Elements.');
$GLOBALS['TL_LANG']['tl_form_field']['fsStart']        = array('Umschlag Anfang', 'Markiert den Anfang eines Fieldset und kann eine Legende enthalten.');
$GLOBALS['TL_LANG']['tl_form_field']['fsStop']         = array('Umschlag Ende', 'Markiert das Ende eines Fieldset.');
$GLOBALS['TL_LANG']['tl_form_field']['value']          = array('Standard-Wert', 'Hier können Sie einen Standard-Wert für das Feld eingeben.');
$GLOBALS['TL_LANG']['tl_form_field']['class']          = array('CSS-Klasse', 'Hier können Sie eine oder mehrere Klassen eingeben.');
$GLOBALS['TL_LANG']['tl_form_field']['accesskey']      = array('Tastaturkürzel', 'Ein Formularfeld kann direkt angewählt werden, indem man gleichzeitig die [ALT]- bzw. [STRG]-Taste und das Tastaturkürzel drückt.');
$GLOBALS['TL_LANG']['tl_form_field']['addSubmit']      = array('Absende-Schaltfläche hinzufügen', 'Eine Absende-Schaltfläche neben dem Feld einfügen, um ein einzeiliges Formular zu erstellen.');
$GLOBALS['TL_LANG']['tl_form_field']['slabel']         = array('Bezeichnung der Absende-Schaltfläche', 'Bitte geben Sie die Bezeichnung der Absende-Schaltfläche ein.');
$GLOBALS['TL_LANG']['tl_form_field']['imageSubmit']    = array('Bildschaltfläche erstellen', 'Eine Bildschaltfläche anstatt der Standard-Schaltfläche verwenden.');
$GLOBALS['TL_LANG']['tl_form_field']['singleSRC']      = array('Quelldatei', 'Bitte wählen Sie ein Bild aus der Dateiübersicht.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_form_field']['type_legend']    = 'Feldtyp und -name';
$GLOBALS['TL_LANG']['tl_form_field']['text_legend']    = 'Text/HTML';
$GLOBALS['TL_LANG']['tl_form_field']['fconfig_legend'] = 'Feldkonfiguration';
$GLOBALS['TL_LANG']['tl_form_field']['options_legend'] = 'Optionen';
$GLOBALS['TL_LANG']['tl_form_field']['store_legend']   = 'Datei speichern';
$GLOBALS['TL_LANG']['tl_form_field']['expert_legend']  = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_form_field']['submit_legend']  = 'Absende-Schaltfläche';
$GLOBALS['TL_LANG']['tl_form_field']['image_legend']   = 'Bildschaltfläche';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_form_field']['new']        = array('Neues Feld', 'Ein neues Feld anlegen');
$GLOBALS['TL_LANG']['tl_form_field']['show']       = array('Felddetails', 'Details des Feldes ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_form_field']['edit']       = array('Feld bearbeiten', 'Das Feld ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_form_field']['cut']        = array('Feld verschieben', 'Das Feld ID %s verschieben');
$GLOBALS['TL_LANG']['tl_form_field']['copy']       = array('Feld duplizieren', 'Das Feld ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_form_field']['delete']     = array('Feld löschen', 'Das Feld ID %s löschen');
$GLOBALS['TL_LANG']['tl_form_field']['editheader'] = array('Formular bearbeiten', 'Die Formular-Einstellungen bearbeiten');
$GLOBALS['TL_LANG']['tl_form_field']['pasteafter'] = array('Oben einfügen', 'Nach dem Feld ID %s einfügen');
$GLOBALS['TL_LANG']['tl_form_field']['pastenew']   = array('Neues Feld oben erstellen', 'Neues Element nach dem Feld ID %s erstellen');
$GLOBALS['TL_LANG']['tl_form_field']['toggle']     = array('Sichtbarkeit ändern', 'Die Sichtbarkeit des Feldes ID %s ändern');

?>