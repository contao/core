<?php

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
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once('../system/initialize.php');


/**
 * Class Main
 *
 * Main back end controller.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class Main extends Backend
{

	/**
	 * Current Ajax object
	 * @var object
	 */
	protected $objAjax;


	/**
	 * Initialize the controller
	 * 
	 * 1. Import user
	 * 2. Call parent constructor
	 * 3. Authenticate user
	 * 4. Load language files
	 * DO NOT CHANGE THIS ORDER!
	 */
	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct();

		$this->User->authenticate();

		$this->loadLanguageFile('default');
		$this->loadLanguageFile('modules');
	}


	/**
	 * Run controller and parse the login template
	 */
	public function run()
	{
		$this->Template = new BackendTemplate('be_main');
		$this->Template->main = '';

		if ($this->Input->post('isAjax'))
		{
			$this->objAjax = new Ajax($this->Input->post('action'));
			$this->objAjax->executePreActions();
		}

		// Welcome screen
		if (!$this->Input->get('do') && !$this->Input->get('act'))
		{
			$this->welcomeScreen();
		}

		// Open module
		if ($this->Input->get('do'))
		{
			$this->Template->main .= $this->getBackendModule($this->Input->get('do'));
		}

		$this->output();
	}


	/**
	 * Add the welcome screen
	 */
	private function welcomeScreen()
	{
		$this->loadLanguageFile('explain');

		// Create template object
		$objTemplate = new BackendTemplate('be_welcome');

		$objTemplate->messages = false;
		$objTemplate->arrShortcuts = $GLOBALS['TL_LANG']['XPL']['shortcuts'];
		$objTemplate->welcome = sprintf($GLOBALS['TL_LANG']['MSC']['welcomeTo'], $GLOBALS['TL_CONFIG']['websiteTitle']);
		$objTemplate->systemMessages = $GLOBALS['TL_LANG']['MSC']['systemMessages'];

		// Check for latest version
		$objRequest = new Request();
		$objRequest->send('http://www.inetrobots.com/liveupdate/version.txt');

		// Notify user
		if (!$objRequest->hasError())
		{
			if (version_compare(VERSION . '.' . BUILD, $objRequest->response, '<'))
			{
				$this->Config->update("\$GLOBALS['TL_CONFIG']['latestVersion']", $objRequest->response);
				$GLOBALS['TL_CONFIG']['latestVersion'] = $objRequest->response;

				$objTemplate->update = sprintf($GLOBALS['TL_LANG']['MSC']['updateVersion'], $objRequest->response);
				$objTemplate->messages = true;
			}
		}

		// Check for tasks
		$tasksReg = 0;
		$tasksNew = 0;
		$tasksDue = 0;

		$objTask = $this->Database->prepare("SELECT t.deadline, s.status, s.assignedTo FROM tl_task t LEFT JOIN tl_task_status s ON t.id=s.pid AND s.tstamp=(SELECT MAX(tstamp) FROM tl_task_status ts WHERE ts.pid=t.id)" . (!$this->User->isAdmin ? " WHERE (t.createdBy=? OR s.assignedTo=?)" : ""))
								  ->execute($this->User->id, $this->User->id);

		if ($objTask->numRows)
		{
			$time = time();

			while ($objTask->next())
			{
				if ($objTask->status == 'completed')
				{
					continue;
				}
				if ($objTask->deadline <= $time)
				{
					++$tasksDue;
				}
				elseif ($objTask->status == 'created' && $objTask->assignedTo == $this->User->id)
				{
					++$tasksNew;
				}
				else
				{
					++$tasksReg;
				}
			}

			if ($tasksReg > 0)
			{
				$objTemplate->tasksCur = sprintf($GLOBALS['TL_LANG']['MSC']['tasksCur'], $tasksReg);
				$objTemplate->messages = true;
			}

			if ($tasksNew > 0)
			{
				$objTemplate->tasksNew = sprintf($GLOBALS['TL_LANG']['MSC']['tasksNew'], $tasksNew);
				$objTemplate->messages = true;
			}

			if ($tasksDue > 0)
			{
				$objTemplate->tasksDue = sprintf($GLOBALS['TL_LANG']['MSC']['tasksDue'], $tasksDue);
				$objTemplate->messages = true;
			}
		}

		// Modules
		$arrGroups = array();

		foreach ($GLOBALS['BE_MOD'] as $strGroup=>$arrModules)
		{
			foreach (array_keys($arrModules) as $strModule)
			{
				if ($strGroup == 'profile' || $this->User->hasAccess($strModule, 'modules'))
				{
					$arrGroups[$GLOBALS['TL_LANG']['MOD'][$strGroup]][$strModule] = array
					(
						'name' => $GLOBALS['TL_LANG']['MOD'][$strModule][0],
						'description' => $GLOBALS['TL_LANG']['MOD'][$strModule][1],
						'icon' => $arrModules[$strModule]['icon']
					);
				}
			}
		}

		$objTemplate->arrGroups = $arrGroups;
		$objTemplate->tasks = $GLOBALS['TL_LANG']['MOD']['tasks'][0];
		$objTemplate->script = $this->Environment->script;

		$this->Template->main .= $objTemplate->parse();
	}


	/**
	 * Output the template file
	 */
	private function output()
	{
		if (!strlen($this->Template->headline))
		{
			$this->Template->headline = $GLOBALS['TL_CONFIG']['websiteTitle'];
		}

		$this->Template->theme = $this->getTheme();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->account = $GLOBALS['TL_LANG']['MOD']['login'][0];
		$this->Template->preview = $GLOBALS['TL_LANG']['MSC']['fePreview'];
		$this->Template->pageOffset = $this->Input->cookie('BE_PAGE_OFFSET');
		$this->Template->logout = specialchars($GLOBALS['TL_LANG']['MSC']['logoutBT']);
		$this->Template->backendModules = $GLOBALS['TL_LANG']['MSC']['backendModules'];
		$this->Template->username = $GLOBALS['TL_LANG']['MSC']['user'] . ' ' . $GLOBALS['TL_USERNAME'];
		$this->Template->error = ($this->Input->get('act') == 'error') ? $GLOBALS['TL_LANG']['ERR']['general'] : '';
		$this->Template->isMac = preg_match('/mac/i', $this->Environment->httpUserAgent);
		$this->Template->skipNavigation = $GLOBALS['TL_LANG']['MSC']['skipNavigation'];
		$this->Template->request = ampersand($this->Environment->request);
		$this->Template->top = $GLOBALS['TL_LANG']['MSC']['backToTop'];
		$this->Template->modules = $this->User->navigation();

		$this->Template->frontendFile = 'index.php';

		// Preview pages
		if ($this->Input->get('do') == 'page' && strlen(CURRENT_ID)) 
		{
			$objPreview = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										 ->limit(1)
										 ->execute(CURRENT_ID);

			if ($objPreview->numRows)
			{
				if ($GLOBALS['TL_CONFIG']['disableAlias'])
				{
					$this->Template->frontendFile = 'index.php?id=' . $objPreview->id;
				}
				else
				{
					$this->Template->frontendFile = 'index.php/' . (strlen($objPreview->alias) ? $objPreview->alias : $objPreview->id) . $GLOBALS['TL_CONFIG']['urlSuffix'];
				}
			}
		}

		// Preview article
		if ($this->Input->get('do') == 'article' && strlen(CURRENT_ID)) 
		{
			$objPreview = $this->Database->prepare("SELECT p.id AS pid, p.alias AS palias, a.id AS aid, a.alias AS aalias FROM tl_article a, tl_page p WHERE a.id=? AND a.pid=p.id")
										 ->limit(1)
										 ->execute(CURRENT_ID);

			if ($objPreview->numRows)
			{
				if ($GLOBALS['TL_CONFIG']['disableAlias'])
				{
					$this->Template->frontendFile = 'index.php?id=' . $objPreview->pid . '&amp;articles=' . $objPreview->aid;
				}
				else
				{
					$this->Template->frontendFile = 'index.php/' . (strlen($objPreview->palias) ? $objPreview->palias : $objPreview->pid) . '/articles/' . (strlen($objPreview->aalias) ? $objPreview->aalias : $objPreview->aid) . $GLOBALS['TL_CONFIG']['urlSuffix'];
				}
			}
		}

		$this->Template->output();
	}
}


/**
 * Instantiate controller
 */
$objMain = new Main();
$objMain->run();

?>