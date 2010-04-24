<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_newsletter']['subject']       = array('Betreff', 'Bitte geben Sie den Betreff des Newsletters ein.');
$GLOBALS['TL_LANG']['tl_newsletter']['alias']         = array('Newsletteralias', 'Der Newsletteralias ist eine eindeutige Referenz, die anstelle der numerischen Newsletter-Id aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_newsletter']['content']       = array('HTML-Inhalt', 'Hier können Sie den HTML-Inhalt des Newsletters eingeben. Benutzen Sie den Platzhalter <em>##email##</em>, um die Empfängeradresse einzufügen.');
$GLOBALS['TL_LANG']['tl_newsletter']['text']          = array('Text-Inhalt', 'Hier können Sie den Text-Inhalt des Newsletters eingeben. Benutzen Sie den Platzhalter <em>##email##</em>, um die Empfängeradresse einzufügen.');
$GLOBALS['TL_LANG']['tl_newsletter']['addFile']       = array('Dateien anhängen', 'Dem Newsletter eine oder mehrere Dateien anhängen.');
$GLOBALS['TL_LANG']['tl_newsletter']['files']         = array('Dateianhänge', 'Bitte wählen Sie die anzuhängenden Dateien aus der Dateiübersicht.');
$GLOBALS['TL_LANG']['tl_newsletter']['template']      = array('E-Mail-Template', 'Hier können Sie das E-Mail-Template auswählen.');
$GLOBALS['TL_LANG']['tl_newsletter']['sendText']      = array('Als Text senden', 'Den Newsletter als reinen Text ohne HTML-Inhalt versenden.');
$GLOBALS['TL_LANG']['tl_newsletter']['senderName']    = array('Absendername', 'Hier können Sie den Namen des Absenders eingeben.');
$GLOBALS['TL_LANG']['tl_newsletter']['sender']        = array('Absenderadresse', 'Hier können Sie eine individuelle Absenderadresse eingeben.');
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'] = array('Testsendung an', 'Die Testsendung des Newsletters an diese E-Mail-Adresse versenden.');
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'] = array('Mails pro Zyklus', 'Die Versendung wird in mehreren Teilschritten ausgeführt, um einen vorzeitigen Skriptabbruch zu verhindern.');
$GLOBALS['TL_LANG']['tl_newsletter']['timeout']       = array('Wartezeit in Sekunden', 'Hier können Sie die Wartezeit zwischen den Teilschritten festlegen, um die Anzahl der E-Mails pro Minute zu kontrollieren.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend']      = 'Titel und Betreff';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend']       = 'HTML-Inhalt';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend']       = 'Text-Inhalt';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Dateianhänge';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend']   = 'Template-Einstellungen';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend']     = 'Experten-Einstellungen';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_newsletter']['sent']        = 'Gesendet';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn']      = 'Gesendet am %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent']     = 'Noch nicht gesendet';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Versanddatum';
$GLOBALS['TL_LANG']['tl_newsletter']['headline']    = 'Newsletter versenden';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm']     = 'Der Newsletter wurde an %s Empfänger versendet.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected']    = '%s ungültige E-Mail-Adresse(n) wurde(n) deaktiviert (siehe System-Log).';
$GLOBALS['TL_LANG']['tl_newsletter']['error']       = 'In diesem Verteiler sind keine aktiven Abonnenten vorhanden.';
$GLOBALS['TL_LANG']['tl_newsletter']['from']        = 'Absender';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Dateianhänge';
$GLOBALS['TL_LANG']['tl_newsletter']['preview']     = 'Testsendung';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_newsletter']['new']        = array('Neuer Newsletter', 'Einen neuen Newsletter erstellen');
$GLOBALS['TL_LANG']['tl_newsletter']['show']       = array('Newsletterdetails', 'Details des Newsletter ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_newsletter']['edit']       = array('Newsletter bearbeiten', 'Newsletter ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_newsletter']['copy']       = array('Newsletter duplizieren', 'Newsletter ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_newsletter']['cut']        = array('Newsletter verschieben', 'Newsletter ID %s verschieben');
$GLOBALS['TL_LANG']['tl_newsletter']['delete']     = array('Newsletter löschen', 'Newsletter ID %s löschen');
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'] = array('Verteiler bearbeiten', 'Die Verteiler-Einstellungen bearbeiten');
$GLOBALS['TL_LANG']['tl_newsletter']['send']       = array('Newsletter versenden', 'Newsletter ID %s versenden');

?>