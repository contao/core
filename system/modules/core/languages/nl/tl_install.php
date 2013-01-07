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
 * @link https://www.transifex.com/projects/p/contao/language/nl/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Installatie';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Installatie programma login';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'Het installatie programma is geblokkeerd';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'Om veiligheidsredenen is het installatie programma geblokkeerd omdat een wachtwoord meer dan drie keer achter elkaar verkeerd is ingevoerd. Om te ontgrendelen, open localconfig en zet <em>installCount</em> op<em>0</em>.';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Wachtwoord';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Geef het installatie programma wachtwoord op. Dit is niet hetzelfde als het Contao backend wachtwoord.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Installatie programma wachtwoord';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'Om het Contao installatie programma extra te beveiligen kunt u het bestand <strong>contao/install.php</strong> hernoemen of geheel verwijderen.';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Maak een codesleutel aan';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Deze sleutel wordt gebruikt om versleutelde data op te slaan. Bedenk dat versleutelde data alleen kunnen worden ontcijferd met deze sleutel! Belangrijk: noteer deze code ergens en wijzig deze niet als er al versleutelde data aanwezig is. Laat dit vak leeg om een willekeurige code te genereren.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Controleer database verbinding';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Controleer uw gegevens om met de database te verbinden.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Vergelijking (Collation)';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'Voor meer informatie zie de <a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" target="_blank">MySQL manual</a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Update database tabellen';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Bedenk dat de update assistent alleen getest is met MySQL en MySQLi drivers. Als u een andere database gebruikt (bv.Oracle), dient u wellicht de database handmatig te installeren of te updaten. Ga in dat geval naar <strong>system/modules</strong> en zoek in alle submappen naar <strong>config/database.sql</strong> bestanden.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Importeer een template';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Hier kunt u een <em>.sql</em> bestand importeren van de <em>templates</em> directory met een vooraf geconfigureerde Contao website. Bestaande gegevens worden verwijderd! Als u alleen een thema wilt importeren, gebruik dan de thema-manager in het Contao backend.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Maak een admin gebruiker aan';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Als u de voorbeeld website hebt geïmporteerd is de admin gebruikersnaam <strong>k.jones</strong> en het wachtwoord <strong>kevinjones</strong>. Zie de voorbeeld website (frontend) voor meer informatie.';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'Gefeliciteerd!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'De installatie is succesvol afgesloten. Log in het <a href="contao/index.php">Contao backend</a> in en controleer de systeeminstellingen. Bezoek hierna uw website en controleer of Contao correct werkt.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Wijzig bestanden via FTP';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Uw server ondersteunt geen datatoegang via PHP; waarschijnlijk draait PHP als Apache module onder een andere gebruiker. Geef daarom uw FTP login gegevens op, zodat Contao bestanden kan wijzigen via FTP (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Accepteer licentie';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Contao backend login';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Voer een wachtwoord in om onbevoegde toegang te voorkomen!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'Het standaard wachtwoord is gewijzigd.';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Bewaar wachtwoord';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Maak een codesleutel aan!';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'Een codesleutel dient minstens 12 karakters lang te zijn!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'Een codesleutel is aangemaakt.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Maak een codesleutel aan';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Genereer of bewaar sleutel';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Database verbinding tot stand gebracht.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Geen verbinding met een database!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Driver';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Host';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Gebruikersnaam';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'Database';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Blijvende verbinding';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Tekenset';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Vergelijking (Collation)';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Poortnummer';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Bewaar database gegevens';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Het wijzigen van de vergelijking heeft invloed op alle tabellen met een <em>tl_</em> prefix.';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'De database is niet up-to-date!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'De database is up-to-date!';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Update database';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = 'Wijzig sortering';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Het lijkt erop dat u aan het upgraden bent van een Contao versie voorafgaand aan versie %s. In dat geval, <strong>is het noodzakelijk om versie %s update te gebruiken</strong> om de integriteit van uw gegevens te garanderen!';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'Uitvoeren versie %s update';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'UItvoeren versie %s update - step %s';
$GLOBALS['TL_LANG']['tl_install']['importException'] = 'Importeren is mislukt! Is de database structuur up-to-date en is het template bestand compatibel met uw Contao versie?';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Kies een template bestand!';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Template geïmporteerd op %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'Alle bestaande gegevens worden verwijderd!';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Templates';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Maak tabellen niet leeg';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Importeer template';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Alle bestaande gegevens worden verwijderd! Wilt u werkelijk verder gaan?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Vul alle velden in om een admin gebruiker aan te maken!';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'Een admin gebruiker is aangemaakt.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Maak een admin account';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Contao is met succes geïnstalleerd.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'FTP hostnaam';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Relatief pad naar Contao directory (b.v. <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'FTP gebruikersnaam';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'FTP wachtwoord';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Beveiligde verbinding';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Verbinding via FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'FTP poort';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'Bewaar FTP gegevens';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Kan geen verbinding maken met FTP server %s';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Kon niet inloggen als "%s"';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Geen Contao map %s gevonden ';
$GLOBALS['TL_LANG']['tl_install']['filesRenamed'] = 'De geconfigureerde bestanden map bestaat niet!';
$GLOBALS['TL_LANG']['tl_install']['filesWarning'] = 'Heeft u de <strong>tl_files</strong> map naar <strong>files</strong> hernoemd? U kunt niet zomaar de naam van de map hernoemen, omdat alle bestandsreferenties in de database en uw style sheets nog steeds naar de oude locatie verwijzen. Als u de map wilt wijzigen, kunt u dit doen na de versie 3 update, zorg ervoor uw database gegevens aan te passen met het volgende script: %s.';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Maak nieuwe tabellen';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Voeg nieuwe kolommen toe';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Wijzig bestaande kolommen';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Dump bestaande kolommen';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Dump bestaande tabellen';
