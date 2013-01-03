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
 * Class ModuleQuicklink
 *
 * Front end module "quick link".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
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
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Redirect to selected page
		if ($this->Input->post('FORM_SUBMIT') == 'tl_quicklink')
		{
			if (strlen($this->Input->post('target', true)))
			{
				$this->redirect($this->Input->post('target', true));
			}

			$this->reload();
		}

		// Get all pages
		$this->pages = deserialize($this->pages);

		if (!is_array($this->pages) || !strlen($this->pages[0]))
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$time = time();
		$arrPages = array();

		// Get all active pages
		foreach ($this->pages as $intId)
		{
			$objPage = $this->Database->prepare("SELECT id, title, alias, pageTitle FROM tl_page WHERE id=? AND type!='root' AND type!='error_403' AND type!='error_404'" . ((FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN) ? " AND guests!=1" : "") . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : ""))
									  ->limit(1)
									  ->execute($intId);

			if ($objPage->numRows < 1)
			{
				continue;
			}

			$arrPages[] = $objPage->fetchAssoc();
		}

		if (empty($arrPages))
		{
			return;
		}

		$items = array();

		foreach ($arrPages as $arrPage)
		{
			$arrPage['title'] = strip_insert_tags($arrPage['title']);
			$arrPage['pageTitle'] = strip_insert_tags($arrPage['pageTitle']);

			$items[] = array
			(
				'href' => $this->generateFrontendUrl($arrPage),
				'title' => (strlen($arrPage['pageTitle']) ? specialchars($arrPage['pageTitle']) : specialchars($arrPage['title'])),
				'link' => $arrPage['title']
			);
		}

		$this->Template->items = $items;
		$this->Template->request = ampersand($this->Environment->request, true);
		$this->Template->title = strlen($this->customLabel) ? $this->customLabel :$GLOBALS['TL_LANG']['MSC']['quicklink'];
		$this->Template->button = specialchars($GLOBALS['TL_LANG']['MSC']['go']);
	}
}

?>