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
$GLOBALS['TL_LANG']['tl_article']['title']      = array('Titel', 'Bitte geben Sie den Titel des Artikels ein.');
$GLOBALS['TL_LANG']['tl_article']['alias']      = array('Artikelalias', 'Der Alias eines Artikels ist eine eindeutige Referenz, die anstelle der Artikel-ID aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_article']['author']     = array('Autor', 'Bitte geben Sie den Namen des Autors ein.');
$GLOBALS['TL_LANG']['tl_article']['inColumn']   = array('Anzeigen in', 'Bitte weisen Sie den Artikel einer Spalte bzw. der Kopf- oder Fußzeile der Seite zu.');
$GLOBALS['TL_LANG']['tl_article']['teaser']     = array('Teasertext', 'Der Teasertext kann automatisch oder mit dem Inhaltselement "Artikelteaser" angezeigt werden.');
$GLOBALS['TL_LANG']['tl_article']['showTeaser'] = array('Teasertext anzeigen', 'Den Teasertext anstatt des Artikels anzeigen wenn es mehrere Artikel gibt.');
$GLOBALS['TL_LANG']['tl_article']['space']      = array('Abstand davor und dahinter', 'Bitte geben Sie den Abstand (in Pixeln) ein, der vor und hinter dem Artikel eingefügt werden soll.');
$GLOBALS['TL_LANG']['tl_article']['cssID']      = array('Stylesheet-ID und -Klasse', 'Hier können Sie eine Stylesheet-ID (id attribute) sowie eine odere mehrere Stylesheet-Klassen (class attribute) eingeben, um den Artikel mittles CSS formatieren zu können.');
$GLOBALS['TL_LANG']['tl_article']['keywords']   = array('Suchbegriffe', 'Sie können mehrere durch Komma getrennte Suchbegriffe eingeben. Suchbegriffe werden von Suchmaschinen verwendet, um eine Seite zu finden. Eine Suchmaschine indiziert normalerweise bis zu 800 Zeichen.');
$GLOBALS['TL_LANG']['tl_article']['printable']  = array('Druckbar', 'Artikel als PDF drucken (Stylesheets vom Medientyp "print" oder "all" werden berücksichtigt).');
$GLOBALS['TL_LANG']['tl_article']['label']      = array('Bezeichnung des Links', 'Hier können Sie eine eigene Bezeichnung oder ein HTML Bildtag einfügen. Lassen Sie das Feld leer, um die voreingestellte Bezeichnung zu verwenden.');
$GLOBALS['TL_LANG']['tl_article']['published']  = array('Veröffentlicht', 'Solange Sie diese Option nicht wählen, ist der Artikel für die Besucher Ihrer Webseite nicht sichtbar.');
$GLOBALS['TL_LANG']['tl_article']['start']      = array('Anzeigen ab', 'Wenn Sie hier ein Datum erfassen, wird der Artikel erst ab diesem Tag angezeigt.');
$GLOBALS['TL_LANG']['tl_article']['stop']       = array('Anzeigen bis', 'Wenn Sie hier ein Datum erfassen, wird der Artikel nur bis zu diesem Tag angezeigt.');
$GLOBALS['TL_LANG']['tl_article']['tstamp']     = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_article']['header'] = 'Kopfzeile';
$GLOBALS['TL_LANG']['tl_article']['left']   = 'Linke Spalte';
$GLOBALS['TL_LANG']['tl_article']['main']   = 'Hauptspalte';
$GLOBALS['TL_LANG']['tl_article']['right']  = 'Rechte Spalte';
$GLOBALS['TL_LANG']['tl_article']['footer'] = 'Fußzeile';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_article']['new']        = array('Neuer Artikel', 'Einen neuen Artikel anlegen');
$GLOBALS['TL_LANG']['tl_article']['show']       = array('Artikeldetails', 'Details des Artikels ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_article']['edit']       = array('Artikel bearbeiten', 'Artikel ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_article']['copy']       = array('Artikel duplizieren', 'Artikel ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_article']['cut']        = array('Artikel verschieben', 'Artikel ID %s verschieben');
$GLOBALS['TL_LANG']['tl_article']['delete']     = array('Artikel löschen', 'Artikel ID %s löschen');
$GLOBALS['TL_LANG']['tl_article']['pasteafter'] = array('Einfügen nach', 'Nach Artikel ID %s einfügen');
$GLOBALS['TL_LANG']['tl_article']['pasteinto']  = array('Einfügen in', 'In Seite ID %s einfügen');

?>