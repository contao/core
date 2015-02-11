<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Main back end controller.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class BackendMain extends \Backend
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
		if (\Input::get('do') == 'feRedirect')
		{
			$this->redirectToFrontendPage(\Input::get('page'), \Input::get('article'));
		}

		// Convenience functions
		if ($this->User->isAdmin)
		{
			// Safe mode off
			if (\Input::get('smo'))
			{
				$this->import('Automator');
				$this->Automator->purgeInternalCache();
				\Config::persist('coreOnlyMode', false);
				$this->redirect($this->getReferer());
			}

			// Maintenance mode off
			if (\Input::get('mmo'))
			{
				\Config::persist('maintenanceMode', false);
				$this->redirect($this->getReferer());
			}

			// Build internal cache
			if (\Input::get('bic'))
			{
				$this->import('Automator');
				$this->Automator->generateInternalCache();
				$this->redirect($this->getReferer());
			}
		}

		\System::loadLanguageFile('default');
		\System::loadLanguageFile('modules');
	}


	/**
	 * Run the controller and parse the login template
	 */
	public function run()
	{
		/** @var \BackendTemplate|object $objTemplate */
		$objTemplate = new \BackendTemplate('be_main');
		$objTemplate->main = '';

		// Ajax request
		if ($_POST && \Environment::get('isAjaxRequest'))
		{
			$this->objAjax = new \Ajax(\Input::post('action'));
			$this->objAjax->executePreActions();
		}

		// Error
		if (\Input::get('act') == 'error')
		{
			$objTemplate->error = $GLOBALS['TL_LANG']['ERR']['general'];
			$objTemplate->title = $GLOBALS['TL_LANG']['ERR']['general'];
		}
		// Welcome screen
		elseif (!\Input::get('do') && !\Input::get('act'))
		{
			$objTemplate->main .= $this->welcomeScreen();
			$objTemplate->title = $GLOBALS['TL_LANG']['MSC']['home'];
		}
		// Open a module
		elseif (\Input::get('do'))
		{
			$objTemplate->main .= $this->getBackendModule(\Input::get('do'));
			$objTemplate->title = $objTemplate->headline;
		}

		// Default headline
		if ($objTemplate->headline == '')
		{
			$objTemplate->headline = \Config::get('websiteTitle');
		}

		// Default title
		if ($objTemplate->title == '')
		{
			$objTemplate->title = $objTemplate->headline;
		}

		// File picker reference
		if (\Input::get('popup') && \Input::get('act') != 'show' && (\Input::get('do') == 'page' || \Input::get('do') == 'files') && $this->Session->get('filePickerRef'))
		{
			$objTemplate->managerHref = $this->Session->get('filePickerRef');
			$objTemplate->manager = (strpos($this->Session->get('filePickerRef'), 'contao/page.php') !== false) ? $GLOBALS['TL_LANG']['MSC']['pagePickerHome'] : $GLOBALS['TL_LANG']['MSC']['filePickerHome'];
		}

		$objTemplate->theme = \Backend::getTheme();
		$objTemplate->base = \Environment::get('base');
		$objTemplate->language = $GLOBALS['TL_LANGUAGE'];
		$objTemplate->title = specialchars($objTemplate->title);
		$objTemplate->charset = \Config::get('characterSet');
		$objTemplate->account = $GLOBALS['TL_LANG']['MOD']['login'][1];
		$objTemplate->preview = $GLOBALS['TL_LANG']['MSC']['fePreview'];
		$objTemplate->previewTitle = specialchars($GLOBALS['TL_LANG']['MSC']['fePreviewTitle']);
		$objTemplate->pageOffset = \Input::cookie('BE_PAGE_OFFSET');
		$objTemplate->logout = $GLOBALS['TL_LANG']['MSC']['logoutBT'];
		$objTemplate->logoutTitle = specialchars($GLOBALS['TL_LANG']['MSC']['logoutBTTitle']);
		$objTemplate->backendModules = $GLOBALS['TL_LANG']['MSC']['backendModules'];
		$objTemplate->username = $GLOBALS['TL_LANG']['MSC']['user'] . ' ' . $GLOBALS['TL_USERNAME'];
		$objTemplate->skipNavigation = specialchars($GLOBALS['TL_LANG']['MSC']['skipNavigation']);
		$objTemplate->request = ampersand(\Environment::get('request'));
		$objTemplate->top = $GLOBALS['TL_LANG']['MSC']['backToTop'];
		$objTemplate->modules = $this->User->navigation();
		$objTemplate->home = $GLOBALS['TL_LANG']['MSC']['home'];
		$objTemplate->homeTitle = $GLOBALS['TL_LANG']['MSC']['homeTitle'];
		$objTemplate->backToTop = specialchars($GLOBALS['TL_LANG']['MSC']['backToTopTitle']);
		$objTemplate->expandNode = $GLOBALS['TL_LANG']['MSC']['expandNode'];
		$objTemplate->collapseNode = $GLOBALS['TL_LANG']['MSC']['collapseNode'];
		$objTemplate->loadingData = $GLOBALS['TL_LANG']['MSC']['loadingData'];
		$objTemplate->loadFonts = \Config::get('loadGoogleFonts');
		$objTemplate->isAdmin = $this->User->isAdmin;
		$objTemplate->isCoreOnlyMode = \Config::get('coreOnlyMode');
		$objTemplate->coreOnlyMode = $GLOBALS['TL_LANG']['MSC']['coreOnlyMode'];
		$objTemplate->coreOnlyOff = specialchars($GLOBALS['TL_LANG']['MSC']['coreOnlyOff']);
		$objTemplate->coreOnlyHref = $this->addToUrl('smo=1');
		$objTemplate->isMaintenanceMode = \Config::get('maintenanceMode');
		$objTemplate->maintenanceMode = $GLOBALS['TL_LANG']['MSC']['maintenanceMode'];
		$objTemplate->maintenanceOff = specialchars($GLOBALS['TL_LANG']['MSC']['maintenanceOff']);
		$objTemplate->maintenanceHref = $this->addToUrl('mmo=1');
		$objTemplate->buildCacheLink = $GLOBALS['TL_LANG']['MSC']['buildCacheLink'];
		$objTemplate->buildCacheText = $GLOBALS['TL_LANG']['MSC']['buildCacheText'];
		$objTemplate->buildCacheHref = $this->addToUrl('bic=1');
		$objTemplate->isPopup = \Input::get('popup');

		// Hide the cache message in the repository manager (see #5966)
		if (!\Config::get('bypassCache') && $this->User->isAdmin)
		{
			$objTemplate->needsCacheBuild = ((\Input::get('do') != 'repository_manager' || !isset($_GET['install']) && !isset($_GET['uninstall']) && !isset($_GET['update'])) && !is_dir(TL_ROOT . '/system/cache/dca'));
		}

		// Front end preview links
		if (defined('CURRENT_ID') && CURRENT_ID != '')
		{
			// Pages
			if (\Input::get('do') == 'page')
			{
				$objTemplate->frontendFile = '?page=' . CURRENT_ID;
			}

			// Articles
			elseif (\Input::get('do') == 'article')
			{
				if (($objArticle = \ArticleModel::findByPk(CURRENT_ID)) !== null)
				{
					$objTemplate->frontendFile = '?page=' . $objArticle->pid;
				}
			}
		}

		$objTemplate->output();
	}


	/**
	 * Add the welcome screen
	 * @return string
	 */
	protected function welcomeScreen()
	{
		\System::loadLanguageFile('explain');

		/** @var \BackendTemplate|object $objTemplate */
		$objTemplate = new \BackendTemplate('be_welcome');
		$objTemplate->messages = \Message::generate(false, true);

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

		// Add the versions overview
		\Versions::addToTemplate($objTemplate);

		$objTemplate->welcome = sprintf($GLOBALS['TL_LANG']['MSC']['welcomeTo'], \Config::get('websiteTitle'));
		$objTemplate->showDifferences = specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MSC']['showDifferences']));
		$objTemplate->systemMessages = $GLOBALS['TL_LANG']['MSC']['systemMessages'];
		$objTemplate->shortcuts = $GLOBALS['TL_LANG']['MSC']['shortcuts'][0];
		$objTemplate->shortcutsLink = $GLOBALS['TL_LANG']['MSC']['shortcuts'][1];
		$objTemplate->editElement = specialchars($GLOBALS['TL_LANG']['MSC']['editElement']);

		return $objTemplate->parse();
	}
}
