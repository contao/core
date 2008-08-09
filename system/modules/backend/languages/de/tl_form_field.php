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
 * Form fields
 */
$GLOBALS['TL_LANG']['FFL']['headline']    = array('Überschrift', 'Eine Feldüberschrift kann dazu verwendet werden, einzelne Felder voneinander abzugrenzen und das Formular so zu strukturieren. Eine Feldüberschrift kann HTML-Code enthalten.');
$GLOBALS['TL_LANG']['FFL']['explanation'] = array('Erklärung', 'Ein Erklärungsfeld kann dazu verwendet werden, eine längere Erklärung oder Frage vor einem Feld einzufügen. Ein Erklärungsfeld kann HTML-Code enthalten.');
$GLOBALS['TL_LANG']['FFL']['html']        = array('HTML', 'Verwenden Sie dieses Feld, um HTML-Code in die Form einzufügen.');
$GLOBALS['TL_LANG']['FFL']['text']        = array('Textfeld', 'Ein Textfeld ist ein einzeiliges Eingabefeld für einen kurzen oder mittellangen Text.');
$GLOBALS['TL_LANG']['FFL']['password']    = array('Passwortfeld', 'Ein Passwortfeld ist ein einzeiliges Eingabefeld für ein Passwort.');
$GLOBALS['TL_LANG']['FFL']['textarea']    = array('Textarea', 'Eine Textarea ist ein mehrzeiliges Eingabefeld für einen mittellangen oder langen Text.');
$GLOBALS['TL_LANG']['FFL']['select']      = array('Select-Menü', 'Ein Select-Menü ist ein einzeiliges Drop-Down-Menü mit mehreren Optionen von denen eine ausgewählt werden kann.');
$GLOBALS['TL_LANG']['FFL']['radio']       = array('Radio-Button-Menü', 'Ein Radio-Button-Menü ist ein mehrzeiliges Menü mit mehreren Optionen von denen eine ausgewählt werden kann.');
$GLOBALS['TL_LANG']['FFL']['checkbox']    = array('Checkbox-Menü', 'Ein Checkbox-Menü ist ein mehrzeiliges Menü mit einer oder mehreren Optionen von denen beliebig viele ausgewählt werden können.');
$GLOBALS['TL_LANG']['FFL']['upload']      = array('Datei-Upload', 'Ein Datei-Uploadfeld enthält eine Schaltfläche um eine lokale Festplatte zu durchsuchen und eine lokale Datei hochzuladen.');
$GLOBALS['TL_LANG']['FFL']['submit']      = array('Absendefeld', 'Ein Absendefeld enthält die Absende-Schaltfläche und die Kopfdaten des Formulars (z.B. Empfänger und Betreff der E-Mail).');
$GLOBALS['TL_LANG']['FFL']['captcha']     = array('Sicherheitsfrage', 'Dieses Feld fügt dem Formular eine einfache Mathematik Aufgabe hinzu, die zum Versenden richtig beantwortet werden muss.');
$GLOBALS['TL_LANG']['FFL']['hidden']      = array('Verstecktes Feld', 'Ein verstecktes Feld ist ein einzeiliges Eingabefeld, das nicht im Formular zu sehen ist.');


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_form_field']['type']           = array('Feldtyp', 'Bitte wählen Sie einen Feldtyp.');
$GLOBALS['TL_LANG']['tl_form_field']['text']           = array('Text', 'Bitte geben Sie die Überschrift oder Erklärung ein. HTML-Tags sind erlaubt.');
$GLOBALS['TL_LANG']['tl_form_field']['html']           = array('HTML', 'Bitte geben Sie den HTML-Code ein.');
$GLOBALS['TL_LANG']['tl_form_field']['name']           = array('Feldname', 'Bitte geben Sie den Namen des Feldes ein. Der Feldname ist gleichzeitig der Name der Variable wenn die Form abgeschickt wird. Der Feldname ist nicht sichtbar für die Besucher Ihrer Webseite.');
$GLOBALS['TL_LANG']['tl_form_field']['label']          = array('Feldbezeichnung', 'Bitte geben Sie eine Bezeichnung für das Feld ein. Die Feldbezeichnung wird den Besuchern Ihrer Webseite angezeigt. Wenn Sie eine mehrsprachige Website erstellen, sollte die Feldbezeichnung in die jeweilige Sprache übersetzt werden.');
$GLOBALS['TL_LANG']['tl_form_field']['mandatory']      = array('Pflichtfeld', 'Wenn Sie diese Option wählen, muss das aktuelle Feld ausgefüllt werden.');
$GLOBALS['TL_LANG']['tl_form_field']['rgxp']           = array('Eingabeprüfung', 'Wenn Sie hier ein Suchmuster wählen, werden Eingaben in das aktuelle Feld nur dann weiterverarbeitet, wenn sie mit dem Suchmuster übereinstimmen.');
$GLOBALS['TL_LANG']['tl_form_field']['digit']          = array('Numerische Zeichen', 'erlaubt numerische Zeichen, Minus (-), Punkt (.) und Leerzeichen');
$GLOBALS['TL_LANG']['tl_form_field']['alpha']          = array('Alphabetische Zeichen', 'erlaubt alphabetische Zeichen, Bindestrich (-), Punkt (.) und Leerzeichen');
$GLOBALS['TL_LANG']['tl_form_field']['alnum']          = array('Alphanumerische Zeichen', 'erlaubt alphabetische und numerische Zeichen, Minus (-), Punkt (.) und Leerzeichen');
$GLOBALS['TL_LANG']['tl_form_field']['extnd']          = array('Erweiterte alphanumerische Zeichen', 'erlaubt alle Zeichen außer denen, die standardmäßig kodiert werden (#&()/<=>), um z.B. Probleme mit Passwortfeldern zu vermeiden.');
$GLOBALS['TL_LANG']['tl_form_field']['url']            = array('URL Format', 'prüft ob die Eingabe einer gültigen URL entspricht. Eine gültige URL besteht aus den Buchstaben A-Za-z und folgenden Sonderzeichen (-_.:;/?&=+#). Alle anderen Sonderzeichen müssen kodiert werden!');
$GLOBALS['TL_LANG']['tl_form_field']['date']           = array('Datum', 'prüft ob die Eingabe dem verwendeten Datumsformat entspricht.');
$GLOBALS['TL_LANG']['tl_form_field']['datim']          = array('Datum und Uhrzeit', 'prüft ob die Eingabe dem verwendeten Datums- und Uhrzeitformat entspricht.');
$GLOBALS['TL_LANG']['tl_form_field']['email']          = array('E-Mail-Adresse', 'prüft ob die Eingabe eine gültige E-Mail-Adresse ist.');
$GLOBALS['TL_LANG']['tl_form_field']['phone']          = array('Telefonnummer', 'erlaubt numerische Zeichen, Plus (+), Minus (-), Schrägstrich (/), Klammern () und Leerzeichen');
$GLOBALS['TL_LANG']['tl_form_field']['maxlength']      = array('Maximale Eingabelänge', 'Hier können Sie die maximale Anzahl Zeichen eines Textfeldes bzw. die maximale Dateigröße eines Datei-Uploadfelds in Bytes festlegen (1 MB = 1024 kB = 1024000 Bytes).');
$GLOBALS['TL_LANG']['tl_form_field']['value']          = array('Wert', 'Wenn Sie hier einen Wert eingeben, wird das Feld mit diesem Wert vorbelegt.');
$GLOBALS['TL_LANG']['tl_form_field']['size']           = array('Reihen und Spalten', 'Bitte geben Sie die Anzahl an Reihen und Spalten für die Textarea ein.');
$GLOBALS['TL_LANG']['tl_form_field']['options']        = array('Optionen', 'Verwenden Sie die Schaltflächen, um Optionen zu bearbeiten, hinzuzufügen, zu verschieben oder zu löschen. Wenn Sie ohne JavaScript-Unterstützung arbeiten, sollten Sie Ihre Eingaben speichern bevor Sie Optionen verändern!');
$GLOBALS['TL_LANG']['tl_form_field']['multiple']       = array('Mehrfachauswahl', 'Erlaubt die Auswahl mehrerer Optionen.');
$GLOBALS['TL_LANG']['tl_form_field']['mSize']          = array('Listengröße', 'Bitte geben Sie die Größe der Auswahlliste an.');
$GLOBALS['TL_LANG']['tl_form_field']['extensions']     = array('Erlaubte Dateitypen', 'Kommagetrennte Liste gültiger Dateitypen, die auf den Server übertragen werden dürfen.');
$GLOBALS['TL_LANG']['tl_form_field']['accesskey']      = array('Tastaturkürzel', 'Ein Tastaturkürzel ist ein einzelnes Zeichen, das einem Formfeld zugewiesen werden kann. Wenn ein Besucher gleichzeitig die [ALT] Taste und das Tastaturkürzel drückt, gelangt er direkt zu dem entsprechenden Formfeld.');
$GLOBALS['TL_LANG']['tl_form_field']['class']          = array('CSS-Klasse', 'Hier können Sie eine oder mehrere CSS-Klassen eingeben.');
$GLOBALS['TL_LANG']['tl_form_field']['storeFile']      = array('Hochgeladene Dateien speichern', 'Wenn Sie diese Option wählen, werden hochgeladene Dateien in einen Ordner auf Ihrem Server verschoben. Wählen Sie diese Option nicht, wenn Sie hochgeladene Dateien per E-Mail versenden und sie nicht speichern möchten.');
$GLOBALS['TL_LANG']['tl_form_field']['uploadFolder']   = array('Upload-Verzeichnis', 'Bitte wählen Sie das Verzeichnis, in dem hochgeladene Bilder gespeichert werden sollen.');
$GLOBALS['TL_LANG']['tl_form_field']['useHomeDir']     = array('Im Benutzerverzeichnis speichern', 'Ist ein Benutzer angemeldet, wird die Datei in seinem Benutzerverzeichnis gespeichert.');
$GLOBALS['TL_LANG']['tl_form_field']['doNotOverwrite'] = array('Bestehende Dateien nicht überschreiben', 'Dem Dateinamen eine numerische ID hinzufügen wenn die Datei bereits besteht.');
$GLOBALS['TL_LANG']['tl_form_field']['addSubmit']      = array('Absende-Schaltfläche hinzufügen', 'Wenn Sie aber diese Option wählen, wird neben dem aktuellen Feld eine Absende-Schaltfläche eingefügt. Benutzen Sie diese Option, um einzeilige Formulare (z.B. ein Suchformular) zu erstellen.');
$GLOBALS['TL_LANG']['tl_form_field']['imageSubmit']    = array('Bildschaltfläche', 'Eine Bildschaltfläche anstatt einer Textschaltfläche verwenden.');
$GLOBALS['TL_LANG']['tl_form_field']['singleSRC']      = array('Quelldatei', 'Bitte wählen Sie ein Bild aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['tl_form_field']['slabel']         = array('Bezeichnung der Absende-Schaltfläche', 'Bitte geben Sie eine Bezeichnung für die Absende-Schaltfläche ein.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_form_field']['opValue']   = 'Wert';
$GLOBALS['TL_LANG']['tl_form_field']['opLabel']   = 'Bezeichnung';
$GLOBALS['TL_LANG']['tl_form_field']['opDefault'] = 'Standard';
$GLOBALS['TL_LANG']['tl_form_field']['opGroup']   = 'Gruppe';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_form_field']['new']        = array('Neues Feld', 'Ein neues Feld anlegen');
$GLOBALS['TL_LANG']['tl_form_field']['show']       = array('Felddetails', 'Details des Feldes ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_form_field']['edit']       = array('Feld bearbeiten', 'Das Feld ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_form_field']['cut']        = array('Feld verschieben', 'Das Feld ID %s verschieben');
$GLOBALS['TL_LANG']['tl_form_field']['copy']       = array('Feld duplizieren', 'Das Feld ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_form_field']['delete']     = array('Feld löschen', 'Das Feld ID %s löschen');
$GLOBALS['TL_LANG']['tl_form_field']['editheader'] = array('Formularkopf bearbeiten', 'Den Kopf dieses Formulars bearbeiten');
$GLOBALS['TL_LANG']['tl_form_field']['pasteafter'] = array('Am Anfang einfügen', 'Nach dem Feld ID %s einfügen');
$GLOBALS['TL_LANG']['tl_form_field']['pastenew']   = array('Neues Feld am Anfang erstellen', 'Neues Feld nach dem Feld ID %s erstellen');
$GLOBALS['TL_LANG']['tl_form_field']['up']         = array('Eine Position nach oben', 'Dieses Feld eine Position nach oben verschieben');
$GLOBALS['TL_LANG']['tl_form_field']['down']       = array('Eine Position nach unten', 'Dieses Feld eine Position nach unten verschieben');

?>