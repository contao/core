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
$GLOBALS['TL_LANG']['tl_task']['title']       = array('Titel', 'Bitte geben Sie einen Titel ein.');
$GLOBALS['TL_LANG']['tl_task']['assignTo']    = array('Übertragen an', 'Bitte weisen Sie die Aufgabe einem Benutzer zu.');
$GLOBALS['TL_LANG']['tl_task']['notify']      = array('Benutzer benachrichtigen', 'Den Benutzer per E-Mail benachrichtigen.');
$GLOBALS['TL_LANG']['tl_task']['deadline']    = array('Deadline', 'Bitte geben Sie die Deadline der Aufgabe ein.');
$GLOBALS['TL_LANG']['tl_task']['status']      = array('Status', 'Bitte wählen Sie einen Status.');
$GLOBALS['TL_LANG']['tl_task']['progress']    = array('Stand', 'Bitte wählen Sie den aktuellen Bearbeitungsstand.');
$GLOBALS['TL_LANG']['tl_task']['comment']     = array('Kommentar', 'Bitte schreiben Sie einen kurzen Kommentar.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_task']['date']       = 'Datum';
$GLOBALS['TL_LANG']['tl_task']['assignedTo'] = 'Bearbeiter';
$GLOBALS['TL_LANG']['tl_task']['noTasks']    = 'Momentan sind keine Aufgaben vorhanden.';
$GLOBALS['TL_LANG']['tl_task']['delConfirm'] = 'Soll die Aufgabe ID %s wirklich gelöscht werden?';
$GLOBALS['TL_LANG']['tl_task']['message']    = "\n\n---\n\nDiese Aufgabe wurde Ihnen von %s übertragen.\n";
$GLOBALS['TL_LANG']['tl_task']['history']    = 'Bearbeitungshistorie';


/**
 * Status
 */
$GLOBALS['TL_LANG']['tl_task_status']['created']    = 'Erstellt';
$GLOBALS['TL_LANG']['tl_task_status']['inProcess']  = 'In Bearbeitung';
$GLOBALS['TL_LANG']['tl_task_status']['completed']  = 'Fertig';
$GLOBALS['TL_LANG']['tl_task_status']['forwarded']  = 'Weitergeleitet';
$GLOBALS['TL_LANG']['tl_task_status']['declined']   = 'Abgelehnt';


/**
 * Submit buttons
 */
$GLOBALS['TL_LANG']['tl_task']['createSubmit'] = 'Die Aufgabe erstellen';
$GLOBALS['TL_LANG']['tl_task']['editSubmit']   = 'Die Aufgabe aktualisieren';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_task']['new']    = array('Neue Aufgabe', 'Eine neue Aufgabe erstellen');
$GLOBALS['TL_LANG']['tl_task']['edit']   = array('Aufgabe bearbeiten', 'Aufgabe ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_task']['delete'] = array('Aufgabe löschen', 'Aufgabe ID %s löschen');

?>