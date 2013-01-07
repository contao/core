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

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Ämne';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Ange ett ämne för nyhetsbrevet. Detta används som ämnesrad i det skickade E-postmeddelandet med nyhetsbrevet som mottagaren får.';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Nyhetsbrevsalias';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'Nyhetsbrevsalias är ett unik referensnamn som kan användas istället för nyhetsbrevets numeriska ID-nummer när man refererar till nyhetsbrevet.';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'HTML-innehåll';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Här kan du skriva HTML-innehållet i nyhetsbrevet. Använd wildcard <em>##email##</em> för att infoga mottagarens E-postadress. Följande exempel genererar en länk för uppsägning av prenumeration: <em>http://www.dindomän.se/unsubscribe-page.html?email=##email##</em> (Byt ut <em>www.dindomän.se/unsubscribe-page.html</em> mot sökvägen till en sida med frontend-modulen "Avsluta prenumeration".)';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Textinnehåll';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Här kan du skriva textinnehållet i nyhetsbrevet. Använd wildcard <em>##email##</em> för att infoga mottagarens E-postadress. Följande exempel genererar en länk för uppsägning av prenumeration: <em>http://www.dindomän.se/unsubscribe-page.html?email=##email##</em> (Byt ut <em>www.dindomän.se/unsubscribe-page.html</em> mot sökvägen till en sida med frontend-modulen "Avsluta prenumeration".)';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Lägg till bilaga';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Lägg till en eller flera bilagor till nyhetsbrevet.';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Bilagor';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Ange vilka filer du vill lägga till i nyhetsbrevet.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'E-postmall';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Välj en E-postmall (mallgrupp <em>mail_</em>).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Skicka som ren text';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Om du väljer detta alternativ kommer nyhetsbrevet att skickas som ren text. Alla HTML-taggar kommer då att tas bort.';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'Externa bilder';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Skicka inte bilderna tillsammans med HTML-nyhetsbrevet (istället länkas dessa mot bilderna på din webbserver).';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Avsändarnamn';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Ange namn på avsändaren.';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'Avsändaradress';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Om du inte anger en avsändaradress kommer administratörskontots E-postadress att användas.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Antal E-postutskick per omgång';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'För att hindra att skriptet når timeout så delas processen upp i omgångar. Här kan du ange antalet E-postutskick per omgång beroende av maxtiden i din php.ini fil.';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Timeout i sekunder';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Vissa E-postservrar begränsar antalet e-postmeddelanden som kan skickas per minut. Här kan du ändra tidsgränsen mellan varje cykel i sekunder för att få mer kontroll över tiden.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Offset';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'Om sändningsprocessen avbryts kan du ange numret för den nyhetsbrevmottagare som skulle varit på tur. Du kan se hur många utskick som gjorts i loggfilen <em>system/logs/newsletter_*.log</em>. Om t.ex. 120 nyhetsbrev skickats anger du 120 i fältet, vilket innebär att utskicket börjar med nyhetsbrevmottagare nr 121 (räknaren börjar på 0 för den första mottagaren).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Skicka förhandsgranskning till';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Skicka en förhandsgranskning av nyhetsbrevet till denna E-postadressen.';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Titel och ämne';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'HTML-innehåll';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Textinnehåll';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Bilagor';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Mallinställningar';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Expertinställningar';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Skickat';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Skickat den %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Ännu inte skickat';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Utskicksdatum';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'Nyhetsbrevet har skickats till %s prenumeranter.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '%s ogiltiga E-postadresser har blivit inaktiverade (se systemloggen).';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'Det finns inga aktiva prenumeranter på denna kanal';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'Från';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Bilagor';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Skicka förhandsgranskning';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Vill du verkligen skicka nyhetsbrevet?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'Nytt nyhetsbrev';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Skapa ett nytt nyhetsbrev';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Nyhetsbrevsdetaljer';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Visa detaljer i nyhetsbrev med ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Redigera nyhetsbrev';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Redigera nyhetsbrev med ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Kopiera nyhetsbrev';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Kopiera nyhetsbrev med ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Flytta nyhetsbrev';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Flytta nyhetsbrev med ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Ta bort nyhetsbrev';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Ta bort nyhetsbrev med ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Redigera nyhetskanal';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Redigera nyhetskanalens inställningar';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'Klistra in i denna kanal';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Klistra in efter nyhetsbrev med ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Skicka nyhetsbrev';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Skicka nyhetsbrev med ID %s';
