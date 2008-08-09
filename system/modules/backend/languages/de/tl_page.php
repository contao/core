<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_page']['title']          = array('Seitenname', 'Der Name einer Seite wird im Menü der Webseite angezeigt.');
$GLOBALS['TL_LANG']['tl_page']['alias']          = array('Seitenalias', 'Der Alias einer Seite ist eine eindeutige Referenz, die anstelle der Seiten-ID aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_page']['adminEmail']     = array('E-Mail-Adresse des Webseiten-Administrators', 'Die E-Mail-Adresse des Webseiten-Administrators wird als Absenderadresse für automatisch generierte Systemnachrichten wie z.B. Aktivierungsmails oder Bestätigungsmails verwendet.');
$GLOBALS['TL_LANG']['tl_page']['type']           = array('Seitentyp', 'Bitte wählen Sie den Seitentyp je nach Funktion der Seite.');
$GLOBALS['TL_LANG']['tl_page']['language']       = array('Sprache', 'Bitte geben Sie die Sprache ein, die Sie auf der aktuellen Webseite verwenden. Erfassen Sie eine Sprache über ihr primäres Subtag (ISO-639 / RFC3066), also z.B. "de" für Deutsch.');
$GLOBALS['TL_LANG']['tl_page']['pageTitle']      = array('Seitentitel', 'Der Seitentitle wird unter anderem im TITLE Tag der Seite und in den Suchergebnissen verwendet. Er sollte nicht mehr als 65 Zeichen enthalten. Ist kein Seitentitel vorhanden, wird stattdessen der Seitenname verwendet.');
$GLOBALS['TL_LANG']['tl_page']['description']    = array('Beschreibung der Seite', 'Sie können eine kurze Beschreibung der Seite eingeben, die dann von Suchmaschinen angezeigt wird. Eine Suchmaschine indiziert normalerweise zwischen 150 und 300 Zeichen.');
$GLOBALS['TL_LANG']['tl_page']['cssClass']       = array('CSS-Klasse', 'Wenn Sie hier einen Klassennamen eingeben, wird dieser als Class-Attribut im Navigationsmenü verwendet. Auf diese Weise können Sie einen Menüpunkt individuell formatieren.');
$GLOBALS['TL_LANG']['tl_page']['protected']      = array('Seite schützen', 'Wenn Sie diese Option wählen, können Sie den Zugriff auf die Seite auf bestimmte Mitgliedergruppen beschränken. Ein Besucher Ihrer Webseite kann die Seite und ihre Unterseiten dann nicht mehr aufrufen, wenn er nicht eingeloggt ist.');
$GLOBALS['TL_LANG']['tl_page']['groups']         = array('Erlaubte Mitgliedergruppen', 'Bitte wählen Sie eine oder mehrere Mitgliedergruppen, die Zugang zu der geschützen Seite haben sollen. Wenn Sie keine Auswahl vornehmen, haben alle angemeldeten Benutzer Zugang zu der Seite.');
$GLOBALS['TL_LANG']['tl_page']['includeLayout']  = array('Ein Layout zuweisen', 'Standardmäßig verwendet eine Seite dasselbe Layout wie ihre übergeordnete Seite. Wenn Sie diese Option wählen, können Sie der Seite und ihren Unterseiten ein neues Layout zuweisen.');
$GLOBALS['TL_LANG']['tl_page']['layout']         = array('Seitenlayout', 'Bitte wählen Sie ein Layout für die Seite. Sie können Layouts mit Hilfe des Moduls "Seitenlayout" erstellen oder bearbeiten.');
$GLOBALS['TL_LANG']['tl_page']['includeCache']   = array('Cache-Verfallszeit zuweisen', 'Standardmäßig verwendet eine Seite dieselbe Cache-Verfallszeit wie ihre übergeordnete Seite. Wenn Sie diese Option wählen, können Sie der Seite und ihren Unterseiten eine neue Cache-Verfallszeit zuweisen.');
$GLOBALS['TL_LANG']['tl_page']['cache']          = array('Cache-Speicherzeit', 'Für die Dauer der Cache-Speicherzeit wird die Seite aus dem Zwischenspeicher und nicht vom Server geladen. Auf diese Weise wird weniger Traffic verursacht und die Seite kann schneller aufgerufen werden.');
$GLOBALS['TL_LANG']['tl_page']['includeChmod']   = array('Zugriffsrechte zuweisen', 'Durch Zugriffsrechte können Sie festlegen, inwiefern ein Backend-Benutzer eine Seite und ihre Artikel verändern kann. Wenn Sie diese Option nicht wählen, verwendet die Seite dieselbe Zugriffsrechte wie ihre übergeordnete Seite.');
$GLOBALS['TL_LANG']['tl_page']['chmod']          = array('Zugriffsrechte', 'Jede Seite hat drei Zugriffsebenen: eine für den Benutzer, eine für die Gruppe und eine für alle anderen. Sie können jeder Ebene verschiedene Zugriffsrechte zuweisen.');
$GLOBALS['TL_LANG']['tl_page']['cuser']          = array('Besitzer', 'Bitte wählen Sie einen Benutzer als Besitzer der Seite aus.');
$GLOBALS['TL_LANG']['tl_page']['cgroup']         = array('Gruppe', 'Bitte wählen Sie eine Gruppe als Besitzer der Seite aus.');
$GLOBALS['TL_LANG']['tl_page']['createSitemap']  = array('Eine XML-Sitemap erstellen', 'Eine XML-Sitemap für Google im Root-Verzeichnis anlegen.');
$GLOBALS['TL_LANG']['tl_page']['sitemapName']    = array('Dateiname', 'Bitte geben Sie einen Namen für die XML-Datei ohne Dateiendung ein.');
$GLOBALS['TL_LANG']['tl_page']['hide']           = array('Seite im Menü verstecken', 'Diese Seite nicht im Menü angezeigen.');
$GLOBALS['TL_LANG']['tl_page']['guests']         = array('Nur Gästen anzeigen', 'Diese Seite nicht im Menü angezeigen wenn ein Benutzer angemeldet ist.');
$GLOBALS['TL_LANG']['tl_page']['noSearch']       = array('Seite nicht durchsuchen', 'Diese Seite nicht indizieren.');
$GLOBALS['TL_LANG']['tl_page']['accesskey']      = array('Tastaturkürzel', 'Ein Tastaturkürzel ist ein einzelnes Zeichen, das mit einem Menüpunkt verknüpft werden kann. Drückt ein Besucher gleichzeitig die [ALT] Taste und das Tastaturkürzel, wird der Menüpunkt aktiviert.');
$GLOBALS['TL_LANG']['tl_page']['tabindex']       = array('Tabulator-Reihenfolge', 'Diese Zahl bestimmt die Position des aktuellen Menüpunktes innerhalb der Reihenfolge der Tabulatoren. Sie können eine Zahl zwischen 1 und 32767 eingeben.');
$GLOBALS['TL_LANG']['tl_page']['autoforward']    = array('Zu einer anderen Seite weiterleiten', 'Wenn Sie diese Option wählen, werden Besucher zu einer anderen Seite (z.B. einer Login-Seite oder einer Willkommen-Seite) weitergeleitet.');
$GLOBALS['TL_LANG']['tl_page']['redirect']       = array('Weiterleitungstyp', 'Temporäre Weiterleitungen erfolgen mit dem HTTP Header 302, permanente mit dem HTTP Header 301.');
$GLOBALS['TL_LANG']['tl_page']['jumpTo']         = array('Weiterleitung zu', 'Bitte wählen Sie die Zielseite aus, die anstelle der aktuellen Seite angezeigt werden soll.');
$GLOBALS['TL_LANG']['tl_page']['dns']            = array('Domain Name', 'Wenn Sie einem Startpunkt einer neuen Webseite einen Domainnamen zuweisen, werden Ihre Besucher automatisch zu dieser Webseite umgeleitet, wenn sie den entsprechenden Domainnamen eingeben (z.B. <em>Ihre-Seite.de</em>).');
$GLOBALS['TL_LANG']['tl_page']['fallback']       = array('Sprachen-Fallback', 'TYPOlight leitet einen Benutzer automatisch zu einer Startseite in seiner Sprache oder zu der Sprachen-Fallback Seite um. Wenn keine Sprachen-Fallback Seite existiert, wird die Fehlermeldung <em>No pages found</em> angezeigt.');
$GLOBALS['TL_LANG']['tl_page']['published']      = array('Veröffentlicht', 'Solange Sie diese Option nicht wählen, ist die Seite für die Besucher Ihrer Webseite nicht sichtbar.');
$GLOBALS['TL_LANG']['tl_page']['start']          = array('Anzeigen ab', 'Wenn Sie hier ein Datum erfassen, wird die Seite erst ab diesem Tag angezeigt.');
$GLOBALS['TL_LANG']['tl_page']['stop']           = array('Anzeigen bis', 'Wenn Sie hier ein Datum erfassen, wird die Seite nur bis zu diesem Tag angezeigt.');


