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
 * Class ModuleArticleList
 *
 * Front end module "article list".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleArticleList extends Module
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
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ARTICLE LIST ###';

			return $objTemplate->parse();
		}

		$strBuffer = parent::generate();
		return count($this->Template->articles) ? $strBuffer : '';
	}


	/**
	 * Generate module
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

		$this->Template->request = $this->Environment->request;

		// Show articles of a different page
		if ($this->defineRoot && $this->rootPage > 0)
		{
			$objTarget = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										->limit(1)
										->execute($this->rootPage);

			if ($objTarget->numRows)
			{
				$id = $this->rootPage;
				$this->Template->request = $this->generateFrontendUrl($objTarget->row());
			}
		}

		$time = time();

		// Get published articles
		$objArticles = $this->Database->prepare("SELECT id, title, inColumn, cssID FROM tl_article WHERE pid=? AND inColumn=?" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : "") . " ORDER BY sorting")
									  ->execute($id, $this->inColumn, $time, $time);

		if ($objArticles->numRows < 1)
		{
			return;
		}

		while ($objArticles->next())
		{
			// Skip first article
			if (++$intCount == 1 && $this->skipFirst)
			{
				continue;
			}

			$cssID = deserialize($objArticles->cssID, true);

			$articles[] = array
			(
				'link' => $objArticles->title,
				'title' => specialchars($objArticles->title),
				'id' => strlen($cssID[0]) ? $cssID[0] : standardize($objArticles->title)
			);
		}

		$this->Template->articles = $articles;
	}
}

?>