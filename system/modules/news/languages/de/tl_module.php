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
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['news_archives']      = array('Nachrichtenarchive', 'Bitte wählen Sie mindestens ein Nachrichtenarchiv.');
$GLOBALS['TL_LANG']['tl_module']['news_showQuantity']  = array('Anzahl der Einträge anzeigen', 'Die Anzahl der Beiträge jedes Monats im Archivmenü angezeigt.');
$GLOBALS['TL_LANG']['tl_module']['news_numberOfItems'] = array('Anzahl an Nachrichten', 'Bitte legen Sie fest, wie viele Beiträge angezeigt werden sollen (0 = alle Beiträge anzeigen).');
$GLOBALS['TL_LANG']['tl_module']['news_template']      = array('Nachrichtenvorlage', 'Bitte wählen Sie ein Nachrichtenlayout. Sie können eigene <em>news_</em>-Vorlagen im Ordner <em>templates</em> speichern.');
$GLOBALS['TL_LANG']['tl_module']['news_metaFields']    = array('Felder des Nachrichtenkopfs', 'Bitte wählen Sie, welche Felder im Nachrichtenkopf angezeigt werden sollen.');
$GLOBALS['TL_LANG']['tl_module']['news_format']        = array('Format', 'Bitte wählen Sie ein Archivformat.');
$GLOBALS['TL_LANG']['tl_module']['news_dateFormat']    = array('Datumsformat', 'Bitte geben Sie ein gültiges Datumsformat wie bei der PHP Funktion <em>date()</em> ein.');
$GLOBALS['TL_LANG']['tl_module']['news_jumpToCurrent'] = array('Zum aktuellen Monat springen', 'Zum aktuellen Monat springen wenn kein bestimmter Monat in der URL vorgegeben wurde.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['news_month'] = 'Monat';
$GLOBALS['TL_LANG']['tl_module']['news_year']  = 'Jahr';

?>