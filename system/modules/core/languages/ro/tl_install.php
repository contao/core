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
 * @link https://www.transifex.com/projects/p/contao/language/ro/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'Utilitar de instalare pentru Contao';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Autentificare pentru instalare';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'Utilitarul de instalare a fost blocat';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'Din motive de securitate, utilitarul de instalare a fost blocat după introducerea unei parole greşite de mai mult de trei ori. Pentru deblocare, deschideţi fişierul local de configurare şi atribuiţi <em>0</em> variabilei <em>installCount</em>.';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Parolă';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Introduceţi parola pentru utilitarul de intalare. Aceasta nu are legătură cu parola de acces la back-end în Contao.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Parola pentru instalare';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'Pentru a securiza în plus acest script, puteţi insera o instrucţiune <strong>exit;</strong> în <strong>contao/install.php</strong> sau îl puteţi şterge complet de pe server.';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Generează o cheie de incriptare';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Această cheie este folosită pentru a stoca date incriptate. Trebuie ştiut că datele incriptate pot fi decriptate doar cu această cheie! Aşadar păstraţi-o şi nu o schimbaţi dacă există date incriptate deja cu aceasta. Lăsaţi liber pentru a genera o cheie aleatoare.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Verifică conexiunea cu baza de date';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Introduceţi parametri de conexiune cu baza de date.';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Colaţionare';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'Pentru mai multe informaţii verificaţi <a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" onclick="window.open(this.href); return false;">manualul MySQL</a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Actualizare tabele';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Asistentul de update a fost testat doar cu drivere MySQL şi MySQLi. Dacă folosiţi o bază de date diferită (de ex. Oracle), este posibil să trebuiască să instalaţi sau să actualizaţi baza de date manual. În acest caz, mergeţi la <strong>system/modules</strong> şi căutaţi toate subfolderele după fişiere de tipul <strong>config/database.sql</strong>.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Imporă şablon';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Introduceţi un fişier <em>.sql</em> din directorul <em>templates</em>.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Creeză un utilizator admin';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Dacă aţi importat website-ul demo, utilizatorul admin este <strong>k.jones</strong> iar parola este <strong>kevinjones</strong>. Verificaţi în front-end pentru mai multe informaţii.';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'Felicitări!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Acum autentificaţi-vă în Contao back-end şi verificaţi setările sistemului. Apoi vizitaţi website-ul pentru a proba dacă totul este în regulă.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Modificare fişiere prin FTP';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Introduceţi datele de acces la contul FTP astfel încât Contao să îşi poată modifica fişierele prin această metodă (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Acceptă licenţă';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Autentificare Contao back-end';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Schimbaţi parola iniţială pentru a preveni accesul neautorizat!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'Parola iniţială a fost schimbată';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Salvare parolă';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Creaţi o cheie de incriptare!';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'O cheie de incriptare trebuie să fie de cel puţin 12 caractere!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'O cheie de incriptare a fost creată';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Generează cheie de incriptare';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Generează sau salvează cheie';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Conexiunea cu baza de date este OK.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Nu se poate face conexiunea cu baza de date!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Driver';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Host';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Utilizator';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'Baza da date';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Conexiune persistentă';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Set de caractere';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Colaţionare';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Port';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Salvează setările cu baza de date';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Prin schimbarea colaţionării sunt afectate toate tabelele cu prefixul <em>tl_</em>.';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'Baza de date nu este actualizată!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'Baza de date s-a actualizat.';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Actualizare bază de date';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = 'Schimbă colaţionarea';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Se pare că faceţi upgrade la Contao de la o versiune anterioară lui %s. În acest caz, <strong>este necesar să executaţi un upgrade la versiunea %s</strong> pentru a asigura integritatea datelor!';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'Execută update la versiune %s';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'Rulează version %s update - step %s';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Alegeţi fişierul şablon!';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Şablon importat';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'Datele existente vor fi şterse';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Şabloane';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Nu segmenta tabelele';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Importă şablon';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Toate datele existenete vor fi şterse! Doriţi să continuaţi?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Completaţi toate câmpurile pentru a crea un utilizator administrator!';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'Utilizatorul admin a fost creat.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Creează utilizatorul admin';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Contao s-a instalat cu succes.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'Host FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Calea relativă spre directorul Contao (de ex. <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'Utilizator FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'Parola FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Conectare securizată';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Conectare prin FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'Port FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'Setări FTP';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Nu se poate face conexiunea cu serverul FTP %s';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Nu se poate face autentificare ca "%s"';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Nu se poate localiza directorul Contao %s';
$GLOBALS['TL_LANG']['tl_install']['filesRenamed'] = 'Directorul de fişiere configurat nu există!';
$GLOBALS['TL_LANG']['tl_install']['filesWarning'] = 'Aţi redenumit directorul <strong>tl_files</strong> în <strong>files</strong>? Nu se poate redenumi simplu directorul, deoarece toate referinţele din baza de date la fişiere vor arăta în continuare spre vechea locaţie. Dacă doriţi să redenumiţi acest director, puteţi face asta după update-ul la versiunea 3 şi aveţi grijă să ajustaţi datele din baza de date cu următorul script: %s.';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Creează tabele noi';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Adaugă noi coloane';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Schimbă coloanele existente';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Şterge coloanele existente';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Şterge tabelele existente';
