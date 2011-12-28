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
 * @package    News
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleEventMenu
 *
 * Front end module "event menu".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleEventMenu extends ModuleCalendar
{

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### EVENT MENU ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate module
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

		$this->Template = new FrontendTemplate('mod_eventmenu_year');
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

		// Get current "jumpTo" page
		$objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($this->jumpTo);

		$strUrl = $this->generateFrontendUrl($objPage->row());
		$count = 0;
		$limit = count($arrData);

		// Prepare navigation
		foreach ($arrData as $intYear=>$intCount)
		{
			$intDate = $intYear;
			$quantity = sprintf((($intCount < 2) ? $GLOBALS['TL_LANG']['MSC']['entry'] : $GLOBALS['TL_LANG']['MSC']['entries']), $intCount);

			$arrItems[$intYear]['date'] = $intDate;
			$arrItems[$intYear]['link'] = $intYear;
			$arrItems[$intYear]['href'] = $strUrl . ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?') . 'year=' . $intDate;
			$arrItems[$intYear]['title'] = specialchars($intYear . ' (' . $quantity . ')');
			$arrItems[$intYear]['class'] = trim(((++$count == 1) ? 'first ' : '') . (($count == $limit) ? 'last' : ''));
			$arrItems[$intYear]['isActive'] = ($this->Input->get('year') == $intDate);
			$arrItems[$intYear]['quantity'] = $quantity;
		}

		$this->Template->items = $arrItems;
		$this->Template->showQuantity = strlen($this->cal_showQuantity) ? true : false;
	}


	/**
	 * Generate the monthly menu
	 */
	protected function compileMonthlyMenu()
	{
		$arrData = array();

		$this->Template = new FrontendTemplate('mod_eventmenu');
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

		// Get current "jumpTo" page
		$objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($this->jumpTo);

		// Prepare navigation
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
				$arrItems[$intYear][$intMonth]['href'] = $this->generateFrontendUrl($objPage->row()) . ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?') . 'month=' . $intDate;
				$arrItems[$intYear][$intMonth]['title'] = specialchars($GLOBALS['TL_LANG']['MONTHS'][$intMonth].' '.$intYear . ' (' . $quantity . ')');
				$arrItems[$intYear][$intMonth]['class'] = trim(((++$count == 1) ? 'first ' : '') . (($count == $limit) ? 'last' : ''));
				$arrItems[$intYear][$intMonth]['isActive'] = ($this->Input->get('month') == $intDate);
				$arrItems[$intYear][$intMonth]['quantity'] = $quantity;
			}
		}

		$this->Template->items = $arrItems;
		$this->Template->showQuantity = strlen($this->cal_showQuantity) ? true : false;
	}
}

?>