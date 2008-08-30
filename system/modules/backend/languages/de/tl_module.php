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
$GLOBALS['TL_LANG']['tl_module']['name']           = array('Name des Moduls', 'Bitte geben Sie einen eindeutigen Namen für das Modul ein.');
$GLOBALS['TL_LANG']['tl_module']['type']           = array('Modultyp', 'Bitte wählen Sie den Modultyp.');
$GLOBALS['TL_LANG']['tl_module']['headline']       = array('Überschrift', 'Wenn Sie eine Überschrift eingeben, wird sie über dem Modul angezeigt.');
$GLOBALS['TL_LANG']['tl_module']['singleSRC']      = array('Quelldatei', 'Bitte wählen Sie eine Datei aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['tl_module']['multiSRC']       = array('Quelldateien', 'Bitte wählen Sie eine oder mehrere Dateien aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['tl_module']['showLevel']      = array('Stoplevel', 'Legt fest bis zu welchem Level die Menüpunkte angezeigt werden. Wählen Sie 0, um alle Level anzuzeigen.');
$GLOBALS['TL_LANG']['tl_module']['levelOffset']    = array('Startlevel', 'Legt fest ab welchem Level die Menüpunkte angezeigt werden. Wählen Sie 0, um alle Level anzuzeigen.');
$GLOBALS['TL_LANG']['tl_module']['hardLimit']      = array('Hard Limit', 'Kein Menüpunkte (auch keine aktiven Menüpunkte) jenseits des Stoplevels anzeigen.');
$GLOBALS['TL_LANG']['tl_module']['showProtected']  = array('Geschütze Menüpunkte anzeigen', 'Auch Menüpunkte anzeigen, die sonst nur für angemeldete Benutzer sichtbar sind.');
$GLOBALS['TL_LANG']['tl_module']['showHidden']     = array('Versteckte Menüpunkte anzeigen', 'Auch Menüpunkte anzeigen, die in der Navigation versteckt sind.');
$GLOBALS['TL_LANG']['tl_module']['navigationTpl']  = array('Vorlage für die Navigation', 'Bitte wählen Sie eine Layoutvorlage für die Navigation aus (Navigationsvorlagen beginnen mit <em>nav_</em>).');
$GLOBALS['TL_LANG']['tl_module']['includeRoot']    = array('Wurzelseite als Startseite', 'Die Wurzelseite der Seitenstruktur als Anfangspunkt des Moduls verwenden.');
$GLOBALS['TL_LANG']['tl_module']['defineRoot']     = array('Referenzseite festlegen', 'Eine Referenzseite als Quell- oder Zielseite des Moduls festlegen.');
$GLOBALS['TL_LANG']['tl_module']['rootPage']       = array('Referenzseite', 'Hier können Sie eine Referenzseite als Quell- oder Zielseite festlegen (z.B. eine alternative Startseite).');
$GLOBALS['TL_LANG']['tl_module']['customLabel']    = array('Individuelle Bezeichnung', 'Hier können Sie eine individuelle Bezeichnung eingeben, die anstatt <em>Quicklink</em> oder <em>Quick Navigation</em> verwendet wird.');
$GLOBALS['TL_LANG']['tl_module']['pages']          = array('Seiten', 'Hier können Sie die Seite für das Quicklink Modul auswählen.');
$GLOBALS['TL_LANG']['tl_module']['queryType']      = array('Standard-Abfragetyp', 'Bitte wählen Sie den Standard-Abfragetyp für die Suche aus.');
$GLOBALS['TL_LANG']['tl_module']['searchType']     = array('Suchformular', 'Bitte wählen Sie ein Suchformular aus.');
$GLOBALS['TL_LANG']['tl_module']['perPage']        = array('Datensätze pro Seite', 'Bitte geben Sie die Anzahl der Datensätze pro Seite ein (0 = Seitenumbruch deaktivieren).');
$GLOBALS['TL_LANG']['tl_module']['searchTpl']      = array('Vorlage für Suchergebnisse', 'Bitte wählen Sie eine Layout für die Suchergebnisse aus. Speichern Sie eigene <em>search_</em>-Vorlagen im Ordner <em>templates</em>.');
$GLOBALS['TL_LANG']['tl_module']['contextLength']  = array('Kontext-Spannweite', 'Die Anzahl an Buchstaben, die links und rechts des Suchbegriffs als Kontext verwendet werden.');
$GLOBALS['TL_LANG']['tl_module']['totalLength']    = array('Gesamte Kontextlänge', 'Bitte geben Sie die Gesamtlänge der Kontextzeile jedes Ergebnisses ein.');
$GLOBALS['TL_LANG']['tl_module']['editable']       = array('Editierbare Felder', 'Bitte wählen Sie mindestens ein Feld aus, das im Frontend editierbar sein soll.');
$GLOBALS['TL_LANG']['tl_module']['newsletters']    = array('Newsletter', 'Bitte wählen Sie welche Newsletter der Benutzer bei der Registrierung abonnieren kann.');
$GLOBALS['TL_LANG']['tl_module']['memberTpl']      = array('Formularvorlage', 'Bitte wählen Sie eine Vorlage für das Formular aus. Die Standardvorlage heißt <em>member_default</em>. Wenn Sie eigene Vorlagen hinzufügen möchten, speichern Sie diese im Ordner <em>templates</em> (Dateiendung muss <em>tpl</em> sein).');
$GLOBALS['TL_LANG']['tl_module']['cols']           = array('Anzahl an Spalten', 'Bitte wählen Sie die Anzahl an Spalten des Formulars.');
$GLOBALS['TL_LANG']['tl_module']['1cl']            = array('Eine Spalte', 'die Bezeichnung jedes Eingabefeldes wird über dem Eingabefeld angezeigt.');
$GLOBALS['TL_LANG']['tl_module']['2cl']            = array('Zwei Spalten', 'die Bezeichnung jedes Eingabefeldes wird links neben dem Eingabefeld angezeigt.');
$GLOBALS['TL_LANG']['tl_module']['redirectBack']   = array('Zur zuletzt besuchten Seite weiterleiten', 'Den Benutzer nach dem Login/Logout zurück zu der zuletzt besuchten Seiten leiten.');
$GLOBALS['TL_LANG']['tl_module']['jumpTo']         = array('Weiterleitung zu Seite', 'Mit dieser Einstellung legen Sie fest, auf welche Seite ein Benutzer nach einer bestimmten Aktion wie z.B. dem Anklicken eines Links oder dem Abschicken eines Formulars weitergeleitet wird.');
$GLOBALS['TL_LANG']['tl_module']['form']           = array('Formular', 'Bitte wählen Sie ein Formular.');
$GLOBALS['TL_LANG']['tl_module']['html']           = array('HTML Code', 'Bitte geben Sie Ihren HTML-Code ein.');
$GLOBALS['TL_LANG']['tl_module']['size']           = array('Breite und Höhe', 'Bitte geben Sie die Breite und die Höhe in Pixeln ein.');
$GLOBALS['TL_LANG']['tl_module']['imgSize']        = array('Bildbreite und Bildhöhe', 'Geben Sie entweder die Bildbreite, die Bildhöhe oder beide Werte ein, um die Bildgröße anzupassen. Wenn Sie keine Angaben machen, wird das Bild in seiner Originalgröße angezeigt.');
$GLOBALS['TL_LANG']['tl_module']['alt']            = array('Alternativer Text', 'Damit Bilder oder Filme barrierefrei dargestellt werden können, sollten sie immer einen alternativen Text mit einer kurzen Beschreibung des Inhaltes enthalten.');
$GLOBALS['TL_LANG']['tl_module']['useCaption']     = array('Bildunterschrift anzeigen', 'Wenn Sie diese Option wählen, wird der Name des jeweiligen Bildes unter dem Bild angezeigt.');
$GLOBALS['TL_LANG']['tl_module']['source']         = array('Quelle', 'Bitte wählen Sie die Quelle des Elements.');
$GLOBALS['TL_LANG']['tl_module']['url']            = array('URL', 'Bitte geben Sie vollständige URL inklusive http:// ein.');
$GLOBALS['TL_LANG']['tl_module']['flashvars']      = array('FlashVars', 'Hier können Sie Parameter eingeben, die Sie an den Flash-Film übergeben möchten (<em>var1=value1&amp;var2=value2</em>).');
$GLOBALS['TL_LANG']['tl_module']['altContent']     = array('Alternativer Inhalt', 'Bitte geben Sie hier einen alternativen Inhalt ein, falls der Flash-Film nicht geladen werden kann. HTML ist erlaubt.');
$GLOBALS['TL_LANG']['tl_module']['transparent']    = array('Transparenter Flash-Film', 'Wählen Sie diese Option um den Flash-Film transparent zu machen (wmode = transparent). Beachten Sie, dass Buttons und Textfelder in transparenten Flash-Filmen in mehreren Browsern nicht korrekt funktionieren.');
$GLOBALS['TL_LANG']['tl_module']['interactive']    = array('Interaktiver Flash-Film', 'Wählen Sie diese Option wenn der Flash-Film mit dem Webbrowser über JavaScript und die Flash-Funktion <em>fscommand()</em> kommunizieren soll.');
$GLOBALS['TL_LANG']['tl_module']['flashID']        = array('Flash-Film ID', 'Bitte geben Sie eine eindeutige ID für den Flash-Film ein.');
$GLOBALS['TL_LANG']['tl_module']['version']        = array('Flash-Player Version und Build', 'Geben Sie hier die benötigte Flash-Player Versions- und Build Nummer ein (wenn Ihr Flash-Movie z.B. mindestens den Flash-Player 6.0.12.0 erfordert, geben Sie 6 und 12 ein).');
$GLOBALS['TL_LANG']['tl_module']['flashJS']        = array('JavaScript _DoFSCommand(command, args) {', 'Bitte geben Sie den Inhalt der JavaScript <em>_DoFSCommand()</em> Funktion ein. Die Variable, die das Kommando enthält, heißt <em>command</em>, die Variable, die die Argumente enthält, heißt <em>args</em>.');
$GLOBALS['TL_LANG']['tl_module']['inColumn']       = array('Spalte', 'Bitte wählen Sie die Spalte, deren Artikel Sie auflisten möchten.');
$GLOBALS['TL_LANG']['tl_module']['skipFirst']      = array('Ersten Artikel überspringen', 'Wählen Sie diese Option um den ersten Artikel von der Liste auszunehmen.');
$GLOBALS['TL_LANG']['tl_module']['searchable']     = array('Durchsuchbar', 'Das Modul für die Website-Suche indizieren (nicht verfügbar bei geschützten Modulen).');
$GLOBALS['TL_LANG']['tl_module']['disableCaptcha'] = array('Sicherheitsfrage deaktivieren', 'Wählen Sie diese Option um die Sicherheitsfrage abzuschalten (nicht empfohlen).');
$GLOBALS['TL_LANG']['tl_module']['protected']      = array('Modul schützen', 'Das Modul nur bestimmten Gruppen anzeigen.');
$GLOBALS['TL_LANG']['tl_module']['guests']         = array('Nur Gästen anzeigen', 'Das Modul verstecken sobald ein Mitglied angemeldet ist.');
$GLOBALS['TL_LANG']['tl_module']['groups']         = array('Erlaubte Mitgliedergruppen', 'Hier können Sie festlegen, welche Mitgliedergruppen das Modul sehen dürfen.');
$GLOBALS['TL_LANG']['tl_module']['space']          = array('Abstand davor und danach', 'Bitte geben Sie den Abstand vor und nach dem Modul in Pixeln ein.');
$GLOBALS['TL_LANG']['tl_module']['align']          = array('Ausrichtung des Moduls', 'Hier können Sie die Ausrichtung des Moduls innerhalb seiner Spalte ändern.');
$GLOBALS['TL_LANG']['tl_module']['cssID']          = array('Stylesheet-ID und -Klasse', 'Hier können Sie eine Stylesheet-ID (id attribute) sowie eine odere mehrere Stylesheet-Klassen (class attribute) eingeben, um das Modul mittles CSS formatieren zu können.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_module']['and']      = array('Finde alle Wörter', 'Die Suchmaschine findet die Seiten, die alle gesuchten Begriffe enthalten.');
$GLOBALS['TL_LANG']['tl_module']['or']       = array('Finde irgendein Wort', 'Die Suchmaschine findet die Seiten, die mindestens einen der Suchbegriffe enthalten.');
$GLOBALS['TL_LANG']['tl_module']['simple']   = array('Einfach', 'Einfaches Suchformular mit nur einem Eingabefeld.');
$GLOBALS['TL_LANG']['tl_module']['advanced'] = array('Erweitert', 'Erweitertes Suchfomular mit dem der Abfragetyp dynamisch geändert werden kann.');
$GLOBALS['TL_LANG']['tl_module']['header']   = 'Kopfzeile';
$GLOBALS['TL_LANG']['tl_module']['left']     = 'Linke Spalte';
$GLOBALS['TL_LANG']['tl_module']['main']     = 'Hauptspalte';
$GLOBALS['TL_LANG']['tl_module']['right']    = 'Rechte Spalte';
$GLOBALS['TL_LANG']['tl_module']['footer']   = 'Fußzeile';
$GLOBALS['TL_LANG']['tl_module']['internal'] = 'interne Datei';
$GLOBALS['TL_LANG']['tl_module']['external'] = 'externe URL';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_module']['new']    = array('Neues Modul', 'Ein neues Modul anlegen');
$GLOBALS['TL_LANG']['tl_module']['show']   = array('Moduldetails', 'Details des Moduls ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_module']['copy']   = array('Modul duplizieren', 'Modul ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_module']['delete'] = array('Modul löschen', 'Modul ID %s löschen');
$GLOBALS['TL_LANG']['tl_module']['edit']   = array('Modul bearbeiten', 'Modul ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_module']['up']     = array('Eine Position nach oben', 'Diesen Eintrag eine Position nach oben verschieben');
$GLOBALS['TL_LANG']['tl_module']['down']   = array('Eine Position nach unten', 'Diesen Eintrag eine Position nach unten verschieben');

?>