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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_user']['username']     = array('Benutzername', 'Bitte geben Sie einen eindeutigen Benutzernamen ein.');
$GLOBALS['TL_LANG']['tl_user']['name']         = array('Name', 'Bitte geben Sie den Vor- und Nachnamen ein.');
$GLOBALS['TL_LANG']['tl_user']['email']        = array('E-Mail-Adresse', 'Bitte geben Sie eine gültige E-Mail-Adresse ein.');
$GLOBALS['TL_LANG']['tl_user']['language']     = array('Backend-Sprache', 'Hier können Sie die Backend-Sprache auswählen.');
$GLOBALS['TL_LANG']['tl_user']['backendTheme'] = array('Backendmotiv', 'Hier können Sie das globale Backendmotiv überschreiben.');
$GLOBALS['TL_LANG']['tl_user']['showHelp']     = array('Erklärungen anzeigen', 'Unter jedem Eingabefeld einen kurzen Erklärungstext anzeigen.');
$GLOBALS['TL_LANG']['tl_user']['thumbnails']   = array('Vorschaubilder anzeigen', 'Vorschaubilder im Dateimanager anzeigen.');
$GLOBALS['TL_LANG']['tl_user']['useRTE']       = array('Rich Text Editor verwenden', 'Den Rich Text Editor zur Textformatierung verwenden.');
$GLOBALS['TL_LANG']['tl_user']['useCE']        = array('Code-Editor verwenden', 'Den Code-Editor zur Bearbeitung von Code-Elementen verwenden.');
$GLOBALS['TL_LANG']['tl_user']['fancyUpload']  = array('FancyUpload aktivieren', 'Falls FancyUpload in Ihrem Browser nicht fehlerfrei läuft, können Sie das Skript hier deaktivieren.');
$GLOBALS['TL_LANG']['tl_user']['oldBeTheme']   = array('Das alte Formularlayout verwenden', 'Die neuen einklappbaren 2-spaltigen Formulare nicht verwenden.');
$GLOBALS['TL_LANG']['tl_user']['admin']        = array('Zum Administrator machen', 'Administratoren haben uneingeschränkten Zugriff auf alle Module und Elemente!');
$GLOBALS['TL_LANG']['tl_user']['groups']       = array('Benutzergruppen', 'Hier können Sie den Benutzer einer oder mehreren Gruppen zuweisen.');
$GLOBALS['TL_LANG']['tl_user']['inherit']      = array('Rechtevererbung', 'Hier können Sie festlegen, welche Gruppenrechte der Benutzer erbt.');
$GLOBALS['TL_LANG']['tl_user']['group']        = array('Nur Gruppenrechte verwenden', 'Es werden ausschließlich Gruppenrechte verwendet.');
$GLOBALS['TL_LANG']['tl_user']['extend']       = array('Gruppenrechte erweitern', 'Gruppenrechte werden durch individuelle Rechte erweitert.');
$GLOBALS['TL_LANG']['tl_user']['custom']       = array('Nur Benutzerrechte verwenden', 'Es werden ausschließlich individuelle Rechte verwendet.');
$GLOBALS['TL_LANG']['tl_user']['modules']      = array('Backend-Module', 'Hier können Sie den Zugriff auf ein oder mehrere Backend-Module erlauben.');
$GLOBALS['TL_LANG']['tl_user']['themes']       = array('Theme-Module', 'Hier können Sie den Zugriff auf die Theme-Module steuern.');
$GLOBALS['TL_LANG']['tl_user']['pagemounts']   = array('Pagemounts', 'Hier können Sie den Zugriff auf eine oder mehrere Seiten erlauben (Unterseiten werden automatisch hinzugefügt).');
$GLOBALS['TL_LANG']['tl_user']['alpty']        = array('Erlaubte Seitentypen', 'Hier können Sie die erlaubten Seitentypen festlegen.');
$GLOBALS['TL_LANG']['tl_user']['filemounts']   = array('Filemounts', 'Hier können Sie den Zugriff auf ein oder mehrere Verzeichnisse erlauben (Unterverzeichnisse werden automatisch hinzugefügt).');
$GLOBALS['TL_LANG']['tl_user']['forms']        = array('Erlaubte Formulare', 'Hier können Sie den Zugriff auf ein oder mehrere Formulare erlauben.');
$GLOBALS['TL_LANG']['tl_user']['formp']        = array('Formularrechte', 'Hier können Sie die Formularrechte festlegen.');
$GLOBALS['TL_LANG']['tl_user']['disable']      = array('Deaktivieren', 'Das Konto vorübergehend deaktivieren.');
$GLOBALS['TL_LANG']['tl_user']['start']        = array('Aktivieren am', 'Das Konto automatisch an diesem Tag aktivieren.');
$GLOBALS['TL_LANG']['tl_user']['stop']         = array('Deaktivieren am', 'Das Konto automatisch an diesem Tag deaktivieren.');
$GLOBALS['TL_LANG']['tl_user']['session']      = array('Daten bereinigen', 'Bitte wählen Sie die zu bereinigenden Daten aus.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_user']['name_legend']       = 'Name und E-Mail';
$GLOBALS['TL_LANG']['tl_user']['backend_legend']    = 'Backend-Einstellungen';
$GLOBALS['TL_LANG']['tl_user']['password_legend']   = 'Passwort-Einstellungen';
$GLOBALS['TL_LANG']['tl_user']['admin_legend']      = 'Administrator';
$GLOBALS['TL_LANG']['tl_user']['groups_legend']     = 'Benutzergruppen';
$GLOBALS['TL_LANG']['tl_user']['modules_legend']    = 'Erlaubte Module';
$GLOBALS['TL_LANG']['tl_user']['pagemounts_legend'] = 'Pagemounts';
$GLOBALS['TL_LANG']['tl_user']['filemounts_legend'] = 'Filemounts';
$GLOBALS['TL_LANG']['tl_user']['forms_legend']      = 'Formular-Rechte';
$GLOBALS['TL_LANG']['tl_user']['account_legend']    = 'Konto-Einstellungen';
$GLOBALS['TL_LANG']['tl_user']['session_legend']    = 'Cache leeren';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_user']['sessionLabel']  = 'Session-Daten';
$GLOBALS['TL_LANG']['tl_user']['htmlLabel']     = 'Bildercache';
$GLOBALS['TL_LANG']['tl_user']['tempLabel']     = 'Temporärer Ordner';
$GLOBALS['TL_LANG']['tl_user']['sessionPurged'] = 'Die Session-Daten wurden gelöscht';
$GLOBALS['TL_LANG']['tl_user']['htmlPurged']    = 'Der Bildercache wurde geleert';
$GLOBALS['TL_LANG']['tl_user']['tempPurged']    = 'Der temporäre Ordner wurde geleert';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_user']['new']    = array('Neuer Benutzer', 'Einen neuen Benutzer anlegen');
$GLOBALS['TL_LANG']['tl_user']['show']   = array('Benutzerdetails', 'Details des Benutzers ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_user']['edit']   = array('Benutzer bearbeiten', 'Benutzer ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_user']['copy']   = array('Benutzer duplizieren', 'Benutzer ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_user']['delete'] = array('Benutzer löschen', 'Benutzer ID %s löschen');
$GLOBALS['TL_LANG']['tl_user']['toggle'] = array('Benutzer aktivieren/deaktivieren', 'Benutzer ID %s aktivieren/deaktivieren');
$GLOBALS['TL_LANG']['tl_user']['su']     = array('Benutzer wechseln', 'Zu Benutzer ID %s wechseln');

?>