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
 * Class ModuleBreadcrumb
 *
 * Front end module "breadcrumb".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
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
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;

		$pages = array();
		$items = array();
		$pageId = $objPage->id;
		$type = null;

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
			$time = time();

			// Get the first page
			$objFirstPage = $this->Database->prepare("SELECT * FROM tl_page WHERE pid=? AND type!='root' AND type!='error_403' AND type!='error_404'" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY sorting")
										   ->limit(1)
										   ->execute($objPages->id);

			$items[] = array
			(
				'isRoot' => true,
				'isActive' => false,
				'href' => (($objFirstPage->numRows) ? $this->generateFrontendUrl($objFirstPage->fetchAssoc()) : $this->Environment->base),
				'title' => (strlen($objPages->pageTitle) ? specialchars($objPages->pageTitle, true) : specialchars($objPages->title, true)),
				'link' => $objPages->title,
				'data' => $objFirstPage->row()
			);

			array_pop($pages);
		}

		// Build the breadcrumb menu
		for ($i=(count($pages)-1); $i>0; $i--)
		{
			if (($pages[$i]['hide'] && !$this->showHidden) || (!$pages[$i]['published'] && !BE_USER_LOGGED_IN))
			{
				continue;
			}

			// Get href
			switch ($pages[$i]['type'])
			{
				case 'redirect':
					$href = $pages[$i]['url'];

					if (strncasecmp($href, 'mailto:', 7) === 0)
					{
						$this->import('String');
						$href = $this->String->encodeEmail($href);
					}
					break;

				case 'forward':
					$objNext = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
											  ->limit(1)
											  ->execute($pages[$i]['jumpTo']);

					if ($objNext->numRows)
					{
						$href = $this->generateFrontendUrl($objNext->fetchAssoc());
						break;
					}
					// DO NOT ADD A break; STATEMENT

				default:
					$href = $this->generateFrontendUrl($pages[$i]);
					break;
			}

			$items[] = array
			(
				'isRoot' => false,
				'isActive' => false,
				'href' => $href,
				'title' => (strlen($pages[$i]['pageTitle']) ? specialchars($pages[$i]['pageTitle'], true) : specialchars($pages[$i]['title'], true)),
				'link' => $pages[$i]['title'],
				'data' => $pages[$i]
			);
		}

		// Active article
		if (strlen($this->Input->get('articles')))
		{
			$items[] = array
			(
				'isRoot' => false,
				'isActive' => false,
				'href' => $this->generateFrontendUrl($pages[0]),
				'title' => (strlen($pages[0]['pageTitle']) ? specialchars($pages[0]['pageTitle'], true) : specialchars($pages[0]['title'], true)),
				'link' => $pages[0]['title'],
				'data' => $pages[0]
			);

			list($strSection, $strArticle) = explode(':', $this->Input->get('articles'));

			if ($strArticle === null)
			{
				$strArticle = $strSection;
			}

			// Get article title
			$objArticle = $this->Database->prepare("SELECT * FROM tl_article WHERE id=? OR alias=?")
										 ->limit(1)
										 ->execute((is_numeric($strArticle) ? $strArticle : 0), $strArticle);

			if ($objArticle->numRows)
			{
				$items[] = array
				(
					'isRoot' => false,
					'isActive' => true,
					'title' => specialchars($objArticle->title, true),
					'link' => $objArticle->title,
					'data' => $objArticle->row()
				);
			}
		}

		// Active page
		else
		{
			$items[] = array
			(
				'isRoot' => false,
				'isActive' => true,
				'title' => (strlen($pages[0]['pageTitle']) ? specialchars($pages[0]['pageTitle']) : specialchars($pages[0]['title'])),
				'link' => $pages[0]['title'],
				'data' => $pages[0]
			);
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['generateBreadcrumb']) && is_array($GLOBALS['TL_HOOKS']['generateBreadcrumb']))
		{
			foreach ($GLOBALS['TL_HOOKS']['generateBreadcrumb'] as $callback)
			{
				$this->import($callback[0]);
				$items = $this->$callback[0]->$callback[1]($items, $this);
			}
		}

		$this->Template->items = $items;
	}
}

?>