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
$GLOBALS['TL_LANG']['tl_layout']['name']          = array('Name des Layouts', 'Bitte geben Sie einen eindeutigen Namen für das Layout ein.');
$GLOBALS['TL_LANG']['tl_layout']['fallback']      = array('Standardlayout', 'Das Standardlayout wird für alle Seiten verwendet, denen kein Layout zugewiesen wurde.');
$GLOBALS['TL_LANG']['tl_layout']['template']      = array('Layoutvorlage', 'Bitte wählen Sie eine Layoutvorlage. Die Standardvorlage heißt <em>fe_page</em>. Wenn Sie eigene Vorlagen hinzufügen möchten, speichern Sie diese im Ordner <em>templates</em> (Dateiendung muss <em>tpl</em> sein).');
$GLOBALS['TL_LANG']['tl_layout']['mootools']      = array('Mootools JavaScript', 'Wenn Sie auf Ihrer Webseite ein Mootools Akkordeon verwenden wollen, müssen Sie eine JavaScript-Vorlage hinzufügen, die den Effekt initialisiert. Diese Vorlage können Sie hier auswählen. Mootools-Vorlagen beginnen mit <em>moo_</em> (Endung <em>tpl</em>).');
$GLOBALS['TL_LANG']['tl_layout']['doctype']       = array('Documenttyp Definition', 'Bitte wählen Sie eine DTD für das aktuelle Layout.');
$GLOBALS['TL_LANG']['tl_layout']['urchinId']      = array('Google Analytics ID', 'Wenn Sie eine Google Analytics ID haben, können Sie diese hier eingeben.');
$GLOBALS['TL_LANG']['tl_layout']['stylesheet']    = array('Stylesheets', 'Bitte wählen Sie die Stylesheets, die in das aktuelle Layout eingebunden werden sollen.');
$GLOBALS['TL_LANG']['tl_layout']['newsfeeds']     = array('RSS Newsfeeds', 'Bitte wählen Sie die RSS Newsfeeds, die in das aktuelle Layout eingebunden werden sollen.');
$GLOBALS['TL_LANG']['tl_layout']['calendarfeeds'] = array('RSS Kalender-Feeds', 'Bitte wählen Sie die RSS Kalender-Feeds, die in das aktuelle Layout eingebunden werden sollen.');
$GLOBALS['TL_LANG']['tl_layout']['onload']        = array('Body onload', 'Falls eines Ihrer Skripte ein <em>body onload</em> Event benötigt, können Sie den entsprechenden Quelltext hier eingeben.');
$GLOBALS['TL_LANG']['tl_layout']['head']          = array('Zusätzliche &lt;head&gt; Tags', 'Hier können Sie zusätzliche Meta Tags und Skripte angeben, die am Ende des Seitenkopfs eingefügt werden. Bitte beachten Sie, dass Standard Meta Tags, Script Tags und Stylesheet Tags automatisch erstellt werden.');
$GLOBALS['TL_LANG']['tl_layout']['cols']          = array('Spalten', 'Bitte wählen Sie die Anzahl der Spalten.');
$GLOBALS['TL_LANG']['tl_layout']['1cl']           = array('Eine Spalte', 'es wird nur die Hauptspalte angezeigt.');
$GLOBALS['TL_LANG']['tl_layout']['2cll']          = array('Zwei Spalten mit der Hauptspalte auf der rechten Seite', 'die Hauptspalte wird auf der rechten Seite neben der linken Spalte angezeigt.');
$GLOBALS['TL_LANG']['tl_layout']['2clr']          = array('Zwei Spalten mit der Hauptspalte auf der linken Seite', 'die Hauptspalte wird auf der linken Seite neben der rechten Spalte angezeigt.');
$GLOBALS['TL_LANG']['tl_layout']['3cl']           = array('Drei Spalten mit der Hauptspalte in der Mitte', 'die Hauptspalte wird in der Mitte zwischen der linken und der rechten Spalte angezeigt.');
$GLOBALS['TL_LANG']['tl_layout']['widthLeft']     = array('Breite der linken Spalte', 'Bitte geben Sie die Breite der linken Spalte ein.');
$GLOBALS['TL_LANG']['tl_layout']['widthRight']    = array('Breite der rechten Spalte', 'Bitte geben Sie die Breite der rechten Spalte ein.');
$GLOBALS['TL_LANG']['tl_layout']['header']        = array('Kopfzeile einfügen', 'Fügt eine Kopfzeile in das Layout ein.');
$GLOBALS['TL_LANG']['tl_layout']['headerHeight']  = array('Höhe der Kopfzeile', 'Bitte geben Sie die Höhe der Kopfzeile ein.');
$GLOBALS['TL_LANG']['tl_layout']['footer']        = array('Fußzeile einfügen', 'Fügt eine Fußzeile in das Layout ein.');
$GLOBALS['TL_LANG']['tl_layout']['footerHeight']  = array('Höhe der Fußzeile', 'Bitte geben Sie die Höhe der Fußzeile ein.');
$GLOBALS['TL_LANG']['tl_layout']['static']        = array('Statisches Layout', 'Ein statisches Layout mit fester Breite und Ausrichtung erstellen.');
$GLOBALS['TL_LANG']['tl_layout']['width']         = array('Gesamtbreite', 'Bitte geben Sie die Gesamtbreite ein (wird dem Wrapper-Element zugewiesen).');
$GLOBALS['TL_LANG']['tl_layout']['align']         = array('Ausrichtung der Seite', 'Bitte wählen Sie die Ausrichtung der Seite.');
$GLOBALS['TL_LANG']['tl_layout']['sections']      = array('Eigene Layoutbereiche', 'Dies ist eine Liste aller eigenen Layoutbereiche, die im Modul <em>Einstellungen</em> definiert wurden. Bitte wählen Sie die Bereiche, die Sie in diesem Layout verwenden möchten.');
$GLOBALS['TL_LANG']['tl_layout']['sPosition']     = array('Position der Layoutbereiche', 'Bitte wählen Sie aus, an welcher Position die Layoutbereiche dargestellt werden sollen.');
$GLOBALS['TL_LANG']['tl_layout']['modules']       = array('Eingebundene Module', 'Benutzen Sie die Schaltflächen, um Module hinzuzufügen, zu verschieben oder zu löschen. Wenn Sie ohne JavaScript-Unterstützung arbeiten, sollten Sie Ihre Eingaben speichern bevor Sie die Struktur der Module verändern!');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_layout']['module']       = 'Modul';
$GLOBALS['TL_LANG']['tl_layout']['column']       = 'Spalte';
$GLOBALS['TL_LANG']['tl_layout']['xhtml_strict'] = 'XHTML Strict';
$GLOBALS['TL_LANG']['tl_layout']['xhtml_trans']  = 'XHTML Transitional';
$GLOBALS['TL_LANG']['tl_layout']['before']       = 'Nach der Kopfzeile';
$GLOBALS['TL_LANG']['tl_layout']['main']         = 'In der Hauptspalte';
$GLOBALS['TL_LANG']['tl_layout']['after']        = 'Vor der Fußzeile';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_layout']['new']    = array('Neues Layout', 'Ein neues Layout anlegen');
$GLOBALS['TL_LANG']['tl_layout']['show']   = array('Layoutdetails', 'Details des Layouts ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_layout']['copy']   = array('Layout duplizieren', 'Layout ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_layout']['delete'] = array('Layout löschen', 'Layout ID %s löschen');
$GLOBALS['TL_LANG']['tl_layout']['edit']   = array('Layout bearbeiten', 'Layout ID %s bearbeiten');


/**
 * Wizard buttons
 */
$GLOBALS['TL_LANG']['tl_layout']['wz_copy']   = 'Diesen Eintrag duplizieren';
$GLOBALS['TL_LANG']['tl_layout']['wz_up']     = 'Diesen Eintrag eine Position nach oben verschieben';
$GLOBALS['TL_LANG']['tl_layout']['wz_down']   = 'Diesen Eintrag eine Position nach unten verschieben';
$GLOBALS['TL_LANG']['tl_layout']['wz_delete'] = 'Diesen Eintrag löschen';

?>