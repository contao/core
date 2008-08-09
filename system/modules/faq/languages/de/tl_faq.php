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
$GLOBALS['TL_LANG']['tl_faq']['question']     = array('Frage', 'Bitte geben Sie die Frage ein.');
$GLOBALS['TL_LANG']['tl_faq']['alias']        = array('FAQ-Alias', 'Ein Alias ist eine eindeutige Referenz, die anstelle der ID aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_faq']['answer']       = array('Antwort', 'Bitte geben Sie die Antwort ein.');
$GLOBALS['TL_LANG']['tl_faq']['addImage']     = array('Ein Bild einfügen', 'Der Antwort ein Bild hinzufügen.');
$GLOBALS['TL_LANG']['tl_faq']['addEnclosure'] = array('Enclosure hinzufügen', 'Der Antwort eine oder mehrere Dateien als Download hinzufügen.');
$GLOBALS['TL_LANG']['tl_faq']['enclosure']    = array('Enclosure', 'Bitte wählen Sie die Dateien, die Sie der Antwort hinzufügen möchten.');
$GLOBALS['TL_LANG']['tl_faq']['author']       = array('Autor', 'Bitte wählen Sie einen Autor.');
$GLOBALS['TL_LANG']['tl_faq']['published']    = array('Veröffentlicht', 'Die FAQ wird erst auf Ihrer Webseite erscheinen, wenn sie veröffentlicht ist.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_faq']['new']        = array('Neue Frage', 'Eine Frage erstellen');
$GLOBALS['TL_LANG']['tl_faq']['edit']       = array('Frage bearbeiten', 'Frage ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_faq']['copy']       = array('Frage duplizieren', 'Frage ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_faq']['cut']        = array('Frage verschieben', 'Frage ID %s verschieben');
$GLOBALS['TL_LANG']['tl_faq']['delete']     = array('Frage löschen', 'Frage ID %s löschen');
$GLOBALS['TL_LANG']['tl_faq']['show']       = array('Fragedetails', 'Details der Frage ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_faq']['editheader'] = array('Kategorie bearbeiten', 'Diese Kategorie bearbeiten');
$GLOBALS['TL_LANG']['tl_faq']['pasteafter'] = array('Am Anfang einfügen', 'Nach der Frage ID %s einfügen');
$GLOBALS['TL_LANG']['tl_faq']['pastenew']   = array('Neue Frage am Anfang erstellen', 'Neue Frage nach der Frage ID %s erstellen');

?>