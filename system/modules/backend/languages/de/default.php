<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Error messages
 */
$GLOBALS['TL_LANG']['ERR']['general']           = 'Ein Fehler ist aufgetreten!';
$GLOBALS['TL_LANG']['ERR']['unique']            = 'Doppelter Eintrag im Feld "%s"!';
$GLOBALS['TL_LANG']['ERR']['mandatory']         = 'Bitte füllen Sie das Feld "%s" aus!';
$GLOBALS['TL_LANG']['ERR']['mdtryNoLabel']      = 'Bitte füllen Sie dieses Feld aus!';
$GLOBALS['TL_LANG']['ERR']['minlength']         = 'Das Feld "%s" muss mindestens %d Zeichen lang sein!';
$GLOBALS['TL_LANG']['ERR']['maxlength']         = 'Das Feld "%s" darf höchstens %d Zeichen lang sein!';
$GLOBALS['TL_LANG']['ERR']['digit']             = 'Bitte geben Sie nur Zahlen ein!';
$GLOBALS['TL_LANG']['ERR']['prcnt']             = 'Bitte geben Sie einen Prozentsatz zwischen 0 und 100 ein!';
$GLOBALS['TL_LANG']['ERR']['alpha']             = 'Bitte geben Sie nur Buchstaben ein!';
$GLOBALS['TL_LANG']['ERR']['alnum']             = 'Bitte geben Sie nur Buchstaben und Zahlen ein!';
$GLOBALS['TL_LANG']['ERR']['phone']             = 'Bitte geben Sie eine gültige Telefonnummer ein!';
$GLOBALS['TL_LANG']['ERR']['extnd']             = 'Aus Sicherheitsgründen können Sie folgende Zeichen hier nicht verwenden: =<>&/()#';
$GLOBALS['TL_LANG']['ERR']['email']             = 'Bitte geben Sie eine gültige E-Mail-Adresse ein!';
$GLOBALS['TL_LANG']['ERR']['emails']            = 'Mindestens eine der E-Mail-Adressen ist ungültig!';
$GLOBALS['TL_LANG']['ERR']['url']               = 'Bitte geben Sie ein gültiges URL-Format ein und kodieren Sie Sonderzeichen!';
$GLOBALS['TL_LANG']['ERR']['date']              = 'Bitte geben Sie das Datum im Format "%s" ein!';
$GLOBALS['TL_LANG']['ERR']['time']              = 'Bitte geben Sie die Uhrzeit im Format "%s" ein!';
$GLOBALS['TL_LANG']['ERR']['dateTime']          = 'Bitte geben Sie Datum und Uhrzeit im Format "%s" ein!';
$GLOBALS['TL_LANG']['ERR']['invalidDate']       = 'Ungültiges Datum "%s"!';
$GLOBALS['TL_LANG']['ERR']['noSpace']           = 'Das Feld "%s" darf keine Leerzeichen enthalten!';
$GLOBALS['TL_LANG']['ERR']['filesize']          = 'Die maximale Größe für Datei-Uploads beträgt %s (Contao- oder php.ini-Einstellungen)!';
$GLOBALS['TL_LANG']['ERR']['filetype']          = 'Der Dateityp "%s" darf nicht hochgeladen werden!';
$GLOBALS['TL_LANG']['ERR']['filepartial']       = 'Die Datei %s wurde nur teilweise hochgeladen!';
$GLOBALS['TL_LANG']['ERR']['filewidth']         = 'Die Datei %s übersteigt die maximale Bildbreite von %d Pixel!';
$GLOBALS['TL_LANG']['ERR']['fileheight']        = 'Die Datei %s übersteigt die maximale Bildhöhe von %d Pixel!';
$GLOBALS['TL_LANG']['ERR']['invalidReferer']    = 'Zugriff verweigert! Die aktuelle Hostadresse (%s) stimmt nicht mit der verweisenden Hostadresse (%s) überein!';
$GLOBALS['TL_LANG']['ERR']['invalidPass']       = 'Ungültiges Passwort!';
$GLOBALS['TL_LANG']['ERR']['passwordLength']    = 'Ein Passwort muss mindestens %d Zeichen lang sein!';
$GLOBALS['TL_LANG']['ERR']['passwordName']      = 'Der Benutzername und das Passwort müssen unterschiedlich sein!';
$GLOBALS['TL_LANG']['ERR']['passwordMatch']     = 'Die eingegebenen Passwörter stimmen nicht überein!';
$GLOBALS['TL_LANG']['ERR']['accountLocked']     = 'Das Konto wurde gesperrt! Sie können sich in %d Minuten erneut anmelden.';
$GLOBALS['TL_LANG']['ERR']['invalidLogin']      = 'Anmeldung fehlgeschlagen!';
$GLOBALS['TL_LANG']['ERR']['invalidColor']      = 'Ungültiges Farbformat!';
$GLOBALS['TL_LANG']['ERR']['all_fields']        = 'Bitte wählen Sie mindestens ein Feld aus!';
$GLOBALS['TL_LANG']['ERR']['aliasExists']       = 'Der Alias "%s" existiert bereits!';
$GLOBALS['TL_LANG']['ERR']['importFolder']      = 'Der Ordner "%s" kann nicht importiert werden!';
$GLOBALS['TL_LANG']['ERR']['notWriteable']      = 'Die Datei "%s" ist nicht beschreibbar und kann daher nicht aktualisiert werden!';
$GLOBALS['TL_LANG']['ERR']['invalidName']       = 'Dieser Datei- bzw. Verzeichnisname ist ungültig!';
$GLOBALS['TL_LANG']['ERR']['invalidFile']       = 'Die Datei bzw. das Verzeichnis "%s" ist ungültig!';
$GLOBALS['TL_LANG']['ERR']['fileExists']        = 'Die Datei "%s" existiert bereits!';
$GLOBALS['TL_LANG']['ERR']['circularReference'] = 'Ungültiges Weiterleitungsziel (Zirkelbezug)!';
$GLOBALS['TL_LANG']['ERR']['ie6warning']        = '<strong>Achtung!</strong> Sie verwenden einen %sveralteten Browser%s und <strong>können nicht alle Funktionen dieser Webseite nutzen</strong>.';
$GLOBALS['TL_LANG']['ERR']['noFallbackEmpty']   = 'Bei keinem der aktiven Website-Startpunkte ohne explizite DNS-Angabe wurde die Option "Sprachen-Fallback" ausgewählt, d.h. diese Webseiten sind nur in der einen Sprache verfügbar, die in den Seiteneinstellungen definiert wurde! Besucher und Suchmaschinen, die diese Sprache nicht sprechen, können die Webseite nicht aufrufen.';
$GLOBALS['TL_LANG']['ERR']['noFallbackDns']     = 'Bei keinem der aktiven Website-Startpunkte für <strong>%s</strong> wurde die Option "Sprachen-Fallback" ausgewählt, d.h. diese Webseiten sind nur in der einen Sprache verfügbar, die in den Seiteneinstellungen definiert wurde! Besucher und Suchmaschinen, die diese Sprache nicht sprechen, können die Webseite nicht aufrufen.';
$GLOBALS['TL_LANG']['ERR']['multipleFallback']  = 'Sie können nur einen Website-Startpunkt pro Domain als Sprachen-Fallback definieren.';
$GLOBALS['TL_LANG']['ERR']['topLevelRoot']      = 'Seiten in der obersten Ebene müssen Website-Startpunkte sein!';
$GLOBALS['TL_LANG']['ERR']['topLevelRegular']   = 'Auf der obersten Ebene befinden sich Seiten, die keine Website-Startpunkte sind. Webseiten ohne Startpunkt werden nicht mehr unterstützt, daher stellen Sie bitte sicher, dass alle Seiten unter einem Startpunkt gruppiert sind.';


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
 * Short day names
 */
