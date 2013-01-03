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
 * Class ModuleArticleList
 *
 * Front end module "article list".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class ModuleArticleList extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_article_list';


	/**
	 * Do not display the module if there are no articles
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ARTICLE LIST ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$strBuffer = parent::generate();
		return !empty($this->Template->articles) ? $strBuffer : '';
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;

		if (!strlen($this->inColumn))
		{
			$this->inColumn = 'main';
		}

		$intCount = 0;
		$articles = array();
		$id = $objPage->id;

		$this->Template->request = \Environment::get('request');

		// Show the articles of a different page
		if ($this->defineRoot && $this->rootPage > 0)
		{
			if (($objTarget = $this->objModel->getRelated('rootPage')) !== null)
			{
				$id = $objTarget->id;
				$this->Template->request = $this->generateFrontendUrl($objTarget->row());
			}
		}

		// Get published articles
		$objArticles = \ArticleModel::findPublishedByPidAndColumn($id, $this->inColumn);

		if ($objArticles === null)
		{
			return;
		}

		while ($objArticles->next())
		{
			// Skip first article
			if (++$intCount <= intval($this->skipFirst))
			{
				continue;
			}

			$cssID = deserialize($objArticles->cssID, true);
			$alias = $objArticles->alias ?: $objArticles->title;

			$articles[] = array
			(
				'link' => $objArticles->title,
				'title' => specialchars($objArticles->title),
				'id' => $cssID[0] ?: standardize($alias),
				'articleId' => $objArticles->id
			);
		}

		$this->Template->articles = $articles;
	}
}
