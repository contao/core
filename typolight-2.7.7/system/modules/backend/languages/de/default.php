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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Error messages
 */
$GLOBALS['TL_LANG']['ERR']['general']        = 'Ein Fehler ist aufgetreten!';
$GLOBALS['TL_LANG']['ERR']['unique']         = 'Doppelter Eintrag im Feld "%s"!';
$GLOBALS['TL_LANG']['ERR']['mandatory']      = 'Bitte füllen Sie das Feld "%s" aus!';
$GLOBALS['TL_LANG']['ERR']['minlength']      = 'Das Feld "%s" muss mindestens %d Zeichen lang sein!';
$GLOBALS['TL_LANG']['ERR']['digit']          = 'Bitte geben Sie nur Zahlen ein!';
$GLOBALS['TL_LANG']['ERR']['prcnt']          = 'Bitte geben Sie einen Prozentsatz zwischen 0 und 100 ein!';
$GLOBALS['TL_LANG']['ERR']['alpha']          = 'Bitte geben Sie nur Buchstaben ein!';
$GLOBALS['TL_LANG']['ERR']['alnum']          = 'Bitte geben Sie nur Buchstaben und Zahlen ein!';
$GLOBALS['TL_LANG']['ERR']['phone']          = 'Bitte geben Sie eine gültige Telefonnummer ein!';
$GLOBALS['TL_LANG']['ERR']['extnd']          = 'Aus Sicherheitsgründen können Sie diese Zeichen (=<>&/()#) hier nicht verwenden!';
$GLOBALS['TL_LANG']['ERR']['email']          = 'Bitte geben Sie eine gültige E-Mail-Adresse ein!';
$GLOBALS['TL_LANG']['ERR']['url']            = 'Bitte geben Sie ein gültiges URL-Format ein und kodieren Sie Sonderzeichen!';
$GLOBALS['TL_LANG']['ERR']['date']           = 'Bitte geben Sie das Datum im Format "%s" ein!';
$GLOBALS['TL_LANG']['ERR']['time']           = 'Bitte geben Sie die Uhrzeit im Format "%s" ein!';
$GLOBALS['TL_LANG']['ERR']['dateTime']       = 'Bitte geben Sie Datum und Uhrzeit im Format "%s" ein!';
$GLOBALS['TL_LANG']['ERR']['noSpace']        = 'Das Feld "%s" darf keine Leerzeichen enthalten!';
$GLOBALS['TL_LANG']['ERR']['filesize']       = 'Die maximale Größe für Datei-Uploads beträgt %s kB!';
$GLOBALS['TL_LANG']['ERR']['filetype']       = 'Der Dateityp "%s" darf nicht hochgeladen werden!';
$GLOBALS['TL_LANG']['ERR']['filepartial']    = 'Die Datei %s wurde nur teilweise hochgeladen!';
$GLOBALS['TL_LANG']['ERR']['filewidth']      = 'Die Datei %s übersteigt die maximale Bildbreite von %d Pixel!';
$GLOBALS['TL_LANG']['ERR']['fileheight']     = 'Die Datei %s übersteigt die maximale Bildhöhe von %d Pixel!';
$GLOBALS['TL_LANG']['ERR']['invalidReferer'] = 'Zugriff verweigert! Die aktuelle Hostadresse (%s) stimmt nicht mit der verweisenden Hostadresse (%s) überein!';
$GLOBALS['TL_LANG']['ERR']['invalidPass']    = 'Ungültiges Passwort!';
$GLOBALS['TL_LANG']['ERR']['passwordLength'] = 'Ein Passwort muss mindestens %d Zeichen lang sein!';
$GLOBALS['TL_LANG']['ERR']['passwordName']   = 'Der Benutzername und das Passwort müssen unterschiedlich sein!';
$GLOBALS['TL_LANG']['ERR']['passwordMatch']  = 'Die eingegebenen Passwörter stimmen nicht überein!';
$GLOBALS['TL_LANG']['ERR']['accountLocked']  = 'Das Konto wurde gesperrt! Sie können sich in %d Minuten erneut anmelden.';
$GLOBALS['TL_LANG']['ERR']['invalidLogin']   = 'Anmeldung fehlgeschlagen!';
$GLOBALS['TL_LANG']['ERR']['invalidColor']   = 'Ungültiges Farbformat!';
$GLOBALS['TL_LANG']['ERR']['all_fields']     = 'Bitte wählen Sie mindestens ein Feld aus!';
$GLOBALS['TL_LANG']['ERR']['aliasExists']    = 'Der Alias "%s" existiert bereits!';
$GLOBALS['TL_LANG']['ERR']['importFolder']   = 'Der Ordner "%s" kann nicht importiert werden!';


