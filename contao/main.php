<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once '../system/initialize.php';


/**
 * Class Main
 *
 * Main back end controller.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
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
			$objSession = $this->Database->prepare("SELECT su FROM tl_session WHERE sessionID=? AND pid=?")
										 ->execute(session_id(), $this->User->id);

			if (!$objSession->su)
			{
				$this->redirect('contao/password.php');
			}
		}

		// Front end redirect
		if (Input::get('do') == 'feRedirect')
		{
			$this->redirectToFrontendPage(Input::get('page'), Input::get('article'));
		}

		// Safe mode off
		if (Input::get('smo') && $this->User->isAdmin)
		{
			$this->Config->update("\$GLOBALS['TL_CONFIG']['coreOnlyMode']", false);
			$this->redirect($this->getReferer());
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
		if ($_POST && Environment::get('isAjaxRequest'))
		{
			$this->objAjax = new Ajax(Input::post('action'));
			$this->objAjax->executePreActions();
		}

		// Error
		if (Input::get('act') == 'error')
		{
			$this->Template->error = $GLOBALS['TL_LANG']['ERR']['general'];
			$this->Template->title = $GLOBALS['TL_LANG']['ERR']['general'];
		}
		// Welcome screen
		elseif (!Input::get('do') && !Input::get('act'))
		{
			$this->Template->main .= $this->welcomeScreen();
			$this->Template->title = $GLOBALS['TL_LANG']['MSC']['home'];
		}
		// Open a module
		elseif (Input::get('do'))
		{
			$this->Template->main .= $this->getBackendModule(Input::get('do'));
			$this->Template->title = $this->Template->headline;
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
		$objTemplate->messages = Message::generate(false, true);

		// HOOK: add custom messages
		if (isset($GLOBALS['TL_HOOKS']['getSystemMessages']) && is_array($GLOBALS['TL_HOOKS']['getSystemMessages']))
		{
			$arrMessages = array();

			foreach ($GLOBALS['TL_HOOKS']['getSystemMessages'] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]();

				if ($strBuffer != '')
				{
					$arrMessages[] = $strBuffer;
				}
			}

			if (!empty($arrMessages))
			{
				$objTemplate->messages .= "\n" . implode("\n", $arrMessages);
			}
		}

		$arrVersions = array();

		// Get the total number of versions
		$objTotal = $this->Database->prepare("SELECT COUNT(*) AS count FROM tl_version" . (!$this->User->isAdmin ? " WHERE userid=?" : ""))
								   ->execute($this->User->id);

		$intPage   = Input::get('vp') ?: 1;
		$intOffset = ($intPage - 1) * 30;
		$intLast   = ceil($objTotal->count / 30);

		// Validate the page number
		if ($intPage < 1 || $intPage > $intLast)
		{
			header('HTTP/1.1 404 Not Found');
		}

		// Create the pagination menu
		$objPagination = new Pagination($objTotal->count, 30, 7, 'vp', new \BackendTemplate('be_pagination'));
		$objTemplate->pagination = $objPagination->generate();

		// Get the versions
		$objVersions = $this->Database->prepare("SELECT pid, tstamp, version, fromTable, username, userid, description, editUrl FROM tl_version v" . (!$this->User->isAdmin ? " WHERE userid=?" : "") . " ORDER BY tstamp DESC, pid, version DESC")
									  ->limit(30, $intOffset)
									  ->execute($this->User->id);

		while ($objVersions->next())
		{
			$arrRow = $objVersions->row();

			// Add some parameters
			$arrRow['from'] = max(($objVersions->version - 1), 1); // see #4828
			$arrRow['to'] = $objVersions->version;
			$arrRow['date'] = date($GLOBALS['TL_CONFIG']['datimFormat'], $objVersions->tstamp);
			$arrRow['description'] = String::substr($arrRow['description'], 32);

			if ($arrRow['editUrl'] != '')
			{
				$arrRow['editUrl'] = preg_replace('/&(amp;)?rt=[a-f0-9]+/', '&amp;rt=' . REQUEST_TOKEN, ampersand($arrRow['editUrl']));
			}

			$arrVersions[] = $arrRow;
		}

		$intCount = -1;
		$arrVersions = array_values($arrVersions);

		// Add the "even" and "odd" classes
		foreach ($arrVersions as $k=>$v)
		{
			$arrVersions[$k]['class'] = (++$intCount%2 == 0) ? 'even' : 'odd';

			try
			{
				// Mark deleted versions (see #4336)
				$objDeleted = $this->Database->prepare("SELECT COUNT(*) AS count FROM " . $v['fromTable'] . " WHERE id=?")
											 ->execute($v['pid']);

				$arrVersions[$k]['deleted'] = ($objDeleted->count < 1);
			}
			catch (Exception $e)
			{
				// Probably a disabled module
				--$intCount;
				unset($arrVersions[$k]);
			}

		}

		$objTemplate->versions = $arrVersions;
		$objTemplate->welcome = sprintf($GLOBALS['TL_LANG']['MSC']['welcomeTo'], $GLOBALS['TL_CONFIG']['websiteTitle']);
		$objTemplate->showDifferences = specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MSC']['showDifferences']));
		$objTemplate->systemMessages = $GLOBALS['TL_LANG']['MSC']['systemMessages'];
		$objTemplate->shortcuts = $GLOBALS['TL_LANG']['MSC']['shortcuts'][0];
		$objTemplate->shortcutsLink = $GLOBALS['TL_LANG']['MSC']['shortcuts'][1];
		$objTemplate->editElement = specialchars($GLOBALS['TL_LANG']['MSC']['editElement']);

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

		// Default title
		if ($this->Template->title == '')
		{
			$this->Template->title = $this->Template->headline;
		}

		$this->Template->theme = $this->getTheme();
		$this->Template->base = Environment::get('base');
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = specialchars($this->Template->title);
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->account = $GLOBALS['TL_LANG']['MOD']['login'][1];
		$this->Template->preview = $GLOBALS['TL_LANG']['MSC']['fePreview'];
		$this->Template->previewTitle = specialchars($GLOBALS['TL_LANG']['MSC']['fePreviewTitle']);
		$this->Template->pageOffset = Input::cookie('BE_PAGE_OFFSET');
		$this->Template->logout = $GLOBALS['TL_LANG']['MSC']['logoutBT'];
		$this->Template->logoutTitle = specialchars($GLOBALS['TL_LANG']['MSC']['logoutBTTitle']);
		$this->Template->backendModules = $GLOBALS['TL_LANG']['MSC']['backendModules'];
		$this->Template->username = $GLOBALS['TL_LANG']['MSC']['user'] . ' ' . $GLOBALS['TL_USERNAME'];
		$this->Template->skipNavigation = specialchars($GLOBALS['TL_LANG']['MSC']['skipNavigation']);
		$this->Template->request = ampersand(Environment::get('request'));
		$this->Template->top = $GLOBALS['TL_LANG']['MSC']['backToTop'];
		$this->Template->modules = $this->User->navigation();
		$this->Template->home = $GLOBALS['TL_LANG']['MSC']['home'];
		$this->Template->homeTitle = $GLOBALS['TL_LANG']['MSC']['homeTitle'];
		$this->Template->backToTop = specialchars($GLOBALS['TL_LANG']['MSC']['backToTopTitle']);
		$this->Template->expandNode = $GLOBALS['TL_LANG']['MSC']['expandNode'];
		$this->Template->collapseNode = $GLOBALS['TL_LANG']['MSC']['collapseNode'];
		$this->Template->loadingData = $GLOBALS['TL_LANG']['MSC']['loadingData'];
		$this->Template->coreOnlyMode = $GLOBALS['TL_LANG']['MSC']['coreOnlyMode'];
		$this->Template->isCoreOnlyMode = $GLOBALS['TL_CONFIG']['coreOnlyMode'];
		$this->Template->loadFonts = $GLOBALS['TL_CONFIG']['loadGoogleFonts'];
		$this->Template->isAdmin = $this->User->isAdmin;
		$this->Template->coreOnlyOff = specialchars($GLOBALS['TL_LANG']['MSC']['coreOnlyOff']);
		$this->Template->coreOnlyHref = $this->addToUrl('smo=1');

		// Front end preview links
		if (CURRENT_ID != '')
		{
			// Pages
			if (Input::get('do') == 'page')
			{
				$this->Template->frontendFile = '?page=' . CURRENT_ID;
			}

			// Articles
			elseif (Input::get('do') == 'article')
			{
				$objArticle = ArticleModel::findByPk(CURRENT_ID);

				if ($objArticle !== null)
				{
					$this->Template->frontendFile = '?page=' . $objArticle->pid . '&amp;article=' . (($objArticle->inColumn != 'main') ? $objArticle->inColumn . ':' : '') . (($objArticle->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objArticle->alias : $objArticle->id);
				}
			}
		}

		$this->Template->output();
	}
}


/**
 * Instantiate the controller
 */
$objMain = new Main();
$objMain->run();
