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
 * Main front end controller.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class FrontendIndex extends \Frontend
{

	/**
	 * Initialize the object
	 */
	public function __construct()
	{
		// Try to read from cache
		$this->outputFromCache();

		// Load the user object before calling the parent constructor
		$this->import('FrontendUser', 'User');
		parent::__construct();

		// Check whether a user is logged in
		define('BE_USER_LOGGED_IN', $this->getLoginStatus('BE_USER_AUTH'));
		define('FE_USER_LOGGED_IN', $this->getLoginStatus('FE_USER_AUTH'));

		// No back end user logged in
		if (!$_SESSION['DISABLE_CACHE'])
		{
			// Maintenance mode (see #4561 and #6353)
			if (\Config::get('maintenanceMode'))
			{
				header('HTTP/1.1 503 Service Unavailable');
				die_nicely('be_unavailable', 'This site is currently down for maintenance. Please come back later.');
			}

			// Disable the debug mode (see #6450)
			\Config::set('debugMode', false);
		}
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

			/** @var \PageRoot $objHandler */
			$objHandler = new $GLOBALS['TL_PTY']['root']();
			$pageId = $objHandler->generate($objRootPage->id, true, true);
		}
		// Throw a 404 error if the request is not a Contao request (see #2864)
		elseif ($pageId === false)
		{
			$this->User->authenticate();

			/** @var \PageError404 $objHandler */
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($pageId);
		}
		// Throw a 404 error if URL rewriting is active and the URL contains the index.php fragment
		elseif (\Config::get('rewriteURL') && strncmp(\Environment::get('request'), 'index.php/', 10) === 0)
		{
			$this->User->authenticate();

			/** @var \PageError403 $objHandler */
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($pageId);
		}

		// Get the current page object(s)
		$objPage = \PageModel::findPublishedByIdOrAlias($pageId);

		// Check the URL and language of each page if there are multiple results
		if ($objPage !== null && $objPage->count() > 1)
		{
			$objNewPage = null;
			$arrPages = array();

			// Order by domain and language
			while ($objPage->next())
			{
				/** @var \PageModel $objModel */
				$objModel = $objPage->current();
				$objCurrentPage = $objModel->loadDetails();

				$domain = $objCurrentPage->domain ?: '*';
				$arrPages[$domain][$objCurrentPage->rootLanguage] = $objCurrentPage;

				// Also store the fallback language
				if ($objCurrentPage->rootIsFallback)
				{
					$arrPages[$domain]['*'] = $objCurrentPage;
				}
			}

			$strHost = \Environment::get('host');

			// Look for a root page whose domain name matches the host name
			if (isset($arrPages[$strHost]))
			{
				$arrLangs = $arrPages[$strHost];
			}
			else
			{
				$arrLangs = $arrPages['*'] ?: array(); // empty domain
			}

			// Use the first result (see #4872)
			if (!\Config::get('addLanguageToUrl'))
			{
				$objNewPage = current($arrLangs);
			}

			// Try to find a page matching the language parameter
			elseif (($lang = \Input::get('language')) != '' && isset($arrLangs[$lang]))
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
		if ($objPage === null || ($objPage instanceof \Model\Collection && $objPage->count() != 1))
		{
			$this->User->authenticate();
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($pageId);
		}

		// Make sure $objPage is a Model
		if ($objPage instanceof \Model\Collection)
		{
			$objPage = $objPage->current();
		}

		// If the page has an alias, it can no longer be called via ID (see #7661)
		if ($objPage->alias != '' && $pageId == $objPage->id)
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
			$objPage->loadDetails();
		}

		// Set the admin e-mail address
		if ($objPage->adminEmail != '')
		{
			list($GLOBALS['TL_ADMIN_NAME'], $GLOBALS['TL_ADMIN_EMAIL']) = \String::splitFriendlyEmail($objPage->adminEmail);
		}
		else
		{
			list($GLOBALS['TL_ADMIN_NAME'], $GLOBALS['TL_ADMIN_EMAIL']) = \String::splitFriendlyEmail(\Config::get('adminEmail'));
		}

		// Exit if the root page has not been published (see #2425)
		// Do not try to load the 404 page, it can cause an infinite loop!
		if (!BE_USER_LOGGED_IN && !$objPage->rootIsPublic)
		{
			header('HTTP/1.1 404 Not Found');
			die_nicely('be_no_page', 'Page not found');
		}

		// Check wether the language matches the root page language
		if (\Config::get('addLanguageToUrl') && \Input::get('language') != $objPage->rootLanguage)
		{
			$this->User->authenticate();
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($pageId);
		}

		// Check whether there are domain name restrictions
		if ($objPage->domain != '')
		{
			// Load an error 404 page object
			if ($objPage->domain != \Environment::get('host'))
			{
				$this->User->authenticate();
				$objHandler = new $GLOBALS['TL_PTY']['error_404']();
				$objHandler->generate($objPage->id, $objPage->domain, \Environment::get('host'));
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
				$this->log('Page "' . $pageId . '" can only be accessed by groups "' . implode(', ', (array) $objPage->groups) . '" (current user groups: ' . implode(', ', $this->User->groups) . ')', __METHOD__, TL_ERROR);

				$objHandler = new $GLOBALS['TL_PTY']['error_403']();
				$objHandler->generate($pageId, $objRootPage);
			}
		}

		// Load the page object depending on its type
		$objHandler = new $GLOBALS['TL_PTY'][$objPage->type]();

		// Backup some globals (see #7659)
		$arrHead = $GLOBALS['TL_HEAD'];
		$arrBody = $GLOBALS['TL_BODY'];
		$arrMootools = $GLOBALS['TL_MOOTOOLS'];
		$arrJquery = $GLOBALS['TL_JQUERY'];

		try
		{
			// Generate the page
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
					$objHandler->generate($objPage, true);
					break;
			}
		}
		catch (\UnusedArgumentsException $e)
		{
			// Restore the globals (see #7659)
			$GLOBALS['TL_HEAD'] = $arrHead;
			$GLOBALS['TL_BODY'] = $arrBody;
			$GLOBALS['TL_MOOTOOLS'] = $arrMootools;
			$GLOBALS['TL_JQUERY'] = $arrJquery;

			// Render the error page (see #5570)
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($pageId, null, null, true);
		}

		// Stop the script (see #4565)
		exit;
	}


	/**
	 * Try to load the page from the cache
	 */
	protected function outputFromCache()
	{
		// Build the page if a user is (potentially) logged in or there is POST data
		if (!empty($_POST) || \Input::cookie('FE_USER_AUTH') || \Input::cookie('FE_AUTO_LOGIN') || $_SESSION['DISABLE_CACHE'] || isset($_SESSION['LOGIN_ERROR']) || \Config::get('debugMode'))
		{
			return;
		}

		// Try to map the empty request
		if (\Environment::get('request') == '' || \Environment::get('request') == 'index.php')
		{
			// Return if the language is added to the URL and the empty domain will be redirected
			if (\Config::get('addLanguageToUrl') && !\Config::get('doNotRedirectEmpty'))
			{
				return;
			}

			$strCacheKey = null;
			$arrLanguage = \Environment::get('httpAcceptLanguage');

			// Try to get the cache key from the mapper array
			if (file_exists(TL_ROOT . '/system/cache/config/mapping.php'))
			{
				$arrMapper = include TL_ROOT . '/system/cache/config/mapping.php';
				$arrPaths = array(\Environment::get('host'), '*');

				// Try the language specific keys
				foreach ($arrLanguage as $strLanguage)
				{
					foreach ($arrPaths as $strPath)
					{
						$strKey = $strPath . '/empty.' . $strLanguage;

						if (isset($arrMapper[$strKey]))
						{
							$strCacheKey = $arrMapper[$strKey];
							break;
						}
					}
				}

				// Try the fallback key
				if ($strCacheKey === null)
				{
					foreach ($arrPaths as $strPath)
					{
						$strKey = $strPath . '/empty.fallback';

						if (isset($arrMapper[$strKey]))
						{
							$strCacheKey = $arrMapper[$strKey];
							break;
						}
					}
				}
			}

			// Fall back to the first accepted language
			if ($strCacheKey === null)
			{
				$strCacheKey = \Environment::get('host') . '/empty.' . $arrLanguage[0];
			}
		}
		else
		{
			$strCacheKey = \Environment::get('host') . '/' . \Environment::get('request');
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
		$strCacheFile = null;

		// Check for a mobile layout
		if (\Input::cookie('TL_VIEW') == 'mobile' || (\Environment::get('agent')->mobile && \Input::cookie('TL_VIEW') != 'desktop'))
		{
			$strMd5CacheKey = md5($strCacheKey . '.mobile');
			$strCacheFile = TL_ROOT . '/system/cache/html/' . substr($strMd5CacheKey, 0, 1) . '/' . $strMd5CacheKey . '.html';

			if (file_exists($strCacheFile))
			{
				$blnFound = true;
			}
		}

		// Check for a desktop layout (see #7826)
		else
		{
			$strMd5CacheKey = md5($strCacheKey . '.desktop');
			$strCacheFile = TL_ROOT . '/system/cache/html/' . substr($strMd5CacheKey, 0, 1) . '/' . $strMd5CacheKey . '.html';

			if (file_exists($strCacheFile))
			{
				$blnFound = true;
			}
		}

		// Check for a regular layout
		if (!$blnFound)
		{
			$strMd5CacheKey = md5($strCacheKey);
			$strCacheFile = TL_ROOT . '/system/cache/html/' . substr($strMd5CacheKey, 0, 1) . '/' . $strMd5CacheKey . '.html';

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
		$type = null;

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
		if (!isset($_GET['pdf']) && !isset($_GET['file']) && !isset($_GET['id']) && $session['referer']['current'] != \Environment::get('requestUri'))
		{
			$session['referer']['last'] = $session['referer']['current'];
			$session['referer']['current'] = substr(\Environment::get('requestUri'), strlen(TL_PATH) + 1);
		}

		// Store the session data
		$this->Session->setData($session);

		// Load the default language file (see #2644)
		\System::loadLanguageFile('default');

		// Replace the insert tags and then re-replace the request_token tag in case a form element has been loaded via insert tag
		$strBuffer = $this->replaceInsertTags($strBuffer, false);
		$strBuffer = str_replace(array('{{request_token}}', '[{]', '[}]'), array(REQUEST_TOKEN, '{{', '}}'), $strBuffer);

		// HOOK: allow to modify the compiled markup (see #4291 and #7457)
		if (isset($GLOBALS['TL_HOOKS']['modifyFrontendPage']) && is_array($GLOBALS['TL_HOOKS']['modifyFrontendPage']))
		{
			foreach ($GLOBALS['TL_HOOKS']['modifyFrontendPage'] as $callback)
			{
				$this->import($callback[0]);
				$strBuffer = $this->$callback[0]->$callback[1]($strBuffer, null);
			}
		}

		// Content type
		if (!$content)
		{
			$content = 'text/html';
		}

		// Send the status header (see #6585)
		if ($type == 'error_403')
		{
			header('HTTP/1.1 403 Forbidden');
		}
		elseif ($type == 'error_404')
		{
			header('HTTP/1.1 404 Not Found');
		}
		else
		{
			header('HTTP/1.1 200 Ok');
		}

		header('Vary: User-Agent', false);
		header('Content-Type: ' . $content . '; charset=' . \Config::get('characterSet'));

		// Send the cache headers
		if ($expire !== null && (\Config::get('cacheMode') == 'both' || \Config::get('cacheMode') == 'browser'))
		{
			header('Cache-Control: public, max-age=' . ($expire - time()));
			header('Pragma: public');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
			header('Expires: ' . gmdate('D, d M Y H:i:s', $expire) . ' GMT');
		}
		else
		{
			header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
			header('Pragma: no-cache');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header('Expires: Fri, 06 Jun 1975 15:10:00 GMT');
		}

		echo $strBuffer;
		exit;
	}
}
