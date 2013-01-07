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

$GLOBALS['TL_LANG']['tl_page']['title'][0] = 'Sidnamn';
$GLOBALS['TL_LANG']['tl_page']['title'][1] = 'Sidans namn så som den visas i navigeringen, ska inte innehålla fler än 65 tecken.';
$GLOBALS['TL_LANG']['tl_page']['alias'][0] = 'Sidalias';
$GLOBALS['TL_LANG']['tl_page']['alias'][1] = 'Sidalias är ett unik referensnamn som kan användas istället för sidans numeriska ID-nummer när man refererar till sidan.<br />Detta är speciellt användbart om Contao använder statiska URLer.';
$GLOBALS['TL_LANG']['tl_page']['type'][0] = 'Sidtyp';
$GLOBALS['TL_LANG']['tl_page']['type'][1] = 'Ange en sidtyp beroende på sidans ändamål.';
$GLOBALS['TL_LANG']['tl_page']['pageTitle'][0] = 'Sidtitel';
$GLOBALS['TL_LANG']['tl_page']['pageTitle'][1] = 'Sidans titel syns t.ex. i webbsidans TITEL-tagg och i sökresultatet. Den ska inte innehålla mer än 65 tecken. <br />Om du lämnar detta fält tomt kommer sidnamnet att användas i stället.';
$GLOBALS['TL_LANG']['tl_page']['language'][0] = 'Språk';
$GLOBALS['TL_LANG']['tl_page']['robots'][0] = 'Robot-tag';
$GLOBALS['TL_LANG']['tl_page']['robots'][1] = 'Här kan du specificera hur sökmotorer ska hantera sidan.';
$GLOBALS['TL_LANG']['tl_page']['description'][0] = 'Sidbeskrivning';
$GLOBALS['TL_LANG']['tl_page']['description'][1] = 'Du kan ange en kort beskrivning av sidan som kommer att visas av sökmotorerna. <br />En sökmotor indexerar vanligtvis mellan 150 och 300 tecken.';
$GLOBALS['TL_LANG']['tl_page']['redirect'][0] = 'Omdirigeringstyp';
$GLOBALS['TL_LANG']['tl_page']['redirect'][1] = 'Tillfälliga omdirigeringar kommer skicka ett HTTP 302-huvud, permanenta ett HTTP 301-huvud.';
$GLOBALS['TL_LANG']['tl_page']['jumpTo'][0] = 'Omdirigera till';
$GLOBALS['TL_LANG']['tl_page']['jumpTo'][1] = 'Välj den sida som besökare kommer att omdirigeras till. Lämna tomt för att omdirigera till den första ordinarie undersidan.';
$GLOBALS['TL_LANG']['tl_page']['fallback'][0] = 'Språkreträtt';
$GLOBALS['TL_LANG']['tl_page']['fallback'][1] = 'Contao kommer automatiskt att omdirigera en användare till en rotsida på dennes språk eller till språkreträtt-sidan. Om det inte finns någon språkreträtt-sida kommer ett felmeddelande - <em>Ingen sida funnen</em> - att visas.';
$GLOBALS['TL_LANG']['tl_page']['dns'][0] = 'Domännamn';
$GLOBALS['TL_LANG']['tl_page']['dns'][1] = 'Om du tilldelar ett domännamn tilll en hemsidas rotsida kommer besökarna att omdirigeras till denna hemsida när de anger dess domännamn (t.ex.. <em>minsida.se</em> eller <em>subdomän.minsida.se</em>).';
$GLOBALS['TL_LANG']['tl_page']['adminEmail'][0] = 'E-postadress till hemsidans administratör';
$GLOBALS['TL_LANG']['tl_page']['adminEmail'][1] = 'Autogenererade E-postmeddelanden, som t.ex. bekräftelse för prenumerationer, kommer att skickas till denna adress.';
$GLOBALS['TL_LANG']['tl_page']['dateFormat'][0] = 'Datumformat';
$GLOBALS['TL_LANG']['tl_page']['dateFormat'][1] = 'Datumformat som kommer användas i systemet av PHP-funktionen date().';
$GLOBALS['TL_LANG']['tl_page']['timeFormat'][0] = 'Tidsformat';
$GLOBALS['TL_LANG']['tl_page']['timeFormat'][1] = 'Tidsformat som kommer användas i systemet av PHP-funktionen date().';
$GLOBALS['TL_LANG']['tl_page']['datimFormat'][0] = 'Datum- och tidsformat';
$GLOBALS['TL_LANG']['tl_page']['datimFormat'][1] = 'Datum- och tidsformat som kommer användas i systemet av PHP-funktionen date().';
$GLOBALS['TL_LANG']['tl_page']['createSitemap'][0] = 'Skapa en XML-sajtkarta';
$GLOBALS['TL_LANG']['tl_page']['sitemapName'][0] = 'Sajtkartans filnamn';
$GLOBALS['TL_LANG']['tl_page']['sitemapName'][1] = 'Ange ett namn för XML-filen.';
$GLOBALS['TL_LANG']['tl_page']['useSSL'][0] = 'Använd HTTPS i sajtkartor';
$GLOBALS['TL_LANG']['tl_page']['useSSL'][1] = 'Generera hemsidans sajtkartors URL:er med <em>https://</em>.';
$GLOBALS['TL_LANG']['tl_page']['autoforward'][0] = 'Omdirigering till en annan sida';
$GLOBALS['TL_LANG']['tl_page']['autoforward'][1] = 'Om du väljer detta alternativ kommer besökaren att omdirigeras till en annan sida (t.ex. en loginsida eller en välkomstsida).';
$GLOBALS['TL_LANG']['tl_page']['protected'][0] = 'Skydda sidan';
$GLOBALS['TL_LANG']['tl_page']['protected'][1] = 'Om du anger detta val kan du styra så att bara vissa medlemsgrupper har åtkomst till sidan.';
$GLOBALS['TL_LANG']['tl_page']['groups'][0] = 'Tillåtna medlemsgrupper';
$GLOBALS['TL_LANG']['tl_page']['groups'][1] = 'Här kan du tilldela åtkomst till en eller flera medlemsgrupper. Om du inte anger någon grupp kommer alla som loggar in i frontend att ha åtkomst till denna sida.';
$GLOBALS['TL_LANG']['tl_page']['includeLayout'][0] = 'Tilldela en layout';
$GLOBALS['TL_LANG']['tl_page']['includeLayout'][1] = 'Som standard använder en sida samma layout som dess föräldrasida. <br />Om du väljer detta val kan du tilldela en ny layout till den aktuella sidan och dess undersidor.';
$GLOBALS['TL_LANG']['tl_page']['layout'][0] = 'Sidlayout';
$GLOBALS['TL_LANG']['tl_page']['layout'][1] = 'Du kan hantera sidlayouter med modulen "Teman".';
$GLOBALS['TL_LANG']['tl_page']['mobileLayout'][0] = 'Mobil sidlayout';
$GLOBALS['TL_LANG']['tl_page']['mobileLayout'][1] = 'Denna layout kommer att användas om besökaren använder en mobil webbläsare';
$GLOBALS['TL_LANG']['tl_page']['includeCache'][0] = 'Tilldela ett cache-timeoutvärde';
$GLOBALS['TL_LANG']['tl_page']['includeCache'][1] = 'Ange ett cache-timeoutvärde för sidan och dess undersidor.';
$GLOBALS['TL_LANG']['tl_page']['cache'][0] = 'Cache-timeout';
$GLOBALS['TL_LANG']['tl_page']['cache'][1] = 'Inom tiden för cache-timeout kommer en sida att laddas från en cache-tabell. <br />Detta kommer att sänka laddningstiden för din hemsida.';
$GLOBALS['TL_LANG']['tl_page']['includeChmod'][0] = 'Tilldela behörigheter';
$GLOBALS['TL_LANG']['tl_page']['includeChmod'][1] = 'Med behörigheter kan du definiera till vilken gräns en backend-användare kan ändra i en sida och sina artiklar. <br />Om du inte väljer detta alernativ kommer sidan att ha samma behörigheter som sin förälder.';
$GLOBALS['TL_LANG']['tl_page']['cuser'][0] = 'Ägare';
$GLOBALS['TL_LANG']['tl_page']['cuser'][1] = 'Ange en användare som ägare av denna sida.';
$GLOBALS['TL_LANG']['tl_page']['cgroup'][0] = 'Grupp';
$GLOBALS['TL_LANG']['tl_page']['cgroup'][1] = 'Ange en grupp som ägare av denna sida.';
$GLOBALS['TL_LANG']['tl_page']['chmod'][0] = 'Behörigheter';
$GLOBALS['TL_LANG']['tl_page']['chmod'][1] = 'Varje sida har tre behörighetsnivåer: en för Användare, en för Användargrupp och en för Alla. Du kan tilldela olika behörigheter till var och en av dessa nivåer.';
$GLOBALS['TL_LANG']['tl_page']['noSearch'][0] = 'Sök inte på denna sida';
$GLOBALS['TL_LANG']['tl_page']['noSearch'][1] = 'Om du väljer detta alternativ kommer denna sida att uteslutas från sökoperationer på hemsidan.';
$GLOBALS['TL_LANG']['tl_page']['cssClass'][0] = 'Stilmallsklass';
$GLOBALS['TL_LANG']['tl_page']['cssClass'][1] = 'Här kan du ange en eller flera stilmalls-klasser (CSS-klass) som kommer att användas i navigationsmenyn samt i body-taggen. Klasser anges utan prefix (punkt .).';
$GLOBALS['TL_LANG']['tl_page']['sitemap'][0] = 'Visa i sajtkartan';
$GLOBALS['TL_LANG']['tl_page']['sitemap'][1] = 'Här kan du definiera om sidan visas i sajtkartan.';
$GLOBALS['TL_LANG']['tl_page']['hide'][0] = 'Göm sidan i navigeringen';
$GLOBALS['TL_LANG']['tl_page']['hide'][1] = 'Om du väljer detta alternativ kommer sidan inte att visas i navigationsmenyer på hemsidan.';
$GLOBALS['TL_LANG']['tl_page']['guests'][0] = 'Visa enbart för gäster';
$GLOBALS['TL_LANG']['tl_page']['guests'][1] = 'Göm denna sida när medlemmen är inloggad.';
$GLOBALS['TL_LANG']['tl_page']['tabindex'][0] = 'Tabbordning';
$GLOBALS['TL_LANG']['tl_page']['tabindex'][1] = 'Detta värde specificerar det aktuella objektets position i tabbordningen. <br />Du kan ange ett nummer mellan 1 och 32767.';
$GLOBALS['TL_LANG']['tl_page']['accesskey'][0] = 'Kortkommando';
$GLOBALS['TL_LANG']['tl_page']['accesskey'][1] = 'Ett kortkommando är en kombination av [ALT]-tangenten och ett annat tecken som i detta fall ger ett visst objekt fokus.';
$GLOBALS['TL_LANG']['tl_page']['published'][0] = 'Publicerad';
$GLOBALS['TL_LANG']['tl_page']['published'][1] = 'Om du markerar detta alternativ kommer sidan att visas för besökarna på din webbplats.';
$GLOBALS['TL_LANG']['tl_page']['start'][0] = 'Börja visa fr.o.m';
$GLOBALS['TL_LANG']['tl_page']['start'][1] = 'Om du anger att datum här kommer den aktuella sidan att börja visas från och med denna dag.';
$GLOBALS['TL_LANG']['tl_page']['stop'][0] = 'Sluta visa fr.o.m';
$GLOBALS['TL_LANG']['tl_page']['stop'][1] = 'Om du anger att datum här kommer den aktuella sidan att sluta visas från och med denna dag.';
$GLOBALS['TL_LANG']['tl_page']['title_legend'] = 'Namn och typ';
$GLOBALS['TL_LANG']['tl_page']['meta_legend'] = 'Meta-information';
$GLOBALS['TL_LANG']['tl_page']['system_legend'] = 'Systeminställningar';
$GLOBALS['TL_LANG']['tl_page']['redirect_legend'] = 'Omdirigeringsinställningar';
$GLOBALS['TL_LANG']['tl_page']['dns_legend'] = 'DNS-inställningar';
$GLOBALS['TL_LANG']['tl_page']['global_legend'] = 'Globala inställningar';
$GLOBALS['TL_LANG']['tl_page']['mobile_legend'] = 'Inställningar för mobil';
$GLOBALS['TL_LANG']['tl_page']['sitemap_legend'] = 'XML-sajtkarta';
$GLOBALS['TL_LANG']['tl_page']['forward_legend'] = 'Omdirigera automatiskt';
$GLOBALS['TL_LANG']['tl_page']['protected_legend'] = 'Åtkomstskydd';
$GLOBALS['TL_LANG']['tl_page']['layout_legend'] = 'Layout-inställningar';
$GLOBALS['TL_LANG']['tl_page']['cache_legend'] = 'Cache-inställningar';
$GLOBALS['TL_LANG']['tl_page']['chmod_legend'] = 'Användarbehörighet';
$GLOBALS['TL_LANG']['tl_page']['search_legend'] = 'Sökinställningar';
$GLOBALS['TL_LANG']['tl_page']['expert_legend'] = 'Expertinställningar';
$GLOBALS['TL_LANG']['tl_page']['tabnav_legend'] = 'Tangentbordsnavigation';
$GLOBALS['TL_LANG']['tl_page']['publish_legend'] = 'Publiceringsinställningar';
$GLOBALS['TL_LANG']['tl_page']['permanent'] = '301 Permanent omdirigering';
$GLOBALS['TL_LANG']['tl_page']['temporary'] = '302 Tillfällig omdirigering';
$GLOBALS['TL_LANG']['tl_page']['map_default'] = 'Standard';
$GLOBALS['TL_LANG']['tl_page']['map_always'] = 'Visa alltid';
$GLOBALS['TL_LANG']['tl_page']['map_never'] = 'Visa aldrig';
$GLOBALS['TL_LANG']['tl_page']['new'][0] = 'Ny sida';
$GLOBALS['TL_LANG']['tl_page']['new'][1] = 'Skapa en ny sida';
$GLOBALS['TL_LANG']['tl_page']['show'][0] = 'Visa siddetaljer';
$GLOBALS['TL_LANG']['tl_page']['show'][1] = 'Visa detaljer för sidan med ID %s';
$GLOBALS['TL_LANG']['tl_page']['edit'][0] = 'Redigera sida';
$GLOBALS['TL_LANG']['tl_page']['edit'][1] = 'Redigera sidan med ID %s';
$GLOBALS['TL_LANG']['tl_page']['cut'][0] = 'Flytta sida';
$GLOBALS['TL_LANG']['tl_page']['cut'][1] = 'Flytta sida med ID %s';
$GLOBALS['TL_LANG']['tl_page']['copy'][0] = 'Kopiera sida';
$GLOBALS['TL_LANG']['tl_page']['copy'][1] = 'Kopiera sidan med ID %s';
$GLOBALS['TL_LANG']['tl_page']['copyChilds'][0] = 'Kopiera sida med undersidor';
$GLOBALS['TL_LANG']['tl_page']['copyChilds'][1] = 'Kopiera sidan med ID %s tillsammans med ev. undersidor';
$GLOBALS['TL_LANG']['tl_page']['delete'][0] = 'Ta bort sida';
$GLOBALS['TL_LANG']['tl_page']['delete'][1] = 'Ta bort sidan med ID %s';
$GLOBALS['TL_LANG']['tl_page']['toggle'][0] = 'Publicera / avpublicera sida';
$GLOBALS['TL_LANG']['tl_page']['toggle'][1] = 'Publicera / avpublicera sidan med ID %s';
$GLOBALS['TL_LANG']['tl_page']['pasteafter'][0] = 'Klistra in efter';
$GLOBALS['TL_LANG']['tl_page']['pasteafter'][1] = 'Klistra in efter sidan med ID %s';
$GLOBALS['TL_LANG']['tl_page']['pasteinto'][0] = 'Klistra in i';
$GLOBALS['TL_LANG']['tl_page']['pasteinto'][1] = 'Klistra in i sidan med ID %s';
$GLOBALS['TL_LANG']['tl_page']['articles'][0] = 'Redigera artiklar';
$GLOBALS['TL_LANG']['tl_page']['articles'][1] = 'Redigera artiklarna på sidan med ID %s';
$GLOBALS['TL_LANG']['CACHE'][0] = '0 (Ingen cachning)';
$GLOBALS['TL_LANG']['CACHE'][5] = '5 sekunder';
$GLOBALS['TL_LANG']['CACHE'][15] = '15 sekunder';
$GLOBALS['TL_LANG']['CACHE'][30] = '30 sekunder';
$GLOBALS['TL_LANG']['CACHE'][60] = '60 sekunder';
$GLOBALS['TL_LANG']['CACHE'][300] = '5 minuter';
$GLOBALS['TL_LANG']['CACHE'][900] = '15 minuter';
$GLOBALS['TL_LANG']['CACHE'][1800] = '30 minuter';
$GLOBALS['TL_LANG']['CACHE'][3600] = '60 minuter';
$GLOBALS['TL_LANG']['CACHE'][10800] = '3 timmar';
$GLOBALS['TL_LANG']['CACHE'][21600] = '6 timmar';
$GLOBALS['TL_LANG']['CACHE'][43200] = '12 timmar';
$GLOBALS['TL_LANG']['CACHE'][86400] = '24 timmar';
$GLOBALS['TL_LANG']['CACHE'][259200] = '3 dagar';
$GLOBALS['TL_LANG']['CACHE'][604800] = '7 dagar';
$GLOBALS['TL_LANG']['CACHE'][2592000] = '30 dagar';
