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
$GLOBALS['TL_LANG']['tl_article']['title']       = array('Titel', 'Bitte geben Sie den Artikel-Titel ein.');
$GLOBALS['TL_LANG']['tl_article']['alias']       = array('Artikelalias', 'Der Artikelalias ist eine eindeutige Referenz, die anstelle der numerischen Artikel-ID aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_article']['author']      = array('Autor', 'Hier können Sie den Autor des Artikels ändern.');
$GLOBALS['TL_LANG']['tl_article']['inColumn']    = array('Anzeigen in', 'Bitte wählen Sie den Layoutbereich, in dem der Artikel angezeigt werden soll.');
$GLOBALS['TL_LANG']['tl_article']['keywords']    = array('Suchbegriffe', 'Hier können Sie eine Liste kommagetrennter Suchbegriffe eingeben, die von Suchmaschinen wie Google oder Yahoo ausgewertet werden. Suchmaschinen indizieren normalerweise bis zu 800 Zeichen.');
$GLOBALS['TL_LANG']['tl_article']['teaserCssID'] = array('Teaser-CSS-ID/Klasse', 'Hier können Sie eine ID und beliebig viele Klassen für das Teaser-Element eingeben.');
$GLOBALS['TL_LANG']['tl_article']['showTeaser']  = array('Teasertext anzeigen', 'Den Teasertext anzeigen, wenn es mehrere Artikel gibt.');
$GLOBALS['TL_LANG']['tl_article']['teaser']      = array('Teasertext', 'Der Teasertext kann auch mit dem Inhaltselement "Artikelteaser" dargestellt werden.');
$GLOBALS['TL_LANG']['tl_article']['printable']   = array('Syndikation', 'Hier legen Sie fest, welche Optionen verfügbar sind.');
$GLOBALS['TL_LANG']['tl_article']['cssID']       = array('CSS-ID/Klasse', 'Hier können Sie eine ID und beliebig viele Klassen eingeben.');
$GLOBALS['TL_LANG']['tl_article']['space']       = array('Abstand davor und dahinter', 'Hier können Sie den Abstand vor und nach dem Artikel in Pixeln eingeben. Sie sollten Inline-Styles jedoch nach Möglichkeit vermeiden und den Abstand in einem Stylesheet definieren.');
$GLOBALS['TL_LANG']['tl_article']['published']   = array('Artikel veröffentlichen', 'Den Artikel auf der Webseite anzeigen.');
$GLOBALS['TL_LANG']['tl_article']['start']       = array('Anzeigen ab', 'Den Artikel erst ab diesem Tag auf der Webseite anzeigen.');
$GLOBALS['TL_LANG']['tl_article']['stop']        = array('Anzeigen bis', 'Den Artikel nur bis zu diesem Tag auf der Webseite anzeigen.');
$GLOBALS['TL_LANG']['tl_article']['tstamp']      = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_article']['title_legend']   = 'Titel und Autor';
$GLOBALS['TL_LANG']['tl_article']['layout_legend']  = 'Layoutbereich und Suchbegriffe';
$GLOBALS['TL_LANG']['tl_article']['teaser_legend']  = 'Teasertext';
$GLOBALS['TL_LANG']['tl_article']['expert_legend']  = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_article']['publish_legend'] = 'Veröffentlichung';
$GLOBALS['TL_LANG']['tl_article']['print']          = 'Seite drucken';
$GLOBALS['TL_LANG']['tl_article']['pdf']            = 'Artikel als PDF';
$GLOBALS['TL_LANG']['tl_article']['facebook']       = 'Auf Facebook teilen';
$GLOBALS['TL_LANG']['tl_article']['twitter']        = 'Auf Twitter teilen';


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
$GLOBALS['TL_LANG']['tl_article']['editheader'] = array('Artikeleinstellungen bearbeiten', 'Einstellungen des Artikels ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_article']['copy']       = array('Artikel duplizieren', 'Artikel ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_article']['cut']        = array('Artikel verschieben', 'Artikel ID %s verschieben');
$GLOBALS['TL_LANG']['tl_article']['delete']     = array('Artikel löschen', 'Artikel ID %s löschen');
$GLOBALS['TL_LANG']['tl_article']['toggle']     = array('Artikel veröffentlichen/unveröffentlichen', 'Artikel ID %s veröffentlichen/unveröffentlichen');
$GLOBALS['TL_LANG']['tl_article']['pasteafter'] = array('Einfügen nach', 'Nach Artikel ID %s einfügen');
$GLOBALS['TL_LANG']['tl_article']['pasteinto']  = array('Einfügen in', 'In Seite ID %s einfügen');

?>