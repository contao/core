<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Frontend
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class PageError404
 *
 * Provide methods to handle an error 404 page.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class PageError404 extends \Frontend
{

	/**
	 * Generate an error 404 page
	 * @param integer
	 * @param string
	 * @param string
	 * @return void
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
			$this->log('No active page for page ID "' . $pageId . '", host "' . $this->Environment->host . '" and languages "' . implode(', ', $this->Environment->httpAcceptLanguage) . '" (' . $this->Environment->base . $this->Environment->request . ')', 'PageError404 generate()', TL_ERROR);
		}

		// Check the search index (see #3761)
		\Search::removeEntry($this->Environment->request);

		// Look for an 404 page
		$objRootPage = $this->getRootPageFromUrl();
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
