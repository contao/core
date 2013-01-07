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
 * @link https://www.transifex.com/projects/p/contao/language/hu/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'Adatok törlése';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = 'Jelölje ki a törlendő adatokat.';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'Front end felhasználó';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = 'Automatikusan belépteti a regisztrált felhasználót a védett oldalakra.';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = 'Munka';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = 'Leírás';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Adatok törlése';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'Az adatok törölve';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'Online frissítés';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'Online Frissítés ID (azonosító)';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'Ugrás az Online frissítéshez';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'A Contao verziója %s a legfrissebb';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'Újabb Contao verzió érhető el: %s';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Kérem adja meg az online frissítési ID-t (azonosítót)';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'Az átmeneti tároló mappa (system/tmp) írásvédett';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'Tekintse meg a változtatási napló';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Frissítés futtatása';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Frissítés archívum tartalma';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'Visszaállított fájlok';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'Frissített fájlok';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Kereső index újjáépítése';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Index újjáépítése';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'Nem kereshető oldalak találhatók';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = 'Kérem várjon, míg az oldal betöltődése folyik!';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Kérem várjon, míg a kereső index újjáépül.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'A kereső index újjáépítése kész. Most folytathatja!';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = 'Kérem írja be ide a %s -t';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = 'Keresési index ürítése';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = 'Kiüríti a következő táblákat: <em>tl_search</em>, <em>tl_search_index</em>. Utána újra kell építeni a kereső indexet. (lásd fentebb)';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = 'Visszavonás tábla ürítése';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = 'Kiüríti a <em>tl_undo</em> táblát, mely tárolja a törölt rekordokat. Ezzel véglegesen törli ezeket a bejegyzéseket.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'Verzió táblát ürítése';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'Levágja a <em>tl_version</em> táblát, amely tárolja a korábbi verziók rekordjait. Ezzel véglegesen törli ezeket a bejegyzéseket.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = 'Képek gyorsítótár ürítése';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = 'Eltávolítja az automatikusan generált képeket és megtisztítja az oldal gyorsítótárat, úgy hogy a forrás linkeket nem törli.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'Script gyorsítótár ürítése';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = 'Eltávolítja az automatikusan generált .css és a .js fájlokat, helyreállítja az eredeti stíluslapokat, majd kitisztítja az oldal gyorsítótárat.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'Oldal gyorsítótár ürítése';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'Eltávolítja a felhasználói felület oldalainak tárolt változatát.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = 'Belső gyorsítótár ürítése';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'Eltávolítja a tárolt változatát a DCA és a nyelvi fájloknak. Letilthatja a belső gyorsítótárat az adminisztrációs felület beállítások menüpontjában.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = 'Temp mappa ürítése';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = 'Eltávolítja az átmeneti tárolóból a fájlokat.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'XML fájlok helyreállítása';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'Helyreállítja az XML fájlokat (oldaltérkép és hírfolyam) és törli az oldal gyorsítótárat, úgy hogy a forrás linkeket nem törli.';
