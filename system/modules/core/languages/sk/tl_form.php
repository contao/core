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

$GLOBALS['TL_LANG']['tl_form']['title'][0] = 'Názov';
$GLOBALS['TL_LANG']['tl_form']['title'][1] = 'Prosím zadajte názov formulára.';
$GLOBALS['TL_LANG']['tl_form']['alias'][0] = 'Alias formulára';
$GLOBALS['TL_LANG']['tl_form']['alias'][1] = 'Alias formulára je unikátny odkaz na formulár, ktorý je možné vyvolať namiesto ID formulára.';
$GLOBALS['TL_LANG']['tl_form']['jumpTo'][0] = 'Presmerovať po odoslaní na stránku';
$GLOBALS['TL_LANG']['tl_form']['jumpTo'][1] = 'Formulár je obvykle prevzatý inou stránkou, ktorá spracuje formulárové dáta, alebo zobrazí správu "Ďakujeme Vám". Túto stránku môžete zvoliť tu.';
$GLOBALS['TL_LANG']['tl_form']['sendViaEmail'][0] = 'Pošlať fromulárové dáta e-mailom';
$GLOBALS['TL_LANG']['tl_form']['sendViaEmail'][1] = 'Keď zvolíte túto možnosť, formulárové dáta budú odoslané na e-mailovú adresu.';
$GLOBALS['TL_LANG']['tl_form']['recipient'][0] = 'Adresát';
$GLOBALS['TL_LANG']['tl_form']['recipient'][1] = 'Vložte jednu, alebo viac e-mailových adries. Viac adries oddeľte čiarkou. Môžete tiež použiť "friendly name format" (napr. "Meno [meno@domena.com]").';
$GLOBALS['TL_LANG']['tl_form']['subject'][0] = 'Predmet';
$GLOBALS['TL_LANG']['tl_form']['subject'][1] = 'Zadajte prosím predmet. Keď nezadáte predmet, zvýši sa tým pravdepodobnosť, že e-mail bude identifikovaný ako nevyžiadaný pošta (SPAM).';
$GLOBALS['TL_LANG']['tl_form']['format'][0] = 'Formát dát';
$GLOBALS['TL_LANG']['tl_form']['format'][1] = 'Zvoľte prosím formát dát.';
$GLOBALS['TL_LANG']['tl_form']['raw'][0] = 'Nespracované dáta';
$GLOBALS['TL_LANG']['tl_form']['raw'][1] = 'Formulárové dáta budú poslané ako čistý text, každé pole na novom riadku.';
$GLOBALS['TL_LANG']['tl_form']['xml'][0] = 'XML súbor';
$GLOBALS['TL_LANG']['tl_form']['xml'][1] = 'Formulárové dáta budú priložené k e-mailu ako XML súbor.';
$GLOBALS['TL_LANG']['tl_form']['csv'][0] = 'CSV súbor';
$GLOBALS['TL_LANG']['tl_form']['csv'][1] = 'Formulárové dáta budú priložené k e-mailu ako CSV súbor (hodnoty oddelené čiarkami).';
$GLOBALS['TL_LANG']['tl_form']['email'][0] = 'E-mailový formát';
$GLOBALS['TL_LANG']['tl_form']['email'][1] = 'Tento formát očakáva polia <em>email</em>, <em>subject</em>, <em>message</em> a <em>cc</em> (pošli kópiu e-mailu odosielateľovi). Ostatné názvy polí sú ignorované. Nahrávanie (upload) súborov je povolené.';
$GLOBALS['TL_LANG']['tl_form']['skipEmtpy'][0] = 'Preskočiť prázdne polia';
$GLOBALS['TL_LANG']['tl_form']['skipEmtpy'][1] = 'Nezahrnie prázdne polia do e-mailu.';
$GLOBALS['TL_LANG']['tl_form']['storeValues'][0] = 'Ukladať hodnoty';
$GLOBALS['TL_LANG']['tl_form']['storeValues'][1] = 'Uložiť hodnoty odoslaného formulára do databázy.';
$GLOBALS['TL_LANG']['tl_form']['targetTable'][0] = 'Cieľová tabuľka';
$GLOBALS['TL_LANG']['tl_form']['targetTable'][1] = 'Prosím zvoľte tabuľku, do ktorej chcete vkladať formulárové dáta.';
$GLOBALS['TL_LANG']['tl_form']['method'][0] = 'Metóda spracovania formulára';
$GLOBALS['TL_LANG']['tl_form']['method'][1] = 'Prosím vyberte metódu spracovania formulára (predvolená: POST).';
$GLOBALS['TL_LANG']['tl_form']['attributes'][0] = 'ID a class (trieda) kaskádového štýlu';
$GLOBALS['TL_LANG']['tl_form']['attributes'][1] = 'Tu môžete vložiť ID kaskádového štýlu (ID atribút) a jednu, alebo viac tried (atribút class), aby bolo možné formátovať formulár pomocou CSS.';
$GLOBALS['TL_LANG']['tl_form']['formID'][0] = 'ID formulára';
$GLOBALS['TL_LANG']['tl_form']['formID'][1] = 'Tu môžete zadať nepovinné ID formulára (potrebné k triggerovým Contao modulom).';
$GLOBALS['TL_LANG']['tl_form']['tableless'][0] = 'Beztabuľkový vzhľad';
$GLOBALS['TL_LANG']['tl_form']['tableless'][1] = 'Keď zvolíte túto voľbu, formulár bude stvárnený (vyrenderovaný) bez použitia tabuliek.';
$GLOBALS['TL_LANG']['tl_form']['allowTags'][0] = 'Povoliť HTML tagy';
$GLOBALS['TL_LANG']['tl_form']['allowTags'][1] = 'Ak zvolíte túto možnosť, určité HTML značky nebudú vymazané (podrobnosti na <em>allowedTags</em>).';
$GLOBALS['TL_LANG']['tl_form']['tstamp'][0] = 'Dátum revízie';
$GLOBALS['TL_LANG']['tl_form']['tstamp'][1] = 'Dátum a čas poslednej revízie';
$GLOBALS['TL_LANG']['tl_form']['title_legend'] = 'Názov a presmerovacia stránka';
$GLOBALS['TL_LANG']['tl_form']['email_legend'] = 'Pošlať formulárové dáta';
$GLOBALS['TL_LANG']['tl_form']['store_legend'] = 'Uložiť formulárové dáta';
$GLOBALS['TL_LANG']['tl_form']['expert_legend'] = 'Rozšírené nastavenia';
$GLOBALS['TL_LANG']['tl_form']['config_legend'] = 'Nastavenie formulára';
$GLOBALS['TL_LANG']['tl_form']['new'][0] = 'Nový formulár';
$GLOBALS['TL_LANG']['tl_form']['new'][1] = 'Vytvoriť nový formulár';
$GLOBALS['TL_LANG']['tl_form']['show'][0] = 'Detaily formulára';
$GLOBALS['TL_LANG']['tl_form']['show'][1] = 'Zobraziť detaily formulára ID %s';
$GLOBALS['TL_LANG']['tl_form']['edit'][0] = 'Upraviť formulár';
$GLOBALS['TL_LANG']['tl_form']['edit'][1] = 'Upraviť formulár ID %s';
$GLOBALS['TL_LANG']['tl_form']['editheader'][0] = 'Upraviť nastavenia formulára';
$GLOBALS['TL_LANG']['tl_form']['editheader'][1] = 'Upraviť nastavenia formulára ID %s';
$GLOBALS['TL_LANG']['tl_form']['copy'][0] = 'Kopírovať formulár';
$GLOBALS['TL_LANG']['tl_form']['copy'][1] = 'Kopírovať formulár D %s';
$GLOBALS['TL_LANG']['tl_form']['delete'][0] = 'Odstrániť formulár';
$GLOBALS['TL_LANG']['tl_form']['delete'][1] = 'Odstrániť formulár ID %s';
