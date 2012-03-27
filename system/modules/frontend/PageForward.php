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
 * Class PageForward
 *
 * Provide methods to handle a forward page.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class PageForward extends \Frontend
{

	/**
	 * Redirect to an internal page
	 * @param object
	 * @return void
	 */
	public function generate($objPage)
	{
		// Forward to the jumpTo or first published page
		if ($objPage->jumpTo)
		{
			$objNextPage = \PageModel::findPublishedById($objPage->jumpTo);
		}
		else
		{
			$objNextPage = \PageModel::findFirstPublishedRegularByPid($objPage->id);
		}

		// Forward page does not exist
		if ($objNextPage === null)
		{
			header('HTTP/1.1 404 Not Found');
			$this->log('Forward page ID "' . $objPage->jumpTo . '" does not exist', 'PageForward generate()', TL_ERROR);
			die('Forward page not found');
		}

		$strGet = '';

		// Add $_GET parameters
		if (is_array($_GET) && !empty($_GET))
		{
			foreach (array_keys($_GET) as $key)
			{
				if ($GLOBALS['TL_CONFIG']['disableAlias'] && $key == 'id')
				{
					continue;
				}

				if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'] && $key == 'language')
				{
					continue;
				}

				$strGet .= '/' . $key . '/' . $this->Input->get($key);
			}
		}

		$this->redirect($this->generateFrontendUrl($objNextPage->row(), $strGet), (($objPage->redirect == 'temporary') ? 302 : 301));
	}
}