$GLOBALS['TL_LANG']['DAYS_SHORT'][0] = 'So';
$GLOBALS['TL_LANG']['DAYS_SHORT'][1] = 'Mo';
$GLOBALS['TL_LANG']['DAYS_SHORT'][2] = 'Di';
$GLOBALS['TL_LANG']['DAYS_SHORT'][3] = 'Mi';
$GLOBALS['TL_LANG']['DAYS_SHORT'][4] = 'Do';
$GLOBALS['TL_LANG']['DAYS_SHORT'][5] = 'Fr';
$GLOBALS['TL_LANG']['DAYS_SHORT'][6] = 'Sa';


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
 * Short month names
 */
$GLOBALS['TL_LANG']['MONTHS_SHORT'][0]  = 'Jan';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][1]  = 'Feb';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][2]  = 'Mär';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][3]  = 'Apr';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][4]  = 'Mai';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][5]  = 'Jun';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][6]  = 'Jul';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][7]  = 'Aug';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][8]  = 'Sep';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][9]  = 'Okt';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][10] = 'Nov';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][11] = 'Dez';


/**
 * Short names length
 */
$GLOBALS['TL_LANG']['MSC']['dayShortLength']   = 2;
$GLOBALS['TL_LANG']['MSC']['monthShortLength'] = 3;


