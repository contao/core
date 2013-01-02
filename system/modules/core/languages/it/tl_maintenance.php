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
 * @link https://www.transifex.com/projects/p/contao/language/it/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'Elimina file temporanei e memoria cache';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = 'Seleziona la tipologia di dati temporanei da eliminare';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'Utente frontend';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = 'Per poter indicizzare le pagine protette occorre creare un utente alla quale è permesso accedere a queste pagine.';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = 'Attività';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = 'Descrizione';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Elimina file temporanei';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'I file temporanei sono stati eliminati';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'Aggiornamento automatico';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'Live update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'Attiva il Live Update';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'La versione corrente di Contao %s è aggiornata all\'ultima disponibile';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'È disponibile la nuova versione %s di Contao';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Inserisci il Live update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'La cartella temporanea (system/tmp) non è scrivibile';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'Visualizza Changelog';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Esegui l\'aggiornamento';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Contenuto dell\'archivio di aggiornamento';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'File nella copia di backup';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'File aggiornati';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Rigenera indice di ricerca';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Rigenera indice';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'Non sono state trovate pagine ricercabili';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = 'Attendere il caricamento completo della pagina prima di procedere';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Si prega di attendere mentre l\'indice di ricerca viene rigenerato';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'L\'indice di ricerca è stato rigenerato. Ora è possibile continuare.';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = 'Inserire il %s qui.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = 'Ripulisci gli indici del motore di ricerca interno';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = 'Ripulisci le tabelle <em> tl_search </em> e <em> tl_search_index </em>. In seguito, sarà necessario ricostruire l\'indice di ricerca.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = 'Ripulisci la tabella degli elementi eliminati';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = 'Ripulisce la tabella <em>tl_undo</em> che memorizza i record eliminati. Questo processo elimina permanentemente questi record.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'Ripulisci la tabella delle versioni';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'Ripulisce la tabella <em>tl_version </em> che memorizza le varie versioni degli elementi modificati. Questo processo elimina permanentemente questi record.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = 'Ripulisci la cache delle immagini';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = 'Rimuove le immagini generate in modo automatico e ripulisce la cache delle pagine.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'Ripulisci la cache degli script';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = 'Rimuove i file .css e .js generati automaticamente, ricrea i fogli di stile interni ed elimina la cache delle pagine.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'Ripulisci le pagine in cache';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'Rimuove la cache delle pagine di front end';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = 'Ripulisci la cache interna';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'Rimuove le versioni dei file DCA e delle lingue che sono in cache. E\' possibile disabilitare la cache interna attraverso l\'opzione nelle Impostazioni Generali.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = 'Ripulisci la cartella temporanea';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = 'Rimuovi i file temporanei';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'Ricrea i file XML';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'Ricrea  i file XML (le sitemap ed i feeds) e ripulisci le pagine eliminate dalla cache.';
