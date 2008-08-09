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
 * @package    Listing
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['list_table']       = array('Tabelle', 'Bitte wählen Sie die Tabelle, deren Datensätze Sie auflisten möchten.');
$GLOBALS['TL_LANG']['tl_module']['list_fields']      = array('Felder', 'Bitte geben Sie eine durch Komma getrennte Liste der Felder ein, die Sie auflisten möchten.');
$GLOBALS['TL_LANG']['tl_module']['list_where']       = array('Bedingung', 'Um bestimmte Datensätze auszuschließen, können Sie hier eine Bedingung eingeben (z.B. <em>published=1</em> oder <em>type!=\'admin\'</em>).');
$GLOBALS['TL_LANG']['tl_module']['list_sort']        = array('Sortieren nach', 'Bitte geben Sie eine durch Komma getrennte Liste der Felder ein, nach denen sortiert werden soll. Fügen Sie <em>DESC</em> nach dem Feldnamen ein, um in absteigender Reihenfolge zu sortieren (z.B. <em>name, date DESC</em>).');
$GLOBALS['TL_LANG']['tl_module']['list_layout']      = array('Listenvorlage', 'Bitte wählen Sie ein Listenlayout. Sie können eigene Listenlayouts im Ordner <em>templates</em> speichern. Listenvorlagen müssen mit <em>list_</em> beginnen und die Dateiendung <em>.tpl</em> haben.');
$GLOBALS['TL_LANG']['tl_module']['list_search']      = array('Durchsuchbare Felder', 'Bitte geben Sie eine durch Komma getrennte Liste der Felder ein, die im Frontend durchsuchbar sein sollen.');
$GLOBALS['TL_LANG']['tl_module']['list_info']        = array('Felder der Detailseite', 'Bitte geben Sie eine durch Komma getrennte Liste der Felder ein, die Sie auf der Detailseite anzeigen möchten. Lassen Sie das Feld leer, um die Detailansicht eines Datensatzes zu deaktivieren.');
$GLOBALS['TL_LANG']['tl_module']['list_info_layout'] = array('Detailseitenvorlage', 'Bitte wählen Sie ein Detailseitenlayout. Sie können eigene Layouts im Ordner <em>templates</em> speichern. Detailseitenvorlagen müssen mit <em>info_</em> beginnen und die Dateiendung <em>.tpl</em> haben.');

?>