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
 * Class ModuleUpcomingEvents
 *
 * Front end module "upcoming events".
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleUpcomingEvents extends Events
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
			$objTemplate->wildcard = '### UPCOMING EVENTS ###';

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
		$this->Date = new Date(date('Ymd'), 'Ymd');

		$strEvents = '';
		$strBegin = $this->Date->dayBegin;
		$strEnd = ($this->Date->dayEnd + 31536000);

		// Get all events
		$arrAllEvents = $this->getAllEvents($this->cal_calendar, $strBegin, $strEnd);
		ksort($arrAllEvents);

		$dayMax = 0;
		$dateBegin = date('Ymd', $strBegin);
		$dateEnd = date('Ymd', $strEnd);

		// Unset events outside the scope
		foreach ($arrAllEvents as $k=>$v)
		{
			if ($k < $dateBegin || $k > $dateEnd)
			{
				unset($arrAllEvents[$k]);
				continue;
			}

			$dayMax += count($v);
		}

		$count = 0;
		$dayCount = 0;

		// Render events
		foreach ($arrAllEvents as $days)
		{
			foreach ($days as $day=>$events)
			{
				++$dayCount;
				$strDay = $GLOBALS['TL_LANG']['DAYS'][date('w', $day)];

				foreach ($events as $event)
				{
					++$count;

					if ($this->cal_limit && $count == $this->cal_limit)
					{
						$dayMax = $dayCount;
					}

					if ($this->cal_limit && $count > $this->cal_limit)
					{
						break(3);
					}

					$objTemplate = new FrontendTemplate($this->cal_template);

					$objTemplate->day = $strDay;
					$objTemplate->title = $event['title'];
					$objTemplate->date = date($GLOBALS['TL_CONFIG']['dateFormat'], $day);
					$objTemplate->time = $event['time'];
					$objTemplate->link = $event['href'];
					$objTemplate->month = $event['month'];
					$objTemplate->teaser = $event['teaser'];
					$objTemplate->details = $event['details'];
					$objTemplate->calendar = $event['calendar'];
					$objTemplate->class = ((($count % 2) == 0) ? ' odd' : ' even') . (($count == 1) ? ' first' : '') . (($dayCount >= $dayMax) ? ' last' : '') . ' cal_' . $event['parent'];
					$objTemplate->more = $GLOBALS['TL_LANG']['MSC']['more'];
					$objTemplate->start = $event['start'];
					$objTemplate->end = $event['end'];
					$objTemplate->data = $event;

					// Short view
					if ($this->cal_noSpan)
					{
						$objTemplate->day = $event['day'];
						$objTemplate->date = $event['date'];
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
			$strEvents = "\n" . '<div class="empty">' . $GLOBALS['TL_LANG']['MSC']['cal_empty'] . '</div>' . "\n";
		}

		$this->Template->events = $strEvents;
	}
}

?>