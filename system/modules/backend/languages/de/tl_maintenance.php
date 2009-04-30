<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables']  = array('Daten bereinigen', 'Bitte wählen Sie die zu bereinigenden Daten aus.');
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'] = array('Frontend-Benutzer', 'Um geschützte Seiten zu indizieren, muss ein Frontend-Benutzer angemeldet werden.');

/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache']    = 'Daten bereinigen';
$GLOBALS['TL_LANG']['tl_maintenance']['clearTemp']     = 'system/tmp';
$GLOBALS['TL_LANG']['tl_maintenance']['clearHtml']     = 'system/html';
$GLOBALS['TL_LANG']['tl_maintenance']['clearXml']      = 'XML-Sitemaps';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared']  = 'Die Daten wurde bereinigt';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate']    = 'Live Update';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId']  = 'Live Update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate']      = 'Ihre TYPOlight-Version %s ist aktuell';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion']    = 'Eine neuere TYPOlight-Version %s ist verfügbar';
$GLOBALS['TL_LANG']['tl_maintenance']['betaVersion']   = 'Beta-Versionen können nicht per Live Update aktualisiert werden';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId']     = 'Bitte geben Sie Ihre Live Update ID ein';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable']  = 'Das temporäre Verzeichnis (system/tmp) ist nicht beschreibbar';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog']     = 'Changelog aufrufen';
$GLOBALS['TL_LANG']['tl_maintenance']['backupFiles']   = 'Backup der zu aktualisierenden Dateien erstellen';
$GLOBALS['TL_LANG']['tl_maintenance']['showToc']       = 'Die Dateien des Update-Archivs anzeigen';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Aktualisierung starten';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex']   = 'Suchindex neu aufbauen';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit']   = 'Suchindex aufbauen';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable']  = 'Keine durchsuchbaren Seiten gefunden';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote']     = 'Bitte warten Sie bis die Seite vollständig geladen ist, bevor Sie Ihre Arbeit fortsetzen!';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading']  = 'Bitte warten Sie während der Suchindex neu aufgebaut wird.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'Der Suchindex wurde neu aufgebaut. Sie können nun fortfahren.';

?>