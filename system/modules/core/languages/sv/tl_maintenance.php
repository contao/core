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

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'Cache-tabeller';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = 'Välj vilken/vilka cache-tabell(er) som du vill rensa.';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'Vanlig användare';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = 'För att indexera skyddade sidor måste du skapa en vanlig användare som är tillåten att komma åt dessa sidor.';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = 'Åtgärd';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = 'Beskrivning';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Rensa cachen';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'Cachen har rensats';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'Live update';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'Live update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'Gå till Live Update';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'Din Contao version %s är den senaste versionen.';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'En nyare Contao-version med versionsnummer %s finns tillgänglig';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Ange ditt Live Update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'Den temporära mappen (system/tmp) är inte skrivbar';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'Visa ändringslogg';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Kör uppdateringarna';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Innehåll i uppdateringsarkivet';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'Säkerhetskopierade filer';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'Uppdaterade filer';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Bygg om sökindex';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Bygg om index';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'Inga sökbara sidor hittades';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = 'Vänligen vänta på att sidan är färdigladdad innan du fortsätter!';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Vänligen vänta på att sökindex byggs om.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'Sökindex har byggts om. Du kan nu fortsätta.';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = 'Vänligen ange din %s här.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = 'Rensa sökindex';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = 'Rensar tabellerna <em>tl_search</em> och <em>tl_search_index</em>. Efter detta måste du återbygga sökindex (se ovan).';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = 'Rensa ångra-tabell';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = 'Rensar tabellen <em>tl_undo</em> som lagrar raderade poster. Detta raderar posterna permanent.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'Rensa versionstabell';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'Rensar tabellen <em>tl_version</em> som lagrar tidigare versioner av poster. Detta raderar posterna permanent.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = 'Rensa bild-cache';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = 'Rensar bort de automatiskt genererade bilderna samt sid-cache så att det inte finns några länkningar mot raderade resurser.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'Rensa skript-cache';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = 'Rensar bort de automatiskt genererade .css- och .js-filer och återskapar interna stilmallar samt rensar sid-cache.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'Rensa sid-cache';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'Rensar cache:ade versioner av frontend-sidor.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = 'Rensa intern cache';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'Rensar bort den cache:ade versionerna av DCA och språkfiler. Du kan avaktivera intern cache permanent under backend-inställningar.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = 'Rensa temp-folder';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = 'Rensar bort temporära filer.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'Återskapa XML-filer';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'Återskapar XML-filer (sidkarta och flöden) samt rensar sid-cache så att det inte finns några länkningar till raderade resurser.';
