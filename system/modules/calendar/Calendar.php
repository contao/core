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
 * @package    Calendar
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class Calendar
 *
 * Provide methods regarding calendars.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class Calendar extends \Frontend
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
		$objCalendar = \CalendarModel::findByPk($intId);

		if ($objCalendar === null || !$objCalendar->makeFeed)
		{
			return;
		}

		$objCalendar->feedName = ($objCalendar->alias != '') ? $objCalendar->alias : 'calendar' . $objCalendar->id;

		// Delete XML file
		if ($this->Input->get('act') == 'delete' || $objCalendar->protected)
		{
			$this->import('Files');
			$this->Files->delete('share/' . $objCalendar->feedName . '.xml');
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
		$objCalendar = \CalendarModel::findUnprotectedWithFeeds();

		if ($objCalendar !== null)
		{
			while ($objCalendar->next())
			{
				$objCalendar->feedName = ($objCalendar->alias != '') ? $objCalendar->alias : 'calendar' . $objCalendar->id;
				$this->generateFiles($objCalendar->row());
				$this->log('Generated event feed "' . $objCalendar->feedName . '.xml"', 'Calendar generateFeeds()', TL_CRON);
			}
		}
	}


	/**
	 * Generate an XML file and save it to the root directory
	 * @param array
	 */
	protected function generateFiles($arrArchive)
	{
		if (!$arrArchive['jumpTo']['id'])
		{
			return;
		}

		$time = time();
		$this->arrEvents = array();
		$strType = ($arrArchive['format'] == 'atom') ? 'generateAtom' : 'generateRss';
		$strLink = ($arrArchive['feedBase'] != '') ? $arrArchive['feedBase'] : $this->Environment->base;
		$strFile = $arrArchive['feedName'];

		$objFeed = new \Feed($strFile);

		$objFeed->link = $strLink;
		$objFeed->title = $arrArchive['title'];
		$objFeed->description = $arrArchive['description'];
		$objFeed->language = $arrArchive['language'];
		$objFeed->published = $arrArchive['tstamp'];

		// Get the upcoming events
		$objArticle = \CalendarEventsModel::findUpcomingByPid($arrArchive['id'], $arrArchive['maxItems']);

		if ($objArticle === null)
		{
			return;
		}

		$objParent = $this->getPageDetails($arrArchive['jumpTo']['id']);
		$strUrl = $strLink . $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/events/%s'), $objParent->language);

		// Parse items
		while ($objArticle->next())
		{
			$this->addEvent($objArticle, $objArticle->startTime, $objArticle->endTime, $strUrl, $strLink);

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
						$this->addEvent($objArticle, $objArticle->startTime, $objArticle->endTime, $strUrl, $strLink);
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

					$objItem = new \FeedItem();

					$objItem->title = $event['title'];
					$objItem->link = $event['link'];
					$objItem->published = $event['published'];
					$objItem->start = $event['start'];
					$objItem->end = $event['end'];
					$objItem->author = $event['authorName'];

					// Prepare the description
					$strDescription = ($arrArchive['source'] == 'source_text') ? $event['description'] : $event['teaser'];
					$strDescription = $this->replaceInsertTags($strDescription);
					$objItem->description = $this->convertRelativeUrls($strDescription, $strLink);

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
		$objRss = new \File('share/' . $strFile . '.xml');
		$objRss->write($this->replaceInsertTags($objFeed->$strType()));
		$objRss->close();
	}


	/**
	 * Add events to the indexer
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

		// Get all calendars
		$objCalendar = \CalendarModel::findBy('protected', '');

		// Walk through each calendar
		if ($objCalendar !== null)
		{
			while ($objCalendar->next())
			{
				// Skip events without target page
				if ($objCalendar->jumpTo['id'] < 1)
				{
					continue;
				}

				// Skip events outside the root nodes
				if (!empty($arrRoot) && !in_array($objCalendar->jumpTo['id'], $arrRoot))
				{
					continue;
				}

				// Get the URL of the jumpTo page
				if (!isset($arrProcessed[$objCalendar->jumpTo['id']]))
				{
					$domain = $this->Environment->base;
					$objParent = $this->getPageDetails($objCalendar->jumpTo['id']);

					if ($objParent->domain != '')
					{
						$domain = ($this->Environment->ssl ? 'https://' : 'http://') . $objParent->domain . TL_PATH . '/';
					}

					$arrProcessed[$objCalendar->jumpTo['id']] = $domain . $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/events/%s'), $objParent->language);
				}

				$strUrl = $arrProcessed[$objCalendar->jumpTo['id']];

				// Get the items
				$objEvents = \CalendarEventsModel::findPublishedDefaultByPid($objCalendar->id);

				if ($objEvents !== null)
				{
					while ($objEvents->next())
					{
						$arrPages[] = sprintf($strUrl, (($objEvents->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objEvents->alias : $objEvents->id));
					}
				}
			}
		}

		return $arrPages;
	}


	/**
	 * Add an event to the array of active events
	 * @param Database_Result|Model
	 * @param integer
	 * @param integer
	 * @param string
	 * @param string
	 */
	protected function addEvent($objEvent, $intStart, $intEnd, $strUrl, $strLink)
	{
		if ($intStart < time())
		{
			return;
		}

		global $objPage;
		$this->import('String');

		$intKey = date('Ymd', $intStart);
		$span = self::calculateSpan($intStart, $intEnd);
		$format = $objEvent->addTime ? 'datimFormat' : 'dateFormat';

		// Add date
		if ($span > 0)
		{
			$title = $this->parseDate($objPage->$format, $intStart) . ' - ' . $this->parseDate($objPage->$format, $intEnd);
		}
		else
		{
			$title = $this->parseDate($objPage->dateFormat, $intStart) . ($objEvent->addTime ? ' (' . $this->parseDate($objPage->timeFormat, $intStart) . (($intStart < $intEnd) ? ' - ' . $this->parseDate($objPage->timeFormat, $intEnd) : '') . ')' : '');
		}

		// Add title and link
		$title .= ' ' . $objEvent->title;
		$link = '';

		switch ($objEvent->source)
		{
			case 'external':
				$link = $objEvent->url;
				break;

			case 'internal':
				$objEvent->getRelated('jumpTo');

				if ($objEvent->jumpTo['id'] > 0)
				{
					$link = $strLink . $this->generateFrontendUrl($objEvent->jumpTo);
				}
				break;

			case 'article':
				$objArticle = \ArticleModel::findByPk($objEvent->articleId, true);

				if ($objArticle !== null)
				{
					return ampersand($this->generateFrontendUrl($objArticle->pid, '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id)));
				}
				break;
		}

		// Link to default page
		if ($link == '')
		{
			$link = sprintf($strUrl, (($objEvent->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objEvent->alias : $objEvent->id));
		}

		// Clean the RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$objEvent->teaser = $this->String->toXhtml($objEvent->teaser);
		}
		else
		{
			$objEvent->teaser = $this->String->toHtml5($objEvent->teaser);
		}

		$arrEvent = array
		(
			'title' => $title,
			'description' => $objEvent->details,
			'teaser' => $objEvent->teaser,
			'link' => $link,
			'published' => $objEvent->tstamp,
			'authorName' => $objEvent->authorName
		);

		// Enclosure
		if ($objEvent->addEnclosure)
		{
			$arrEnclosure = deserialize($objEvent->enclosure, true);

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
		return self::unixToJd($intEnd) - self::unixToJd($intStart);
	}


	/**
	 * Convert a UNIX timestamp to a Julian day
	 * @param integer
	 * @return integer
	 */
	public static function unixToJd($tstamp)
	{
		list($year, $month, $day) = explode(',', date('Y,m,d', $tstamp));

		// Make year a positive number
		$year += ($year < 0 ? 4801 : 4800);

		// Adjust the start of the year
		if ($month > 2)
		{
			$month -= 3;
		}
		else
		{
			$month += 9;
			--$year;
		}

		$sdn  = floor((floor($year / 100) * 146097) / 4);
		$sdn += floor((($year % 100) * 1461) / 4);
		$sdn += floor(($month * 153 + 2) / 5);
		$sdn += $day - 32045;

		return $sdn;
	}
}

?>