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
 * Class ModuleQuicklink
 *
 * Front end module "quick link".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleQuicklink extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_quicklink';


	/**
	 * Redirect to the selected page
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### QUICK LINK ###';

			return $objTemplate->parse();
		}

		// Redirect to selected page
		if ($this->Input->post('FORM_SUBMIT') == 'tl_quicklink')
		{
			if (strlen($this->Input->post('target')))
			{
				$this->redirect($this->Input->post('target'));
			}

			$this->reload();
		}

		// Get all pages
		$this->pages = deserialize($this->pages);

		if (!is_array($this->pages) || !strlen($this->pages[0]))
		{
			return '';
		}

		$strBuffer = parent::generate();
		return strlen($this->Template->items) ? $strBuffer : '';
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$time = time();
		$arrPages = array();

		// Get all active pages
		foreach ($this->pages as $intId)
		{
			$objPage = $this->Database->prepare("SELECT id, title, alias FROM tl_page WHERE id=? AND type!=? AND type!=? AND type!=?" . ((FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN) ? " AND guests!=1" : "") . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : ""))
									  ->limit(1)
									  ->execute($intId, 'root', 'error_403', 'error_404', $time, $time);

			if ($objPage->numRows < 1)
			{
				continue;
			}

			$arrPages[] = $objPage->fetchAssoc();
		}

		if (count($arrPages) < 1)
		{
			return;
		}

		$items = array();

		foreach ($arrPages as $arrPage)
		{
			$items[] = array
			(
				'href' => $this->generateFrontendUrl($arrPage),
				'link' => $arrPage['title']
			);
		}

		$this->Template->items = $items;
		$this->Template->request = ampersand($this->Environment->request, ENCODE_AMPERSANDS);
		$this->Template->title = strlen($this->customLabel) ? $this->customLabel :$GLOBALS['TL_LANG']['MSC']['quicklink'];
		$this->Template->button = specialchars($GLOBALS['TL_LANG']['MSC']['go']);
	}
}

?>