<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * Core translations are managed using Transifex. To create a new translation
 * or to help to maintain an existing one, please register at transifex.com.
 * 
 * @link http://help.transifex.com/intro/translating.html
 * @link https://www.transifex.com/projects/p/contao/language/bs/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Contao-Instalacijski alat';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Ulogovanje u Instalacijski alat';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'Instalacijski alat je zaključan';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'Zbog sigurnosnih razloga instalacijski alat je zaključan nakon što je upisana pogrešna lozinka više od tri puta zaredom.Da bi ste ga otključali otvorite lokalni konfiguracijski fajl i postavite <em>installCount</em> na <em>0</em>.';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Lozinka';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Upišite lozinku za instalacijski alat. Ova lozinka nije ista kao lozinka za Contao backend.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Lozinka instalacijskog alata';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'Da bi ste dodatno osigurali skriptu, možete umetnuti <strong>exit;</strong> izjavu u <strong>typolight/install.php</strong> ili možete u potpunosti izbrisati fajl sa vašeg servera. U tom slučaju morate editovati postavke sistema direktno u lokalnom konfiguracijskom fajlu.';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Generiraj ključ za šifriranje';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Ovaj ključ koristi se za pohranjivanje šifrovanih podataka. Ne zaboravite da se šifrovani podaci mogu otvoriti samo s ovim ključem! Stoga ga zapišite i ne mijenjajte ako ste već šifrovali podatke. Ostavite prazno ako želite generirati slučajni ključ.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Provjeri konekciju s bazom podataka';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Upišite parametre konekcije sa vašom bazom podataka.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Uspoređivanje';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'Za dodatne informacije pogledajte <a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" onclick="window.open(this.href); return false;">MySQL upute</a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Ažuriraj tablice baze podataka';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Ne zaboravite da je asistent za ažuriranje testiran samo sa MySQL i MySQLi drajverima. Ako koristite drugačiju bazu podataka (npr. Oracle), možda će te morati ručno instalirati ili ažurirati vašu bazu podataka. U tom slučaju otiđite u <strong>system/modules</strong> i pretražite sve podmape za <strong>config/database.sql</strong> fajlove.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Importiraj templejt';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Odaberite <em>.sql</em> fajl iz mape <em>templates</em>.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Kreiraj admin korisnika';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Ako ste importirali primjer sajta, admin korisničko ime je <strong>k.jones</strong> a lozinka je <strong>kevinjones</strong>. Pogledajte primjer sajta (frontend) za dodatne informacije.';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'Čestitamo!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Sad se možete prijaviti u Contao backend i provjeriti postavke sistema. Zatim posjetite vaš sajt kako bi ste provjerili da on funkcioniše ispravno.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Modifikuj fajlove pomoću FTP-a';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Upišite detalje za vašu FTP prijavu kako bi Contao mogao izmjeniti fajlove putem FTP (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Prihvati licencu';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Contao backend logovanje';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Molimo vas da promijenite standardnu lozinku kako bi spriječili neovlašteni pristup.';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'Standardna lozinka je promijenjena.';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Sačuvaj lozinku';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Kreirajte ključ za šifriranje!';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'Ključ za šifriranje mora imati barem 12 znakova!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'Ključ za šifriranje je kreiran.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Generiraj ključ za šifriranje';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Generiraj ili sačuvaj ključ';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Veza s bazom podataka je uredu.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Konekcija sa bazom podataka nije uspjela!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Drajver';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Host';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Korisničko ime';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'Baza podataka';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Dosljedna veza';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Skup znakova';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Uspoređivanje';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Port broj';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Sačuvaj postavke baze podataka';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Izmjena uspoređivanja utjecat će na sve tablice sa <em>tl_</em> prefiksom.';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'Baza podataka nije ažurna!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'Baza podataka je ažurna!';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Ažuriraj bazu podataka';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Odaberite templejt fajl!';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Templejt je importiran %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'Postojeći podaci bit će izbrisani!';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Templejtovi';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Nemoj izbrisati tablice';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Importiraj templejt';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Postojeći podaci bit će izbrisani! Da li zaista želite nastaviti?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Molimo vas da ispunite sva polja kako bi kreirali admin korisnika!';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'Admin korisnik je kreiran.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Kreiraj admin račun';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Uspješno ste instalirali TYPOlight-Bosansku verziju.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'Ime FTP hosta';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Relativna putanja do TYPOlight mape (npr. <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'FTP korisničko ime';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'FTP lozinka';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Sigurna veza';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Konektuj se putem FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'FTP port';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'Spremi FTP postavke';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Povezivanje s FTP serverom %s nije uspjelo';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Ulogovanje kao "%s" nije uspjelo';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Lociranje TYPOlight direktorija %s nije uspjelo';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Kreiraj nove tablice';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Dodaj nove stupce';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Izmjeni postojeće stupce';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Izbaci postojeće stupce';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Izbaci postojeće tablice';
