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
 * @link https://www.transifex.com/projects/p/contao/language/sl/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Contao namestitveno orodje';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Vpis v namestitveno orodje';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'Namestitveno orodje je bilo zaklenjeno';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'Zavoljo varnostnih razlogov je bilo namestitveno orodje zaklenjeno, ker ste trikrat zaored vnesli napačno geslo. Da bi ga odklenili, odprite nastavitveno datoteko in nastavite <em>installCount</em> na <em>0</em>.';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Geslo';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Vnesite geslo namestitvenega orodja. Omenjeno geslo NI enako geslu za vpis v Contao administracijo.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Geslo namestitvenega orodja';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'Za dodatno zaščito lahko tudi preimenujete ali v celoti odstranite datoteko <strong>contao/install.php</strong>.';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Ustvari šifrirni ključ';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Šifrirni ključ bo uporabljen za shranjevanje šifriranih podatkov. Vedite, da so lahko ti podatki nato odšifrirani le s tem ključem! Zato vam svetujemo, da si ga zabeležite in ga ne spreminjate, če imate kako vsebino že šifrirano. Pusite prazno za ustvarjanje poljubnega ključa.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Preveri povezavo s podatkovno bazo.';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Prosimo, vnesite parametre za povezavo s podatkovno bazo.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Kodni nabor';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'Za več informacij obiščite <a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" onclick="window.open(this.href); return false;">MySQL priročnik</a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Posodobi tabele podatkovne baze';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Vedite, da je bil pomočnik za posodabljanje preizkušen le z MySQL in MySQLi gonilniki. Če uporabljate drugo podatkovno bazo (npr. Oracle), boste mogoče morali bazo posodobiti ročno. V tem primeru pojdite v <strong>system/modules</strong> in poiščite vse poddirektorije za <strong>config/database.sql</strong> datoteke.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Uvozi predlogo';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Prosimo, izberite <em>.sql</em> datoteko iz imenika <em>templates</em>.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Ustvari skrbniški račun';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'V kolikor ste uvozili vzorčno spletno stran, se uporabniško ime skrbnika glasi <strong>k.jones</strong> in geslo <strong>kevinjones</strong>. Za več informacij si oglejte predstavitveni del (front end) spletne strani.';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'Čestitamo!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Sedaj se, prosimo, vpišite v Contao administracijo (back end) in preverite sistemske nastavitve. Nato obiščite še predstavitveni del (front end) in preverite, da Contao deluje pravilno.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Urejaj datoteke prek FTP protokola';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Prosimo, vnesite podatke za FTP prijavo, da lahko nato Contao spreminja datoteke prek FTP protokola (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Sprejmi licenčne pogoje';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Vpis v Contao administracijo';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Prosimo, spremenite privzeto geslo, da se prepreči nepreverjen dostop!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'Privzeto geslo je bilo spremenjeno.';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Shrani geslo.';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Prosimo, ustvarite šifrirni ključ.';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'Šifrirni ključ mora biti dolg vsaj 12 znakov!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'Šifrirni ključ je bil uspešno ustvarjen.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Ustvari šifrirni ključ.';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Ustvari ali shrani ključ.';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Povezava s podatkovno bazo je bila uspešna.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Povezovanje s podatkovno bazo je bilo neuspešno.';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Gonilnik';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Gostitelj';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Uporabniško ime';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'Podatkovna baza';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Vztrajaj s povezavo';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Nabor znakov';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Kodni nabor';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Številka vrat';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Shrani nastavitve podatkovne baze';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Spreminjanje kodnega nabora bo vplivalo na vse tabele s predpono <em>tl_</em>.';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'Podatkovna baza ni posodobljena!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'Podatkovna baza je posodobljena.';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Posodobi podatkovno bazo';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = 'Spremeni collation';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Vse kaže, da nadgrajujete s Contao verzije pred verzijo %s. Če je to res, <strong>potem je potrebno zagnati posodobitev verzije %s<strong> za zagotovitev integritete vaših podatkov!';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'Zaženi posodobitev verzije %s';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'Zaženi verzijo %s posodobitev - korak %s';
$GLOBALS['TL_LANG']['tl_install']['importException'] = 'Uvoz je spodletel! Preverite, da je struktura podatkovne baze osvežena ter da je datoteka s predlogo skladna z vašo različico Contao.';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Prosimo, izberite datoteko predloge!';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Predloga uvožena na %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'Vsi obstoječi podatki bodo izbrisani!';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Predloge';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Ne prazni (truncate) tabel';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Uvozi predlogo';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Vsi obstoječi podatki bodo izbrisani! Res želite nadaljevati?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Prosimo, izpolnite vsa polja za ustvarjanje skrbniškega računa!';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'Skrbniški račun je bil ustvarjen.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Ustvari skrbniški račun';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Contao je bil uspešno nameščen.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'FTP ime gostitelja';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Relativna pot do Contao imenika (npr. <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'FTP uporabniško ime';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'FTP geslo';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Varna povezava';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Poveži se prek FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'FTP vrata';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'Shrani FTP nastavitve';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Povezave na FTP strežnik %s ni bilo mogoče vzpostaviti';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Ni se bilo mogoče prijaviti kot %s';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Ni bilo mogoče najti Contao imenika %s';
$GLOBALS['TL_LANG']['tl_install']['filesRenamed'] = 'Nastavljen direktorij datotek ne obstaja!';
$GLOBALS['TL_LANG']['tl_install']['filesWarning'] = 'Ali ste preimenovali mapo <strong>tl_files</strong> v <strong>files</strong>? Mape ne morete preimenovati kar tako, ker bodo vse reference datotek v bazi in css-ju še vedno kazale na staro lokacijo. Če želite preimenovati mapo, naredite to po posodobitvi na verzijo 3 in poskrbite, da boste prilagodili podatke baze z naslednjo skripto: %s.';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Ustvari nove tabele';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Dodaj novo vrstico';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Spremeni obstoječe vrstice';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Zavrži obstoječe vrstice';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Zavrži obstoječe tabele';
