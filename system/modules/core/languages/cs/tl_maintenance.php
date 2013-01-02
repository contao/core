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

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'Vyčistit cache';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = 'Vyberte zdroje z cache které chcete vyčistit';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'Uživatel frontendu';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = 'Aby se daly indikovat chráněné stránky, musí být přihlášený alespoň jeden uživatel frontendu.';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = 'Úpravy';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = 'Popis';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Vyčistit cache';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'Chache byla vyčištěna';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'Online aktualizace';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'Online aktualizace ID';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'Přejít na online aktualizace';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'Tato verze %s Contao je nejnovější dostupná verze';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'Je dostupná nová verze Contao %s';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Zadejte vaše ID online aktualizace';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'Do temp adresáře (systém/tmp) se nedá zapisovat';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'Prohlédnout si log změn';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Spustit aktualizaci';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Obsah této aktualizace';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'Zálohováné soubory';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'Aktualizované soubory';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Přetvořit vyhledávací index';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Přetvořit index';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'Nebyla nalezena žádná prohledávatelná';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = 'Počkejte prosím, dokud se stránka nenačte, než začne pokračovat v práci!';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Počkejte prosím, než se se vytvoří nový index pro vyhledávání.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'Index pro vyhledávání byl přestaven. Můžete pokračovat v práci.';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = 'Zadejte sem prosím Vaší %s.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = 'Vyčistit index vyhledávání';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = 'Vyprázdnit tabulky <em>tl_search</em> and <em>tl_search_index</em>. Potom můžete přestavět index vyhledávání (viz výše).';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = 'Vyčist tabulku historie';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = 'Vyprázdnit tabulku <em>tl_undo</em>, v níž se ukládají smazané záznamy. Tento krok navždy odstraní dané záznamy.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'Vyčistit tabulku verzí';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'Vyprázdnit tabulku <em>tl_version</em>, v níž se ukládají předešlé verze záznamů. Tento krok navždy odstraní dané záznamy.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = 'Vyčistit meziúložiště obrázků';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = 'Odstraní automaticky vytvořené obrázky a pak vyčistí meziúložiště stránek. Tím zmizí odkazy ke smazaným zdrojům.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'Vyčistit meziúložiště skriptů';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = 'Odstaní automaticky vytvořené soubory .css a .js, znovuvytvoří soubor již definovaných kaskádovitých stylů a pak vyčistí meziúložiště stránek.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'Vyčistit meziúložiště stránek';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'Odstraní meziuložené verze frontendových stránek. ';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = 'Vyčistit vnitřní meziúložiště';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'Odstraní meziuložené verze DCA a jazykových souborů. Můžete nastálo deaktivovat vnitřní meziúložiště v nastavení backendu (redakčního systému).';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = 'Vyčistit složky s dočasnými soubory';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = 'Odstraní dočasné soubory.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'Přetvořit soubory XML';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'Přetvoří soubory XML (mapu stránky a kanály) a vyčistí meziúložiště stránek. Tím zmizí odkazy na smazané zdroje. ';
