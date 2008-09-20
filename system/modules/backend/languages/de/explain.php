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
 * Shortcuts
 */
$GLOBALS['TL_LANG']['XPL']['shortcuts'] = array
(
	array('colspan', 'Backend-Tastaturkürzel'),
	array('[ALT] + <strong>s</strong>', 'Speichern'),
	array('[ALT] + <strong>c</strong>', 'Speichern und Schließen'),
	array('[ALT] + <strong>e</strong>', 'Speichern und Bearbeiten'),
	array('[ALT] + <strong>n</strong>', 'Neues Element'),
	array('[ALT] + <strong>b</strong>', 'Zurück'),
	array('[ALT] + <strong>t</strong>', 'Nach oben'),
	array('[ALT] + <strong>f</strong>', 'Frontend Vorschau'),
	array('[ALT] + <strong>x</strong>', 'Abmelden')
);


/**
 * Insert tags
 */
$GLOBALS['TL_LANG']['XPL']['insertTags'] = array
(
	array('Rich Text Editor', 'weitere Informationen zu TinyMCE finden Sie unter <a href="http://tinymce.moxiecode.com" title="TinyMCE by moxiecode" onclick="window.open(this.href); return false;">http://tinymce.moxiecode.com</a>.'),
	array('Insert-Tags', 'weitere Informationen zu Insert-Tags finden Sie unter <a href="http://www.typolight.org/wiki/german:inserttags" title="TYPOlight Online-Dokumentation" onclick="window.open(this.href); return false;">http://www.typolight.org/wiki/german:inserttags</a>.')
	/*
	array('date', 'wird durch das aktuelle Datum gemäß dem globalen Datumsformat ersetzt.'),
	array('date::format', 'wird durch das aktuelle Datum gemäß dem Format <em>format</em> ersetzt (z.B. ergibt das Ersetzen von <em>format</em> durch <em>d.m.Y</em> das Datum <em>'.date('d.m.Y').'</em>). Weitere Informationen über Datumsformate finden Sie auf <a href="http://www.php.net/date" title="http://www.php.net in einem neuen Fenster öffnen" onclick="window.open(this.href); return false;">www.php.net/date</a>.'),
	array('user::property', 'wird durch die Eigenschaft <em>property</em> des angemeldeten Frontend-Benutzers ersetzt. Verfügbare Eigenschaften sind <em>firstname</em>, <em>lastname</em>, <em>company</em>, <em>email</em>, <em>street</em>, <em>postal</em>, <em>city</em>, <em>phone</em>, <em>mobile</em> und <em>fax</em>.'),
	array('link::page', 'wird durch einen Link auf eine interne Seite ersetzt (anstatt <em>page</em> ist die ID oder der Alias einer Seite anzugeben).'),
	array('link::back', 'wird durch einen Link auf die zuletzt besuchte Seite ersetzt (kann auch mit <em>link_open</em>, <em>link_url</em> und <em>link_title</em> verwendet werden).'),
	array('link::login', 'wird durch einen Link auf die Login Seite des aktuellen Benutzers ersetzt (kann auch mit <em>link_open</em>, <em>link_url</em> und <em>link_title</em> verwendet werden) und erscheint nur wenn ein Benutzer angemeldet ist.'),
	array('link_open::page', 'wird durch das öffnende Tag eines Links auf eine interne Seite ersetzt. Damit ist es möglich, einen beliebigen Text oder ein Bild als Link zu verwenden (z.B. <em>link_open::pagemein Text&lt;/a&gt;</em>).'),
	array('link_url::page', 'wird durch die URL einer internen Seite ersetzt. Damit ist es möglich, einen Link auf eine interne Seite zu setzen (z.B. <em>&lt;a href="link_url::page"&gt;mein Text&lt;/a&gt;</em>).'),
	array('link_title::page', 'wird durch den Titel einer internen Seite ersetzt und kann unter anderem in TITLE oder ALT Attributen verwendet werden (z.B. <em>&lt;a href="link_url::page" title="link_title::page"&gt;mein Text&lt;/a&gt;</em>).'),
	array('article::ID', 'wird durch einen Link auf einen Artikel ersetzt (anstatt <em>ID</em> ist die ID des Artikels anzugeben).'),
	array('env::page_title', 'wird durch den Titel der aktuellen Seite ersetzt (env = environment).'),
	array('env::page_alias', 'wird durch den Alias der aktuellen Seite ersetzt (env = environment).'),
	array('env::main_title', 'wird durch den Titel der übergeordneten Hauptmenü Seite ersetzt (env = environment).'),
	array('env::main_alias', 'wird durch den Alias der übergeordneten Hauptmenü Seite ersetzt (env = environment).'),
	array('env::website_title', 'wird durch den Titel der Webseite ersetzt (env = environment).'),
	array('env::url', 'wird durch die aktuelle URL ersetzt (z.B. http://www.Ihre-Seite.de).'),
	array('env::path', 'wird durch die aktuelle URL und den Pfad zum TYPOlight Verzeichnis ersetzt (z.B. http://www.Ihre-Seite.de/pfad).'),
	array('env::request', 'wird durch den aktuellen Request ersetzt (z.B. home.html).'),
	array('env::referer', 'wird durch die URL der zuletzt besuchten Seite ersetzt.'),
	array('file::file.php', 'wird durch die Ausgabe der Datei file.php ersetzt. Einzubindende PHP Dateien müssen sich im Ordner <em>templates</em> befinden. Es ist möglich Argumente zu übergeben (z.B. file.php?arg=val&amp;arg2=val2).')
	*/
);


