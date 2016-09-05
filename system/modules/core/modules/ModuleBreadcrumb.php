<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Front end module "breadcrumb".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleBreadcrumb extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_breadcrumb';


	/**
	 * Display a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			/** @var \BackendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['breadcrumb'][0]) . ' ###';
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
		/** @var \PageModel $objPage */
		global $objPage;

		$type = null;
		$pageId = $objPage->id;
		$pages = array($objPage);
		$items = array();

		// Get all pages up to the root page
		$objPages = \PageModel::findParentsById($objPage->pid);

		if ($objPages !== null)
		{
			while ($pageId > 0 && $type != 'root' && $objPages->next())
			{
				$type = $objPages->type;
				$pageId = $objPages->pid;
				$pages[] = $objPages->current();
			}
		}

		// Get the first active regular page and display it instead of the root page
		if ($type == 'root')
		{
			$objFirstPage = \PageModel::findFirstPublishedByPid($objPages->id);

			$items[] = array
			(
				'isRoot'   => true,
				'isActive' => false,
				'href'     => (($objFirstPage !== null) ? $objFirstPage->getFrontendUrl() : \Environment::get('base')),
				'title'    => specialchars($objPages->pageTitle ?: $objPages->title, true),
				'link'     => $objPages->title,
				'data'     => $objFirstPage->row(),
				'class'    => ''
			);

			array_pop($pages);
		}

		/** @var \PageModel[] $pages */
		for ($i=(count($pages)-1); $i>0; $i--)
		{
			if (($pages[$i]->hide && !$this->showHidden) || (!$pages[$i]->published && !BE_USER_LOGGED_IN))
			{
				continue;
			}

			// Get href
			switch ($pages[$i]->type)
			{
				case 'redirect':
					$href = $pages[$i]->url;

					if (strncasecmp($href, 'mailto:', 7) === 0)
					{
						$href = \StringUtil::encodeEmail($href);
					}
					break;

				case 'forward':
					if (($objNext = $pages[$i]->getRelated('jumpTo')) !== null || ($objNext = \PageModel::findFirstPublishedRegularByPid($pages[$i]->id)) !== null)
					{
						/** @var \PageModel $objNext */
						$href = $objNext->getFrontendUrl();
						break;
					}
					// DO NOT ADD A break; STATEMENT

				default:
					$href = $pages[$i]->getFrontendUrl();
					break;
			}

			$items[] = array
			(
				'isRoot'   => false,
				'isActive' => false,
				'href'     => $href,
				'title'    => specialchars($pages[$i]->pageTitle ?: $pages[$i]->title, true),
				'link'     => $pages[$i]->title,
				'data'     => $pages[$i]->row(),
				'class'    => ''
			);
		}

		// Active article
		if (isset($_GET['articles']))
		{
			$items[] = array
			(
				'isRoot'   => false,
				'isActive' => false,
				'href'     => $pages[0]->getFrontendUrl(),
				'title'    => specialchars($pages[0]->pageTitle ?: $pages[0]->title, true),
				'link'     => $pages[0]->title,
				'data'     => $pages[0]->row(),
				'class'    => ''
			);

			list($strSection, $strArticle) = explode(':', \Input::get('articles'));

			if ($strArticle === null)
			{
				$strArticle = $strSection;
			}

			$objArticle = \ArticleModel::findByIdOrAlias($strArticle);
			$strAlias = ($objArticle->alias != '' && !\Config::get('disableAlias')) ? $objArticle->alias : $objArticle->id;

			if ($objArticle->inColumn != 'main')
			{
				$strAlias = $objArticle->inColumn . ':' . $strAlias;
			}

			if ($objArticle !== null)
			{
				$items[] = array
				(
					'isRoot'   => false,
					'isActive' => true,
					'href'     => $pages[0]->getFrontendUrl('/articles/' . $strAlias),
					'title'    => specialchars($objArticle->title, true),
					'link'     => $objArticle->title,
					'data'     => $objArticle->row(),
					'class'    => ''
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
				'href'     => $pages[0]->getFrontendUrl(),
				'title'    => specialchars($pages[0]->pageTitle ?: $pages[0]->title),
				'link'     => $pages[0]->title,
				'data'     => $pages[0]->row(),
				'class'    => ''
			);
		}

		// Mark the first element (see #4833)
		$items[0]['class'] = 'first';

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['generateBreadcrumb']) && is_array($GLOBALS['TL_HOOKS']['generateBreadcrumb']))
		{
			foreach ($GLOBALS['TL_HOOKS']['generateBreadcrumb'] as $callback)
			{
				$this->import($callback[0]);
				$items = $this->{$callback[0]}->{$callback[1]}($items, $this);
			}
		}

		$this->Template->items = $items;
	}
}
