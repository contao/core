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
 * Class PageError403
 *
 * Provide methods to handle an error 403 page.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class PageError403 extends Frontend
{

	/**
	 * Generate an error 403 page
	 * @param integer
	 * @param integer
	 */
	public function generate($pageId, $rootId=false)
	{
		$time = time();

		// Add a log entry
		$this->log('Access to page ID "' . $pageId . '" denied', 'PageError403 generate()', TL_ERROR);

		// Use the given $rootId if available (thanks to Andreas Schempp)
		if (!$rootId)
		{
			$rootId = $this->getRootIdFromUrl();
		}

		// Look for an error_403 page within the website root
		$obj403 = $this->Database->prepare("SELECT * FROM tl_page WHERE type=? AND pid=?" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : ""))
								 ->limit(1)
								 ->execute('error_403', $rootId, $time, $time);

		// Look for a global error_403 page
		if ($obj403->numRows < 1)
		{
			$obj403 = $this->Database->prepare("SELECT * FROM tl_page WHERE type='error_403' AND pid=0" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : ""))
									 ->limit(1)
									 ->execute();
		}

		// Die if there is no page at all
		if ($obj403->numRows < 1)
		{
			header('HTTP/1.1 403 Forbidden');
			die('Forbidden');
		}

		// Generate the error page
		if (!$obj403->autoforward || $obj403->jumpTo < 1)
		{
			global $objPage;

			$objPage = $this->getPageDetails($obj403->id);
			$objHandler = new $GLOBALS['TL_PTY']['regular']();

			header('HTTP/1.1 403 Forbidden');
			$objHandler->generate($objPage);

			exit;
		}

		// Forward to another page
		$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									  ->limit(1)
									  ->execute($obj403->jumpTo);

		if ($objNextPage->numRows)
		{
			$this->redirect($this->generateFrontendUrl($objNextPage->fetchAssoc()), (($obj403->redirect == 'temporary') ? 302 : 301));
		}

		$this->log('Forward page ID "' . $obj403->jumpTo . '" does not exist', 'PageError403 generate()', TL_ERROR);

		header('HTTP/1.1 403 Forbidden');
		die('Forward page not found');
	}
}

?>