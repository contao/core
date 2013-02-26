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
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['cal_calendar']      = array('Kalender', 'Bitte wählen Sie einen oder mehrere Kalender.');
$GLOBALS['TL_LANG']['tl_module']['cal_noSpan']        = array('Verkürzte Darstellung', 'Events nur einmal anzeigen, auch wenn sie mehrere Tage umfassen.');
$GLOBALS['TL_LANG']['tl_module']['cal_startDay']      = array('Erster Wochentag', 'Hier können Sie den ersten Tag der Woche festlegen.');
$GLOBALS['TL_LANG']['tl_module']['cal_format']        = array('Anzeigeformat', 'Hier können Sie das Anzeigeformat der Eventliste auswählen.');
$GLOBALS['TL_LANG']['tl_module']['cal_ignoreDynamic'] = array('URL-Parameter ignorieren', 'Den Zeitraum nicht anhand der date/month/year-Parameter in der URL ändern.');
$GLOBALS['TL_LANG']['tl_module']['cal_order']         = array('Sortierreihenfolge', 'Hier können Sie die Sortierreihenfolge festlegen.');
$GLOBALS['TL_LANG']['tl_module']['cal_readerModule']  = array('Eventleser', 'Automatisch zum Eventleser wechseln wenn ein Event ausgewählt wurde.');
$GLOBALS['TL_LANG']['tl_module']['cal_limit']         = array('Anzahl an Events', 'Hier können Sie die Event-Anzahl beschränken. Geben Sie 0 ein, um alle anzuzeigen.');
$GLOBALS['TL_LANG']['tl_module']['cal_template']      = array('Event-Template', 'Hier können Sie das Event-Template auswählen.');
$GLOBALS['TL_LANG']['tl_module']['cal_ctemplate']     = array('Kalendar-Template', 'Hier können Sie das Kalendar-Template auswählen.');
$GLOBALS['TL_LANG']['tl_module']['cal_showQuantity']  = array('Anzahl der Events anzeigen', 'Die Anzahl der Events jedes Monats im Menü anzeigen.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_module']['cal_list']       = 'Eventliste';
$GLOBALS['TL_LANG']['tl_module']['cal_day']        = 'Tag';
$GLOBALS['TL_LANG']['tl_module']['cal_month']      = 'Monat';
$GLOBALS['TL_LANG']['tl_module']['cal_year']       = 'Jahr';
$GLOBALS['TL_LANG']['tl_module']['cal_all']        = 'Alle Events';
$GLOBALS['TL_LANG']['tl_module']['cal_upcoming']   = 'Zukünftige Events';
$GLOBALS['TL_LANG']['tl_module']['next_7']         = '+ 1 Woche';
$GLOBALS['TL_LANG']['tl_module']['next_14']        = '+ 2 Wochen';
$GLOBALS['TL_LANG']['tl_module']['next_30']        = '+ 1 Monat';
$GLOBALS['TL_LANG']['tl_module']['next_90']        = '+ 3 Monate';
$GLOBALS['TL_LANG']['tl_module']['next_180']       = '+ 6 Monate';
$GLOBALS['TL_LANG']['tl_module']['next_365']       = '+ 1 Jahr';
$GLOBALS['TL_LANG']['tl_module']['next_two']       = '+ 2 Jahre';
$GLOBALS['TL_LANG']['tl_module']['next_cur_month'] = 'des laufenden Monats';
$GLOBALS['TL_LANG']['tl_module']['next_cur_year']  = 'des laufenden Jahres';
$GLOBALS['TL_LANG']['tl_module']['next_all']       = 'Alle zukünftigen Events';
$GLOBALS['TL_LANG']['tl_module']['cal_past']       = 'Vergangene Events';
$GLOBALS['TL_LANG']['tl_module']['past_7']         = '- 1 Woche';
$GLOBALS['TL_LANG']['tl_module']['past_14']        = '- 2 Wochen';
$GLOBALS['TL_LANG']['tl_module']['past_30']        = '- 1 Monat';
$GLOBALS['TL_LANG']['tl_module']['past_90']        = '- 3 Monate';
$GLOBALS['TL_LANG']['tl_module']['past_180']       = '- 6 Monate';
$GLOBALS['TL_LANG']['tl_module']['past_365']       = '- 1 Jahr';
$GLOBALS['TL_LANG']['tl_module']['past_two']       = '- 2 Jahre';
$GLOBALS['TL_LANG']['tl_module']['past_cur_month'] = 'des laufenden Monats';
$GLOBALS['TL_LANG']['tl_module']['past_cur_year']  = 'des laufenden Jahres';
$GLOBALS['TL_LANG']['tl_module']['past_all']       = 'Alle vergangenen Events';

?>