/**
 * Week offset (0 = Sunday, 1 = Monday, …)
 */
$GLOBALS['TL_LANG']['MSC']['weekOffset']  = 1;
$GLOBALS['TL_LANG']['MSC']['titleFormat'] = '%d. %B %Y';


/**
 * URLs
 */
$GLOBALS['TL_LANG']['MSC']['url']    = array('Link-Adresse', 'Geben Sie eine Web-Adresse (http://…), eine E-Mail-Adresse (mailto:…) oder ein Inserttag ein.');
$GLOBALS['TL_LANG']['MSC']['target'] = array('In neuem Fenster öffnen', 'Den Link in einem neuen Browserfenster öffnen.');


/**
 * Number format
 */
$GLOBALS['TL_LANG']['MSC']['decimalSeparator']   = ',';
$GLOBALS['TL_LANG']['MSC']['thousandsSeparator'] = '.';


/**
 * Units
 */
$GLOBALS['TL_LANG']['UNITS'][0] = 'Byte';
$GLOBALS['TL_LANG']['UNITS'][1] = 'KiB';
$GLOBALS['TL_LANG']['UNITS'][2] = 'MiB';
$GLOBALS['TL_LANG']['UNITS'][3] = 'GiB';
$GLOBALS['TL_LANG']['UNITS'][4] = 'TiB';
$GLOBALS['TL_LANG']['UNITS'][5] = 'PiB';
$GLOBALS['TL_LANG']['UNITS'][6] = 'EiB';
$GLOBALS['TL_LANG']['UNITS'][7] = 'ZiB';
$GLOBALS['TL_LANG']['UNITS'][8] = 'YiB';


/**
 * Datepicker
 */
$GLOBALS['TL_LANG']['DP']['select_a_time']       = 'Uhrzeit auswählen';
$GLOBALS['TL_LANG']['DP']['use_mouse_wheel']     = 'Ändern Sie die Werte mit Hilfe des Mausrads';
$GLOBALS['TL_LANG']['DP']['time_confirm_button'] = 'OK';
$GLOBALS['TL_LANG']['DP']['apply_range']         = 'Anwenden';
$GLOBALS['TL_LANG']['DP']['cancel']              = 'Abbrechen';
$GLOBALS['TL_LANG']['DP']['week']                = 'W';


/**
 * CSV files
 */
