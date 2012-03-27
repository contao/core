<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Language
 * @license    LGPL
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_task']['title']       = array('Titel', 'Bitte geben Sie den Titel der Aufgabe ein.');
$GLOBALS['TL_LANG']['tl_task']['deadline']    = array('Deadline', 'Hier können Sie die Deadline der Aufgabe eingeben.');
$GLOBALS['TL_LANG']['tl_task']['assignTo']    = array('Übertragen an', 'Hier können Sie die Aufgabe einem Benutzer zuweisen.');
$GLOBALS['TL_LANG']['tl_task']['notify']      = array('Benutzer benachrichtigen', 'Den Benutzer per E-Mail benachrichtigen.');
$GLOBALS['TL_LANG']['tl_task']['status']      = array('Status', 'Hier können Sie den Bearbeitungsstatus auswählen.');
$GLOBALS['TL_LANG']['tl_task']['progress']    = array('Stand', 'Hier können Sie den Bearbeitungsstand in Prozent festlegen.');
$GLOBALS['TL_LANG']['tl_task']['comment']     = array('Kommentar', 'Hier können Sie einen Kommentar hinzufügen.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_task']['date']       = 'Datum';
$GLOBALS['TL_LANG']['tl_task']['assignedTo'] = 'Bearbeiter';
$GLOBALS['TL_LANG']['tl_task']['createdBy']  = 'erstellt von %s';
$GLOBALS['TL_LANG']['tl_task']['creator']    = 'Ersteller';
$GLOBALS['TL_LANG']['tl_task']['noTasks']    = 'Momentan sind keine Aufgaben vorhanden.';
$GLOBALS['TL_LANG']['tl_task']['delConfirm'] = 'Soll die Aufgabe ID %s wirklich gelöscht werden?';
$GLOBALS['TL_LANG']['tl_task']['message']    = "\n\n---\n\nDiese Aufgabe wurde Ihnen von %s übertragen.\n%s\n";
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
$GLOBALS['TL_LANG']['tl_task']['edit']   = array('Aufgabe bearbeiten', 'Die Aufgabe ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_task']['delete'] = array('Aufgabe löschen', 'Die Aufgabe ID %s löschen');
