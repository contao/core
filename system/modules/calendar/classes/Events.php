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
				$strUrl = $this->generateFrontendUrl($objTarget->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/%s' : '/events/%s'));
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

					if ($arrRepeat['value'] < 1)
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
				$this->arrEvents = $this->$callback[0]->$callback[1]($this->arrEvents, $arrCalendars, $intStart, $intEnd, $this);
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

		$span = \Calendar::calculateSpan($intStart, $intEnd);

		// Adjust the start time of a multi-day event (see #6802)
		if ($this->cal_noSpan && $span > 0 && $intStart < $intBegin && $intBegin < $intEnd)
		{
			$intStart = $intBegin;
		}

		$intDate = $intStart;
		$intKey = date('Ymd', $intStart);
		$strDate = \Date::parse($objPage->dateFormat, $intStart);
		$strDay = $GLOBALS['TL_LANG']['DAYS'][date('w', $intStart)];
		$strMonth = $GLOBALS['TL_LANG']['MONTHS'][(date('n', $intStart)-1)];

		if ($span > 0)
		{
			$strDate = \Date::parse($objPage->dateFormat, $intStart) . ' - ' . \Date::parse($objPage->dateFormat, $intEnd);
			$strDay = '';
		}

		$strTime = '';

		if ($objEvents->addTime)
		{
			if ($span > 0)
			{
				$strDate = \Date::parse($objPage->datimFormat, $intStart) . ' - ' . \Date::parse($objPage->datimFormat, $intEnd);
			}
			elseif ($intStart == $intEnd)
			{
				$strTime = \Date::parse($objPage->timeFormat, $intStart);
			}
			else
			{
				$strTime = \Date::parse($objPage->timeFormat, $intStart) . ' - ' . \Date::parse($objPage->timeFormat, $intEnd);
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
		$arrEvent['calendar'] = $objEvents->getRelated('pid');
		$arrEvent['link'] = $objEvents->title;
		$arrEvent['target'] = '';
		$arrEvent['title'] = specialchars($objEvents->title, true);
		$arrEvent['href'] = $this->generateEventUrl($objEvents, $strUrl);
		$arrEvent['class'] = ($objEvents->cssClass != '') ? ' ' . $objEvents->cssClass : '';
		$arrEvent['begin'] = $intStart;
		$arrEvent['end'] = $intEnd;
		$arrEvent['details'] = '';

		// Override the link target
		if ($objEvents->source == 'external' && $objEvents->target)
		{
			$arrEvent['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
		}

		// Clean the RTE output
		if ($arrEvent['teaser'] != '')
		{
			if ($objPage->outputFormat == 'xhtml')
			{
				$arrEvent['teaser'] = \String::toXhtml($arrEvent['teaser']);
			}
			else
			{
				$arrEvent['teaser'] = \String::toHtml5($arrEvent['teaser']);
			}
		}

		// Display the "read more" button for external/article links
		if ($objEvents->source != 'default')
		{
			$arrEvent['details'] = true;
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
					return \String::encodeEmail($objEvent->url);
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
					return ampersand($this->generateFrontendUrl($objTarget->row()));
				}
				break;

			// Link to an article
			case 'article':
				if (($objArticle = \ArticleModel::findByPk($objEvent->articleId, array('eager'=>true))) !== null && ($objPid = $objArticle->getRelated('pid')) !== null)
				{
					return ampersand($this->generateFrontendUrl($objPid->row(), '/articles/' . ((!\Config::get('disableAlias') && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id)));
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
				return array(time(), (strtotime('+7 days') - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_14':
				return array(time(), (strtotime('+14 days') - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_30':
				return array(time(), (strtotime('+1 month') - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_90':
				return array(time(), (strtotime('+3 months') - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_180':
				return array(time(), (strtotime('+6 months') - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_365':
				return array(time(), (strtotime('+1 year') - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_two':
				return array(time(), (strtotime('+2 years') - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_cur_month':
				$objToday = new \Date();

				return array(time(), $objToday->monthEnd, $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_cur_year':
				$objToday = new \Date();

				return array(time(), $objToday->yearEnd, $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_next_month':
				$objToday = new \Date();

				return array(($objToday->monthEnd + 1), strtotime('+1 month', $objToday->monthEnd), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_next_year':
				$objToday = new \Date();

				return array(($objToday->yearEnd + 1), strtotime('+1 year', $objToday->yearEnd), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_all': // 2038-01-01 00:00:00
				return array(time(), 2145913200, $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_7':
				return array(strtotime('-7 days'), (time() - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_14':
				return array(strtotime('-14 days'), (time() - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_30':
				return array(strtotime('-1 month'), (time() - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_90':
				return array(strtotime('-3 months'), (time() - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_180':
				return array(strtotime('-6 months'), (time() - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_365':
				return array(strtotime('-1 year'), (time() - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_two':
				return array(strtotime('-2 years'), (time() - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_cur_month':
				$objToday = new \Date();

				return array($objToday->monthBegin, (time() - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_cur_year':
				$objToday = new \Date();

				return array($objToday->yearBegin, (time() - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_prev_month':
				$objToday = new \Date();

				return array(strtotime('-1 month', $objToday->monthBegin), ($objToday->monthBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_prev_year':
				$objToday = new \Date();

				return array(strtotime('-1 year', $objToday->yearBegin), ($objToday->yearBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_all': // 1970-01-01 00:00:00
				$objToday = new \Date();

				return array(0, ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;
		}
	}
}
