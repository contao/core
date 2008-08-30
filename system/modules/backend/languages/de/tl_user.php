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
$GLOBALS['TL_LANG']['tl_user']['username']   = array('Benutzername', 'Bitte geben Sie einen eindeutigen Benutzernamen an.');
$GLOBALS['TL_LANG']['tl_user']['name']       = array('Name', 'Bitte geben Sie Vor- und Nachnamen ein.');
$GLOBALS['TL_LANG']['tl_user']['email']      = array('E-Mail-Adresse', 'Bitte geben Sie eine gültige E-Mail-Adresse ein.');
$GLOBALS['TL_LANG']['tl_user']['language']   = array('Backend Sprache', 'Wählen Sie die Sprache, die für den Benutzer angezeigt werden soll.');
$GLOBALS['TL_LANG']['tl_user']['showHelp']   = array('Erklärung anzeigen', 'Wenn Sie diese Option wählen, wird nach jedem Eingabefeld eine kurze Erklärung angezeigt.');
$GLOBALS['TL_LANG']['tl_user']['useRTE']     = array('Rich Text Editor verwenden', 'Sollte der Rich Text Editor nicht einwandfrei funktionieren, können Sie ihn hier deaktivieren.');
$GLOBALS['TL_LANG']['tl_user']['thumbnails'] = array('Vorschaubilder anzeigen', 'Wenn Sie diese Option wählen, werden Vorschaubilder im Dateimangager angezeigt.');
$GLOBALS['TL_LANG']['tl_user']['admin']      = array('Administrator', 'Ein Administrator hat uneingeschränkten Zugriff auf alle Seiten, Module, Extensions und Dateien!');
$GLOBALS['TL_LANG']['tl_user']['groups']     = array('Gruppen', 'Jeder Benutzer bezieht seine Zugriffsrechte von einer Gruppe und muss daher Mitglied mindestens einer Gruppen sein. Ist ein Benutzer mehreren Gruppen zugewiesen, addieren sich die einzelnen Zugriffsrechte.');
$GLOBALS['TL_LANG']['tl_user']['inherit']    = array('Rechtevererbung', 'Bitte legen Sie fest, inwiefern der Benutzer seine Rechte von seinen Gruppen bezieht.');
$GLOBALS['TL_LANG']['tl_user']['group']      = array('Nur Rechte der Gruppe verwenden', 'die Rechte des Benutzers werden ausschließlich von den freigeschalteten Gruppen bezogen.');
$GLOBALS['TL_LANG']['tl_user']['extend']     = array('Rechte der Gruppe erweitern', 'von den Gruppen bezogene Rechte werden durch individuelle Einstellungen erweitert.');
$GLOBALS['TL_LANG']['tl_user']['custom']     = array('Nur individuelle Rechte verwenden', 'es werden ausschließlich die Rechte des Benutzers unabhängig von den Rechten der Gruppen verwendet.');
$GLOBALS['TL_LANG']['tl_user']['modules']    = array('Backend Module', 'Bitte wählen Sie die Module aus, die für den Benutzer freigeschaltet werden sollen.');
$GLOBALS['TL_LANG']['tl_user']['pagemounts'] = array('Pagemounts', 'Bitte wählen Sie die Seiten aus, die für den Benutzer freigeschaltet werden sollen. Eine Seite wird immer inklusive aller Unterseiten freigeschaltet!');
$GLOBALS['TL_LANG']['tl_user']['alpty']      = array('Erlaubte Seitentypen', 'Bitte wählen Sie, welche Seitentypen für den Benutzer verfügbar sein sollen.');
$GLOBALS['TL_LANG']['tl_user']['filemounts'] = array('Filemounts', 'Bitte wählen Sie die Verzeichnisse aus, die für den Benutzer freigeschaltet werden sollen. Ein Verzeichnis wird immer inklusive aller Unterverzeichnisse freigeschaltet!');
$GLOBALS['TL_LANG']['tl_user']['forms']      = array('Formulare', 'Bitte wählen Sie die Formulare, die für den Benutzer sichtbar sein sollen.');
$GLOBALS['TL_LANG']['tl_user']['disable']    = array('Deaktivieren', 'Ist ein Benutzerkonto deaktiviert, kann sich der Benutzer nicht mehr am System anmelden.');
$GLOBALS['TL_LANG']['tl_user']['start']      = array('Aktivieren am', 'Wenn Sie hier ein Datum eingeben, wird das Benutzerkonto zu diesem Tag aktiviert.');
$GLOBALS['TL_LANG']['tl_user']['stop']       = array('Deaktivieren am', 'Wenn Sie hier ein Datum eingeben, wird das Benutzerkonto zu diesem Tag deaktiviert.');
$GLOBALS['TL_LANG']['tl_user']['session']    = array('Session-Daten löschen', 'Wenn Sie diese Option wählen, werden die gespeicherten Session-Daten gelöscht.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_user']['sessionDeleted'] = 'Ihre Session-Daten wurden gelöscht';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_user']['new']    = array('Neuer Benutzer', 'Einen neuen Benutzer anlegen');
$GLOBALS['TL_LANG']['tl_user']['show']   = array('Benutzerdetails', 'Details des Benutzers ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_user']['edit']   = array('Benutzer bearbeiten', 'Benutzer ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_user']['copy']   = array('Benutzer duplizieren', 'Benutzer ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_user']['delete'] = array('Benutzer löschen', 'Benutzer ID %s löschen');
$GLOBALS['TL_LANG']['tl_user']['su']     = array('Benutzer wechseln', 'Zu Benutzer ID %s wechseln');

?>