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

$GLOBALS['TL_LANG']['tl_form']['title'][0] = 'Namn';
$GLOBALS['TL_LANG']['tl_form']['title'][1] = 'Ange ett namn för formuläret.';
$GLOBALS['TL_LANG']['tl_form']['alias'][0] = 'Formulär-alias';
$GLOBALS['TL_LANG']['tl_form']['alias'][1] = 'Formuläralias är ett unik referensnamn som kan användas istället för formulärets numeriska ID-nummer när man refererar till formuläret.';
$GLOBALS['TL_LANG']['tl_form']['jumpTo'][0] = 'Hoppa till sida';
$GLOBALS['TL_LANG']['tl_form']['jumpTo'][1] = 'Ett formulär postas vanligtvis till en annan sida, som processar datan eller visar ett "Tack"-meddelande. <br /> Du kan välja denna sida här.';
$GLOBALS['TL_LANG']['tl_form']['sendViaEmail'][0] = 'Skicka formulärdata via E-post';
$GLOBALS['TL_LANG']['tl_form']['sendViaEmail'][1] = 'Om du väljer detta val kommer formulärdatan att skickas med E-post.';
$GLOBALS['TL_LANG']['tl_form']['recipient'][0] = 'Mottagare';
$GLOBALS['TL_LANG']['tl_form']['recipient'][1] = 'Ange en eller flera mottagaradresser. Separera adresserna med komma.';
$GLOBALS['TL_LANG']['tl_form']['subject'][0] = 'Ämne';
$GLOBALS['TL_LANG']['tl_form']['subject'][1] = 'Ange ett E-postämne. Om du inte anger ett ämne så ökar du risken att detta E-postmeddelande kommer att klassas som spam.';
$GLOBALS['TL_LANG']['tl_form']['format'][0] = 'Format';
$GLOBALS['TL_LANG']['tl_form']['format'][1] = 'Ange vilket format du vill använda för att skicka formulärdatat.';
$GLOBALS['TL_LANG']['tl_form']['raw'][0] = 'Rådata';
$GLOBALS['TL_LANG']['tl_form']['raw'][1] = 'Formuläret kommer att skickas som ren text med varje fält på en ny rad.';
$GLOBALS['TL_LANG']['tl_form']['xml'][0] = 'XML-fil';
$GLOBALS['TL_LANG']['tl_form']['xml'][1] = 'Formulärdata kommer att bifogas till E-postmeddelandet som en XML-fil.';
$GLOBALS['TL_LANG']['tl_form']['csv'][0] = 'CSV-fil';
$GLOBALS['TL_LANG']['tl_form']['csv'][1] = 'Formulärdata kommer att bifogas till E-postmeddelandet som en CVS-fil (kommaseparerade värden).';
$GLOBALS['TL_LANG']['tl_form']['email'][0] = 'E-postformat';
$GLOBALS['TL_LANG']['tl_form']['email'][1] = 'Detta format kräver fälten <em>email</em>, <em>subject</em>, <em>cc</em> (carbon copy) och <em>message</em>. Andra fält kommer att ignoreras. Filuppladdningar är tillåtna.';
$GLOBALS['TL_LANG']['tl_form']['skipEmtpy'][0] = 'Hoppa över tomma fält';
$GLOBALS['TL_LANG']['tl_form']['skipEmtpy'][1] = 'Inkludera inte tomma fält i E-postmeddelandet.';
$GLOBALS['TL_LANG']['tl_form']['storeValues'][0] = 'Spara värden';
$GLOBALS['TL_LANG']['tl_form']['storeValues'][1] = 'Om du väljer detta val kommer valda värden att sparas och återskrivas i sina formulärfält.';
$GLOBALS['TL_LANG']['tl_form']['targetTable'][0] = 'Måltabell';
$GLOBALS['TL_LANG']['tl_form']['targetTable'][1] = 'Ange tabellen som du vill spara formulärvärden i. Tabellen måste innehålla en kolumn för varje fält i formuläret.';
$GLOBALS['TL_LANG']['tl_form']['method'][0] = 'Metod för att skicka formulär';
$GLOBALS['TL_LANG']['tl_form']['method'][1] = 'Välj metod för att skicka formulär (standard: POST).';
$GLOBALS['TL_LANG']['tl_form']['attributes'][0] = 'Stilmall ID och klass';
$GLOBALS['TL_LANG']['tl_form']['attributes'][1] = 'Här kan du ange ett stilmalls-ID (CSS ID) och/eller en eller flera stilmallsklasser (CSS-klass). ID och klasser anges utan prefix (nummertecken # resp. punkt .).';
$GLOBALS['TL_LANG']['tl_form']['formID'][0] = 'Formulärets ID';
$GLOBALS['TL_LANG']['tl_form']['formID'][1] = 'Formulär-ID krävs i vissa fall för att kunna trigga en Contao-modul.';
$GLOBALS['TL_LANG']['tl_form']['tableless'][0] = 'Tabellfri layout';
$GLOBALS['TL_LANG']['tl_form']['tableless'][1] = 'Om du väljer detta val kommer formuläret att renderas utan tabeller.';
$GLOBALS['TL_LANG']['tl_form']['allowTags'][0] = 'Tillåt HTML-taggar';
$GLOBALS['TL_LANG']['tl_form']['allowTags'][1] = 'Som standard raderas alla HTML-taggar från användarinmatning av säkerhetsskäl.<br />Men om du ändrar detta val kommer vissa HTML-taggar inte att raderas.';
$GLOBALS['TL_LANG']['tl_form']['tstamp'][0] = 'Ändringsdatum';
$GLOBALS['TL_LANG']['tl_form']['tstamp'][1] = 'Datum och tid för senaste ändringen.';
$GLOBALS['TL_LANG']['tl_form']['title_legend'] = 'Titel och omdirigering';
$GLOBALS['TL_LANG']['tl_form']['email_legend'] = 'Skicka formulärdata';
$GLOBALS['TL_LANG']['tl_form']['store_legend'] = 'Lagra formulärdata';
$GLOBALS['TL_LANG']['tl_form']['expert_legend'] = 'Expertinställningar';
$GLOBALS['TL_LANG']['tl_form']['config_legend'] = 'Formulärkonfiguration';
$GLOBALS['TL_LANG']['tl_form']['new'][0] = 'Nytt formulär';
$GLOBALS['TL_LANG']['tl_form']['new'][1] = 'Skapa ett nytt formulär';
$GLOBALS['TL_LANG']['tl_form']['show'][0] = 'Visa formulärdetaljer';
$GLOBALS['TL_LANG']['tl_form']['show'][1] = 'Visa detaljer för formulär med ID %s';
$GLOBALS['TL_LANG']['tl_form']['edit'][0] = 'Redigera formulär';
$GLOBALS['TL_LANG']['tl_form']['edit'][1] = 'Redigera formulär med ID %s';
$GLOBALS['TL_LANG']['tl_form']['editheader'][0] = 'Redigera formulärinställningar';
$GLOBALS['TL_LANG']['tl_form']['editheader'][1] = 'Redigera inställningarna för formulär med ID %s';
$GLOBALS['TL_LANG']['tl_form']['copy'][0] = 'Kopiera formulär';
$GLOBALS['TL_LANG']['tl_form']['copy'][1] = 'Kopiera formulär med ID %s';
$GLOBALS['TL_LANG']['tl_form']['delete'][0] = 'Ta bort formulär';
$GLOBALS['TL_LANG']['tl_form']['delete'][1] = 'Ta bort formulär med ID %s';
