<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Provide methods to get all events of a certain period from the database.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
abstract class Events extends \Module
{

	/**
	 * Current URL
	 * @var string
	 */
	protected $strUrl;

	/**
	 * Today 00:00:00
	 * @var string
	 */
	protected $intTodayBegin;

	/**
	 * Today 23:59:59
	 * @var string
	 */
	protected $intTodayEnd;

	/**
	 * Current events
	 * @var array
	 */
	protected $arrEvents = array();


	/**
	 * Sort out protected archives
	 *
	 * @param array $arrCalendars
	 *
	 * @return array
	 */
	protected function sortOutProtected($arrCalendars)
	{
		if (BE_USER_LOGGED_IN || !is_array($arrCalendars) || empty($arrCalendars))
		{
			return $arrCalendars;
		}

		$this->import('FrontendUser', 'User');
		$objCalendar = \CalendarModel::findMultipleByIds($arrCalendars);
		$arrCalendars = array();

		if ($objCalendar !== null)
		{
			while ($objCalendar->next())
			{
				if ($objCalendar->protected)
				{
					if (!FE_USER_LOGGED_IN)
					{
						continue;
					}

					$groups = deserialize($objCalendar->groups);

					if (!is_array($groups) || empty($groups) || count(array_intersect($groups, $this->User->groups)) < 1)
					{
						continue;
					}
				}

				$arrCalendars[] = $objCalendar->id;
			}
		}

		return $arrCalendars;
	}


