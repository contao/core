<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Calendar
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class Calendar
 *
 * Provide methods regarding calendars.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Calendar
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
	 * @param boolean
	 */
	public function generateFeed($intId, $blnIsFeedId=false)
	{
		$objCalendar = $blnIsFeedId ? \CalendarFeedModel::findByPk($intId) : \CalendarFeedModel::findByCalendar($intId);

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
			$this->log('Generated calendar feed "' . $objCalendar->feedName . '.xml"', 'Calendar generateFeed()', TL_CRON);
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
				$this->log('Generated calendar feed "' . $objCalendar->feedName . '.xml"', 'Calendar generateFeeds()', TL_CRON);
			}
		}
	}


	/**
	 * Generate an XML file and save it to the root directory
	 * @param array
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
					$objParent = $this->getPageDetails($jumpTo);
					$arrUrls[$jumpTo] = $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/events/%s'), $objParent->language);
				}

				$strUrl = $arrUrls[$jumpTo];
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
					$objItem->published = $event['published'];
					$objItem->start = $event['start'];
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
								$strDescription .= $this->getContentElement($objElement->id);
							}
						}
					}
					else
					{
						$strDescription = $event['teaser'];
					}

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
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot=0)
	{
		$arrRoot = array();

		if ($intRoot > 0)
		{
			$arrRoot = $this->Database->getChildRecords($intRoot, 'tl_page');
		}

		$arrProcessed = array();

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
					$domain = \Environment::get('base');
					$objParent = $this->getPageDetails($objCalendar->jumpTo);

					// The target page does not exist
					if ($objParent === null)
					{
						continue;
					}

					if ($objParent->domain != '')
					{
						$domain = (\Environment::get('ssl') ? 'https://' : 'http://') . $objParent->domain . TL_PATH . '/';
					}

					$arrProcessed[$objCalendar->jumpTo] = $domain . $this->generateFrontendUrl($objParent->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/events/%s'), $objParent->language);
				}

				$strUrl = $arrProcessed[$objCalendar->jumpTo];

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
	 * @param object
	 * @param integer
	 * @param integer
	 * @param string
	 * @param string
	 */
	protected function addEvent($objEvent, $intStart, $intEnd, $strUrl, $strBase)
	{
		if ($intEnd < time()) // see #3917
		{
			return;
		}

		global $objPage;

		// Called in the back end (see #4026)
		if ($objPage === null)
		{
			$objPage = new \stdClass();
			$objPage->dateFormat = $GLOBALS['TL_CONFIG']['dateFormat'];
			$objPage->datimFormat = $GLOBALS['TL_CONFIG']['datimFormat'];
			$objPage->timeFormat = $GLOBALS['TL_CONFIG']['timeFormat'];
		}

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
				if (($objTarget = $objEvent->getRelated('jumpTo')) !== null)
				{
					$link = $strBase . $this->generateFrontendUrl($objTarget->row());
				}
				break;

			case 'article':
				if (($objArticle = \ArticleModel::findByPk($objEvent->articleId, array('eager'=>true))) !== null)
				{
					$link = $strBase . ampersand($this->generateFrontendUrl($objArticle->getRelated('pid')->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id)));
				}
				break;
		}

		// Link to the default page
		if ($link == '')
		{
			$link = $strBase . sprintf($strUrl, (($objEvent->alias != '' && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objEvent->alias : $objEvent->id));
		}

		// Clean the RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$objEvent->teaser = \String::toXhtml($objEvent->teaser);
		}
		else
		{
			$objEvent->teaser = \String::toHtml5($objEvent->teaser);
		}

		// Store the whole row (see #5085)
		$arrEvent = $objEvent->row();

		// Override link and title
		$arrEvent['link'] = $link;
		$arrEvent['title'] = $title;

		// Add the article image as enclosure
		if ($objEvent->addImage)
		{
			$objFile = \FilesModel::findByPk($objEvent->singleSRC);

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
				$objFile = \FilesModel::findMultipleByIds($arrEnclosure);

				while ($objFile->next())
				{
					$arrEvent['enclosure'][] = $objFile->path;
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


	/**
	 * Return the names of the existing feeds so they are not removed
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
