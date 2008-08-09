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
$GLOBALS['TL_LANG']['tl_news_comments']['name']      = array('Name', 'Bitte geben Sie den richtigen Namen des Autors ein.');
$GLOBALS['TL_LANG']['tl_news_comments']['email']     = array('E-Mail-Adresse', 'Bitte geben Sie die E-Mail-Adresse des Autors ein (wird nicht veröffentlicht).');
$GLOBALS['TL_LANG']['tl_news_comments']['website']   = array('Webseite', 'Bitte geben Sie eine optionale Webadresse ein.');
$GLOBALS['TL_LANG']['tl_news_comments']['comment']   = array('Kommentar', 'Bitte geben Sie den Kommentar ein.');
$GLOBALS['TL_LANG']['tl_news_comments']['published'] = array('Veröffentlicht', 'Nur veröffentlichte Kommentare erscheinen auf der Webseite.');
$GLOBALS['TL_LANG']['tl_news_comments']['date']      = array('Datum', 'Bitte geben Sie ein Datum ein.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_news_comments']['approved'] = 'Genehmigt';
$GLOBALS['TL_LANG']['tl_news_comments']['pending']  = 'Zur Überprüfung';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news_comments']['edit']       = array('Kommentar bearbeiten', 'Kommentar ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_news_comments']['delete']     = array('Kommentar löschen', 'Kommentar ID %s löschen');
$GLOBALS['TL_LANG']['tl_news_comments']['show']       = array('Kommentardetails', 'Details des Kommentars ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_news_comments']['editheader'] = array('Archiv bearbeiten', 'Das aktuelle Archiv bearbeiten');

?>