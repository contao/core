<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_news']['headline']     = array('Titel', 'Bitte geben Sie den Nachrichten-Titel ein.');
$GLOBALS['TL_LANG']['tl_news']['alias']        = array('Nachrichtenalias', 'Der Nachrichtenalias ist eine eindeutige Referenz, die anstelle der numerischen Artikel-ID aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_news']['author']       = array('Autor', 'Hier können Sie den Autor des Beitrags ändern.');
$GLOBALS['TL_LANG']['tl_news']['date']         = array('Datum', 'Bitte geben Sie das Datum gemäß des globalen Datumsformats ein.');
$GLOBALS['TL_LANG']['tl_news']['time']         = array('Uhrzeit', 'Bitte geben Sie die Uhrzeit gemäß des globalen Zeitformats ein.');
$GLOBALS['TL_LANG']['tl_news']['subheadline']  = array('Unterüberschrift', 'Hier können Sie eine Unterüberschrift eingeben.');
$GLOBALS['TL_LANG']['tl_news']['teaser']       = array('Teasertext', 'Der Teasertext kann in einer Nachrichtenliste anstatt des Nachrichtentextes angezeigt werden. Ein "Weiterlesen …"-Link wird automatisch hinzugefügt.');
$GLOBALS['TL_LANG']['tl_news']['text']         = array('Nachrichtentext', 'Hier können Sie den Nachrichtentext eingeben.');
$GLOBALS['TL_LANG']['tl_news']['addImage']     = array('Ein Bild hinzufügen', 'Dem Beitrag ein Bild hinzufügen.');
$GLOBALS['TL_LANG']['tl_news']['addEnclosure'] = array('Anlagen hinzufügen', 'Dem Beitrag eine oder mehrere Dateien als Download hinzufügen.');
$GLOBALS['TL_LANG']['tl_news']['enclosure']    = array('Anlagen', 'Bitte wählen Sie die Dateien aus, die Sie hinzufügen möchten.');
$GLOBALS['TL_LANG']['tl_news']['source']       = array('Weiterleitungsziel', 'Hier können Sie die Standard-Weiterleitung überschreiben.');
$GLOBALS['TL_LANG']['tl_news']['default']      = array('Standard', 'Beim Anklicken des "Weiterlesen …"-Links wird der Besucher auf die Standardseite des Nachrichtenarchivs weitergeleitet.');
$GLOBALS['TL_LANG']['tl_news']['internal']     = array('Seite', 'Beim Anklicken des "Weiterlesen …"-Links wird der Besucher auf eine Seite weitergeleitet.');
$GLOBALS['TL_LANG']['tl_news']['article']      = array('Artikel', 'Beim Anklicken des "Weiterlesen …"-Links wird der Besucher auf einen Artikel weitergeleitet.');
$GLOBALS['TL_LANG']['tl_news']['external']     = array('Externe URL', 'Beim Anklicken des "Weiterlesen …"-Links wird der Besucher auf eine externe Webseite weitergeleitet.');
$GLOBALS['TL_LANG']['tl_news']['jumpTo']       = array('Weiterleitungsseite', 'Bitte wählen Sie die Seite aus, zu der Besucher weitergeleitet werden, wenn Sie einen Beitrag anklicken.');
$GLOBALS['TL_LANG']['tl_news']['articleId']    = array('Artikel', 'Bitte wählen Sie den Artikel aus, zu der Besucher weitergeleitet werden, wenn Sie einen Beitrag anklicken.');
$GLOBALS['TL_LANG']['tl_news']['cssClass']     = array('CSS-Klasse', 'Hier können Sie eine oder mehrere Klassen eingeben.');
$GLOBALS['TL_LANG']['tl_news']['noComments']   = array('Kommentare deaktivieren', 'Die Kommentarfunktion für diesen Nachrichtenbeitrag deaktivieren.');
$GLOBALS['TL_LANG']['tl_news']['featured']     = array('Beitrag hervorheben', 'Den Beitrag in einer Liste hervorgehobener Nachrichten anzeigen.');
$GLOBALS['TL_LANG']['tl_news']['published']    = array('Beitrag veröffentlichen', 'Den Beitrag auf der Webseite anzeigen.');
$GLOBALS['TL_LANG']['tl_news']['start']        = array('Anzeigen ab', 'Den Beitrag erst ab diesem Tag auf der Webseite anzeigen.');
$GLOBALS['TL_LANG']['tl_news']['stop']         = array('Anzeigen bis', 'Den Beitrag nur bis zu diesem Tag auf der Webseite anzeigen.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news']['title_legend']     = 'Titel und Autor';
$GLOBALS['TL_LANG']['tl_news']['date_legend']      = 'Datum und Uhrzeit';
$GLOBALS['TL_LANG']['tl_news']['teaser_legend']    = 'Unterüberschrift und Teaser';
$GLOBALS['TL_LANG']['tl_news']['text_legend']      = 'Nachrichtentext';
$GLOBALS['TL_LANG']['tl_news']['image_legend']     = 'Bild-Einstellungen';
$GLOBALS['TL_LANG']['tl_news']['enclosure_legend'] = 'Anlagen';
$GLOBALS['TL_LANG']['tl_news']['source_legend']    = 'Weiterleitungsziel';
$GLOBALS['TL_LANG']['tl_news']['expert_legend']    = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_news']['publish_legend']   = 'Veröffentlichung';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news']['new']        = array('Neuer Beitrag', 'Einen neuen Nachrichtenbeitrag erstellen');
$GLOBALS['TL_LANG']['tl_news']['show']       = array('Beitragsdetails', 'Die Details des Beitrags ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_news']['edit']       = array('Beitrag bearbeiten', 'Beitrag ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_news']['copy']       = array('Beitrag duplizieren', 'Beitrag ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_news']['cut']        = array('Beitrag verschieben', 'Beitrag ID %s verschieben');
$GLOBALS['TL_LANG']['tl_news']['delete']     = array('Beitrag löschen', 'Beitrag ID %s löschen');
$GLOBALS['TL_LANG']['tl_news']['toggle']     = array('Beitrag veröffentlichen/unveröffentlichen', 'Beitrag ID %s veröffentlichen/unveröffentlichen');
$GLOBALS['TL_LANG']['tl_news']['feature']    = array('Beitrag hervorheben/zurücksetzen', 'Beitrag ID %s hervorheben/zurücksetzen');
$GLOBALS['TL_LANG']['tl_news']['editheader'] = array('Nachrichtenarchiv bearbeiten', 'Die Nachrichtenarchiv-Einstellungen bearbeiten');
$GLOBALS['TL_LANG']['tl_news']['pasteafter'] = array('In dieses Nachrichtenarchiv einfügen', 'Nach dem Beitrag ID %s einfügen');

?>