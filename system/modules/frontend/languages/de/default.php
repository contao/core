<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Error messages
 */
$GLOBALS['TL_LANG']['ERR']['form']    = 'Das Formular konnte nicht gesendet werden';
$GLOBALS['TL_LANG']['ERR']['captcha'] = 'Bitte beantworten Sie die Sicherheitsfrage!';


/**
 * Security questions
 */
$GLOBALS['TL_LANG']['SEC']['question1'] = 'Bitte addieren Sie %d und %d.';
$GLOBALS['TL_LANG']['SEC']['question2'] = 'Was ist die Summe aus %d und %d?';
$GLOBALS['TL_LANG']['SEC']['question3'] = 'Bitte rechnen Sie %d plus %d.';


/**
 * Content elements
 */
$GLOBALS['TL_LANG']['CTE']['texts']     = 'Text-Elemente';
$GLOBALS['TL_LANG']['CTE']['headline']  = array('Überschrift', 'Erzeugt eine Überschrift (h1 - h6).');
$GLOBALS['TL_LANG']['CTE']['text']      = array('Text', 'Erzeugt ein Rich-Text-Element.');
$GLOBALS['TL_LANG']['CTE']['html']      = array('HTML', 'Erlaubt das Hinzufügen von eigenem HTML-Code.');
$GLOBALS['TL_LANG']['CTE']['list']      = array('Aufzählung', 'Erzeugt eine geordnete oder ungeordnete Liste.');
$GLOBALS['TL_LANG']['CTE']['table']     = array('Tabelle', 'Erzeugt eine optional sortierbare Tabelle.');
$GLOBALS['TL_LANG']['CTE']['accordion'] = array('Akkordeon', 'Erzeugt ein mootools Akkordeon-Element.');
$GLOBALS['TL_LANG']['CTE']['code']      = array('Code', 'Gibt formatierten Programmcode auf dem Bildschirm aus.');
$GLOBALS['TL_LANG']['CTE']['links']     = 'Link-Elemente';
$GLOBALS['TL_LANG']['CTE']['hyperlink'] = array('Hyperlink', 'Erzeugt einen Verweis auf eine andere Webseite.');
$GLOBALS['TL_LANG']['CTE']['toplink']   = array('Top-Link', 'Erzeugt einen Link zum Seitenanfang.');
$GLOBALS['TL_LANG']['CTE']['images']    = 'Bild-Elemente';
$GLOBALS['TL_LANG']['CTE']['image']     = array('Bild', 'Erzeugt ein einzelnes Bild.');
$GLOBALS['TL_LANG']['CTE']['gallery']   = array('Galerie', 'Erzeugt eine lightbox Bildergalerie.');
$GLOBALS['TL_LANG']['CTE']['files']     = 'Datei Elemente';
$GLOBALS['TL_LANG']['CTE']['download']  = array('Download', 'Erzeugt einen Link zum Download einer Datei.');
$GLOBALS['TL_LANG']['CTE']['downloads'] = array('Downloads', 'Erzeugt mehrere Links zum Download von Dateien.');
$GLOBALS['TL_LANG']['CTE']['includes']  = 'Include-Elemente';
$GLOBALS['TL_LANG']['CTE']['article']   = array('Artikel', 'Fügt einen anderen Artikel ein.');
$GLOBALS['TL_LANG']['CTE']['alias']     = array('Inhaltselement', 'Fügt ein anderes Inhaltselement ein.');
$GLOBALS['TL_LANG']['CTE']['form']      = array('Formular', 'Fügt ein Formular ein.');
$GLOBALS['TL_LANG']['CTE']['module']    = array('Modul', 'Fügt ein Frontend-Modul ein.');
$GLOBALS['TL_LANG']['CTE']['teaser']    = array('Artikelteaser', 'Zeigt den Teasertext eines Artikels an.');


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['go']            = 'Los';
$GLOBALS['TL_LANG']['MSC']['quicknav']      = 'Quicknavigation';
$GLOBALS['TL_LANG']['MSC']['quicklink']     = 'Quicklink';
$GLOBALS['TL_LANG']['MSC']['username']      = 'Benutzername';
$GLOBALS['TL_LANG']['MSC']['login']         = 'Anmelden';
$GLOBALS['TL_LANG']['MSC']['logout']        = 'Abmelden';
$GLOBALS['TL_LANG']['MSC']['loggedInAs']    = 'Sie sind angemeldet als %s.';
$GLOBALS['TL_LANG']['MSC']['emptyField']    = 'Bitte geben Sie Benutzername und Passwort ein!';
$GLOBALS['TL_LANG']['MSC']['confirmation']  = 'Bestätigung';
$GLOBALS['TL_LANG']['MSC']['sMatches']      = '%s Vorkommen für %s';
$GLOBALS['TL_LANG']['MSC']['sEmpty']        = 'Keine Ergebnisse für <strong>%s</strong>';
$GLOBALS['TL_LANG']['MSC']['sResults']      = 'Ergebnisse %s - %s von %s für <strong>%s</strong>';
$GLOBALS['TL_LANG']['MSC']['sNoResult']     = 'Ihre Suche nach <strong>%s</strong> ergab keine Treffer.';
$GLOBALS['TL_LANG']['MSC']['seconds']       = 'Sekunden';
$GLOBALS['TL_LANG']['MSC']['up']            = 'Nach oben';
$GLOBALS['TL_LANG']['MSC']['first']         = '&#171; Anfang';
$GLOBALS['TL_LANG']['MSC']['previous']      = 'Zurück';
$GLOBALS['TL_LANG']['MSC']['next']          = 'Vorwärts';
$GLOBALS['TL_LANG']['MSC']['last']          = 'Ende &#187;';
$GLOBALS['TL_LANG']['MSC']['goToPage']      = 'Gehe zu Seite %s';
$GLOBALS['TL_LANG']['MSC']['totalPages']    = 'Seite %s von %s';
$GLOBALS['TL_LANG']['MSC']['fileUploaded']  = 'Die Datei %s wurde erfolgreich hochgeladen.';
$GLOBALS['TL_LANG']['MSC']['fileExceeds']   = 'Das Bild %s wurde erfolgreich hochgeladen, ist jedoch zu groß für die automatische Bildbearbeitung.';
$GLOBALS['TL_LANG']['MSC']['fileResized']   = 'Das Bild %s wurde erfolgreich hochgeladen und auf die maximalen Abmessungen verkleinert.';
$GLOBALS['TL_LANG']['MSC']['searchLabel']   = 'Suchen';
$GLOBALS['TL_LANG']['MSC']['keywords']      = 'Suchbegriffe';
$GLOBALS['TL_LANG']['MSC']['options']       = 'Optionen';
$GLOBALS['TL_LANG']['MSC']['matchAll']      = 'finde alle Wörter';
$GLOBALS['TL_LANG']['MSC']['matchAny']      = 'finde irgendein Wort';
$GLOBALS['TL_LANG']['MSC']['saveData']      = 'Daten speichern';
$GLOBALS['TL_LANG']['MSC']['printPage']     = 'Diese Seite drucken';
$GLOBALS['TL_LANG']['MSC']['printAsPdf']    = 'Artikel als PDF drucken';
$GLOBALS['TL_LANG']['MSC']['facebookShare'] = 'Auf Facebook teilen';
$GLOBALS['TL_LANG']['MSC']['twitterShare']  = 'Auf Twitter teilen';
$GLOBALS['TL_LANG']['MSC']['pleaseWait']    = 'Bitte warten Sie';
$GLOBALS['TL_LANG']['MSC']['loading']       = 'Laden …';
$GLOBALS['TL_LANG']['MSC']['more']          = 'Weiterlesen …';
$GLOBALS['TL_LANG']['MSC']['readMore']      = 'Den Artikel lesen: %s';
$GLOBALS['TL_LANG']['MSC']['targetPage']    = 'Zielseite';
$GLOBALS['TL_LANG']['MSC']['com_name']      = 'Name';
$GLOBALS['TL_LANG']['MSC']['com_email']     = 'E-Mail (wird nicht veröffentlicht)';
$GLOBALS['TL_LANG']['MSC']['com_website']   = 'Webseite';
$GLOBALS['TL_LANG']['MSC']['com_comment']   = 'Kommentar';
$GLOBALS['TL_LANG']['MSC']['com_submit']    = 'Kommentar absenden';
$GLOBALS['TL_LANG']['MSC']['comment_by']    = 'Kommentar von';
$GLOBALS['TL_LANG']['MSC']['reply_by']      = 'Antwort von';
$GLOBALS['TL_LANG']['MSC']['com_quote']     = '%s schrieb:';
$GLOBALS['TL_LANG']['MSC']['com_code']      = 'Code:';
$GLOBALS['TL_LANG']['MSC']['com_subject']   = 'Contao :: Neuer Kommentar auf %s';
$GLOBALS['TL_LANG']['MSC']['com_message']   = "%s hat einen neuen Kommentar auf Ihrer Webseite erstellt.\n\n---\n\n%s\n\n---\n\nAnsehen: %s\nBearbeiten: %s\n\nWenn Sie Kommentare moderieren, müssen Sie sich im Backend anmelden und den Kommentar veröffentlichen.";
$GLOBALS['TL_LANG']['MSC']['com_confirm']   = 'Ihr Kommentar wurde hinzugefügt und wird nach redaktioneller Prüfung veröffentlicht.';
$GLOBALS['TL_LANG']['MSC']['invalidPage']   = 'Der Eintrag "%s" existiert leider nicht.';
$GLOBALS['TL_LANG']['MSC']['list_orderBy']  = 'Sortiere nach %s';
$GLOBALS['TL_LANG']['MSC']['list_perPage']  = 'Ergebnisse pro Seite';
$GLOBALS['TL_LANG']['MSC']['published']     = 'Veröffentlicht';
$GLOBALS['TL_LANG']['MSC']['unpublished']   = 'Nicht veröffentlicht';
$GLOBALS['TL_LANG']['MSC']['addComment']    = 'Einen Kommentar schreiben';
$GLOBALS['TL_LANG']['MSC']['autologin']     = 'Angemeldet bleiben';
$GLOBALS['TL_LANG']['MSC']['relevance']     = '%s Relevanz';

?>