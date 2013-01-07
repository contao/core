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

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Telepítés';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Telepítő eszköz belépés';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'A telepítő eszköz le van zárva';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'Biztonsági okokból a telepítő eszköz le lett zárva, miután három egymást követő alkalommal hibás jelszó lett beírva. A feloldáshoz nyissa meg a helyi konfigurációs fájlt és állítsa az <em>installCount</em> elemet <em>0</em>-ra.';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Jelszó';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Adja meg a telepítő eszköz jelszavát. A telepítő eszköz jelszava nem azonos a Contao adminisztrációs felület jelszavával.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Telepítő eszköz jelszó';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'A nagyobb biztonság érdekében a <strong>contao/install.php</strong> fájlt átnevezheti vagy teljesen eltávolíthatja.';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Titkosítási kulcs létrehozása';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Ezt a kulcsot használja a tárolt adatok titkosítására. Kérjük, vegye figyelembe, hogy a titkosított adatokat csak ezzel a kulccsal tudja visszafejteni! Ezért jegyezze le, és ne változtassa meg ha már titkosította az adatokat. Ha üresen hegyja generál egy véletlenszerű kulcsot.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Adatbázis kapcsolat ellenőrzése';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Adja meg az adatbázis kapcsolat paramétereit.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Egybevetés';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'További információkért lásd a<a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" target="_blank">MySQL kézikönyvet</a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Adatbázis táblák frissítése';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Kérjük vegye figyelembe, hogy a frissítési asszisztenst csak MySQL és MySQLi rendszerekkel tesztelték. Amennyiben ön más adatbázis-kezelőt használ (pl. Oracle), előfordulhat, hogy manuálisan kell telepítenie vagy frissítenie.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Sablon importálása';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Kérjük válasszon egy <em>.sql</em> fájlt a <em>templates</em> könyvtárból egy előre konfigurált példa honlaphoz. A meglévő adatok törlésre kerülnek! Ha csak a témát kívánja importálni, akkor használja az adminisztrációs felület téma kezelő menüpontját.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Adminisztrátor létrehozása';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Ha ön importálta a példa honlapot, az adminisztrátor felhasználónév <strong>k.jones</strong> és a jelszó <strong>kevinjones</strong>. Tekintse meg a példa honlapot (felhasználói felület) további információért.';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'Gratulálunk!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Kérjük most jelentkezzen be a <a href="contao/">Contao adminisztrációs felületre</a> és ellenőrizze a rendszer beállításait. Látogassa meg a weboldalát, hogy ellenőrizze a Contao megfelelő működését.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Fájlok módosítása FTP-n keresztül.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'A szerver nem támogatja a PHP fájl hozzáférést; valószínűleg az Apache PHP modulja egy másik felhasználó alatt fut. Kérem adja meg az FTP bejelentkezési adatait, hogy a Contao módosíthassa a fájlokat FTP-n keresztül (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Licensz elfogadása';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Contao adminisztrációs felület belépés';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Kérem, írja be a jelszót, hogy megakadályozza a jogosulatlan hozzáférést!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'Az egyéni jelszó be lett állítva.';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Jelszó mentése';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Kérem hozzon létre egy titkosítási kulcsot!';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'A titkosítási kulcs legalább 12 karakter hosszú kell legyen!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'Titkosítási kulcs létrehozva.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Titkosítási kulcs generálása';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Kulcs generálása vagy mentése';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Adatbázis kapcsolat rendben.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Nem sikerült kapcsolódni az adatbázishoz!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Adatbázis-kezelő';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Kiszolgáló';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Felhasználónév';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'Adatbázis';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Állandó kapcsolatot';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Karakterkészlet';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Egybevetés';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Port száma';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Adatbázis beállításainak mentése';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Az egybevetés megváltoztatása hatással lesz az összes <em>tl_</em> előtagú táblára.';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'Az adatbázis nem naprakész!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'Az adatbázis naprakész.';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Adatbázis frissítése';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = 'Módosítás egybevetése';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Úgy tűnik, hogy éppen egy korábbi (%s) Contao verzióról frissít. Ha ez a helyzet <strong>szükséges, hogy futtassa a %s verzió frissítést</strong> az adatok integritásának megőrzése érdekében!';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = '%s verzió frissítés futtatása.';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'Folyamatban a %s verzióra frissítés - lépés: %s';
$GLOBALS['TL_LANG']['tl_install']['importException'] = 'Az importálás sikertelen! Az adatbázis szerkezete naprakész és a sablon kompatibilis az ön Contao verziójával?';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Kérjük, válasszon egy sablon fájl!';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Sablon importálva %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'A meglévő adatok törlésre kerülnek!';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Sablonok';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Ne vágja a táblákat';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Sablon importálása';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'A meglévő adatok törlésre kerülnek! Valóban folytatni akarja?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Kérem töltse ki az összes mezőt az adminisztrátor létrehozásához!';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'Adminisztrátor létrehozva.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Adminisztrátor létrehozása.';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Sikeresen telepítette a Contao rendszert.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'FTP kiszolgáló';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Contao könyvtár relatív elérési útja';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'FTP felhasználónév';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'FTP jelszó';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Biztonságos kapcsolat';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Kapcsolódás FTP-SSL-en keresztül';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'FTP port';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'FTP beállítások mentése';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Nem lehet kapcsolódni az FTP szerverhez %s';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Nem sikerült bejelentkezni mint "%s"';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Nem található Contao könyvtár itt %s';
$GLOBALS['TL_LANG']['tl_install']['filesRenamed'] = 'A beállított fájl könyvtár nem létezik!';
$GLOBALS['TL_LANG']['tl_install']['filesWarning'] = 'Átnevezte a <strong>tl_files</strong> mappát <strong>files</strong> mappává? Nem elegendő csupán átnevezni a könyvtárat, mert a fájl hivatkozások az adatbázisban, és az ön stíluslapjain továbbra is a régi helyre mutatnak. Ha át szeretné nevezni, kérjük a 3-as verzióra frissítés futtatását követően az adatbázis adatait a következő script használatával aktualizálja: %s.';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Új táblák létrehozása';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Új oszlopok hozzáadása';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Meglévő oszlopok módosítása';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Meglévő oszlopok eldobása';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Meglévő táblák eldobása';
