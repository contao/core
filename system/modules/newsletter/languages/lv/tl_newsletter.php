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

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Temats';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Lūdzu ievadiet apkārtraksta tematu.';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Biļetena aizstājējvārds';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'Biļetens aizstājējvārds ir unikāla atsauce uz jaunumiem, ko var izsaukt pēc tā nosaukuma, nevis pēc tā ciparu ID.';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'HTML saturs';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Šeit varat ievadīt HTML saturu biļetenam. Izmantojiet aizstājējzīmes <em>##email##</em>, lai ievietotu adresāta e-pasta adresi.';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Teksta saturs';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Šeit varat ievadīt biļetenā saturu, jeb - tekstu. Izmantojiet aizstājējzīmes <em>##email##</em>, lai ievietotu adresāta e-pasta adresi.';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Pievienot pielikumus';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Pievienot vienu vai vairākas datnes apkārtrakstam.';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Pielikumi';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Lūdzu, izvēlieties failus, ko pievienot no failiem direktorijā.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'E-pasta veidne';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Varat izvēlēties e-pasta veidni.';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Sūtīt kā vienkāršu tekstu';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Sūtīt biļetenu kā vienkārša teksta e-pastu, neizmantojot HTML saturu.';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'Ārējie attēli';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Neieguldiet HTML biļetenos attēlus.';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Nosūtītāju vārds';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Šeit jūs varat ievadīt nosūtītāja vārdu.';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'Nosūtītāja adrese';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Šeit jūs varat ievadīt pielāgotu nosūtītāja adresi.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Vēstules ciklā';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'Sūtīšanas process ir sadalīts vairākos ciklos, lai novērstu skripta izpildes noildzi.';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Noildze sekundēs';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Šeit jūs varat mainīt gaidīšanas laiks starp katru ciklu, lai kontrolētu minūtē nosūtāmo e-pastu skaitu.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Kompensācija (Offset)';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'Gadījumā, ja nosūtīšana ir pārtraukta, jūs varat šeit ievadīt skaitlisku kompensāciju, lai turpinātu ar konkrētu adresātu. Varat pārbaudīt, cik daudzas vēstules ir nosūtītas <em>system/logs/newsletter_*.log</em> failā. Piemēram, ja nosūtīti 120 e-pasta sūtījumi, un lai turpinātu ar 121.saņēmēju (vispār skaitīšana sākas ar 0), ieraksta "120".';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Nosūtīt priekšskatījumu';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Nosūtīt apkārtraksta priekšskatījumu uz šo e-pasta adresi.';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Virsraksts un temats';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'HTML saturs';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Teksta saturs';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Pielikums';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Veidnes uzstādījumi';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Eksperta uzstādījumi';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Nosūtīts';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Nosūtīts %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Nav vel izsūtīts';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Izsūtīšanas datums';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'Biļetens ir nosūtīts% s saņēmējiem.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '% s nederīga e-pasta adrese (-es) ir/tika izslēgta (sk. sistēmas log).';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'Nav aktīvo abonentu uz kanālu.';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'No';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Pielikumi';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Sūtīt priekšskatījumu';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Vai tiešām vēlaties nosūtīt apkārtrakstu?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'Jauns apkārtraksts';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Izveidot jaunu apkārtrakstu';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Apkārtraksta detaļas';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Parādīt apkārtraksta ID %s detaļas';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Rediģēt apkārtrakstu';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Rediģēt apkārtrakstu ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Dublēt apkārtrakstu';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Dublēt apkārtrakstu ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Pārvietot apkārtrakstu';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Pārvietot apkārtrakstu ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Dzēst apkārtrakstu';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Dzēst apkārtrakstu ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Rediģēt kanālu';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Rediģēt kanāla uzstādījumus';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'Ielīmēt šajā kanāla';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Ielīmēt aiz apkārtraksta ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Sūtīt apkārtrakstu';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Sūtīt apkārtrakstu ID %s';
