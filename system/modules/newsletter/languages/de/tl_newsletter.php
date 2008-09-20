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
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_newsletter']['subject']       = array('Betreff', 'Bitte geben Sie den Betreff des Newsletters ein.');
$GLOBALS['TL_LANG']['tl_newsletter']['alias']         = array('Newsletteralias', 'Der Alias eines Newsletters ist eine eindeutige Referenz, die anstelle der ID aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_newsletter']['sender']        = array('Absenderadresse', 'Wenn Sie keine Absenderadresse eingeben, wird die E-Mail-Adresse des Administrators verwendet.');
$GLOBALS['TL_LANG']['tl_newsletter']['senderName']    = array('Absendername', 'Hier können Sie den Namen des Absenders eingeben.');
$GLOBALS['TL_LANG']['tl_newsletter']['content']       = array('HTML-Inhalt', 'Geben Sie den HTML-Inhalt des Newsletters ein. Benutzen Sie den Platzhalter <em>##email##</em> um die Empfängeradresse einzufügen. Erstellen Sie Links zum Abbestellen des Newsletters im der Form <em>http://www.domain.de/abbestellen.html?email=##email##</em>.');
$GLOBALS['TL_LANG']['tl_newsletter']['text']          = array('Text-Inhalt', 'Geben Sie den Text-Inhalt des Newsletters ein. Benutzen Sie den Platzhalter <em>##email##</em> um die Empfängeradresse einzufügen. Erstellen Sie Links zum Abbestellen des Newsletters im der Form <em>http://www.domain.de/abbestellen.html?email=##email##</em>.');
$GLOBALS['TL_LANG']['tl_newsletter']['template']      = array('E-Mail-Template', 'Bitte wählen Sie ein E-Mail-Template (Templategruppe <em>mail_</em>).');
$GLOBALS['TL_LANG']['tl_newsletter']['sendText']      = array('Als Text senden', 'Wenn Sie diese Option wählen, wird der Newsletter als reiner Text ohne den HTML-Inhalt versendet.');
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'] = array('Mails pro Zyklus', 'Um zu verhindern dass das Skript vorzeitig abbricht, wird der Prozess in mehreren Teilschritten ausgeführt. Hier können Sie die Anzahl der Mails pro Teilschritt im Verhältnis zur maximalen Skriptlaufzeit in Ihrer php.ini festlegen.');
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'] = array('Testsendung an', 'Eine Testsendung des Newsletters an diese E-Mail-Adresse verschicken.');
$GLOBALS['TL_LANG']['tl_newsletter']['timeout']       = array('Wartezeit in Sekunden', 'Einige Mailserver beschränken die Anzahl der E-Mails, die pro Minute versendet werden können. Durch eine Anpassung der Wartezeit zwischen den einzelnen Versandzyklen in Sekunden, lässt sich der zeitlichen Aspekt des Versands beeinflussen.');
$GLOBALS['TL_LANG']['tl_newsletter']['addFile']       = array('Attachment hinzufügen', 'Dem Newsletter eine oder mehrere Dateien hinzufügen.');
$GLOBALS['TL_LANG']['tl_newsletter']['files']         = array('Attachments', 'Bitte wählen Sie die Dateien, die Sie dem Newsletter anhängen möchten.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_newsletter']['sent']        = 'Gesendet';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn']      = 'Gesendet am %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent']     = 'Noch nicht gesendet';
$GLOBALS['TL_LANG']['tl_newsletter']['headline']    = 'Newsletter versenden';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm']     = 'Der Newsletter wurde an %s Empfänger versendet.';
$GLOBALS['TL_LANG']['tl_newsletter']['error']       = 'Keine aktiven Abonnenten zu diesem Newsletter vorhanden.';
$GLOBALS['TL_LANG']['tl_newsletter']['from']        = 'Absender';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Anhänge';
$GLOBALS['TL_LANG']['tl_newsletter']['preview']     = 'Testsendung';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_newsletter']['new']        = array('Neuer Newsletter', 'Einen neuen Newsletter erstellen');
$GLOBALS['TL_LANG']['tl_newsletter']['edit']       = array('Newsletter bearbeiten', 'Newsletter ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_newsletter']['copy']       = array('Newsletter duplizieren', 'Newsletter ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_newsletter']['delete']     = array('Newsletter löschen', 'Newsletter ID %s löschen');
$GLOBALS['TL_LANG']['tl_newsletter']['show']       = array('Details des Newsletter', 'Details des Newsletter ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'] = array('Newsletter Header bearbeiten', 'Den Header des Newsletters bearbeiten');
$GLOBALS['TL_LANG']['tl_newsletter']['send']       = array('Newsletter versenden', 'Newsletter ID %s versenden');

?>