$GLOBALS['TL_LANG']['MSC']['separator'] = array('Trennzeichen', 'Bitte wählen Sie ein Feld-Trennzeichen aus.');
$GLOBALS['TL_LANG']['MSC']['source']    = array('Quelldateien', 'Bitte wählen Sie die zu importierenden CSV-Dateien aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['MSC']['comma']     = 'Komma';
$GLOBALS['TL_LANG']['MSC']['semicolon'] = 'Strichpunkt';
$GLOBALS['TL_LANG']['MSC']['tabulator'] = 'Tabulator';
$GLOBALS['TL_LANG']['MSC']['linebreak'] = 'Zeilenumbruch';


/**
 * List wizard
 */
$GLOBALS['TL_LANG']['MSC']['lw_import'] = array('CSV-Import', 'Listeneinträge aus einer CSV-Datei importieren');
$GLOBALS['TL_LANG']['MSC']['lw_copy']   = 'Das Element duplizieren';
$GLOBALS['TL_LANG']['MSC']['lw_up']     = 'Das Element eine Position nach oben verschieben';
$GLOBALS['TL_LANG']['MSC']['lw_down']   = 'Das Element eine Position nach unten verschieben';
$GLOBALS['TL_LANG']['MSC']['lw_delete'] = 'Das Element löschen';


/**
 * Table wizard
 */
$GLOBALS['TL_LANG']['MSC']['tw_import']  = array('CSV-Import', 'Tabelleneinträge aus einer CSV-Datei importieren');
$GLOBALS['TL_LANG']['MSC']['tw_expand']  = 'Die Eingabefelder vergrößern';
$GLOBALS['TL_LANG']['MSC']['tw_shrink']  = 'Die Eingabefelder verkleinern';
$GLOBALS['TL_LANG']['MSC']['tw_rcopy']   = 'Die Reihe duplizieren';
$GLOBALS['TL_LANG']['MSC']['tw_rup']     = 'Die Reihe eine Position nach oben verschieben';
$GLOBALS['TL_LANG']['MSC']['tw_rdown']   = 'Die Reihe eine Position nach unten verschieben';
$GLOBALS['TL_LANG']['MSC']['tw_rdelete'] = 'Die Reihe löschen';
$GLOBALS['TL_LANG']['MSC']['tw_ccopy']   = 'Die Spalte duplizieren';
$GLOBALS['TL_LANG']['MSC']['tw_cmovel']  = 'Die Spalte eine Position nach links verschieben';
$GLOBALS['TL_LANG']['MSC']['tw_cmover']  = 'Die Spalte eine Position nach rechts verschieben';
$GLOBALS['TL_LANG']['MSC']['tw_cdelete'] = 'Die Spalte löschen';


/**
 * Option wizard
 */
$GLOBALS['TL_LANG']['MSC']['ow_copy']    = 'Die Reihe duplizieren';
$GLOBALS['TL_LANG']['MSC']['ow_up']      = 'Die Reihe eine Position nach oben verschieben';
$GLOBALS['TL_LANG']['MSC']['ow_down']    = 'Die Reihe eine Position nach unten verschieben';
$GLOBALS['TL_LANG']['MSC']['ow_delete']  = 'Die Reihe löschen';
$GLOBALS['TL_LANG']['MSC']['ow_value']   = 'Wert';
$GLOBALS['TL_LANG']['MSC']['ow_label']   = 'Bezeichnung';
$GLOBALS['TL_LANG']['MSC']['ow_key']     = 'Schlüssel';
$GLOBALS['TL_LANG']['MSC']['ow_default'] = 'Standard';
$GLOBALS['TL_LANG']['MSC']['ow_group']   = 'Gruppe';


/**
 * Module wizard
 */
$GLOBALS['TL_LANG']['MSC']['mw_copy']   = 'Die Reihe duplizieren';
$GLOBALS['TL_LANG']['MSC']['mw_up']     = 'Die Reihe eine Position nach oben verschieben';
$GLOBALS['TL_LANG']['MSC']['mw_down']   = 'Die Reihe eine Position nach unten verschieben';
$GLOBALS['TL_LANG']['MSC']['mw_delete'] = 'Die Reihe löschen';
$GLOBALS['TL_LANG']['MSC']['mw_module'] = 'Modul';
$GLOBALS['TL_LANG']['MSC']['mw_column'] = 'Spalte';


/**
 * Images
 */
$GLOBALS['TL_LANG']['MSC']['relative']      = 'Relatives Format';
$GLOBALS['TL_LANG']['MSC']['proportional']  = array('Proportional', 'Die längere Seite des Bildes wird an die vorgegebenen Abmessungen angepasst und das Bild proportional verkleinert.');
$GLOBALS['TL_LANG']['MSC']['box']           = array('An Rahmen anpassen', 'Die kürzere Seite des Bildes wird an die vorgegebenen Abmessungen angepasst und das Bild proportional verkleinert.');
$GLOBALS['TL_LANG']['MSC']['crop']          = 'Exaktes Format';
$GLOBALS['TL_LANG']['MSC']['left_top']      = array('Links oben', 'Erhält den linken Teil eines Querformat-Bildes und den oberen Teil eines Hochformat-Bildes.');
$GLOBALS['TL_LANG']['MSC']['center_top']    = array('Mitte oben', 'Erhält den mittleren Teil eines Querformat-Bildes und den oberen Teil eines Hochformat-Bildes.');
$GLOBALS['TL_LANG']['MSC']['right_top']     = array('Rechts oben', 'Erhält den rechten Teil eines Querformat-Bildes und den oberen Teil eines Hochformat-Bildes.');
$GLOBALS['TL_LANG']['MSC']['left_center']   = array('Links Mitte', 'Erhält den linken Teil eines Querformat-Bildes und den mittleren Teil eines Hochformat-Bildes.');
$GLOBALS['TL_LANG']['MSC']['center_center'] = array('Mitte Mitte', 'Erhält den mittleren Teil eines Querformat-Bildes und den mittleren Teil eines Hochformat-Bildes.');
$GLOBALS['TL_LANG']['MSC']['right_center']  = array('Rechts Mitte', 'Erhält den rechten Teil eines Querformat-Bildes und den mittleren Teil eines Hochformat-Bildes.');
$GLOBALS['TL_LANG']['MSC']['left_bottom']   = array('Links unten', 'Erhält den linken Teil eines Querformat-Bildes und den unteren Teil eines Hochformat-Bildes.');
$GLOBALS['TL_LANG']['MSC']['center_bottom'] = array('Mitte unten', 'Erhält den mittleren Teil eines Querformat-Bildes und den unteren Teil eines Hochformat-Bildes.');
$GLOBALS['TL_LANG']['MSC']['right_bottom']  = array('Rechts unten', 'Erhält den rechten Teil eines Querformat-Bildes und den unteren Teil eines Hochformat-Bildes.');


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['id']            = array('ID', 'Das Ändern der ID kann die Konsistenz der Daten gefährden!');
$GLOBALS['TL_LANG']['MSC']['pid']           = array('Parent-ID', 'Das Ändern der Parent-ID kann die Konsistenz der Daten gefährden!');
$GLOBALS['TL_LANG']['MSC']['sorting']       = array('Sortierindex', 'Der Sortierindex wird normalerweise automatisch gesetzt.');
$GLOBALS['TL_LANG']['MSC']['all']           = array('Mehrere bearbeiten', 'Mehrere Datensätze auf einmal bearbeiten');
$GLOBALS['TL_LANG']['MSC']['all_fields']    = array('Vorhandene Felder', 'Bitte wählen Sie die Felder aus, die Sie bearbeiten möchten.');
$GLOBALS['TL_LANG']['MSC']['password']      = array('Passwort', 'Bitte geben Sie ein Passwort ein.');
$GLOBALS['TL_LANG']['MSC']['confirm']       = array('Bestätigung', 'Bitte bestätigen Sie das Passwort.');
$GLOBALS['TL_LANG']['MSC']['dateAdded']     = array('Hinzugefügt am', 'Hinzugefügt am: %s');
$GLOBALS['TL_LANG']['MSC']['lastLogin']     = array('Letzte Anmeldung', 'Letzte Anmeldung: %s');
$GLOBALS['TL_LANG']['MSC']['move_up']       = array('Nach oben', 'Den Eintrag eine Position nach oben verschieben');
$GLOBALS['TL_LANG']['MSC']['move_down']     = array('Nach unten', 'Den Eintrag eine Position nach unten verschieben');
$GLOBALS['TL_LANG']['MSC']['staticFiles']   = array('Datei-URL', 'Die Datei-URL gilt für das <em>tl_files</em>-Verzeichnis sowie alle Vorschaubilder (Page-Speed-Optimierung).');
$GLOBALS['TL_LANG']['MSC']['staticSystem']  = array('Skript-URL', 'Die Skript-URL gilt für alle JavaScript- und CSS-Dateien inklusive eingebundener Hintergrundbilder (Page-Speed-Optimierung).');
$GLOBALS['TL_LANG']['MSC']['staticPlugins'] = array('Plugins-URL', 'Die Plugins-URL gilt für alle Ressourcen im <em>plugins</em>-Verzeichnis (Page-Speed-Optimierung).');
$GLOBALS['TL_LANG']['MSC']['shortcuts']     = array('Backend-Tastaturkürzel', 'Wie Sie Ihren Arbeitsablauf durch die Verwendung von <a href="https://contao.org/de/manual/2.11/administration-area.html#backend-tastaturk%C3%BCrzel" target="_blank">Tastaturkürzeln</a> beschleunigen.');


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['feLink']           = 'Zum Frontend';
$GLOBALS['TL_LANG']['MSC']['fePreview']        = 'Frontend-Vorschau';
$GLOBALS['TL_LANG']['MSC']['feUser']           = 'Frontend-Benutzer';
$GLOBALS['TL_LANG']['MSC']['backToTop']        = 'Nach oben';
$GLOBALS['TL_LANG']['MSC']['home']             = 'Startseite';
$GLOBALS['TL_LANG']['MSC']['user']             = 'Benutzer';
$GLOBALS['TL_LANG']['MSC']['loginTo']          = 'Anmelden bei %s';
$GLOBALS['TL_LANG']['MSC']['loginBT']          = 'Anmelden';
$GLOBALS['TL_LANG']['MSC']['logoutBT']         = 'Abmelden';
$GLOBALS['TL_LANG']['MSC']['backBT']           = 'Zurück';
$GLOBALS['TL_LANG']['MSC']['cancelBT']         = 'Abbrechen';
$GLOBALS['TL_LANG']['MSC']['deleteConfirm']    = 'Soll der Eintrag ID %s wirklich gelöscht werden?';
$GLOBALS['TL_LANG']['MSC']['delAllConfirm']    = 'Wollen Sie die ausgewählten Einträge wirklich löschen?';
$GLOBALS['TL_LANG']['MSC']['filterRecords']    = 'Datensätze';
$GLOBALS['TL_LANG']['MSC']['filterAll']        = 'Alle';
$GLOBALS['TL_LANG']['MSC']['showRecord']       = 'Details des Datensatzes %s anzeigen';
$GLOBALS['TL_LANG']['MSC']['editRecord']       = 'Datensatz %s bearbeiten';
$GLOBALS['TL_LANG']['MSC']['all_info']         = 'Ausgewählte Datensätze der Tabelle %s bearbeiten';
$GLOBALS['TL_LANG']['MSC']['showOnly']         = 'Anzeigen';
$GLOBALS['TL_LANG']['MSC']['sortBy']           = 'Sortieren';
$GLOBALS['TL_LANG']['MSC']['filter']           = 'Filtern';
$GLOBALS['TL_LANG']['MSC']['search']           = 'Suchen';
$GLOBALS['TL_LANG']['MSC']['noResult']         = 'Keine Einträge gefunden.';
$GLOBALS['TL_LANG']['MSC']['save']             = 'Speichern';
$GLOBALS['TL_LANG']['MSC']['saveNclose']       = 'Speichern und schließen';
$GLOBALS['TL_LANG']['MSC']['saveNedit']        = 'Speichern und bearbeiten';
$GLOBALS['TL_LANG']['MSC']['saveNback']        = 'Speichern und zurück';
$GLOBALS['TL_LANG']['MSC']['saveNcreate']      = 'Speichern und neu';
$GLOBALS['TL_LANG']['MSC']['continue']         = 'Weiter';
$GLOBALS['TL_LANG']['MSC']['skipNavigation']   = 'Navigation überspringen';
$GLOBALS['TL_LANG']['MSC']['selectAll']        = 'Alle auswählen';
$GLOBALS['TL_LANG']['MSC']['pw_change']        = 'Bitte geben Sie ein neues Passwort ein';
$GLOBALS['TL_LANG']['MSC']['pw_changed']       = 'Das Passwort wurde aktualisiert.';
$GLOBALS['TL_LANG']['MSC']['fallback']         = 'Standard';
$GLOBALS['TL_LANG']['MSC']['view']             = 'In einem neuen Fenster ansehen';
$GLOBALS['TL_LANG']['MSC']['fullsize']         = 'Großansicht des Bildes in einem neuen Fenster öffnen';
$GLOBALS['TL_LANG']['MSC']['colorpicker']      = 'Farbwähler (benötigt JavaScript)';
$GLOBALS['TL_LANG']['MSC']['pagepicker']       = 'Seitenwähler (benötigt JavaScript)';
$GLOBALS['TL_LANG']['MSC']['filepicker']       = 'Dateiwähler (benötigt JavaScript)';
$GLOBALS['TL_LANG']['MSC']['ppHeadline']       = 'Contao-Seiten';
$GLOBALS['TL_LANG']['MSC']['fpHeadline']       = 'Contao-Dateien';
$GLOBALS['TL_LANG']['MSC']['yes']              = 'ja';
$GLOBALS['TL_LANG']['MSC']['no']               = 'nein';
$GLOBALS['TL_LANG']['MSC']['goBack']           = 'Zurück';
$GLOBALS['TL_LANG']['MSC']['reload']           = 'Neu laden';
$GLOBALS['TL_LANG']['MSC']['above']            = 'oberhalb';
$GLOBALS['TL_LANG']['MSC']['below']            = 'unterhalb';
$GLOBALS['TL_LANG']['MSC']['date']             = 'Datum';
$GLOBALS['TL_LANG']['MSC']['tstamp']           = 'Änderungsdatum';
$GLOBALS['TL_LANG']['MSC']['entry']            = '%s Eintrag';
$GLOBALS['TL_LANG']['MSC']['entries']          = '%s Einträge';
$GLOBALS['TL_LANG']['MSC']['left']             = 'linksbündig';
$GLOBALS['TL_LANG']['MSC']['center']           = 'zentriert';
$GLOBALS['TL_LANG']['MSC']['right']            = 'rechtsbündig';
$GLOBALS['TL_LANG']['MSC']['justify']          = 'Blocksatz';
$GLOBALS['TL_LANG']['MSC']['filetree']         = 'Dateisystem';
$GLOBALS['TL_LANG']['MSC']['male']             = 'Männlich';
$GLOBALS['TL_LANG']['MSC']['female']           = 'Weiblich';
$GLOBALS['TL_LANG']['MSC']['fileSize']         = 'Dateigröße';
$GLOBALS['TL_LANG']['MSC']['fileCreated']      = 'Erstellt am';
$GLOBALS['TL_LANG']['MSC']['fileModified']     = 'Zuletzt geändert am';
$GLOBALS['TL_LANG']['MSC']['fileAccessed']     = 'Letzter Zugriff am';
$GLOBALS['TL_LANG']['MSC']['fileDownload']     = 'Herunterladen';
$GLOBALS['TL_LANG']['MSC']['fileImageSize']    = 'Breite/Höhe in Pixeln';
$GLOBALS['TL_LANG']['MSC']['filePath']         = 'Relativer Pfad';
$GLOBALS['TL_LANG']['MSC']['version']          = 'Version';
$GLOBALS['TL_LANG']['MSC']['restore']          = 'Wiederherstellen';
$GLOBALS['TL_LANG']['MSC']['backendModules']   = 'Backend-Module';
$GLOBALS['TL_LANG']['MSC']['welcomeTo']        = '%s Backend';
$GLOBALS['TL_LANG']['MSC']['updateVersion']    = 'Contao Version %s verfügbar';
$GLOBALS['TL_LANG']['MSC']['wordWrap']         = 'Zeilenumbruch';
$GLOBALS['TL_LANG']['MSC']['saveAlert']        = 'ACHTUNG! Nicht gespeicherte Änderungen gehen verloren. Fortfahren?';
$GLOBALS['TL_LANG']['MSC']['toggleNodes']      = 'Alle öffnen/schließen';
$GLOBALS['TL_LANG']['MSC']['expandNode']       = 'Bereich öffnen';
$GLOBALS['TL_LANG']['MSC']['collapseNode']     = 'Bereich schließen';
$GLOBALS['TL_LANG']['MSC']['loadingData']      = 'Die Daten werden geladen';
$GLOBALS['TL_LANG']['MSC']['deleteSelected']   = 'Löschen';
$GLOBALS['TL_LANG']['MSC']['editSelected']     = 'Bearbeiten';
$GLOBALS['TL_LANG']['MSC']['overrideSelected'] = 'Überschreiben';
$GLOBALS['TL_LANG']['MSC']['moveSelected']     = 'Verschieben';
$GLOBALS['TL_LANG']['MSC']['copySelected']     = 'Kopieren';
$GLOBALS['TL_LANG']['MSC']['changeSelected']   = 'Auswahl ändern';
$GLOBALS['TL_LANG']['MSC']['resetSelected']    = 'Auswahl aufheben';
$GLOBALS['TL_LANG']['MSC']['fileManager']      = 'Dateimanager in einem Popup-Fenster öffnen';
$GLOBALS['TL_LANG']['MSC']['systemMessages']   = 'Systemnachrichten';
$GLOBALS['TL_LANG']['MSC']['clearClipboard']   = 'Ablage leeren';
$GLOBALS['TL_LANG']['MSC']['hiddenElements']   = 'Unveröffentlichte Elemente';
$GLOBALS['TL_LANG']['MSC']['hiddenHide']       = 'verstecken';
$GLOBALS['TL_LANG']['MSC']['hiddenShow']       = 'anzeigen';
$GLOBALS['TL_LANG']['MSC']['apply']            = 'Anwenden';
$GLOBALS['TL_LANG']['MSC']['mandatory']        = 'Pflichtfeld';
$GLOBALS['TL_LANG']['MSC']['create']           = 'Anlegen';
$GLOBALS['TL_LANG']['MSC']['delete']           = 'Löschen';
$GLOBALS['TL_LANG']['MSC']['protected']        = 'geschützt';
$GLOBALS['TL_LANG']['MSC']['guests']           = 'nur Gäste';
$GLOBALS['TL_LANG']['MSC']['updateMode']       = 'Update-Modus';
$GLOBALS['TL_LANG']['MSC']['updateAdd']        = 'Ausgewählte Werte hinzufügen';
$GLOBALS['TL_LANG']['MSC']['updateRemove']     = 'Ausgewählte Werte entfernen';
$GLOBALS['TL_LANG']['MSC']['updateReplace']    = 'Bestehende Einträge überschreiben';
$GLOBALS['TL_LANG']['MSC']['ascending']        = 'aufsteigend';
$GLOBALS['TL_LANG']['MSC']['descending']       = 'absteigend';
$GLOBALS['TL_LANG']['MSC']['default']          = 'Standard';
$GLOBALS['TL_LANG']['MSC']['helpWizard']       = 'Hilfe-Assistent';
$GLOBALS['TL_LANG']['MSC']['noCookies']        = 'Für die Nutzung von Contao müssen Cookies erlaubt sein.';
$GLOBALS['TL_LANG']['MSC']['copyOf']           = '%s (Kopie)';
$GLOBALS['TL_LANG']['MSC']['coreOnlyMode']     = 'Contao befindet sich momentan im <strong>abgesicherten Modus</strong>, in dem nur Core-Module geladen werden. Dieser Modus ist z.B. nach einem Live Update aktiv, um eventuellen Fehlern durch inkompatible Third-Party-Erweiterungen vorzubeugen. Sie können den Betriebsmodus nach der Prüfung der installierten Third-Party-Erweiterungen in den Backend-Einstellungen anpassen.';
$GLOBALS['TL_LANG']['MSC']['emailAddress']     = 'E-Mail-Adresse';

?>