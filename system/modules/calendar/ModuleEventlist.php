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
 * Class ModuleEventlist
 *
 * Front end module "event list".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
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
		$this->Date = new Date(($this->Input->get('day') ? $this->Input->get('day') : date('Ymd')), 'Ymd');

		// Set current format
		switch ($this->cal_format)
		{
			case 'cal_day':
				$strBegin = $this->Date->dayBegin;
				$strEnd = $this->Date->dayEnd;
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyDay'];
				break;

			case 'cal_week':
				$strBegin = $this->Date->dayBegin - ((date('w', $this->Date->dayBegin) - $this->cal_startDay) * 86400);
				$strEnd = (strtotime('+7 days', $strBegin) - 1);
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyWeek'];
				break;

			case 'cal_year':
				$strBegin = $this->Date->yearBegin;
				$strEnd = $this->Date->yearEnd;
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyYear'];
				break;

			case 'cal_two':
				$strBegin = $this->Date->yearBegin;
				$strEnd = (strtotime('+2 years', $strBegin) - 1);
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyYear'];
				break;

			case 'next_7':
				$strBegin = $this->Date->dayBegin;
				$strEnd = (strtotime('+7 days', $strBegin) - 1);
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyWeek'];
				break;

			case 'next_14':
				$strBegin = $this->Date->dayBegin;
				$strEnd = (strtotime('+14 days', $strBegin) - 1);
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyWeek'];
				break;

			case 'next_30':
				$strBegin = $this->Date->dayBegin;
				$strEnd = (strtotime('+1 month', $strBegin) - 1);
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyMonth'];
				break;

			case 'next_90':
				$strBegin = $this->Date->dayBegin;
				$strEnd = (strtotime('+3 month', $strBegin) - 1);
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyMonth'];
				break;

			case 'next_180':
				$strBegin = $this->Date->dayBegin;
				$strEnd = (strtotime('+6 month', $strBegin) - 1);
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyMonth'];
				break;

			case 'next_365':
				$strBegin = $this->Date->dayBegin;
				$strEnd = (strtotime('+1 year', $strBegin) - 1);
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyYear'];
				break;

			default:
				$strBegin = $this->Date->monthBegin;
				$strEnd = $this->Date->monthEnd;
				$strEmpty = $GLOBALS['TL_LANG']['MSC']['cal_emptyMonth'];
				break;
		}

		// Get all events
		$arrAllEvents = $this->getAllEvents($this->cal_calendar, $strBegin, $strEnd);
		ksort($arrAllEvents);

		$dateBegin = date('Ymd', $strBegin);
		$dateEnd = date('Ymd', $strEnd);

		// Unset events outside the scope
		foreach (array_keys($arrAllEvents) as $key)
		{
			if ($key < $dateBegin || $key > $dateEnd)
			{
				unset($arrAllEvents[$key]);
			}
		}

		$dayCount = 0;
		$dayMax = count($arrAllEvents);
		$strEvents = '';

		// List events
		foreach ($arrAllEvents as $days)
		{
			++$dayCount;
			$strHclass = '';

			if ($dayCount == 1)
			{
				$strHclass .= ' first';
			}

			if ($dayCount >= $dayMax)
			{
				$strHclass .= ' last';
			}

			$count = 0;
			$eventCount = 0;

			// Get number of events in the day
			foreach ($days as $events)
			{
				$eventCount += count($events);
			}

			// List events
			foreach ($days as $day=>$events)
			{
				$strDay = $GLOBALS['TL_LANG']['DAYS'][date('w', $day)];

				foreach ($events as $event)
				{
					$objTemplate = new FrontendTemplate($this->cal_template);

					// Store raw data
					$objTemplate->setData($event);

					if ($count < 1)
					{
						$objTemplate->header = true;
						$objTemplate->hclass = $strHclass;
					}

					$objTemplate->day = $strDay;
					$objTemplate->firstDay = $strDay;
					$objTemplate->link = $event['href'];
					$objTemplate->class = ((($count++ % 2) == 0) ? ' even' : ' odd') . (($count == 1) ? ' first' : '') . (($count >= $eventCount) ? ' last' : '') . ' cal_' . $event['parent'];
					$objTemplate->date = date($GLOBALS['TL_CONFIG']['dateFormat'], $day);
					$objTemplate->more = $GLOBALS['TL_LANG']['MSC']['more'];
					$objTemplate->firstDate = $objTemplate->date;
					$objTemplate->span = '';

					// Short view
					if ($this->cal_noSpan)
					{
						$objTemplate->day = $event['day'];
						$objTemplate->date = $event['date'];

						if (!strlen($event['time']) && !strlen($event['day']))
						{
							$objTemplate->span = $event['date'];
						}
					}

					$objTemplate->addImage = false;

					// Add image
					if ($event['addImage'] && is_file(TL_ROOT . '/' . $event['singleSRC']))
					{
						$size = deserialize($event['size']);
						$src = $this->getImage($this->urlEncode($event['singleSRC']), $size[0], $size[1]);

						if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
						{
							$objTemplate->imgSize = ' ' . $imgSize[3];
						}

						$objTemplate->src = $src;
						$objTemplate->href = $event['singleSRC'];
						$objTemplate->alt = htmlspecialchars($event['alt']);
						$objTemplate->fullsize = $event['fullsize'] ? true : false;
						$objTemplate->margin = $this->generateMargin(deserialize($event['imagemargin']), 'padding');
						$objTemplate->float = in_array($event['floating'], array('left', 'right')) ? sprintf(' float:%s;', $event['floating']) : '';
						$objTemplate->caption = $event['caption'];
						$objTemplate->addImage = true;
					}

					$arrEnclosures = array();

					// Add enclosure
					if ($event['addEnclosure'])
					{
						$arrEnclosure = deserialize($event['enclosure'], true);
						$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

						if (is_array($arrEnclosure))
						{
							// Send file to the browser
							if (strlen($this->Input->get('file')) && in_array($this->Input->get('file'), $arrEnclosure))
							{
								$this->sendFileToBrowser($this->Input->get('file'));
							}

							// Add download links
							for ($i=0; $i<count($arrEnclosure); $i++)
							{
								if (is_file(TL_ROOT . '/' . $arrEnclosure[$i]))
								{				
									$objFile = new File($arrEnclosure[$i]);

									if (in_array($objFile->extension, $allowedDownload))
									{
										$size = ' ('.number_format(($objFile->filesize/1024), 1, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']).' kB)';
										$src = 'system/themes/' . $this->getTheme() . '/images/' . $objFile->icon;

										if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
										{
											$arrEnclosures[$i]['size'] = ' ' . $imgSize[3];
										}

										$arrEnclosures[$i]['icon'] = $src;
										$arrEnclosures[$i]['link'] = basename($arrEnclosure[$i]) . $size;
										$arrEnclosures[$i]['title'] = ucfirst(str_replace('_', ' ', $objFile->filename));
										$arrEnclosures[$i]['href'] = $this->Environment->request . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($this->Environment->request, '?') !== false) ? '&amp;' : '?') . 'file=' . $this->urlEncode($arrEnclosure[$i]);
										$arrEnclosures[$i]['enclosure'] = $arrEnclosure[$i];
									}
								}
							}
						}
					}

					$objTemplate->enclosure = $arrEnclosures;
					$strEvents .= $objTemplate->parse();
				}
			}
		}

		// No events found
		if (!strlen($strEvents))
		{
			$strEvents = "\n" . '<div class="empty">' . $strEmpty . '</div>' . "\n";
		}

		$this->Template->events = $strEvents;
		$this->Template->searchable = $this->searchable;
	}
}

?>