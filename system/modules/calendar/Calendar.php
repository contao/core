<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Class Calendar
 *
 * Provide methods regarding calendars.
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class Calendar extends Frontend
{

	/**
	 * Current events
	 * @var array
	 */
	protected $arrEvents = array();


	/**
	 * Update a particular RSS feed
	 * @param integer
	 */
	public function generateFeed($intId)
	{
		$objCalendar = $this->Database->prepare("SELECT * FROM tl_calendar WHERE id=? AND makeFeed=?")
									  ->limit(1)
									  ->execute($intId, 1);

		if ($objCalendar->numRows < 1)
		{
			return;
		}

		$objCalendar->feedName = strlen($objCalendar->alias) ? $objCalendar->alias : 'calendar' . $objCalendar->id;

		// Delete XML file
		if ($this->Input->get('act') == 'delete')
		{
			$this->import('Files');
			$this->Files->delete($objCalendar->feedName . '.xml');
		}

		// Update XML file
		else
		{
			$this->generateFiles($objCalendar->row());
			$this->log('Generated event feed "' . $objCalendar->feedName . '.xml"', 'Calendar generateFeed()', TL_CRON);
		}
	}


	/**
	 * Delete old files and generate all feeds
	 */
	public function generateFeeds()
	{
		$this->removeOldFeeds();
		$objCalendar = $this->Database->execute("SELECT * FROM tl_calendar WHERE makeFeed=1");

		while ($objCalendar->next())
		{
			$objCalendar->feedName = strlen($objCalendar->alias) ? $objCalendar->alias : 'calendar' . $objCalendar->id;

			$this->generateFiles($objCalendar->row());
			$this->log('Generated event feed "' . $objCalendar->feedName . '.xml"', 'Calendar generateFeeds()', TL_CRON);
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
		$strLink = strlen($arrArchive['feedBase']) ? $arrArchive['feedBase'] : $this->Environment->base;
		$strFile = $arrArchive['feedName'];

		$objFeed = new Feed($strFile);

		$objFeed->link = $strLink;
		$objFeed->title = $arrArchive['title'];
		$objFeed->description = $arrArchive['description'];
		$objFeed->language = $arrArchive['language'];
		$objFeed->published = $arrArchive['tstamp'];

		// Get upcoming events
		$objArticleStmt = $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE pid=? AND (startTime>=? OR (recurring=1 AND (recurrences=0 OR repeatEnd>=?))) AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1 ORDER BY startTime");

		if ($arrArchive['maxItems'] > 0)
		{
			$objArticleStmt->limit($arrArchive['maxItems']);
		}

		$objArticle = $objArticleStmt->execute($arrArchive['id'], $time, $time, $time, $time);

		// Get default URL
		$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									->limit(1)
									->execute($arrArchive['jumpTo']);

		$strUrl = $strLink . $this->generateFrontendUrl($objParent->fetchAssoc(), '/events/%s');

		// Parse items
		while ($objArticle->next())
		{
			$this->addEvent($objArticle, $objArticle->startTime, $objArticle->endTime, $strUrl);

			// Recurring events
			if ($objArticle->recurring)
			{
				$count = 0;
				$arrRepeat = deserialize($objArticle->repeatEach);

				// Do not include more than 20 recurrences
				while ($count++ < 20)
				{
					if ($objArticle->recurrences > 0 && $count >= $objArticle->recurrences)
					{
						break;
					}

					$arg = $arrRepeat['value'];
					$unit = $arrRepeat['unit'];

					$strtotime = '+ ' . $arg . ' ' . $unit;

					$objArticle->startTime = strtotime($strtotime, $objArticle->startTime);
					$objArticle->endTime = strtotime($strtotime, $objArticle->endTime);

					if ($objArticle->startTime >= $time)
					{
						$this->addEvent($objArticle, $objArticle->startTime, $objArticle->endTime, $strUrl);
					}
				}
			}
		}

		$count = 0;
		ksort($this->arrEvents);

		// Add feed items
		foreach ($this->arrEvents as $days)
		{
			foreach ($days as $events)
			{
				foreach ($events as $event)
				{
					if ($arrArchive['maxItems'] > 0 && $count++ >= $arrArchive['maxItems'])
					{
						break(3);
					}

					$objItem = new FeedItem();

					$objItem->title = $event['title'];
					$objItem->description = ($arrArchive['source'] == 'source_text') ? $event['description'] : $event['teaser'];
					$objItem->link = $event['link'];
					$objItem->published = $event['published'];
					$objItem->start = $event['start'];
					$objItem->end = $event['end'];

					if (is_array($event['enclosure']))
					{
						foreach ($event['enclosure'] as $enclosure)
						{
							$objItem->addEnclosure($enclosure);
						}
					}

					$objFeed->addItem($objItem);
				}
			}
		}

		// Create file
		$objRss = new File($strFile . '.xml');
		$objRss->write($this->replaceInsertTags($objFeed->$strType()));
		$objRss->close();
	}


	/**
	 * Add events to the indexer
	 * @param array
	 * @param integer
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot=0)
	{
		$arrRoot = array();

		if ($intRoot > 0)
		{
			$arrRoot = $this->getChildRecords($intRoot, 'tl_page', true);
		}

		$time = time();
		$arrProcessed = array();

		// Get all calendars
		$objCalendar = $this->Database->prepare("SELECT id, jumpTo FROM tl_calendar WHERE protected!=?")
									  ->execute(1);

		// Walk through each calendar
		while ($objCalendar->next())
		{
			if (is_array($arrRoot) && count($arrRoot) > 0 && !in_array($objCalendar->jumpTo, $arrRoot))
			{
				continue;
			}

			// Get the URL of the jumpTo page
			if (!isset($arrProcessed[$objCalendar->jumpTo]))
			{
				$arrProcessed[$objCalendar->jumpTo] = false;

				// Get target page
				$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=? AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1")
											->limit(1)
											->execute($objCalendar->jumpTo, $time, $time);

				// Determin domain
				if ($objParent->numRows)
				{
					$domain = $this->Environment->base;
					$objParent = $this->getPageDetails($objParent->id);

					if (strlen($objParent->domain))
					{
						$domain = ($this->Environment->ssl ? 'https://' : 'http://') . $objParent->domain . TL_PATH . '/';
					}

					$arrProcessed[$objCalendar->jumpTo] = $domain . $this->generateFrontendUrl($objParent->row(), '/events/%s');
				}
			}

			// Skip events without target page
			if ($arrProcessed[$objCalendar->jumpTo] === false)
			{
				continue;
			}

			$strUrl = $arrProcessed[$objCalendar->jumpTo];

			// Get items
			$objArticle = $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE pid=? AND source=? AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1 ORDER BY startTime DESC")
										 ->execute($objCalendar->id, 'default', $time, $time);

			// Add items to the indexer
			while ($objArticle->next())
			{
				$arrPages[] = sprintf($strUrl, ((strlen($objArticle->alias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objArticle->alias : $objArticle->id));
			}
		}

		return $arrPages;
	}


	/**
	 * Add an event to the array of active events
	 * @param object
	 * @param integer
	 * @param integer
	 * @param string
	 */
	protected function addEvent(Database_Result $objArticle, $intStart, $intEnd, $strUrl)
	{
		if ($intStart < time())
		{
			return;
		}

		$intKey = date('Ymd', $intStart);
		$span = floor(($intEnd - $intStart) / 86400);
		$format = $objArticle->addTime ? 'datimFormat' : 'dateFormat';

		// Add date
		if ($span > 0)
		{
			$title = $this->parseDate($GLOBALS['TL_CONFIG'][$format], $intStart) . ' - ' . $this->parseDate($GLOBALS['TL_CONFIG'][$format], $intEnd);
		}
		else
		{
			$title = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $intStart) . ($objArticle->addTime ? ' (' . $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $intStart) . (($intStart < $intEnd) ? ' - ' . $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $intEnd) : '') . ')' : '');
		}

		// Add title
		$title  .= ' ' . $objArticle->title;

		// Add link
		if ($objArticle->source == 'external')
		{
			$link = $objArticle->url;
		}
		else
		{
			$link = sprintf($strUrl, ((strlen($objArticle->alias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objArticle->alias : $objArticle->id));
		}

		$arrEvent = array
		(
			'title' => $title,
			'description' => $objArticle->details,
			'teaser' => $objArticle->teaser,
			'link' => $link,
			'published' => $intStart
		);

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
						$arrEvent['enclosure'][] = $strEnclosure;
					}
				}
			}
		}

		$this->arrEvents[$intKey][$intStart][] = $arrEvent;
	}


	/**
	 * Calculate the span between two timestamps in days
	 * @param integer
	 * @param integer
	 * @return integer
	 */
	public static function calculateSpan($intStart, $intEnd)
	{
		$blnSummer = date('I', $intStart);
		$intOffset = 0;

		if (date('I', $intEnd) !== $blnSummer)
		{
			$intOffset = $blnSummer ? 3600 : -3600;
		}

		return floor(($intEnd - $intStart - $intOffset) / 86400);
	}
}

?>