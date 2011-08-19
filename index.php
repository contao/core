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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Initialize the system
 */
define('TL_MODE', 'FE');
require('system/initialize.php');


/**
 * Class Index
 *
 * Main front end controller.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
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

		// Load user object before calling the parent constructor
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

		// Get the page ID
		$pageId = $this->getPageIdFromUrl();

		// Load a website root page object if there is no page ID
		if (is_null($pageId))
		{
			$objHandler = new $GLOBALS['TL_PTY']['root']();
			$pageId = $objHandler->generate($this->getRootIdFromUrl(), true);
		}

		// Throw a 404 error if URL rewriting is active and the URL contains the index.php fragment
		if ($GLOBALS['TL_CONFIG']['rewriteURL'] && strncmp($this->Environment->request, 'index.php/', 10) === 0)
		{
			$this->User->authenticate();
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($pageId);
		}

		$time = time();

		// Get the current page object
		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE (id=? OR alias=?)" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : ""))
								  ->execute((is_numeric($pageId) ? $pageId : 0), $pageId);

		// Check the URL of each page if there are multiple results
		if ($objPage->numRows > 1)
		{
			$objNewPage = null;

			while ($objPage->next())
			{
				$objCurrentPage = $this->getPageDetails($objPage->id);

				// Look for a root page whose domain name matches the host name
				if ($objCurrentPage->domain == $this->Environment->host)
				{
					$objNewPage = $objCurrentPage;
					break;
				}

				// Fall back to a root page without domain name
				if ($objCurrentPage->domain == '')
				{
					$objNewPage = $objCurrentPage;
				}
			}

			// Matching root page found
			if (is_object($objNewPage))
			{
				$objPage = $objNewPage;
			}
		}

		// Load an error 404 page object if the result is empty or still ambiguous
		if ($objPage->numRows != 1)
		{
			$this->User->authenticate();
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($pageId);
		}

		// Load a website root page object if the page is a website root page
		if ($objPage->type == 'root')
		{
			$objHandler = new $GLOBALS['TL_PTY']['root']();
			$objHandler->generate($objPage->id);
		}

		// Inherit settings from parent pages if it has not been done yet
		if (!is_bool($objPage->protected))
		{
			$objPage = $this->getPageDetails($objPage->id);
		}

		// Exit if the root page has not been published (see #2425) and
		// do not try to load the 404 page! It can cause an infinite loop.
		if (!BE_USER_LOGGED_IN && !$objPage->rootIsPublic)
		{
			header('HTTP/1.1 404 Not Found');
			die('Page not found');
		}

		// Check whether there are domain name restrictions
		if ($objPage->domain != '')
		{
			// Load an error 404 page object
			if ($objPage->domain != $this->Environment->host)
			{
				$objHandler = new $GLOBALS['TL_PTY']['error_404']();
				$objHandler->generate($objPage->id, $objPage->domain, $this->Environment->host);
			}
		}

		// Authenticate the current user
		if (!$this->User->authenticate() && $objPage->protected && !BE_USER_LOGGED_IN)
		{
			$objHandler = new $GLOBALS['TL_PTY']['error_403']();
			$objHandler->generate($pageId, $objPage->rootId);
		}

		// Check user groups if the page is protected
		if ($objPage->protected && !BE_USER_LOGGED_IN && (!is_array($objPage->groups) || count($objPage->groups) < 1 || count(array_intersect($objPage->groups, $this->User->groups)) < 1))
		{
			$this->log('Page "' . $pageId . '" can only be accessed by groups "' . implode(', ', (array) $objPage->groups) . '" (current user groups: ' . implode(', ', $this->User->groups) . ')', 'Index run()', TL_ERROR);

			$objHandler = new $GLOBALS['TL_PTY']['error_403']();
			$objHandler->generate($pageId, $objPage->rootId);
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
				$objHandler->generate($pageId, $objPage->rootId);
				break;

			default:
				$objHandler->generate($objPage);
				break;
		}
	}


	/**
	 * Load the page from the cache table
	 */
	protected function outputFromCache()
	{
		// Build page if a user is logged in or there is POST data
		if (!empty($_POST) || $_SESSION['TL_USER_LOGGED_IN'] || $_SESSION['DISABLE_CACHE'] || isset($_SESSION['LOGIN_ERROR']))
		{
			return;
		}

		$this->import('Environment');

		/**
		 * If the request string is empty, look for a cached page matching the
		 * primary browser language. This is a compromise between not caching
		 * empty requests at all and considering all browser languages, which
		 * is not possible for various reasons.
		 */
		if ($this->Environment->request == '' || $this->Environment->request == 'index.php')
		{
			$strCacheKey = $this->Environment->base .'empty.'. $this->Environment->httpAcceptLanguage[0];
		}
		else
		{
			$strCacheKey = $this->Environment->base . $this->Environment->request;
		}

		$strCacheFile = TL_ROOT . '/system/tmp/' . md5($strCacheKey) . '.html';

		// Return if the file does not exist
		if (!file_exists($strCacheFile))
		{
			return;
		}

		$expire = null;
		$content = null;

		// Include file
		ob_start();
		require_once($strCacheFile);

		// File has expired
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

		// Set new referer
		if (!isset($_GET['pdf']) && !isset($_GET['file']) && !isset($_GET['id']) && $session['referer']['current'] != $this->Environment->requestUri)
		{
			$session['referer']['last'] = $session['referer']['current'];
			$session['referer']['current'] = $this->Environment->requestUri;
		}

		// Store the session data
		$this->Session->setData($session);

		// Load the default language file (see #2644)
		$this->import('Config');
		$this->loadLanguageFile('default');

		// Replace insert tags and then re-replace the request_token
		// tag in case a form element has been loaded via insert tag
		$strBuffer = $this->replaceInsertTags($strBuffer);
		$strBuffer = str_replace(array('{{request_token}}', '[{]', '[}]'), array(REQUEST_TOKEN, '{{', '}}'), $strBuffer);

		// Content type
		if (!$content)
		{
			$content = 'text/html';
		}

		header('Vary: User-Agent', false);
		header('Content-Type: ' . $content . '; charset=' . $GLOBALS['TL_CONFIG']['characterSet']);

		// Send cache headers
		if (!is_null($expire) && ($GLOBALS['TL_CONFIG']['cacheMode'] == 'both' || $GLOBALS['TL_CONFIG']['cacheMode'] == 'browser'))
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
 * Instantiate controller
 */
$objIndex = new Index();
$objIndex->run();

?>