/**
 * Page types
 */
$GLOBALS['TL_LANG']['PTY']['regular']   = array('Reguläre Seite', 'Eine reguläre Seite enthält Artikel und Inhaltselemente. Sie ist der Standard-Seitentyp.');
$GLOBALS['TL_LANG']['PTY']['redirect']  = array('Externe Weiterleitung', 'Dieser Seitentyp leitet Besucher automatisch zu einer externen Webseite um. Die Funktionsweise entspricht der eines Hyperlinks.');
$GLOBALS['TL_LANG']['PTY']['forward']   = array('Interne Weiterleitung', 'Dieser Seitentyp leitet Besucher automatisch zu einer anderen Seite innerhalb der Seitenstruktur um.');
$GLOBALS['TL_LANG']['PTY']['root']      = array('Startpunkt einer Webseite', 'Dieser Seitentyp kennzeichnet den Startpunkt einer neuen Webseite innerhalb der Seitenstruktur.');
$GLOBALS['TL_LANG']['PTY']['error_403'] = array('403 Zugriff verweigert', 'Ruft ein Benutzer eine geschützte Seite ohne die entsprechenden Zugriffsrechte auf, wird stattdessen die 403-Fehlerseite geladen.');
$GLOBALS['TL_LANG']['PTY']['error_404'] = array('404 Seite nicht gefunden', 'Ruft ein Benutzer eine nicht vorhandene Seite auf, wird stattdessen die 404-Fehlerseite geladen.');


/**
 * File operation permissions
 */
$GLOBALS['TL_LANG']['FOP']['fop'] = array('Erlaubte Datei-Operationen', 'Hier können Sie die erlaubten Datei-Operationen festlegen.');
$GLOBALS['TL_LANG']['FOP']['f1']  = 'Dateien auf den Server hochladen';
$GLOBALS['TL_LANG']['FOP']['f2']  = 'Dateien und Verzeichnisse bearbeiten, kopieren und verschieben';
$GLOBALS['TL_LANG']['FOP']['f3']  = 'Einzelne Dateien und leere Verzeichnisse löschen';
$GLOBALS['TL_LANG']['FOP']['f4']  = 'Verzeichnisse inklusive aller Dateien und Unterordner löschen (!)';
$GLOBALS['TL_LANG']['FOP']['f5']  = 'Dateien im Quelltexteditor bearbeiten';


/**
 * CHMOD levels
 */
$GLOBALS['TL_LANG']['CHMOD']['editpage']       = 'Seite bearbeiten';
$GLOBALS['TL_LANG']['CHMOD']['editnavigation'] = 'Hierarchie der Seiten ändern';
$GLOBALS['TL_LANG']['CHMOD']['deletepage']     = 'Seite löschen';
$GLOBALS['TL_LANG']['CHMOD']['editarticles']   = 'Artikel bearbeiten';
$GLOBALS['TL_LANG']['CHMOD']['movearticles']   = 'Hierarchie der Artikel ändern';
$GLOBALS['TL_LANG']['CHMOD']['deletearticles'] = 'Artikel löschen';
$GLOBALS['TL_LANG']['CHMOD']['cuser']          = 'Besitzer';
$GLOBALS['TL_LANG']['CHMOD']['cgroup']         = 'Gruppe';
$GLOBALS['TL_LANG']['CHMOD']['cworld']         = 'Alle';


/**
 * Day names
 */
$GLOBALS['TL_LANG']['DAYS'][0] = 'Sonntag';
$GLOBALS['TL_LANG']['DAYS'][1] = 'Montag';
$GLOBALS['TL_LANG']['DAYS'][2] = 'Dienstag';
$GLOBALS['TL_LANG']['DAYS'][3] = 'Mittwoch';
$GLOBALS['TL_LANG']['DAYS'][4] = 'Donnerstag';
$GLOBALS['TL_LANG']['DAYS'][5] = 'Freitag';
$GLOBALS['TL_LANG']['DAYS'][6] = 'Samstag';


