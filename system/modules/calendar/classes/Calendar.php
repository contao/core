<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Provide methods regarding calendars.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
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
	 *
	 * @param integer $intId
	 */
	public function generateFeed($intId)
	{
		$objCalendar = \CalendarFeedModel::findByPk($intId);

		if ($objCalendar === null)
		{
			return;
		}

		$objCalendar->feedName = $objCalendar->alias ?: 'calendar' . $objCalendar->id;

		// Delete XML file
		if (\Input::get('act') == 'delete')
		{
			$this->import('Files');
			$this->Files->delete('share/' . $objCalendar->feedName . '.xml');
		}

		// Update XML file
		else
		{
			$this->generateFiles($objCalendar->row());
			$this->log('Generated calendar feed "' . $objCalendar->feedName . '.xml"', __METHOD__, TL_CRON);
		}
	}


	/**
	 * Delete old files and generate all feeds
	 */
	public function generateFeeds()
	{
		$this->import('Automator');
		$this->Automator->purgeXmlFiles();

		$objCalendar = \CalendarFeedModel::findAll();

		if ($objCalendar !== null)
		{
			while ($objCalendar->next())
			{
				$objCalendar->feedName = $objCalendar->alias ?: 'calendar' . $objCalendar->id;
				$this->generateFiles($objCalendar->row());
				$this->log('Generated calendar feed "' . $objCalendar->feedName . '.xml"', __METHOD__, TL_CRON);
			}
		}
	}


	/**
	 * Generate all feeds including a certain calendar
	 *
	 * @param integer $intId
	 */
	public function generateFeedsByCalendar($intId)
	{
		$objFeed = \CalendarFeedModel::findByCalendar($intId);

		if ($objFeed !== null)
		{
			while ($objFeed->next())
			{
				$objFeed->feedName = $objFeed->alias ?: 'calendar' . $objFeed->id;

				// Update the XML file
				$this->generateFiles($objFeed->row());
				$this->log('Generated calendar feed "' . $objFeed->feedName . '.xml"', __METHOD__, TL_CRON);
			}
		}
	}


	/**
	 * Generate an XML file and save it to the root directory
	 *
	 * @param array $arrFeed
	 */
	protected function generateFiles($arrFeed)
	{
		$arrCalendars = deserialize($arrFeed['calendars']);

		if (!is_array($arrCalendars) || empty($arrCalendars))
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

		$arrUrls = array();
		$this->arrEvents = array();
		$time = time();

		// Get the upcoming events
		$objArticle = \CalendarEventsModel::findUpcomingByPids($arrCalendars, $arrFeed['maxItems']);

		// Parse the items
		if ($objArticle !== null)
		{
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
					$objParent = \PageModel::findWithDetails($jumpTo);

					// A jumpTo page is set but does no longer exist (see #5781)
					if ($objParent === null)
					{
						$arrUrls[$jumpTo] = false;
					}
					else
					{
						$arrUrls[$jumpTo] = $this->generateFrontendUrl($objParent->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/%s' : '/events/%s'), $objParent->language);
					}
				}

				// Skip the event if it requires a jumpTo URL but there is none
				if ($arrUrls[$jumpTo] === false && $objArticle->source == 'default')
				{
					continue;
				}

				$strUrl = $arrUrls[$jumpTo];
				$this->addEvent($objArticle, $objArticle->startTime, $objArticle->endTime, $strUrl, $strLink);

				// Recurring events
				if ($objArticle->recurring)
				{
					$arrRepeat = deserialize($objArticle->repeatEach);

					if ($arrRepeat['value'] < 1)
					{
						continue;
					}

					$count = 0;
					$intStartTime = $objArticle->startTime;
					$intEndTime = $objArticle->endTime;
					$strtotime = '+ ' . $arrRepeat['value'] . ' ' . $arrRepeat['unit'];

					// Do not include more than 20 recurrences
					while ($count++ < 20)
					{
						if ($objArticle->recurrences > 0 && $count >= $objArticle->recurrences)
						{
							break;
						}

						$intStartTime = strtotime($strtotime, $intStartTime);
						$intEndTime = strtotime($strtotime, $intEndTime);

						if ($intStartTime >= $time)
						{
							$this->addEvent($objArticle, $intStartTime, $intEndTime, $strUrl, $strLink);
						}
					}
				}
			}
		}

		$count = 0;
		ksort($this->arrEvents);

		// Add the feed items
		foreach ($this->arrEvents as $days)
		{
			foreach ($days as $events)
			{
				foreach ($events as $event)
				{
					if ($arrFeed['maxItems'] > 0 && $count++ >= $arrFeed['maxItems'])
					{
						break(3);
					}

					$objItem = new \FeedItem();

					$objItem->title = $event['title'];
					$objItem->link = $event['link'];
					$objItem->published = $event['tstamp'];
					$objItem->begin = $event['begin'];
					$objItem->end = $event['end'];
					$objItem->author = $event['authorName'];

					// Prepare the description
					if ($arrFeed['source'] == 'source_text')
					{
						$strDescription = '';
						$objElement = \ContentModel::findPublishedByPidAndTable($event['id'], 'tl_calendar_events');

						if ($objElement !== null)
						{
							while ($objElement->next())
							{
								$strDescription .= $this->getContentElement($objElement->current());
							}
						}
					}
					else
					{
						$strDescription = $event['teaser'];
					}

					$strDescription = $this->replaceInsertTags($strDescription, false);
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

		// Create the file
		\File::putContent('share/' . $strFile . '.xml', $this->replaceInsertTags($objFeed->$strType(), false));
	}


	/**
	 * Add events to the indexer
	 *
	 * @param array   $arrPages
	 * @param integer $intRoot
	 * @param boolean $blnIsSitemap
	 *
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
		$time = \Date::floorToMinute();

		// Get all calendars
		$objCalendar = \CalendarModel::findByProtected('');

		// Walk through each calendar
		if ($objCalendar !== null)
		{
			while ($objCalendar->next())
			{
				// Skip calendars without target page
				if (!$objCalendar->jumpTo)
				{
					continue;
				}

				// Skip calendars outside the root nodes
				if (!empty($arrRoot) && !in_array($objCalendar->jumpTo, $arrRoot))
				{
					continue;
				}

				// Get the URL of the jumpTo page
				if (!isset($arrProcessed[$objCalendar->jumpTo]))
				{
					$objParent = \PageModel::findWithDetails($objCalendar->jumpTo);

					// The target page does not exist
					if ($objParent === null)
					{
						continue;
					}

					// The target page has not been published (see #5520)
					if (!$objParent->published || ($objParent->start != '' && $objParent->start > $time) || ($objParent->stop != '' && $objParent->stop <= ($time + 60)))
					{
						continue;
					}

					// The target page is exempt from the sitemap (see #6418)
					if ($blnIsSitemap && $objParent->sitemap == 'map_never')
					{
						continue;
					}

					// Set the domain (see #6421)
					$domain = ($objParent->rootUseSSL ? 'https://' : 'http://') . ($objParent->domain ?: \Environment::get('host')) . TL_PATH . '/';

					// Generate the URL
					$arrProcessed[$objCalendar->jumpTo] = $domain . $this->generateFrontendUrl($objParent->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/%s' : '/events/%s'), $objParent->language);
				}

				$strUrl = $arrProcessed[$objCalendar->jumpTo];

				// Get the items
				$objEvents = \CalendarEventsModel::findPublishedDefaultByPid($objCalendar->id);

				if ($objEvents !== null)
				{
					while ($objEvents->next())
					{
						$arrPages[] = sprintf($strUrl, (($objEvents->alias != '' && !\Config::get('disableAlias')) ? $objEvents->alias : $objEvents->id));
					}
				}
			}
		}

		return $arrPages;
	}


	/**
	 * Add an event to the array of active events
	 *
	 * @param \CalendarEventsModel $objEvent
	 * @param integer              $intStart
	 * @param integer              $intEnd
	 * @param string               $strUrl
	 * @param string               $strBase
	 */
	protected function addEvent($objEvent, $intStart, $intEnd, $strUrl, $strBase)
	{
		if ($intEnd < time()) // see #3917
		{
			return;
		}

		/** @var \PageModel $objPage */
		global $objPage;

		// Called in the back end (see #4026)
		if ($objPage === null)
		{
			$objPage = new \stdClass();
			$objPage->dateFormat = \Config::get('dateFormat');
			$objPage->datimFormat = \Config::get('datimFormat');
			$objPage->timeFormat = \Config::get('timeFormat');
		}

		$intKey = date('Ymd', $intStart);
		$span = self::calculateSpan($intStart, $intEnd);
		$format = $objEvent->addTime ? 'datimFormat' : 'dateFormat';

		// Add date
		if ($span > 0)
		{
			$title = \Date::parse($objPage->$format, $intStart) . ' - ' . \Date::parse($objPage->$format, $intEnd);
		}
		else
		{
			$title = \Date::parse($objPage->dateFormat, $intStart) . ($objEvent->addTime ? ' (' . \Date::parse($objPage->timeFormat, $intStart) . (($intStart < $intEnd) ? ' - ' . \Date::parse($objPage->timeFormat, $intEnd) : '') . ')' : '');
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
				if (($objTarget = $objEvent->getRelated('jumpTo')) !== null)
				{
					$link = $strBase . $this->generateFrontendUrl($objTarget->row());
				}
				break;

			case 'article':
				if (($objArticle = \ArticleModel::findByPk($objEvent->articleId, array('eager'=>true))) !== null && ($objPid = $objArticle->getRelated('pid')) !== null)
				{
					$link = $strBase . ampersand($this->generateFrontendUrl($objPid->row(), '/articles/' . ((!\Config::get('disableAlias') && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id)));
				}
				break;
		}

		// Link to the default page
		if ($link == '')
		{
			$link = $strBase . sprintf($strUrl, (($objEvent->alias != '' && !\Config::get('disableAlias')) ? $objEvent->alias : $objEvent->id));
		}

		// Store the whole row (see #5085)
		$arrEvent = $objEvent->row();

		// Override link and title
		$arrEvent['link'] = $link;
		$arrEvent['title'] = $title;

		// Clean the RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$arrEvent['teaser'] = \String::toXhtml($objEvent->teaser);
		}
		else
		{
			$arrEvent['teaser'] = \String::toHtml5($objEvent->teaser);
		}

		// Reset the enclosures (see #5685)
		$arrEvent['enclosure'] = array();

		// Add the article image as enclosure
		if ($objEvent->addImage)
		{
			$objFile = \FilesModel::findByUuid($objEvent->singleSRC);

			if ($objFile !== null)
			{
				$arrEvent['enclosure'][] = $objFile->path;
			}
		}

		// Enclosures
		if ($objEvent->addEnclosure)
		{
			$arrEnclosure = deserialize($objEvent->enclosure, true);

			if (is_array($arrEnclosure))
			{
				$objFile = \FilesModel::findMultipleByUuids($arrEnclosure);

				if ($objFile !== null)
				{
					while ($objFile->next())
					{
						$arrEvent['enclosure'][] = $objFile->path;
					}
				}
			}
		}

		$this->arrEvents[$intKey][$intStart][] = $arrEvent;
	}


	/**
	 * Calculate the span between two timestamps in days
	 *
	 * @param integer $intStart
	 * @param integer $intEnd
	 *
	 * @return integer
	 */
	public static function calculateSpan($intStart, $intEnd)
	{
		return self::unixToJd($intEnd) - self::unixToJd($intStart);
	}


	/**
	 * Convert a UNIX timestamp to a Julian day
	 *
	 * @param integer $tstamp
	 *
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


	/**
	 * Return the names of the existing feeds so they are not removed
	 *
	 * @return array
	 */
	public function purgeOldFeeds()
	{
		$arrFeeds = array();
		$objFeeds = \CalendarFeedModel::findAll();

		if ($objFeeds !== null)
		{
			while ($objFeeds->next())
			{
				$arrFeeds[] = $objFeeds->alias ?: 'calendar' . $objFeeds->id;
			}
		}

		return $arrFeeds;
	}
}
