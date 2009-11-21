<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleNewsList
 *
 * Front end module "news list".
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Controller
 */
class ModuleNewsList extends ModuleNews
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_newslist';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### NEWS LIST ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->news_archives = $this->sortOutProtected(deserialize($this->news_archives, true));

		// Return if there are no archives
		if (!is_array($this->news_archives) || count($this->news_archives) < 1)
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$time = time();
		$skipFirst = intval($this->skipFirst);
		$offset = 0;
		$limit = null;

		// Maximum number of items
		if ($this->news_numberOfItems > 0)
		{
			$limit = $this->news_numberOfItems;
		}

		// Split results
		if ($this->perPage > 0 && (!isset($limit) || $this->news_numberOfItems > $this->perPage))
		{
			// Get total number of items
			$objTotal = $this->Database->execute("SELECT COUNT(*) AS total FROM tl_news WHERE pid IN(" . implode(',', $this->news_archives) . ")" . ($this->news_featured ? " AND featured=1" : "") . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY date DESC");
			$total = $objTotal->total - $skipFirst;

			// Overall limit
			if (isset($limit))
			{
				$total = min($limit, $total);
			}

			$page = $this->Input->get('page') ? $this->Input->get('page') : 1;

			// Check maximum page number
			if ($page > ($total/$this->perPage))
			{
				$page = ceil($total/$this->perPage);
			}

			// Limit and offset
			$limit = $this->perPage;
			$offset = ($page - 1) * $this->perPage;

			// Overall limit
			if ($offset + $limit > $total)
			{
				$limit = $total - $offset;
			}

			// Add pagination menu
			$objPagination = new Pagination($total, $this->perPage);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		$objArticlesStmt = $this->Database->prepare("SELECT *, author AS authorId, (SELECT title FROM tl_news_archive WHERE tl_news_archive.id=tl_news.pid) AS archive, (SELECT jumpTo FROM tl_news_archive WHERE tl_news_archive.id=tl_news.pid) AS parentJumpTo, (SELECT name FROM tl_user WHERE id=author) AS author FROM tl_news WHERE pid IN(" . implode(',', $this->news_archives) . ")" . ($this->news_featured ? " AND featured=1" : "") . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY date DESC");

		// Limit result
		if (isset($limit))
		{
			$objArticlesStmt->limit($limit + $skipFirst, $offset);
		}

		$objArticles = $objArticlesStmt->execute();

		// Skip first article
		for ($i=0; $i<$skipFirst; $i++)
		{
			$objArticles->next();
		}

		$this->Template->articles = $this->parseArticles($objArticles);
		$this->Template->archives = $this->news_archives;
	}
}

?>