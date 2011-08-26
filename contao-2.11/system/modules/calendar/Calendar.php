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
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Class Calendar
 *
 * Provide methods regarding calendars.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
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
		if ($this->Input->get('act') == 'delete' || $objCalendar->protected)
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
		$objCalendar = $this->Database->execute("SELECT * FROM tl_calendar WHERE makeFeed=1 AND protected!=1");

		while ($objCalendar->next())
		{
			$objCalendar->feedName = strlen($objCalendar->alias) ? $objCalendar->alias : 'calendar' . $objCalendar->id;

			$this->generateFiles($objCalendar->row());
			$this->log('Generated event feed "' . $objCalendar->feedName . '.xml"', 'Calendar generateFeeds()', TL_CRON);
		}
	}


	/**
	 * Generate an XML file and save it to the root directory
	 * @param array
	 */
	protected function generateFiles($arrArchive)
	{
		$time = time();
		$this->arrEvents = array();
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
		$objArticleStmt = $this->Database->prepare("SELECT *, (SELECT name FROM tl_user u WHERE u.id=e.author) AS authorName FROM tl_calendar_events e WHERE pid=? AND (startTime>=$time OR (recurring=1 AND (recurrences=0 OR repeatEnd>=$time))) AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1 ORDER BY startTime");

		if ($arrArchive['maxItems'] > 0)
		{
			$objArticleStmt->limit($arrArchive['maxItems']);
		}

		$objArticle = $objArticleStmt->execute($arrArchive['id']);

		// Get default URL
		$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									->limit(1)
									->execute($arrArchive['jumpTo']);

		$strUrl = $strLink . $this->generateFrontendUrl($objParent->fetchAssoc(), '/events/%s');

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

					$objItem = new FeedItem();

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
			$arrRoot = $this->getChildRecords($intRoot, 'tl_page');
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
				$objParent = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=? AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1 AND noSearch!=1")
											->limit(1)
											->execute($objCalendar->jumpTo);

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
			$objArticle = $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE pid=? AND source='default' AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1 ORDER BY startTime DESC")
										 ->execute($objCalendar->id);

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
	 * @param string
	 */
	protected function addEvent(Database_Result $objArticle, $intStart, $intEnd, $strUrl, $strLink)
	{
		if ($intStart < time())
		{
			return;
		}

		global $objPage;
		$this->import('String');

		$intKey = date('Ymd', $intStart);
		$span = self::calculateSpan($intStart, $intEnd);
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

		// Add title and link
		$title .= ' ' . $objArticle->title;
		$link = '';

		switch ($objArticle->source)
		{
			case 'external':
				$link = $objArticle->url;
				break;

			case 'internal':
				$objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									 	  ->limit(1)
										  ->execute($objArticle->jumpTo);

				if ($objPage->numRows)
				{
					$link = $strLink . $this->generateFrontendUrl($objPage->row());
				}
				break;

			case 'article':
				$objPage = $this->Database->prepare("SELECT a.id AS aId, a.alias AS aAlias, a.title, p.id, p.alias FROM tl_article a, tl_page p WHERE a.pid=p.id AND a.id=?")
										  ->limit(1)
										  ->execute($objArticle->articleId);

				if ($objPage->numRows)
				{
					$link = $strLink . $this->generateFrontendUrl($objPage->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && strlen($objPage->aAlias)) ? $objPage->aAlias : $objPage->aId));
				}
				break;
		}

		// Link to default page
		if ($link == '')
		{
			$link = sprintf($strUrl, ((strlen($objArticle->alias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objArticle->alias : $objArticle->id));
		}

		// Clean the RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$objArticle->teaser = $this->String->toXhtml($objArticle->teaser);
		}
		else
		{
			$objArticle->teaser = $this->String->toHtml5($objArticle->teaser);
		}

		$arrEvent = array
		(
			'title' => $title,
			'description' => $objArticle->details,
			'teaser' => $objArticle->teaser,
			'link' => $link,
			'published' => $objArticle->tstamp,
			'authorName' => $objArticle->authorName
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