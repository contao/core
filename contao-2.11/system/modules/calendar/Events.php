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
 * Class Events
 *
 * Provide methods to get all events of a certain period from the database.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
abstract class Events extends Module
{

	/**
	 * Current URL
	 * @var string
	 */
	protected $strUrl;

	/**
	 * Current events
	 * @var array
	 */
	protected $arrEvents = array();


	/**
	 * Sort out protected archives
	 * @param array
	 * @return array
	 */
	protected function sortOutProtected($arrCalendars)
	{
		if (BE_USER_LOGGED_IN || !is_array($arrCalendars) || count($arrCalendars) < 1)
		{
			return $arrCalendars;
		}

		$this->import('FrontendUser', 'User');
		$objCalendar = $this->Database->execute("SELECT id, protected, groups FROM tl_calendar WHERE id IN(" . implode(',', array_map('intval', $arrCalendars)) . ")");
		$arrCalendars = array();

		while ($objCalendar->next())
		{
			if ($objCalendar->protected)
			{
				if (!FE_USER_LOGGED_IN)
				{
					continue;
				}

				$groups = deserialize($objCalendar->groups);

				if (!is_array($groups) || count($groups) < 1 || count(array_intersect($groups, $this->User->groups)) < 1)
				{
					continue;
				}
			}

			$arrCalendars[] = $objCalendar->id;
		}

		return $arrCalendars;
	}


	/**
	 * Get all events of a certain period
	 * @param array
	 * @param integer
	 * @param integer
	 * @return array
	 */
	protected function getAllEvents($arrCalendars, $intStart, $intEnd)
	{
		if (!is_array($arrCalendars))
		{
			return array();
		}

		$this->import('String');

		$time = time();
		$this->arrEvents = array();

		foreach ($arrCalendars as $id)
		{
			$strUrl = $this->strUrl;

			// Get current "jumpTo" page
			$objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=(SELECT jumpTo FROM tl_calendar WHERE id=?)")
									  ->limit(1)
									  ->execute($id);

			if ($objPage->numRows)
			{
				$strUrl = $this->generateFrontendUrl($objPage->row(), '/events/%s');
			}

			// Get events of the current period
			$objEvents = $this->Database->prepare("SELECT *, (SELECT title FROM tl_calendar WHERE id=?) AS calendar, (SELECT name FROM tl_user WHERE id=author) author FROM tl_calendar_events WHERE pid=? AND ((startTime>=? AND startTime<=?) OR (endTime>=? AND endTime<=?) OR (startTime<=? AND endTime>=?) OR (recurring=1 AND (recurrences=0 OR repeatEnd>=?) AND startTime<=?))" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY startTime")
										->execute($id, $id, $intStart, $intEnd, $intStart, $intEnd, $intStart, $intEnd, $intStart, $intEnd);

			if ($objEvents->numRows < 1)
			{
				continue;
			}

			while ($objEvents->next())
			{
				$this->addEvent($objEvents, $objEvents->startTime, $objEvents->endTime, $strUrl, $intStart, $intEnd, $id);

				// Recurring events
				if ($objEvents->recurring)
				{
					$count = 0;

					$arrRepeat = deserialize($objEvents->repeatEach);
					$strtotime = '+ ' . $arrRepeat['value'] . ' ' . $arrRepeat['unit'];

					if ($arrRepeat['value'] < 1)
					{
						continue;
					}

					while ($objEvents->endTime < $intEnd)
					{
						if ($objEvents->recurrences > 0 && $count++ >= $objEvents->recurrences)
						{
							break;
						}

						$objEvents->startTime = strtotime($strtotime, $objEvents->startTime);
						$objEvents->endTime = strtotime($strtotime, $objEvents->endTime);

						// Skip events outside the scope
						if ($objEvents->endTime < $intStart || $objEvents->startTime > $intEnd)
						{
							continue;
						}

						$this->addEvent($objEvents, $objEvents->startTime, $objEvents->endTime, $strUrl, $intStart, $intEnd, $id);
					}
				}
			}
		}

		// Sort the array
		foreach (array_keys($this->arrEvents) as $key)
		{
			ksort($this->arrEvents[$key]);
		}

		// HOOK: modify the result set
		if (isset($GLOBALS['TL_HOOKS']['getAllEvents']) && is_array($GLOBALS['TL_HOOKS']['getAllEvents']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getAllEvents'] as $callback)
			{
				$this->import($callback[0]);
				$this->arrEvents = $this->$callback[0]->$callback[1]($this->arrEvents, $arrCalendars, $intStart, $intEnd, $this);
			}
		}

		return $this->arrEvents;
	}


	/**
	 * Add an event to the array of active events
	 * @param object
	 * @param integer
	 * @param integer
	 * @param string
	 * @param integer
	 * @param integer
	 * @param integer
	 */
	protected function addEvent(Database_Result $objEvents, $intStart, $intEnd, $strUrl, $intBegin, $intLimit, $intCalendar)
	{
		global $objPage;

		$intDate = $intStart;
		$intKey = date('Ymd', $intStart);
		$span = Calendar::calculateSpan($intStart, $intEnd);
		$strDate = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $intStart);
		$strDay = $GLOBALS['TL_LANG']['DAYS'][date('w', $intStart)];
		$strMonth = $GLOBALS['TL_LANG']['MONTHS'][(date('n', $intStart)-1)];

		if ($span > 0)
		{
			$strDate = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $intStart) . ' - ' . $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $intEnd);
			$strDay = '';
		}

