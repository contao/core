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
 * Front end module "event menu".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleEventMenu extends \ModuleCalendar
{

	/**
	 * Display a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			/** @var \BackendTemplate|object $objTemplate */
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['eventmenu'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		switch ($this->cal_format)
		{
			case 'cal_year':
				$this->compileYearlyMenu();
				break;

			default:
			case 'cal_month':
				$this->compileMonthlyMenu();
				break;

			case 'cal_day':
				$this->cal_ctemplate = 'cal_mini';
				parent::compile();
				break;
		}
	}


	/**
	 * Generate the yearly menu
	 */
	protected function compileYearlyMenu()
	{
		$arrData = array();

		/** @var \FrontendTemplate|object $objTemplate */
		$objTemplate = new \FrontendTemplate('mod_eventmenu_year');

		$this->Template = $objTemplate;
		$arrAllEvents = $this->getAllEvents($this->cal_calendar, 0, 2145913200);

		foreach ($arrAllEvents as $intDay=>$arrDay)
		{
			foreach ($arrDay as $arrEvents)
			{
				$arrData[substr($intDay, 0, 4)] += count($arrEvents);
			}
		}

		// Sort data
		($this->cal_order == 'ascending') ? ksort($arrData) : krsort($arrData);

		$arrItems = array();
		$count = 0;
		$limit = count($arrData);
		$strUrl = \Environment::get('request');

		// Get the current "jumpTo" page
		if ($this->jumpTo && ($objTarget = $this->objModel->getRelated('jumpTo')) !== null)
		{
			$strUrl = $this->generateFrontendUrl($objTarget->row());
		}

		// Prepare navigation
		foreach ($arrData as $intYear=>$intCount)
		{
			$intDate = $intYear;
			$quantity = sprintf((($intCount < 2) ? $GLOBALS['TL_LANG']['MSC']['entry'] : $GLOBALS['TL_LANG']['MSC']['entries']), $intCount);

			$arrItems[$intYear]['date'] = $intDate;
			$arrItems[$intYear]['link'] = $intYear;
			$arrItems[$intYear]['href'] = $strUrl . (\Config::get('disableAlias') ? '&amp;' : '?') . 'year=' . $intDate;
			$arrItems[$intYear]['title'] = specialchars($intYear . ' (' . $quantity . ')');
			$arrItems[$intYear]['class'] = trim(((++$count == 1) ? 'first ' : '') . (($count == $limit) ? 'last' : ''));
			$arrItems[$intYear]['isActive'] = (\Input::get('year') == $intDate);
			$arrItems[$intYear]['quantity'] = $quantity;
		}

		$this->Template->items = $arrItems;
		$this->Template->showQuantity = ($this->cal_showQuantity != '') ? true : false;
	}


	/**
	 * Generate the monthly menu
	 */
	protected function compileMonthlyMenu()
	{
		$arrData = array();

		/** @var \FrontendTemplate|object $objTemplate */
		$objTemplate = new \FrontendTemplate('mod_eventmenu');

		$this->Template = $objTemplate;
		$arrAllEvents = $this->getAllEvents($this->cal_calendar, 0, 2145913200);

		foreach ($arrAllEvents as $intDay=>$arrDay)
		{
			foreach ($arrDay as $arrEvents)
			{
				$arrData[substr($intDay, 0, 4)][substr($intDay, 4, 2)] += count($arrEvents);
			}
		}

		// Sort data
		foreach (array_keys($arrData) as $key)
		{
			($this->cal_order == 'ascending') ? ksort($arrData[$key]) : krsort($arrData[$key]);
		}

		($this->cal_order == 'ascending') ? ksort($arrData) : krsort($arrData);

		$arrItems = array();
		$strUrl = \Environment::get('request');

		// Get the current "jumpTo" page
		if ($this->jumpTo && ($objTarget = $this->objModel->getRelated('jumpTo')) !== null)
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
				$arrItems[$intYear][$intMonth]['href'] = $strUrl . (\Config::get('disableAlias') ? '&amp;' : '?') . 'month=' . $intDate;
				$arrItems[$intYear][$intMonth]['title'] = specialchars($GLOBALS['TL_LANG']['MONTHS'][$intMonth].' '.$intYear . ' (' . $quantity . ')');
				$arrItems[$intYear][$intMonth]['class'] = trim(((++$count == 1) ? 'first ' : '') . (($count == $limit) ? 'last' : ''));
				$arrItems[$intYear][$intMonth]['isActive'] = (\Input::get('month') == $intDate);
				$arrItems[$intYear][$intMonth]['quantity'] = $quantity;
			}
		}

		$this->Template->items = $arrItems;
		$this->Template->showQuantity = ($this->cal_showQuantity != '') ? true : false;
		$this->Template->url = $strUrl . (\Config::get('disableAlias') ? '&amp;' : '?');
		$this->Template->activeYear = \Input::get('year');
	}
}
