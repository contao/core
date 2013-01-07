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
 * @link https://www.transifex.com/projects/p/contao/language/sk/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Predmet';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Zadajte predmet správy.';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Alias správy';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'Alias správy je unikátny reťazec popisujúci správu a nahardazuje ID.';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'Obsah';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Zadajte obsah správy. Ak chcete vložiť e-mail prijímateľa, môžete použiť <em>##email##</em>. Odkaz na stránku, kde sa dá odhlásiť, vytvorte ako <em>http://www.domain.com/unsubscribe-page.html?email=##email##</em>.';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Textový obsah';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Prosím zadajte textový obsah správy. Použite skratky <em>##email##</em> k vloženiu adresy prijímateľa. Vygenerujte odkaz na odhlásenie sa z odberu správ v tvare <em>http://www.domain.com/unsubscribe-page.html?email=##email##</em>.';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Pripojiť prílohu';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Pripojiť jeden, alebo viac súborov.';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Prílohy';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Prosím vyberte súbory, ktoré chcete pripojiť k správe.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'Šablóna e-mailu';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Prosím vyberte šablónu správy (šablónová skupina <em>mail_</em>).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Poslať ako text';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Ak zvolíte túto možnosť, e-mail sa pošle ako text. Všetky HTML značky sa odstránia.';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'Externé obrázky';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Nevkladajte obrázky priamo do HTML správ.';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Meno odosielateľa';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Zadajte meno odosielateľa. Zobrazí sa v hlavičke správy.';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'Adresa odosielateľa';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Ak nezadáte e-mail odosielateľa, použije sa e-mail administrátora.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Správ počas cyklu';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'Zabrániť skriptu pred timeoutom, proces odosielania správ môžete rozdeliť-obmedziť na určitý počet odoslaných správ v jednom timeout cykle.';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Timeout v sekundách';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Niektoré mailservery obmedzujú počet odoslaných správ za minútu. Nastavte dĺžku jedného cyklu.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Začiatok nového cyklu odosielania';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'V prípade, že je prerušený proces odosielania, môžete zadať číslo posledného príjemcu, po ktorom sa má začať opätovné odesielanie. Môžete skontrolovať, koľko správ bolo odoslaných v <em>system/logs/newsletter_*.log</em> súbore. Napr. bolo odoslaných 120 správ, zadajte 120, aby sa pokračovalo 121 príjemcom (začína sa 0 pre prvý cyklus).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Zaslať ukážku na';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Zaslať ukážku správy špecifickému adresátorvi. Slúži pre kontrolu.';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Názov a predmet';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'HTML obsah';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Textový obsah';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Prílohy';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Nastavenia šablóny';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Rozšírené nastavenia';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Odoslaná';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Odoslaná dňa';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Neodoslaná';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Dátum odoslania';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'Správa bola poslaná %s používateľom.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '%s neplatné e-mailové adresy boli zablokované (viď. system log).';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'Žiadni používatelia neodoberajú správy z tohto kanála.';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'Od';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Prílohy';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Náhľad';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Naozaj chcete odoslať túto správu?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'Nová správa';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Vytvoriť novú správu';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Detaily správy';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Zobraziť detaily správy s ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Upraviť správu';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Upraviť správu s ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Duplikovať správu';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Duplikovať správu s ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Premiestniť správu';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Premiestniť správu s ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Odstrániť správu';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Odstrániť správu s ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Upraviť kanál';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Upraviť nastavenia kanálu';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'Vložiť do tohto kanálu';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Vložiť za správu s ID %s';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Poslať správu';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Poslať správu s ID %s';