		$strTime = '';

		if ($objEvents->addTime)
		{
			if ($span > 0)
			{
				$strDate = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $intStart) . ' - ' . $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $intEnd);
			}
			elseif ($intStart == $intEnd)
			{
				$strTime = $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $intStart);
			}
			else
			{
				$strTime = $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $intStart) . ' - ' . $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $intEnd);
			}
		}

		// Store raw data
		$arrEvent = $objEvents->row();

		// Overwrite some settings
		$arrEvent['time'] = $strTime;
		$arrEvent['date'] = $strDate;
		$arrEvent['day'] = $strDay;
		$arrEvent['month'] = $strMonth;
		$arrEvent['parent'] = $intCalendar;
		$arrEvent['link'] = $objEvents->title;
		$arrEvent['target'] = '';
		$arrEvent['title'] = specialchars($objEvents->title, true);
		$arrEvent['href'] = $this->generateEventUrl($objEvents, $strUrl);
		$arrEvent['class'] = ($objEvents->cssClass != '') ? ' ' . $objEvents->cssClass : '';
		$arrEvent['details'] = $this->String->encodeEmail($objEvents->details);
		$arrEvent['start'] = $intStart;
		$arrEvent['end'] = $intEnd;

		// Override the link target
		if ($objEvents->source == 'external' && $objEvents->target)
		{
			$arrEvent['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="window.open(this.href); return false;"' : ' target="_blank"';
		}

		// Clean the RTE output
		if ($arrEvent['teaser'] != '')
		{
			if ($objPage->outputFormat == 'xhtml')
			{
				$arrEvent['teaser'] = $this->String->toXhtml($arrEvent['teaser']);
			}
			else
			{
				$arrEvent['teaser'] = $this->String->toHtml5($arrEvent['teaser']);
			}
		}

		// Display the "read more" button for external/article links
		if (($objEvents->source == 'external' || $objEvents->source == 'article') && $objEvents->details == '')
		{
			$arrEvent['details'] = true;
		}

		// Clean the RTE output
		else
		{
			if ($objPage->outputFormat == 'xhtml')
			{
				$arrEvent['details'] = $this->String->toXhtml($arrEvent['details']);
			}
			else
			{
				$arrEvent['details'] = $this->String->toHtml5($arrEvent['details']);
			}
		}

		$this->arrEvents[$intKey][$intStart][] = $arrEvent;

		// Multi-day event
		for ($i=1; $i<=$span && $intDate<=$intLimit; $i++)
		{
			// Only show first occurrence
			if ($this->cal_noSpan && $intDate >= $intBegin)
			{
				break;
			}

			$intDate = strtotime('+ 1 day', $intDate);
			$intNextKey = date('Ymd', $intDate);

			$this->arrEvents[$intNextKey][$intDate][] = $arrEvent;
		}
	}


	/**
	 * Generate a URL and return it as string
	 * @param object
	 * @param string
	 * @return string
	 */
	protected function generateEventUrl(Database_Result $objEvent, $strUrl)
	{
		switch ($objEvent->source)
		{
			// Link to an external page
			case 'external':
				$this->import('String');

				if (substr($objEvent->url, 0, 7) == 'mailto:')
				{
					return $this->String->encodeEmail($objEvent->url);
				}
				else
				{
					return ampersand($objEvent->url);
				}
				break;

			// Link to an internal page
			case 'internal':
				$objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									 	  ->limit(1)
										  ->execute($objEvent->jumpTo);

				if ($objPage->numRows)
				{
					return ampersand($this->generateFrontendUrl($objPage->row()));
				}
				break;

			// Link to an article
			case 'article':
				$objPage = $this->Database->prepare("SELECT a.id AS aId, a.alias AS aAlias, a.title, p.id, p.alias FROM tl_article a, tl_page p WHERE a.pid=p.id AND a.id=?")
										  ->limit(1)
										  ->execute($objEvent->articleId);

				if ($objPage->numRows)
				{
					return ampersand($this->generateFrontendUrl($objPage->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objPage->aAlias != '') ? $objPage->aAlias : $objPage->aId)));
				}
				break;
		}

		// Link to the default page
		return ampersand(sprintf($strUrl, ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objEvent->alias != '') ? $objEvent->alias : $objEvent->id)));
	}


	/**
	 * Return the begin and end timestamp and an error message as array
	 * @param object
	 * @param string
	 * @return array
	 */
	protected function getDatesFromFormat(Date $objDate, $strFormat)
	{
		switch ($strFormat)
		{
			case 'cal_day':
				return array($objDate->dayBegin, $objDate->dayEnd, $GLOBALS['TL_LANG']['MSC']['cal_emptyDay']);
				break;

			default:
			case 'cal_month':
				return array($objDate->monthBegin, $objDate->monthEnd, $GLOBALS['TL_LANG']['MSC']['cal_emptyMonth']);
				break;

			case 'cal_year':
				return array($objDate->yearBegin, $objDate->yearEnd, $GLOBALS['TL_LANG']['MSC']['cal_emptyYear']);
				break;

			case 'cal_all': // 1970-01-01 00:00:00 - 2038-01-01 00:00:00
				return array(0, 2145913200, $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_7':
				$objToday = new Date();
				return array($objToday->dayBegin, (strtotime('+7 days', $objToday->dayBegin) - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_14':
				$objToday = new Date();
				return array($objToday->dayBegin, (strtotime('+14 days', $objToday->dayBegin) - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_30':
				$objToday = new Date();
				return array($objToday->dayBegin, (strtotime('+1 month', $objToday->dayBegin) - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_90':
				$objToday = new Date();
				return array($objToday->dayBegin, (strtotime('+3 months', $objToday->dayBegin) - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_180':
				$objToday = new Date();
				return array($objToday->dayBegin, (strtotime('+6 months', $objToday->dayBegin) - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_365':
				$objToday = new Date();
				return array($objToday->dayBegin, (strtotime('+1 year', $objToday->dayBegin) - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_two':
				$objToday = new Date();
				return array($objToday->dayBegin, (strtotime('+2 years', $objToday->dayBegin) - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_all': // 2038-01-01 00:00:00
				$objToday = new Date();
				return array($objToday->dayBegin, 2145913200, $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_7':
				$objToday = new Date();
				return array((strtotime('-7 days', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_14':
				$objToday = new Date();
				return array((strtotime('-14 days', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_30':
				$objToday = new Date();
				return array((strtotime('-1 month', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_90':
				$objToday = new Date();
				return array((strtotime('-3 months', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_180':
				$objToday = new Date();
				return array((strtotime('-6 months', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_365':
				$objToday = new Date();
				return array((strtotime('-1 year', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_two':
				$objToday = new Date();
				return array((strtotime('-2 years', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_all': // 1970-01-01 00:00:00
				$objToday = new Date();
				return array(0, ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;
		}
	}
}

?>