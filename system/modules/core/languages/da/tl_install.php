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
 * @link https://www.transifex.com/projects/p/contao/language/da/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Contao installationsværktøj';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Installationsværktøj login';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'Installationsværktøjet er låst';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'Af sikkerhedsmæssige årsager er installationsværktøjet låst, da et forkert kodeord er indtastet mere end 3 gang i træk. For at låse det op igen skal du åbne filen "local configuration" og sætte <em>installCount</em> til <em>0</em>.';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Kodeord';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Indtast kodeordet til installationsværktøjet. Kodeordet er ikke det samme som bruges til administrationsdelen.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Kodeord for installeringsværktøjet';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'For at sikre installationsværktøjet yderligere kan du enten omdøbe eller slette filen <strong>contao/install.php</strong>';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Generer en krypteringsnøgle';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Denne nøgle (kode) bliver brugt til at gemme krypteret data. Bemærk at krypteret data kun kan dekrypteres med denne nøgle. Skriv den ned og undgå at ændre den hvis du allerede har krypteret data. Lad feltet stå tomt for at oprette en tilfældig nøgle.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Tjek database forbindelse';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Indtast venligst data for at forbinde til databasen.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Sammenligning';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'For mere information se <a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" onclick="window.open(this.href); return false;">MySQL manual</a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Opdater database tabeller';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Bemærk at database opdateringen kun er testet med MySQL og MySQLi drivere. Hvis du bruger en anden database (f.eks. Oracle), vil det måske være nødvendigt at opdatere databasen manuelt. Hvis det er tilfældet åbn mappen <strong>system/modules</strong> og gennemse alle undermapper for <strong>config/database.sql</strong> filer.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Importer en skabelon';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Vælg venligst en <em>.sql</em> fil fra mappen <em>templates</em>.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Opret en administrator bruger';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Hvis du har importeret demosiden er dit admin brugernavn <strong>k.jones</strong> og dit kodeord er <strong>kevinjones</strong>.';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'Tillykke!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Du kan nu logge ind i administrationsdelen og kontrollere dine systemindstillinger.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Ret filer via FTP';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Indtast venligst dit FTP login detaljer så Contao kan modificere filer via FTP (Safe Mode Hack)';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Accepter licens';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Contao back end login';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Ret venligst standard password for at hindre uautoriseret adgang.';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'Standart password er nu ændret.';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Gem password';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Opret venligst en krypteret nøgle!';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'En krypteret nøgle skal være på mindst 12 tegn!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'En krypteret nøgle er genereret.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Generer krypteret nøgle';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Generer eller gem nøgle';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Databasen tilslutning er OK.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Kunne ikke tilslutte databasen!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Driver';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Adresse';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Brugernavn';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'Database';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Vedvarende tilslutning';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Karektersæt';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Sammenligning';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Port nummer';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Gem database opsætninger';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Ændring af sammenligning vil påvirke alle tabeller med prefixet <em>tl_</em>';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'Databasen er ikke opdateret.';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'Databasen er allerede opdateret.';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Opdater database';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = 'Ændre sortering (collation)';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Det ser ud til at du opdaterer fra en ældre version end %s. Er dette tilfældet, <strong>påkræves det at køre version %s opdateringen</strong> for at sikre integriteten af dine data!';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'Kør version %s opdateringen';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'Kør version %s opdatering - trin %s';
$GLOBALS['TL_LANG']['tl_install']['importException'] = 'Importeringen fejlede! Er database strukturen up to date og er skabelon filen kompatibel med din version af Contao?';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Vælg venligst en skabelon!';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Skabelon importeret i %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'Al eksisterende data til blive slettet!';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Skabeloner';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Afkort ikke tabeller';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Importer skabelon';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Al eksisterende data til blive slettet! Er du sikker på at du vil fortsætte?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Udfyld venligst alle felter for at oprette en administrator konto!';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'En administrator konto er nu oprettet.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Opret administrator konto';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Du har nu installeret Contao med sucess.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'FTP adresse';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Relativ sti til Contao mappe (f.eks. <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'FTP brugernavn';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'FTP password';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Sikker forbindelse';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Tilslut via FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'FTP port';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'Gem FTP opsætninger';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Kunne ikke tilslutte til FTP serveren %S';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Kunne ikke logge ind som "%s"';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Kunne ikke finde Contao mappen %s';
$GLOBALS['TL_LANG']['tl_install']['filesRenamed'] = 'Den konfigurerede mappe til filarkivet eksisterer ikke!';
$GLOBALS['TL_LANG']['tl_install']['filesWarning'] = 'Har du omdøbt <strong>tl_files</strong> mappen til <strong>files</strong>? Du kan ikke uden videre omdøbe mappen, fordi alle fil referencerne i databasen og i dine style sheets vil stadig pege til den gamle lokation. Hvis du ønsker at omdøbe mappen, skal det gøres efter version 3 opdateringen og sørg for at tilpas database data med følgende script: %s.';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Opret nye tabeller';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Tilføj nye kolonner';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Ret eksisterende kolonner';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Slet eksisterende kolonner';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Slet eksisterende tabeller';