/**
 * Month names
 */
$GLOBALS['TL_LANG']['MONTHS'][0]  = 'Januar';
$GLOBALS['TL_LANG']['MONTHS'][1]  = 'Februar';
$GLOBALS['TL_LANG']['MONTHS'][2]  = 'März';
$GLOBALS['TL_LANG']['MONTHS'][3]  = 'April';
$GLOBALS['TL_LANG']['MONTHS'][4]  = 'Mai';
$GLOBALS['TL_LANG']['MONTHS'][5]  = 'Juni';
$GLOBALS['TL_LANG']['MONTHS'][6]  = 'Juli';
$GLOBALS['TL_LANG']['MONTHS'][7]  = 'August';
$GLOBALS['TL_LANG']['MONTHS'][8]  = 'September';
$GLOBALS['TL_LANG']['MONTHS'][9]  = 'Oktober';
$GLOBALS['TL_LANG']['MONTHS'][10] = 'November';
$GLOBALS['TL_LANG']['MONTHS'][11] = 'Dezember';


/**
 * Week offset (0 = Sunday, 1 = Monday, …)
 */
$GLOBALS['TL_LANG']['MSC']['weekOffset']  = 1;
$GLOBALS['TL_LANG']['MSC']['titleFormat'] = 'l, d. F Y';


/**
 * URLs
 */
$GLOBALS['TL_LANG']['MSC']['url']    = array('Link-Adresse', 'Geben Sie eine Web-Adresse (http://…), eine E-Mail-Adresse (mailto:…) oder ein Insert-Tag ein.');
$GLOBALS['TL_LANG']['MSC']['target'] = array('In neuem Fenster öffnen', 'Den Link in einem neuen Browserfenster öffnen.');


/**
 * Number format
 */
$GLOBALS['TL_LANG']['MSC']['decimalSeparator']   = ',';
$GLOBALS['TL_LANG']['MSC']['thousandsSeparator'] = '.';


/**
 * CSV files
 */
