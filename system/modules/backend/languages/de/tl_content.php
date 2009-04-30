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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_content']['invisible']    = array('Unsichtbar', 'Das Element auf der Webseite nicht anzeigen.');
$GLOBALS['TL_LANG']['tl_content']['type']         = array('Elementtyp', 'Bitte wählen sie den Typ des Inhaltselements.');
$GLOBALS['TL_LANG']['tl_content']['headline']     = array('Überschrift', 'Hier können Sie dem Inhaltselement eine Überschrift hinzufügen.');
$GLOBALS['TL_LANG']['tl_content']['text']         = array('Text', 'Sie können HTML-Tags verwenden, um den Text zu formatieren.');
$GLOBALS['TL_LANG']['tl_content']['addImage']     = array('Ein Bild hinzufügen', 'Dem Inhaltselement ein Bild hinzufügen.');
$GLOBALS['TL_LANG']['tl_content']['singleSRC']    = array('Quelldatei', 'Bitte wählen Sie eine Datei oder einen Ordner aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['tl_content']['alt']          = array('Alternativer Text', 'Eine barrierefreie Webseite sollte immer einen alternativen Text für Bilder und Filme mit einer kurzen Beschreibung deren Inhalts enthalten.');
$GLOBALS['TL_LANG']['tl_content']['size']         = array('Bildbreite und Bildhöhe', 'Geben Sie entweder Breite oder Höhe ein, um das Bild zu skalieren, oder beide Werte, um es zu beschneiden.');
$GLOBALS['TL_LANG']['tl_content']['imagemargin']  = array('Bildabstand', 'Hier können Sie den oberen, rechten, unteren und linken Bildabstand eingeben.');
$GLOBALS['TL_LANG']['tl_content']['imageUrl']     = array('Link-Adresse', 'Eine eigene Link-Adresse überschreibt den Lightbox-Link, so dass das Bild nicht mehr in der Großansicht dargestellt werden kann.');
$GLOBALS['TL_LANG']['tl_content']['caption']      = array('Bildunterschrift', 'Hier können Sie einen kurzen Text eingeben, der unterhalb des Bildes angezeigt wird.');
$GLOBALS['TL_LANG']['tl_content']['floating']     = array('Bildausrichtung', 'Bitte legen Sie fest, wie das Bild ausgerichtet werden soll.');
$GLOBALS['TL_LANG']['tl_content']['fullsize']     = array('Großansicht', 'Einen Lightbox-Link zur Großansicht des Bildes hinzufügen.');
$GLOBALS['TL_LANG']['tl_content']['html']         = array('HTML-Code', 'Sie können die Liste der erlaubten HTML-Tags in den Backend-Einstellungen ändern.');
$GLOBALS['TL_LANG']['tl_content']['listtype']     = array('Listentyp', 'Bitte wählen Sie den Typ der Liste.');
$GLOBALS['TL_LANG']['tl_content']['listitems']    = array('Listeneinträge', 'Wenn JavaScript deaktiviert ist, speichern Sie unbedingt Ihre Änderungen bevor Sie die Reihenfolge ändern.');
$GLOBALS['TL_LANG']['tl_content']['tableitems']   = array('Tabelleneinträge', 'Wenn JavaScript deaktiviert ist, speichern Sie unbedingt Ihre Änderungen bevor Sie die Reihenfolge ändern.');
$GLOBALS['TL_LANG']['tl_content']['summary']      = array('Zusammenfassung', 'Bitte geben Sie eine kurze Zusammenfassung des Inhalts und der Struktur der Tabelle ein.');
$GLOBALS['TL_LANG']['tl_content']['thead']        = array('Kopfzeile hinzufügen', 'Die erste Reihe der Tabelle als Kopfzeile verwenden.');
$GLOBALS['TL_LANG']['tl_content']['tfoot']        = array('Fußzeile hinzufügen', 'Die letzte Reihe der Tabelle als Fußzeile verwenden.');
$GLOBALS['TL_LANG']['tl_content']['sortable']     = array('Sortierbare Tabelle', 'Die Tabelle sortierbar machen (benötigt JavaScript und eine Kopfzeile).');
$GLOBALS['TL_LANG']['tl_content']['sortIndex']    = array('Sortierindex', 'Die Nummer der Spalte, nach der standardmäßig sortiert wird.');
$GLOBALS['TL_LANG']['tl_content']['sortOrder']    = array('Sortierreihenfolge', 'Bitte wählen Sie die Sortierreihenfolge aus.');
$GLOBALS['TL_LANG']['tl_content']['mooType']      = array('Betriebsart', 'Bitte wählen Sie die Betriebsart des Akkordeon-Elements.');
$GLOBALS['TL_LANG']['tl_content']['single']       = array('Einzelnes Element', 'Entspricht einem Text-Inhaltselement in einem Akkordeon-Fenster.');
$GLOBALS['TL_LANG']['tl_content']['start']        = array('Umschlag Anfang', 'Markiert den Anfang eines Akkordeon-Fensters, das mehrere Inhaltselemente umfasst.');
$GLOBALS['TL_LANG']['tl_content']['stop']         = array('Umschlag Ende', 'Markiert das Ende eines Akkordeon-Fensters, das mehrere Inhaltselemente umfasst.');
$GLOBALS['TL_LANG']['tl_content']['mooHeadline']  = array('Bereichsüberschrift', 'Bitte geben Sie die Überschrift des Akkordeon-Fensters ein. HTML-Tags sind erlaubt.');
$GLOBALS['TL_LANG']['tl_content']['mooStyle']     = array('CSS-Format', 'Hier können Sie die Bereichsüberschrift mittels CSS-Code formatieren.');
$GLOBALS['TL_LANG']['tl_content']['mooClasses']   = array('Klassennamen', 'Lassen Sie das Feld leer, um die Standard-Klassennamen zu verwenden, oder geben Sie eigene Toggler- und Accordion-Klassen ein.');
$GLOBALS['TL_LANG']['tl_content']['shClass']      = array('Konfiguration', 'Hier können Sie den Syntax-Highlighter konfigurieren (z.B. <em>gutter: false;</em>).');
$GLOBALS['TL_LANG']['tl_content']['highlight']    = array('Syntaxhervorhebung', 'Bitte wählen Sie eine Skriptsprache aus.');
$GLOBALS['TL_LANG']['tl_content']['code']         = array('Code', 'Beachten Sie, dass der Code nicht ausgeführt wird.');
$GLOBALS['TL_LANG']['tl_content']['linkTitle']    = array('Link-Text', 'Der Link-Text wird anstelle der Link-Adresse angezeigt.');
$GLOBALS['TL_LANG']['tl_content']['embed']        = array('Den Link einbetten', 'Verwenden Sie den Platzhalter "%s", um den Link in einen Text einzubetten (z.B. <em>Für mehr Informationen besuchen Sie %s</em>).');
$GLOBALS['TL_LANG']['tl_content']['useImage']     = array('Einen Bildlink erstellen', 'Ein Bild anstatt des Link-Textes verwenden.');
$GLOBALS['TL_LANG']['tl_content']['multiSRC']     = array('Quelldateien', 'Bitte wählen Sie eine oder mehr Dateien oder Ordner aus der Dateiübersicht. Wenn Sie einen Ordner auswählen, werden die darin enthaltenen Dateien automatisch eingefügt.');
$GLOBALS['TL_LANG']['tl_content']['useHomeDir']   = array('Benutzerverzeichnis verwenden', 'Das Benutzerverzeichnis als Dateiquelle verwenden wenn sich ein Benutzer angemeldet hat.');
$GLOBALS['TL_LANG']['tl_content']['perRow']       = array('Vorschaubilder pro Reihe', 'Die Anzahl an Vorschaubildern pro Reihe.');
$GLOBALS['TL_LANG']['tl_content']['perPage']      = array('Elemente pro Seite', 'Die Anzahl an Elementen pro Seite. Geben Sie 0 ein, um den automatischen Seitenumbruch zu deaktivieren.');
$GLOBALS['TL_LANG']['tl_content']['sortBy']       = array('Sortieren nach', 'Bitte wählen Sie eine Sortierreihenfolge aus.');
$GLOBALS['TL_LANG']['tl_content']['cteAlias']     = array('Bezogenes Inhaltselement', 'Bitte wählen Sie das Inhaltselement aus, das Sie einfügen möchten.');
$GLOBALS['TL_LANG']['tl_content']['articleAlias'] = array('Bezogener Artikel', 'Bitte wählen Sie den Artikel aus, den Sie einfügen möchten.');
$GLOBALS['TL_LANG']['tl_content']['article']      = array('Artikel', 'Bitte wählen Sie einen Artikel aus.');
$GLOBALS['TL_LANG']['tl_content']['form']         = array('Formular', 'Bitte wählen Sie ein Formular aus.');
$GLOBALS['TL_LANG']['tl_content']['module']       = array('Modul', 'Bitte wählen Sie ein Modul aus.');
$GLOBALS['TL_LANG']['tl_content']['protected']    = array('Element schützen', 'Das Inhaltselement nur bestimmten Gruppen anzeigen.');
$GLOBALS['TL_LANG']['tl_content']['groups']       = array('Erlaubte Mitgliedergruppen', 'Diese Gruppen können das Inhaltselement sehen.');
$GLOBALS['TL_LANG']['tl_content']['guests']       = array('Nur Gästen anzeigen', 'Das Inhaltselement verstecken sobald ein Mitglied angemeldet ist.');
$GLOBALS['TL_LANG']['tl_content']['cssID']        = array('CSS-Id/Klasse', 'Hier können Sie eine Id und beliebig viele Klassen eingeben.');
$GLOBALS['TL_LANG']['tl_content']['space']        = array('Abstand davor und dahinter', 'Hier können Sie den Abstand vor und nach dem Inhaltselement in Pixeln eigeben. Sie sollten Inline-Styles jedoch nach Möglichkeit vermeiden und den Abstand in einem Stylesheet definieren.');
$GLOBALS['TL_LANG']['tl_content']['source']       = array('Quelldateien', 'Bitte wählen Sie die zu importierenden CSV-Dateien aus der Dateiübersicht.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_content']['type_legend']      = 'Elementtyp';
$GLOBALS['TL_LANG']['tl_content']['text_legend']      = 'Text/HTML/Code';
$GLOBALS['TL_LANG']['tl_content']['image_legend']     = 'Bild-Einstellungen';
$GLOBALS['TL_LANG']['tl_content']['list_legend']      = 'Listeneinträge';
$GLOBALS['TL_LANG']['tl_content']['table_legend']     = 'Tabelleneinträge';
$GLOBALS['TL_LANG']['tl_content']['tconfig_legend']   = 'Tabellenkonfiguration';
$GLOBALS['TL_LANG']['tl_content']['sortable_legend']  = 'Sortieroptionen';
$GLOBALS['TL_LANG']['tl_content']['moo_legend']       = 'Akkordeon-Einstellungen';
$GLOBALS['TL_LANG']['tl_content']['link_legend']      = 'Hyperlink-Einstellungen';
$GLOBALS['TL_LANG']['tl_content']['imglink_legend']   = 'Bildlink-Einstellungen';
$GLOBALS['TL_LANG']['tl_content']['source_legend']    = 'Dateien und Ordner';
$GLOBALS['TL_LANG']['tl_content']['dwnconfig_legend'] = 'Download-Einstellungen';
$GLOBALS['TL_LANG']['tl_content']['include_legend']   = 'Include-Einstellungen';
$GLOBALS['TL_LANG']['tl_content']['protected_legend'] = 'Zugriffsschutz';
$GLOBALS['TL_LANG']['tl_content']['expert_legend']    = 'Experten-Einstellungen';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_content']['ordered']   = 'nummerierte Liste';
$GLOBALS['TL_LANG']['tl_content']['unordered'] = 'unnummerierte Liste';
$GLOBALS['TL_LANG']['tl_content']['name_asc']  = 'Dateiname (aufsteigend)';
$GLOBALS['TL_LANG']['tl_content']['name_desc'] = 'Dateiname (absteigend)';
$GLOBALS['TL_LANG']['tl_content']['date_asc']  = 'Datum (aufsteigend)';
$GLOBALS['TL_LANG']['tl_content']['date_desc'] = 'Datum (absteigend)';
$GLOBALS['TL_LANG']['tl_content']['meta']      = 'Meta Datei (meta.txt)';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_content']['new']         = array('Neues Element', 'Ein neues Element erstellen');
$GLOBALS['TL_LANG']['tl_content']['show']        = array('Elementdetails', 'Details des Inhaltselements ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_content']['cut']         = array('Element verschieben', 'Inhaltselement ID %s verschieben');
$GLOBALS['TL_LANG']['tl_content']['copy']        = array('Element duplizieren', 'Inhaltselement ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_content']['delete']      = array('Element löschen', 'Inhaltselement ID %s löschen');
$GLOBALS['TL_LANG']['tl_content']['edit']        = array('Element bearbeiten', 'Inhaltselement ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_content']['editheader']  = array('Artikel bearbeiten', 'Die Artikel-Einstellungen bearbeiten');
$GLOBALS['TL_LANG']['tl_content']['pasteafter']  = array('Oben einfügen', 'Nach dem Inhaltselement ID %s einfügen');
$GLOBALS['TL_LANG']['tl_content']['pastenew']    = array('Neues Element oben erstellen', 'Neues Element nach dem Inhaltselement ID %s erstellen');
$GLOBALS['TL_LANG']['tl_content']['up']          = array('Eine Position nach oben', 'Den Eintrag eine Position nach oben verschieben');
$GLOBALS['TL_LANG']['tl_content']['down']        = array('Eine Position nach unten', 'Den Eintrag eine Position nach unten verschieben');
$GLOBALS['TL_LANG']['tl_content']['toggle']      = array('Sichtbarkeit ändern', 'Die Sichtbarkeit des Inhaltselements ID %s ändern');
$GLOBALS['TL_LANG']['tl_content']['editalias']   = array('Quellelement bearbeiten', 'Das Quellelement ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_content']['editarticle'] = array('Artikel bearbeiten', 'Artikel ID %s bearbeiten');


/**
 * Table wizard
 */
$GLOBALS['TL_LANG']['tl_content']['importList']  = array('CSV-Import', 'Listeneinträge aus einer CSV-Datei importieren');
$GLOBALS['TL_LANG']['tl_content']['importTable'] = array('CSV-Import', 'Tabelleneinträge aus einer CSV-Datei importieren');
$GLOBALS['TL_LANG']['tl_content']['rcopy']       = array('Reihe duplizieren', 'Die Reihe duplizieren');
$GLOBALS['TL_LANG']['tl_content']['rup']         = array('Eine Position nach oben', 'Die Reihe eine Position nach oben verschieben');
$GLOBALS['TL_LANG']['tl_content']['rdown']       = array('Eine Position nach unten', 'Die Reihe eine Position nach unten verschieben');
$GLOBALS['TL_LANG']['tl_content']['rdelete']     = array('Reihe löschen', 'Die Reihe löschen');
$GLOBALS['TL_LANG']['tl_content']['ccopy']       = array('Spalte duplizieren', 'Die Spalte duplizieren');
$GLOBALS['TL_LANG']['tl_content']['cmovel']      = array('Eine Position nach links', 'Die Spalte eine Position nach links verschieben');
$GLOBALS['TL_LANG']['tl_content']['cmover']      = array('Eine Position nach rechts', 'Die Spalte eine Position nach rechts verschieben');
$GLOBALS['TL_LANG']['tl_content']['cdelete']     = array('Spalte löschen', 'Die Spalte löschen');

?>