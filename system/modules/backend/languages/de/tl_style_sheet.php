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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['name']   = array('Name', 'Bitte geben Sie einen eindeutigen Namen für das Stylesheet ein.');
$GLOBALS['TL_LANG']['tl_style_sheet']['tstamp'] = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');
$GLOBALS['TL_LANG']['tl_style_sheet']['media']  = array('Medientypen', 'Wenn das aktuelle Stylesheet nur für bestimmte Medientypen gedacht ist, können Sie dessen Verwendung durch das Auswählen beliebig vieler Medientypen auf diese beschränken.');
$GLOBALS['TL_LANG']['tl_style_sheet']['source'] = array('Quelldateien', 'Bitte wählen Sie die CSS-Dateien, die Sie importieren möchten.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_style_sheet']['new']    = array('Neues Stylesheet', 'Ein neues Stylesheet anlegen');
$GLOBALS['TL_LANG']['tl_style_sheet']['show']   = array('Stylesheetdetails', 'Details des Stylesheets ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_style_sheet']['edit']   = array('Stylesheet bearbeiten', 'Stylesheet ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_style_sheet']['copy']   = array('Stylesheet duplizieren', 'Stylesheet ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_style_sheet']['delete'] = array('Stylesheet löschen', 'Stylesheet ID %s löschen');
$GLOBALS['TL_LANG']['tl_style_sheet']['import'] = array('CSS Import', 'Eine bestehende CSS-Datei importieren');


/**
 * Messages
 */
$GLOBALS['TL_LANG']['ERROR']['css_exists']   = 'ACHTUNG! Gleichnamige Stylesheets werden überschrieben. Fortfahren?';
$GLOBALS['TL_LANG']['CONFIRM']['css_exists'] = 'Das Stylesheet "%s" wurde importiert.';

?>