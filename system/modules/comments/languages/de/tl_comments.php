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
 * @package    Comments
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_comments']['source']    = array('Ursprung', 'Die bezogene Tabelle.');
$GLOBALS['TL_LANG']['tl_comments']['parent']    = array('Elternelement', 'Das dazugehörige Elternelement.');
$GLOBALS['TL_LANG']['tl_comments']['date']      = array('Datum', 'Bitte geben Sie das Kommentardatum ein.');
$GLOBALS['TL_LANG']['tl_comments']['name']      = array('Autor', 'Bitte geben Sie den Namen des Autors ein.');
$GLOBALS['TL_LANG']['tl_comments']['email']     = array('E-Mail-Adresse', 'Die E-Mail-Adresse wird nicht veröffentlicht.');
$GLOBALS['TL_LANG']['tl_comments']['website']   = array('Webseite', 'Hier können Sie eine Webadresse eingeben.');
$GLOBALS['TL_LANG']['tl_comments']['comment']   = array('Kommentar', 'Bitte geben Sie den Kommentar ein.');
$GLOBALS['TL_LANG']['tl_comments']['addReply']  = array('Antwort hinzufügen', 'Hier können Sie auf den Kommentar antworten.');
$GLOBALS['TL_LANG']['tl_comments']['author']    = array('Autor', 'Hier können Sie den Autor der Antwort ändern.');
$GLOBALS['TL_LANG']['tl_comments']['reply']     = array('Antwort', 'Hier können Sie Ihre Antwort eingeben.');
$GLOBALS['TL_LANG']['tl_comments']['published'] = array('Kommentar veröffentlichen', 'Den Kommentar auf der Webseite anzeigen.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_comments']['author_legend']  = 'Autor';
$GLOBALS['TL_LANG']['tl_comments']['comment_legend'] = 'Kommentar';
$GLOBALS['TL_LANG']['tl_comments']['reply_legend']   = 'Antwort';
$GLOBALS['TL_LANG']['tl_comments']['publish_legend'] = 'Veröffentlichung';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_comments']['approved']           = 'Genehmigt';
$GLOBALS['TL_LANG']['tl_comments']['pending']            = 'Zur Überprüfung';
$GLOBALS['TL_LANG']['tl_comments']['tl_content']         = 'Artikel';
$GLOBALS['TL_LANG']['tl_comments']['tl_page']            = 'Seite';
$GLOBALS['TL_LANG']['tl_comments']['tl_news']            = 'Nachricht';
$GLOBALS['TL_LANG']['tl_comments']['tl_faq']             = 'FAQ';
$GLOBALS['TL_LANG']['tl_comments']['tl_calendar_events'] = 'Event';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_comments']['show']   = array('Kommentardetails', 'Details des Kommentars ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_comments']['edit']   = array('Kommentar bearbeiten', 'Kommentar ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_comments']['delete'] = array('Kommentar löschen', 'Kommentar ID %s löschen');
$GLOBALS['TL_LANG']['tl_comments']['toggle'] = array('Kommentar veröffentlichen/unveröffentlichen', 'Kommentar ID %s veröffentlichen/unveröffentlichen');

?>