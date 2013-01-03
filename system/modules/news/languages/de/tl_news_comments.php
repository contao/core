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
$GLOBALS['TL_LANG']['tl_news_comments']['pid']       = array('Nachrichtenbeitrag', 'Der dazugehörige Nachrichtenbeitrag.');
$GLOBALS['TL_LANG']['tl_news_comments']['date']      = array('Datum', 'Bitte geben Sie das Kommentardatum ein.');
$GLOBALS['TL_LANG']['tl_news_comments']['name']      = array('Autor', 'Bitte geben Sie den Namen des Autors ein.');
$GLOBALS['TL_LANG']['tl_news_comments']['email']     = array('E-Mail-Adresse', 'Die E-Mail-Adresse wird nicht veröffentlicht.');
$GLOBALS['TL_LANG']['tl_news_comments']['website']   = array('Webseite', 'Hier können Sie eine Webadresse eingeben.');
$GLOBALS['TL_LANG']['tl_news_comments']['comment']   = array('Kommentar', 'Bitte geben Sie den Kommentar ein.');
$GLOBALS['TL_LANG']['tl_news_comments']['published'] = array('Kommentar veröffentlichen', 'Den Kommentar auf der Webseite anzeigen.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news_comments']['author_legend']  = 'Autor';
$GLOBALS['TL_LANG']['tl_news_comments']['comment_legend'] = 'Kommentar';
$GLOBALS['TL_LANG']['tl_news_comments']['publish_legend'] = 'Veröffentlichung';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_news_comments']['approved'] = 'Genehmigt';
$GLOBALS['TL_LANG']['tl_news_comments']['pending']  = 'Zur Überprüfung';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news_comments']['show']       = array('Kommentardetails', 'Details des Kommentars ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_news_comments']['edit']       = array('Kommentar bearbeiten', 'Kommentar ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_news_comments']['delete']     = array('Kommentar löschen', 'Kommentar ID %s löschen');
$GLOBALS['TL_LANG']['tl_news_comments']['toggle']     = array('Kommentar veröffentlichen/unveröffentlichen', 'Kommentar ID %s veröffentlichen/unveröffentlichen');
$GLOBALS['TL_LANG']['tl_news_comments']['editheader'] = array('Archiv bearbeiten', 'Das aktuelle Archiv bearbeiten');

?>