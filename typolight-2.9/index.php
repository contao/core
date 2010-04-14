<?php

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
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

		// HOOK: trigger recall extension
		if (!FE_USER_LOGGED_IN && $this->Input->cookie('tl_recall_fe') && in_array('recall', $this->Config->getActiveModules()))
		{
			Recall::frontend($this);
		}
	}


	/**
	 * Run the controller
	 */
	public function run()
	{
		global $objPage;

		// Get page ID
		$pageId = $this->getPageIdFromUrl();

		// Load a website root page object if there is no page ID
		if (is_null($pageId))
		{
			$objHandler = new $GLOBALS['TL_PTY']['root']();
			$pageId = $objHandler->generate($this->getRootIdFromUrl(), true);
		}

		$time = time();

		// Get the current page object
		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE (id=? OR alias=?)" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : ""))
								  ->execute((is_numeric($pageId) ? $pageId : 0), $pageId, $time, $time);

		// Check the URL of each page if there are multiple results
		if ($objPage->numRows > 1)
		{
			$objNewPage = null;
			$strHost = $this->Environment->host;

			while ($objPage->next())
			{
				$objCurrentPage = $this->getPageDetails($objPage->id);

				// Look for a root page whose domain name matches the host name
				if ($objCurrentPage->domain == $strHost || $objCurrentPage->domain == 'www.' . $strHost)
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

			// Check whether there are domain name restrictions
			if (strlen($objPage->domain))
			{
				$strDomain = preg_replace('/^www\./i', '', $objPage->domain);

				// Load an error 404 page object
				if ($strDomain != $this->Environment->host)
				{
					$objHandler = new $GLOBALS['TL_PTY']['error_404']();
					$objHandler->generate($objPage->id, $strDomain, $this->Environment->host);
				}
			}
		}

		// Authenticate the current user
		if (!$this->User->authenticate() && $objPage->protected && !BE_USER_LOGGED_IN)
		{
			$objHandler = new $GLOBALS['TL_PTY']['error_403']();
			$objHandler->generate($pageId);
		}

		// Check user groups if the page is protected
		if ($objPage->protected && !BE_USER_LOGGED_IN && (!is_array($objPage->groups) || count($objPage->groups) < 1 || count(array_intersect($objPage->groups, $this->User->groups)) < 1))
		{
			$this->log('Page "' . $pageId . '" can only be accessed by groups "' . implode(', ', (array) $objPage->groups) . '" (current user groups: ' . implode(', ', $this->User->groups) . ')', 'Index run()', TL_ERROR);

			$objHandler = new $GLOBALS['TL_PTY']['error_403']();
			$objHandler->generate($pageId);
		}

		// Load the page object depending on its type
		$objHandler = new $GLOBALS['TL_PTY'][$objPage->type]();

		switch ($objPage->type)
		{
			case 'root':
			case 'error_403':
			case 'error_404':
				$objHandler->generate($pageId);
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
		if ($_SESSION['TL_USER_LOGGED_IN'] || !empty($_POST))
		{
			return;
		}

		$this->import('Environment');
		$strCacheKey = $this->Environment->base . preg_replace('@^index.php/?@i', '', $this->Environment->request);

		// Do not cache empty requests
		if (!$strCacheKey)
		{
			return;
		}

		$strCacheFile = TL_ROOT . '/system/tmp/' . md5($strCacheKey);

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

		// Read buffer
		$strBuffer = ob_get_contents();
		ob_end_clean();

		/**
		 * Copyright notice
		 * 
		 * ACCORDING TO THE LESSER GENERAL PUBLIC LICENSE (LGPL),YOU ARE NOT
		 * PERMITTED TO RUN TYPOlight WITHOUT THIS COPYRIGHT NOTICE. CHANGING,
		 * REMOVING OR OBSTRUCTING IT IS PROHIBITED BY LAW!
		 */
		$strBuffer = preg_replace
		(
			'/(<head[^>]*>)/',
			"<!--\n\n"
			. "\tThis website is powered by TYPOlight Open Source CMS :: Licensed under GNU/LGPL\n"
			. "\tCopyright ©2005-" . date('Y') . " by Leo Feyer :: Extensions are copyright of their respective owners\n"
			. "\tVisit the project website at http://www.typolight.org for more information\n\n"
			. "//-->\n$1",
			$strBuffer, 1
		);

		// Session required to determine the referer
		$this->import('Session');
		$session = $this->Session->getData();

		// Set new referer
		if (!isset($_GET['pdf']) && !isset($_GET['file']) && !isset($_GET['id']) && $session['referer']['current'] != $this->Environment->requestUri)
		{
			$session['referer']['last'] = $session['referer']['current'];
			$session['referer']['current'] = $this->Environment->requestUri;
		}

		// Store session data
		$this->Session->setData($session);

		// Content type
		if (!$content)
		{
			$content = 'text/html';
		}

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
			header('Expires: Wed, 28 Jan 1976 11:52:00 GMT');
			header('Pragma: no-cache');
		}

		// Replace insert tags
		$strBuffer = $this->replaceInsertTags($strBuffer);
		$strBuffer = str_replace(array('[{]', '[}]'), array('{{', '}}'), $strBuffer);

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