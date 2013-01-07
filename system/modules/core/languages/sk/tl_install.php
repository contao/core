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
 * @link https://www.transifex.com/projects/p/contao/language/sk/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Inštalačný nástroj Contao';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Prihlásenie k inštalačnému nástroju';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'Inštalačný nástroj bol zablokovaný';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'Z bezpečnostných dôvodou bol inštalačný nástroj zablokovaný, pretože bolo viac než tri krát zadané nesprávne heslo. Pre odblokovanie otvorte lokálny konfiguračný súbor a nastavte <em>installCount</em> na <em>0</em>.';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Heslo';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Vložte prosím heslo pre inštalačný nástroj. Toto heslo je totožné s heslom pre vstup do administrácie Contao.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Heslo k inštalačnému nástroju';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'K dodatočnej ochrane inštalačného skriptu Contao doporučujeme premenovať alebo úplne odstrániť súbor <strong>contao/install.php</strong>.';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Vygenerovať šifrovací kľúč';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Tento kľúč sa používa na ukladanie zašifrovaných dát. Pozor, zašifrované dáta je možné odšifrovať iba týmto kľúčom! Poznačte si ho a nemeňte ho, pokiaľ ste už niektoré dáta zašifrovali. Ponechajte pole prázdne pre vygenerovanie náhodného kľúča.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Spojenie s databázou';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Zadajte prosím nastavenia spojenia s databázou.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Overenie';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'Viac informácií nájdete na <a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" onclick="window.open(this.href); return false;">MySQL manuál</a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Aktualizovať databázové tabuľky';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Upozorňujeme Vás, že aktualizačný asistent bol doteraz otestovaný len s MySQL a MySQLi. Ak používate nejaký iný databázový systém (napr. Oracle), musíte prípadne databázu aktualizovať, resp. nainštalovať ručne. V tomto prípade vyhľadajte vo všetkých podadresároch adresára<strong>system/modules</strong> tieto súbory <strong>config/database.sql</strong>.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Import predlohy (template)';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Tu si môžte vybrať súbor s koncovkou <em>.sql</em> z adresára <em>templates</em>, ktorý obsahuje dáta pre prednastavenú vzorovú stránku. Existujúce dáta budú zmazané! Ak chcete importovať iba tému vzhľadu, prosím urobte to v administračnom rozhraní Contao.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Vytvorenie účtu administrátora';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Ak ste importovali vzorovú stránku (Music Academy), tak užívateľské meno je <strong>k.jones</strong> a heslo je <strong>kevinjones</strong>. Viac sa dozviete po zobrazení vzorovej stránky (frontend).';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'Gratulujeme!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Teraz sa prosím prihláste do administračného rozhrania - <a href="contao/index.php">Contao back end</a> a nastavte celý systém. Potom navštívte svoje stránky, aby ste sa uistili, že systém Contao funguje správne.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Spracovať dáta cez FTP';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Zadajte prosím prístupové údaje k FTP, aby mohol systém Contao spracovať dáta cez FTP (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Súhlasím s licenciou';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Prihlásenie do administračného rozhrania Contao';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Zmeňte prosím prednastavené heslo, aby ste zabránili neautorizovanému prístupu!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'Prednastavené heslo bolo zmenené.';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Uložiť heslo';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Vytvorte prosím šifrovací kľúč!';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'Šifrovací kľúč by mal mať aspoň 12 znakov!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'Šifrovací kľúč bol vytvorený.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Vygenerovať šifrovací kľúč';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Vygenerovať alebo uložiť kľúč';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Spojenie s databázov je v poriadku.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Nepodarilo sa spojiť s databázov!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Typ databázy';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Poskytovateľ (host)';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Užívateľské meno';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'Názov databázy';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Trvalé pripojenie';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Znaková sada (kódovanie)';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Overenie';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Číslo portu';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Uložiť nastavenia pripojenia k databáze';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Zmena Overenia (kolácie) sa týka všetkých tabuliek s <em>tl_</em>.';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'Databáza nie je aktuálna!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'Databáza je aktuálna.';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Aktualizovať databázu';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = 'Zmeniť radenie';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Zdá sa, že akualizujete z verzie nižšej ako %s. V tom prípade, <strong>je nevyhnutné, aby ste vykonali aktualizáciu systému na verziu %s</strong>. Tým zaistíte integráciu vašich dát.';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'Spustiť aktualizáciu na verziu %s';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'Spustiť aktualizáciu verzie %s - krok %s';
$GLOBALS['TL_LANG']['tl_install']['importException'] = 'Import zlyhal! Je štruktúra databázy aktuálna a je súbor šablóny kompatibilný s Vašou verziou systému Contao?';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Vyberte prosím súbor predlohy!';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Predloha importovaná %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'Všetky existujúce dáta budú odstránené!';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Predlohy (templates)';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Nevymazať tabuľky';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Importovať predlohu (template)';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Všetky existujúce dáta budú odstránené! Naozaj chcete pokračovať?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Na vytvorenie účtu administrátora je potrebné vyplniť všetky polia!';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'Účet administrátora bol vytvorený.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Vytvoriť účet administrátora';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Úspešne ste nainštalovali Contao.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'FTP hostiteľ (hostname)';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Relatívna cesta k zložke Contao (napr. <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'FTP užívateľské meno';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'FTP heslo';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Zabezpečené spojenie';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Spojenie cez FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'FTP port';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'Uložiť FTP nastavenia spojenia';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Nedá sa pripojiť k FTP serveru %s';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Prihlásenie ako "%s" sa nepodarilo';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Nedá sa nájsť zložka Contao %s';
$GLOBALS['TL_LANG']['tl_install']['filesRenamed'] = 'Nakonfigurovaná súborová zložka neexistuje!';
$GLOBALS['TL_LANG']['tl_install']['filesWarning'] = 'Premenovali ste adresár <strong>tl_files</strong> na <strong>files</strong>? Nemôžete iba premenovať adresár, pretože všetky  referencie v databáze and kaskádových štýlov by odkazovali na pôvodný názov. Ak chcete premenovať tento adresár, vykonajte tak až po aktualizácii na verziu 3. Následne upravte dáta v databáze pomocou nasledujúceho skriptu %s.';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Vytvoriť nové tabuľky';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Vložiť nové stĺpce';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Zmeniť existujúce stĺpce';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Odstrániť existujúce stĺpce';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Odstrániť existujúce tabuľky';
