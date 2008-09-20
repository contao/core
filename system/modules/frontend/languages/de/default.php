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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Error messages
 */
$GLOBALS['TL_LANG']['ERR']['captcha'] = 'Bitte beantworten Sie die Sicherheitsfrage!';


/**
 * Security questions
 */
$GLOBALS['TL_LANG']['SEC']['question1'] = 'Bitte addieren Sie %d und %d.';
$GLOBALS['TL_LANG']['SEC']['question2'] = 'Was ist die Summe aus %d und %d?';
$GLOBALS['TL_LANG']['SEC']['question3'] = 'Bitte rechnen Sie %d plus %s.';


/**
 * Content elements
 */
$GLOBALS['TL_LANG']['CTE']['texts']     = 'Text Elemente';
$GLOBALS['TL_LANG']['CTE']['headline']  = array('Überschrift', 'Dieses Element enthält eine allein stehende Überschrift, die von &#60;H1&#62; Tags umschlossen wird.');
$GLOBALS['TL_LANG']['CTE']['text']      = array('Text', 'Dieses Element enthält formatierten Text und Hyperlinks. Außerdem kann dem Element ein Bild hinzugefügt werden.');
$GLOBALS['TL_LANG']['CTE']['html']      = array('HTML', 'Dieses Element enthält HTML-Code. Beachten Sie, dass bestimmte HTML-Tags aus Sicherheitsgründen nicht erlaubt sind.');
$GLOBALS['TL_LANG']['CTE']['list']      = array('Aufzählung', 'Dieses Element enthält eine geordnete oder ungeordnete Liste.');
$GLOBALS['TL_LANG']['CTE']['table']     = array('Tabelle', 'Dieses Element enthält eine Tabelle.');
$GLOBALS['TL_LANG']['CTE']['accordion'] = array('Akkordeon', 'Dieses Element erzeugt ein Inhaltsfeld eines mootools Akkordeons. Beachten Sie, dass dieser Effekt eine mootols JavaScript-Vorlage benötigt, die Sie im Modul Seitenlayout auswählen können.');
$GLOBALS['TL_LANG']['CTE']['code']      = array('Code', 'Dieses Element enthält Teile eines Programmcodes, der auf eine bestimmte Weise formatiert wird. Der Code wird nur auf dem Bildschirm ausgegeben und nicht ausgeführt.');
$GLOBALS['TL_LANG']['CTE']['links']     = 'Link Elemente';
$GLOBALS['TL_LANG']['CTE']['hyperlink'] = array('Hyperlink', 'Dieses Element enthält einen Verweis auf eine andere Webseite.');
$GLOBALS['TL_LANG']['CTE']['toplink']   = array('Top-Link', 'Dieses Element erzeugt einen Link mit dem man an den Seitenanfang springen kann.');
$GLOBALS['TL_LANG']['CTE']['images']    = 'Bild Elemente';
$GLOBALS['TL_LANG']['CTE']['image']     = array('Bild', 'Dieses Element enthält ein einzelnes Bild.');
$GLOBALS['TL_LANG']['CTE']['gallery']   = array('Bildergalerie', 'Dieses Element enthält mehrere kleine Vorschaubilder, die bei Anklicken in der Großansicht erscheinen.');
$GLOBALS['TL_LANG']['CTE']['files']     = 'Datei Elemente';
$GLOBALS['TL_LANG']['CTE']['download']  = array('Download', 'Dieses Element enthält einen Verweis auf eine Datei, die von Besuchern der Webseite heruntergeladen werden kann.');
$GLOBALS['TL_LANG']['CTE']['downloads'] = array('Downloads', 'Diese Element enthält Verweise auf mehrere Dateien, die von Besuchern der Webseite heruntergeladen werden können.');
$GLOBALS['TL_LANG']['CTE']['includes']  = 'Include-Elemente';
$GLOBALS['TL_LANG']['CTE']['alias']     = array('Alias', 'Dieses Element ermöglicht es, ein Inhaltselement aus einem anderen Artikel einzufügen.');
$GLOBALS['TL_LANG']['CTE']['article']   = array('Artikel-Alias', 'Dieses Element ermöglicht es, einen bestimmten Artikel in einem anderen Artikel anzuzeigen.');
$GLOBALS['TL_LANG']['CTE']['teaser']    = array('Artikelteaser', 'Dieses Element ermöglicht es, den Teasertext eines bestimmten Artikels anzuzeigen.');
$GLOBALS['TL_LANG']['CTE']['form']      = array('Formular', 'Verwenden Sie diese Option, um ein Formular in den Artikel einzubinden.');
$GLOBALS['TL_LANG']['CTE']['module']    = array('Modul', 'Verwenden Sie diese Option, um ein Modul (z.B. ein Navigationsmenü oder einen Flash Film) in einen Artikel einzubinden.');


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['go']           = 'Los';
$GLOBALS['TL_LANG']['MSC']['quicknav']     = 'Quicknavigation';
$GLOBALS['TL_LANG']['MSC']['quicklink']    = 'Quicklink';
$GLOBALS['TL_LANG']['MSC']['username']     = 'Benutzername';
$GLOBALS['TL_LANG']['MSC']['login']        = 'Anmelden';
$GLOBALS['TL_LANG']['MSC']['logout']       = 'Abmelden';
$GLOBALS['TL_LANG']['MSC']['loggedInAs']   = 'Sie sind angemeldet als %s.';
$GLOBALS['TL_LANG']['MSC']['emptyField']   = 'Bitte geben Sie Benutzernamen und Passwort ein!';
$GLOBALS['TL_LANG']['MSC']['confirmation'] = 'Bestätigung';
$GLOBALS['TL_LANG']['MSC']['sMatches']     = '%s Vorkommen für %s';
$GLOBALS['TL_LANG']['MSC']['sEmpty']       = 'Keine Ergebnisse für <strong>%s</strong>';
$GLOBALS['TL_LANG']['MSC']['sResults']     = 'Ergebnisse %s - %s von %s für <strong>%s</strong>';
$GLOBALS['TL_LANG']['MSC']['sNoResult']    = 'Ihre Suche nach <strong>%s</strong> ergab keine Treffer.';
$GLOBALS['TL_LANG']['MSC']['seconds']      = 'Sekunden';
$GLOBALS['TL_LANG']['MSC']['first']        = '&#171; Anfang';
$GLOBALS['TL_LANG']['MSC']['previous']     = 'Zurück';
$GLOBALS['TL_LANG']['MSC']['next']         = 'Vorwärts';
$GLOBALS['TL_LANG']['MSC']['last']         = 'Ende &#187;';
$GLOBALS['TL_LANG']['MSC']['goToPage']     = 'Gehe zu Seite %s';
$GLOBALS['TL_LANG']['MSC']['totalPages']   = 'Seite %s von %s';
$GLOBALS['TL_LANG']['MSC']['fileUploaded'] = 'Die Datei %s wurde erfolgreich hochgeladen';
$GLOBALS['TL_LANG']['MSC']['fileResized']  = 'Die Datei %s wurde hochgeladen und auf die maximalen Abmessungen verkleinert';
$GLOBALS['TL_LANG']['MSC']['searchLabel']  = 'Suchen';
$GLOBALS['TL_LANG']['MSC']['matchAll']     = 'finde alle Wörter';
$GLOBALS['TL_LANG']['MSC']['matchAny']     = 'finde irgendein Wort';
$GLOBALS['TL_LANG']['MSC']['saveData']     = 'Daten speichern';
$GLOBALS['TL_LANG']['MSC']['printAsPdf']   = 'Artikel als PDF drucken';
$GLOBALS['TL_LANG']['MSC']['pleaseWait']   = 'Bitte warten Sie';
$GLOBALS['TL_LANG']['MSC']['loading']      = 'Laden …';
$GLOBALS['TL_LANG']['MSC']['more']         = 'Weiterlesen …';
$GLOBALS['TL_LANG']['MSC']['com_name']     = 'Name';
$GLOBALS['TL_LANG']['MSC']['com_email']    = 'E-Mail (wird nicht veröffentlicht)';
$GLOBALS['TL_LANG']['MSC']['com_website']  = 'Webseite';
$GLOBALS['TL_LANG']['MSC']['com_submit']   = 'Kommentar absenden';
$GLOBALS['TL_LANG']['MSC']['comment_by']   = 'Kommentar von';
$GLOBALS['TL_LANG']['MSC']['com_quote']    = '%s schrieb:';
$GLOBALS['TL_LANG']['MSC']['com_code']     = 'Code:';
$GLOBALS['TL_LANG']['MSC']['com_subject']  = 'TYPOlight :: Neuer Kommentar auf %s';
$GLOBALS['TL_LANG']['MSC']['com_message']  = 'Ein neuer Kommentar wurde auf Ihrer Webseite erstellt.%sWenn Sie Ihre Kommentare moderieren, müssen Sie sich im Backend anmelden und den Kommentar freischalten.';

?>