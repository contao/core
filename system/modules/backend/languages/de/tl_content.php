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
$GLOBALS['TL_LANG']['tl_content']['type']         = array('Elementtyp', 'Bitte wählen sie den Typ des aktuellen Inhaltselements.');
$GLOBALS['TL_LANG']['tl_content']['headline']     = array('Überschrift', 'Wenn Sie eine Überschrift eingeben, wird sie zu Beginn des Inhaltselements angezeigt.');
$GLOBALS['TL_LANG']['tl_content']['text']         = array('Text', 'Bitte geben Sie den Text ein. HTML-Tags sind erlaubt.');
$GLOBALS['TL_LANG']['tl_content']['html']         = array('HTML', 'Bitte geben Sie den HTML-Text ein.');
$GLOBALS['TL_LANG']['tl_content']['code']         = array('Code', 'Bitte geben Sie den Code ein.');
$GLOBALS['TL_LANG']['tl_content']['highlight']    = array('Syntaxhervorhebung', 'Bitte wählen Sie eine Skriptsprache.');
$GLOBALS['TL_LANG']['tl_content']['addImage']     = array('Ein Bild hinzufügen', 'Wenn Sie diese Option wählen, wird dem Inhaltselement ein Bild hinzugefügt.');
$GLOBALS['TL_LANG']['tl_content']['floating']     = array('Bildausrichtung', 'Bitte wählen Sie die Ausrichtung des Bildes. Ein Bild kann oberhalb des Textes oder auf der oberen linken oder rechten Seite des Textes angezeigt werden.');
$GLOBALS['TL_LANG']['tl_content']['imagemargin']  = array('Bildabstand', 'Bitte geben Sie den oberen, rechten, unteren und linken Bildabstand sowie die Einheit ein. Der Bildabstand ist der Abstand zwischen einem Bild und seinen benachbarten Elementen.');
$GLOBALS['TL_LANG']['tl_content']['singleSRC']    = array('Quelldatei', 'Bitte wählen Sie eine Datei oder einen Ordner aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['tl_content']['alt']          = array('Alternativer Text', 'Damit Bilder oder Filme barrierefrei dargestellt werden können, sollten sie immer einen alternativen Text mit einer kurzen Beschreibung des Inhaltes enthalten.');
$GLOBALS['TL_LANG']['tl_content']['caption']      = array('Bildunterschrift', 'Wenn Sie hier einen Text eingeben, wird dieser unterhalb des Bildes angezeigt. Lassen Sie das Feld leer, um keine Bildunterschrift zu verwenden.');
$GLOBALS['TL_LANG']['tl_content']['size']         = array('Bildbreite und Bildhöhe', 'Geben Sie entweder die Bildbreite, die Bildhöhe oder beide Werte ein, um die Bildgröße anzupassen. Wenn Sie keine Angaben machen, wird das Bild in seiner Originalgröße angezeigt.');
$GLOBALS['TL_LANG']['tl_content']['fullsize']     = array('Großansicht', 'Wenn Sie diese Option wählen, öffnet sich bei Anklicken des Bildes dessen Großansicht.');
$GLOBALS['TL_LANG']['tl_content']['linkTitle']    = array('Titel des Links', 'Der Titel wird den Besuchern der Webseite anstelle der Zieladresse oder der Quelldatei angezeigt. Wenn Sie einen Bildlink erstellen, wird das verwendete Bild anstelle des Titels angezeigt.');
$GLOBALS['TL_LANG']['tl_content']['useImage']     = array('Bildlink', 'Ein Bild anstatt des Link Titels verwenden.');
$GLOBALS['TL_LANG']['tl_content']['embed']        = array('Den Link einbetten', 'Wenn Sie hier einen Text und den Platzhalter %s eingeben, wird der Link in den Text eingebettet (z.B. wird <em>Besuchen Sie unsere %s!</em> zu <em>Besuchen Sie unsere <u>Firmenseite</u>!</em> sofern <em>Firmenseite</em> der Titel des Links ist).');
$GLOBALS['TL_LANG']['tl_content']['listitems']    = array('Listeneinträge', 'Benutzen Sie die Schaltflächen, um Einträge zu kopieren, zu verschieben oder zu löschen. Wenn Sie ohne JavaScript-Unterstützung arbeiten, sollten Sie Ihre Eingaben speichern bevor Sie die Struktur der Liste verändern!');
$GLOBALS['TL_LANG']['tl_content']['listtype']     = array('Listentyp', 'Bitte legen Sie den Typ der Liste fest.');
$GLOBALS['TL_LANG']['tl_content']['tableitems']   = array('Tabelleneinträge', 'Benutzen Sie die Schaltflächen, um Spalten oder Reihen zu kopieren, zu verschieben oder zu löschen. Wenn Sie ohne JavaScript Unterstützung arbeiten, sollten Sie Ihre Eingaben speichern bevor Sie die Struktur der Tabelle verändern!');
$GLOBALS['TL_LANG']['tl_content']['summary']      = array('Zusammenfassung', 'Eine barrierefreie Tabelle sollte immer eine kurze Zusammenfassung des Inhalts enthalten.');
$GLOBALS['TL_LANG']['tl_content']['thead']        = array('Tabellenkopfzeile', 'Wenn Sie diese Option wählen, wird die erste Reihe der Tabelle als Kopfzeile verwendet.');
$GLOBALS['TL_LANG']['tl_content']['tfoot']        = array('Tabellenfußzeile', 'Wenn Sie diese Option wählen, wird die letzte Reihe der Tabelle als Fußzeile verwendet.');
$GLOBALS['TL_LANG']['tl_content']['sortable']     = array('Sortierbar', 'Diese Option ermöglicht es, die Tabellenspalten anhand der <em>Tabellenkopfzeile</em> zu sortieren.');
$GLOBALS['TL_LANG']['tl_content']['multiSRC']     = array('Quelldateien', 'Bitte wählen Sie alle Dateien und Ordner, die in das Inhaltselement eingefügt werden sollen. Wenn Sie einen Ordner auswählen, werden alle darin enthaltenen Dateien automatisch eingefügt.');
$GLOBALS['TL_LANG']['tl_content']['useHomeDir']   = array('Benutzerverzeichnis verwenden', 'Das Benutzerverzeichnis eines angemeldeten Frontend Benutzers als Dateiquelle verwenden.');
$GLOBALS['TL_LANG']['tl_content']['sortBy']       = array('Sortieren nach', 'Bitte wählen Sie eine Sortierreihenfolge.');
$GLOBALS['TL_LANG']['tl_content']['sortIndex']    = array('Sortierindex', 'Bitte geben Sie die Nummer der Spalte an, nach der standardmäßig sortiert werden soll (erste Spalte = 0!).');
$GLOBALS['TL_LANG']['tl_content']['sortOrder']    = array('Sortierung', 'Bitte wählen Sie die Standard-Sortierung.');
$GLOBALS['TL_LANG']['tl_content']['perRow']       = array('Vorschaubilder pro Reihe', 'Bitte legen Sie fest, wie viele Vorschaubilder pro Reihe angezeigt werden sollen.');
$GLOBALS['TL_LANG']['tl_content']['perPage']      = array('Elemente pro Seite', 'Bitte geben Sie die Anzahl an Elementen pro Seite ein (0 = Seitenumbruch deaktivieren).');
$GLOBALS['TL_LANG']['tl_content']['imageUrl']     = array('Bild verlinken', 'Geben Sie eine vollständige Zieladresse mit Netzwerkprotokoll ein (z.B. <em>http://www.domain.com</em>) um das Bild zu verlinken. Beachten Sie, dass in diesem Fall die Großansicht des Bildes nicht mehr aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_content']['mooType']      = array('Funktion', 'Bitte wählen Sie die Funktion des Elements.');
$GLOBALS['TL_LANG']['tl_content']['mooHeadline']  = array('Überschrift', 'Bitte geben Sie die Überschrift des Akkordeon Inhaltsfeldes ein (HTML-Tags sind erlaubt).');
$GLOBALS['TL_LANG']['tl_content']['mooStyle']     = array('CSS-Format', 'Hier können Sie die Überschrift mittels CSS formatieren.');
$GLOBALS['TL_LANG']['tl_content']['mooClasses']   = array('Klassennamen', 'Wenn Sie mehr als eine Akkordeon-Instanz pro Seite betreiben möchten, müssen Sie die Klassennamen der Toggler und Akkordeon-Elemente ändern. Standardmäßig werden die Klassen <em>toggler</em> und <em>accordion</em> benutzt.');
$GLOBALS['TL_LANG']['tl_content']['cteAlias']     = array('Element-ID', 'Bitte wählen Sie die ID des Inhaltselements, das Sie einfügen möchten.');
$GLOBALS['TL_LANG']['tl_content']['articleAlias'] = array('Artikel-ID', 'Bitte wählen Sie die ID des Artikels, den Sie einfügen möchten.');
$GLOBALS['TL_LANG']['tl_content']['article']      = array('Artikel-ID', 'Bitte wählen Sie die ID des Artikels, den Sie anzeigen möchten.');
$GLOBALS['TL_LANG']['tl_content']['form']         = array('Formular', 'Bitte wählen Sie ein Formular.');
$GLOBALS['TL_LANG']['tl_content']['module']       = array('Modul', 'Bitte wählen Sie das Modul, das Sie in den Artikel einbinden möchten.');
$GLOBALS['TL_LANG']['tl_content']['invisible']    = array('Unsichtbar', 'Das Element ist nicht sichtbar auf Ihrer Webseite.');
$GLOBALS['TL_LANG']['tl_content']['protected']    = array('Element schützen', 'Das Inhaltselement nur bestimmten Gruppen anzeigen.');
$GLOBALS['TL_LANG']['tl_content']['guests']       = array('Nur Gästen anzeigen', 'Das Inhaltselement verstecken sobald ein Mitglied angemeldet ist.');
$GLOBALS['TL_LANG']['tl_content']['groups']       = array('Erlaubte Mitgliedergruppen', 'Hier können Sie festlegen, welche Mitgliedergruppen das Inhaltselement sehen dürfen.');
$GLOBALS['TL_LANG']['tl_content']['align']        = array('Ausrichtung des Inhaltselements', 'Hier können Sie die Ausrichtung des Inhaltselements innerhalb des Artikels ändern.');
$GLOBALS['TL_LANG']['tl_content']['space']        = array('Abstand davor und dahinter', 'Bitte geben Sie den Abstand vor und hinter dem Inhaltselement in Pixeln ein.');
$GLOBALS['TL_LANG']['tl_content']['cssID']        = array('Stylesheet-ID und -Klasse', 'Hier können Sie eine Stylesheet-ID (id attribute) sowie eine odere mehrere Stylesheet-Klassen (class attribute) eingeben, um das Inhaltselement mittles CSS formatieren zu können.');
$GLOBALS['TL_LANG']['tl_content']['source']       = array('Quelldateien', 'Bitte wählen Sie die CSV-Dateien, die Sie importieren möchten.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_content']['single']    = array('Einzelnes Element', 'In dieser Funktion wird das Element direkt in ein Akkordeon-Inhaltsfeld umgewandelt. Sie können Inhalte mittels Rich Text Editor erfassen.');
$GLOBALS['TL_LANG']['tl_content']['start']     = array('Umschlag Anfang', 'Diese Funktion erlaubt es, mehrere Inhaltselemente in einem Akkordeon-Inhaltsfeld darzustellen, indem sie zwischen den Elementen <em>Umschlag Anfang</em> und <em>Umschlag Ende</em> eingefügt werden.');
$GLOBALS['TL_LANG']['tl_content']['stop']      = array('Umschlag Ende', 'Zeigt das Ende des Umschlags an.');
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
$GLOBALS['TL_LANG']['tl_content']['editheader']  = array('Artikelkopf bearbeiten', 'Den Kopf dieses Artikels bearbeiten');
$GLOBALS['TL_LANG']['tl_content']['pasteafter']  = array('Am Anfang einfügen', 'Nach dem Inhaltselement ID %s einfügen');
$GLOBALS['TL_LANG']['tl_content']['pastenew']    = array('Neues Inhaltselement am Anfang erstellen', 'Neues Inhaltselement nach dem Inhaltselement ID %s erstellen');
$GLOBALS['TL_LANG']['tl_content']['up']          = array('Eine Position nach oben', 'Diesen Eintrag eine Position nach oben verschieben');
$GLOBALS['TL_LANG']['tl_content']['down']        = array('Eine Position nach unten', 'Diesen Eintrag eine Position nach unten verschieben');
$GLOBALS['TL_LANG']['tl_content']['toggle']      = array('Element ein- oder ausschalten', 'Inhaltselement ID %s ein- oder ausschalten');
$GLOBALS['TL_LANG']['tl_content']['editalias']   = array('Quellelement bearbeiten', 'Das Quellelement ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_content']['editarticle'] = array('Artikel bearbeiten', 'Artikel ID %s bearbeiten');


/**
 * Table wizard
 */
$GLOBALS['TL_LANG']['tl_content']['importList']  = array('CSV-Import', 'Liste aus einer CSV-Datei importieren');
$GLOBALS['TL_LANG']['tl_content']['importTable'] = array('CSV-Import', 'Tabelle aus einer CSV-Datei importieren');
$GLOBALS['TL_LANG']['tl_content']['rcopy']       = array('Reihe duplizieren', 'Diese Reihe duplizieren');
$GLOBALS['TL_LANG']['tl_content']['rup']         = array('Eine Position nach oben', 'Diese Reihe eine Position nach oben verschieben');
$GLOBALS['TL_LANG']['tl_content']['rdown']       = array('Eine Position nach unten', 'Diese Reihe eine Position nach unten verschieben');
$GLOBALS['TL_LANG']['tl_content']['rdelete']     = array('Reihe löschen', 'Diese Reihe löschen');
$GLOBALS['TL_LANG']['tl_content']['ccopy']       = array('Spalte duplizieren', 'Diese Spalte duplizieren');
$GLOBALS['TL_LANG']['tl_content']['cmovel']      = array('Eine Position nach links', 'Diese Spalte eine Position nach links verschieben');
$GLOBALS['TL_LANG']['tl_content']['cmover']      = array('Eine Position nach rechts', 'Diese Spalte eine Position nach rechts verschieben');
$GLOBALS['TL_LANG']['tl_content']['cdelete']     = array('Spalte löschen', 'Diese Spalte löschen');

?>