$GLOBALS['TL_LANG']['MSC']['separator'] = array('Trennzeichen', 'Bitte wählen Sie ein Feld-Trennzeichen aus.');
$GLOBALS['TL_LANG']['MSC']['comma']     = 'Komma';
$GLOBALS['TL_LANG']['MSC']['semicolon'] = 'Strichpunkt';
$GLOBALS['TL_LANG']['MSC']['tabulator'] = 'Tabulator';
$GLOBALS['TL_LANG']['MSC']['linebreak'] = 'Zeilenumbruch';


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['id']         = array('Id', 'Das Ändern der Id kann die Konsistenz der Daten gefährden!');
$GLOBALS['TL_LANG']['MSC']['pid']        = array('Parent-Id', 'Das Ändern der Parent-Id kann die Konsistenz der Daten gefährden!');
$GLOBALS['TL_LANG']['MSC']['sorting']    = array('Sortierindex', 'Der Sortierindex wird normalerweise automatisch gesetzt.');
$GLOBALS['TL_LANG']['MSC']['all']        = array('Mehrere bearbeiten', 'Mehrere Datensätze auf einmal bearbeiten');
$GLOBALS['TL_LANG']['MSC']['all_fields'] = array('Vorhandene Felder', 'Bitte wählen Sie die Felder aus, die Sie bearbeiten möchten.');
$GLOBALS['TL_LANG']['MSC']['password']   = array('Passwort', 'Bitte geben Sie ein Passwort ein.');
$GLOBALS['TL_LANG']['MSC']['confirm']    = array('Bestätigung', 'Bitte bestätigen Sie das Passwort.');


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['feLink']         = 'Zum Frontend';
$GLOBALS['TL_LANG']['MSC']['fePreview']      = 'Frontend-Vorschau';
$GLOBALS['TL_LANG']['MSC']['feUser']         = 'Frontend-Benutzer';
$GLOBALS['TL_LANG']['MSC']['backToTop']      = 'Nach oben';
$GLOBALS['TL_LANG']['MSC']['home']           = 'Startseite';
$GLOBALS['TL_LANG']['MSC']['user']           = 'Benutzer';
$GLOBALS['TL_LANG']['MSC']['loginTo']        = 'Anmelden bei %s';
$GLOBALS['TL_LANG']['MSC']['loginBT']        = 'Anmelden';
$GLOBALS['TL_LANG']['MSC']['logoutBT']       = 'Abmelden';
$GLOBALS['TL_LANG']['MSC']['backBT']         = 'Zurück';
$GLOBALS['TL_LANG']['MSC']['cancelBT']       = 'Abbrechen';
$GLOBALS['TL_LANG']['MSC']['deleteConfirm']  = 'Soll der Eintrag ID %s wirklich gelöscht werden?';
$GLOBALS['TL_LANG']['MSC']['delAllConfirm']  = 'Wollen Sie die ausgewählten Einträge wirklich löschen?';
$GLOBALS['TL_LANG']['MSC']['filterRecords']  = 'Datensätze';
$GLOBALS['TL_LANG']['MSC']['filterAll']      = 'Alle';
$GLOBALS['TL_LANG']['MSC']['showRecord']     = 'Details des Datensatzes %s anzeigen';
$GLOBALS['TL_LANG']['MSC']['editRecord']     = 'Datensatz %s bearbeiten';
$GLOBALS['TL_LANG']['MSC']['all_info']       = 'Ausgewählte Datensätze der Tabelle %s bearbeiten';
$GLOBALS['TL_LANG']['MSC']['showOnly']       = 'Anzeigen';
$GLOBALS['TL_LANG']['MSC']['sortBy']         = 'Sortieren';
$GLOBALS['TL_LANG']['MSC']['filter']         = 'Filtern';
$GLOBALS['TL_LANG']['MSC']['search']         = 'Suchen';
$GLOBALS['TL_LANG']['MSC']['noResult']       = 'Keine Einträge gefunden.';
$GLOBALS['TL_LANG']['MSC']['save']           = 'Speichern';
$GLOBALS['TL_LANG']['MSC']['saveNclose']     = 'Speichern und schließen';
$GLOBALS['TL_LANG']['MSC']['saveNedit']      = 'Speichern und bearbeiten';
$GLOBALS['TL_LANG']['MSC']['saveNback']      = 'Speichern und zurück';
$GLOBALS['TL_LANG']['MSC']['saveNcreate']    = 'Speichern und neu';
$GLOBALS['TL_LANG']['MSC']['continue']       = 'Weiter';
$GLOBALS['TL_LANG']['MSC']['skipNavigation'] = 'Navigation überspringen';
$GLOBALS['TL_LANG']['MSC']['selectAll']      = 'Alle auswählen';
$GLOBALS['TL_LANG']['MSC']['pw_changed']     = 'Das Passwort wurde aktualisiert.';
$GLOBALS['TL_LANG']['MSC']['fallback']       = 'Standard';
$GLOBALS['TL_LANG']['MSC']['view']           = 'In einem neuen Fenster ansehen';
$GLOBALS['TL_LANG']['MSC']['fullsize']       = 'Großansicht des Bildes in einem neuen Fenster öffnen';
$GLOBALS['TL_LANG']['MSC']['colorpicker']    = 'Farbwähler (benötigt JavaScript)';
$GLOBALS['TL_LANG']['MSC']['pagepicker']     = 'Seitenwähler (benötigt JavaScript)';
$GLOBALS['TL_LANG']['MSC']['filepicker']     = 'Dateiwähler (benötigt JavaScript)';
$GLOBALS['TL_LANG']['MSC']['ppHeadline']     = 'TYPOlight-Seiten';
$GLOBALS['TL_LANG']['MSC']['fpHeadline']     = 'TYPOlight-Dateien';
$GLOBALS['TL_LANG']['MSC']['yes']            = 'ja';
$GLOBALS['TL_LANG']['MSC']['no']             = 'nein';
$GLOBALS['TL_LANG']['MSC']['goBack']         = 'Zurück';
$GLOBALS['TL_LANG']['MSC']['reload']         = 'Neu laden';
$GLOBALS['TL_LANG']['MSC']['above']          = 'oberhalb';
$GLOBALS['TL_LANG']['MSC']['below']          = 'unterhalb';
$GLOBALS['TL_LANG']['MSC']['date']           = 'Datum';
$GLOBALS['TL_LANG']['MSC']['tstamp']         = 'Änderungsdatum';
$GLOBALS['TL_LANG']['MSC']['entry']          = '%s Eintrag';
$GLOBALS['TL_LANG']['MSC']['entries']        = '%s Einträge';
$GLOBALS['TL_LANG']['MSC']['left']           = 'linksbündig';
$GLOBALS['TL_LANG']['MSC']['center']         = 'zentriert';
$GLOBALS['TL_LANG']['MSC']['right']          = 'rechtsbündig';
$GLOBALS['TL_LANG']['MSC']['justify']        = 'Blocksatz';
$GLOBALS['TL_LANG']['MSC']['filetree']       = 'Dateisystem';
$GLOBALS['TL_LANG']['MSC']['male']           = 'Männlich';
$GLOBALS['TL_LANG']['MSC']['female']         = 'Weiblich';
$GLOBALS['TL_LANG']['MSC']['fileSize']       = 'Dateigröße';
$GLOBALS['TL_LANG']['MSC']['fileCreated']    = 'Erstellt am';
$GLOBALS['TL_LANG']['MSC']['fileModified']   = 'Zuletzt geändert am';
$GLOBALS['TL_LANG']['MSC']['fileAccessed']   = 'Letzter Zugriff am';
$GLOBALS['TL_LANG']['MSC']['fileDownload']   = 'Herunterladen';
$GLOBALS['TL_LANG']['MSC']['fileImageSize']  = 'Breite/Höhe in Pixeln';
$GLOBALS['TL_LANG']['MSC']['filePath']       = 'Relativer Pfad';
$GLOBALS['TL_LANG']['MSC']['version']        = 'Version';
$GLOBALS['TL_LANG']['MSC']['restore']        = 'Wiederherstellen';
$GLOBALS['TL_LANG']['MSC']['backendModules'] = 'Backend-Module';
$GLOBALS['TL_LANG']['MSC']['welcomeTo']      = '%s Backend';
$GLOBALS['TL_LANG']['MSC']['updateVersion']  = 'TYPOlight Version %s verfügbar';
$GLOBALS['TL_LANG']['MSC']['wordWrap']       = 'Zeilenumbruch';
$GLOBALS['TL_LANG']['MSC']['saveAlert']      = 'ACHTUNG! Nicht gespeicherte Änderungen gehen verloren. Fortfahren?';
$GLOBALS['TL_LANG']['MSC']['toggleNodes']    = 'Alle öffnen/schließen';
$GLOBALS['TL_LANG']['MSC']['expandNode']     = 'Bereich öffnen';
$GLOBALS['TL_LANG']['MSC']['collapseNode']   = 'Bereich schließen';
$GLOBALS['TL_LANG']['MSC']['deleteSelected'] = 'Auswahl löschen';
$GLOBALS['TL_LANG']['MSC']['editSelected']   = 'Auswahl bearbeiten';
$GLOBALS['TL_LANG']['MSC']['changeSelected'] = 'Auswahl ändern';
$GLOBALS['TL_LANG']['MSC']['resetSelected']  = 'Auswahl aufheben';
$GLOBALS['TL_LANG']['MSC']['fileManager']    = 'Dateimanager in einem Popup-Fenster öffnen';
$GLOBALS['TL_LANG']['MSC']['systemMessages'] = 'Systemnachrichten';
$GLOBALS['TL_LANG']['MSC']['tasksCur']       = '%s offene Aufgabe(n)';
$GLOBALS['TL_LANG']['MSC']['tasksNew']       = '%s neue Aufgabe(n)';
$GLOBALS['TL_LANG']['MSC']['tasksDue']       = '%s überfällige Aufgabe(n)';
$GLOBALS['TL_LANG']['MSC']['clearClipboard'] = 'Ablage leeren';
$GLOBALS['TL_LANG']['MSC']['hiddenElements'] = 'Unveröffentlichte Elemente';
$GLOBALS['TL_LANG']['MSC']['hiddenHide']     = 'verstecken';
$GLOBALS['TL_LANG']['MSC']['hiddenShow']     = 'anzeigen';
$GLOBALS['TL_LANG']['MSC']['apply']          = 'Anwenden';

?>