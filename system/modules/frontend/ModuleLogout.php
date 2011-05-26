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
 * Class ModuleLogout
 *
 * Front end module "logout".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleLogout extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate;


	/**
	 * Logout the current user and redirect
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### FRONTEND LOGOUT ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Set last page visited
		if ($this->redirectBack)
		{
			$_SESSION['LAST_PAGE_VISITED'] = $this->getReferer();
		}

		$this->import('FrontendUser', 'User');
		$strRedirect = $this->Environment->base;

		// Redirect to last page visited
		if ($this->redirectBack && strlen($_SESSION['LAST_PAGE_VISITED']))
		{
			$strRedirect = $_SESSION['LAST_PAGE_VISITED'];
		}

		// Redirect to jumpTo page
		elseif (strlen($this->jumpTo))
		{
			$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($this->jumpTo);

			if ($objNextPage->numRows)
			{
				$strRedirect = $this->generateFrontendUrl($objNextPage->fetchAssoc());
			}
		}

		// Log out and redirect
		if ($this->User->logout())
		{
			$this->redirect($strRedirect);
		}

		return '';
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		return;
	}
}

?>