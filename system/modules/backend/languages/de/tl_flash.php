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
$GLOBALS['TL_LANG']['tl_flash']['title']   = array('Titel', 'Bitte geben Sie einen eindeutigen Titel für den Artikel ein.');
$GLOBALS['TL_LANG']['tl_flash']['flashID'] = array('Flash-ID', 'Bitte geben Sie eine eindeutige Flash-ID ein. Um den Artikel in ein Flash-Film zu laden, muss die Flash-ID einem dynamischen Textfeld übergeben werden (z.B. <em>textfeld._loadArticle("flashID");</em>).');
$GLOBALS['TL_LANG']['tl_flash']['content'] = array('Artikeltext', 'Bitte geben Sie den Text des Artikels ein. HTML-Tags sind erlaubt, werden aber von Flash nur sehr eingeschränkt unterstüzt.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_flash']['new']    = array('Neues Inhaltselement', 'Ein neues Flash Inhaltselement anlegen');
$GLOBALS['TL_LANG']['tl_flash']['edit']   = array('Inhaltselement bearbeiten', 'Das Inhaltselement ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_flash']['copy']   = array('Inhaltselement duplizieren', 'Das Inhaltselement ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_flash']['delete'] = array('Inhaltselement löschen', 'Das Inhaltselement ID %s löschen');
$GLOBALS['TL_LANG']['tl_flash']['show']   = array('Details anzeigen', 'Details des Inhaltselements ID %s anzeigen');

?>