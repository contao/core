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
$GLOBALS['TL_LANG']['tl_newsletter_channel']['title']  = array('Titel', 'Bitte geben Sie den Titel des Newsletters ein.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['tstamp'] = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['jumpTo'] = array('Weiterleitung zu Seite', 'Bitte wählen Sie die Seite, zu der ein Besucher weitergeleitet werden soll wenn er einen Newsletter anklickt.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_newsletter_channel']['new']        = array('Neuer Verteiler', 'Einen neuen Verteiler erstellen');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['edit']       = array('Verteiler bearbeiten', 'Verteiler ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['copy']       = array('Verteiler duplizieren', 'Verteiler ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['delete']     = array('Verteiler löschen', 'Verteiler ID %s löschen');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['show']       = array('Verteilerdetails', 'Details des Verteilers ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['recipients'] = array('Empfänger bearbeiten', 'Empfänger des Verteilers ID %s bearbeiten');

?>