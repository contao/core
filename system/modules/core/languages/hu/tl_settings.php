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

$GLOBALS['TL_LANG']['tl_settings']['websiteTitle'][0] = 'Honlap címe';
$GLOBALS['TL_LANG']['tl_settings']['websiteTitle'][1] = 'Adja meg a honlap címét.';
$GLOBALS['TL_LANG']['tl_settings']['adminEmail'][0] = 'A rendszer adminisztrátor e-mail címe.';
$GLOBALS['TL_LANG']['tl_settings']['adminEmail'][1] = 'Automatikusan generált üzeneteket fog erre a címre küldeni a rendszer, mint például a feliratkozást visszaigazoló értesítő.';
$GLOBALS['TL_LANG']['tl_settings']['dateFormat'][0] = 'Dátum formátum';
$GLOBALS['TL_LANG']['tl_settings']['dateFormat'][1] = 'A dátum formátumát a PHP date() függvény határozza meg.';
$GLOBALS['TL_LANG']['tl_settings']['timeFormat'][0] = 'Idő formátum';
$GLOBALS['TL_LANG']['tl_settings']['timeFormat'][1] = 'A idő formátumát a PHP date() függvény határozza meg.';
$GLOBALS['TL_LANG']['tl_settings']['datimFormat'][0] = 'Dátum és idő formátum';
$GLOBALS['TL_LANG']['tl_settings']['datimFormat'][1] = 'A dátum és idő formátumát a PHP date() függvény határozza meg.';
$GLOBALS['TL_LANG']['tl_settings']['timeZone'][0] = 'Időzóna';
$GLOBALS['TL_LANG']['tl_settings']['timeZone'][1] = 'Válassza ki a szerver időzónáját.';
$GLOBALS['TL_LANG']['tl_settings']['websitePath'][0] = 'Contao könyvtárának relatív útvonala';
$GLOBALS['TL_LANG']['tl_settings']['websitePath'][1] = 'A Contao könyvtár relatív útvonalát általában automatikusan beállítja a telepítő eszköz.';
$GLOBALS['TL_LANG']['tl_settings']['characterSet'][0] = 'Karakterkészlet';
$GLOBALS['TL_LANG']['tl_settings']['characterSet'][1] = 'Ajánlott az UTF-8 használata, így a különleges karakterek is helyesen jelennek meg.';
$GLOBALS['TL_LANG']['tl_settings']['customSections'][0] = 'Egyéni elrendezésű szakaszok';
$GLOBALS['TL_LANG']['tl_settings']['customSections'][1] = 'Itt megadhatja a vesszővel elválasztott listáját az egyéni elrendezésű szakaszoknak.';
$GLOBALS['TL_LANG']['tl_settings']['disableCron'][0] = 'Tiltsa le a parancs ütemezőt.';
$GLOBALS['TL_LANG']['tl_settings']['disableCron'][1] = 'Tiltsa le az ütemezett parancs végrehajtást és helyette a cron.php script fog végrehajtódni (kézzel kell beállítania).';
$GLOBALS['TL_LANG']['tl_settings']['minifyMarkup'][0] = 'Markup sűrítése';
$GLOBALS['TL_LANG']['tl_settings']['minifyMarkup'][1] = 'Sűrítse a HTML markup-ot mielőtt a böngészönek elküldi (ehez a PHP tidy bővítés szükséges).';
$GLOBALS['TL_LANG']['tl_settings']['gzipScripts'][0] = 'Scriptek tömörítése';
$GLOBALS['TL_LANG']['tl_settings']['gzipScripts'][1] = 'Hozza létre egy tömörített változatát a CSS és JavaScript fájloknak. A .htaccess fájl módosítása szükséges hozzá.';
$GLOBALS['TL_LANG']['tl_settings']['resultsPerPage'][0] = 'Elemek oldalanként';
$GLOBALS['TL_LANG']['tl_settings']['resultsPerPage'][1] = 'Itt adhatja meg a tételek számát oldalanként a back end részen.';
$GLOBALS['TL_LANG']['tl_settings']['maxResultsPerPage'][0] = 'Elemek maximális száma oldalanként';
$GLOBALS['TL_LANG']['tl_settings']['maxResultsPerPage'][1] = 'Ez az általános korlát lép életbe, ha a felhasználó a "minden elem mutatása" opciót választja.';
$GLOBALS['TL_LANG']['tl_settings']['doNotCollapse'][0] = 'Ne zárja össze az elemeket';
$GLOBALS['TL_LANG']['tl_settings']['doNotCollapse'][1] = 'Ne zárja össze az elemeket a back end előnézetnél.';
$GLOBALS['TL_LANG']['tl_settings']['urlSuffix'][0] = 'URL utótag';
$GLOBALS['TL_LANG']['tl_settings']['urlSuffix'][1] = 'Az URL-képző hozzáadódik az URI stringhez a statikus oldal szimulálásához.';
$GLOBALS['TL_LANG']['tl_settings']['cacheMode'][0] = 'Gyorsítótárazás módja';
$GLOBALS['TL_LANG']['tl_settings']['cacheMode'][1] = 'Itt lehet kiválasztani a gyorsítótárazás módját.';
$GLOBALS['TL_LANG']['tl_settings']['privacyAnonymizeIp'][0] = 'IP cím titkosítása';
$GLOBALS['TL_LANG']['tl_settings']['privacyAnonymizeIp'][1] = 'Minden az adatbázisban tárolt IP cím titkosítása, kivéve a <em>tl_session</em> táblázatban (az IP cím biztonsági okokból az üléshez van hozzárendelve).';
$GLOBALS['TL_LANG']['tl_settings']['privacyAnonymizeGA'][0] = 'Google Analytics titkosítás';
$GLOBALS['TL_LANG']['tl_settings']['privacyAnonymizeGA'][1] = 'A Google Analytics-nek küldött IP címek titkosítása.';
$GLOBALS['TL_LANG']['tl_settings']['rewriteURL'][0] = 'URL átírása';
$GLOBALS['TL_LANG']['tl_settings']['rewriteURL'][1] = 'A Contao egy statikus URL-t generál az index.php töredékek nélkül. A "mod_rewrite" funkció bekapcsolásához szükséges a ".htaccess.default" fájl átnevezése ".htaccess"-re és a RewriteBase paraméter beállítása.';
$GLOBALS['TL_LANG']['tl_settings']['addLanguageToUrl'][0] = 'Nyelv hozzáadása az URL-hez';
$GLOBALS['TL_LANG']['tl_settings']['addLanguageToUrl'][1] = 'Adja hozzá a nyelvet első paraméterként az URL-hez. (pl. <em>http://domain.tld/en/</em>).';
$GLOBALS['TL_LANG']['tl_settings']['disableAlias'][0] = 'Oldal alias használatának kikapcsolása';
$GLOBALS['TL_LANG']['tl_settings']['disableAlias'][1] = 'A numerikus ID-t használja egy oldal vagy cikk az alias helyett.';
$GLOBALS['TL_LANG']['tl_settings']['folderUrl'][0] = 'Könyvtár stílusú URLek';
$GLOBALS['TL_LANG']['tl_settings']['folderUrl'][1] = 'Itt engedélyezheti könyvtárszerkezet stílusú oldal álneveket, mint <em> docs/install/download.html</em> az ilyen alak helyett: <em>docs-install-download.html</em>.';
$GLOBALS['TL_LANG']['tl_settings']['allowedTags'][0] = 'Engedélyezett HTML elemek';
$GLOBALS['TL_LANG']['tl_settings']['allowedTags'][1] = 'Itt megadhat egy listát az engedélyezett HTML elemekről, melyek nem lesznek eltávolítva.';
$GLOBALS['TL_LANG']['tl_settings']['debugMode'][0] = 'Hibakereső mód aktiválása';
$GLOBALS['TL_LANG']['tl_settings']['debugMode'][1] = 'Kiírja a futásidejű adatokat a képernyőre, mint például adatbázis-lekérdezések.';
$GLOBALS['TL_LANG']['tl_settings']['bypassCache'][0] = 'Belső gyorsítótár kikerülése';
$GLOBALS['TL_LANG']['tl_settings']['bypassCache'][1] = 'Ne használja a belső gyorsítótár fájlokat (pl.: hasznos, ha kiterjesztéseket fejleszt).';
$GLOBALS['TL_LANG']['tl_settings']['disableRefererCheck'][0] = 'Referer ellenőrzés kikapcsolása';
$GLOBALS['TL_LANG']['tl_settings']['disableRefererCheck'][1] = 'Ne ellenőrizze a referer host címét az űrlap elküldésekor. Az opció bekapcsolás potenciális biztonsági kockázatot jelent!';
$GLOBALS['TL_LANG']['tl_settings']['lockPeriod'][0] = 'Fiók zárolási idő';
$GLOBALS['TL_LANG']['tl_settings']['lockPeriod'][1] = 'A fiók zárolva lesz, ha egymást követő 3 próbálkozás során helytelen jelszó kerül megadásra.';
$GLOBALS['TL_LANG']['tl_settings']['displayErrors'][0] = 'Hibaüzenet megjelenítése';
$GLOBALS['TL_LANG']['tl_settings']['displayErrors'][1] = 'Hibaüzenetek kiíratása a képernyőre (nem javasolt működő, használatban lévő weboldal esetében).';
$GLOBALS['TL_LANG']['tl_settings']['logErrors'][0] = 'Hibaüzenetek naplózása';
$GLOBALS['TL_LANG']['tl_settings']['logErrors'][1] = 'Írja a hibaüzeneteket a hibanapló fájlba (<em>system/logs/error.log</em>).';
$GLOBALS['TL_LANG']['tl_settings']['coreOnlyMode'][0] = 'Futtatás biztonsági üzemmódban';
$GLOBALS['TL_LANG']['tl_settings']['coreOnlyMode'][1] = 'A Contao biztonsági üzemmódban való futtatása, amelyben csak a rendszer modúlokat tölti be.';
$GLOBALS['TL_LANG']['tl_settings']['disableIpCheck'][0] = 'IP ellenőrzés kikapcsolása';
$GLOBALS['TL_LANG']['tl_settings']['disableIpCheck'][1] = 'Ne kösse az egyes session-öket IP címekhez. Biztonsági kockázatot jelent!';
$GLOBALS['TL_LANG']['tl_settings']['allowedDownload'][0] = 'Letölthető fájltípusok';
$GLOBALS['TL_LANG']['tl_settings']['allowedDownload'][1] = 'Itt adhatja meg a vesszővel elválasztott listáját a letölthető fájltípusoknak.';
$GLOBALS['TL_LANG']['tl_settings']['validImageTypes'][0] = 'Kép fájltípusok';
$GLOBALS['TL_LANG']['tl_settings']['validImageTypes'][1] = 'Itt adhatja meg a vesszővel elválasztott listáját a fájltípusoknak, melyek képként kezelhetőek.';
$GLOBALS['TL_LANG']['tl_settings']['editableFiles'][0] = 'Szerkeszthető fájltípusok';
$GLOBALS['TL_LANG']['tl_settings']['editableFiles'][1] = 'Itt adhatja meg a vesszővel elválasztott listáját a forrás szerkesztőben módosítható fájltípusoknak.';
$GLOBALS['TL_LANG']['tl_settings']['templateFiles'][0] = 'Template fájltípusok';
$GLOBALS['TL_LANG']['tl_settings']['templateFiles'][1] = 'Vesszővel elválasztva adja meg a támogatott fájltípusokat.';
$GLOBALS['TL_LANG']['tl_settings']['maxImageWidth'][0] = 'Maximális szélesség a front enden';
$GLOBALS['TL_LANG']['tl_settings']['maxImageWidth'][1] = 'Ha a szélessége egy képnek vagy filmnek meghaladja ezt az értéket, akkor automatikusan ki lesz igazítva.';
$GLOBALS['TL_LANG']['tl_settings']['jpgQuality'][0] = 'JPG miniatűr minősége';
$GLOBALS['TL_LANG']['tl_settings']['jpgQuality'][1] = 'Itt adhatja meg a JPG miniatűr minőségét százalékban.';
$GLOBALS['TL_LANG']['tl_settings']['gdMaxImgWidth'][0] = 'Maximum GD kép szélesség';
$GLOBALS['TL_LANG']['tl_settings']['gdMaxImgWidth'][1] = 'Itt adhatja meg a kép maximális szélességét amelyet a GD könytár feldolgozáskor használ, ha ez lehetséges.';
$GLOBALS['TL_LANG']['tl_settings']['gdMaxImgHeight'][0] = 'Maximum GD kép magasság';
$GLOBALS['TL_LANG']['tl_settings']['gdMaxImgHeight'][1] = 'Itt adhatja meg a kép maximális szélességét amelyet a GD könytár feldolgozáskor használ, ha ez lehetséges.';
$GLOBALS['TL_LANG']['tl_settings']['uploadPath'][0] = 'Fájlok könyvtára';
$GLOBALS['TL_LANG']['tl_settings']['uploadPath'][1] = 'Itt tudja beállítani a relatív elérési utat a Contao fájlok könyvtárához.';
$GLOBALS['TL_LANG']['tl_settings']['uploadTypes'][0] = 'Feltölthető fájltípusok';
$GLOBALS['TL_LANG']['tl_settings']['uploadTypes'][1] = 'Itt adhatja meg a vesszővel elválasztott listáját a feltölthető fájltípusoknak.';
$GLOBALS['TL_LANG']['tl_settings']['uploadFields'][0] = 'Egyidejű fájl feltöltések';
$GLOBALS['TL_LANG']['tl_settings']['uploadFields'][1] = 'Itt tudja megadni az egyidejű fájl feltöltések maximális számát.';
$GLOBALS['TL_LANG']['tl_settings']['maxFileSize'][0] = 'Maximálisan feltölthető fájl méret';
$GLOBALS['TL_LANG']['tl_settings']['maxFileSize'][1] = 'Itt tudja megadni a feltölthető maximális fájlméretet bájtban (1 MB = 1000 kB = 1000000 byte).';
$GLOBALS['TL_LANG']['tl_settings']['imageWidth'][0] = 'Maximális kép szélesség';
$GLOBALS['TL_LANG']['tl_settings']['imageWidth'][1] = 'Itt lehet megadni a feltölthető kép maximális szélességét pixelben.';
$GLOBALS['TL_LANG']['tl_settings']['imageHeight'][0] = 'Maximális kép magasság';
$GLOBALS['TL_LANG']['tl_settings']['imageHeight'][1] = 'Itt lehet megadni a feltölthető kép maximális magasságát pixelben.';
$GLOBALS['TL_LANG']['tl_settings']['enableSearch'][0] = 'Keresés bekapcsolása';
$GLOBALS['TL_LANG']['tl_settings']['enableSearch'][1] = 'Indexeli az oldalakat, lehetővé téve a keresést.';
$GLOBALS['TL_LANG']['tl_settings']['indexProtected'][0] = 'Védett oldalak indexelése';
$GLOBALS['TL_LANG']['tl_settings']['indexProtected'][1] = 'Óvatosan használja ezt a beállítást és mindig zárja ki a személyes információkkal rendelkező oldalakat az indexelésből!';
$GLOBALS['TL_LANG']['tl_settings']['useSMTP'][0] = 'E-mail küldés SMTP-vel';
$GLOBALS['TL_LANG']['tl_settings']['useSMTP'][1] = 'Használja a levelek elküldéséhez az SMTP szervert a PHP mail() függvény helyett.';
$GLOBALS['TL_LANG']['tl_settings']['smtpHost'][0] = 'SMTP hostname';
$GLOBALS['TL_LANG']['tl_settings']['smtpHost'][1] = 'Adja meg az SMTP szerver nevét.';
$GLOBALS['TL_LANG']['tl_settings']['smtpUser'][0] = 'SMTP felhasználónév';
$GLOBALS['TL_LANG']['tl_settings']['smtpUser'][1] = 'Itt lehet megadni a SMTP felhasználónevet.';
$GLOBALS['TL_LANG']['tl_settings']['smtpPass'][0] = 'SMTP jelszó';
$GLOBALS['TL_LANG']['tl_settings']['smtpPass'][1] = 'Itt lehet megadni az SMTP-jelszót.';
$GLOBALS['TL_LANG']['tl_settings']['smtpEnc'][0] = 'SMTP titkosítás';
$GLOBALS['TL_LANG']['tl_settings']['smtpEnc'][1] = 'Itt kiválaszthatja a titkosítási módszert (SSL vagy TLS).';
$GLOBALS['TL_LANG']['tl_settings']['smtpPort'][0] = 'SMTP port száma';
$GLOBALS['TL_LANG']['tl_settings']['smtpPort'][1] = 'Adja meg az SMTP szerver port számát.';
$GLOBALS['TL_LANG']['tl_settings']['inactiveModules'][0] = 'Kikapcsolt kiterjesztések';
$GLOBALS['TL_LANG']['tl_settings']['inactiveModules'][1] = 'Itt tudja kikapcsolni a nem használt kiterjesztéseket.';
$GLOBALS['TL_LANG']['tl_settings']['undoPeriod'][0] = 'Visszavonási lépések tárolási ideje';
$GLOBALS['TL_LANG']['tl_settings']['undoPeriod'][1] = 'Itt adhatja meg a visszavonási lépések tárolási idejét másodpercben (24 óra = 86400 másodperc).';
$GLOBALS['TL_LANG']['tl_settings']['versionPeriod'][0] = 'Verziók tárolási ideje';
$GLOBALS['TL_LANG']['tl_settings']['versionPeriod'][1] = 'Itt adhatja meg a különböző oldal elemek verzióinak tárolási idejét másodpercben (90 nap = 7.776.000 másodperc).';
$GLOBALS['TL_LANG']['tl_settings']['logPeriod'][0] = 'Naplóbejegyzések tárolási ideje';
$GLOBALS['TL_LANG']['tl_settings']['logPeriod'][1] = 'Itt adhatja meg a naplóbejegyzések tárolási idejét másodpercben (14 nap = 1.209.600 másodperc).';
$GLOBALS['TL_LANG']['tl_settings']['sessionTimeout'][0] = 'Munkamenet időtúllépése';
$GLOBALS['TL_LANG']['tl_settings']['sessionTimeout'][1] = 'Itt tudja megadni a maximális élettartamát a munkamenetnek másodpercben (60 perc = 3600 másodperc).';
$GLOBALS['TL_LANG']['tl_settings']['autologin'][0] = 'Az automatikus bejelentkezés időtartama';
$GLOBALS['TL_LANG']['tl_settings']['autologin'][1] = 'Itt lehet beállítani a front end automatikus bejelentkezési határidőt (90 nap = 7776000 másodperc).';
$GLOBALS['TL_LANG']['tl_settings']['defaultUser'][0] = 'Alapértelmezett oldal tulajdonos';
$GLOBALS['TL_LANG']['tl_settings']['defaultUser'][1] = 'Itt választhatja ki azt a felhasználót aki az oldal alapértelmezett tulajdonosa lesz.';
$GLOBALS['TL_LANG']['tl_settings']['defaultGroup'][0] = 'Alapértelmezett oldal csoport';
$GLOBALS['TL_LANG']['tl_settings']['defaultGroup'][1] = 'Itt választhatja ki az oldal tulajdonosának alapértelmezett csoportját.';
$GLOBALS['TL_LANG']['tl_settings']['defaultChmod'][0] = 'Alapértlmezett hozzáférési jogok';
$GLOBALS['TL_LANG']['tl_settings']['defaultChmod'][1] = 'Kérem adja meg az alapértelmezett jogosultságokat az oldalakhoz és cikkekhez.';
$GLOBALS['TL_LANG']['tl_settings']['liveUpdateBase'][0] = 'Online frissítés URL';
$GLOBALS['TL_LANG']['tl_settings']['liveUpdateBase'][1] = 'Itt lehet megadni az Online frissítés URL-t.';
$GLOBALS['TL_LANG']['tl_settings']['title_legend'] = 'Website címe';
$GLOBALS['TL_LANG']['tl_settings']['date_legend'] = 'Dátum és idő';
$GLOBALS['TL_LANG']['tl_settings']['global_legend'] = 'Globális beállítások';
$GLOBALS['TL_LANG']['tl_settings']['backend_legend'] = 'Back end beállítások';
$GLOBALS['TL_LANG']['tl_settings']['frontend_legend'] = 'Front end beállítások';
$GLOBALS['TL_LANG']['tl_settings']['sections_legend'] = 'Elrendezés szakaszok';
$GLOBALS['TL_LANG']['tl_settings']['privacy_legend'] = 'Adatvédelmi beálítások';
$GLOBALS['TL_LANG']['tl_settings']['security_legend'] = 'Biztonsági beállítások';
$GLOBALS['TL_LANG']['tl_settings']['files_legend'] = 'Fájlok és képek';
$GLOBALS['TL_LANG']['tl_settings']['uploads_legend'] = 'Feltöltés beállítások';
$GLOBALS['TL_LANG']['tl_settings']['search_legend'] = 'Kereső motor beállítások';
$GLOBALS['TL_LANG']['tl_settings']['smtp_legend'] = 'SMTP beállítások';
$GLOBALS['TL_LANG']['tl_settings']['ftp_legend'] = 'Safe Mode Hack';
$GLOBALS['TL_LANG']['tl_settings']['modules_legend'] = 'Kikapcsolt kiterjesztések';
$GLOBALS['TL_LANG']['tl_settings']['timeout_legend'] = 'Időtúllépési értékek';
$GLOBALS['TL_LANG']['tl_settings']['chmod_legend'] = 'Alapértelmezett hozzáférési jogok';
$GLOBALS['TL_LANG']['tl_settings']['update_legend'] = 'Online frissítés';
$GLOBALS['TL_LANG']['tl_settings']['static_legend'] = 'Statikus források';
$GLOBALS['TL_LANG']['tl_settings']['edit'] = 'A helyi konfiguráció szerkesztése';
$GLOBALS['TL_LANG']['tl_settings']['both'] = 'A szerver és a böngésző gyorsítótár használata';
$GLOBALS['TL_LANG']['tl_settings']['server'] = 'Csak a szerver gyorsítótár használata';
$GLOBALS['TL_LANG']['tl_settings']['browser'] = 'Csak a böngésző gyorsítótár használata';
$GLOBALS['TL_LANG']['tl_settings']['none'] = 'Gyorsítótár kikapcsolása';
