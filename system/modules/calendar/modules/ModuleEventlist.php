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
 * Front end module "event list".
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleEventlist extends \Events
{

	/**
	 * Current date object
	 * @var \Date
	 */
	protected $Date;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_eventlist';


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

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['eventlist'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->cal_calendar = $this->sortOutProtected(deserialize($this->cal_calendar, true));

		// Return if there are no calendars
		if (!is_array($this->cal_calendar) || empty($this->cal_calendar))
		{
			return '';
		}

		// Show the event reader if an item has been selected
		if ($this->cal_readerModule > 0  && (isset($_GET['events']) || (\Config::get('useAutoItem') && isset($_GET['auto_item']))))
		{
			return $this->getFrontendModule($this->cal_readerModule, $this->strColumn);
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		/** @var \PageModel $objPage */
		global $objPage;

		$blnClearInput = false;

		$intYear = \Input::get('year');
		$intMonth = \Input::get('month');
		$intDay = \Input::get('day');

		// Jump to the current period
		if (!isset($_GET['year']) && !isset($_GET['month']) && !isset($_GET['day']))
		{
			switch ($this->cal_format)
			{
				case 'cal_year':
					$intYear = date('Y');
					break;

				case 'cal_month':
					$intMonth = date('Ym');
					break;

				case 'cal_day':
					$intDay = date('Ymd');
					break;
			}

			$blnClearInput = true;
		}

		$blnDynamicFormat = (!$this->cal_ignoreDynamic && in_array($this->cal_format, array('cal_day', 'cal_month', 'cal_year')));

		// Create the date object
		try
		{
			if ($blnDynamicFormat && $intYear)
			{
				$this->Date = new \Date($intYear, 'Y');
				$this->cal_format = 'cal_year';
				$this->headline .= ' ' . date('Y', $this->Date->tstamp);
			}
			elseif ($blnDynamicFormat && $intMonth)
			{
				$this->Date = new \Date($intMonth, 'Ym');
				$this->cal_format = 'cal_month';
				$this->headline .= ' ' . \Date::parse('F Y', $this->Date->tstamp);
			}
			elseif ($blnDynamicFormat && $intDay)
			{
				$this->Date = new \Date($intDay, 'Ymd');
				$this->cal_format = 'cal_day';
				$this->headline .= ' ' . \Date::parse($objPage->dateFormat, $this->Date->tstamp);
			}
			else
			{
				$this->Date = new \Date();
			}
		}
		catch (\OutOfBoundsException $e)
		{
			/** @var \PageError404 $objHandler */
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($objPage->id);
		}

		list($strBegin, $strEnd, $strEmpty) = $this->getDatesFromFormat($this->Date, $this->cal_format);

		// Get all events
		$arrAllEvents = $this->getAllEvents($this->cal_calendar, $strBegin, $strEnd);
		$sort = ($this->cal_order == 'descending') ? 'krsort' : 'ksort';

		// Sort the days
		$sort($arrAllEvents);

		// Sort the events
		foreach (array_keys($arrAllEvents) as $key)
		{
			$sort($arrAllEvents[$key]);
		}

		$arrEvents = array();
		$dateBegin = date('Ymd', $strBegin);
		$dateEnd = date('Ymd', $strEnd);

		// Remove events outside the scope
		foreach ($arrAllEvents as $key=>$days)
		{
			if ($key < $dateBegin || $key > $dateEnd)
			{
				continue;
			}

			foreach ($days as $day=>$events)
			{
				foreach ($events as $event)
				{
					$event['firstDay'] = $GLOBALS['TL_LANG']['DAYS'][date('w', $day)];
					$event['firstDate'] = \Date::parse($objPage->dateFormat, $day);
					$event['datetime'] = date('Y-m-d', $day);

					$arrEvents[] = $event;
				}
			}
		}

		unset($arrAllEvents);
		$total = count($arrEvents);
		$limit = $total;
		$offset = 0;

		// Overall limit
		if ($this->cal_limit > 0)
		{
			$total = min($this->cal_limit, $total);
			$limit = $total;
		}

		// Pagination
		if ($this->perPage > 0)
		{
			$id = 'page_e' . $this->id;
			$page = (\Input::get($id) !== null) ? \Input::get($id) : 1;

			// Do not index or cache the page if the page number is outside the range
			if ($page < 1 || $page > max(ceil($total/$this->perPage), 1))
			{
				/** @var \PageError404 $objHandler */
				$objHandler = new $GLOBALS['TL_PTY']['error_404']();
				$objHandler->generate($objPage->id);
			}

			$offset = ($page - 1) * $this->perPage;
			$limit = min($this->perPage + $offset, $total);

			$objPagination = new \Pagination($total, $this->perPage, \Config::get('maxPaginationLinks'), $id);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		$strMonth = '';
		$strDate = '';
		$strEvents = '';
		$dayCount = 0;
		$eventCount = 0;
		$headerCount = 0;
		$imgSize = false;

		// Override the default image size
		if ($this->imgSize != '')
		{
			$size = deserialize($this->imgSize);

			if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]))
			{
				$imgSize = $this->imgSize;
			}
		}

		// Parse events
		for ($i=$offset; $i<$limit; $i++)
		{
			$event = $arrEvents[$i];
			$blnIsLastEvent = false;

			// Last event on the current day
			if (($i+1) == $limit || !isset($arrEvents[($i+1)]['firstDate']) || $event['firstDate'] != $arrEvents[($i+1)]['firstDate'])
			{
				$blnIsLastEvent = true;
			}

			/** @var \FrontendTemplate|object $objTemplate */
			$objTemplate = new \FrontendTemplate($this->cal_template);
			$objTemplate->setData($event);

			// Month header
			if ($strMonth != $event['month'])
			{
				$objTemplate->newMonth = true;
				$strMonth = $event['month'];
			}

			// Day header
			if ($strDate != $event['firstDate'])
			{
				$headerCount = 0;
				$objTemplate->header = true;
				$objTemplate->classHeader = ((($dayCount % 2) == 0) ? ' even' : ' odd') . (($dayCount == 0) ? ' first' : '') . (($event['firstDate'] == $arrEvents[($limit-1)]['firstDate']) ? ' last' : '');
				$strDate = $event['firstDate'];

				++$dayCount;
			}

			// Show the teaser text of redirect events (see #6315)
			if (is_bool($event['details']))
			{
				$objTemplate->details = $event['teaser'];
			}

			// Add the template variables
			$objTemplate->classList = $event['class'] . ((($headerCount % 2) == 0) ? ' even' : ' odd') . (($headerCount == 0) ? ' first' : '') . ($blnIsLastEvent ? ' last' : '') . ' cal_' . $event['parent'];
			$objTemplate->classUpcoming = $event['class'] . ((($eventCount % 2) == 0) ? ' even' : ' odd') . (($eventCount == 0) ? ' first' : '') . ((($offset + $eventCount + 1) >= $limit) ? ' last' : '') . ' cal_' . $event['parent'];
			$objTemplate->readMore = specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $event['title']));
			$objTemplate->more = $GLOBALS['TL_LANG']['MSC']['more'];
			$objTemplate->locationLabel = $GLOBALS['TL_LANG']['MSC']['location'];

			// Short view
			if ($this->cal_noSpan)
			{
				$objTemplate->day = $event['day'];
				$objTemplate->date = $event['date'];
				$objTemplate->span = ($event['time'] == '' && $event['day'] == '') ? $event['date'] : '';
			}
			else
			{
				$objTemplate->day = $event['firstDay'];
				$objTemplate->date = $event['firstDate'];
				$objTemplate->span = '';
			}

			$objTemplate->addImage = false;

			// Add an image
			if ($event['addImage'] && $event['singleSRC'] != '')
			{
				$objModel = \FilesModel::findByUuid($event['singleSRC']);

				if ($objModel === null)
				{
					if (!\Validator::isUuid($event['singleSRC']))
					{
						$objTemplate->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
					}
				}
				elseif (is_file(TL_ROOT . '/' . $objModel->path))
				{
					if ($imgSize)
					{
						$event['size'] = $imgSize;
					}

					$event['singleSRC'] = $objModel->path;
					$this->addImageToTemplate($objTemplate, $event);
				}
			}

			$objTemplate->enclosure = array();

			// Add enclosure
			if ($event['addEnclosure'])
			{
				$this->addEnclosuresToTemplate($objTemplate, $event);
			}

			$strEvents .= $objTemplate->parse();

			++$eventCount;
			++$headerCount;
		}

		// No events found
		if ($strEvents == '')
		{
			$strEvents = "\n" . '<div class="empty">' . $strEmpty . '</div>' . "\n";
		}

		// See #3672
		$this->Template->headline = $this->headline;
		$this->Template->events = $strEvents;

		// Clear the $_GET array (see #2445)
		if ($blnClearInput)
		{
			\Input::setGet('year', null);
			\Input::setGet('month', null);
			\Input::setGet('day', null);
		}
	}
}
