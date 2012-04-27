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
use \ArticleModel, \BackendTemplate, \Environment, \Input, \Module, \PageModel, \String;


/**
 * Class ModuleBreadcrumb
 *
 * Front end module "breadcrumb".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
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
	 * @return void
	 */
	protected function compile()
	{
		global $objPage;

		$type = null;
		$pageId = $objPage->id;
		$pages = array($objPage->row());
		$items = array();

		// Get all pages up to the root page
		$objPages = PageModel::findParentsById($objPage->pid);

		if ($objPages !== null)
		{
			while ($objPages->next() && $pageId > 0 && $type != 'root')
			{
				$type = $objPages->type;
				$pageId = $objPages->pid;
				$pages[] = $objPages->row();
			}
		}

		// Get the first active regular page and display it instead of the root page
		if ($type == 'root')
		{
			$objFirstPage = PageModel::findFirstPublishedByPid($objPages->id);

			$items[] = array
			(
				'isRoot'   => true,
				'isActive' => false,
				'href'     => (($objFirstPage !== null) ? $this->generateFrontendUrl($objFirstPage->row()) : Environment::get('base')),
				'title'    => specialchars($objPages->pageTitle ?: $objPages->title, true),
				'link'     => $objPages->title,
				'data'     => $objFirstPage->row()
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
						$href = String::encodeEmail($href);
					}
					break;

				case 'forward':
					$objNext = PageModel::findPublishedById($pages[$i]['jumpTo']);

					if ($objNext !== null)
					{
						$href = $this->generateFrontendUrl($objNext->row());
						break;
					}
					// DO NOT ADD A break; STATEMENT

				default:
					$href = $this->generateFrontendUrl($pages[$i]);
					break;
			}

			$items[] = array
			(
				'isRoot'   => false,
				'isActive' => false,
				'href'     => $href,
				'title'    => specialchars($pages[$i]['pageTitle'] ?: $pages[$i]['title'], true),
				'link'     => $pages[$i]['title'],
				'data'     => $pages[$i]
			);
		}

		// Active article
		if (isset($_GET['articles']))
		{
			$items[] = array
			(
				'isRoot'   => false,
				'isActive' => false,
				'href'     => $this->generateFrontendUrl($pages[0]),
				'title'    => specialchars($pages[0]['pageTitle'] ?: $pages[0]['title'], true),
				'link'     => $pages[0]['title'],
				'data'     => $pages[0]
			);

			list($strSection, $strArticle) = explode(':', Input::get('articles'));

			if ($strArticle === null)
			{
				$strArticle = $strSection;
			}

			// Get the article title
			$objArticle = ArticleModel::findByIdOrAlias($strArticle);

			if ($objArticle !== null)
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
				'isRoot'   => false,
				'isActive' => true,
				'title'    => specialchars($pages[0]['pageTitle'] ?: $pages[0]['title']),
				'link'     => $pages[0]['title'],
				'data'     => $pages[0]
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
