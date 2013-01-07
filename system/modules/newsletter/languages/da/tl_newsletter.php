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

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Emne';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Angiv venligst emne/overskrift for nyhedsbrevet.';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Nyhedsbrev alias';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'Et nyhedsbrev alias er en unik reference til nyhedsbrevet der kan benyttes i stedet for dets ID.';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'HTML indhold';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Skriv HTML indholdet til nyhedsbrevet. Du kan benytte <em>##email##</em> som pladsholder til at indsætte modtagerens e-mailadresse. Skab \'Afmeld nyhedsbrev\'-link som <em>http://www.domain.com/unsubscribe-page.html?email=##email##</em>.';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Tekst indhold';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Skriv tekst indholdet til nyhedsbrevet. Du kan benytte <em>##email##</em> som pladsholder til at indsætte modtagerens e-mailadresse. Skab \'Afmeld nyhedsbrev\'-link som <em>http://www.domain.com/unsubscribe-page.html?email=##email##</em>.';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Vedhæft fil';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Vedhæft en eller flere filer til nyhedsbrevet.';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Vedhæftede filer';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Vælg filer du vil vedhæfte nyhedsbrevet.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'E-mail skabelon';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Vælg venligst en e-mail skabelon (skabelon gruppe <em>mail_</em>).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Send som tekst';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Vælger du denne mulighed bliver nyhedsbrevet sendt som tekst. Alle HTML-koder bliver fjernet.';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'Externe billeder';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Vedhæft ikke billeder i HTML nyhedsbreve';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Afsendernavn';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Her kan du skrive afsenderens navn.';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'Afsenderadresse';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Hvis du ikke angiver afsenderadresse bliver administratorens e-mailadresse brugt som afsender.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Breve pr. gennemløb';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'For at undgå at scriptet tidsafbrydes deles afsendelsen ind i flere gennemløb. Her kan du angive antal breve pr. gennemløb afhængig at den maksimale scriptkøretid du har angivet i din php.ini.';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Timeout i sekunder';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Nogle mail servere begrænser antallet af e-mails der kan sendes pr. minut. Her kan du ændre timeout mellem hver gennemløb for at få mere kontrol over tidsrammen.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Start cyklus';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'I tilfælde af at afsendelses proceduren bliver afbrudt, kan du indtaste nummeret på den cyklus som du ønsker at fortsætte fra. Første cyklus starter ved 0.';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Send eksempel til';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Send et eksempel af nyhedsbrevet til denne e-mail adresse.';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Titel og emne';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'HTML-indhold';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Tekstindhold';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Bilag';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Skabelonindstillinger';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Avancerede indstillinger';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Afsendt';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Afsendt den %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Endnu ikke afsendt';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Afsendelses dato';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'Nyhedsbrevet er sendt til %s modtagere.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '%s ugyldig e-mail adresse(er) er blevet deaktiveret (se system log).';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'Denne kanal har ingen aktive abonnenter.';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'Afsender';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Vedhæftede filer';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Send eksempel';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Er du sikker på at du vil udsende nyhedsbrevet?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'Nyt nyhedsbrev';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Opret et nyt nyhedsbrev';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Detaljer for nyhedsbrev';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Vis detaljer for nyhedsbrev-ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Rediger nyhedsbrev';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Rediger nyhedsbrev-ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Kopier nyhedsbrev';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Kopier nyhedsbrev-ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Flyt nyhedsbrev';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Flyt nyhedsbrev-ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Slet nyhedsbrev';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Slet nyhedsbrev-ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Rediger kanal';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Rediger kanalen';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'Sæt ind i denne kanal';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Sæt ind efter nyhedsbrev ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Send nyhedsbrev';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Send nyhedsbrev-ID %s';