	/**
	 * Get all events of a certain period
	 *
	 * @param array   $arrCalendars
	 * @param integer $intStart
	 * @param integer $intEnd
	 *
	 * @return array
	 */
	protected function getAllEvents($arrCalendars, $intStart, $intEnd)
	{
		if (!is_array($arrCalendars))
		{
			return array();
		}

		$this->arrEvents = array();

		foreach ($arrCalendars as $id)
		{
			$strUrl = $this->strUrl;
			$objCalendar = \CalendarModel::findByPk($id);

			// Get the current "jumpTo" page
			if ($objCalendar !== null && $objCalendar->jumpTo && ($objTarget = $objCalendar->getRelated('jumpTo')) !== null)
			{
				/** @var \PageModel $objTarget */
				$strUrl = $objTarget->getFrontendUrl((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ? '/%s' : '/events/%s');
			}

			// Get the events of the current period
			$objEvents = \CalendarEventsModel::findCurrentByPid($id, $intStart, $intEnd);

			if ($objEvents === null)
			{
				continue;
			}

			while ($objEvents->next())
			{
				$this->addEvent($objEvents, $objEvents->startTime, $objEvents->endTime, $strUrl, $intStart, $intEnd, $id);

				// Recurring events
				if ($objEvents->recurring)
				{
					$arrRepeat = deserialize($objEvents->repeatEach);

					if (!is_array($arrRepeat) || !isset($arrRepeat['unit']) || !isset($arrRepeat['value']) || $arrRepeat['value'] < 1)
					{
						continue;
					}

					$count = 0;
					$intStartTime = $objEvents->startTime;
					$intEndTime = $objEvents->endTime;
					$strtotime = '+ ' . $arrRepeat['value'] . ' ' . $arrRepeat['unit'];

					while ($intEndTime < $intEnd)
					{
						if ($objEvents->recurrences > 0 && $count++ >= $objEvents->recurrences)
						{
							break;
						}

						$intStartTime = strtotime($strtotime, $intStartTime);
						$intEndTime = strtotime($strtotime, $intEndTime);

						// Stop if the upper boundary is reached (see #8445)
						if ($intStartTime === false || $intEndTime === false)
						{
							break;
						}

						// Skip events outside the scope
						if ($intEndTime < $intStart || $intStartTime > $intEnd)
						{
							continue;
						}

						$this->addEvent($objEvents, $intStartTime, $intEndTime, $strUrl, $intStart, $intEnd, $id);
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
				$this->arrEvents = $this->{$callback[0]}->{$callback[1]}($this->arrEvents, $arrCalendars, $intStart, $intEnd, $this);
			}
		}

		return $this->arrEvents;
	}


	/**
	 * Add an event to the array of active events
	 *
	 * @param \CalendarEventsModel $objEvents
	 * @param integer              $intStart
	 * @param integer              $intEnd
	 * @param string               $strUrl
	 * @param integer              $intBegin
	 * @param integer              $intLimit
	 * @param integer              $intCalendar
	 */
	protected function addEvent($objEvents, $intStart, $intEnd, $strUrl, $intBegin, $intLimit, $intCalendar)
	{
		/** @var \PageModel $objPage */
		global $objPage;

		$intDate = $intStart;
		$intKey = date('Ymd', $intStart);
		$strDate = \Date::parse($objPage->dateFormat, $intStart);
		$strDay = $GLOBALS['TL_LANG']['DAYS'][date('w', $intStart)];
		$strMonth = $GLOBALS['TL_LANG']['MONTHS'][(date('n', $intStart)-1)];
		$span = \Calendar::calculateSpan($intStart, $intEnd);

		if ($span > 0)
		{
			$strDate = \Date::parse($objPage->dateFormat, $intStart) . $GLOBALS['TL_LANG']['MSC']['cal_timeSeparator'] . \Date::parse($objPage->dateFormat, $intEnd);
			$strDay = '';
		}

		$strTime = '';

		if ($objEvents->addTime)
		{
			if ($span > 0)
			{
				$strDate = \Date::parse($objPage->datimFormat, $intStart) . $GLOBALS['TL_LANG']['MSC']['cal_timeSeparator'] . \Date::parse($objPage->datimFormat, $intEnd);
			}
			elseif ($intStart == $intEnd)
			{
				$strTime = \Date::parse($objPage->timeFormat, $intStart);
			}
			else
			{
				$strTime = \Date::parse($objPage->timeFormat, $intStart) . $GLOBALS['TL_LANG']['MSC']['cal_timeSeparator'] . \Date::parse($objPage->timeFormat, $intEnd);
			}
		}

		$until = '';
		$recurring = '';

		// Recurring event
		if ($objEvents->recurring)
		{
			$arrRange = deserialize($objEvents->repeatEach);

			if (is_array($arrRange) && isset($arrRange['unit']) && isset($arrRange['value']))
			{
				$strKey = 'cal_' . $arrRange['unit'];
				$recurring = sprintf($GLOBALS['TL_LANG']['MSC'][$strKey], $arrRange['value']);

				if ($objEvents->recurrences > 0)
				{
					$until = sprintf($GLOBALS['TL_LANG']['MSC']['cal_until'], \Date::parse($objPage->dateFormat, $objEvents->repeatEnd));
				}
			}
		}

		// Store raw data
		$arrEvent = $objEvents->row();

		// Overwrite some settings
		$arrEvent['date'] = $strDate;
		$arrEvent['time'] = $strTime;
		$arrEvent['datetime'] = $objEvents->addTime ? date('Y-m-d\TH:i:sP', $intStart) : date('Y-m-d', $intStart);
		$arrEvent['day'] = $strDay;
		$arrEvent['month'] = $strMonth;
		$arrEvent['parent'] = $intCalendar;
		$arrEvent['calendar'] = $objEvents->getRelated('pid');
		$arrEvent['link'] = $objEvents->title;
		$arrEvent['target'] = '';
		$arrEvent['title'] = specialchars($objEvents->title, true);
		$arrEvent['href'] = $this->generateEventUrl($objEvents, $strUrl);
		$arrEvent['class'] = ($objEvents->cssClass != '') ? ' ' . $objEvents->cssClass : '';
		$arrEvent['recurring'] = $recurring;
		$arrEvent['until'] = $until;
		$arrEvent['begin'] = $intStart;
		$arrEvent['end'] = $intEnd;
		$arrEvent['details'] = '';
		$arrEvent['hasDetails'] = false;
		$arrEvent['hasTeaser'] = false;

		// Override the link target
		if ($objEvents->source == 'external' && $objEvents->target)
		{
			$arrEvent['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
		}

		// Clean the RTE output
		if ($arrEvent['teaser'] != '')
		{
			$arrEvent['hasTeaser'] = true;

			if ($objPage->outputFormat == 'xhtml')
			{
				$arrEvent['teaser'] = \StringUtil::toXhtml($arrEvent['teaser']);
			}
			else
			{
				$arrEvent['teaser'] = \StringUtil::toHtml5($arrEvent['teaser']);
			}

			$arrEvent['teaser'] = \StringUtil::encodeEmail($arrEvent['teaser']);
		}

		// Display the "read more" button for external/article links
		if ($objEvents->source != 'default')
		{
			$arrEvent['details'] = true;
			$arrEvent['hasDetails'] = true;
		}

		// Compile the event text
		else
		{
			$id = $objEvents->id;

			$arrEvent['details'] = function () use ($id)
			{
				$strDetails = '';
				$objElement = \ContentModel::findPublishedByPidAndTable($id, 'tl_calendar_events');

				if ($objElement !== null)
				{
					while ($objElement->next())
					{
						$strDetails .= $this->getContentElement($objElement->current());
					}
				}

				return $strDetails;
			};

			$arrEvent['hasDetails'] = function () use ($id)
			{
				return \ContentModel::countPublishedByPidAndTable($id, 'tl_calendar_events') > 0;
			};
		}

		// Get todays start and end timestamp
		if ($this->intTodayBegin === null)
		{
			$this->intTodayBegin = strtotime('00:00:00');
		}
		if ($this->intTodayEnd === null)
		{
			$this->intTodayEnd = strtotime('23:59:59');
		}

		// Mark past and upcoming events (see #3692)
		if ($intEnd < $this->intTodayBegin)
		{
			$arrEvent['class'] .= ' bygone';
		}
		elseif ($intStart > $this->intTodayEnd)
		{
			$arrEvent['class'] .= ' upcoming';
		}
		else
		{
			$arrEvent['class'] .= ' current';
		}

		$this->arrEvents[$intKey][$intStart][] = $arrEvent;

		// Multi-day event
		for ($i=1; $i<=$span; $i++)
		{
			// Only show first occurrence
			if ($this->cal_noSpan)
			{
				break;
			}

			$intDate = strtotime('+1 day', $intDate);

			if ($intDate > $intLimit)
			{
				break;
			}

			$this->arrEvents[date('Ymd', $intDate)][$intDate][] = $arrEvent;
		}
	}


	/**
	 * Generate a URL and return it as string
	 *
	 * @param \CalendarEventsModel $objEvent
	 * @param string               $strUrl
	 *
	 * @return string
	 */
	protected function generateEventUrl($objEvent, $strUrl)
	{
		switch ($objEvent->source)
		{
			// Link to an external page
			case 'external':
				if (substr($objEvent->url, 0, 7) == 'mailto:')
				{
					return \StringUtil::encodeEmail($objEvent->url);
				}
				else
				{
					return ampersand($objEvent->url);
				}
				break;

			// Link to an internal page
			case 'internal':
				if (($objTarget = $objEvent->getRelated('jumpTo')) !== null)
				{
					/** @var \PageModel $objTarget */
					return ampersand($objTarget->getFrontendUrl());
				}
				break;

			// Link to an article
			case 'article':
				if (($objArticle = \ArticleModel::findByPk($objEvent->articleId, array('eager'=>true))) !== null && ($objPid = $objArticle->getRelated('pid')) !== null)
				{
					/** @var \PageModel $objPid */
					return ampersand($objPid->getFrontendUrl('/articles/' . ((!\Config::get('disableAlias') && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id)));
				}
				break;
		}

		// Link to the default page
		return ampersand(sprintf($strUrl, ((!\Config::get('disableAlias') && $objEvent->alias != '') ? $objEvent->alias : $objEvent->id)));
	}


	/**
	 * Return the begin and end timestamp and an error message as array
	 *
	 * @param \Date  $objDate
	 * @param string $strFormat
	 *
	 * @return array
	 */
	protected function getDatesFromFormat(\Date $objDate, $strFormat)
	{
		switch ($strFormat)
		{
			case 'cal_day':
				return array($objDate->dayBegin, $objDate->dayEnd, $GLOBALS['TL_LANG']['MSC']['cal_emptyDay']);

			default:
			case 'cal_month':
				return array($objDate->monthBegin, $objDate->monthEnd, $GLOBALS['TL_LANG']['MSC']['cal_emptyMonth']);

			case 'cal_year':
				return array($objDate->yearBegin, $objDate->yearEnd, $GLOBALS['TL_LANG']['MSC']['cal_emptyYear']);

			case 'cal_all': // 1970-01-01 00:00:00 - 2038-01-01 00:00:00
				return array(0, 2145913200, $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_7':
				return array(time(), strtotime('+7 days'), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'next_14':
				return array(time(), strtotime('+14 days'), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'next_30':
				return array(time(), strtotime('+1 month'), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'next_90':
				return array(time(), strtotime('+3 months'), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'next_180':
				return array(time(), strtotime('+6 months'), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'next_365':
				return array(time(), strtotime('+1 year'), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'next_two':
				return array(time(), strtotime('+2 years'), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'next_cur_month':
				return array(time(), strtotime('last day of this month 23:59:59'), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'next_cur_year':
				return array(time(), strtotime('last day of december this year 23:59:59'), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'next_next_month':
				return array(strtotime('first day of next month 00:00:00'), strtotime('last day of next month 23:59:59'), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'next_next_year':
				return array(strtotime('first day of january next year 00:00:00'), strtotime('last day of december next year 23:59:59'), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'next_all': // 2038-01-01 00:00:00
				return array(time(), 2145913200, $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'past_7':
				return array(strtotime('-7 days'), time(), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'past_14':
				return array(strtotime('-14 days'), time(), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'past_30':
				return array(strtotime('-1 month'), time(), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'past_90':
				return array(strtotime('-3 months'), time(), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'past_180':
				return array(strtotime('-6 months'), time(), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'past_365':
				return array(strtotime('-1 year'), time(), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'past_two':
				return array(strtotime('-2 years'), time(), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'past_cur_month':
				return array(strtotime('first day of this month 00:00:00'), time(), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'past_cur_year':
				return array(strtotime('first day of january this year 00:00:00'), time(), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'past_prev_month':
				return array(strtotime('first day of last month 00:00:00'), strtotime('last day of last month 23:59:59'), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'past_prev_year':
				return array(strtotime('first day of january last year 00:00:00'), strtotime('last day of december last year 23:59:59'), $GLOBALS['TL_LANG']['MSC']['cal_empty']);

			case 'past_all': // 1970-01-01 00:00:00
				return array(0, time(), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
		}
	}
}
