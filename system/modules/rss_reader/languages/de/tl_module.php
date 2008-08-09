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
 * @package    RssReader
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['rss_feed']          = array('Feed-URL', 'Bitte geben Sie die URL des RSS-Feeds ein.');
$GLOBALS['TL_LANG']['tl_module']['rss_template']      = array('Layoutvorlage', 'Bitte wählen Sie eine Layoutvorlage. Sie können eigene Layoutvorlagen im Ordner <em>templates</em> speichern. Vorlagen müssen mit <em>rss_</em> beginnen und die Dateiendung <em>.tpl</em> haben.');
$GLOBALS['TL_LANG']['tl_module']['rss_numberOfItems'] = array('Anzahl an Beiträgen', 'Bitte legen Sie fest, wie viele Beiträge angezeigt werden sollen (0 = alle Beiträge anzeigen).');
$GLOBALS['TL_LANG']['tl_module']['rss_cache']         = array('Cache-Verfallszeit', 'Hier können Sie festlegen, wie lange der RSS-Feed zwischengespeichert wird.');

?>