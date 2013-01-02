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

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'Vymazať cache pamäť';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = 'Prosím vyberte cache pamäťové zdroje, ktoré chcete vyprázdniť.';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'Front end používateľ';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = 'K indexovaniu chránených stránok je potrebné vytvoriť frontend používateľa, ktorý má oprávnenie pristupovať k týmto stránkam.';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = 'Úlohy';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = 'Popis';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Vyčisti cache';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'Bude vyčistená cache pamäť';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'On-line aktualizácia';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'ID On-line aktualizácie';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'Prejsť na On-line aktualizáciu';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'Vaša verzia Contao %s je aktualizovaná';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'Nová verzia Contao %s je dostupná';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Prosím, vložte ID vašej on-line aktualizácie';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'Priečinok dočasného zápisu (system/tmp) nemá práva k zapisovaniu';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'Prezrieť zmeny';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Spustiť aktualizáciu';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Obsah tejto aktualizácie';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'Zálohované súbory';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'Aktualizované súbory';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Prestavať index vyhľadávania';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Prestavať index';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'Žiadne prehľadávatelné stránky neboli nájdené';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = 'Prosím čakajte na kompletné načítanie stránky predtým, ako budete pokračovať ďalej!';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Prosím čakajte pokiaľ sa prestaví index vyhľadávania.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'Index vyhľadávania sa prestaval. Môžte pokračovať.';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = 'Prosím tu zadajte Vašu %s.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = 'Vyprázdniť index vyhľadávania';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = 'Vyprázdni tabuľky <em>tl_search</em> a <em>tl_search_index</em>. Potom môžete prestavať index vyhľadávania (viď vyššie).';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = 'Vyprázdniť tabuľku histórie';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = 'Vyprázdni tabuľku <em>tl_undo</em>, v ktorej sa ukladajú odstránené záznamy. Tento krok navždy odstráni dané záznamy.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'Vyprázdniť tabuľku verzií';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'Vyprázdni tabuľku <em>tl_version</em>, v ktorej sa ukladajú predošlé verzie záznamov. Tento krok navždy odstráni dané záznamy.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = 'Vyprázdniť dočasné úložisko obrázkov';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = 'Odstráni automaticky vytvorené obrázky a potom vyprázdni dočasné úložisko stránok. Týmto krokom zaniknú odkazy k odstráneným zdrojom.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'Vyprázdniť dočasné úložisko skriptov';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = 'Odstráni automaticky vytvorené súbory .css a .js, znovuvytvorí súbor už nadefinovaných kaskádových štýlov a potom vyprázdni dočasné úložisko stránok.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'Vyprázdniť dočasné úložisko stránok';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'Odstráni dočasne uložené verzie frontendových stránok.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = 'Vyprázdniť vnútorné dočasné úložisko';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'Odstráni dočasne uložené verzie DCA a jazykových súborov. Môžete nastálo deaktivovať vnútorné dočasné úložisko v nastaveniach backendu.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = 'Vyprázdniť priečinok s dočasnými súbormi';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = 'Odstráni dočasné súbory.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'Znovu vytvorí XML súbory';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'Znovu vytvorí XML súbory (mapu stránky a kanály) a vyprázdni dočasné úložisko stránok. Týmto krokom zaniknú odkazy k odstráneným zdrojom.';
