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
define('TL_MODE', 'FE');
require 'system/initialize.php';


/**
 * Class Index
 *
 * Main front end controller.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class Index extends Frontend
{

	/**
	 * Initialize the object
	 */
	public function __construct()
	{
		// Try to read from cache
		$this->outputFromCache();

		// Redirect to the install tool
		if (!Config::getInstance()->isComplete())
		{
			$this->redirect('contao/install.php');
		}

		// Load the user object before calling the parent constructor
		$this->import('FrontendUser', 'User');
		parent::__construct();

		// Check whether a user is logged in
		define('BE_USER_LOGGED_IN', $this->getLoginStatus('BE_USER_AUTH'));
		define('FE_USER_LOGGED_IN', $this->getLoginStatus('FE_USER_AUTH'));
	}


	/**
	 * Run the controller
	 */
	public function run()
	{
		global $objPage;
		$pageId = $this->getPageIdFromUrl();
		$objRootPage = null;

		// Load a website root page object if there is no page ID
		if ($pageId === null)
		{
			$objRootPage = $this->getRootPageFromUrl();
			$objHandler = new $GLOBALS['TL_PTY']['root']();
			$pageId = $objHandler->generate($objRootPage->id, true);
		}
		// Throw a 404 error if the request is not a Contao request (see #2864)
		elseif ($pageId === false)
		{
			$this->User->authenticate();
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($pageId);
		}
		// Throw a 404 error if URL rewriting is active and the URL contains the index.php fragment
		elseif ($GLOBALS['TL_CONFIG']['rewriteURL'] && strncmp(Environment::get('request'), 'index.php/', 10) === 0)
		{
			$this->User->authenticate();
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($pageId);
		}

		// Get the current page object(s)
		$objPage = PageModel::findPublishedByIdOrAlias($pageId);

		// Check the URL and language of each page if there are multiple results
		if ($objPage !== null && $objPage->count() > 1)
		{
			$objNewPage = null;
			$arrPages = array();

			// Order by domain and language
			while ($objPage->next())
			{
				$objCurrentPage = $this->getPageDetails($objPage->current());

				$domain = $objCurrentPage->domain ?: '*';
				$arrPages[$domain][$objCurrentPage->rootLanguage] = $objCurrentPage;

				// Also store the fallback language
				if ($objCurrentPage->rootIsFallback)
				{
					$arrPages[$domain]['*'] = $objCurrentPage;
				}
			}

			$strHost = Environment::get('host');

			// Look for a root page whose domain name matches the host name
			if (isset($arrPages[$strHost]))
			{
				$arrLangs = $arrPages[$strHost];
			}
			else
			{
				$arrLangs = $arrPages['*']; // Empty domain
			}

			// Use the first result (see #4872)
			if (!$GLOBALS['TL_CONFIG']['addLanguageToUrl'])
			{
				$objNewPage = current($arrLangs);
			}
			// Try to find a page matching the language parameter
			elseif (($lang = Input::get('language')) != '' && isset($arrLangs[$lang]))
			{
				$objNewPage = $arrLangs[$lang];
			}

			// Store the page object
			if (is_object($objNewPage))
			{
				$objPage = $objNewPage;
			}
		}

		// Throw a 404 error if the page could not be found or the result is still ambiguous
		if ($objPage === null || ($objPage instanceof Model\Collection && $objPage->count() != 1))
		{
			$this->User->authenticate();
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($pageId);
		}

		// Load a website root page object (will redirect to the first active regular page)
		if ($objPage->type == 'root')
		{
			$objHandler = new $GLOBALS['TL_PTY']['root']();
			$objHandler->generate($objPage->id);
		}

		// Inherit the settings from the parent pages if it has not been done yet
		if (!is_bool($objPage->protected))
		{
			$objPage = $this->getPageDetails($objPage);
		}

		// Use the global date format if none is set
		if ($objPage->dateFormat == '')
		{
			$objPage->dateFormat = $GLOBALS['TL_CONFIG']['dateFormat'];
		}
		if ($objPage->timeFormat == '')
		{
			$objPage->timeFormat = $GLOBALS['TL_CONFIG']['timeFormat'];
		}
		if ($objPage->datimFormat == '')
		{
			$objPage->datimFormat = $GLOBALS['TL_CONFIG']['datimFormat'];
		}

		// Set the admin e-mail address
		if ($objPage->adminEmail != '')
		{
			list($GLOBALS['TL_ADMIN_NAME'], $GLOBALS['TL_ADMIN_EMAIL']) = $this->splitFriendlyName($objPage->adminEmail);
		}
		else
		{
			list($GLOBALS['TL_ADMIN_NAME'], $GLOBALS['TL_ADMIN_EMAIL']) = $this->splitFriendlyName($GLOBALS['TL_CONFIG']['adminEmail']);
		}

		// Exit if the root page has not been published (see #2425) and
		// do not try to load the 404 page! It can cause an infinite loop.
		if (!BE_USER_LOGGED_IN && !$objPage->rootIsPublic)
		{
			header('HTTP/1.1 404 Not Found');
			die('Page not found');
		}

		// Check wether the language matches the root page language
		if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'] && Input::get('language') != $objPage->rootLanguage)
		{
			$this->User->authenticate();
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($pageId);
		}

		// Check whether there are domain name restrictions
		if ($objPage->domain != '')
		{
			// Load an error 404 page object
			if ($objPage->domain != Environment::get('host'))
			{
				$this->User->authenticate();
				$objHandler = new $GLOBALS['TL_PTY']['error_404']();
				$objHandler->generate($objPage->id, $objPage->domain, Environment::get('host'));
			}
		}

		// Authenticate the user
		if (!$this->User->authenticate() && $objPage->protected && !BE_USER_LOGGED_IN)
		{
			$objHandler = new $GLOBALS['TL_PTY']['error_403']();
			$objHandler->generate($pageId, $objRootPage);
		}

		// Check the user groups if the page is protected
		if ($objPage->protected && !BE_USER_LOGGED_IN)
		{
			$arrGroups = $objPage->groups; // required for empty()

			if (!is_array($arrGroups) || empty($arrGroups) || !count(array_intersect($arrGroups, $this->User->groups)))
			{
				$this->log('Page "' . $pageId . '" can only be accessed by groups "' . implode(', ', (array) $objPage->groups) . '" (current user groups: ' . implode(', ', $this->User->groups) . ')', 'Index run()', TL_ERROR);

				$objHandler = new $GLOBALS['TL_PTY']['error_403']();
				$objHandler->generate($pageId, $objRootPage);
			}
		}

		// Load the page object depending on its type
		$objHandler = new $GLOBALS['TL_PTY'][$objPage->type]();

		switch ($objPage->type)
		{
			case 'root':
			case 'error_404':
				$objHandler->generate($pageId);
				break;

			case 'error_403':
				$objHandler->generate($pageId, $objRootPage);
				break;

			default:
				$objHandler->generate($objPage);
				break;
		}

		// Stop the script (see #4565)
		exit;
	}


	/**
	 * Try to load the page from the cache
	 */
	protected function outputFromCache()
	{
		// Build the page if a user is logged in or there is POST data
		if (!empty($_POST) || $_SESSION['TL_USER_LOGGED_IN'] || $_SESSION['DISABLE_CACHE'] || isset($_SESSION['LOGIN_ERROR']) || $GLOBALS['TL_CONFIG']['debugMode'])
		{
			return;
		}

		/**
		 * If the request string is empty, look for a cached page matching the
		 * primary browser language. This is a compromise between not caching
		 * empty requests at all and considering all browser languages, which
		 * is not possible for various reasons.
		 */
		if (Environment::get('request') == '' || Environment::get('request') == 'index.php')
		{
			// Return if the language is added to the URL and the empty domain will be redirected
			if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'] && !$GLOBALS['TL_CONFIG']['doNotRedirectEmpty'])
			{
				return;
			}

			$arrLanguage = Environment::get('httpAcceptLanguage');
			$strCacheKey = Environment::get('base') .'empty.'. $arrLanguage[0];
		}
		else
		{
			$strCacheKey = Environment::get('base') . Environment::get('request');
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getCacheKey']) && is_array($GLOBALS['TL_HOOKS']['getCacheKey']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getCacheKey'] as $callback)
			{
				$this->import($callback[0]);
				$strCacheKey = $this->$callback[0]->$callback[1]($strCacheKey);
			}
		}

		$blnFound = false;

		// Check for a mobile layout
		if (Environment::get('agent')->mobile)
		{
			$strCacheKey = md5($strCacheKey . '.mobile');
			$strCacheFile = TL_ROOT . '/system/cache/html/' . substr($strCacheKey, 0, 1) . '/' . $strCacheKey . '.html';

			if (file_exists($strCacheFile))
			{
				$blnFound = true;
			}
		}

		// Check for a regular layout
		if (!$blnFound)
		{
			$strCacheKey = md5($strCacheKey);
			$strCacheFile = TL_ROOT . '/system/cache/html/' . substr($strCacheKey, 0, 1) . '/' . $strCacheKey . '.html';

			if (file_exists($strCacheFile))
			{
				$blnFound = true;
			}
		}

		// Return if the file does not exist
		if (!$blnFound)
		{
			return;
		}

		$expire = null;
		$content = null;

		// Include the file
		ob_start();
		require_once $strCacheFile;

		// The file has expired
		if ($expire < time())
		{
			ob_end_clean();
			return;
		}

		// Read the buffer
		$strBuffer = ob_get_contents();
		ob_end_clean();

		// Session required to determine the referer
		$this->import('Session');
		$session = $this->Session->getData();

		// Set the new referer
		if (!isset($_GET['pdf']) && !isset($_GET['file']) && !isset($_GET['id']) && $session['referer']['current'] != Environment::get('requestUri'))
		{
			$session['referer']['last'] = $session['referer']['current'];
			$session['referer']['current'] = Environment::get('requestUri');
		}

		// Store the session data
		$this->Session->setData($session);

		// Load the default language file (see #2644)
		$this->import('Config');
		$this->loadLanguageFile('default');

		// Replace the insert tags and then re-replace the request_token
		// tag in case a form element has been loaded via insert tag
		$strBuffer = $this->replaceInsertTags($strBuffer, false);
		$strBuffer = str_replace(array('{{request_token}}', '[{]', '[}]'), array(REQUEST_TOKEN, '{{', '}}'), $strBuffer);

		// Content type
		if (!$content)
		{
			$content = 'text/html';
		}

		header('Vary: User-Agent', false);
		header('Content-Type: ' . $content . '; charset=' . $GLOBALS['TL_CONFIG']['characterSet']);

		// Send the cache headers
		if ($expire !== null && ($GLOBALS['TL_CONFIG']['cacheMode'] == 'both' || $GLOBALS['TL_CONFIG']['cacheMode'] == 'browser'))
		{
			header('Cache-Control: public, max-age=' . ($expire - time()));
			header('Expires: ' . gmdate('D, d M Y H:i:s', $expire) . ' GMT');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
			header('Pragma: public');
		}
		else
		{
			header('Cache-Control: no-cache');
			header('Cache-Control: pre-check=0, post-check=0', false);
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header('Expires: Fri, 06 Jun 1975 15:10:00 GMT');
			header('Pragma: no-cache');
		}

		echo $strBuffer;
		exit;
	}
}


/**
 * Instantiate the controller
 */
$objIndex = new Index();
$objIndex->run();
