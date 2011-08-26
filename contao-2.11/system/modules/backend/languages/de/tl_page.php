<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_page']['title']          = array('Seitenname', 'Bitte geben Sie den Namen der Seite ein.');
$GLOBALS['TL_LANG']['tl_page']['alias']          = array('Seitenalias', 'Der Seitenalias ist eine eindeutige Referenz, die anstelle der numerischen Seiten-ID aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_page']['type']           = array('Seitentyp', 'Bitte wählen Sie den Typ der Seite aus.');
$GLOBALS['TL_LANG']['tl_page']['pageTitle']      = array('Seitentitel', 'Bitte geben Sie den Titel der Seite ein.');
$GLOBALS['TL_LANG']['tl_page']['language']       = array('Sprache', 'Bitte geben Sie die Sprache der Seite gemäß des ISO-639-1 Standards ein (z.B. "de" für Deutsch).');
$GLOBALS['TL_LANG']['tl_page']['robots']         = array('Robots-Tag', 'Hier legen Sie fest, wie Suchmaschinen die Seite behandeln.');
$GLOBALS['TL_LANG']['tl_page']['description']    = array('Beschreibung der Seite', 'Hier können Sie eine kurze Beschreibung der Seite eingeben, die von Suchmaschinen wie Google oder Yahoo ausgewertet wird. Suchmaschinen indizieren normalerweise zwischen 150 und 300 Zeichen.');
$GLOBALS['TL_LANG']['tl_page']['redirect']       = array('Weiterleitungstyp', 'Bitte wählen Sie den Typ der Weiterleitung.');
$GLOBALS['TL_LANG']['tl_page']['jumpTo']         = array('Weiterleitungsseite', 'Bitte wählen Sie die Seite aus, zu der Besucher weitergeleitet werden. Wenn Sie keine Zielseite auswählen, wird automatisch zur ersten regulären Unterseite weitergeleitet.');
$GLOBALS['TL_LANG']['tl_page']['fallback']       = array('Sprachen-Fallback', 'Diese Seite anzeigen, wenn es keine in der Sprache des Besuchers gibt.');
$GLOBALS['TL_LANG']['tl_page']['dns']            = array('Domainname', 'Hier können Sie den Zugriff auf die Webseite auf einen bestimmten Domainnamen beschränken.');
$GLOBALS['TL_LANG']['tl_page']['adminEmail']     = array('E-Mail-Adresse des Webseiten-Administrators', 'Automatisch generierte Systemnachrichten wie z.B. Bestätigungsmails an diese Adresse versenden.');
$GLOBALS['TL_LANG']['tl_page']['dateFormat']     = array('Datumsformat', 'Der Datumsformat-String wird mit der PHP-Funktion date() geparst.');
$GLOBALS['TL_LANG']['tl_page']['timeFormat']     = array('Zeitformat', 'Der Zeitformat-String wird mit der PHP-Funktion date() geparst.');
$GLOBALS['TL_LANG']['tl_page']['datimFormat']    = array('Datums- und Zeitformat', 'Der Datums- und Zeitformat-String wird mit der PHP-Funktion date() geparst.');
$GLOBALS['TL_LANG']['tl_page']['createSitemap']  = array('Eine XML-Sitemap erstellen', 'Eine Google XML-Sitemap im Wurzelverzeichnis erstellen.');
$GLOBALS['TL_LANG']['tl_page']['sitemapName']    = array('Sitemap-Dateiname', 'Bitte geben Sie den Namen der Sitemap-Datei ohne Dateiendung ein.');
$GLOBALS['TL_LANG']['tl_page']['useSSL']         = array('HTTPS in Sitemaps', 'Die Sitemap-URLs dieser Webseite mit <em>https://</em> generieren.');
$GLOBALS['TL_LANG']['tl_page']['autoforward']    = array('Zu einer anderen Seite weiterleiten', 'Die Besucher zu einer anderen Seite (z.B. einer Login-Seite) weiterleiten.');
$GLOBALS['TL_LANG']['tl_page']['protected']      = array('Seite schützen', 'Den Seiten-Zugriff auf bestimmte Mitgliedergruppen beschränken.');
$GLOBALS['TL_LANG']['tl_page']['groups']         = array('Erlaubte Mitgliedergruppen', 'Diese Gruppen dürfen auf die Seite zugreifen.');
$GLOBALS['TL_LANG']['tl_page']['includeLayout']  = array('Ein Layout zuweisen', 'Der Seite und ihren Unterseiten ein Layout zuweisen.');
$GLOBALS['TL_LANG']['tl_page']['layout']         = array('Seitenlayout', 'Seitenlayouts können mit dem Modul "Themes" verwaltet werden.');
$GLOBALS['TL_LANG']['tl_page']['includeCache']   = array('Cachezeit festlegen', 'Eine Cachezeit für die Seite und ihre Unterseiten festlegen.');
$GLOBALS['TL_LANG']['tl_page']['cache']          = array('Cachezeit', 'Nach Ablauf dieser Zeitdauer verfällt die zwischengespeicherte Version der Seite.');
$GLOBALS['TL_LANG']['tl_page']['includeChmod']   = array('Zugriffsrechte zuweisen', 'Zugriffsrechte legen fest, was Backend-Benutzer mit der Seite tun dürfen.');
$GLOBALS['TL_LANG']['tl_page']['cuser']          = array('Besitzer', 'Bitte wählen Sie einen Benutzer als Besitzer der Seite aus.');
$GLOBALS['TL_LANG']['tl_page']['cgroup']         = array('Gruppe', 'Bitte wählen Sie eine Gruppe als Besitzer der Seite aus.');
$GLOBALS['TL_LANG']['tl_page']['chmod']          = array('Zugriffsrechte', 'Bitte legen Sie die Zugriffsrechte der Seite und ihrer Unterseiten fest.');
$GLOBALS['TL_LANG']['tl_page']['noSearch']       = array('Nicht durchsuchen', 'Diese Seite nicht in den Suchindex aufnehmen.');
$GLOBALS['TL_LANG']['tl_page']['cssClass']       = array('CSS-Klasse', 'Die Klasse wird sowohl in der Navigation als auch im Body-Tag verwendet.');
$GLOBALS['TL_LANG']['tl_page']['sitemap']        = array('In der Sitemap zeigen', 'Hier können Sie festlegen, ob die Seite in der Sitemap angezeigt wird.');
$GLOBALS['TL_LANG']['tl_page']['hide']           = array('Im Menü verstecken', 'Diese Seite in der Navigation nicht anzeigen.');
$GLOBALS['TL_LANG']['tl_page']['guests']         = array('Nur Gästen anzeigen', 'Diese Seite ausblenden, sobald ein Benutzer angemeldet ist.');
$GLOBALS['TL_LANG']['tl_page']['tabindex']       = array('Tab-Index', 'Die Position des Menüpunktes innerhalb der Tabulator-Reihenfolge.');
$GLOBALS['TL_LANG']['tl_page']['accesskey']      = array('Tastaturkürzel', 'Ein Menüpunkt kann direkt angewählt werden, indem man gleichzeitig die [ALT]- bzw. [STRG]-Taste und das Tastaturkürzel drückt.');
$GLOBALS['TL_LANG']['tl_page']['published']      = array('Seite veröffentlichen', 'Die Seite auf der Webseite anzeigen.');
$GLOBALS['TL_LANG']['tl_page']['start']          = array('Anzeigen ab', 'Die Seite erst ab diesem Tag auf der Webseite anzeigen.');
$GLOBALS['TL_LANG']['tl_page']['stop']           = array('Anzeigen bis', 'Die Seite nur bis zu diesem Tag auf der Webseite anzeigen.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_page']['title_legend']     = 'Name und Typ';
$GLOBALS['TL_LANG']['tl_page']['meta_legend']      = 'Meta-Informationen';
$GLOBALS['TL_LANG']['tl_page']['redirect_legend']  = 'Weiterleitung';
$GLOBALS['TL_LANG']['tl_page']['dns_legend']       = 'DNS-Einstellungen';
$GLOBALS['TL_LANG']['tl_page']['sitemap_legend']   = 'XML-Sitemap';
$GLOBALS['TL_LANG']['tl_page']['forward_legend']   = 'Auto-Weiterleitung';
$GLOBALS['TL_LANG']['tl_page']['protected_legend'] = 'Zugriffsschutz';
$GLOBALS['TL_LANG']['tl_page']['layout_legend']    = 'Layout-Einstellungen';
$GLOBALS['TL_LANG']['tl_page']['cache_legend']     = 'Cache-Einstellungen';
$GLOBALS['TL_LANG']['tl_page']['chmod_legend']     = 'Zugriffsrechte';
$GLOBALS['TL_LANG']['tl_page']['search_legend']    = 'Sucheinstellungen';
$GLOBALS['TL_LANG']['tl_page']['expert_legend']    = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_page']['tabnav_legend']    = 'Tastatur-Navigation';
$GLOBALS['TL_LANG']['tl_page']['publish_legend']   = 'Veröffentlichung';


