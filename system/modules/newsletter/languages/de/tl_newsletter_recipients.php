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
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['email']  = array('E-Mail-Adresse', 'Bitte geben Sie die E-Mail-Adresse des Empfängers ein.');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['active'] = array('Aktiviert', 'E-Mail-Adressen werden normalerweise durch Anklicken eines Links in der Bestätigungsmail aktiviert (double-opt-in).');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['source'] = array('Quelldateien', 'Bitte wählen Sie die CSV-Dateien, die Sie importieren möchten.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['confirm'] = '%s Empfänger wurden importiert.';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['new']    = array('Empfänger hinzufügen', 'Einen neuen Empfänger hinzufügen');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['edit']   = array('Empfänger bearbeiten', 'Empfänger ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['copy']   = array('Empfänger duplizieren', 'Empfänger ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['delete'] = array('Empfänger löschen', 'Empfänger ID %s löschen');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['show']   = array('Empfängerdetails', 'Details des Empfängers ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_newsletter_recipients']['import'] = array('CSV-Import', 'Empfänger aus einer CSV-Datei importieren');

?>