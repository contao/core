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
 * @link https://www.transifex.com/projects/p/contao/language/hu/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Tárgy';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Adja meg a hírlevél tárgyát';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Hírlevél álnév';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'A hírlevél álneve egy egyedi hivatkozás az oldalra, amit a numerikus azonosítója (ID) helyett lehet használni.';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'HTML tartalom';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Itt lehet megadni a hírlevél HTML tartalmát. Használd a helyettesítőt: <em> # # # # e-mail </em>, hogy helyezze be a címzett e-mail címét.';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Szöveg tartalom';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Itt lehet megadni a hírlevél szöveges tartalmát. Használd a helyettesítőt: <em>##email##</em>, hogy helyezze be a címzett e-mail címét.';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Melléklet hozzáadása';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Egy vagy több fájl csatolása a hírlevélhez.';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Mellékletek';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Válassza ki a csatolni kívánt fájlokat.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'E-mail sablon';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Itt lehet kiválasztani az e-mail sablont.';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Küldés egyszerű szövegként';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Küldje egyszerű szövegként a hírlevelet a HTML elemek nélkül';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'Külső képek';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Ne ágyazzon be képeket a HTML hírlevélbe.';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Feladó neve';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Itt tudja megadni a küldő nevét.';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'Feladó címe';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Itt lehet megadni a feladó egyedi címét.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Levelek ciklusonként';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'A küldési folyamat több ciklusra van osztva, hogy megakadályozza a szkript időtúllépését.';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Időtúllépés másodpercben';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Itt lehet módosítani a várakozási időt a percenként elküldött levelek ellenőrzési ciklusai között.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Ciklus indítása';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'Abban az esetben ha a küldő folyamat megszakad, akkor adja meg a ciklus számát ahonnan folytatni szeretné. A kiküldött levelek számát megtekintheti a <em>system/logs/newsletter_*.log</em> fájlban. Pl.: ha 120 levél lett kiküldve. üsse be "120" hogy a 121. levél küldésével folytassa. (a számlálás 0-val kezdődik)';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Előnézet küldése ide';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Küldje az előnézetet erre az e-mail címre.';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Megnevezés és tárgy';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'HTML tartalom';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Szöveg tartalom';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Mellékletek';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Sablon beállítások';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Haladó beállítások';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Elküldött';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = '%s elküldött';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Még el nem küldött';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Levelezési dátum';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'A hírlevél elküldve %s címzettnek';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '%s érvénytelen e-mail cím van tiltva. (tekintse meg a rendszernaplót)';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'Nincsenek aktív előfizetők a csatornán';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'Feladó';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Mellékletek';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Előnézet küldése';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Valóban el szeretné küldeni a hírlevelet?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'Új hírlevél';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Új hírlevél létrehozása';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Hírlevél részletei';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Az ID %s hírlevél részleteinek mutatása';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Hírlevél szerkesztése';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Az ID %s hírlevél szerkesztése';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Hírlevél duplázása';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Az ID %s hírlevél duplázása';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Hírlevél mozgatása';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Az ID %s hírlevél mozgatása';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Hírlevél törlése';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Az ID %s hírlevél törlése';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Csatorna szerkesztése';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Csatorna beállítások szerkesztése';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'Beillesztés ebbe a csatornába';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Beillesztés az ID %s hírlevél után';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Hírlevél küldése';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Az ID %s hírlevél küldése';