/**
 * Cache timeout labels
 */
$GLOBALS['TL_LANG']['CACHE'][0]      = 'Kein Caching';
$GLOBALS['TL_LANG']['CACHE'][15]     = '15 Sekunden';
$GLOBALS['TL_LANG']['CACHE'][30]     = '30 Sekunden';
$GLOBALS['TL_LANG']['CACHE'][60]     = '1 Minute';
$GLOBALS['TL_LANG']['CACHE'][300]    = '5 Minuten';
$GLOBALS['TL_LANG']['CACHE'][900]    = '15 Minuten';
$GLOBALS['TL_LANG']['CACHE'][1800]   = '30 Minuten'; 
$GLOBALS['TL_LANG']['CACHE'][3600]   = '1 Stunde';
$GLOBALS['TL_LANG']['CACHE'][21600]  = '6 Stunden';
$GLOBALS['TL_LANG']['CACHE'][43200]  = '12 Stunde';
$GLOBALS['TL_LANG']['CACHE'][86400]  = '1 Tag';
$GLOBALS['TL_LANG']['CACHE'][259200] = '3 Tage';
$GLOBALS['TL_LANG']['CACHE'][604800] = '7 Tage';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_page']['temporary'] = 'temporär';
$GLOBALS['TL_LANG']['tl_page']['permanent'] = 'permanent';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_page']['new']        = array('Neue Seite', 'Eine neue Seite anlegen');
$GLOBALS['TL_LANG']['tl_page']['show']       = array('Seitendetails', 'Details der Seite ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_page']['cut']        = array('Seite verschieben', 'Seite ID %s verschieben');
$GLOBALS['TL_LANG']['tl_page']['copy']       = array('Seite duplizieren', 'Seite ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_page']['copyChilds'] = array('Seite inklusive Unterseiten duplizieren', 'Seite ID %s inklusive Unterseiten duplizieren');
$GLOBALS['TL_LANG']['tl_page']['delete']     = array('Seite löschen', 'Seite ID %s löschen');
$GLOBALS['TL_LANG']['tl_page']['edit']       = array('Seite bearbeiten', 'Seite ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_page']['all']        = array('Alle bearbeiten', 'Alle angezeigten Seiten bearbeiten');
$GLOBALS['TL_LANG']['tl_page']['pasteafter'] = array('Einfügen nach', 'Nach der Seite ID %s einfügen');
$GLOBALS['TL_LANG']['tl_page']['pasteinto']  = array('Einfügen in', 'In die Seite ID %s einfügen');

?>