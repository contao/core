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
 * @link https://www.transifex.com/projects/p/contao/language/cs/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Předmět';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Zadejte prosím předmět zpravodaje.';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Alias zpravodaje';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'Alias zpravodaje je jednoznačný odkaz, který může být použitý namísto čísla ID zpravodaje.';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'Obsah v HTML';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Zde můžete zadat obsah v HTML Vašeho Zpravodaje. Použijte link <em>##email##</em> pro vložení mailové adresy příjemce.';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Textový obsah';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Zde můžete zadat textový obsah Vašeho Zpravodaje. Použijte link <em>##email##</em> pro vložení mailové adresy příjemce.';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Přidat přílohy';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Přidejte jednu nebo více příloh ke Zpravodaji.';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Přílohy';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Vyberte prosím soubory, které chcete připojit k Vašemu zpravodaji a které mají být načteny z adresáře souborů.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'Předloha e-mailu';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Zde můžete zvolit předlohu e-mailu.';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Zaslat jako neformátovaný text';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Zaslat Zpravodaje jak nenaformátovaný text, tzn. bez použití znaků HTML.';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'Externí obrázky';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Nevkládat obrázky v HTML zpravodaji.';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Jméno odesílatele';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Zde můžete uvést jméno odesílatele.';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'Mailová adresa odesílatele';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Zde můžete zadat mailovou adresu odesílatele.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Cykly rozeslání mailů';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'Rozeslání mailů proběhne v několika krocích, aby nedošlo k přerušení.';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Čekací doba v sekundách';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Zde můžete nastavit čekací dobu mezi cykly rozesílání mailů, abyste mohli kontroloval počet odeslaných mailů za minutu.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Začátek nového cyklu odesílání';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'V případě, že je přerušen proces odesílání, můžete zde uvést číslo s posledním příjemcem, po kterém se má začít opětovné odesílání. Můžete zkontrolovat, kolik zpráv bylo odesláno v <em>system/logs/newsletter_*.log</em> souboru. Např. bylo odesláno 120 zpráv, zadejte 120, aby se pokračovalo 121 příjemcem (začíná se 0 pro první cyklus).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Testovací mail odeslat na';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Odeslat testovací mail se Zpravodajem na tuto mailovou adresu.';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Název a předmět';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'Obsah HTML';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Text';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Přílohy';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Nastavení předlohy';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Rozšířená nastavení';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Posláno';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Posláno %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Ještě neodesláno';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Datum odeslání';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'Zpravodaj byl rozeslán %s příjemcům.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '%s neplatné mailové adresy byly deaktivovány.';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'Neexistuje žádný odebíratel pro tento Zpravodaj.';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'Formulář';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Přílohy';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Testovací mail';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Chcete opravdu odeslat tento Zpravodaj?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'Nový zpravodaj';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Vytvoří nový zpravodaj.';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Zobrazit podrobnosti';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Zobrazit podrobnosti ke zpravoji ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Upravit zpravodaj';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Upraví zpravodaje ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Kopírovat zpravodaj';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Zkopíruje zpravodaje ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Přesunout zpravodaj';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Přesune zpravodaje ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Smazat zpravodaj';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Smaže zpravodaje ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Upravit distribuci';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Upraví nastavení distribuce';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'Vložit do této distribuce';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Vložit za zpravodaj ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Poslat zpravodaj';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Rozešle zpravodaj %s';
