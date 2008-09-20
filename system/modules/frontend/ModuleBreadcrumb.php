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
 * Class ModuleBreadcrumb
 *
 * Front end module "breadcrumb".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleBreadcrumb extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_breadcrumb';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### BREADCRUMB NAVIGATION ###';

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		global $objPage;

		$pages = array();
		$items = array();
		$pageId = $objPage->id;

		// Get all pages up to the root page
		do
		{
			$objPages = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
									   ->limit(1)
									   ->execute($pageId);

			$type = $objPages->type;
			$pageId = $objPages->pid;
			$pages[] = $objPages->row();
		}
		while ($pageId > 0 && $type != 'root' && $objPages->numRows);

		// Get the first active regular page and display it instead of the root page
		if ($type == 'root')
		{
			if ($this->includeRoot)
			{
				$time = time();

				// Get first page
				$objFirstPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE pid=? AND type!=? AND type!=? AND type!=?" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : "") . " ORDER BY sorting")
											   ->limit(1)
											   ->execute($objPages->id, 'root', 'error_403', 'error_404', $time, $time);

				$items[] = array
				(
					'isActive' => false,
					'href' => (($objFirstPage->numRows) ? $this->generateFrontendUrl($objFirstPage->fetchAssoc()) : $this->Environment->base),
					'title' => (strlen($objPages->pageTitle) ? specialchars($objPages->pageTitle) : specialchars($objPages->title)),
					'link' => $objPages->title
				);
			}

			array_pop($pages);
		}

		// Link to website root
		elseif ($this->includeRoot)
		{
			$items[] = array
			(
				'isActive' => false,
				'href' => $this->Environment->base,
				'title' => specialchars($GLOBALS['TL_CONFIG']['websiteTitle']),
				'link' => $GLOBALS['TL_CONFIG']['websiteTitle']
			);
		}

		// Build breadcrumb menu
		for ($i=(count($pages)-1); $i>0; $i--)
		{
			if (($pages[$i]['hide'] && !$this->showHidden) || (!$pages[$i]['published'] && !BE_USER_LOGGED_IN))
			{
				continue;
			}

			$items[] = array
			(
				'isActive' => false,
				'href' => $this->generateFrontendUrl($pages[$i]),
				'title' => (strlen($pages[$i]['pageTitle']) ? specialchars($pages[$i]['pageTitle']) : specialchars($pages[$i]['title'])),
				'link' => $pages[$i]['title']
			);
		}

		// Active article
		if (strlen($this->Input->get('articles')))
		{
			$items[] = array
			(
				'isActive' => false,
				'href' => $this->generateFrontendUrl($pages[0]),
				'title' => specialchars($pages[0]['title']),
				'link' => $pages[0]['title']
			);

			// Get article title
			$objArticle = $this->Database->prepare("SELECT title FROM tl_article WHERE id=? OR alias=?")
										 ->limit(1)
										 ->execute((is_numeric($this->Input->get('articles')) ? $this->Input->get('articles') : 0), $this->Input->get('articles'));

			if ($objArticle->numRows)
			{
				$items[] = array
				(
					'isActive' => true,
					'link' => $objArticle->title
				);
			}
		}

		// Active page
		else
		{
			$items[] = array
			(
				'isActive' => true,
				'link' => (strlen($pages[0]['pageTitle']) ? $pages[0]['pageTitle'] : $pages[0]['title'])
			);
		}

		$this->Template->items = $items;
	}
}

?>