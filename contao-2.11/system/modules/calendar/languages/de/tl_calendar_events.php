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
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_calendar_events']['title']        = array('Titel', 'Bitte geben Sie den Event-Titel ein.');
$GLOBALS['TL_LANG']['tl_calendar_events']['alias']        = array('Event-Alias', 'Der Event-Alias ist eine eindeutige Referenz, die anstelle der numerischen Event-ID aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_calendar_events']['author']       = array('Autor', 'Hier können Sie den Autor des Events ändern.');
$GLOBALS['TL_LANG']['tl_calendar_events']['addTime']      = array('Zeit hinzufügen', 'Dem Event eine Start- und Endzeit hinzufügen.');
$GLOBALS['TL_LANG']['tl_calendar_events']['startTime']    = array('Startzeit', 'Bitte geben Sie die Startzeit gemäß des globalen Zeitformats ein.');
$GLOBALS['TL_LANG']['tl_calendar_events']['endTime']      = array('Endzeit', 'Geben Sie dieselbe Start- und Endzeit ein, um ein Event mit offenem Ende zu erstellen.');
$GLOBALS['TL_LANG']['tl_calendar_events']['startDate']    = array('Startdatum', 'Bitte geben Sie das Startdatum gemäß des globalen Datumsformats ein.');
$GLOBALS['TL_LANG']['tl_calendar_events']['endDate']      = array('Enddatum', 'Lassen Sie das Feld leer, um ein eintägiges Event zu erstellen.');
$GLOBALS['TL_LANG']['tl_calendar_events']['teaser']       = array('Teasertext', 'Der Teasertext kann in einer Eventliste anstatt des Event-Textes angezeigt werden. Ein "Weiterlesen …"-Link wird automatisch hinzugefügt.');
$GLOBALS['TL_LANG']['tl_calendar_events']['details']      = array('Event-Text', 'Hier können Sie den Event-Text eingeben.');
$GLOBALS['TL_LANG']['tl_calendar_events']['recurring']    = array('Event wiederholen', 'Ein wiederkehrendes Event erstellen.');
$GLOBALS['TL_LANG']['tl_calendar_events']['repeatEach']   = array('Intervall', 'Hier können Sie das Wiederholungsintervall festlegen.');
$GLOBALS['TL_LANG']['tl_calendar_events']['recurrences']  = array('Wiederholungen', 'Geben Sie 0 ein, um das Event unbefristet zu wiederholen.');
$GLOBALS['TL_LANG']['tl_calendar_events']['addImage']     = array('Ein Bild hinzufügen', 'Dem Event ein Bild hinzufügen.');
$GLOBALS['TL_LANG']['tl_calendar_events']['addEnclosure'] = array('Anlagen hinzufügen', 'Dem Event eine oder mehrere Dateien als Download hinzufügen.');
$GLOBALS['TL_LANG']['tl_calendar_events']['enclosure']    = array('Anlagen', 'Bitte wählen Sie die Dateien aus, die Sie hinzufügen möchten.');
$GLOBALS['TL_LANG']['tl_calendar_events']['source']       = array('Weiterleitungsziel', 'Hier können Sie die Standard-Weiterleitung überschreiben.');
$GLOBALS['TL_LANG']['tl_calendar_events']['default']      = array('Standard', 'Beim Anklicken des "Weiterlesen …"-Links wird der Besucher auf die Standardseite des Kalenders weitergeleitet.');
$GLOBALS['TL_LANG']['tl_calendar_events']['internal']     = array('Seite', 'Beim Anklicken des "Weiterlesen …"-Links wird der Besucher auf eine interne Seite weitergeleitet.');
$GLOBALS['TL_LANG']['tl_calendar_events']['article']      = array('Artikel', 'Beim Anklicken des "Weiterlesen …"-Links wird der Besucher auf einen Artikel weitergeleitet.');
$GLOBALS['TL_LANG']['tl_calendar_events']['external']     = array('Externe URL', 'Beim Anklicken des "Weiterlesen …"-Links wird der Besucher auf eine externe Webseite weitergeleitet.');
$GLOBALS['TL_LANG']['tl_calendar_events']['jumpTo']       = array('Weiterleitungsseite', 'Bitte wählen Sie die Seite aus, zu der Besucher weitergeleitet werden, wenn Sie ein Event anklicken.');
$GLOBALS['TL_LANG']['tl_calendar_events']['articleId']    = array('Artikel', 'Bitte wählen Sie den Artikel aus, zu der Besucher weitergeleitet werden, wenn Sie ein Event anklicken.');
$GLOBALS['TL_LANG']['tl_calendar_events']['cssClass']     = array('CSS-Klasse', 'Hier können Sie eine oder mehrere Klassen eingeben.');
$GLOBALS['TL_LANG']['tl_calendar_events']['noComments']   = array('Kommentare deaktivieren', 'Die Kommentarfunktion für dieses Event deaktivieren.');
$GLOBALS['TL_LANG']['tl_calendar_events']['published']    = array('Event veröffentlichen', 'Das Event auf der Webseite anzeigen.');
$GLOBALS['TL_LANG']['tl_calendar_events']['start']        = array('Anzeigen ab', 'Das Event erst ab diesem Tag auf der Webseite anzeigen.');
$GLOBALS['TL_LANG']['tl_calendar_events']['stop']         = array('Anzeigen bis', 'Das Event nur bis zu diesem Tag auf der Webseite anzeigen.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_calendar_events']['title_legend']     = 'Titel und Autor';
$GLOBALS['TL_LANG']['tl_calendar_events']['date_legend']      = 'Datum und Zeit';
$GLOBALS['TL_LANG']['tl_calendar_events']['teaser_legend']    = 'Teaser';
$GLOBALS['TL_LANG']['tl_calendar_events']['text_legend']      = 'Event-Text';
$GLOBALS['TL_LANG']['tl_calendar_events']['image_legend']     = 'Bild-Einstellungen';
$GLOBALS['TL_LANG']['tl_calendar_events']['recurring_legend'] = 'Wiederholungen';
$GLOBALS['TL_LANG']['tl_calendar_events']['enclosure_legend'] = 'Anlagen';
$GLOBALS['TL_LANG']['tl_calendar_events']['source_legend']    = 'Weiterleitungsziel';
$GLOBALS['TL_LANG']['tl_calendar_events']['expert_legend']    = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_calendar_events']['publish_legend']   = 'Veröffentlichung';

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_calendar_events']['days']     = 'Tag(e)';
$GLOBALS['TL_LANG']['tl_calendar_events']['weeks']    = 'Woche(n)';
$GLOBALS['TL_LANG']['tl_calendar_events']['months']   = 'Monat(e)';
$GLOBALS['TL_LANG']['tl_calendar_events']['years']    = 'Jahr(e)';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_calendar_events']['new']        = array('Neues Event', 'Ein neues Event erstellen');
$GLOBALS['TL_LANG']['tl_calendar_events']['show']       = array('Eventdetails', 'Details des Events ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_calendar_events']['edit']       = array('Event bearbeiten', 'Event ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_calendar_events']['copy']       = array('Event duplizieren', 'Event ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_calendar_events']['cut']        = array('Event verschieben', 'Event ID %s verschieben');
$GLOBALS['TL_LANG']['tl_calendar_events']['delete']     = array('Event löschen', 'Event ID %s löschen');
$GLOBALS['TL_LANG']['tl_calendar_events']['toggle']     = array('Event veröffentlichen/unveröffentlichen', 'Event ID %s veröffentlichen/unveröffentlichen');
$GLOBALS['TL_LANG']['tl_calendar_events']['editheader'] = array('Kalender bearbeiten', 'Die Kalender-Einstellungen bearbeiten');
$GLOBALS['TL_LANG']['tl_calendar_events']['pasteafter'] = array('In diesen Kalender einfügen', 'Nach dem Event ID %s einfügen');

?>