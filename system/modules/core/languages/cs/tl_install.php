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
 * @link https://www.transifex.com/projects/p/contao/language/cs/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Instalační nástroj Contao';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Přihlášení k instalačnímu nástroji';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'Instalační nástroj byl zablokován';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'Z bezpečnostních důvodů byl instalační nástroj zablokován, protože bylo zadáno více než tři krát špatné heslo. Pro odblokování otevřete lokální konfigurační soubor a nastavte <em>installCount</em> na <em>0</em>';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Heslo';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Vložte prosím heslo pro instalační nástroj. Heslo pro instalační nástroj je totožné s heslem pro vstup do administrace Contao.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Heslo k instalačnímu modulu';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'K dodatečné ochraně instalačního skriptu Contao doporučujeme přejmenovat nebo úplně odstranit soubor <strong>contao/install.php</strong>.';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Vytvořit šifrovací klíč';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Tento klíč se používá k ukládání zašifrovaných dat. Pozor, zašifrovaná data lze odšifrovat pouze s tímto klíčem! Poznamenejte si ho a neměňte ho, pokud jste již některá data zašifrovali. Ponechejte pole prázdné pro vygenerování náhodného klíče.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Spojení k databázi';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Zadejte prosím nastavení připojení k databázi.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Ověření';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'Více informací najdete na <a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" onclick="window.open(this.href); return false;">Návod MySQL</a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Aktualizovat databázové tabulky';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Upozorňujeme Vás, že aktualizační asistent byl dosud vyzkoušený jen s MySQL a MySQLi. Používáte-li nějaký jiný databázový systém (např. Oracle), musíte případně databázi aktualizovat, resp. nainstalovat ručně. V tomto případě vyhledejte v podadresářích adresáře <strong>system/modules</strong> tyto soubory <strong>config/database.sql</strong>.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Import předlohy';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Vyberte prosím soubor končící na <em>.sql</em> z adresáře <em>templates</em>.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Vytvoření účtu administrátora';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Importovali-li jste vzorovou stránku, zní Vaše uživatelské jméno <strong>k.jones</strong> a heslo je <strong>kevinjones</strong>. Více se dozvíte při zobrazení vzorové stránky.';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'Blahopřejeme Vám!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Nyní se prosím přihlašte do Contao a nastavte celý systém. Pak navštivte Vaší stránky, abyste se ujistili, že Contao funguje správným způsobem.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Zpracovat data přes FTP';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Zadejte prosím přístupové údaje k FTP, aby mohl systém Contao zpracovávat soubory přes FTP (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Souhlasím s licencí';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Přihlášení do administrace Contao';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Změňte prosím výchozí heslo, abyste zabránili neautorizovanému přístupu!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'Výchozí heslo bylo změněno.';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Uložit heslo';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Vytvořte prosím šifrovací klíč!';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'Šifrovací klíč by měl mít alespoň 12 znaků!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'Šifrovací klíč byl vytvořen.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Vygenerovat zašifrovaný klíč';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Vygenerovat nebo uložit klíč';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Spojení s databází navázáno.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Nepodařilo se navázat spojení s databází!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Server';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Poskytovatel';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Uživatelské jméno';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'Databáze';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Trvalé připojení';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Kódování';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Kolace';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Číslo portu';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Uložit nastavení připojení k databázi';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Změna kolace se týká všech tabulek s <em>tl_</em>.';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'Databáze není aktuální!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'Databáze je aktuální.';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Zaktualizovat databázi';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = 'Změnit shromažďování';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Zdá se, že akualizujete z verze nižší než %s. Je-li tomu tak, <strong>je nezbytné, abyste provedli aktualizaci na verzi: %s</strong>. Tím zajistíte integraci Vašich dat.';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'Spustit aktualizaci na %s verzi';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'Spustit aktualizaci verze %s - krok %s';
$GLOBALS['TL_LANG']['tl_install']['importException'] = 'Import selhal! Je databáze aktuální a soubor předlohy kompatabilní s touto verzí Contaa?';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Vyberte prosím předlohu!';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Předloha importována %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'Všechna stávající data budou smazána.';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Předlohy';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Nevymazat tabulky';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Importovat předlohu';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Všechny stávající data budou smazána! Chcete přesto pokračovat?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Pro vytvoření účtu administrátora je třeba vyplnit všechna pole!';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'Byl vytvořen účet administrátora.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Vytvořit účet administrátora';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Úspěšně jste nainstalovali Contao.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'Hostitel (FTP)';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Relativní cesta ke složce Contao (např. <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'uživatelské jméno (FTP)';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'heslo (FTP)';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Zabezpečené spojení';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Spojení přes FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'FTP port';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'Uložit spojení k FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Nelze se připojit k FTP serveru %s';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Přihlášení jako "%s" se nezdařilo.';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Nelze najít složku Contao %s';
$GLOBALS['TL_LANG']['tl_install']['filesRenamed'] = 'Nakonfigurovaná složka souborů neexistuje!';
$GLOBALS['TL_LANG']['tl_install']['filesWarning'] = 'Přejmenovali jste složku <strong>tl_files</strong> na <strong>files</strong>? Nestačí však danou složku jen přejmenovat, protože by jinak záznamy v databázi a v kaskádovitých stylech odkazovaly na starý zdroj souborů. Přejmenujte danou složku až po aktualizaci na Contao 3 a ujistěte se, že poté upravíte Vaší databázi pomocí následujícího skriptu: %s.';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Vytvořit nové tabulky';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Přidat nové sloupce';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Změnit existující sloupce';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Smazat existující sloupce';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Smazat existující tabulky';
