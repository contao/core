<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class PageRoot
 *
 * Provide methods to handle a website root page.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class PageRoot extends Frontend
{

	/**
	 * Redirect to the first active regular page
	 * @param integer
	 * @param boolean
	 * @return integer
	 */
	public function generate($pageId, $blnReturn=false)
	{
		$time = time();

		// Get the first active page
		$objNextPage = $this->Database->prepare("SELECT id, alias, language FROM tl_page WHERE pid=? AND type!='root' AND type!='error_403' AND type!='error_404'" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY sorting")
									  ->limit(1)
									  ->execute($pageId);

		// No active pages yet
		if ($objNextPage->numRows < 1)
		{
			header('HTTP/1.1 404 Not Found');
			$this->log('No active page found under root page "' . $pageId . '")', 'PageRoot generate()', TL_ERROR);
			die('No active pages found');
		}

		// Only return the page ID
		if ($blnReturn)
		{
			return $objNextPage->id;
		}

		$this->redirect($this->generateFrontendUrl($objNextPage->fetchAssoc()));
	}
}

?>