/**
 * Cache timeout labels
 */
$GLOBALS['TL_LANG']['CACHE'][0]       = '0 (nicht cachen)';
$GLOBALS['TL_LANG']['CACHE'][5]       = '5 Sekunden';
$GLOBALS['TL_LANG']['CACHE'][15]      = '15 Sekunden';
$GLOBALS['TL_LANG']['CACHE'][30]      = '30 Sekunden';
$GLOBALS['TL_LANG']['CACHE'][60]      = '60 Sekunden';
$GLOBALS['TL_LANG']['CACHE'][300]     = '5 Minuten';
$GLOBALS['TL_LANG']['CACHE'][900]     = '15 Minuten';
$GLOBALS['TL_LANG']['CACHE'][1800]    = '30 Minuten';
$GLOBALS['TL_LANG']['CACHE'][3600]    = '60 Minuten';
$GLOBALS['TL_LANG']['CACHE'][10800]   = '3 Stunden';
$GLOBALS['TL_LANG']['CACHE'][21600]   = '6 Stunden';
$GLOBALS['TL_LANG']['CACHE'][43200]   = '12 Stunden';
$GLOBALS['TL_LANG']['CACHE'][86400]   = '24 Stunden';
$GLOBALS['TL_LANG']['CACHE'][259200]  = '3 Tage';
$GLOBALS['TL_LANG']['CACHE'][604800]  = '7 Tage';
$GLOBALS['TL_LANG']['CACHE'][2592000] = '30 Tage';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_page']['permanent']   = '301 Permanente Weiterleitung';
$GLOBALS['TL_LANG']['tl_page']['temporary']   = '302 Temporäre Weiterleitung';
$GLOBALS['TL_LANG']['tl_page']['map_default'] = 'Standard';
$GLOBALS['TL_LANG']['tl_page']['map_always']  = 'Immer anzeigen';
$GLOBALS['TL_LANG']['tl_page']['map_never']   = 'Nie anzeigen';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_page']['new']        = array('Neue Seite', 'Eine neue Seite anlegen');
$GLOBALS['TL_LANG']['tl_page']['show']       = array('Seitendetails', 'Details der Seite ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_page']['edit']       = array('Seite bearbeiten', 'Seite ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_page']['cut']        = array('Seite verschieben', 'Seite ID %s verschieben');
$GLOBALS['TL_LANG']['tl_page']['copy']       = array('Seite duplizieren', 'Seite ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_page']['copyChilds'] = array('Seite mit Unterseiten duplizieren', 'Seite ID %s inklusive Unterseiten duplizieren');
$GLOBALS['TL_LANG']['tl_page']['delete']     = array('Seite löschen', 'Seite ID %s löschen');
$GLOBALS['TL_LANG']['tl_page']['toggle']     = array('Seite veröffentlichen/unveröffentlichen', 'Seite ID %s veröffentlichen/unveröffentlichen');
$GLOBALS['TL_LANG']['tl_page']['pasteafter'] = array('Einfügen nach', 'Nach der Seite ID %s einfügen');
$GLOBALS['TL_LANG']['tl_page']['pasteinto']  = array('Einfügen in', 'In die Seite ID %s einfügen');
$GLOBALS['TL_LANG']['tl_page']['articles']   = array('Artikel bearbeiten', 'Die Artikel der Seite ID %s bearbeiten');

?>