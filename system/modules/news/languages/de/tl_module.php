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
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['news_archives']      = array('Nachrichtenarchive', 'Bitte wählen Sie ein oder mehrere Nachrichtenarchive.');
$GLOBALS['TL_LANG']['tl_module']['news_featured']      = array('Hervorgehobene Beiträge', 'Hier legen Sie fest, wie hervorgehobene Beiträge gehandhabt werden.');
$GLOBALS['TL_LANG']['tl_module']['news_numberOfItems'] = array('Gesamtzahl der Beiträge', 'Hier können Sie die Gesamtzahl der Beiträge begrenzen. Geben Sie 0 ein, um alle anzuzeigen.');
$GLOBALS['TL_LANG']['tl_module']['news_jumpToCurrent'] = array('Kein Zeitraum ausgewählt', 'Hier legen Sie fest, was angezeigt wird, wenn kein Zeitraum ausgewählt ist.');
$GLOBALS['TL_LANG']['tl_module']['news_readerModule']  = array('Nachrichtenleser', 'Automatisch zum Nachrichtenleser wechseln wenn ein Beitrag ausgewählt wurde.');
$GLOBALS['TL_LANG']['tl_module']['news_metaFields']    = array('Meta-Felder', 'Hier können Sie die Meta-Felder auswählen.');
$GLOBALS['TL_LANG']['tl_module']['news_template']      = array('Nachrichtentemplate', 'Hier können Sie das Nachrichtentemplate auswählen.');
$GLOBALS['TL_LANG']['tl_module']['news_format']        = array('Archivformat', 'Hier können Sie das Archivformat auswählen.');
$GLOBALS['TL_LANG']['tl_module']['news_startDay']      = array('Erster Wochentag', 'Hier können Sie den ersten Tag der Woche festlegen.');
$GLOBALS['TL_LANG']['tl_module']['news_order']         = array('Sortierreihenfolge', 'Hier können Sie die Sortierreihenfolge festlegen.');
$GLOBALS['TL_LANG']['tl_module']['news_showQuantity']  = array('Anzahl der Beiträge anzeigen', 'Die Anzahl der Beiträge jedes Monats anzeigen.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['news_day']     = 'Tag';
$GLOBALS['TL_LANG']['tl_module']['news_month']   = 'Monat';
$GLOBALS['TL_LANG']['tl_module']['news_year']    = 'Jahr';
$GLOBALS['TL_LANG']['tl_module']['hide_module']  = 'Das Modul ausblenden';
$GLOBALS['TL_LANG']['tl_module']['show_current'] = 'Zum aktuellen Zeitraum springen';
$GLOBALS['TL_LANG']['tl_module']['all_items']    = 'Alle Beiträge anzeigen';
$GLOBALS['TL_LANG']['tl_module']['featured']     = 'Nur hervorgehobene Beiträge anzeigen';
$GLOBALS['TL_LANG']['tl_module']['unfeatured']   = 'Hervorgehobene Beiträge überspringen';

?>