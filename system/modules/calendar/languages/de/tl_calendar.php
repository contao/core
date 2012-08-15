<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Calendar
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_calendar']['title']          = array('Titel', 'Bitte geben Sie den Kalender-Titel ein.');
$GLOBALS['TL_LANG']['tl_calendar']['jumpTo']         = array('Weiterleitungsseite', 'Bitte wählen Sie die Eventleser-Seite aus, zu der Besucher weitergeleitet werden, wenn Sie einen Event anklicken.');
$GLOBALS['TL_LANG']['tl_calendar']['allowComments']  = array('Kommentare aktivieren', 'Besuchern das Kommentieren von Events erlauben.');
$GLOBALS['TL_LANG']['tl_calendar']['notify']         = array('Benachrichtigung an', 'Bitte legen Sie fest, wer beim Hinzufügen neuer Kommentare benachrichtigt wird.');
$GLOBALS['TL_LANG']['tl_calendar']['sortOrder']      = array('Sortierung', 'Standardmäßig werden Kommentare aufsteigend sortiert, beginnend mit dem ältesten.');
$GLOBALS['TL_LANG']['tl_calendar']['perPage']        = array('Kommentare pro Seite', 'Anzahl an Kommentaren pro Seite. Geben Sie 0 ein, um den automatischen Seitenumbruch zu deaktivieren.');
$GLOBALS['TL_LANG']['tl_calendar']['moderate']       = array('Kommentare moderieren', 'Kommentare erst nach Bestätigung auf der Webseite veröffentlichen.');
$GLOBALS['TL_LANG']['tl_calendar']['bbcode']         = array('BBCode erlauben', 'Besuchern das Formatieren ihrer Kommentare mittels BBCode erlauben.');
$GLOBALS['TL_LANG']['tl_calendar']['requireLogin']   = array('Login zum Kommentieren benötigt', 'Nur angemeldeten Benutzern das Erstellen von Kommentaren erlauben.');
$GLOBALS['TL_LANG']['tl_calendar']['disableCaptcha'] = array('Sicherheitsfrage deaktivieren', 'Wählen Sie diese Option nur, wenn das Erstellen von Kommentaren auf authentifizierte Benutzer beschränkt ist.');
$GLOBALS['TL_LANG']['tl_calendar']['protected']      = array('Kalender schützen', 'Events nur bestimmten Frontend-Gruppen anzeigen.');
$GLOBALS['TL_LANG']['tl_calendar']['groups']         = array('Erlaubte Mitgliedergruppen', 'Diese Mitgliedergruppen können die Events des Kalenders sehen.');
$GLOBALS['TL_LANG']['tl_calendar']['makeFeed']       = array('Feed erstellen', 'Einen RSS- oder Atom-Feed aus dem Kalender generieren.');
$GLOBALS['TL_LANG']['tl_calendar']['format']         = array('Feed-Format', 'Bitte wählen Sie ein Format.');
$GLOBALS['TL_LANG']['tl_calendar']['language']       = array('Feed-Sprache', 'Bitte geben Sie die Sprache der Seite gemäß des ISO-639 Standards ein (z.B. <em>de</em>, <em>de-ch</em>).');
$GLOBALS['TL_LANG']['tl_calendar']['source']         = array('Export-Einstellungen', 'Hier können Sie festlegen, was exportiert werden soll.');
$GLOBALS['TL_LANG']['tl_calendar']['maxItems']       = array('Maximale Anzahl an Beiträgen', 'Hier können Sie die Anzahl der Events limitieren. Geben Sie 0 ein, um alle zu exportieren.');
$GLOBALS['TL_LANG']['tl_calendar']['feedBase']       = array('Basis-URL', 'Bitte geben Sie die Basis-URL mit Protokoll (z.B. <em>http://</em>) ein.');
$GLOBALS['TL_LANG']['tl_calendar']['alias']          = array('Feed-Alias', 'Hier können Sie einen eindeutigen Dateinamen (ohne Endung) eingeben. Die XML-Datei wird automatisch im Wurzelverzeichnis Ihrer Contao-Installation erstellt, z.B. als <em>name.xml</em>.');
$GLOBALS['TL_LANG']['tl_calendar']['description']    = array('Feed-Beschreibung', 'Bitte geben Sie eine kurze Beschreibung des Kalender-Feeds ein.');
$GLOBALS['TL_LANG']['tl_calendar']['tstamp']         = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_calendar']['title_legend']     = 'Titel und Weiterleitung';
$GLOBALS['TL_LANG']['tl_calendar']['comments_legend']  = 'Kommentare';
$GLOBALS['TL_LANG']['tl_calendar']['protected_legend'] = 'Zugriffsschutz';
$GLOBALS['TL_LANG']['tl_calendar']['feed_legend']      = 'RSS/Atom-Feed';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_calendar']['notify_admin']  = 'Systemadministrator';
$GLOBALS['TL_LANG']['tl_calendar']['notify_author'] = 'Autor des Events';
$GLOBALS['TL_LANG']['tl_calendar']['notify_both']   = 'Autor und Systemadministrator';
$GLOBALS['TL_LANG']['tl_calendar']['source_teaser'] = 'Teasertexte';
$GLOBALS['TL_LANG']['tl_calendar']['source_text']   = 'Komplette Beiträge';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_calendar']['new']        = array('Neuer Kalender', 'Einen neuen Kalender erstellen');
$GLOBALS['TL_LANG']['tl_calendar']['show']       = array('Kalenderdetails', 'Details des Kalenders ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_calendar']['edit']       = array('Kalender bearbeiten', 'Kalender ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_calendar']['editheader'] = array('Kalender-Einstellungen bearbeiten', 'Einstellungen des Kalenders ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_calendar']['copy']       = array('Kalender duplizieren', 'Kalender ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_calendar']['delete']     = array('Kalender löschen', 'Kalender ID %s löschen');
$GLOBALS['TL_LANG']['tl_calendar']['feeds']      = array('RSS-Feeds', 'RSS-Feeds verwalten');
