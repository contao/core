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
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables']  = array('Cache leeren', 'Bitte wählen Sie die zu leerenden Ressourcen.');
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'] = array('Frontend-Benutzer', 'Um geschützte Seiten zu indizieren, müssen Sie einen Frontend-Benutzer anlegen, der auf diese Seiten zugreifen darf.');

/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate']   = 'Live Update';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache']   = 'Cache leeren';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'Der Cache wurde geleert';
$GLOBALS['TL_LANG']['tl_maintenance']['clearTemp']    = 'Temporärer Ordner';
$GLOBALS['TL_LANG']['tl_maintenance']['clearHtml']    = 'Ordner system/html';
$GLOBALS['TL_LANG']['tl_maintenance']['clearXml']     = 'XML-Sitemaps neu erstellen';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate']     = 'Ihre TYPOlight-Version %s ist aktuell';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion']   = 'Es ist eine neuere TYPOlight-Version %s verfügbar';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog']    = 'Changelog aufrufen';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex']  = 'Suchindex neu aufbauen';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit']  = 'Suchindex aufbauen';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'Live Update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['backupFiles']  = 'Backup der zu aktualisierenden Dateien erstellen';
$GLOBALS['TL_LANG']['tl_maintenance']['showToc']      = 'Die Dateien des Update-Archivs anzeigen';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'Das temporäre Verzeichnis (system/tmp) ist nicht beschreibbar';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId']    = 'Bitte geben Sie Ihre Live Update ID ein';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'Keine durchsuchbaren Seiten gefunden';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote']    = 'Bitte warten Sie bis die Seite vollständig geladen ist, bevor Sie Ihre Arbeit fortsetzen!';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Aktualisierung starten';

?>