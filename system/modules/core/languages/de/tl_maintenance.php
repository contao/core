<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * Core translations are managed using Transifex. To create a new translation
 * or to help to maintain an existing one, please register at transifex.com.
 * 
 * @link http://help.transifex.com/intro/translating.html
 * @link https://www.transifex.com/projects/p/contao/language/de/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'Daten bereinigen';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = 'Bitte wählen Sie die zu bereinigenden bzw. neu zu erstellenden Daten aus.';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'Frontend-Benutzer';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = 'Um geschützte Seiten zu indizieren, muss ein Frontend-Benutzer angemeldet werden.';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = 'Job';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = 'Beschreibung';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Daten bereinigen';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'Die Daten wurden bereinigt';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'Live Update';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'Live Update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'Zum Live Update';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'Ihre Contao-Version %s ist aktuell';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'Eine neuere Contao-Version %s ist verfügbar';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Bitte geben Sie Ihre Live Update ID ein';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'Das temporäre Verzeichnis (system/tmp) ist nicht beschreibbar';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'Changelog aufrufen';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Aktualisierung starten';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Inhalt des Update-Archivs';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'Gesicherte Dateien';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'Aktualisierte Dateien';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Suchindex neu aufbauen';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Suchindex aufbauen';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'Keine durchsuchbaren Seiten gefunden';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = 'Bitte warten Sie, bis die Seite vollständig geladen ist, bevor Sie Ihre Arbeit fortsetzen!';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Bitte warten Sie, während der Suchindex neu aufgebaut wird.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'Der Suchindex wurde neu aufgebaut. Sie können nun fortfahren.';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = 'Bitte geben Sie Ihre %s ein.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = 'Suchindex löschen';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = 'Leert die Tabellen <em>tl_search</em> und <em>tl_search_index</em>. Anschließend muss der Suchindex neu aufgebaut werden (siehe oben).';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = 'Papierkorb leeren';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = 'Leert die Tabelle <em>tl_undo</em>, in der die gelöschten Datensätze gespeichert werden. Die Daten werden hierdurch endgültig gelöscht.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'Versionen löschen';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'Leert die Tabelle <em>tl_version</em>, in der die Versionen eines Datensatzes gespeichert werden. Die Daten werden hierdurch endgültig gelöscht.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = 'Bildercache leeren';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = 'Löscht die automatisch erstellten Bilder und leert anschließend den Seitencache, damit keine ungültigen Links zurück bleiben.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'Skriptcache leeren';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = 'Löscht die automatisch erstellten .css- und .js-Dateien, schreibt die internen Stylesheets neu und leert anschließend den Seitencache.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'Seitencache leeren';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'Löscht die gespeicherten Versionen der Frontend-Seiten.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = 'Internen Cache leeren';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'Löscht die gespeicherten DCA- und Sprachdateien. Der interne Cache kann in den Backend-Einstellungen dauerhaft deaktiviert werden.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = 'Temp-Ordner leeren';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = 'Löscht die temporären Dateien.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'XML-Dateien neu schreiben';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'Schreibt die XML-Dateien (Sitemaps/Feeds) neu und leert anschließend den Seitencache, damit keine ungültigen Links zurück bleiben.';
