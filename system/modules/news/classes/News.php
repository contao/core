<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package News
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class News
 *
 * Provide methods regarding news archives.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 */
class News extends \Frontend
{

	/**
	 * Update a particular RSS feed
	 * @param integer
	 * @param boolean
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
		if (\Input::get('act') == 'delete')
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
	 */
	public function generateFeeds()
	{
		$this->import('Automator');
		$this->Automator->purgeXmlFiles();

		$objFeed = \NewsFeedModel::findAll();

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
	 */
	protected function generateFiles($arrFeed)
	{
		$arrArchives = deserialize($arrFeed['archives']);

		if (!is_array($arrArchives) || empty($arrArchives))
		{
			return;
		}

		$strType = ($arrFeed['format'] == 'atom') ? 'generateAtom' : 'generateRss';
		$strLink = $arrFeed['feedBase'] ?: \Environment::get('base');
		$strFile = $arrFeed['feedName'];

		$objFeed = new \Feed($strFile);
		$objFeed->link = $strLink;
		$objFeed->title = $arrFeed['title'];
		$objFeed->description = $arrFeed['description'];
		$objFeed->language = $arrFeed['language'];
		$objFeed->published = $arrFeed['tstamp'];

		// Get the items
		if ($arrFeed['maxItems'] > 0)
		{
			$objArticle = \NewsModel::findPublishedByPids($arrArchives, null, $arrFeed['maxItems']);
		}
		else
		{
			$objArticle = \NewsModel::findPublishedByPids($arrArchives);
		}

		// Parse the items
		if ($objArticle !== null)
		{
			$arrUrls = array();

			while ($objArticle->next())
			{
				$jumpTo = $objArticle->getRelated('pid')->jumpTo;

				// No jumpTo page set (see #4784)
				if (!$jumpTo)
				{
					continue;
				}

				// Get the jumpTo URL
				if (!isset($arrUrls[$jumpTo]))
				{
					$objParent = $this->getPageDetails($jumpTo);
					$arrUrls[$jumpTo] = $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s'), $objParent->language);
				}

				$strUrl = $arrUrls[$jumpTo];
				$objItem = new \FeedItem();

				$objItem->title = $objArticle->headline;
				$objItem->link = $this->getLink($objArticle, $strUrl, $strLink);
				$objItem->published = $objArticle->date;
				$objItem->author = $objArticle->authorName;

				// Prepare the description
				if ($arrFeed['source'] == 'source_text')
				{
					$strDescription = '';
					$objElement = \ContentModel::findPublishedByPidAndTable($objArticle->id, 'tl_news');

					if ($objElement !== null)
					{
						while ($objElement->next())
						{
							$strDescription .= $this->getContentElement($objElement->id);
						}
					}
				}
				else
				{
					$strDescription = $objArticle->teaser;
				}

				$strDescription = $this->replaceInsertTags($strDescription);
				$objItem->description = $this->convertRelativeUrls($strDescription, $strLink);

				// Add the article image as enclosure
				if ($objArticle->addImage)
				{
					$objFile = \FilesModel::findByPk($objArticle->singleSRC);

					if ($objFile !== null)
					{
						$objItem->addEnclosure($objFile->path);
					}
				}

				// Enclosures
				if ($objArticle->addEnclosure)
				{
					$arrEnclosure = deserialize($objArticle->enclosure, true);

					if (is_array($arrEnclosure))
					{
						$objFile = \FilesModel::findMultipleByIds($arrEnclosure);

						while ($objFile->next())
						{
							$objItem->addEnclosure($objFile->path);
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
			$arrRoot = $this->Database->getChildRecords($intRoot, 'tl_page');
		}

		$arrProcessed = array();

		// Get all news archives
		$objArchive = \NewsArchiveModel::findByProtected('');

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
					$domain = \Environment::get('base');
					$objParent = $this->getPageDetails($objArchive->jumpTo);

					// The target page does not exist
					if ($objParent === null)
					{
						continue;
					}

					if ($objParent->domain != '')
					{
						$domain = (\Environment::get('ssl') ? 'https://' : 'http://') . $objParent->domain . TL_PATH . '/';
					}

					$arrProcessed[$objArchive->jumpTo] = $domain . $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s'), $objParent->language);
				}

				$strUrl = $arrProcessed[$objArchive->jumpTo];

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
	 * @param object
	 * @param string
	 * @param string
	 * @return string
	 */
	protected function getLink($objItem, $strUrl, $strBase='')
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
					return $strBase . $this->generateFrontendUrl($objTarget->row());
				}
				break;

			// Link to an article
			case 'article':
				if (($objArticle = \ArticleModel::findByPk($objItem->articleId, array('eager'=>true))) !== null)
				{
					return $strBase . ampersand($this->generateFrontendUrl($objArticle->getRelated('pid')->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id)));
				}
				break;
		}

		// Link to the default page
		return $strBase . sprintf($strUrl, (($objItem->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objItem->alias : $objItem->id));
	}


	/**
	 * Return the names of the existing feeds so they are not removed
	 * @return array
	 */
	public function purgeOldFeeds()
	{
		$arrFeeds = array();
		$objFeeds = \NewsFeedModel::findAll();

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
