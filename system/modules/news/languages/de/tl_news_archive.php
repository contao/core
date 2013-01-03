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
$GLOBALS['TL_LANG']['tl_news_archive']['title']          = array('Titel', 'Bitte geben Sie den Archiv-Titel ein.');
$GLOBALS['TL_LANG']['tl_news_archive']['jumpTo']         = array('Weiterleitungsseite', 'Bitte wählen Sie die Nachrichtenleser-Seite aus, zu der Besucher weitergeleitet werden, wenn Sie einen Beitrag anklicken.');
$GLOBALS['TL_LANG']['tl_news_archive']['allowComments']  = array('Kommentare aktivieren', 'Besuchern das Kommentieren von Nachrichtenbeiträgen erlauben.');
$GLOBALS['TL_LANG']['tl_news_archive']['notify']         = array('Benachrichtigung an', 'Bitte legen Sie fest, wer beim Hinzufügen neuer Kommentare benachrichtigt wird.');
$GLOBALS['TL_LANG']['tl_news_archive']['sortOrder']      = array('Sortierung', 'Standardmäßig werden Kommentare aufsteigend sortiert, beginnend mit dem ältesten.');
$GLOBALS['TL_LANG']['tl_news_archive']['perPage']        = array('Kommentare pro Seite', 'Anzahl an Kommentaren pro Seite. Geben Sie 0 ein, um den automatischen Seitenumbruch zu deaktivieren.');
$GLOBALS['TL_LANG']['tl_news_archive']['moderate']       = array('Kommentare moderieren', 'Kommentare erst nach Bestätigung auf der Webseite veröffentlichen.');
$GLOBALS['TL_LANG']['tl_news_archive']['bbcode']         = array('BBCode erlauben', 'Besuchern das Formatieren ihrer Kommentare mittels BBCode erlauben.');
$GLOBALS['TL_LANG']['tl_news_archive']['requireLogin']   = array('Login zum Kommentieren benötigt', 'Nur angemeldeten Benutzern das Erstellen von Kommentaren erlauben.');
$GLOBALS['TL_LANG']['tl_news_archive']['disableCaptcha'] = array('Sicherheitsfrage deaktivieren', 'Wählen Sie diese Option nur, wenn das Erstellen von Kommentaren auf authentifizierte Benutzer beschränkt ist.');
$GLOBALS['TL_LANG']['tl_news_archive']['protected']      = array('Archiv schützen', 'Nachrichten nur bestimmten Frontend-Gruppen anzeigen.');
$GLOBALS['TL_LANG']['tl_news_archive']['groups']         = array('Erlaubte Mitgliedergruppen', 'Diese Mitgliedergruppen können die Nachrichten des Archivs sehen.');
$GLOBALS['TL_LANG']['tl_news_archive']['makeFeed']       = array('Feed erstellen', 'Einen RSS- oder Atom-Feed aus dem Nachrichtenarchiv generieren.');
$GLOBALS['TL_LANG']['tl_news_archive']['format']         = array('Feed-Format', 'Bitte wählen Sie ein Format.');
$GLOBALS['TL_LANG']['tl_news_archive']['language']       = array('Feed-Sprache', 'Bitte geben Sie die Sprache der Seite gemäß des ISO-639 Standards ein (z.B. <em>de</em>, <em>de-ch</em>).');
$GLOBALS['TL_LANG']['tl_news_archive']['source']         = array('Export-Einstellungen', 'Hier können Sie festlegen, was exportiert werden soll.');
$GLOBALS['TL_LANG']['tl_news_archive']['maxItems']       = array('Maximale Anzahl an Beiträgen', 'Hier können Sie die Anzahl der Beiträge limitieren. Geben Sie 0 ein, um alle zu exportieren.');
$GLOBALS['TL_LANG']['tl_news_archive']['feedBase']       = array('Basis-URL', 'Bitte geben Sie die Basis-URL mit Protokoll (z.B. <em>http://</em>) ein.');
$GLOBALS['TL_LANG']['tl_news_archive']['alias']          = array('Feed-Alias', 'Hier können Sie einen eindeutigen Dateinamen (ohne Endung) eingeben. Die XML-Datei wird automatisch im Wurzelverzeichnis Ihrer Contao-Installation erstellt, z.B. als <em>name.xml</em>.');
$GLOBALS['TL_LANG']['tl_news_archive']['description']    = array('Feed-Beschreibung', 'Bitte geben Sie eine kurze Beschreibung des Nachrichten-Feeds ein.');
$GLOBALS['TL_LANG']['tl_news_archive']['tstamp']         = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news_archive']['title_legend']     = 'Titel und Weiterleitung';
$GLOBALS['TL_LANG']['tl_news_archive']['comments_legend']  = 'Kommentare';
$GLOBALS['TL_LANG']['tl_news_archive']['protected_legend'] = 'Zugriffsschutz';
$GLOBALS['TL_LANG']['tl_news_archive']['feed_legend']      = 'RSS/Atom-Feed';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_news_archive']['notify_admin']  = 'Systemadministrator';
$GLOBALS['TL_LANG']['tl_news_archive']['notify_author'] = 'Autor des Beitrags';
$GLOBALS['TL_LANG']['tl_news_archive']['notify_both']   = 'Autor und Systemadministrator';
$GLOBALS['TL_LANG']['tl_news_archive']['source_teaser'] = 'Teasertexte';
$GLOBALS['TL_LANG']['tl_news_archive']['source_text']   = 'Komplette Beiträge';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news_archive']['new']        = array('Neues Archiv', 'Ein neues Archiv erstellen');
$GLOBALS['TL_LANG']['tl_news_archive']['show']       = array('Archivdetails', 'Die Details des Archivs ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_news_archive']['edit']       = array('Archiv bearbeiten', 'Archiv ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_news_archive']['editheader'] = array('Archiv-Einstellungen bearbeiten', 'Einstellungen des Archivs ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_news_archive']['copy']       = array('Archiv duplizieren', 'Archiv ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_news_archive']['delete']     = array('Archiv löschen', 'Archiv ID %s löschen');
$GLOBALS['TL_LANG']['tl_news_archive']['comments']   = array('Kommentare', 'Kommentare des Archivs ID %s anzeigen');

?>