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
$GLOBALS['TL_LANG']['tl_task']['title']       = array('Title', 'Please enter a title.');
$GLOBALS['TL_LANG']['tl_task']['assignTo']    = array('Assign to', 'Please assign the task to a user.');
$GLOBALS['TL_LANG']['tl_task']['notify']      = array('Notify user', 'Notify the user via e-mail.');
$GLOBALS['TL_LANG']['tl_task']['deadline']    = array('Deadline', 'Please enter the deadline for the task.');
$GLOBALS['TL_LANG']['tl_task']['status']      = array('Status', 'Please set the status of the task.');
$GLOBALS['TL_LANG']['tl_task']['progress']    = array('Progress', 'Please choose the current progress.');
$GLOBALS['TL_LANG']['tl_task']['comment']     = array('Comment', 'Please add a short comment.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_task']['date']       = 'Date';
$GLOBALS['TL_LANG']['tl_task']['assignedTo'] = 'Assigned to';
$GLOBALS['TL_LANG']['tl_task']['noTasks']    = 'Currently there are no tasks.';
$GLOBALS['TL_LANG']['tl_task']['delConfirm'] = 'Do you really want to remove task ID %s?';
$GLOBALS['TL_LANG']['tl_task']['message']    = "\n\n---\n\nThis task has been assigned by %s.\n";
$GLOBALS['TL_LANG']['tl_task']['history']    = 'Task history';


/**
 * Status
 */
$GLOBALS['TL_LANG']['tl_task_status']['created']    = 'Created';
$GLOBALS['TL_LANG']['tl_task_status']['inProcess']  = 'In process';
$GLOBALS['TL_LANG']['tl_task_status']['completed']  = 'Completed';
$GLOBALS['TL_LANG']['tl_task_status']['forwarded']  = 'Forwarded';
$GLOBALS['TL_LANG']['tl_task_status']['declined']   = 'Rejected';


/**
 * Submit buttons
 */
$GLOBALS['TL_LANG']['tl_task']['createSubmit'] = 'Create the task';
$GLOBALS['TL_LANG']['tl_task']['editSubmit']   = 'Update the task';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_task']['new']    = array('New task', 'Create a new task');
$GLOBALS['TL_LANG']['tl_task']['edit']   = array('Edit task', 'Edit task ID %s');
$GLOBALS['TL_LANG']['tl_task']['delete'] = array('Delete task', 'Delete task ID %s');

?>