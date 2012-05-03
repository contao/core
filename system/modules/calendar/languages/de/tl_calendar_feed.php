<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Calendar
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_calendar_feed']['title']       = array('Titel', 'Bitte geben Sie einen Feed-Titel ein.');
$GLOBALS['TL_LANG']['tl_calendar_feed']['alias']       = array('Feed-Alias', 'Hier können Sie einen eindeutigen Dateinamen (ohne Endung) eingeben. Die XML-Datei wird automatisch im <em>share</em>-Ordner Ihrer Contao-Installation erstellt, z.B. als <em>share/name.xml</em>.');
$GLOBALS['TL_LANG']['tl_calendar_feed']['language']    = array('Feed-Sprache', 'Bitte geben Sie die Sprache der Seite gemäß des ISO-639 Standards ein (z.B. <em>de</em>, <em>de-ch</em>).');
$GLOBALS['TL_LANG']['tl_calendar_feed']['calendars']   = array('Kalender', 'Hier legen Sie fest, welche Kalender in dem Feed enthalten sind.');
$GLOBALS['TL_LANG']['tl_calendar_feed']['format']      = array('Feed-Format', 'Bitte wählen Sie ein Format.');
$GLOBALS['TL_LANG']['tl_calendar_feed']['source']      = array('Export-Einstellungen', 'Hier können Sie festlegen, was exportiert werden soll.');
$GLOBALS['TL_LANG']['tl_calendar_feed']['maxItems']    = array('Maximale Anzahl an Beiträgen', 'Hier können Sie die Anzahl der Beiträge limitieren. Geben Sie 0 ein, um alle zu exportieren.');
$GLOBALS['TL_LANG']['tl_calendar_feed']['feedBase']    = array('Basis-URL', 'Bitte geben Sie die Basis-URL mit Protokoll (z.B. <em>http://</em>) ein.');
$GLOBALS['TL_LANG']['tl_calendar_feed']['description'] = array('Feed-Beschreibung', 'Bitte geben Sie eine kurze Beschreibung des Kalender-Feeds ein.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_calendar_feed']['title_legend']     = 'Titel und Sprache';
$GLOBALS['TL_LANG']['tl_calendar_feed']['calendars_legend'] = 'Kalender';
$GLOBALS['TL_LANG']['tl_calendar_feed']['config_legend']    = 'Feed-Einstellungen';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_calendar_feed']['source_teaser'] = 'Teasertexte';
$GLOBALS['TL_LANG']['tl_calendar_feed']['source_text']   = 'Komplette Beiträge';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_calendar_feed']['new']    = array('Neuer Feed', 'Einen neuen Feed erstellen');
$GLOBALS['TL_LANG']['tl_calendar_feed']['show']   = array('Feeddetails', 'Die Details des Feeds ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_calendar_feed']['edit']   = array('Feed bearbeiten', 'Feed ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_calendar_feed']['copy']   = array('Feed duplizieren', 'Feed ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_calendar_feed']['delete'] = array('Feed löschen', 'Feed ID %s löschen');
