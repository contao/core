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
 * @link https://www.transifex.com/projects/p/contao/language/pl/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Instalator Contao';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Login instalatora';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'Instalator został zablokowany';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'Z przyczyn bezpieczeństwa, instalator został zablokowany po podaniu błędnego hasła trzy razy pod rząd. Aby odblokować otwórz plik konfiguracji lokalnej (system/config/localconfig.php) i ustaw <em>installCount</em> na <em>0</em>.';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Hasło';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Wprowadź hasło instalatora. Hasło instalatora nie jest jednocześnie hasłem do panelu zarządzania.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Hasło instalatora';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'Dla dodatkowego zabezpieczenia narzędzia instalacji Contao można zmienić nazwę lub usunąć plik <strong>contao/install.php</strong>.';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Wygeneruj klucz kodowania';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Klucz kodowania służy do przetrzymywania zaszyfrowanych danych. Należy pamiętać, że zaszyfrowane dane mogą być odczytane jedynie przy pomocy tego klucza. Także, jeśli masz już jakieś zaszyfrowane dane w bazie danych nie zmieniaj tego klucza. Pozostaw to pole puste aby wygenerować losowy klucz.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Sprawdź połaczenie z bazą danych';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Wprowadź parametry twojego połączenia z bazą danych.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Collation (kodowanie znaków w bazie danych)';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'Więcej informacji na ten temat <a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" target="_blank">w podręczniku MySQL</a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Uaktualnij tabele bazy danych';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Prosimy pamiętać, że asystent aktualizacji był testowany jedynie z MySQL oraz sterownikiem MySQLi. Jeśli używasz innej bazy danych (np. Oracle) być może będziesz musiał zainstalować lub zaktualizować bazę ręcznie. W takim przypadku przejdź do katalogu <strong>system/modules</strong> i wyszukaj wszystkie pliki <strong>config/database.sql</strong>.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Importuj szablon';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Wybierz plik <em>.sql</em> z katalogu <em>templates</em>.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Utwórz administratora';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Jeśli zaimportowałeś przykładową stronę to nazwą administratora jest <strong>k.jones</strong> a hasło to <strong>kevinjones</strong>. Aby dowiedzieć się więcej zobacz stronę startową przykładowej instalacji.';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'gratulacje!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Teraz prosimy zalogować się do panelu zarządzania i sprawdzić ustawienia systemowe. Następnie wyświetl stronę webową aby mieć pewność, że Contao pracuje prawidłowo.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Modyfikacja plików poprzez FTP';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Wprowadź dane swojego FTP aby Contao mógł modyfikować pliki poprzez FTP (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Zaakceptuj licencję';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Panel zarządzania Contao';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Prosimy zmienić domyślne hasło aby ustrzec się przed nieautoryzowanym dostępem!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'Domyślne hasło zostało zmienione.';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Zapisz hasło';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Prosimy wygenerować klucz kodowania!';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'Klucz kodowania musi posiadać co najmniej 12 znaków!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'Klucz kodowania został utworzony.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Wygeneruj klucz kodowania';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Wygeneruj lub zapisz klucz';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Połączenie z bazą danych jest poprawne.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Nie można się połączyć z bazą danych!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Sterownik';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Host';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Użytkownik';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'Baza danych';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Stałe połączenie';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Ustawienia znaków';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Collation (kodowanie znaków w bazie danych)';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Numer portu';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Zapisz ustawienia bazy danych';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Zmiana parametru collation będzie miała wpływ na szystkie tabele z prefiksem <em>tl_</em>.';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'Baza danych nie jest uaktualniona!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'Baza danych jest uaktualniona.';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Uaktualnij bazę danych';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = 'Zmień porównywanie';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Wygląda na to, że aktualizujesz Contao z wersji %s lub wcześniejszej. Jeśli rzeczywiście tak jest, <strong>musisz uruchomić aktualizację z wersji %s</strong>, by zachować spójność danych!';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'Uruchom aktualizację wersji %s';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'Uruchom aktualizację wersji %s - krok %s';
$GLOBALS['TL_LANG']['tl_install']['importException'] = 'Import sie nie powiódł! Czy struktura bazy danych jest aktualna i czy plik templatki jest kombatybilny z Twoja wersją Contao?';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Wybierz plik szablonu!';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Szablon zaimportowano %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'Wszystkie istniejące dane zostaną skasowane!';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Szablony';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Nie czyść tabel';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Importuj szablon';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Wszystkie istniejąca dane zostaną skasowane! Naprawdę chcesz kontynuować?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Prosimy wypełnić wszystkie pola aby utworzyć administratora!';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'Administrator został utworzony.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Utwórz konto administratora';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Pomyślnie zainstalowałeś Contao.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'Serwer FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Relatywna ścieżka do katalogu Contao (np. <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'Użytkownik FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'Hasło FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Bezpieczne połączenie';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Połącz poprzez FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'Port FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'Zapisz hasło FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Nie można się połączyć z serwerem FTP: %s';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Nie można się zalogować jako "%s"';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Nie można zlokalizować katalogu Contao: %s';
$GLOBALS['TL_LANG']['tl_install']['filesRenamed'] = 'Skonfigurowany katalog plików nie istnieje!';
$GLOBALS['TL_LANG']['tl_install']['filesWarning'] = 'Czy zmieniłeś nazwę katalogu <strong>tl_files</strong> na <strong>files</strong>? Nie możesz po prostu zmienić nazwę katalogu ponieważ wszystkie odniesienia do plików w bazie danych oraz arkusze stylów wciąż będą wskazywły poprzednią lokalizację. Jeśli chcesz zmienić nazwę tego katalogu, prosimy to zrobić po wcześniejszej aktualizacji do wersji 3 oraz dostosować bazę danych tym skryptem: %s.';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Utwórz nowe tabele';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Dodaj nowe kolumny';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Zmień istniejace kolumny';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Usuń istniejące kolumny';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Usuń istniejące tabele';
