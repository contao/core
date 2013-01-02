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
 * @link https://www.transifex.com/projects/p/contao/language/pl/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'Tabele pamięci podręcznej';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = 'Zaznacz te tabele, które chcesz wyczyścić.';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'Użytkownik';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = 'Automatycznie zaloguj użytkownika by zaindeksować strony chronione.';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = 'Zadanie';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = 'Opis';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Wykonaj';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'Pamięć podręczna została wyczyszczona';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'Live Update';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'Live Update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'Idź do Live Update';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'Twoja wersja Contao %s jest aktualna';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'Nowa wersja Contao %s jest dostępna';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Wprowadź swoje Live Update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'Katalog plików tymczasowych (system/tmp) nie ma praw do zapisu';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'Zobacz zmiany';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Uruchom aktualizację';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Zawartość aktualizacji';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'Kopie zapasowe plików';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'Uaktualnione pliki';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Odbuduj indeks wyszukiwarki';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Odbuduj indeks';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'Nie znaleziono stron do przeszukania.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = 'Proszę poczekać na całkowite załadowanie się strony przed kontynuacją!';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Poczekaj chwilę, rozpoczęto odbudowywanie indeksu wyszukiwarki.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'Indeks wyszukiwarki został odbudowany. Możesz kontynuować.';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = 'Wprowadź swój %s.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = 'Wyczyść indeks wyszukiwarki';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = 'Czyści tabele <em>tl_search</em> i <em>tl_search_index</em>. Po wszystkim musisz odbudować indeks wyszukiwarki (patrz wyżej).';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = 'Wyczyść tabelę "cofnij"';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = 'Czyści tabelę <em>tl_undo</em>, która zawiera usunięte rekordy. To zadanie permanentnie usunie te rekordy.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'Wyczyść tabelę wersji';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'Czyści tabelę <em>tl_version</em>, która zawiera poprzednie wersje danego rekordu. To zadanie permanentnie usunie te wersje.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = 'Wyczyść cache obrazków';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = 'Usuwa automatycznie wygenerowane obrazki i czyści cache stron, więc nie pojawią się odwołania do usuniętych plików.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'Wyczyść cache skryptów';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = 'Usuwa automatycznie wygenerowane pliki .css i .js, odtwarza wewnętrzne style i wtedy czyści cache stron.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'Wyczyść cache stron';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'Usuwa cacheowane wersje stron front end.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = 'Wyczyść wewnętrzny cache';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'Usuwa cacheowane wersje DCA i plików językowych. Możesz permanentnie wyłączyć wewnętrzny cache w ustawieniach systemu.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = 'Wyczyść folder tymczasowy';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = 'Usuwa pliki tymczasowe.';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'Odtwórz pliki XML';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'Odtwarza pliki XML (mapy stron i kanały) i czyści cache stron, więc nie pojawią się odwołania do usuniętych źródeł.';
