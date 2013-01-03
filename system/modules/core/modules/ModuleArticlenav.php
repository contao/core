<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleArticlenav
 *
 * Front end module "article list".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ModuleArticlenav extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_article_nav';

	/**
	 * Articles
	 * @var array
	 */
	protected $objArticles;


	/**
	 * Do not display the module if there are no articles
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ARTICLE NAVIGATION ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		global $objPage;
		$this->objArticles = \ArticleModel::findPublishedWithTeaserByPidAndColumn($objPage->id, $this->strColumn);

		// Return if there are no articles
		if ($this->objArticles === null)
		{
			return '';
		}

		// Redirect to the first article if no article is selected
		if (!\Input::get('articles'))
		{
			if (!$this->loadFirst)
			{
				return '';
			}

			$strAlias = ($this->objArticles->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $this->objArticles->alias : $this->objArticles->id;
			$this->redirect($this->addToUrl('articles=' . $strAlias));
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$intActive = null;
		$articles = array();
		$intCount = 1;

		while ($this->objArticles->next())
		{
			$strAlias = (strlen($this->objArticles->alias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $this->objArticles->alias : $this->objArticles->id;

			// Active article
			if (\Input::get('articles') == $strAlias)
			{
				$articles[] = array
				(
					'isActive' => true,
					'href' => $this->addToUrl('articles=' . $strAlias),
					'title' => specialchars($this->objArticles->title, true),
					'link' => $intCount
				);

				$intActive = ($intCount - 1);
			}

			// Inactive article
			else
			{
				$articles[] = array
				(
					'isActive' => false,
					'href' => $this->addToUrl('articles=' . $strAlias),
					'title' => specialchars($this->objArticles->title, true),
					'link' => $intCount
				);
			}

			++$intCount;
		}

		$this->Template->articles = $articles;
		$total = count($articles);

		// Link to first element
		if ($intActive > 1)
		{
			$this->Template->first = array
			(
				'href' => $articles[0]['href'],
				'title' => $articles[0]['title'],
				'link' => $GLOBALS['TL_LANG']['MSC']['first']
			);
		}

		$key = $intActive - 1;

		// Link to previous element
		if ($intCount > 1 && $key >= 0)
		{
			$this->Template->previous = array
			(
				'href' => $articles[$key]['href'],
				'title' => $articles[$key]['title'],
				'link' => $GLOBALS['TL_LANG']['MSC']['previous']
			);
		}

		$key = $intActive + 1;

		// Link to next element
		if ($intCount > 1 && $key < $total)
		{
			$this->Template->next = array
			(
				'href' => $articles[$key]['href'],
				'title' => $articles[$key]['title'],
				'link' => $GLOBALS['TL_LANG']['MSC']['next']
			);
		}

		$key = $total - 1;

		// Link to last element
		if ($intCount > 1 && $intActive < ($key - 1))
		{
			$this->Template->last = array
			(
				'href' => $articles[$key]['href'],
				'title' => $articles[$key]['title'],
				'link' => $GLOBALS['TL_LANG']['MSC']['last']
			);
		}
	}
}
