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
 * @link https://www.transifex.com/projects/p/contao/language/de/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = 'Betreff';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'Bitte geben Sie den Betreff des Newsletters ein.';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'Newsletteralias';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'Der Newsletteralias ist eine eindeutige Referenz, die anstelle der numerischen Newsletter-ID aufgerufen werden kann.';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'HTML-Inhalt';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'Hier können Sie den HTML-Inhalt des Newsletters eingeben. Benutzen Sie den Platzhalter <em>##email##</em>, um die Empfängeradresse einzufügen.';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'Text-Inhalt';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'Hier können Sie den Text-Inhalt des Newsletters eingeben. Benutzen Sie den Platzhalter <em>##email##</em>, um die Empfängeradresse einzufügen.';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = 'Dateien anhängen';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'Dem Newsletter eine oder mehrere Dateien anhängen.';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = 'Dateianhänge';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'Bitte wählen Sie die anzuhängenden Dateien aus der Dateiübersicht.';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = 'E-Mail-Template';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = 'Hier können Sie das E-Mail-Template auswählen.';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'Als Text senden';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'Den Newsletter als reinen Text ohne HTML-Inhalt versenden.';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = 'Externe Bilder';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'Bilder in HTML-Newslettern nicht einbetten.';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = 'Absendername';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = 'Hier können Sie den Namen des Absenders eingeben.';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = 'Absenderadresse';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = 'Hier können Sie eine individuelle Absenderadresse eingeben.';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = 'Mails pro Zyklus';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'Die Versendung wird in mehreren Teilschritten ausgeführt, um einen vorzeitigen Skriptabbruch zu verhindern.';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = 'Wartezeit in Sekunden';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = 'Hier können Sie die Wartezeit zwischen den Teilschritten festlegen, um die Anzahl der E-Mails pro Minute zu kontrollieren.';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = 'Versatz';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = 'Falls eine Versendung unterbrochen wurde, können Sie hier festlegen, ab welchem Empfänger diese weiterlaufen soll. In der Datei <em>system/logs/newsletter_*.log</em> können Sie prüfen, wie viele Mails bereits versendet wurden. Waren es z.B. 120 Mails, geben Sie "120" ein, um mit dem 121. Empfänger fortzufahren (die Zählung beginnt bei 0).';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'Testsendung an';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'Die Testsendung des Newsletters an diese E-Mail-Adresse versenden.';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Titel und Betreff';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'HTML-Inhalt';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Text-Inhalt';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Dateianhänge';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Template-Einstellungen';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Gesendet';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Gesendet am %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Noch nicht gesendet';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Versanddatum';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'Der Newsletter wurde an %s Empfänger versendet.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '%s ungültige E-Mail-Adresse(n) wurde(n) deaktiviert (siehe System-Log).';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'In diesem Verteiler sind keine aktiven Abonnenten vorhanden.';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'Absender';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Dateianhänge';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Testsendung';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Soll der Newsletter wirklich verschickt werden?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = 'Neuer Newsletter';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = 'Einen neuen Newsletter erstellen';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'Newsletterdetails';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'Details des Newsletter ID %s anzeigen';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'Newsletter bearbeiten';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'Newsletter ID %s bearbeiten';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'Newsletter duplizieren';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'Newsletter ID %s duplizieren';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'Newsletter verschieben';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'Newsletter ID %s verschieben';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'Newsletter löschen';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'Newsletter ID %s löschen';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'Verteiler bearbeiten';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'Die Verteiler-Einstellungen bearbeiten';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'In diesen Verteiler einfügen';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'Nach dem Newsletter ID %s einfügen';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'Newsletter versenden';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'Newsletter ID %s versenden';
