<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Faq
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_faq']['question']     = array('Frage', 'Bitte geben Sie die Frage ein.');
$GLOBALS['TL_LANG']['tl_faq']['alias']        = array('FAQ-Alias', 'Der FAQ-Alias ist eine eindeutige Referenz, die anstelle der numerischen FAQ-ID aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_faq']['author']       = array('Autor', 'Hier können Sie den Autor der Frage ändern.');
$GLOBALS['TL_LANG']['tl_faq']['answer']       = array('Antwort', 'Bitte geben Sie die Antwort ein.');
$GLOBALS['TL_LANG']['tl_faq']['addImage']     = array('Ein Bild hinzufügen', 'Der Frage ein Bild hinzufügen.');
$GLOBALS['TL_LANG']['tl_faq']['addEnclosure'] = array('Anlagen hinzufügen', 'Der Frage eine oder mehrere Dateien als Download hinzufügen.');
$GLOBALS['TL_LANG']['tl_faq']['enclosure']    = array('Anlagen', 'Bitte wählen Sie die Dateien aus, die Sie hinzufügen möchten.');
$GLOBALS['TL_LANG']['tl_faq']['noComments']   = array('Kommentare deaktivieren', 'Die Kommentarfunktion für diese Frage deaktivieren.');
$GLOBALS['TL_LANG']['tl_faq']['published']    = array('Frage veröffentlichen', 'Die Frage auf der Webseite anzeigen.');


/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_faq']['title_legend']     = 'Titel und Autor';
$GLOBALS['TL_LANG']['tl_faq']['answer_legend']    = 'Antwort';
$GLOBALS['TL_LANG']['tl_faq']['image_legend']     = 'Bild-Einstellungen';
$GLOBALS['TL_LANG']['tl_faq']['enclosure_legend'] = 'Anlagen';
$GLOBALS['TL_LANG']['tl_faq']['expert_legend']    = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_faq']['publish_legend']   = 'Veröffentlichung';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_faq']['new']        = array('Neue Frage', 'Eine neue Frage erstellen');
$GLOBALS['TL_LANG']['tl_faq']['show']       = array('Fragedetails', 'Details der Frage ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_faq']['edit']       = array('Frage bearbeiten', 'Frage ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_faq']['copy']       = array('Frage duplizieren', 'Frage ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_faq']['cut']        = array('Frage verschieben', 'Frage ID %s verschieben');
$GLOBALS['TL_LANG']['tl_faq']['delete']     = array('Frage löschen', 'Frage ID %s löschen');
$GLOBALS['TL_LANG']['tl_faq']['toggle']     = array('Frage veröffentlichen/unveröffentlichen', 'Frage ID %s veröffentlichen/unveröffentlichen');
$GLOBALS['TL_LANG']['tl_faq']['editheader'] = array('Kategorie bearbeiten', 'Die Kategorie-Einstellungen bearbeiten');
$GLOBALS['TL_LANG']['tl_faq']['pasteafter'] = array('Oben einfügen', 'Nach der Frage ID %s einfügen');
$GLOBALS['TL_LANG']['tl_faq']['pastenew']   = array('Neue Frage oben erstellen', 'Neues Element nach der Frage ID %s erstellen');

?>