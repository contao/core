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
$GLOBALS['TL_LANG']['tl_module']['name']           = array('Titel', 'Bitte geben Sie den Titel des Moduls ein.');
$GLOBALS['TL_LANG']['tl_module']['headline']       = array('Überschrift', 'Hier können Sie dem Modul eine Überschrift hinzufügen.');
$GLOBALS['TL_LANG']['tl_module']['type']           = array('Modultyp', 'Bitte wählen Sie den Typ des Moduls.');
$GLOBALS['TL_LANG']['tl_module']['levelOffset']    = array('Startlevel', 'Geben Sie einen Wert größer 0 ein, um nur Untermenüpunkte darzustellen.');
$GLOBALS['TL_LANG']['tl_module']['showLevel']      = array('Stoplevel', 'Geben Sie einen Wert größer 0 ein, um die Verschachtelungstiefe des Menüs zu beschränken.');
$GLOBALS['TL_LANG']['tl_module']['hardLimit']      = array('Hard Limit', 'Niemals Menüpunkte jenseits des Stoplevels anzeigen.');
$GLOBALS['TL_LANG']['tl_module']['showProtected']  = array('Geschützte Seiten anzeigen', 'Menüpunkte anzeigen, die sonst nur für angemeldete Benutzer sichtbar sind.');
$GLOBALS['TL_LANG']['tl_module']['defineRoot']     = array('Eine Referenzseite festlegen', 'Dem Modul eine individuelle Quell- oder Zielseite zuweisen.');
$GLOBALS['TL_LANG']['tl_module']['rootPage']       = array('Referenzseite', 'Bitte wählen Sie die Referenzseite aus der Seitenstruktur.');
$GLOBALS['TL_LANG']['tl_module']['navigationTpl']  = array('Navigationstemplate', 'Hier können Sie das Navigationstemplate auswählen.');
$GLOBALS['TL_LANG']['tl_module']['pages']          = array('Seiten', 'Bitte wählen Sie eine oder mehrere Seiten aus der Seitenstruktur.');
$GLOBALS['TL_LANG']['tl_module']['includeRoot']    = array('Wurzelseite als Startpunkt', 'Die Wurzelseite der Seitenstruktur als Ausgangspunkt des Moduls verwenden.');
$GLOBALS['TL_LANG']['tl_module']['showHidden']     = array('Versteckte Seiten anzeigen', 'Menüpunkte anzeigen, die sonst in der Navigation nicht sichtbar sind.');
$GLOBALS['TL_LANG']['tl_module']['customLabel']    = array('Individuelle Bezeichnung', 'Hier können Sie eine individuelle Bezeichnung für das Drop-Down-Menü eingeben.');
$GLOBALS['TL_LANG']['tl_module']['autologin']      = array('Autologin erlauben', 'Mitgliedern die automatische Anmeldung im Frontend erlauben.');
$GLOBALS['TL_LANG']['tl_module']['jumpTo']         = array('Weiterleitungsseite', 'Bitte wählen Sie die Seite aus, zu der Besucher beim Anklicken eines Links oder Abschicken eines Formulars weitergeleitet werden.');
$GLOBALS['TL_LANG']['tl_module']['redirectBack']   = array('Zur zuletzt besuchten Seite', 'Den Benutzer zurück zu der zuletzt besuchten Seiten anstatt der Weiterleitungsseite leiten.');
$GLOBALS['TL_LANG']['tl_module']['cols']           = array('Anzahl an Spalten', 'Bitte wählen Sie die Spaltenanzahl des Formulars.');
$GLOBALS['TL_LANG']['tl_module']['1cl']            = array('Eine Spalte', 'Die Bezeichnung wird über dem Eingabefeld angezeigt.');
$GLOBALS['TL_LANG']['tl_module']['2cl']            = array('Zwei Spalten', 'Die Bezeichnung wird links neben dem Eingabefeld angezeigt.');
$GLOBALS['TL_LANG']['tl_module']['editable']       = array('Editierbare Felder', 'Diese Felder im Frontend-Formular anzeigen.');
$GLOBALS['TL_LANG']['tl_module']['memberTpl']      = array('Formulartemplate', 'Hier können Sie das Formulartemplate auswählen.');
$GLOBALS['TL_LANG']['tl_module']['tableless']      = array('Tabellenloses Layout', 'Das Formular ohne HTML-Tabellen ausgeben.');
$GLOBALS['TL_LANG']['tl_module']['form']           = array('Formular', 'Bitte wählen Sie ein Formular.');
$GLOBALS['TL_LANG']['tl_module']['queryType']      = array('Standard-Abfragetyp', 'Bitte wählen Sie den Standard-Abfragetyp aus.');
$GLOBALS['TL_LANG']['tl_module']['and']            = array('Finde alle Wörter', 'Findet nur Seiten, die alle gesuchten Begriffe enthalten.');
$GLOBALS['TL_LANG']['tl_module']['or']             = array('Finde irgendein Wort', 'Findet alle Seiten, die einen der Suchbegriffe enthalten.');
$GLOBALS['TL_LANG']['tl_module']['fuzzy']          = array('Ungenaue Suche', 'Findet "Contao" bei der Suche nach "con" (entspricht einer Suche mit Platzhaltern).');
$GLOBALS['TL_LANG']['tl_module']['simple']         = array('Einfaches Formular', 'Enthält nur ein Eingabefeld.');
$GLOBALS['TL_LANG']['tl_module']['advanced']       = array('Erweitertes Formular', 'Enthält ein Eingabefeld und ein Radio-Button-Menü zur Auswahl des Abfragetyps.');
$GLOBALS['TL_LANG']['tl_module']['contextLength']  = array('Kontext-Spannweite', 'Die Anzahl an Buchstaben, die links und rechts des Suchbegriffs als Kontext verwendet werden.');
$GLOBALS['TL_LANG']['tl_module']['totalLength']    = array('Gesamte Kontextlänge', 'Hier können Sie die Gesamtlänge der Kontexts pro Ergebnis beschränken.');
$GLOBALS['TL_LANG']['tl_module']['perPage']        = array('Elemente pro Seite', 'Die Anzahl an Elementen pro Seite. Geben Sie 0 ein, um den automatischen Seitenumbruch zu deaktivieren.');
$GLOBALS['TL_LANG']['tl_module']['searchType']     = array('Suchformular-Layout', 'Bitte wählen Sie das Suchformular-Layout aus.');
$GLOBALS['TL_LANG']['tl_module']['searchTpl']      = array('Ergebnistemplate', 'Hier können Sie das Ergebnistemplate auswählen.');
$GLOBALS['TL_LANG']['tl_module']['inColumn']       = array('Spalte', 'Bitte wählen Sie die Spalte, deren Artikel Sie auflisten möchten.');
$GLOBALS['TL_LANG']['tl_module']['skipFirst']      = array('Elemente überspringen', 'Hier legen Sie fest, wie viele Elemente übersprungen werden sollen.');
$GLOBALS['TL_LANG']['tl_module']['loadFirst']      = array('Erstes Element laden', 'Automatisch zum ersten Element weiterleiten, wenn keines ausgewählt ist.');
$GLOBALS['TL_LANG']['tl_module']['size']           = array('Breite und Höhe', 'Bitte geben Sie die Breite und Höhe in Pixeln ein.');
$GLOBALS['TL_LANG']['tl_module']['transparent']    = array('Transparenter Film', 'Den Flash-Film transparent machen (wmode = transparent).');
$GLOBALS['TL_LANG']['tl_module']['flashvars']      = array('FlashVars', 'Variablen an den Flash-Film übergeben (<em>var1=wert1&amp;var2=wert2</em>).');
$GLOBALS['TL_LANG']['tl_module']['version']        = array('Flash-Player-Version', 'Bitte geben Sie die benötigte Flash-Player-Version ein (z.B. 6.0.12).');
$GLOBALS['TL_LANG']['tl_module']['altContent']     = array('Alternativer Inhalt', 'Der alternative Inhalt wird angezeigt, falls der Flash-Film nicht geladen werden kann. HTML-Tags sind erlaubt.');
$GLOBALS['TL_LANG']['tl_module']['source']         = array('Quelle', 'Eine Datei auf dem Server oder eine externe URL verwenden.');
$GLOBALS['TL_LANG']['tl_module']['singleSRC']      = array('Quelldatei', 'Bitte wählen Sie eine Datei aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['tl_module']['url']            = array('URL', 'Bitte geben Sie die URL (http://…) des Flash-Films ein.');
$GLOBALS['TL_LANG']['tl_module']['interactive']    = array('Interaktiv machen', 'Den Flash-Film mit dem Webbrowser über JavaScript interagieren lassen.');
$GLOBALS['TL_LANG']['tl_module']['flashID']        = array('Flash-Film-ID', 'Bitte geben Sie eine eindeutige Flash-Film-ID ein.');
$GLOBALS['TL_LANG']['tl_module']['flashJS']        = array('JavaScript _DoFSCommand(command, args) {', 'Bitte geben Sie den JavaScript-Code ein.');
$GLOBALS['TL_LANG']['tl_module']['imgSize']        = array('Bildbreite und Bildhöhe', 'Hier können Sie die Abmessungen des Bildes und den Skalierungsmodus festlegen.');
$GLOBALS['TL_LANG']['tl_module']['useCaption']     = array('Bildunterschrift anzeigen', 'Den Bildnamen bzw. die Bildunterschrift unter dem Bild anzeigen.');
$GLOBALS['TL_LANG']['tl_module']['fullsize']       = array('Großansicht/Neues Fenster', 'Großansicht des Bildes in einer Lightbox bzw. den Link in einem neuem Browserfenster öffnen.');
$GLOBALS['TL_LANG']['tl_module']['multiSRC']       = array('Quelldateien', 'Bitte wählen Sie eine oder mehrere Dateien aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['tl_module']['html']           = array('HTML-Code', 'Sie können die Liste der erlaubten HTML-Tags in den Backend-Einstellungen ändern.');
$GLOBALS['TL_LANG']['tl_module']['protected']      = array('Modul schützen', 'Das Modul nur bestimmten Gruppen anzeigen.');
$GLOBALS['TL_LANG']['tl_module']['groups']         = array('Erlaubte Mitgliedergruppen', 'Diese Gruppen können das Modul sehen.');
$GLOBALS['TL_LANG']['tl_module']['guests']         = array('Nur Gästen anzeigen', 'Das Modul verstecken, sobald ein Mitglied angemeldet ist.');
$GLOBALS['TL_LANG']['tl_module']['cssID']          = array('CSS-ID/Klasse', 'Hier können Sie eine ID und beliebig viele Klassen eingeben.');
$GLOBALS['TL_LANG']['tl_module']['space']          = array('Abstand davor und dahinter', 'Hier können Sie den Abstand vor und nach dem Modul in Pixeln eingeben. Sie sollten Inline-Styles jedoch nach Möglichkeit vermeiden und den Abstand in einem Stylesheet definieren.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_module']['title_legend']     = 'Titel und Typ';
$GLOBALS['TL_LANG']['tl_module']['nav_legend']       = 'Menü-Konfiguration';
$GLOBALS['TL_LANG']['tl_module']['reference_legend'] = 'Referenzseite';
$GLOBALS['TL_LANG']['tl_module']['redirect_legend']  = 'Weiterleitung';
$GLOBALS['TL_LANG']['tl_module']['template_legend']  = 'Template-Einstellungen';
$GLOBALS['TL_LANG']['tl_module']['config_legend']    = 'Modul-Konfiguration';
$GLOBALS['TL_LANG']['tl_module']['include_legend']   = 'Include-Einstellungen';
$GLOBALS['TL_LANG']['tl_module']['source_legend']    = 'Dateien und Ordner';
$GLOBALS['TL_LANG']['tl_module']['interact_legend']  = 'Interaktiver Flash-Film';
$GLOBALS['TL_LANG']['tl_module']['html_legend']      = 'Text/HTML';
$GLOBALS['TL_LANG']['tl_module']['protected_legend'] = 'Zugriffsschutz';
$GLOBALS['TL_LANG']['tl_module']['expert_legend']    = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_module']['email_legend']     = 'E-Mail-Einstellungen';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_module']['header']   = 'Kopfzeile';
$GLOBALS['TL_LANG']['tl_module']['left']     = 'Linke Spalte';
$GLOBALS['TL_LANG']['tl_module']['main']     = 'Hauptspalte';
$GLOBALS['TL_LANG']['tl_module']['right']    = 'Rechte Spalte';
$GLOBALS['TL_LANG']['tl_module']['footer']   = 'Fußzeile';
$GLOBALS['TL_LANG']['tl_module']['internal'] = 'Interne Datei';
$GLOBALS['TL_LANG']['tl_module']['external'] = 'Externe URL';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_module']['new']        = array('Neues Modul', 'Ein neues Modul anlegen');
$GLOBALS['TL_LANG']['tl_module']['show']       = array('Moduldetails', 'Details des Moduls ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_module']['edit']       = array('Modul bearbeiten', 'Modul ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_module']['cut']        = array('Modul verschieben ', 'Modul ID %s verschieben');
$GLOBALS['TL_LANG']['tl_module']['copy']       = array('Modul duplizieren', 'Modul ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_module']['delete']     = array('Modul löschen', 'Modul ID %s löschen');
$GLOBALS['TL_LANG']['tl_module']['editheader'] = array('Theme bearbeiten', 'Die Theme-Einstellungen bearbeiten');
$GLOBALS['TL_LANG']['tl_module']['pasteafter'] = array('Hier einfügen', 'Nach dem Modul ID %s einfügen');

?>