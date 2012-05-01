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
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;
use \Frontend, \PageModel;


/**
 * Class PageRoot
 *
 * Provide methods to handle a website root page.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Core
 */
class PageRoot extends Frontend
{

	/**
	 * Redirect to the first active regular page
	 * @param integer
	 * @param boolean
	 * @return integer
	 * @return integer
	 */
	public function generate($pageId, $blnReturn=false)
	{
		$objNextPage = PageModel::findFirstPublishedByPid($pageId);

		// No published pages yet
		if ($objNextPage === null)
		{
			header('HTTP/1.1 404 Not Found');
			$this->log('No active page found under root page "' . $pageId . '")', 'PageRoot generate()', TL_ERROR);
			die('No active pages found');
		}

		if (!$blnReturn)
		{
			$this->redirect($this->generateFrontendUrl($objNextPage->row()));
		}

		return $objNextPage->id;
	}
}
