<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_news_feed']['title']          = array('Titel', 'Bitte geben Sie einen Feed-Titel ein.');
$GLOBALS['TL_LANG']['tl_news_feed']['alias']          = array('Feed-Alias', 'Hier können Sie einen eindeutigen Dateinamen (ohne Endung) eingeben. Die XML-Datei wird automatisch im Wurzelverzeichnis Ihrer Contao-Installation erstellt, z.B. als <em>name.xml</em>.');
$GLOBALS['TL_LANG']['tl_news_feed']['language']       = array('Feed-Sprache', 'Bitte geben Sie die Sprache der Seite gemäß des ISO-639 Standards ein (z.B. <em>de</em>, <em>de-ch</em>).');
$GLOBALS['TL_LANG']['tl_news_feed']['archives']       = array('Nachrichtenarchive', 'Hier legen Sie fest, welche Nachrichtenarchive in dem Feed enthalten sind.');
$GLOBALS['TL_LANG']['tl_news_feed']['format']         = array('Feed-Format', 'Bitte wählen Sie ein Format.');
$GLOBALS['TL_LANG']['tl_news_feed']['source']         = array('Export-Einstellungen', 'Hier können Sie festlegen, was exportiert werden soll.');
$GLOBALS['TL_LANG']['tl_news_feed']['maxItems']       = array('Maximale Anzahl an Beiträgen', 'Hier können Sie die Anzahl der Beiträge limitieren. Geben Sie 0 ein, um alle zu exportieren.');
$GLOBALS['TL_LANG']['tl_news_feed']['feedBase']       = array('Basis-URL', 'Bitte geben Sie die Basis-URL mit Protokoll (z.B. <em>http://</em>) ein.');
$GLOBALS['TL_LANG']['tl_news_feed']['description']    = array('Feed-Beschreibung', 'Bitte geben Sie eine kurze Beschreibung des Nachrichten-Feeds ein.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news_feed']['title_legend']     = 'Titel und Sprache';
$GLOBALS['TL_LANG']['tl_news_feed']['archives_legend']  = 'Nachrichtenarchive';
$GLOBALS['TL_LANG']['tl_news_feed']['config_legend']    = 'Feed-Einstellungen';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_news_feed']['source_teaser'] = 'Teasertexte';
$GLOBALS['TL_LANG']['tl_news_feed']['source_text']   = 'Komplette Beiträge';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news_feed']['new']    = array('Neuer Feed', 'Einen neuen Feed erstellen');
$GLOBALS['TL_LANG']['tl_news_feed']['show']   = array('Feeddetails', 'Die Details des Feeds ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_news_feed']['edit']   = array('Feed bearbeiten', 'Feed ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_news_feed']['copy']   = array('Feed duplizieren', 'Feed ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_news_feed']['delete'] = array('Feed löschen', 'Feed ID %s löschen');

?>