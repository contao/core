<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleEventlist
 *
 * Front end module "event list".
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Controller
 */
class ModuleEventlist extends Events
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
	protected $strTemplate = 'mod_eventlist';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### EVENT LIST ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->cal_calendar = $this->sortOutProtected(deserialize($this->cal_calendar, true));

		// Return if there are no calendars
		if (!is_array($this->cal_calendar) || count($this->cal_calendar) < 1)
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		$this->Date = $this->Input->get('day') ? new Date($this->Input->get('day'), 'Ymd') : new Date();
		list($strBegin, $strEnd, $strEmpty) = $this->getDatesFromFormat($this->Date, $this->cal_format, $this->cal_startDay);

		// Get all events
		$arrAllEvents = $this->getAllEvents($this->cal_calendar, $strBegin, $strEnd);
		ksort($arrAllEvents);

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
					$event['firstDate'] = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $day);

					$arrEvents[] = $event;
				}
			}
		}

		unset($arrAllEvents);
		$total = count($arrEvents);
		$limit = $total;
		$offset = 0;

		// Pagination
		if ($this->perPage > 0)
		{
			$page = $this->Input->get('page') ? $this->Input->get('page') : 1;
			$offset = ($page - 1) * $this->perPage;
			$limit = min($this->perPage + $offset, $total);

			$objPagination = new Pagination($total, $this->perPage);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		$strMonth = '';
		$strDate = '';
		$strEvents = '';
		$dayCount = 0;
		$eventCount = 0;
		$imgSize = false;

		// Override the default image size
		if ($this->imgSize != '')
		{
			$size = deserialize($this->imgSize);

			if ($size[0] > 0 || $size[1] > 0)
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

			$objTemplate = new FrontendTemplate($this->cal_template);

			// Store raw data
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
				$eventCount = 0;
				$objTemplate->header = true;
				$objTemplate->hclass = ((($dayCount % 2) == 0) ? ' even' : ' odd') . (($dayCount == 0) ? ' first' : '') . (($event['firstDate'] == $arrEvents[($limit-1)]['firstDate']) ? ' last' : '');
				$strDate = $event['firstDate'];

				++$dayCount;
			}

			// Add template variables
			$objTemplate->link = $event['href'];
			$objTemplate->class = $event['class'] . ((($eventCount % 2) == 0) ? ' even' : ' odd') . (($eventCount == 0) ? ' first' : '') . ($blnIsLastEvent ? ' last' : '') . ' cal_' . $event['parent'];
			$objTemplate->readMore = specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $event['title']));
			$objTemplate->more = $GLOBALS['TL_LANG']['MSC']['more'];

			// Short view
			if ($this->cal_noSpan)
			{
				$objTemplate->day = $event['day'];
				$objTemplate->date = $event['date'];
				$objTemplate->span = (!strlen($event['time']) && !strlen($event['day'])) ? $event['date'] : '';
			}
			else
			{
				$objTemplate->day = $event['firstDay'];
				$objTemplate->date = $event['firstDate'];
				$objTemplate->span = '';
			}

			$objTemplate->addImage = false;

			// Add image
			if ($event['addImage'] && is_file(TL_ROOT . '/' . $event['singleSRC']))
			{
				if ($imgSize)
				{
					$event['size'] = $imgSize;
				}

				$this->addImageToTemplate($objTemplate, $event);
			}

			$objTemplate->enclosure = array();

			// Add enclosure
			if ($event['addEnclosure'])
			{
				$this->addEnclosuresToTemplate($objTemplate, $event);
			}

			$strEvents .= $objTemplate->parse();
			++$eventCount;
		}

		// No events found
		if (!strlen($strEvents))
		{
			$strEvents = "\n" . '<div class="empty">' . $strEmpty . '</div>' . "\n";
		}

		$this->Template->events = $strEvents;
	}
}

?>