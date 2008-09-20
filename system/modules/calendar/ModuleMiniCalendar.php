<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
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
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleMiniCalendar
 *
 * Front end module "mini calendar".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleMiniCalendar extends Events
{

	/**
	 * Current date object
	 * @var integer
	 */
	protected $Date;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_minicalendar';


	/**
	 * Do not show the module if no calendar has been selected
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### MINI CALENDAR ###';

			return $objTemplate->parse();
		}

		$this->cal_calendar = $this->sortOutProtected(deserialize($this->cal_calendar, true));

		// Return if there are no calendars
		if (!is_array($this->cal_calendar) || count($this->cal_calendar) < 1)
		{
			return '';
		}

		$this->strUrl = preg_replace('/\?.*$/i', '', $this->Environment->request);
		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$this->Date = new Date(($this->Input->get('day') ? $this->Input->get('day') : date('Ymd')), 'Ymd');

		$intYear = date('Y', $this->Date->tstamp);
		$intMonth = date('m', $this->Date->tstamp);

		// Previous month
		$prevMonth = ($intMonth == 1) ? 12 : ($intMonth - 1);
		$prevYear = ($intMonth == 1) ? ($intYear - 1) : $intYear;
		$lblPrevious = $GLOBALS['TL_LANG']['MONTHS'][($prevMonth - 1)] . ' ' . $prevYear;

		$this->Template->previous = sprintf('<a href="%s" title="%s">%s</a>',
											($this->strUrl . ($GLOBALS['TL_CONFIG']['disableAlias'] ? '?id=' . $this->Input->get('id') . '&amp;' : '?') . 'day=' . $prevYear . ((strlen($prevMonth) < 2) ? '0' : '') . $prevMonth . '01'),
											specialchars($lblPrevious),
											(strlen($this->cal_previous) ? $this->cal_previous : '&lt;'));

		// Current month
		$this->Template->current = $GLOBALS['TL_LANG']['MONTHS'][(date('m', $this->Date->tstamp) - 1)] .  ' ' . date('Y', $this->Date->tstamp);

		// Next month
		$nextMonth = ($intMonth == 12) ? 1 : ($intMonth + 1);
		$nextYear = ($intMonth == 12) ? ($intYear + 1) : $intYear;
		$lblNext = $GLOBALS['TL_LANG']['MONTHS'][($nextMonth - 1)] . ' ' . $nextYear;

		$this->Template->next = sprintf('<a href="%s" title="%s">%s</a>',
										($this->strUrl . ($GLOBALS['TL_CONFIG']['disableAlias'] ? '?id=' . $this->Input->get('id') . '&amp;' : '?') . 'day=' . $nextYear . ((strlen($nextMonth) < 2) ? '0' : '') . $nextMonth . '01'),
										specialchars($lblNext),
										(strlen($this->cal_next) ? $this->cal_next : '&gt;'));

		// Set week start day
		if (!$this->cal_startDay)
		{
			$this->cal_startDay = 0;
		}

		// Get current "jumpTo" page
		$objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($this->jumpTo);

		if ($objPage->numRows)
		{
			$this->strUrl = $this->generateFrontendUrl($objPage->row());
		}

		$this->Template->days = $this->compileDays();
		$this->Template->weeks = $this->compileWeeks();
	}


	/**
	 * Return the week days and labels as array
	 * @return array
	 */
	private function compileDays()
	{
		$arrDays = array();

		for ($i=0; $i<7; $i++)
		{
			$intCurrentDay = ($i + $this->cal_startDay) % 7;
			$arrDays[$intCurrentDay] = utf8_substr($GLOBALS['TL_LANG']['DAYS'][$intCurrentDay], 0, 2);
		}

		return array_values($arrDays);
	}


	/**
	 * Return all weeks of the current month as array
	 * @return array
	 */
	private function compileWeeks()
	{
		$intDaysInMonth = date('t', $this->Date->monthBegin);
		$intFirstDayOffset = date('w', $this->Date->monthBegin) - $this->cal_startDay;

		while ($intFirstDayOffset < 0)
		{
			$intFirstDayOffset += 7;
		}

		$intColumnCount = -1;
		$intNumberOfRows = ceil(($intDaysInMonth + $intFirstDayOffset) / 7);
		$arrAllEvents = $this->getAllEvents($this->cal_calendar, $this->Date->monthBegin, $this->Date->monthEnd);
		$arrDays = array();
		
		// Compile days
		for ($i=1; $i<=($intNumberOfRows * 7); $i++)
		{
			$intWeek = floor(++$intColumnCount / 7);
			$intDay = $i - $intFirstDayOffset;
			$intCurrentDay = ($i + $this->cal_startDay) % 7;

			$strWeekClass = 'week_' . $intWeek;
			$strWeekClass .= ($intWeek == 0) ? ' first' : '';
			$strWeekClass .= ($intWeek == ($intNumberOfRows - 1)) ? ' last' : '';

			$strClass = ($intCurrentDay < 2) ? ' weekend' : '';
			$strClass .= ($i == 1 || $i == 8 || $i == 15 || $i == 22 || $i == 29 || $i == 36) ? ' col_first' : '';
			$strClass .= ($i == 7 || $i == 14 || $i == 21 || $i == 28 || $i == 35 || $i == 42) ? ' col_last' : '';

			// Empty cell
			if ($intDay < 1 || $intDay > $intDaysInMonth)
			{
				$arrDays[$strWeekClass][$i]['label'] = '&nbsp;';
				$arrDays[$strWeekClass][$i]['class'] = 'days empty' . $strClass;

				continue;
			}

			$intKey = date('Ym', $this->Date->tstamp) . ((strlen($intDay) < 2) ? '0' . $intDay : $intDay);
			$strClass .= ($intKey == date('Ymd')) ? ' today' : '';

			// Inactive days
			if (!array_key_exists($intKey, $arrAllEvents))
			{
				$arrDays[$strWeekClass][$i]['label'] = $intDay;
				$arrDays[$strWeekClass][$i]['class'] = 'days' . $strClass;

				continue;
			}

			$intCount = 0;

			// Count all events
			foreach ($arrAllEvents[$intKey] as $v)
			{
				$intCount += count($v);
			}

			$arrDays[$strWeekClass][$i]['label'] = $intDay;
			$arrDays[$strWeekClass][$i]['class'] = 'days active' . $strClass;
			$arrDays[$strWeekClass][$i]['href'] = $this->strUrl . ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?') . 'day=' . $intKey;
			$arrDays[$strWeekClass][$i]['title'] = sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['cal_events']), $intCount);
		}

		return $arrDays;
	}
}

?>