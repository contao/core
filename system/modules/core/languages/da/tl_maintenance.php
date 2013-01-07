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

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'Mellemlagertabeller';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = 'Vælg venligst de mellemlagertabeller du vil afkorte.';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'Frontend bruger';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = 'For at indeksere beskyttede sider, skal du oprette en frontend bruger der har adgang til disse sider.';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = 'Job';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = 'Beskrivelse';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Tøm mellemlager';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'Mellemlageret er tømt';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'Live-opdatering';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'Live-opdaterings ID';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'Gå til Live Update';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'Din version %s af TYPOlight er ajour';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'En nyere version %s af TYPOlight er tilgængelig';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Indtast venligst dit live-opdaterings ID';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'Der kan ikke skrives i den midlertidige mappe (system/tmp)';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'Vis ændringslog';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Opdater';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Indhold af opdateringsarkivet';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'Sikkerhedskopierede filer';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'Opdaterede filer';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Genopbyg søgeindeks';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Genopbyg indeks';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'Der blev ikke fundet nogle søgbare sider';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = 'Vent venligst til at siden er færdig med at indlæse, før du fortsætter!';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Vent venligst mens søgeindekset bliver genopbygget';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'Søgeindekset er genopbygget';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = 'Indtast venligst dit %s her.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = 'Tøm søgeindekset';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = 'Tømmer tabellerne <em>tl_search</em> og <em>tl_search_index</em>. Efterfølgende skal du genopbygge søgeindekset (se herover).';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = 'Tøm fortryd tabellen';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = 'Tømmer <em>tl_undo</em> tabellen der indeholder midlertidige sikkerhedskopier af slettede data. Dette job sletter disse data for altid.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'Tøm versions tabellen';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'Tømmer <em>tl_version</em> tabellen der gemmer tidligere versioner af data. Dette job sletter disse data.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = 'Tøm den midlertidige hukommelse for billeder';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = 'Sletter de automatisk genererede miniaturebilleder samt tømmer den midlertidige hukommelse for sider så der ikke linkes til slettede ressourcer.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'Tøm den midlertidige hukommelse for scripts';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = 'Sletter de automatisk genererede .css og .js filer, genskaber de interne style sheets og tømmer herefter den midlertidige hukommelse for sider.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'Tøm den midlertidige hukommelse for sider';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'Sletter den midlertidige hukommelse af front end siden.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = 'Tøm den interne midlertidige hukommelse';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'Sletter den midlertidige hukommelse over DCA samt sprog filer. Du kan deaktivere den interne midlertidige hukommelse permanent under back end indstillingerne.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = 'Tøm temp mappen';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = 'Sletter de midlertidige filer';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'Genskaber XML filer';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'Genskaber XML filer (sitemaps samt feeds) og tømmer herefter den midlertige hukommelse for sider så der ikke linkes til slettede ressourcer.';
