<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Language
 * @license    LGPL
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_layout']['name']          = array('Titel', 'Bitte geben Sie den Layout-Titel ein.');
$GLOBALS['TL_LANG']['tl_layout']['fallback']      = array('Standardlayout', 'Das Layout als Standardlayout verwenden.');
$GLOBALS['TL_LANG']['tl_layout']['rows']          = array('Reihen', 'Bitte wählen Sie die Anzahl an Reihen.');
$GLOBALS['TL_LANG']['tl_layout']['1rw']           = array('Nur die Hauptreihe', 'Es wird nur eine Reihe angezeigt.');
$GLOBALS['TL_LANG']['tl_layout']['2rwh']          = array('Kopfzeile und Hauptreihe', 'Eine Kopfzeile oberhalb der Hauptreihe anzeigen.');
$GLOBALS['TL_LANG']['tl_layout']['2rwf']          = array('Hauptreihe und Fußzeile', 'Eine Fußzeile unterhalb der Hauptreihe anzeigen.');
$GLOBALS['TL_LANG']['tl_layout']['3rw']           = array('Kopfzeile, Hauptreihe und Fußzeile', 'Eine Kopfzeile oberhalb und eine Fußzeile unterhalb der Hauptreihe anzeigen.');
$GLOBALS['TL_LANG']['tl_layout']['headerHeight']  = array('Höhe der Kopfzeile', 'Bitte geben Sie die Höhe der Kopfzeile ein.');
$GLOBALS['TL_LANG']['tl_layout']['footerHeight']  = array('Höhe der Fußzeile', 'Bitte geben Sie die Höhe der Fußzeile ein.');
$GLOBALS['TL_LANG']['tl_layout']['cols']          = array('Spalten', 'Bitte wählen Sie die Anzahl an Spalten.');
$GLOBALS['TL_LANG']['tl_layout']['1cl']           = array('Nur die Hauptspalte', 'Es wird nur eine Spalte angezeigt.');
$GLOBALS['TL_LANG']['tl_layout']['2cll']          = array('Hauptspalte und linke Spalte', 'Es werden zwei Spalten mit der Hauptspalte auf der rechten Seite angezeigt.');
$GLOBALS['TL_LANG']['tl_layout']['2clr']          = array('Hauptspalte und rechte Spalte', 'Es werden zwei Spalten mit der Hauptspalte auf der linken Seite angezeigt.');
$GLOBALS['TL_LANG']['tl_layout']['3cl']           = array('Hauptspalte, linke und rechte Spalte', 'Es werden drei Spalten mit der Hauptspalte in der Mitte angezeigt.');
$GLOBALS['TL_LANG']['tl_layout']['widthLeft']     = array('Breite der linken Spalte', 'Bitte geben Sie die Breite der linken Spalte ein.');
$GLOBALS['TL_LANG']['tl_layout']['widthRight']    = array('Breite der rechten Spalte', 'Bitte geben Sie die Breite der rechten Spalte ein.');
$GLOBALS['TL_LANG']['tl_layout']['sections']      = array('Eigene Layoutbereiche', 'Eigene Layoutbereiche können in den Backend-Einstellungen definiert werden.');
$GLOBALS['TL_LANG']['tl_layout']['sPosition']     = array('Position der Layoutbereiche', 'Bitte wählen Sie die Position der eigenen Layoutbereiche aus.');
$GLOBALS['TL_LANG']['tl_layout']['stylesheet']    = array('Stylesheets', 'Bitte wählen Sie die Stylesheets aus, die Sie dem Layout hinzufügen möchten.');
$GLOBALS['TL_LANG']['tl_layout']['skipTinymce']   = array('files/tinymce.css ignorieren', 'Das TinyMCE-Stylesheet <em>files/tinymce.css</em> nicht einbinden.');
$GLOBALS['TL_LANG']['tl_layout']['external']      = array('Externe Stylesheets', 'Hier können Sie externe Stylesheets aus dem Files-Verzeichnis hinzufügen (z.B. <em>files/css/style.css|screen|static</em>).');
$GLOBALS['TL_LANG']['tl_layout']['webfonts']      = array('Google-Webfonts', 'Hier können Sie Google-Webfonts zu Ihrer Webseite hinzfügen. Geben Sie die Schriftarten ohne die Basis-URL an (z.B. <em>Ubuntu|Ubuntu+Mono</em>).');
$GLOBALS['TL_LANG']['tl_layout']['newsfeeds']     = array('Newsfeeds', 'Bitte wählen Sie die Newsfeeds aus, die Sie dem Layout hinzufügen möchten.');
$GLOBALS['TL_LANG']['tl_layout']['calendarfeeds'] = array('Kalender-Feeds', 'Bitte wählen Sie die Kalender-Feeds aus, die Sie dem Layout hinzufügen möchten.');
$GLOBALS['TL_LANG']['tl_layout']['modules']       = array('Eingebundene Module', 'Wenn JavaScript deaktiviert ist, speichern Sie unbedingt Ihre Änderungen, bevor Sie die Reihenfolge ändern.');
$GLOBALS['TL_LANG']['tl_layout']['template']      = array('Seitentemplate', 'Hier können Sie das Seitentemplate auswählen.');
$GLOBALS['TL_LANG']['tl_layout']['skipFramework'] = array('Das CSS-Framework deaktivieren', 'Das Contao-CSS-Framework nicht laden. Beachten Sie, dass der Seitenlayout-Generator in diesem Fall nicht funktioniert.');
$GLOBALS['TL_LANG']['tl_layout']['doctype']       = array('Ausgabeformat', 'Hier legen Sie das Ausgabeformat fest.');
$GLOBALS['TL_LANG']['tl_layout']['cssClass']      = array('Body-Klasse', 'Hier können Sie dem Body-Tag individuelle Klassen hinzufügen.');
$GLOBALS['TL_LANG']['tl_layout']['onload']        = array('Body onload', 'Hier können Sie dem Body-Tag ein Onload-Attribut hinzufügen.');
$GLOBALS['TL_LANG']['tl_layout']['head']          = array('Zusätzliche &lt;head&gt;-Tags', 'Hier können Sie dem Head-Bereich der Seite individuelle Tags hinzufügen.');
$GLOBALS['TL_LANG']['tl_layout']['addJQuery']     = array('jQuery laden', 'Dem Layout die jQuery-Bibliothek hinzufügen.');
$GLOBALS['TL_LANG']['tl_layout']['jSource']       = array('jQuery-Quelle', 'Hier können Sie auswählen, von wo das jQuery-Skript geladen wird.');
$GLOBALS['TL_LANG']['tl_layout']['jquery']        = array('jQuery-Templates', 'Hier können Sie eines oder mehrere jQuery-Templates auswählen.');
$GLOBALS['TL_LANG']['tl_layout']['addMooTools']   = array('MooTools laden', 'Dem Layout die MooTools-Bibliothek hinzufügen.');
$GLOBALS['TL_LANG']['tl_layout']['mooSource']     = array('MooTools-Quelle', 'Hier können Sie auswählen, von wo die MooTools-Skripte geladen werden.');
$GLOBALS['TL_LANG']['tl_layout']['mootools']      = array('MooTools-Templates', 'Hier können Sie eines oder mehrere MooTools-Templates auswählen.');
$GLOBALS['TL_LANG']['tl_layout']['analytics']     = array('Analytics-Template', 'Hier können Sie dem Layout ein Analytics-Template (z.B. für Google-Analytics oder Piwik) hinzufügen. Beachten Sie, dass Sie diese Templates anpassen und Ihre Analytics-ID hinzufügen müssen, bevor Sie sie verwenden können!');
$GLOBALS['TL_LANG']['tl_layout']['script']        = array('Eigener JavaScript-Code', 'Der JavaScript-Code wird am Ende der Seite eingefügt.');
$GLOBALS['TL_LANG']['tl_layout']['static']        = array('Statisches Layout', 'Ein statisches Layout mit fester Breite und Ausrichtung erstellen.');
$GLOBALS['TL_LANG']['tl_layout']['width']         = array('Gesamtbreite', 'Die Gesamtbreite wird dem Wrapper-Element zugewiesen.');
$GLOBALS['TL_LANG']['tl_layout']['align']         = array('Ausrichtung', 'Bitte wählen Sie die Ausrichtung der Seite.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_layout']['title_legend']    = 'Titel';
$GLOBALS['TL_LANG']['tl_layout']['header_legend']   = 'Reihen';
$GLOBALS['TL_LANG']['tl_layout']['column_legend']   = 'Spalten';
$GLOBALS['TL_LANG']['tl_layout']['sections_legend'] = 'Eigene Layoutbereiche';
$GLOBALS['TL_LANG']['tl_layout']['style_legend']    = 'Stylesheets';
$GLOBALS['TL_LANG']['tl_layout']['feed_legend']     = 'RSS/Atom-Feeds';
$GLOBALS['TL_LANG']['tl_layout']['modules_legend']  = 'Frontend-Module';
$GLOBALS['TL_LANG']['tl_layout']['expert_legend']   = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_layout']['script_legend']   = 'Skript-Einstellungen';
$GLOBALS['TL_LANG']['tl_layout']['static_legend']   = 'Statisches Layout';
$GLOBALS['TL_LANG']['tl_layout']['jquery_legend']   = 'jQuery';
$GLOBALS['TL_LANG']['tl_layout']['mootools_legend'] = 'MooTools';
$GLOBALS['TL_LANG']['tl_layout']['j_local']         = 'jQuery - Lokale Datei';
$GLOBALS['TL_LANG']['tl_layout']['j_googleapis']    = 'jQuery - googleapis.com';
$GLOBALS['TL_LANG']['tl_layout']['j_fallback']      = 'jQuery - googleapis.com mit lokalem Fallback';
$GLOBALS['TL_LANG']['tl_layout']['moo_local']       = 'MooTools - lokale Datei';
$GLOBALS['TL_LANG']['tl_layout']['moo_googleapis']  = 'MooTools - googleapis.com';
$GLOBALS['TL_LANG']['tl_layout']['moo_fallback']    = 'MooTools - googleapis.com mit lokalem Fallback';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_layout']['html5']            = 'HTML';
$GLOBALS['TL_LANG']['tl_layout']['xhtml_strict']     = 'XHTML Strict';
$GLOBALS['TL_LANG']['tl_layout']['xhtml_trans']      = 'XHTML Transitional';
$GLOBALS['TL_LANG']['tl_layout']['before']           = 'Unterhalb der Kopfzeile';
$GLOBALS['TL_LANG']['tl_layout']['main']             = 'In der Hauptspalte';
$GLOBALS['TL_LANG']['tl_layout']['after']            = 'Oberhalb der Fußzeile';
$GLOBALS['TL_LANG']['tl_layout']['edit_styles']      = 'Die Stylesheets bearbeiten';
$GLOBALS['TL_LANG']['tl_layout']['edit_module']      = 'Das Modul bearbeiten';
$GLOBALS['TL_LANG']['tl_layout']['analytics_google'] = 'Google-Analytics';
$GLOBALS['TL_LANG']['tl_layout']['analytics_piwik']  = 'Piwik';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_layout']['new']        = array('Neues Layout', 'Ein neues Layout anlegen');
$GLOBALS['TL_LANG']['tl_layout']['show']       = array('Layoutdetails', 'Details des Layouts ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_layout']['edit']       = array('Layout bearbeiten', 'Layout ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_layout']['cut']        = array('Layout verschieben ', 'Layout ID %s verschieben');
$GLOBALS['TL_LANG']['tl_layout']['copy']       = array('Layout duplizieren', 'Layout ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_layout']['delete']     = array('Layout löschen', 'Layout ID %s löschen');
$GLOBALS['TL_LANG']['tl_layout']['editheader'] = array('Theme bearbeiten', 'Die Theme-Einstellungen bearbeiten');
