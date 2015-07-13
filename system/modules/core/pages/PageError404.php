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
 * Provide methods to handle an error 404 page.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class PageError404 extends \Frontend
{

	/**
	 * Generate an error 404 page
	 *
	 * @param integer $pageId
	 * @param string  $strDomain
	 * @param string  $strHost
	 * @param boolean $blnUnusedGet
	 */
	public function generate($pageId, $strDomain=null, $strHost=null, $blnUnusedGet=false)
	{
		// Add a log entry
		if ($blnUnusedGet)
		{
			$this->log('The request for page ID "' . $pageId . '" contained unused GET parameters: "' . implode('", "', \Input::getUnusedGet()) . '" (' . \Environment::get('base') . \Environment::get('request') . ')', __METHOD__, TL_ERROR);
		}
		elseif ($strDomain !== null || $strHost !== null)
		{
			$this->log('Page ID "' . $pageId . '" was requested via "' . $strHost . '" but can only be accessed via "' . $strDomain . '" (' . \Environment::get('base') . \Environment::get('request') . ')', __METHOD__, TL_ERROR);
		}
		elseif ($pageId != 'favicon.ico' && $pageId != 'robots.txt')
		{
			$this->log('No active page for page ID "' . $pageId . '", host "' . \Environment::get('host') . '" and languages "' . implode(', ', \Environment::get('httpAcceptLanguage')) . '" (' . \Environment::get('base') . \Environment::get('request') . ')', __METHOD__, TL_ERROR);
		}

		// Check the search index (see #3761)
		\Search::removeEntry(\Environment::get('request'));

		// Find the matching root page
		$objRootPage = $this->getRootPageFromUrl();

		// Forward if the language should be but is not set (see #4028)
		if (\Config::get('addLanguageToUrl'))
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
			if ($strRequest != '' && !preg_match('@^[a-z]{2}(\-[A-Z]{2})?/@', $strRequest))
			{
				// Handle language fragments without trailing slash (see #7666)
				if (preg_match('@^[a-z]{2}(\-[A-Z]{2})?$@', $strRequest))
				{
					$this->redirect(($GLOBALS['TL_CONFIG']['rewriteURL'] ? '' : 'index.php/') . $strRequest . '/', 301);
				}
				else
				{
					$this->redirect(($GLOBALS['TL_CONFIG']['rewriteURL'] ? '' : 'index.php/') . $objRootPage->language . '/' . $strRequest, 301);
				}
			}
		}

		// Look for a 404 page
		$obj404 = \PageModel::find404ByPid($objRootPage->id);

		// Die if there is no page at all
		if (null === $obj404)
		{
			header('HTTP/1.1 404 Not Found');
			die_nicely('be_no_page', 'Page not found');
		}

		// Generate the error page
		if (!$obj404->autoforward || !$obj404->jumpTo)
		{
			/** @var \PageModel $objPage */
			global $objPage;

			$objPage = $obj404->loadDetails();

			/** @var \PageRegular $objHandler */
			$objHandler = new $GLOBALS['TL_PTY']['regular']();

			header('HTTP/1.1 404 Not Found');
			$objHandler->generate($objPage);

			exit;
		}

		// Forward to another page
		$objNextPage = \PageModel::findPublishedById($obj404->jumpTo);

		if (null === $objNextPage)
		{
			header('HTTP/1.1 404 Not Found');
			$this->log('Forward page ID "' . $obj404->jumpTo . '" does not exist', __METHOD__, TL_ERROR);
			die_nicely('be_no_forward', 'Forward page not found');
		}

		$this->redirect($this->generateFrontendUrl($objNextPage->row(), null, $objRootPage->language), (($obj404->redirect == 'temporary') ? 302 : 301));
	}
}
