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
 * Class Events
 *
 * Provide methods to get all events of a certain period from the database.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Calendar
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
	 * @param array
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

		$this->arrEvents = array();

		foreach ($arrCalendars as $id)
		{
			$strUrl = $this->strUrl;
			$objCalendar = \CalendarModel::findByPk($id);

			// Get the current "jumpTo" page
			if ($objCalendar !== null && $objCalendar->jumpTo && ($objTarget = $objCalendar->getRelated('jumpTo')) !== null)
			{
				$strUrl = $this->generateFrontendUrl($objTarget->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/events/%s'));
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
	protected function addEvent($objEvents, $intStart, $intEnd, $strUrl, $intBegin, $intLimit, $intCalendar)
	{
		global $objPage;

		$intDate = $intStart;
		$intKey = date('Ymd', $intStart);
		$span = \Calendar::calculateSpan($intStart, $intEnd);
		$strDate = $this->parseDate($objPage->dateFormat, $intStart);
		$strDay = $GLOBALS['TL_LANG']['DAYS'][date('w', $intStart)];
		$strMonth = $GLOBALS['TL_LANG']['MONTHS'][(date('n', $intStart)-1)];

		if ($span > 0)
		{
			$strDate = $this->parseDate($objPage->dateFormat, $intStart) . ' - ' . $this->parseDate($objPage->dateFormat, $intEnd);
			$strDay = '';
		}

		$strTime = '';

		if ($objEvents->addTime)
		{
			if ($span > 0)
			{
				$strDate = $this->parseDate($objPage->datimFormat, $intStart) . ' - ' . $this->parseDate($objPage->datimFormat, $intEnd);
			}
			elseif ($intStart == $intEnd)
			{
				$strTime = $this->parseDate($objPage->timeFormat, $intStart);
			}
			else
			{
				$strTime = $this->parseDate($objPage->timeFormat, $intStart) . ' - ' . $this->parseDate($objPage->timeFormat, $intEnd);
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
		$arrEvent['start'] = $intStart;
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
			$objElement = \ContentModel::findPublishedByPidAndTable($objEvents->id, 'tl_calendar_events');

			if ($objElement !== null)
			{
				while ($objElement->next())
				{
					$arrEvent['details'] .= $this->getContentElement($objElement->id);
				}
			}
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
	 * @param object
	 * @param string
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
				$objArticle = \ArticleModel::findByPk($objEvent->articleId, array('eager'=>true));

				if ($objArticle !== null)
				{
					return ampersand($this->generateFrontendUrl($objArticle->getRelated('pid')->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id)));
				}
				break;
		}

		// Link to the default page
		return ampersand(sprintf($strUrl, ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objEvent->alias != '') ? $objEvent->alias : $objEvent->id)));
	}


	/**
	 * Return the begin and end timestamp and an error message as array
	 * @param \Date
	 * @param string
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
				$objToday = new \Date();
				return array($objToday->dayBegin, (strtotime('+7 days', $objToday->dayBegin) - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_14':
				$objToday = new \Date();
				return array($objToday->dayBegin, (strtotime('+14 days', $objToday->dayBegin) - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_30':
				$objToday = new \Date();
				return array($objToday->dayBegin, (strtotime('+1 month', $objToday->dayBegin) - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_90':
				$objToday = new \Date();
				return array($objToday->dayBegin, (strtotime('+3 months', $objToday->dayBegin) - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_180':
				$objToday = new \Date();
				return array($objToday->dayBegin, (strtotime('+6 months', $objToday->dayBegin) - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_365':
				$objToday = new \Date();
				return array($objToday->dayBegin, (strtotime('+1 year', $objToday->dayBegin) - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_two':
				$objToday = new \Date();
				return array($objToday->dayBegin, (strtotime('+2 years', $objToday->dayBegin) - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_cur_month':
				$objToday = new \Date();
				return array($objToday->dayBegin, $objToday->monthEnd, $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_cur_year':
				$objToday = new \Date();
				return array($objToday->dayBegin, $objToday->yearEnd, $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'next_all': // 2038-01-01 00:00:00
				$objToday = new \Date();
				return array($objToday->dayBegin, 2145913200, $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_7':
				$objToday = new \Date();
				return array((strtotime('-7 days', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_14':
				$objToday = new \Date();
				return array((strtotime('-14 days', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_30':
				$objToday = new \Date();
				return array((strtotime('-1 month', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_90':
				$objToday = new \Date();
				return array((strtotime('-3 months', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_180':
				$objToday = new \Date();
				return array((strtotime('-6 months', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_365':
				$objToday = new \Date();
				return array((strtotime('-1 year', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_two':
				$objToday = new \Date();
				return array((strtotime('-2 years', $objToday->dayBegin) - 1), ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_cur_month':
				$objToday = new \Date();
				return array($objToday->monthBegin, ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_cur_year':
				$objToday = new \Date();
				return array($objToday->yearBegin, ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;

			case 'past_all': // 1970-01-01 00:00:00
				$objToday = new \Date();
				return array(0, ($objToday->dayBegin - 1), $GLOBALS['TL_LANG']['MSC']['cal_empty']);
				break;
		}
	}
}
