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
 * @package    TaskCenter
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleTasks
 *
 * Back end module "tasks".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleTasks extends \BackendModule
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_tasks';

	/**
	 * Save input
	 * @var boolean
	 */
	protected $blnSave = true;

	/**
	 * Advanced mode
	 * @var boolean
	 */
	protected $blnAdvanced = true;


	/**
	 * Generate the module
	 * @return void
	 */
	protected function compile()
	{
		$this->import('BackendUser', 'User');
		$this->loadLanguageFile('tl_task');

		// Check the request token (see #4007)
		if (isset($_GET['act']))
		{
			$this->import('RequestToken');

			if (!isset($_GET['rt']) || !$this->RequestToken->validate($this->Input->get('rt')))
			{
				$this->Session->set('INVALID_TOKEN_URL', $this->Environment->request);
				$this->redirect('contao/confirm.php');
			}
		}

		// Dispatch
		switch ($this->Input->get('act'))
		{
			case 'create':
				$this->createTask();
				break;

			case 'edit':
				$this->editTask();
				break;

			case 'delete':
				$this->deleteTask();
				break;

			default:
				$this->showAllTasks();
				break;
		}

		$this->Template->request = ampersand($this->Environment->request, true);

		// Add the CSS and JavaScript files
		$GLOBALS['TL_CSS'][] = 'plugins/mootools/tablesort/css/tablesort.css';
		$GLOBALS['TL_MOOTOOLS'][] = '<script src="' . TL_PLUGINS_URL . 'plugins/mootools/tablesort/js/tablesort.js"></script>';
	}


	/**
	 * Show all tasks
	 * @return void
	 */
	protected function showAllTasks()
	{
		$this->Template->tasks = array();

		// Clean up
		$this->Database->execute("DELETE FROM tl_task WHERE tstamp=0");
		$this->Database->execute("DELETE FROM tl_task_status WHERE tstamp=0");
		$this->Database->execute("DELETE FROM tl_task_status WHERE pid NOT IN(SELECT id FROM tl_task)");

		// Set default variables
		$this->Template->apply = $GLOBALS['TL_LANG']['MSC']['apply'];
		$this->Template->noTasks = $GLOBALS['TL_LANG']['tl_task']['noTasks'];
		$this->Template->createTitle = $GLOBALS['TL_LANG']['tl_task']['new'][1];
		$this->Template->createLabel = $GLOBALS['TL_LANG']['tl_task']['new'][0];
		$this->Template->editLabel = $GLOBALS['TL_LANG']['tl_task']['edit'][0];
		$this->Template->deleteLabel = $GLOBALS['TL_LANG']['tl_task']['delete'][0];

		$this->Template->thTitle = $GLOBALS['TL_LANG']['tl_task']['title'][0];
		$this->Template->thAssignedTo = $GLOBALS['TL_LANG']['tl_task']['assignedTo'];
		$this->Template->thStatus = $GLOBALS['TL_LANG']['tl_task']['status'][0];
		$this->Template->thProgress = $GLOBALS['TL_LANG']['tl_task']['progress'][0];
		$this->Template->thDeadline = $GLOBALS['TL_LANG']['tl_task']['deadline'][0];

		$this->Template->createHref = $this->addToUrl('act=create');

		// Get task object
		if (($objTask = $this->getTaskObject()) != true)
		{
			return;
		}

		$count = -1;
		$time = time();
		$max = ($objTask->numRows - 1);
		$arrTasks = array();

		// List tasks
		while ($objTask->next())
		{
			$trClass = 'row_' . ++$count . (($count == 0) ? ' row_first' : '') . (($count >= $max) ? ' row_last' : '') . (($count % 2 == 0) ? ' odd' : ' even');
			$tdClass = '';

			// Completed
			if ($objTask->status == 'completed')
			{
				$tdClass .= ' completed';
			}

			// Due
			elseif ($objTask->deadline < $time)
			{
				$tdClass .= ' due';
			}

			$deleteHref = '';
			$deleteTitle = '';
			$deleteIcon = TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/delete_.gif';
			$deleteConfirm = '';

			// Check delete permissions
			if ($this->User->isAdmin || $this->User->id == $objTask->createdBy)
			{
				$deleteHref = $this->addToUrl('act=delete&amp;id=' . $objTask->id);
				$deleteTitle = sprintf($GLOBALS['TL_LANG']['tl_task']['delete'][1], $objTask->id);
				$deleteIcon = TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/delete.gif';
				$deleteConfirm = sprintf($GLOBALS['TL_LANG']['tl_task']['delConfirm'], $objTask->id);
			}

			$arrTasks[] = array
			(
				'id' => $objTask->id,
				'user' => $objTask->name,
				'title' => $objTask->title,
				'progress' => $objTask->progress,
				'deadline' => $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objTask->deadline),
				'status' => $GLOBALS['TL_LANG']['tl_task_status'][$objTask->status] ?: $objTask->status,
				'creator' => sprintf($GLOBALS['TL_LANG']['tl_task']['createdBy'], $objTask->creator),
				'editHref' => $this->addToUrl('act=edit&amp;id=' . $objTask->id),
				'editTitle' => sprintf($GLOBALS['TL_LANG']['tl_task']['edit'][1], $objTask->id),
				'editIcon' => TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/edit.gif',
				'deleteHref' => $deleteHref,
				'deleteTitle' => $deleteTitle,
				'deleteIcon' => $deleteIcon,
				'deleteConfirm' => $deleteConfirm,
				'trClass' => $trClass,
				'tdClass' => $tdClass
			);
		}

		$this->Template->tasks = $arrTasks;
	}


	/**
	 * Create a task
	 * @return void
	 */
	protected function createTask()
	{
		$this->Template = new \BackendTemplate('be_task_create');
		$fs = $this->Session->get('fieldset_states');

		$this->Template->titleClass = (isset($fs['tl_tasks']['title_legend']) && !$fs['tl_tasks']['title_legend']) ? ' collapsed' : '';
		$this->Template->assignClass = (isset($fs['tl_tasks']['assign_legend']) && !$fs['tl_tasks']['assign_legend']) ? ' collapsed' : '';
		$this->Template->statusClass = (isset($fs['tl_tasks']['status_legend']) && !$fs['tl_tasks']['status_legend']) ? ' collapsed' : '';
		$this->Template->historyClass = (isset($fs['tl_tasks']['history_legend']) && !$fs['tl_tasks']['history_legend']) ? ' collapsed' : '';

		$this->Template->title = $this->getTitleWidget();
		$this->Template->deadline = $this->getDeadlineWidget();
		$this->Template->assignedTo = $this->getAssignedToWidget();
		$this->Template->notify = $this->getNotifyWidget();
		$this->Template->comment = $this->getCommentWidget();

		$this->Template->goBack = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->Template->headline = $GLOBALS['TL_LANG']['tl_task']['new'][1];
		$this->Template->submit = $GLOBALS['TL_LANG']['tl_task']['createSubmit'];
		$this->Template->titleLabel = $GLOBALS['TL_LANG']['tl_task']['title'][0];
		$this->Template->assignLabel = $GLOBALS['TL_LANG']['tl_task']['assignedTo'];
		$this->Template->statusLabel = $GLOBALS['TL_LANG']['tl_task']['status'][0];

		// Create task
		if ($this->Input->post('FORM_SUBMIT') == 'tl_tasks' && $this->blnSave)
		{
			$time = time();
			$deadline = new \Date($this->Template->deadline->value, $GLOBALS['TL_CONFIG']['dateFormat']);

			// Insert task
			$arrSet = array
			(
				'tstamp' => $time,
				'createdBy' => $this->User->id,
				'title' => $this->Template->title->value,
				'deadline' => $deadline->dayBegin
			);

			$objTask = $this->Database->prepare("INSERT INTO tl_task %s")->set($arrSet)->execute();
			$insertId = $objTask->insertId;

			// Insert status
			$arrSet = array
			(
				'pid' => $insertId,
				'tstamp' => $time,
				'assignedTo' => $this->Template->assignedTo->value,
				'comment' => trim($this->Template->comment->value),
				'status' => 'created',
				'progress' => 0
			);

			$this->Database->prepare("INSERT INTO tl_task_status %s")->set($arrSet)->execute();

			// Notify user
			if ($this->Input->post('notify'))
			{
				$objUser = $this->Database->prepare("SELECT email FROM tl_user WHERE id=?")
										  ->limit(1)
										  ->execute($this->Template->assignedTo->value);

				if ($objUser->numRows)
				{
					$objEmail = new \Email();

					$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
					$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
					$objEmail->subject = $this->Template->title->value;

					$objEmail->text = trim($this->Template->comment->value);
					$objEmail->text .= sprintf($GLOBALS['TL_LANG']['tl_task']['message'], $this->User->name, $this->Environment->base . 'contao/main.php?do=tasks&act=edit&id=' . $insertId);

					$objEmail->sendTo($objUser->email);
				}
			}

			// Go back
			$this->redirect('contao/main.php?do=tasks');
		}
	}


	/**
	 * Edit a task
	 * @return void
	 */
	protected function editTask()
	{
		$this->Template = new \BackendTemplate('be_task_edit');
		$fs = $this->Session->get('fieldset_states');

		$this->Template->titleClass = (isset($fs['tl_tasks']['title_legend']) && !$fs['tl_tasks']['title_legend']) ? ' collapsed' : '';
		$this->Template->assignClass = (isset($fs['tl_tasks']['assign_legend']) && !$fs['tl_tasks']['assign_legend']) ? ' collapsed' : '';
		$this->Template->statusClass = (isset($fs['tl_tasks']['status_legend']) && !$fs['tl_tasks']['status_legend']) ? ' collapsed' : '';
		$this->Template->historyClass = (isset($fs['tl_tasks']['history_legend']) && !$fs['tl_tasks']['history_legend']) ? ' collapsed' : '';

		$this->Template->goBack = $GLOBALS['TL_LANG']['MSC']['goBack'];
		$this->Template->headline = sprintf($GLOBALS['TL_LANG']['tl_task']['edit'][1], $this->Input->get('id'));

		$objTask = $this->Database->prepare("SELECT *, (SELECT name FROM tl_user u WHERE u.id=t.createdBy) AS creator FROM tl_task t WHERE id=?")
								  ->limit(1)
								  ->execute($this->Input->get('id'));

		if ($objTask->numRows < 1)
		{
			$this->log('Invalid task ID "' . $this->Input->get('id') . '"', 'ModuleTask editTask()', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Check if the user is allowed to edit the task
		if (!$this->User->isAdmin && $objTask->createdBy != $this->User->id)
		{
			$this->log('Not enough permissions to edit task ID "' . $this->Input->get('id') . '"', 'ModuleTask editTask()', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Advanced options
		$this->blnAdvanced = ($this->User->isAdmin || $objTask->createdBy == $this->User->id);
		$this->Template->advanced = $this->blnAdvanced;

		$this->Template->title = $this->blnAdvanced ? $this->getTitleWidget($objTask->title) : $objTask->title;
		$this->Template->deadline = $this->blnAdvanced ? $this->getDeadlineWidget($this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objTask->deadline)) : $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objTask->deadline);

		$arrHistory = array();

		// Get the status
		$objStatus = $this->Database->prepare("SELECT *, (SELECT name FROM tl_user u WHERE u.id=s.assignedTo) AS name FROM tl_task_status s WHERE pid=? ORDER BY tstamp")
									->execute($this->Input->get('id'));

		while($objStatus->next())
		{
			$arrHistory[] = array
			(
				'creator' => $objTask->creator,
				'date' => $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objStatus->tstamp),
				'status' => $GLOBALS['TL_LANG']['tl_task_status'][$objStatus->status] ?: $objStatus->status,
				'comment' => (($objStatus->comment != '') ? nl2br_html5($objStatus->comment) : '&nbsp;'),
				'assignedTo' => $objStatus->assignedTo,
				'progress' => $objStatus->progress,
				'class' => $objStatus->status,
				'name' => $objStatus->name
			);
		}

		$this->Template->assignedTo = $this->getAssignedToWidget($objStatus->assignedTo);
		$this->Template->notify = $this->getNotifyWidget();
		$this->Template->status = $this->getStatusWidget($objStatus->status, $objStatus->progress);
		$this->Template->progress = $this->getProgressWidget($objStatus->progress);
		$this->Template->comment = $this->getCommentWidget();

		// Update task
		if ($this->Input->post('FORM_SUBMIT') == 'tl_tasks' && $this->blnSave)
		{
			// Update task
			if ($this->blnAdvanced)
			{
				$deadline = new \Date($this->Template->deadline->value, $GLOBALS['TL_CONFIG']['dateFormat']);

				$this->Database->prepare("UPDATE tl_task SET title=?, deadline=? WHERE id=?")
							   ->execute($this->Template->title->value, $deadline->dayBegin, $this->Input->get('id'));
			}

			// Insert status
			$arrSet = array
			(
				'pid' => $this->Input->get('id'),
				'tstamp' => time(),
				'assignedTo' => $this->Template->assignedTo->value,
				'status' => $this->Template->status->value,
				'progress' => (($this->Template->status->value == 'completed') ? 100 : $this->Template->progress->value),
				'comment' => trim($this->Template->comment->value)
			);

			$this->Database->prepare("INSERT INTO tl_task_status %s")->set($arrSet)->execute();

			// Notify user
			if ($this->Input->post('notify'))
			{
				$objUser = $this->Database->prepare("SELECT email FROM tl_user WHERE id=?")
										  ->limit(1)
										  ->execute($this->Template->assignedTo->value);

				if ($objUser->numRows)
				{
					$objEmail = new \Email();

					$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
					$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
					$objEmail->subject = $objTask->title;

					$objEmail->text = trim($this->Template->comment->value);
					$objEmail->text .= sprintf($GLOBALS['TL_LANG']['tl_task']['message'], $this->User->name, $this->Environment->base . 'contao/main.php?do=tasks&act=edit&id=' . $objTask->id);

					$objEmail->sendTo($objUser->email);
				}
			}

			// Go back
			$this->redirect('contao/main.php?do=tasks');
		}

		$this->Template->history = $arrHistory;
		$this->Template->historyLabel = $GLOBALS['TL_LANG']['tl_task']['history'];
		$this->Template->deadlineLabel = $GLOBALS['TL_LANG']['tl_task']['deadline'][0];
		$this->Template->dateLabel = $GLOBALS['TL_LANG']['tl_task']['date'];
		$this->Template->assignedToLabel = $GLOBALS['TL_LANG']['tl_task']['assignedTo'];
		$this->Template->createdByLabel = $GLOBALS['TL_LANG']['tl_task']['creator'];
		$this->Template->statusLabel = $GLOBALS['TL_LANG']['tl_task']['status'][0];
		$this->Template->progressLabel = $GLOBALS['TL_LANG']['tl_task']['progress'][0];
		$this->Template->submit = $GLOBALS['TL_LANG']['tl_task']['editSubmit'];
		$this->Template->titleLabel = $GLOBALS['TL_LANG']['tl_task']['title'][0];
		$this->Template->assignLabel = $GLOBALS['TL_LANG']['tl_task']['assignedTo'];
	}


	/**
	 * Delete a task
	 * @return void
	 */
	protected function deleteTask()
	{
		$objTask = $this->Database->prepare("SELECT * FROM tl_task WHERE id=?")
								  ->limit(1)
								  ->execute($this->Input->get('id'));

		if ($objTask->numRows < 1)
		{
			$this->log('Invalid task ID "' . $this->Input->get('id') . '"', 'ModuleTask deleteTask()', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Check if the user is allowed to delete the task
		if (!$this->User->isAdmin && $objTask->createdBy != $this->User->id)
		{
			$this->log('Not enough permissions to delete task ID "' . $this->Input->get('id') . '"', 'ModuleTask deleteTask()', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$affected = 1;
		$data = array();
		$data['tl_task'][] = $objTask->row();

		// Get status records
		$objStatus = $this->Database->prepare("SELECT * FROM tl_task_status WHERE pid=? ORDER BY tstamp")
									->execute($this->Input->get('id'));

		while ($objStatus->next())
		{
			$data['tl_task_status'][] = $objStatus->row();
			++$affected;
		}

		$objUndoStmt = $this->Database->prepare("INSERT INTO tl_undo (pid, tstamp, fromTable, query, affectedRows, data) VALUES (?, ?, ?, ?, ?, ?)")
									  ->execute($this->User->id, time(), 'tl_task', 'DELETE FROM tl_task WHERE id= ' . $this->Input->get('id'), $affected, serialize($data));

		// Delete data and add a log entry
		if ($objUndoStmt->affectedRows)
		{
			$this->Database->prepare("DELETE FROM tl_task WHERE id=?")->execute($this->Input->get('id'));
			$this->Database->prepare("DELETE FROM tl_task_status WHERE pid=?")->execute($this->Input->get('id'));

			$this->log('DELETE FROM tl_task WHERE id=' . $this->Input->get('id'), 'ModuleTask deleteTask()', TL_GENERAL);
		}

		// Go back
		$this->redirect($this->getReferer());
	}


	/**
	 * Select all tasks from the DB and return the result object
	 * @return \Database_Result
	 */
	protected function getTaskObject()
	{
		$where = array();
		$value = array();

		$session = $this->Session->getData();
		$query = "SELECT *, t.id AS id, (SELECT name FROM tl_user u WHERE u.id=s.assignedTo) AS name, (SELECT name FROM tl_user u WHERE u.id=t.createdBy) AS creator FROM tl_task t LEFT JOIN tl_task_status s ON t.id=s.pid AND s.tstamp=(SELECT MAX(tstamp) FROM tl_task_status ts WHERE ts.pid=t.id)";

		// Do not show all tasks if the user is not an administrator
		if (!$this->User->isAdmin)
		{
			$where[] = "(t.createdBy=? OR s.assignedTo=?)";
			$value[] = $this->User->id;
			$value[] = $this->User->id;
		}

		// Set filter
		if ($this->Input->post('FORM_SUBMIT') == 'tl_filters')
		{
			// Search
			$session['search']['tl_task']['value'] = '';
			$session['search']['tl_task']['field'] = $this->Input->post('tl_field', true);

			// Make sure the regular expression is valid
			if ($this->Input->postRaw('tl_value') != '')
			{
				try
				{
					$this->Database->prepare("SELECT * FROM tl_task t LEFT JOIN tl_task_status s ON t.id=s.pid AND s.tstamp=(SELECT MAX(tstamp) FROM tl_task_status ts WHERE ts.pid=t.id) WHERE " . $this->Input->post('tl_field', true) . " REGEXP ?")
								   ->limit(1)
								   ->execute($this->Input->postRaw('tl_value'));

					$session['search']['tl_task']['value'] = $this->Input->postRaw('tl_value');
				}
				catch (\Exception $e) {}
			}

			// Filter
			$session['filter']['tl_task']['assignedTo'] = $this->Input->post('assignedTo');
			$session['filter']['tl_task']['deadline'] = $this->Input->post('deadline');

			$this->Session->setData($session);
			$this->reload();
		}

		// Add search value to query
		if ($session['search']['tl_task']['value'] != '')
		{
			$where[] = "CAST(" . $session['search']['tl_task']['field'] . " AS CHAR) REGEXP ?";
			$value[] = $session['search']['tl_task']['value'];

			$this->Template->searchClass = ' active';
		}

		// Search options
		$fields = array('title', 'status', 'progress');
		$options = '';

		foreach ($fields as $field)
		{
			$options .= sprintf('<option value="%s"%s>%s</option>', $field, (($field == $session['search']['tl_task']['field']) ? ' selected="selected"' : ''), (is_array($GLOBALS['TL_LANG']['tl_task'][$field]) ? $GLOBALS['TL_LANG']['tl_task'][$field][0] : $GLOBALS['TL_LANG']['tl_task'][$field]));
		}

		$this->Template->searchOptions = $options;
		$this->Template->keywords = specialchars($session['search']['tl_task']['value']);
		$this->Template->search = specialchars($GLOBALS['TL_LANG']['MSC']['search']);

		// Add deadline value to query
		if ($session['filter']['tl_task']['deadline'] != '')
		{
			$objDate = new \Date($session['filter']['tl_task']['deadline']);

			$where[] = "t.deadline BETWEEN ? AND ?";
			$value[] = $objDate->dayBegin;
			$value[] = $objDate->dayEnd;

			$this->Template->deadlineClass = ' active';
		}

		// Add assignedTo value to query
		if ($session['filter']['tl_task']['assignedTo'] != '')
		{
			$where[] = "s.assignedTo=?";
			$value[] = $session['filter']['tl_task']['assignedTo'];

			$this->Template->assignedToClass = ' active';
		}

		// Filter options
		$objFilter = $this->Database->prepare("SELECT t.deadline, s.assignedTo, (SELECT name FROM tl_user u WHERE u.id=s.assignedTo) AS name FROM tl_task t LEFT JOIN tl_task_status s ON t.id=s.pid AND s.tstamp=(SELECT MAX(tstamp) FROM tl_task_status ts WHERE ts.pid=t.id) ORDER BY deadline")
									->execute($value);

		$deadline = array();
		$assigned = array();

		while ($objFilter->next())
		{
			$deadline[$objFilter->deadline] = sprintf('<option value="%s"%s>%s</option>', $objFilter->deadline, (($objFilter->deadline == $session['filter']['tl_task']['deadline']) ? ' selected="selected"' : ''), $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objFilter->deadline));
			$assigned[$objFilter->assignedTo] = sprintf('<option value="%s"%s>%s</option>', $objFilter->assignedTo, (($objFilter->assignedTo == $session['filter']['tl_task']['assignedTo']) ? ' selected="selected"' : ''), $objFilter->name);
		}

		$this->Template->deadlineOptions = implode('', $deadline);
		$this->Template->assignedOptions = implode('', $assigned);
		$this->Template->filter = specialchars($GLOBALS['TL_LANG']['MSC']['filter']);

		// Where
		if (!empty($where))
		{
			$query .= " WHERE " . implode(' AND ', $where);
		}

		// Order by
		$query .= " ORDER BY (assignedTo=?) DESC, (status!='completed') DESC, deadline";
		$value[] = $this->User->id;

		// Execute query
		$objTask = $this->Database->prepare($query)->execute($value);

		if ($objTask->numRows < 1)
		{
			return null;
		}

		return $objTask;
	}


	/**
	 * Return the title widget as object
	 * @param mixed
	 * @return \Widget
	 */
	protected function getTitleWidget($value=null)
	{
		$widget = new \TextField();

		$widget->id = 'title';
		$widget->name = 'title';
		$widget->mandatory = true;
		$widget->decodeEntities = true;
		$widget->value = $value;
		$widget->autofocus = true;

		$widget->label = $GLOBALS['TL_LANG']['tl_task']['title'][0];

		if ($GLOBALS['TL_CONFIG']['showHelp'] && $GLOBALS['TL_LANG']['tl_task']['title'][1] != '')
		{
			$widget->help = $GLOBALS['TL_LANG']['tl_task']['title'][1];
		}

		// Valiate input
		if ($this->Input->post('FORM_SUBMIT') == 'tl_tasks')
		{
			$widget->validate();

			if ($widget->hasErrors())
			{
				$this->blnSave = false;
			}
		}

		return $widget;
	}


	/**
	 * Return the assignedTo widget as object
	 * @param mixed
	 * @return \Widget
	 */
	protected function getAssignedToWidget($value=null)
	{
		$widget = new \SelectMenu();

		$widget->id = 'assignedTo';
		$widget->name = 'assignedTo';
		$widget->mandatory = true;
		$widget->value = $value;

		$widget->label = $GLOBALS['TL_LANG']['tl_task']['assignTo'][0];

		if ($GLOBALS['TL_CONFIG']['showHelp'] && $GLOBALS['TL_LANG']['tl_task']['assignTo'][1] != '')
		{
			$widget->help = $GLOBALS['TL_LANG']['tl_task']['assignTo'][1];
		}

		$time = time();
		$arrOptions = array();

		// Get all active users
		$objUser = $this->Database->execute("SELECT id, name, admin, groups FROM tl_user WHERE disable!=1 AND (start='' OR start<$time) AND (stop='' OR stop>$time)");

		while ($objUser->next())
		{
			// If the user is not an admin, show only users of his group
			if (!$this->User->isAdmin && !$objUser->admin)
			{
				$groups = deserialize($objUser->groups, true);
				$intersect = array_intersect($this->User->groups, $groups);

				if (!is_array($intersect) || empty($intersect))
				{
					continue;
				}
			}

			$arrOptions[] = array('value'=>$objUser->id, 'label'=>$objUser->name);
		}

		$widget->options = $arrOptions;

		// Valiate input
		if ($this->Input->post('FORM_SUBMIT') == 'tl_tasks')
		{
			$widget->validate();

			if ($widget->hasErrors())
			{
				$this->blnSave = false;
			}
		}

		return $widget;
	}


	/**
	 * Return the deadline widget as object
	 * @param mixed
	 * @return \Widget
	 */
	protected function getDeadlineWidget($value=null)
	{
		$widget = new \TextField();

		$widget->id = 'deadline';
		$widget->name = 'deadline';
		$widget->mandatory = true;
		$widget->rgxp = 'date';
		$widget->value = $value;

		$widget->label = $GLOBALS['TL_LANG']['tl_task']['deadline'][0];

		if ($GLOBALS['TL_CONFIG']['showHelp'] && $GLOBALS['TL_LANG']['tl_task']['deadline'][1] != '')
		{
			$widget->help = $GLOBALS['TL_LANG']['tl_task']['deadline'][1];
		}

		// Valiate input
		if ($this->Input->post('FORM_SUBMIT') == 'tl_tasks')
		{
			$widget->validate();

			if ($widget->hasErrors())
			{
				$this->blnSave = false;
			}
		}

		return $widget;
	}


	/**
	 * Return the status widget as object
	 * @param mixed
	 * @param integer
	 * @return \Widget
	 */
	protected function getStatusWidget($value=null, $progress=null)
	{
		$widget = new \SelectMenu();

		$widget->id = 'status';
		$widget->name = 'status';
		$widget->mandatory = true;
		$widget->value = $value;

		$widget->label = $GLOBALS['TL_LANG']['tl_task']['status'][0];

		if ($GLOBALS['TL_CONFIG']['showHelp'] && $GLOBALS['TL_LANG']['tl_task']['status'][1] != '')
		{
			$widget->help = $GLOBALS['TL_LANG']['tl_task']['status'][1];
		}

		$arrOptions = array();

		// Get all active users
		foreach ($GLOBALS['TL_LANG']['tl_task_status'] as $k=>$v)
		{
			if ($k != 'created' || ($this->blnAdvanced && !$progress) || $value === null)
			{
				$arrOptions[] = array('value'=>$k, 'label'=>$v);
			}
		}

		$widget->options = $arrOptions;

		// Valiate input
		if ($this->Input->post('FORM_SUBMIT') == 'tl_tasks')
		{
			$widget->validate();

			if ($widget->hasErrors())
			{
				$this->blnSave = false;
			}
		}

		return $widget;
	}


	/**
	 * Return the progress widget as object
	 * @param mixed
	 * @return \Widget
	 */
	protected function getProgressWidget($value=null)
	{
		$widget = new \SelectMenu();

		$widget->id = 'progress';
		$widget->name = 'progress';
		$widget->mandatory = true;
		$widget->value = $value;

		$widget->label = $GLOBALS['TL_LANG']['tl_task']['progress'][0];

		if ($GLOBALS['TL_CONFIG']['showHelp'] && $GLOBALS['TL_LANG']['tl_task']['progress'][1] != '')
		{
			$widget->help = $GLOBALS['TL_LANG']['tl_task']['progress'][1];
		}

		$arrOptions = array();
		$arrProgress = array(0,10,20,30,40,50,60,70,80,90,100);

		// Get all active users
		foreach ($arrProgress as $v)
		{
			$arrOptions[] = array('value'=>$v, 'label'=>$v . '%');
		}

		$widget->options = $arrOptions;

		// Valiate input
		if ($this->Input->post('FORM_SUBMIT') == 'tl_tasks')
		{
			$widget->validate();

			if ($widget->hasErrors())
			{
				$this->blnSave = false;
			}
		}

		return $widget;
	}


	/**
	 * Return the comment widget as object
	 * @param mixed
	 * @return \Widget
	 */
	protected function getCommentWidget($value=null)
	{
		$widget = new \TextArea();

		$widget->id = 'comment';
		$widget->name = 'comment';
		$widget->mandatory = true;
		$widget->decodeEntities = true;
		$widget->style = 'height:120px;';
		$widget->value = $value;

		$widget->label = $GLOBALS['TL_LANG']['tl_task']['comment'][0];

		if ($GLOBALS['TL_CONFIG']['showHelp'] && $GLOBALS['TL_LANG']['tl_task']['comment'][1] != '')
		{
			$widget->help = $GLOBALS['TL_LANG']['tl_task']['comment'][1];
		}

		// Valiate input
		if ($this->Input->post('FORM_SUBMIT') == 'tl_tasks')
		{
			$widget->validate();

			if ($widget->hasErrors())
			{
				$this->blnSave = false;
			}
		}

		return $widget;
	}


	/**
	 * Return the notify widget as object
	 * @return \Widget
	 */
	protected function getNotifyWidget()
	{
		$widget = new \CheckBox();

		$widget->id = 'notify';
		$widget->name = 'notify';

		$widget->options = array(array('value'=>1, 'label'=>$GLOBALS['TL_LANG']['tl_task']['notify'][0]));

		if ($GLOBALS['TL_CONFIG']['showHelp'] && $GLOBALS['TL_LANG']['tl_task']['notify'][1] != '')
		{
			$widget->help = $GLOBALS['TL_LANG']['tl_task']['notify'][1];
		}

		return $widget;
	}
}
