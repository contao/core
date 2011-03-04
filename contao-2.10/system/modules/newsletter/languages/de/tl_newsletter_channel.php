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
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_newsletter_channel']['title']    = array('Titel', 'Bitte geben Sie den Titel des Verteilers ein.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['jumpTo']   = array('Weiterleitungsseite', 'Bitte wählen Sie die Newsletterleser-Seite aus, zu der Besucher weitergeleitet werden, wenn Sie einen Newsletter anklicken.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['useSMTP']  = array('Eigener SMTP-Server', 'Einen eigenen SMTP-Server für den Newsletter-Versand verwenden.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtpHost'] = array('SMTP-Hostname', 'Bitte geben Sie den Hostnamen des SMTP-Servers ein.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtpUser'] = array('SMTP-Benutzername', 'Hier können Sie den SMTP-Benutzernamen eingeben.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtpPass'] = array('SMTP-Passwort', 'Hier können Sie das SMTP-Passwort eingeben.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtpEnc']  = array('SMTP-Verschlüsselung', 'Hier können Sie eine Verschlüsselungsmethode auswählen (SSL oder TLS).');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtpPort'] = array('SMTP-Portnummer', 'Bitte geben Sie die Portnummer des SMTP-Servers ein.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['tstamp']   = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_newsletter_channel']['title_legend'] = 'Titel und Weiterleitung';
$GLOBALS['TL_LANG']['tl_newsletter_channel']['smtp_legend']  = 'SMTP-Einstellungen';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_newsletter_channel']['new']        = array('Neuer Verteiler', 'Einen neuen Verteiler erstellen');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['show']       = array('Verteilerdetails', 'Details des Verteilers ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['edit']       = array('Verteiler bearbeiten', 'Verteiler ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['editheader'] = array('Verteiler-Einstellungen bearbeiten', 'Einstellungen des Verteilers ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['copy']       = array('Verteiler duplizieren', 'Verteiler ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['delete']     = array('Verteiler löschen', 'Verteiler ID %s löschen');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['recipients'] = array('Abonnenten bearbeiten', 'Abonnenten des Verteilers ID %s bearbeiten');

?>