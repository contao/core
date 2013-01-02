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
 * @link https://www.transifex.com/projects/p/contao/language/nl/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'Opschonen gegevens uit de cache';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = 'Selecteer de gegevens die u wilt opschonen.';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'Front-end gebruiker';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = 'Automatisch inloggen van een front-end gebruiker om beveiligde pagina\'s te indexeren.';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = 'Taak';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = 'Omschrijving';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Opschonen data';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'De cache is opgeschoond';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'Live Update';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'Live Update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'Ga naar Live Update';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'Uw Contao versie %s is up-to-date';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'Een nieuwe Contao versie %s is beschikbaar';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Vul uw Live Update ID in';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'De tijdelijke map (system/tmp) is niet beschrijfbaar';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'Toon changelog';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Start het updaten';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Inhoud van het update archief';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'Backup bestanden';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'Bijgewerkte bestanden';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Zoekindex heropbouwen';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Index heropbouwen';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'Geen doorzoekbare pagina\'s gevonden.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = 'Wacht tot de pagina helemaal is ingelezen voordat u verder gaat!';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Even wachten terwijl de zoekindex opnieuw wordt opgebouwd.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'De zoekindex is opnieuw opgebouwd. U kunt doorgaan.';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = 'Vul uw %s hier in.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = 'Zoekindex opschonen';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = 'Kort de tabellen <em> tl_search </em> en <em> tl_search_index </em> in. Hierna dient u de zoekindex (zie hierboven) weer opnieuw op te bouwen.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = 'Opschonen van de undo tabel';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = 'Kort de <em>tl_undo</em> tabel in welke de verwijderde records bevat.Deze actie zal de records permanent verwijderen.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'Opschonen versie tabel';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'Kort de <em> tl_version </em> tabel in welke de vorige versies van een opgeslagen record bevat. Deze actie verwijdert permanent deze records.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = 'Opschonen afbeelding cache';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = 'Hiermee verwijdert u de automatisch gegenereerde beelden en schoont u de pagina cache op, zodat er geen links naar verwijderde bronnen zijn.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'Opschonen script cache';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = 'Verwijdert de automatisch gegenereerde. css en. js bestanden, maakt de interne style sheets aan en schoont de pagina cache op.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'Opschonen pagina cache';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'Verwijderd de gecachte versies van de front-end pagina`s.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = 'Opschonen interne cache';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'Verwijdert de gecachte versies van het DCA en taalbestanden. U kunt de interne cache permanent uitschakelen in de back-end instellingen.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = 'Opschonen temp map';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = 'Verwijderd tijdelijke bestanden';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'XML bestanden opnieuw opbouwen';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'Bouwt de XML-bestanden (sitemaps en feeds) en schoont de pagina cache op, zodat er geen links naar verwijderde bronnen zijn.';