/**
 * Date format
 */
$GLOBALS['TL_LANG']['XPL']['dateFormat'] = array
(
	array('colspan', 'TYPOlight unterstützt verschiedene Datums- und Zeitformate, die auf der PHP-Funktion date() aufbauen. Damit alle Benutzereingaben in einen UNIX Zeitstempel transformiert werden können, sind jedoch ausschließlich numerische Formate (j, d, m, n, y, Y, g, G, h, H, i, s) mit jeweils einem weiteren Zeichen zwischen den einzelnen Spezifikationen erlaubt.<br /><br /><em>Hier sind einige Beispiele gültiger Datums- und Zeitangaben</em>:'),
	array('Y-m-d', 'JJJJ-MM-TT, international ISO 8601 z.B. 2005-01-28'),
	array('m/d/Y', 'MM/TT/JJJJ, Englisches Format z.B. 01/28/2005'),
	array('d.m.Y', 'TT.MM.JJJJ, Deutsches Format z.B. 28.01.2005'),
	array('y-n-j', 'JJ-M-T, ohne führende Nullen z.B. 05-1-28'),
	array('Ymd', 'JJJJMMTT, Zeitstempel z.B. 20050128'),
	array('H:i:s', '24 Stunden, Minuten und Sekunden z.B. 20:36:59'),
	array('g:i', '12 Stunden ohne führende Nullen sowie Minuten z.B. 8:36')
);


/**
 * Field "jumpTo"
 */
$GLOBALS['TL_LANG']['XPL']['jumpTo'] = array
(
	array('colspan', 'Mit dieser Einstellung legen Sie fest, auf welche Seite ein Benutzer bei einer bestimmten Aktion weitergeleitet wird.'),
	array('Login Formular', 'nachdem sich ein Benutzer angemeldet hat, wird er auf diese Seite weitergeleitet. Auf diese Weise können Sie ihn z.B. auf die Startseite eines geschützten Bereiches weiterleiten.'),
	array('Automatischer Logout', 'nachdem sich ein Benutzer abgemeldet hat, wird er auf diese Seite weitergeleitet (z.B. eine Anmeldeseite oder eine "Auf Wiedersehen" Seite).'),
	array('Persönliche Daten', 'nachdem ein Benutzer seine persönlichen Daten erfolgreich aktualisiert hat, wird er auf diese Seite weitergeleitet.'),
	array('Website-News', 'wenn ein Benutzer auf einen "Weiterlesen …"-Link klickt, können Sie ihn auf eine Seite, die das Modul <em>Nachrichtenleser</em> enthält, weiterleiten.'),
	array('Nachrichtenarchiv Menü', 'wenn ein Benutzer ein Archiv durch anklicken auswählt, können Sie ihn auf eine Seite, die das Modul <em>Nachrichtenarchiv</em> enthält, weiterleiten.'),
	array('Formulargenerator', 'nachdem ein Benutzer ein Formular abgeschickt hat, können Sie ihn z.B. auf eine "Vielen Dank" Seite oder eine Bestätigungsseite weiterleiten.')
);

?>