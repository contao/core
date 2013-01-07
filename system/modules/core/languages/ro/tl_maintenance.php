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
 * @link https://www.transifex.com/projects/p/contao/language/ro/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'Şterge cache';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = 'Alegeţi tabelele de cache pe care doriţi să le goliţi.';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'Utilizator de front-end';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = 'Pentru a indexa paginile protejate, trebuie să creaţi un utilizator de front-end care să aibă acces la aceste pagini.';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = 'Sarcină';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = 'Descriere';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Şterge cache-ul';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'Cache-ul a fost golit';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'Update online';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'ID pentru update online';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'Mergi la Actualizare Online';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'Versiunea %s Contao este actualizată';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'O nouă versiune %s Contao este disponibilă';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Introduceţi ID-ul pentru update online';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'În directorul temporar (system/tmp) nu se poate scrie';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'Vezi changelog';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Execută actualizare';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Conţinutul arhivei de actualizare';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'Fişiere păstrate pentru backup';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'Fişiere actualizate';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Reconstruieşte indexul pentru căutare';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Reconstruieşte index';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'Nu există pagini pentru căutare';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = 'Aşteptaţi ca pagina să se încarce complet înainte de a continua!';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Aşteptaţi până când indexul de căutare este reconstruit';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'Indexul a fost reconstruit. Acum puteţi continua.';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = 'Introduceţi aici %s.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = 'Şterge indexul de căutare';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = 'Goleşte the tabelele <em>tl_search</em> şi <em>tl_search_index</em>. Ulterior va trebui să reconstruiţi indexul de căutare (vezi mai sus).';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = 'Şterge tabela de anulare';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = 'Goleşte tabela <em>tl_undo</em> unde se stochează înregistrările şterse. Operaţia va şterge permanent aceste înregistrări.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'Şterge tabela de versiuni';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'Goleşte tabela <em>tl_version</em> care stochează versiunile precedente  ale unei înregistrări. Operaţia va şterge permanent aceste înregistrări.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = 'Şterge cache-ul de imagini';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = 'Şterge imaginile generate automat şi apoi elimină cache-ul de pagini, pentru a nu mai exista link-uri la resursele şterse.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'Şterge cache-ul de scripturi';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = 'Şterge fişierele .css şi .js generate automat, recreează fişierele de stil interne şi apoi elimină cache-ul de pagini.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'Şterge cache-ul de pagini';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'Şterge versiunile din cache ale paginilor de front-end.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = 'Şterge cache-ul intern';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'Şterge versiunile din cache ale DCA şi ale fişierelor de limbă. Puteţi dezactiva permanent cache-ul intern în setările de back-end.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = 'Şterge directorul temp';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = 'Şterge fişierele temporare';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'Recreează fişierele XML';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'Regenerează fişierele XML (sitemap şi feed) şi apoi elimină cache-ul  de pagini, astfel încât să nu existe legături la resurse inexistente.';
