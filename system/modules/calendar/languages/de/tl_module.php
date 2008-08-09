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
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['cal_calendar'] = array('Kalender', 'Bitte wählen Sie einen oder mehrere Kalender.');
$GLOBALS['TL_LANG']['tl_module']['cal_template'] = array('Eventvorlage', 'Bitte wählen Sie ein Eventlayout. Sie können eigene <em>event_</em>-Vorlagen im Ordner <em>templates</em> speichern.');
$GLOBALS['TL_LANG']['tl_module']['cal_startDay'] = array('Erster Wochentag', 'Bitte legen Sie den ersten Tag der Woche fest.');
$GLOBALS['TL_LANG']['tl_module']['cal_previous'] = array('Vorheriger Monat', 'Lassen Sie das Feld frei, um die Standardbeschriftung zu verwenden. HTML-Tags sind möglich.');
$GLOBALS['TL_LANG']['tl_module']['cal_next']     = array('Nächster Monat', 'Lassen Sie das Feld frei, um die Standardbeschriftung zu verwenden. HTML-Tags sind möglich.');
$GLOBALS['TL_LANG']['tl_module']['cal_format']   = array('Anzeigeformat', 'Bitte wählen Sie das Anzeigeformat der Eventliste.');
$GLOBALS['TL_LANG']['tl_module']['cal_limit']    = array('Event Anzahl', 'Bitte geben Sie die maximale Anzahl Events ein. Geben Sie 0 ein um alle Events anzuzeigen.');
$GLOBALS['TL_LANG']['tl_module']['cal_noSpan']   = array('Verkürzte Darstellung', 'Events nur einmal anzeigen auch wenn sie über mehrere Tage gehen.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_module']['cal_day']   = 'Tag';
$GLOBALS['TL_LANG']['tl_module']['cal_week']  = 'Woche';
$GLOBALS['TL_LANG']['tl_module']['cal_month'] = 'Monat';
$GLOBALS['TL_LANG']['tl_module']['cal_year']  = 'Jahr';
$GLOBALS['TL_LANG']['tl_module']['cal_two']   = '2 Jahre';
$GLOBALS['TL_LANG']['tl_module']['next_7']    = '+ 1 Woche';
$GLOBALS['TL_LANG']['tl_module']['next_14']   = '+ 2 Wochen';
$GLOBALS['TL_LANG']['tl_module']['next_30']   = '+ 1 Monat';
$GLOBALS['TL_LANG']['tl_module']['next_90']   = '+ 3 Monate';
$GLOBALS['TL_LANG']['tl_module']['next_180']  = '+ 6 Monate';
$GLOBALS['TL_LANG']['tl_module']['next_365']  = '+ 1 Jahr';

?>