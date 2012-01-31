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
 * @package    News
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class News
 *
 * Provide methods regarding news archives.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class News extends \Frontend
{

	/**
	 * Update a particular RSS feed
	 * @param integer
	 */
	public function generateFeed($intId)
	{
		$objArchive = \NewsArchiveModel::findByPk($intId);

		if ($objArchive === null || !$objArchive->makeFeed)
		{
			return;
		}

		$objArchive->feedName = ($objArchive->alias != '') ? $objArchive->alias : 'news' . $objArchive->id;

		// Delete the XML file
		if ($this->Input->get('act') == 'delete' || $objArchive->protected)
		{
			$this->import('Files');
			$this->Files->delete('share/' . $objArchive->feedName . '.xml');
		}

		// Update the XML file
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
		$objArchive = \NewsArchiveModel::findUnprotectedWithFeeds();

		if ($objArchive !== null)
		{
			while ($objArchive->next())
			{
				$objArchive->feedName = ($objArchive->alias != '') ? $objArchive->alias : 'news' . $objArchive->id;
				$this->generateFiles($objArchive->row());
				$this->log('Generated news feed "' . $objArchive->feedName . '.xml"', 'News generateFeeds()', TL_CRON);
			}
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

		$objFeed = new \Feed($strFile);

		$objFeed->link = $strLink;
		$objFeed->title = $arrArchive['title'];
		$objFeed->description = $arrArchive['description'];
		$objFeed->language = $arrArchive['language'];
		$objFeed->published = $arrArchive['tstamp'];

		// Get the items
		$objArticle = \NewsModel::findPublishedByPid($arrArchive['id'], $arrArchive['maxItems']);

		if ($objArticle === null)
		{
			return;
		}

		$objParent = $this->getPageDetails($arrArchive['jumpTo']['id']);
		$strUrl = $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s'), $objParent->language);

		// Parse items
		while ($objArticle->next())
		{
			$objItem = new \FeedItem();

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
		$objRss = new \File('share/' . $strFile . '.xml');
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
		$objArchive = \NewsArchiveModel::findBy('protected', '');

		// Walk through each archive
		if ($objArchive !== null)
		{
			while ($objArchive->next())
			{
				// Skip news archives without target page
				if ($objArchive->jumpTo['id'] < 1)
				{
					continue;
				}

				// Skip news archives outside the root nodes
				if (!empty($arrRoot) && !in_array($objArchive->jumpTo['id'], $arrRoot))
				{
					continue;
				}

				// Get the URL of the jumpTo page
				if (!isset($arrProcessed[$objArchive->jumpTo['id']]))
				{
					$domain = $this->Environment->base;
					$objParent = $this->getPageDetails($objArchive->jumpTo['id']);

					if ($objParent->domain != '')
					{
						$domain = ($this->Environment->ssl ? 'https://' : 'http://') . $objParent->domain . TL_PATH . '/';
					}

					$arrProcessed[$objArchive->jumpTo['id']] = $domain . $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s'), $objParent->language);
				}

				$strUrl = $arrProcessed[$objArchive->jumpTo['id']];

				// Get the items
				$objArticle = \NewsModel::findPublishedDefaultByPid($objArchive->id);

				if ($objArticle !== null)
				{
					while ($objArticle->next())
					{
						$arrPages[] = $this->getLink($objArticle, $strUrl);
					}
				}
			}
		}

		return $arrPages;
	}


	/**
	 * Return the link of a news article
	 * @param Database_Result|Model
	 * @param string
	 * @return string
	 */
	protected function getLink($objItem, $strUrl)
	{
		switch ($objItem->source)
		{
			// Link to an external page
			case 'external':
				return $objItem->url;
				break;

			// Link to an internal page
			case 'internal':
				$objItem->getRelated('jumpTo');

				if ($objItem->jumpTo['id'] > 0)
				{
					return $this->generateFrontendUrl($objItem->jumpTo);
				}
				break;

			// Link to an article
			case 'article':
				$objArticle = \ArticleModel::findByPk($objItem->articleId, true);

				if ($objArticle !== null)
				{
					return ampersand($this->generateFrontendUrl($objArticle->pid, '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id)));
				}
				break;
		}

		// Link to the default page
		return sprintf($strUrl, (($objItem->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objItem->alias : $objItem->id));
	}
}

?>