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
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class PageError404
 *
 * Provide methods to handle an error 404 page.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class PageError404 extends \Frontend
{

	/**
	 * Generate an error 404 page
	 * @param integer
	 * @param string
	 * @param string
	 */
	public function generate($pageId, $strDomain=null, $strHost=null)
	{
		// Add a log entry
		if ($strDomain !== null || $strHost !== null)
		{
			$this->log('Page ID "' . $pageId . '" can only be accessed via domain "' . $strDomain . '" (current request via "' . $strHost . '")', 'PageError404 generate()', TL_ERROR);
		}
		elseif ($pageId != 'favicon.ico' && $pageId != 'robots.txt')
		{
			$this->log('No active page for page ID "' . $pageId . '", host "' . \Environment::get('host') . '" and languages "' . implode(', ', \Environment::get('httpAcceptLanguage')) . '" (' . \Environment::get('base') . \Environment::get('request') . ')', 'PageError404 generate()', TL_ERROR);
		}

		// Check the search index (see #3761)
		\Search::removeEntry(\Environment::get('request'));

		// Find the matching root page
		$objRootPage = $this->getRootPageFromUrl();

		// Forward if the language should be but is not set (see #4028)
		if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'])
		{
			// Get the request string without the index.php fragment
			if (\Environment::get('request') == 'index.php')
			{
				$strRequest = '';
			}
			else
			{
				$strRequest = str_replace('index.php/', '', \Environment::get('request'));
			}

			// Only redirect if there is no language fragment (see #4669)
			if ($strRequest != '' && !preg_match('@^[a-z]{2}/@', $strRequest))
			{
				$this->redirect($objRootPage->language . '/' . \Environment::get('request'), 301);
			}
		}

		// Look for an 404 page
		$obj404 = \PageModel::find404ByPid($objRootPage->id);

		// Die if there is no page at all
		if ($obj404 === null)
		{
			header('HTTP/1.1 404 Not Found');
			die('Page not found');
		}

		// Generate the error page
		if (!$obj404->autoforward || !$obj404->jumpTo)
		{
			global $objPage;

			$objPage = $this->getPageDetails($obj404);
			$objHandler = new $GLOBALS['TL_PTY']['regular']();

			header('HTTP/1.1 404 Not Found');
			$objHandler->generate($objPage);

			exit;
		}

		// Forward to another page
		$objNextPage = \PageModel::findPublishedById($obj404->jumpTo);

		if ($objNextPage === null)
		{
			header('HTTP/1.1 404 Not Found');
			$this->log('Forward page ID "' . $obj404->jumpTo . '" does not exist', 'PageError404 generate()', TL_ERROR);
			die('Forward page not found');
		}

		$this->redirect($this->generateFrontendUrl($objNextPage->row(), null, $objRootPage->language), (($obj404->redirect == 'temporary') ? 302 : 301));
	}
}
