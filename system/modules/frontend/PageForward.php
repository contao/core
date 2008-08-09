<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class PageForward
 *
 * Provide methods to handle a forward page.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
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
		$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									  ->limit(1)
									  ->execute($objPage->jumpTo);

		if ($objNextPage->numRows)
		{
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

			$this->redirect($this->generateFrontendUrl($objNextPage->fetchAssoc(), $strGet), ($objPage->redirect == 'temporary'));
		}

		$this->log('Forward page ID "' . $objPage->jumpTo . '" does not exist', 'PageForward generate()', TL_ERROR);

		header('HTTP/1.0 404 Not Found');
		die('Forward page not found');
	}
}

?>