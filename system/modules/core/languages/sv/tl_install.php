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
 * @link https://www.transifex.com/projects/p/contao/language/sv/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Contaos installationsverktyg';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Logga in i installationsverktyget';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'Installationsverktyget har låsts';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'Av säkerhetsskäl, har installationsverktyget låsts efter att fel lösenord angivits fler än tre gånger i rad. För att låsa upp det, öppna den lokala konfigurationsfilen och ändra <em>installCount</em> till <em>0</em>.';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Lösenord';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Ange lösenordet för installationsverktyget. Lösenordet för installationsverktyget är inte detsamma som lösenordet för Contaos backend.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Lösenord för installationsverktyget';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'För att ytterligare säkra detta skript kan du infoga antingen en <strong>exit;</strong>-kommando i <strong>contao / install.php</strong> eller så kan du helt ta bort filen från servern. I detta fall måste du ändra systeminställningar direkt i den lokala konfigurationsfilen.';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Generera en krypteringsnyckel';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Denna nyckel används för att lagra krypterad data. Observera att krypterad data endast kan dekrypteras med den här nyckeln! Skriv därför ner den och ändra inte den om det redan finns krypterad data. Lämna tomt för att generera en slumpmässig nyckel.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Kontrollera databasanslutning';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Ange dina databasparametrar för anslutningen.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Tabellsortering';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'För mer information se <a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" target="_blank">MySQL-manual</a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Uppdatera databastabeller';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Observera att uppdateringsassistenten har endast testats med MySQL och MySQLi-drivrutiner. Om du använder en annan databas (t.ex. Oracle), kan du behöva installera eller uppdatera databasen manuellt. I det här fallet, gå till <strong>system/modules</strong> och söka i alla dess undermappar efter <strong>config/database.sql</strong>-filer.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Importera en mall';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Här kan du importera en <em>.sql</em>-fil innehållande en prefabricerad exempel-hemsida från <em>templates</em>-mappen. Existerande data kommer att raderas! Om du enbart önskar importera ett tema använder du instället temahanteraren i Contaos backend.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Skapa en administratör';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Om du har importerat exempelwebbplatsen är administratörens användarnamn <strong>k.jones</strong> och lösenordet är <strong>kevinjones</strong>. Se exempelwebbplatsen för mer information.';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'Gratulerar!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Nu kan du logga in i <a href="contao/index.php">Contao backend</a> för att kontrollera systeminställningarna. Besök sedan din hemsida för att se om allt fungerar som det ska.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Modifiera filer via FTP';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Din server stöder inte direkt filåtkomst via PHP; troligen körs PHP som en Apache-modul under en annan användare. Därför bör du ange dina inloggningsdetaljer för FTP så att Contao kan modifiera filer via FTP (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Acceptera licens';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Contao backend-inloggning';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Vänligen ange ett lösenord för att förhindra obehörig åtkomst!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'Lösenordet har ändrats.';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Spara lösenordet';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Skapa en krypteringsnyckel!';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'En krypteringsnyckel måste vara minst 12 tecken långt!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'En krypteringsnyckel har skapats.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Generera krypteringsnyckel';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Generera eller spara nyckel';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Databasanslutning upprättad.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Kunde inte ansluta till databasen!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Drivrutin';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Värd';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Användarnamn';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'Databas';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Ihållande anslutning';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Teckenuppsättning';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Tabellsortering';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Portnummer';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Spara databasinställningar';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Ändring av tabellsortering kommer att påverka alla tabeller med ett <em>tl_</em>-prefix.';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'Databasen är inte uppdaterad!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'Databasen är uppdaterad.';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Uppdatera databasen';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = 'Ändra sortering';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Det verkar som att du uppgraderar från en Contao-version som föregår version %s. Om så är fallet <strong>krävs det att du kör version %s-uppdateringen</strong> för att säkerställa dataintegritet!';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'Kör version %s-uppdatering';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'Kör uppgradering version %s - steg %s';
$GLOBALS['TL_LANG']['tl_install']['importException'] = 'Importeringen misslyckades! Är databasstrukturen aktuell och är mall-filerna kompatibla med din Contao-version?';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Välj en mallfil!';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Mall importeras på %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'Allt befintligt data kommer att raderas!';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Mallar';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Trunkera inte tabeller';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Importera mall';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Befintliga data kommer att raderas! Vill du verkligen fortsätta?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Vänligen fyll i alla fält för att skapa en administratörsanvändare!';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'Administratörsanvändare har skapats.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Skapa administratörskonto';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Du har lyckats med installationen av Contao.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'FTP-värdnamn';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Relativ sökväg till Contao-katalogen (t.ex. <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'FTP-användarnamn';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'FTP-lösenord';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Säker anslutning';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Anslut via FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'FTP-port';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'Spara FTP inställningar';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Det gick inte att ansluta till FTP-server %s';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Det gick inte att logga in som "%s"';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Kunde inte hitta Contao-mappen %s';
$GLOBALS['TL_LANG']['tl_install']['filesRenamed'] = 'Foldern för konfigurationsfiler saknas!';
$GLOBALS['TL_LANG']['tl_install']['filesWarning'] = 'Har du döpt om foldern <strong>tl_files</strong> till <strong>files</strong>? Det går inte att enbart döpa om foldern pga att alla filreferenser i databasen och stilmallarna fortfarande kommer peka mot den gamla foldern. Om du vill döpa om foldern måste detta göras efter uppdatering till Contao version 3 och databasens data måste sedan justeras med detta script: %s';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Skapa nya tabeller';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Lägg till nya kolumner';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Ändra befintliga kolumnerna';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Radera befintliga kolumner';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Radera befintliga tabeller';
