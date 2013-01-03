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
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Class News
 *
 * Provide methods regarding news archives.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class News extends Frontend
{

	/**
	 * Update a particular RSS feed
	 * @param integer
	 */
	public function generateFeed($intId)
	{
		$objArchive = $this->Database->prepare("SELECT * FROM tl_news_archive WHERE id=? AND makeFeed=?")
									 ->limit(1)
									 ->execute($intId, 1);

		if ($objArchive->numRows < 1)
		{
			return;
		}

		$objArchive->feedName = ($objArchive->alias != '') ? $objArchive->alias : 'news' . $objArchive->id;

		// Delete XML file
		if ($this->Input->get('act') == 'delete' || $objArchive->protected)
		{
			$this->import('Files');
			$this->Files->delete($objArchive->feedName . '.xml');
		}

		// Update XML file
		else
		{
			$this->generateFiles($objArchive->row());
			$this->log('Generated news feed "' . $objArchive->feedName . '.xml"', 'News generateFeed()', TL_CRON);
		}
	}


	/**
	 * Delete old files and generate all feeds
	 */
	public function generateFeeds()
	{
		$this->removeOldFeeds();
		$objArchive = $this->Database->execute("SELECT * FROM tl_news_archive WHERE makeFeed=1 AND protected!=1");

		while ($objArchive->next())
		{
			$objArchive->feedName = ($objArchive->alias != '') ? $objArchive->alias : 'news' . $objArchive->id;

			$this->generateFiles($objArchive->row());
			$this->log('Generated news feed "' . $objArchive->feedName . '.xml"', 'News generateFeeds()', TL_CRON);
		}
	}


	/**
	 * Generate an XML files and save them to the root directory
	 * @param array
	 */
	protected function generateFiles($arrArchive)
	{
		$time = time();
		$strType = ($arrArchive['format'] == 'atom') ? 'generateAtom' : 'generateRss';
		$strLink = ($arrArchive['feedBase'] != '') ? $arrArchive['feedBase'] : $this->Environment->base;
		$strFile = $arrArchive['feedName'];

		$objFeed = new Feed($strFile);

		$objFeed->link = $strLink;
		$objFeed->title = $arrArchive['title'];
		$objFeed->description = $arrArchive['description'];
		$objFeed->language = $arrArchive['language'];
		$objFeed->published = $arrArchive['tstamp'];

		// Get items
		$objArticleStmt = $this->Database->prepare("SELECT *, (SELECT name FROM tl_user u WHERE u.id=n.author) AS authorName FROM tl_news n WHERE pid=? AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1 ORDER BY date DESC");

		if ($arrArchive['maxItems'] > 0)
		{
			$objArticleStmt->limit($arrArchive['maxItems']);
		}

		$objArticle = $objArticleStmt->execute($arrArchive['id']);

		// Get the default URL
		$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									->limit(1)
									->execute($arrArchive['jumpTo']);

		if ($objParent->numRows < 1)
		{
			return;
		}

		$objParent = $this->getPageDetails($objParent->id);
		$strUrl = $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s'), $objParent->language);

		// Parse items
		while ($objArticle->next())
		{
			$objItem = new FeedItem();

			$objItem->title = $objArticle->headline;
			$objItem->link = (($objArticle->source == 'external') ? '' : $strLink) . $this->getLink($objArticle, $strUrl);
			$objItem->published = $objArticle->date;
			$objItem->author = $objArticle->authorName;

			// Prepare the description
			$strDescription = ($arrArchive['source'] == 'source_text') ? $objArticle->text : $objArticle->teaser;
			$strDescription = $this->replaceInsertTags($strDescription);
			$objItem->description = $this->convertRelativeUrls($strDescription, $strLink);

			// Add the article image as enclosure
			if ($objArticle->addImage)
			{
				$objItem->addEnclosure($objArticle->singleSRC);
			}

			// Enclosure
			if ($objArticle->addEnclosure)
			{
				$arrEnclosure = deserialize($objArticle->enclosure, true);

				if (is_array($arrEnclosure))
				{
					foreach ($arrEnclosure as $strEnclosure)
					{
						if (is_file(TL_ROOT . '/' . $strEnclosure))
						{				
							$objItem->addEnclosure($strEnclosure);
						}
					}
				}
			}

			$objFeed->addItem($objItem);
		}

		// Create file
		$objRss = new File($strFile . '.xml');
		$objRss->write($this->replaceInsertTags($objFeed->$strType()));
		$objRss->close();
	}


	/**
	 * Add news items to the indexer
	 * @param array
	 * @param integer
	 * @param boolean
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot=0, $blnIsSitemap=false)
	{
		$arrRoot = array();

		if ($intRoot > 0)
		{
			$arrRoot = $this->getChildRecords($intRoot, 'tl_page');
		}

		$time = time();
		$arrProcessed = array();

		// Get all news archives
		$objArchive = $this->Database->execute("SELECT id, jumpTo FROM tl_news_archive WHERE protected!=1");

		// Walk through each archive
		while ($objArchive->next())
		{
			if (!empty($arrRoot) && !in_array($objArchive->jumpTo, $arrRoot))
			{
				continue;
			}

			// Get the URL of the jumpTo page
			if (!isset($arrProcessed[$objArchive->jumpTo]))
			{
				$arrProcessed[$objArchive->jumpTo] = false;

				// Get the target page
				$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=? AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1 AND noSearch!=1" . ($blnIsSitemap ? " AND sitemap!='map_never'" : ""))
											->limit(1)
											->execute($objArchive->jumpTo);

				// Determin domain
				if ($objParent->numRows)
				{
					$domain = $this->Environment->base;
					$objParent = $this->getPageDetails($objParent->id);

					if ($objParent->domain != '')
					{
						$domain = ($this->Environment->ssl ? 'https://' : 'http://') . $objParent->domain . TL_PATH . '/';
					}

					$arrProcessed[$objArchive->jumpTo] = $domain . $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s'), $objParent->language);
				}
			}

			// Skip items without target page
			if ($arrProcessed[$objArchive->jumpTo] === false)
			{
				continue;
			}

			$strUrl = $arrProcessed[$objArchive->jumpTo];

			// Get items
			$objArticle = $this->Database->prepare("SELECT * FROM tl_news WHERE pid=? AND source='default' AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1 ORDER BY date DESC")
										 ->execute($objArchive->id);

			// Add items to the indexer
			while ($objArticle->next())
			{
				$arrPages[] = $this->getLink($objArticle, $strUrl);
			}
		}

		return $arrPages;
	}


	/**
	 * Return the link of a news article
	 * @param Database_Result
	 * @param string
	 * @return string
	 */
	protected function getLink(Database_Result $objArticle, $strUrl)
	{
		switch ($objArticle->source)
		{
			// Link to an external page
			case 'external':
				return $objArticle->url;
				break;

			// Link to an internal page
			case 'internal':
				$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
											->limit(1)
											->execute($objArticle->jumpTo);

				if ($objParent->numRows)
				{
					return $this->generateFrontendUrl($objParent->row());
				}
				break;

			// Link to an article
			case 'article':
				$objParent = $this->Database->prepare("SELECT a.id AS aId, a.alias AS aAlias, a.title, p.id, p.alias FROM tl_article a, tl_page p WHERE a.pid=p.id AND a.id=?")
											->limit(1)
											->execute($objArticle->articleId);

				if ($objParent->numRows)
				{
					return $this->generateFrontendUrl($objParent->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objParent->aAlias != '') ? $objParent->aAlias : $objParent->aId));
				}
				break;
		}

		// Link to the default page
		return sprintf($strUrl, (($objArticle->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objArticle->alias : $objArticle->id));
	}
}

?>