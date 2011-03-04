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
 * Class PageForward
 *
 * Provide methods to handle a forward page.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class PageForward extends Frontend
{

	/**
	 * Redirect to an internal page
	 * @param object
	 */
	public function generate(Database_Result $objPage)
	{
		// Forward to first active page
		if (!$objPage->jumpTo)
		{
			$time = time();

			$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE pid=? AND type='regular'" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY sorting")
										  ->limit(1)
										  ->execute($objPage->id);
		}

		// Forward to jumpTo page
		else
		{
			$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($objPage->jumpTo);
		}

		// Forward page does not exist
		if ($objNextPage->numRows < 1)
		{
			$this->log('Forward page ID "' . $objPage->jumpTo . '" does not exist', 'PageForward generate()', TL_ERROR);

			header('HTTP/1.1 404 Not Found');
			die('Forward page not found');
		}

		$strGet = '';

		// Add $_GET parameters
		if (is_array($_GET) && count($_GET) > 0)
		{
			foreach (array_keys($_GET) as $key)
			{
				if ($GLOBALS['TL_CONFIG']['disableAlias'] && $key == 'id')
				{
					continue;
				}

				$strGet .= '/' . $key . '/' . $this->Input->get($key);
			}
		}

		$this->redirect($this->generateFrontendUrl($objNextPage->fetchAssoc(), $strGet), (($objPage->redirect == 'temporary') ? 302 : 301));
	}
}

?>