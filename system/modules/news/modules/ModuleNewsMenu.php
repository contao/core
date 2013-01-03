<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package News
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleNewsMenu
 *
 * Front end module "news archive".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 */
class ModuleNewsMenu extends \ModuleNews
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
	protected $strTemplate = 'mod_newsmenu';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### NEWS ARCHIVE MENU ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->news_archives = $this->sortOutProtected(deserialize($this->news_archives));

		if (!is_array($this->news_archives) || empty($this->news_archives))
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		switch ($this->news_format)
		{
			case 'news_year':
				$this->compileYearlyMenu();
				break;

			default:
			case 'news_month':
				$this->compileMonthlyMenu();
				break;

			case 'news_day':
				$this->compileDailyMenu();
				break;
		}
	}


	/**
	 * Generate the yearly menu
	 */
	protected function compileYearlyMenu()
	{
		$arrData = array();
		$this->Template = new \FrontendTemplate('mod_newsmenu_year');
		$objArchives = \NewsModel::findPublishedByPids($this->news_archives);

		if ($objArchives !== null)
		{
			while ($objArchives->next())
			{
				++$arrData[date('Y', $objArchives->date)];
			}
		}

		// Sort the data
		($this->news_order == 'ascending') ? ksort($arrData) : krsort($arrData);

		$arrItems = array();
		$count = 0;
		$limit = count($arrData);
		$strUrl = \Environment::get('request');

		// Get the current "jumpTo" page
		if ($this->jumpTo && ($objTarget = $this->objModel->getRelated('jumpTo')) !== null)
		{
			$strUrl = $this->generateFrontendUrl($objTarget->row());
		}

		// Prepare the navigation
		foreach ($arrData as $intYear=>$intCount)
		{
			$intDate = $intYear;
			$quantity = sprintf((($intCount < 2) ? $GLOBALS['TL_LANG']['MSC']['entry'] : $GLOBALS['TL_LANG']['MSC']['entries']), $intCount);

			$arrItems[$intYear]['date'] = $intDate;
			$arrItems[$intYear]['link'] = $intYear;
			$arrItems[$intYear]['href'] = $strUrl . ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?') . 'year=' . $intDate;
			$arrItems[$intYear]['title'] = specialchars($intYear . ' (' . $quantity . ')');
			$arrItems[$intYear]['class'] = trim(((++$count == 1) ? 'first ' : '') . (($count == $limit) ? 'last' : ''));
			$arrItems[$intYear]['isActive'] = (\Input::get('year') == $intDate);
			$arrItems[$intYear]['quantity'] = $quantity;
		}

		$this->Template->items = $arrItems;
		$this->Template->showQuantity = ($this->news_showQuantity != '');
	}


	/**
	 * Generate the monthly menu
	 */
	protected function compileMonthlyMenu()
	{
		$arrData = array();
		$objArchives = \NewsModel::findPublishedByPids($this->news_archives);

		if ($objArchives !== null)
		{
			while ($objArchives->next())
			{
				++$arrData[date('Y', $objArchives->date)][date('m', $objArchives->date)];
			}
		}

		// Sort the data
		foreach (array_keys($arrData) as $key)
		{
			($this->news_order == 'ascending') ? ksort($arrData[$key]) : krsort($arrData[$key]);
		}

		($this->news_order == 'ascending') ? ksort($arrData) : krsort($arrData);

		$strUrl = '';
		$arrItems = array();

		// Get the current "jumpTo" page
		if (($objTarget = $this->objModel->getRelated('jumpTo')) !== null)
		{
			$strUrl = $this->generateFrontendUrl($objTarget->row());
		}

		// Prepare the navigation
		foreach ($arrData as $intYear=>$arrMonth)
		{
			$count = 0;
			$limit = count($arrMonth);

			foreach ($arrMonth as $intMonth=>$intCount)
			{
				$intDate = $intYear . $intMonth;
				$intMonth = (intval($intMonth) - 1);

				$quantity = sprintf((($intCount < 2) ? $GLOBALS['TL_LANG']['MSC']['entry'] : $GLOBALS['TL_LANG']['MSC']['entries']), $intCount);

				$arrItems[$intYear][$intMonth]['date'] = $intDate;
				$arrItems[$intYear][$intMonth]['link'] = $GLOBALS['TL_LANG']['MONTHS'][$intMonth] . ' ' . $intYear;
				$arrItems[$intYear][$intMonth]['href'] = $strUrl . ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?') . 'month=' . $intDate;
				$arrItems[$intYear][$intMonth]['title'] = specialchars($GLOBALS['TL_LANG']['MONTHS'][$intMonth].' '.$intYear . ' (' . $quantity . ')');
				$arrItems[$intYear][$intMonth]['class'] = trim(((++$count == 1) ? 'first ' : '') . (($count == $limit) ? 'last' : ''));
				$arrItems[$intYear][$intMonth]['isActive'] = (\Input::get('month') == $intDate);
				$arrItems[$intYear][$intMonth]['quantity'] = $quantity;
			}
		}

		$this->Template->items = $arrItems;
		$this->Template->showQuantity = ($this->news_showQuantity != '') ? true : false;
	}


	/**
	 * Generate the dayil menu
	 */
	protected function compileDailyMenu()
	{
		$arrData = array();
		$this->Template = new \FrontendTemplate('mod_newsmenu_day');
		$objArchives = \NewsModel::findPublishedByPids($this->news_archives);

		if ($objArchives !== null)
		{
			while ($objArchives->next())
			{
				++$arrData[date('Ymd', $objArchives->date)];
			}
		}

		// Sort the data
		krsort($arrData);
		$strUrl = \Environment::get('request');

		// Get the current "jumpTo" page
		if ($this->jumpTo && ($objTarget = $this->objModel->getRelated('jumpTo')) !== null)
		{
			$strUrl = $this->generateFrontendUrl($objTarget->row());
		}

		$this->Date = \Input::get('day') ? new \Date(\Input::get('day'), 'Ymd') : new \Date();

		$intYear = date('Y', $this->Date->tstamp);
		$intMonth = date('m', $this->Date->tstamp);

		$this->Template->intYear = $intYear;
		$this->Template->intMonth = $intMonth;

		// Previous month
		$prevMonth = ($intMonth == 1) ? 12 : ($intMonth - 1);
		$prevYear = ($intMonth == 1) ? ($intYear - 1) : $intYear;
		$lblPrevious = $GLOBALS['TL_LANG']['MONTHS'][($prevMonth - 1)] . ' ' . $prevYear;

		$this->Template->prevHref = $strUrl . ($GLOBALS['TL_CONFIG']['disableAlias'] ? '?id=' . \Input::get('id') . '&amp;' : '?') . 'day=' . $prevYear . ((strlen($prevMonth) < 2) ? '0' : '') . $prevMonth . '01';
		$this->Template->prevTitle = specialchars($lblPrevious);
		$this->Template->prevLink = $GLOBALS['TL_LANG']['MSC']['news_previous'] . ' ' . $lblPrevious;
		$this->Template->prevLabel = $GLOBALS['TL_LANG']['MSC']['news_previous'];

		// Current month
		$this->Template->current = $GLOBALS['TL_LANG']['MONTHS'][(date('m', $this->Date->tstamp) - 1)] .  ' ' . date('Y', $this->Date->tstamp);

		// Next month
		$nextMonth = ($intMonth == 12) ? 1 : ($intMonth + 1);
		$nextYear = ($intMonth == 12) ? ($intYear + 1) : $intYear;
		$lblNext = $GLOBALS['TL_LANG']['MONTHS'][($nextMonth - 1)] . ' ' . $nextYear;

		$this->Template->nextHref = $strUrl . ($GLOBALS['TL_CONFIG']['disableAlias'] ? '?id=' . \Input::get('id') . '&amp;' : '?') . 'day=' . $nextYear . ((strlen($nextMonth) < 2) ? '0' : '') . $nextMonth . '01';
		$this->Template->nextTitle = specialchars($lblNext);
		$this->Template->nextLink = $lblNext . ' ' . $GLOBALS['TL_LANG']['MSC']['news_next'];
		$this->Template->nextLabel = $GLOBALS['TL_LANG']['MSC']['news_next'];

		// Set week start day
		if (!$this->news_startDay)
		{
			$this->news_startDay = 0;
		}

		$this->Template->days = $this->compileDays();
		$this->Template->weeks = $this->compileWeeks($arrData, $strUrl);

		$this->Template->showQuantity = ($this->news_showQuantity != '') ? true : false;
	}


	/**
	 * Return the week days and labels as array
	 * @return array
	 */
	protected function compileDays()
	{
		$arrDays = array();

		for ($i=0; $i<7; $i++)
		{
			$intCurrentDay = ($i + $this->news_startDay) % 7;
			$arrDays[$intCurrentDay] = $GLOBALS['TL_LANG']['DAYS'][$intCurrentDay];
		}

		return array_values($arrDays);
	}


	/**
	 * Return all weeks of the current month as array
	 * @param array
	 * @param string
	 * @return array
	 */
	protected function compileWeeks($arrData, $strUrl)
	{
		$intDaysInMonth = date('t', $this->Date->monthBegin);
		$intFirstDayOffset = date('w', $this->Date->monthBegin) - $this->news_startDay;

		if ($intFirstDayOffset < 0)
		{
			$intFirstDayOffset += 7;
		}

		$intColumnCount = -1;
		$intNumberOfRows = ceil(($intDaysInMonth + $intFirstDayOffset) / 7);
		$arrDays = array();

		// Compile days
		for ($i=1; $i<=($intNumberOfRows * 7); $i++)
		{
			$intWeek = floor(++$intColumnCount / 7);
			$intDay = $i - $intFirstDayOffset;
			$intCurrentDay = ($i + $this->news_startDay) % 7;

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
				$arrDays[$strWeekClass][$i]['class'] = 'days empty' . $strClass ;
				$arrDays[$strWeekClass][$i]['events'] = array();

				continue;
			}

			$intKey = date('Ym', $this->Date->tstamp) . ((strlen($intDay) < 2) ? '0' . $intDay : $intDay);
			$strClass .= ($intKey == date('Ymd')) ? ' today' : '';

			// Inactive days
			if (empty($intKey) || !isset($arrData[$intKey]))
			{
				$arrDays[$strWeekClass][$i]['label'] = $intDay;
				$arrDays[$strWeekClass][$i]['class'] = 'days' . $strClass;
				$arrDays[$strWeekClass][$i]['events'] = array();

				continue;
			}

			$arrDays[$strWeekClass][$i]['label'] = $intDay;
			$arrDays[$strWeekClass][$i]['class'] = 'days active' . $strClass;
			$arrDays[$strWeekClass][$i]['href'] = $strUrl . ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?') . 'day=' . $intKey;
			$arrDays[$strWeekClass][$i]['title'] = sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['news_items']), $arrData[$intKey]);
		}

		return $arrDays;
	}
}
