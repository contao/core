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
 * @link https://www.transifex.com/projects/p/contao/language/lv/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'TYPOlight instalēšanas rīks';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'Instalēšanas rīka pieslēgšanās';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'Instalēšanas rīks ir bloķēts';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = 'Drošības apsvērumu dēļ Instalēšanas rīks ir bloķēts pēc nepareizas paroles ievadīšanas, kas ir ievadīta vairāk nekā trīs reizes pēc kārtas. Lai to atbloķētu, atveriet vietējo konfigurācijas failu un iestatiet <em>installCount</em> uz <em>0</em>.';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'Parole';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'Lūdzu, ievadiet Instalēšanas rīka paroli. Instalēšanas rīka parole nav tā pati, kas Contao backend parole.';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'Instalēšanas rīka parole';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'Lai palielinātu Contao instalēšanas rīka drošību, jūs varat vai nu pārdēvēt vai pilnībā izņemt <strong>contao/install.php</strong> failu.';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = 'Ģenerēt šifrēšanas atslēgu';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'Šī atslēga tiek izmantota, lai uzglabātu šifrētu datus. Lūdzu, ņemiet vērā, ka šifrētos datus var atšifrēt tikai ar šo atslēgu! Tāpēc, ņemiet vērā to un nemainiet to, ja dati jau ir šifrēti. Atstājiet lauku tukšu, lai izveidotu šfrēšanas atslēgu pēc nejaušības principa.';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'Pārbaudīt datu bāzes savienojumu';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'Lūdzu norādiet Jūsu datu bāzes savienojuma parametrus';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = 'Apkopojums';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'Plašāku informāciju skatiet <a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" onclick="window.open(this.href); return false;">MySQL rokasgrāmata</a>.';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'Aktualizēt datu bāzes tabulas';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'Lūdzu, ņemiet vērā, ka atjauninājumu palīgs ir tikai testēts ar MySQL un mysqli draiveriem. Ja izmantojat citu datu bāzi (piemēram, Oracle), iespējams, ir jāinstalē vai jāatjaunina jūsu datu bāze manuāli. Šajā gadījumā, lūdzu, dodieties uz <strong>system/modules</strong> un meklējiet visās apakšmapās <strong>config/database.sql</strong> failus.';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'Importēt veidni';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = 'Šeit Jūs varat importēt <em>.sql</em> failu no <em>templates</em> direktorijas ar iepriekš sakonfigurētu mājas lapas piemēru. Esošie dati tiks izdzēsti! Ja Jūs tikai vēlaties importēt tēmu, lūdzu, izmantojiet tēmu pārvaldnieku, kas atrodas Contao administrēšanas modulī.';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = 'Izveidojiet administratora lietotāju';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'Ja esat importējis parauga mājas lapu, tad admin lietotājvārds ir <strong>k.jones</strong> un tā parole ir <strong>kevinjones</strong>. Lai iegūtu vairāk informāciju, apskatiet parauga mājas lapu (front end).';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'Apsveicam!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'Tagad, lūdzu, piesakieties <a href="contao/index.php">Contao back end</a> un pārbaudes sistēmas iestatījumus. Tad apmeklējiet jūsu mājas lapu, lai pārliecinātos, ka Contao darbojas pareizi.';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'Modificēt failus caur FTP';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'Jūsu serveris neatbalsta failu piekļuvi, izmantojot PHP, visticamāk PHP darbojas kā Apache modulis ar citu lietotāju. Tāpēc, lūdzu, ievadiet savu FTP pieteikšanās informāciju, lai Contao varētu modificēt failus caur FTP (Safe Mode Hack).';
$GLOBALS['TL_LANG']['tl_install']['accept'] = 'Pieņemt licenci';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Contao aizmugures pieslēgšanās';
$GLOBALS['TL_LANG']['tl_install']['passError'] = 'Lūdzu, ievadiet paroli, lai novērstu neatļautu piekļuvi!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = 'Īpaša parole ir uzstādīta';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'Saglabāt paroli';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = 'Lūdzu izveidojiet šifrēšanas atslēgu!';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = 'Šifrēšanas atslēgai jābūt vismaz 12 rakstzīmes garai!';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = 'Šifrēšanas atslēga ir izveidota.';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = 'Ģenerēt šifrēšanas atslēgu';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = 'Ģenerēt vai saglabāt atslēgu';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'Datubāzes savienojums ok.';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'Nevar savienoties ar datubāzi.';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'Draiveris';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'Hosts';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'Lietotājvārds';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'Datu bāze';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = 'Pastāvīgais savienojums';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = 'Rakstzīmju kopa';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = 'Apkopojums';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'Porta numurs';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'Saglabāt datu bāzes uzstādījumus';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = 'Mainot apkopojumu, tiks ietekmētas visas tabulas ar <em>tl_</em> prefiksu.';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'Datu bāze nav aktuāla!';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'Datu bāze ir aktuāla.';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'Atjaunināt datu bāzi';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'Šķiet, ka Jūs aktualizējat Contao versiju pirms versijas %s. Ja tas tā ir, tad <strong>ir nepieciešams palaist versijas %s jauninājumu</strong>, lai nodrošinātu integritāti jūsu datiem!';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'Uzstādīt versijas %s jauninājumu';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'Lūdzu izvēlieties veidnes datni!';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = 'Veidne importēta %s';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = 'Jebkuri esošie dati tiks izdzēsta!';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'Veidnes';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'Neiztukšot tabulas';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'Importēt veidni';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = 'Jebkuri pašreizējie dati tiks izdzēsti! Vai tiešām vēlaties turpināt?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'Lūdzu, aizpildiet visus laukus, lai izveidotu administratora lietotāju!';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = 'Administratora lietotājs izveidots.';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = 'Izveidot administratora lietotāju';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Esat veiksmīgi instalējis Contao.';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'FTP hosta nosaukums';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Relatīvais ceļš uz Contao direktoriju (piemēram: <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'FTP lietotājvārds';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'FTP parole';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = 'Drošs savienojums';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'Savienotioes, iizmantojot FTP-SSL';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'FTP ports';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'Saglabāt FTP uzstādījumus';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = 'Nevar savienoties ar FTP serveri %s';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = 'Nevar ielogoties kā "%s"';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = 'Nav atroadama Contao direktorija %s';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = 'Izveidot jaunas tabulas';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = 'Pievienot jaunas kolonas';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = 'Mainīt esošās kolonas';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = 'Dzēst esošās kolonas';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = 'Dzēst esošās tabulas';
