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

$GLOBALS['TL_LANG']['tl_form']['title'][0] = 'Titel';
$GLOBALS['TL_LANG']['tl_form']['title'][1] = 'Angiv formularens titel.';
$GLOBALS['TL_LANG']['tl_form']['alias'][0] = 'Form henvisning';
$GLOBALS['TL_LANG']['tl_form']['alias'][1] = 'Form henvisningen er en unik reference til formen, der kan bruges i stedet for dens ID';
$GLOBALS['TL_LANG']['tl_form']['jumpTo'][0] = 'Hop til side';
$GLOBALS['TL_LANG']['tl_form']['jumpTo'][1] = 'Data fra en formular sendes normalt til en anden side som behandler disse eller viser en besked, f.eks. "Tak, informationen er modtaget." Her kan du vælge hvilken side formularen skal sende til.';
$GLOBALS['TL_LANG']['tl_form']['sendViaEmail'][0] = 'Send formular-data via e-post';
$GLOBALS['TL_LANG']['tl_form']['sendViaEmail'][1] = 'Hvis du vælger denne mulighed, vil formulardata blive sendt via e-post.';
$GLOBALS['TL_LANG']['tl_form']['recipient'][0] = 'Modtager';
$GLOBALS['TL_LANG']['tl_form']['recipient'][1] = 'Indtast venligst en eller flere modtageres e-postadresser. Adskil flere adresser med komma. Du kan også bruge det "navneformatet" (f.eks. "Navn[navn@domain.dk]").';
$GLOBALS['TL_LANG']['tl_form']['subject'][0] = 'Emne';
$GLOBALS['TL_LANG']['tl_form']['subject'][1] = 'Indtast venligst emne. Hvis du ikke indtaster et emne, stiger sandsynligheden for at e-posten bliver identificeret som SPAM.';
$GLOBALS['TL_LANG']['tl_form']['format'][0] = 'Dataformat';
$GLOBALS['TL_LANG']['tl_form']['format'][1] = 'Vælg et dataformat.';
$GLOBALS['TL_LANG']['tl_form']['raw'][0] = 'Rå data';
$GLOBALS['TL_LANG']['tl_form']['raw'][1] = 'Data fra formularen sendes som en almindelig tekstbesked hvor de enkelte felter adskilles med et linjeskift.';
$GLOBALS['TL_LANG']['tl_form']['xml'][0] = 'XML-fil';
$GLOBALS['TL_LANG']['tl_form']['xml'][1] = 'Data fra formularen vedhæftes e-posten som en XML-fil.';
$GLOBALS['TL_LANG']['tl_form']['csv'][0] = 'CSV-fil';
$GLOBALS['TL_LANG']['tl_form']['csv'][1] = 'Data fra formularen vedhæftes e-posten som en CSV-fil (kommasepareret).';
$GLOBALS['TL_LANG']['tl_form']['email'][0] = 'E-postformat';
$GLOBALS['TL_LANG']['tl_form']['email'][1] = 'Dette format forventer felterne <em>email</em>, <em>subject</em>, <em>message</em> og <em>cc</em> (send en copy af e-posten til afsenderen.) Andre felter vil blive ignoreret. Fil-uploads er tilladte.';
$GLOBALS['TL_LANG']['tl_form']['skipEmtpy'][0] = 'Spring tomme felter over';
$GLOBALS['TL_LANG']['tl_form']['skipEmtpy'][1] = 'Inkluder ikke tomme felter i e-posten.';
$GLOBALS['TL_LANG']['tl_form']['storeValues'][0] = 'Gem værdier';
$GLOBALS['TL_LANG']['tl_form']['storeValues'][1] = 'Hvis du vælger denne mulighed, vil indsendte værdier blive gemt og genindsat i deres formularfelter.';
$GLOBALS['TL_LANG']['tl_form']['targetTable'][0] = 'Modtagelsestabel';
$GLOBALS['TL_LANG']['tl_form']['targetTable'][1] = 'Vælg venligst tabellen du vil gemme formularværdierne i.';
$GLOBALS['TL_LANG']['tl_form']['method'][0] = 'Formular indsendelsesmetode';
$GLOBALS['TL_LANG']['tl_form']['method'][1] = 'Vælg venligst en formular indsendelsesmetode (standard: POST).';
$GLOBALS['TL_LANG']['tl_form']['attributes'][0] = 'Stilark-ID og klasse';
$GLOBALS['TL_LANG']['tl_form']['attributes'][1] = 'Her kan du indtaste et stilark-ID (id-attributten) og en eller flere stilark-klasser (klasse-attributten) for at kunne formatere formularen vha. CSS.';
$GLOBALS['TL_LANG']['tl_form']['formID'][0] = 'Formularens ID';
$GLOBALS['TL_LANG']['tl_form']['formID'][1] = 'Her kan du indtaste en speciel formular-ID (nogen TYPOlight-udvidelser kunne kræve et specielt formular-ID).';
$GLOBALS['TL_LANG']['tl_form']['tableless'][0] = 'Layout uden brug af tabeller';
$GLOBALS['TL_LANG']['tl_form']['tableless'][1] = 'Hvis du vælger denne mulighed, vil formularen blive renderet uden tabeller.';
$GLOBALS['TL_LANG']['tl_form']['allowTags'][0] = 'Tillad HTML-tags';
$GLOBALS['TL_LANG']['tl_form']['allowTags'][1] = 'Vælger du denne indstilling bevares visse HTML-mærkater (<em>allowedTags</em>).';
$GLOBALS['TL_LANG']['tl_form']['tstamp'][0] = 'Revisionsdato';
$GLOBALS['TL_LANG']['tl_form']['tstamp'][1] = 'Dato og tid for sidste revision';
$GLOBALS['TL_LANG']['tl_form']['title_legend'] = 'Titel og hop til side';
$GLOBALS['TL_LANG']['tl_form']['email_legend'] = 'Send formular-data';
$GLOBALS['TL_LANG']['tl_form']['store_legend'] = 'Gem formular-data';
$GLOBALS['TL_LANG']['tl_form']['expert_legend'] = 'Ekspert indstilling';
$GLOBALS['TL_LANG']['tl_form']['config_legend'] = 'Form konfiguration';
$GLOBALS['TL_LANG']['tl_form']['new'][0] = 'Ny formular';
$GLOBALS['TL_LANG']['tl_form']['new'][1] = 'Opret en ny formular';
$GLOBALS['TL_LANG']['tl_form']['show'][0] = 'Formulardetaljer';
$GLOBALS['TL_LANG']['tl_form']['show'][1] = 'Vis detaljer for formular-ID %s';
$GLOBALS['TL_LANG']['tl_form']['edit'][0] = 'Rediger formular';
$GLOBALS['TL_LANG']['tl_form']['edit'][1] = 'Rediger formular-ID %s';
$GLOBALS['TL_LANG']['tl_form']['editheader'][0] = 'Redigér formular indstillinger';
$GLOBALS['TL_LANG']['tl_form']['editheader'][1] = 'Redigér indstillinger for formular-ID %s';
$GLOBALS['TL_LANG']['tl_form']['copy'][0] = 'Dupliker formular';
$GLOBALS['TL_LANG']['tl_form']['copy'][1] = 'Dupliker formular-ID %s';
$GLOBALS['TL_LANG']['tl_form']['delete'][0] = 'Slet formular';
$GLOBALS['TL_LANG']['tl_form']['delete'][1] = 'Slet formular-ID %s';
