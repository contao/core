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
 * Class ModuleArticlenav
 *
 * Front end module "article list".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleArticlenav extends Module
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
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ARTICLE NAVIGATION ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		global $objPage;
		$id = $objPage->id;
		$time = time();

		$this->objArticles = $this->Database->prepare("SELECT id, alias, title FROM tl_article WHERE pid=? AND inColumn=? AND showTeaser=1" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY sorting")
											->execute($id, $this->strColumn);

		// Return if there are no articles
		if ($this->objArticles->numRows < 1)
		{
			return '';
		}

		// Redirect to the first article if no article is selected
		if (!$this->Input->get('articles'))
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
	 * Generate module
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
			if ($this->Input->get('articles') == $strAlias)
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

?>