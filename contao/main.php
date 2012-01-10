<?php

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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
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
	 * 1. Import the user
	 * 2. Call the parent constructor
	 * 3. Authenticate the user
	 * 4. Load the language files
	 * DO NOT CHANGE THIS ORDER!
	 */
	public function __construct()
	{
		// Redirect to the install tool
		if (!Config::getInstance()->isComplete())
		{
			$this->redirect('install.php');
		}

		$this->import('BackendUser', 'User');
		parent::__construct();

		$this->User->authenticate();

		// Password change required
		if ($this->User->pwChange)
		{
			$this->redirect('contao/password.php');
		}

		$this->loadLanguageFile('default');
		$this->loadLanguageFile('modules');
	}


	/**
	 * Run the controller and parse the login template
	 */
	public function run()
	{
		$this->Template = new BackendTemplate('be_main');
		$this->Template->main = '';

		// Ajax request
		if ($_POST && $this->Environment->isAjaxRequest)
		{
			$this->objAjax = new Ajax($this->Input->post('action'));
			$this->objAjax->executePreActions();
		}

		// Welcome screen
		if (!$this->Input->get('do') && !$this->Input->get('act'))
		{
			$this->Template->main .= $this->welcomeScreen();
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
	 * @return string
	 */
	protected function welcomeScreen()
	{
		$this->loadLanguageFile('explain');
		$objTemplate = new BackendTemplate('be_welcome');
		$arrMessages = array();

		// HOOK: add custom messages
		if (isset($GLOBALS['TL_HOOKS']['getSystemMessages']) && is_array($GLOBALS['TL_HOOKS']['getSystemMessages']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getSystemMessages'] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]();

				if ($strBuffer != '')
				{
					$arrMessages[] = $strBuffer;
				}
			}
		}

		$objTemplate->messages = $this->getMessages(false, true) . "\n" . implode("\n", $arrMessages);
		$objTemplate->arrGroups = $this->User->navigation(true);
 		$objTemplate->welcome = sprintf($GLOBALS['TL_LANG']['MSC']['welcomeTo'], $GLOBALS['TL_CONFIG']['websiteTitle']);
		$objTemplate->systemMessages = $GLOBALS['TL_LANG']['MSC']['systemMessages'];
		$objTemplate->shortcuts = $GLOBALS['TL_LANG']['MSC']['shortcuts'][0];
		$objTemplate->shortcutsLink = $GLOBALS['TL_LANG']['MSC']['shortcuts'][1];

		return $objTemplate->parse();
	}


	/**
	 * Output the template file
	 */
	protected function output()
	{
		// Default headline
		if ($this->Template->headline == '')
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
		$this->Template->skipNavigation = specialchars($GLOBALS['TL_LANG']['MSC']['skipNavigation']);
		$this->Template->request = ampersand($this->Environment->request);
		$this->Template->top = $GLOBALS['TL_LANG']['MSC']['backToTop'];
		$this->Template->modules = $this->User->navigation();
		$this->Template->home = $GLOBALS['TL_LANG']['MSC']['home'];
		$this->Template->backToTop = $GLOBALS['TL_LANG']['MSC']['backToTop'];
		$this->Template->expandNode = $GLOBALS['TL_LANG']['MSC']['expandNode'];
		$this->Template->collapseNode = $GLOBALS['TL_LANG']['MSC']['collapseNode'];
		$this->Template->loadingData = $GLOBALS['TL_LANG']['MSC']['loadingData'];
		$this->Template->coreOnlyMode = $GLOBALS['TL_LANG']['MSC']['coreOnlyMode'];
		$this->Template->isCoreOnlyMode = $GLOBALS['TL_CONFIG']['coreOnlyMode'];
		$this->Template->frontendFile = !$GLOBALS['TL_CONFIG']['rewriteURL'] ? 'index.php' : '';

		// Front end preview links
		if (CURRENT_ID != '')
		{
			// Pages
			if ($this->Input->get('do') == 'page')
			{
				$objPreview = $this->Database->prepare("SELECT id, alias, language FROM tl_page WHERE id=?")
											 ->limit(1)
											 ->execute(CURRENT_ID);

				if ($objPreview->numRows)
				{
					if ($GLOBALS['TL_CONFIG']['disableAlias'])
					{
						$this->Template->frontendFile .= '?id=' . $objPreview->id . ($GLOBALS['TL_CONFIG']['addLanguageToUrl'] ? '&amp;language=' . $objPreview->language :  '');
					}
					else
					{
						$this->Template->frontendFile .= ($GLOBALS['TL_CONFIG']['rewriteURL'] ? '' : '/') . ($GLOBALS['TL_CONFIG']['addLanguageToUrl'] ? $objPreview->language . '/' :  '') . (($objPreview->alias != '') ? $objPreview->alias : $objPreview->id) . $GLOBALS['TL_CONFIG']['urlSuffix'];
					}
				}
			}

			// Articles
			elseif ($this->Input->get('do') == 'article')
			{
				$objPreview = $this->Database->prepare("SELECT p.id AS pid, p.alias AS palias, p.language AS planguage, a.id AS aid, a.alias AS aalias, a.inColumn AS acolumn FROM tl_article a, tl_page p WHERE a.id=? AND a.pid=p.id")
											 ->limit(1)
											 ->execute(CURRENT_ID);

				if ($objPreview->numRows)
				{
					$strColumn = '';

					if ($objPreview->acolumn != 'main')
					{
						$strColumn = $objPreview->acolumn . ':';
					}

					if ($GLOBALS['TL_CONFIG']['disableAlias'])
					{
						$this->Template->frontendFile .= '?id=' . $objPreview->pid . ($GLOBALS['TL_CONFIG']['addLanguageToUrl'] ? '&amp;language=' . $objPreview->planguage :  '') . '&articles=' . $strColumn . $objPreview->aid;
					}
					else
					{
						$this->Template->frontendFile .= ($GLOBALS['TL_CONFIG']['rewriteURL'] ? '' : '/') . ($GLOBALS['TL_CONFIG']['addLanguageToUrl'] ? $objPreview->planguage . '/' :  '') . (($objPreview->palias != '') ? $objPreview->palias : $objPreview->pid) . '/articles/' . $strColumn . (($objPreview->aalias != '') ? $objPreview->aalias : $objPreview->aid) . $GLOBALS['TL_CONFIG']['urlSuffix'];
					}
				}
			}
		
			$this->Template->frontendFile = str_replace(array('?', '&', '='), array('%3F', '%26', '%3D'), $this->Template->frontendFile);
		}

		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objMain = new Main();
$objMain->run();

?>