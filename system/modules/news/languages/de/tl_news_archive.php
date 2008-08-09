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
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_news_archive']['title']          = array('Titel', 'Bitte geben Sie einen Titel für das Nachrichtenarchiv ein.');
$GLOBALS['TL_LANG']['tl_news_archive']['tstamp']         = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');
$GLOBALS['TL_LANG']['tl_news_archive']['language']       = array('Sprache', 'Bitte geben Sie die Sprache im RFC3066-Format ein (z.B. <em>en</em>, <em>en-us</em> oder <em>en-cockney</em>).');
$GLOBALS['TL_LANG']['tl_news_archive']['jumpTo']         = array('Weiterleitung zu Seite', 'Bitte wählen Sie die Seite, zu der ein Besucher weitergeleitet werden soll wenn er einen Nachrichtenbeitrag anklickt.');
$GLOBALS['TL_LANG']['tl_news_archive']['allowComments']  = array('Kommentare erlauben', 'Das Kommentieren von Nachrichtenbeiträge erlauben.');
$GLOBALS['TL_LANG']['tl_news_archive']['template']       = array('Kommentarlayout', 'Bitte wählen Sie ein Kommentarlayout. Vorlagen müssen mit <em>com_</em> beginnen.');
$GLOBALS['TL_LANG']['tl_news_archive']['sortOrder']      = array('Sortierung', 'Bitte wählen Sie eine Sortierung.');
$GLOBALS['TL_LANG']['tl_news_archive']['perPage']        = array('Elemente pro Seite', 'Bitte geben Sie die Anzahl an Kommentaren pro Seite ein (0 = Seitenumbruch deaktivieren).');
$GLOBALS['TL_LANG']['tl_news_archive']['moderate']       = array('Moderieren', 'Kommentare bestätigen bevor sie auf der Webseite angezeigt werden.');
$GLOBALS['TL_LANG']['tl_news_archive']['bbcode']         = array('BBCode erlauben', 'Besuchern erlauben, ihre Kommentare mittels BBCode zu formatieren.');
$GLOBALS['TL_LANG']['tl_news_archive']['requireLogin']   = array('Login benötigt', 'Nur angemeldeten Benutzern das Erstellen von Kommentaren erlauben.');
$GLOBALS['TL_LANG']['tl_news_archive']['disableCaptcha'] = array('Sicherheitsfrage deaktivieren', 'Wählen Sie diese Option um die Sicherheitsfrage abzuschalten (nicht empfohlen).');
$GLOBALS['TL_LANG']['tl_news_archive']['protected']      = array('Archiv schützen', 'Nachrichten nur für bestimmte Frontend Gruppen anzeigen.');
$GLOBALS['TL_LANG']['tl_news_archive']['groups']         = array('Erlaubte Mitgliedergruppen', 'Hier können Sie festlegen, welche Mitgliedergruppen die Nachrichten sehen können.');
$GLOBALS['TL_LANG']['tl_news_archive']['makeFeed']       = array('Feed erstellen', 'Einen RSS/Atom-Feed aus dem Nachrichtenarchiv erstellen.');
$GLOBALS['TL_LANG']['tl_news_archive']['format']         = array('Feed-Format', 'Bitte wählen Sie ein Format.');
$GLOBALS['TL_LANG']['tl_news_archive']['description']    = array('Beschreibung', 'Bitte geben Sie eine kurze Beschreibung des Nachrichtenarchivs ein.');
$GLOBALS['TL_LANG']['tl_news_archive']['alias']          = array('Feed-Alias', 'Hier können Sie einen eindeutigen Feednamen eingeben. Es wird automatisch eine XML-Datei im Wurzelverzeichnis Ihrer TYPOlight-Installation erstellt (<em>name.xml</em>).');
$GLOBALS['TL_LANG']['tl_news_archive']['feedBase']       = array('Basis-URL', 'Bitte geben Sie den Basis-URL inklusive Protokoll (z.B. <em>http://</em>) ein.');
$GLOBALS['TL_LANG']['tl_news_archive']['maxItems']       = array('Maximale Anzahl an Beiträgen', 'Limitiert die Anzahl der Beiträge. Lassen Sie das Feld leer oder geben Sie 0 ein, um alle Beiträge zu exportieren.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_news_archive']['ascending']  = 'aufsteigend';
$GLOBALS['TL_LANG']['tl_news_archive']['descending'] = 'absteigend';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news_archive']['new']      = array('Neues Archiv', 'Ein neues Archiv erstellen');
$GLOBALS['TL_LANG']['tl_news_archive']['edit']     = array('Archiv bearbeiten', 'Archiv ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_news_archive']['copy']     = array('Archiv duplizieren', 'Archiv ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_news_archive']['delete']   = array('Archiv löschen', 'Archiv ID %s löschen');
$GLOBALS['TL_LANG']['tl_news_archive']['show']     = array('Archivdetails', 'Die Details des Archivs ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_news_archive']['comments'] = array('Kommentare', 'Kommentare des Archivs ID %s anzeigen');

?>