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
 * @copyright  Leo Feyer 2008 
 * @author     Leo Feyer <leo@typolight.org> 
 * @package    Faq 
 * @license    LGPL 
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_faq_category']['title']    = array('Titel', 'Bitte geben Sie den Titel der Kategorie ein.');
$GLOBALS['TL_LANG']['tl_faq_category']['headline'] = array('Überschrift', 'Bitte geben Sie eine Überschrift für die Kategorie ein.');
$GLOBALS['TL_LANG']['tl_faq_category']['tstamp']   = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');
$GLOBALS['TL_LANG']['tl_faq_category']['jumpTo']   = array('Weiterleitung zu Seite', 'Bitte wählen Sie die Seite, zu der ein Besucher weitergeleitet werden soll wenn er eine Frage anklickt.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_faq_category']['deleteConfirm'] = 'Wenn Sie eine Kategorie löschen werde auch alle darin enthaltenen FAQs gelöscht. Wollen Sie die Kategorie ID %s wirklich löschen?';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_faq_category']['new']    = array('Neue Kategorie', 'Eine neue Kategorie anlegen');
$GLOBALS['TL_LANG']['tl_faq_category']['edit']   = array('Kategorie bearbeiten', 'Kategorie ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_faq_category']['copy']   = array('Kategorie duplizieren', 'Kategorie ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_faq_category']['delete'] = array('Kategorie löschen', 'Kategorie ID %s löschen');
$GLOBALS['TL_LANG']['tl_faq_category']['show']   = array('Kategoriedetails', 'Details der Kategorie ID %s anzeigen');

?>