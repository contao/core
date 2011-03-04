<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * Class PageError404
 *
 * Provide methods to handle an error 404 page.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class PageError404 extends Frontend
{

	/**
	 * Generate an error 404 page
	 * @param integer
	 * @param string
	 * @param string
	 */
	public function generate($pageId, $strDomain=null, $strHost=null)
	{
		$time = time();

		// Add a log entry
		if (!is_null($strDomain) || !is_null($strHost))
		{
			$this->log('Page ID "' . $pageId . '" can only be accessed via domain "' . $strDomain . '" (current request via "' . $strHost . '")', 'PageError404 generate()', TL_ERROR);
		}

		elseif ($pageId != 'favicon.ico' && $pageId != 'robots.txt')
		{
			$this->log('No active page for page ID "' . $pageId . '", host "' . $this->Environment->host . '" and languages "' . implode(', ', $this->Environment->httpAcceptLanguage) . '" (' . $this->Environment->base . $this->Environment->request . ')', 'PageError404 generate()', TL_ERROR);
		}

		// Look for an 404 page within the website root
		$obj404 = $this->Database->prepare("SELECT * FROM tl_page WHERE type=? AND pid=?" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : ""))
								 ->limit(1)
								 ->execute('error_404', $this->getRootIdFromUrl(), $time, $time);

		// Look for a global 404 page
		if ($obj404->numRows < 1)
		{
			$obj404 = $this->Database->prepare("SELECT * FROM tl_page WHERE type='error_404' AND pid=0" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : ""))
									 ->limit(1)
									 ->execute();
		}

		// Die if there is no page at all
		if ($obj404->numRows < 1)
		{
			header('HTTP/1.1 404 Not Found');
			die('Page not found');
		}

		// Generate the error page
		if (!$obj404->autoforward || $obj404->jumpTo < 1)
		{
			global $objPage;

			$objPage = $this->getPageDetails($obj404->id);
			$objHandler = new $GLOBALS['TL_PTY']['regular']();

			header('HTTP/1.1 404 Not Found');
			$objHandler->generate($objPage);

			exit;
		}

		// Forward to another page
		$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									  ->limit(1)
									  ->execute($obj404->jumpTo);

		if ($objNextPage->numRows)
		{
			$this->redirect($this->generateFrontendUrl($objNextPage->fetchAssoc()), (($obj404->redirect == 'temporary') ? 302 : 301));
		}

		$this->log('Forward page ID "' . $obj404->jumpTo . '" does not exist', 'PageError404 generate()', TL_ERROR);

		header('HTTP/1.1 404 Not Found');
		die('Forward page not found');
	}
}

?>