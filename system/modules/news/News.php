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
	 * @param boolean
	 * @return void
	 */
	public function generateFeed($intId, $blnIsFeedId=false)
	{
		$objFeed = $blnIsFeedId ? \NewsFeedModel::findByPk($intId) : \NewsFeedModel::findByArchive($intId);

		if ($objFeed === null)
		{
			return;
		}

		$objFeed->feedName = $objFeed->alias ?: 'news' . $objFeed->id;

		// Delete XML file
		if ($this->Input->get('act') == 'delete')
		{
			$this->import('Files');
			$this->Files->delete($objFeed->feedName . '.xml');
		}

		// Update XML file
		else
		{
			$this->generateFiles($objFeed->row());
			$this->log('Generated news feed "' . $objFeed->feedName . '.xml"', 'News generateFeed()', TL_CRON);
		}
	}


	/**
	 * Delete old files and generate all feeds
	 * @return void
	 */
	public function generateFeeds()
	{
		$this->removeOldFeeds();
		$objFeed = \NewsFeedCollection::findAll();

		if ($objFeed !== null)
		{
			while ($objFeed->next())
			{
				$objFeed->feedName = $objFeed->alias ?: 'news' . $objFeed->id;
				$this->generateFiles($objFeed->row());
				$this->log('Generated news feed "' . $objFeed->feedName . '.xml"', 'News generateFeeds()', TL_CRON);
			}
		}
	}


	/**
	 * Generate an XML files and save them to the root directory
	 * @param array
	 * @return void
	 */
	protected function generateFiles($arrFeed)
	{
		$arrArchives = deserialize($arrFeed['archives']);

		if (!is_array($arrArchives) || empty($arrArchives))
		{
			return;
		}

		$strType = ($arrFeed['format'] == 'atom') ? 'generateAtom' : 'generateRss';
		$strLink = $arrFeed['feedBase'] ?: $this->Environment->base;
		$strFile = $arrFeed['feedName'];

		$objFeed = new Feed($strFile);
		$objFeed->link = $strLink;
		$objFeed->title = $arrFeed['title'];
		$objFeed->description = $arrFeed['description'];
		$objFeed->language = $arrFeed['language'];
		$objFeed->published = $arrFeed['tstamp'];

		// Get the items
		if ($arrFeed['maxItems'] > 0)
		{
			$objArticle = \NewsCollection::findPublishedByPids($arrArchives, null, $arrFeed['maxItems']);
		}
		else
		{
			$objArticle = \NewsCollection::findPublishedByPids($arrArchives);
		}

		// Parse the items
		if ($objArticle !== null)
		{
			$arrUrls = array();

			while ($objArticle->next())
			{
				$jumpTo = $objArticle->getRelated('pid')->jumpTo;

				// Get the jumpTo URL
				if (!isset($arrUrls[$jumpTo]))
				{
					$objParent = $this->getPageDetails($jumpTo);
					$arrUrls[$jumpTo] = $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s'), $objParent->language);
				}

				$strUrl = $arrUrls[$jumpTo];
				$objItem = new \FeedItem();

				$objItem->title = $objArticle->headline;
				$objItem->link = (($objArticle->source == 'external') ? '' : $strLink) . $this->getLink($objArticle, $strUrl);
				$objItem->published = $objArticle->date;
				$objItem->author = $objArticle->authorName;

				// Prepare the description
				$strDescription = ($arrFeed['source'] == 'source_text') ? $objArticle->text : $objArticle->teaser;
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
		}

		// Create the file
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

		$arrProcessed = array();

		// Get all news archives
		$objArchive = \NewsArchiveCollection::findByProtected('');

		// Walk through each archive
		if ($objArchive !== null)
		{
			while ($objArchive->next())
			{
				// Skip news archives without target page
				if (!$objArchive->jumpTo)
				{
					continue;
				}

				// Skip news archives outside the root nodes
				if (!empty($arrRoot) && !in_array($objArchive->jumpTo, $arrRoot))
				{
					continue;
				}

				// Get the URL of the jumpTo page
				if (!isset($arrProcessed[$objArchive->jumpTo]))
				{
					$domain = $this->Environment->base;
					$objParent = $this->getPageDetails($objArchive->jumpTo);

					if ($objParent->domain != '')
					{
						$domain = ($this->Environment->ssl ? 'https://' : 'http://') . $objParent->domain . TL_PATH . '/';
					}

					$arrProcessed[$objArchive->jumpTo] = $domain . $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s'), $objParent->language);
				}

				$strUrl = $arrProcessed[$objArchive->jumpTo];

				// Get the items
				$objArticle = \NewsCollection::findPublishedDefaultByPid($objArchive->id);

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
	 * @param object
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
				if (($objTarget = $objItem->getRelated('jumpTo')) !== null)
				{
					return $this->generateFrontendUrl($objTarget->row());
				}
				break;

			// Link to an article
			case 'article':
				if (($objArticle = \ArticleModel::findByPk($objItem->articleId, array('eager'=>true))) !== null)
				{
					return ampersand($this->generateFrontendUrl($objArticle->pid, '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id)));
				}
				break;
		}

		// Link to the default page
		return sprintf($strUrl, (($objItem->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objItem->alias : $objItem->id));
	}


	/**
	 * Return the names of the existing feeds so they are not removed
	 * @return array
	 */
	public function purgeOldFeeds()
	{
		$arrFeeds = array();
		$objFeeds = \NewsFeedCollection::findAll();

		if ($objFeeds !== null)
		{
			while ($objFeeds->next())
			{
				$arrFeeds[] = $objFeeds->alias ?: 'news' . $objFeeds->id;
			}
		}

		return $arrFeeds;
	}
}
