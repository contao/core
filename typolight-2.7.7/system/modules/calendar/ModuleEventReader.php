<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleEventReader
 *
 * Front end module "event reader".
 * @copyright  Leo Feyer 2005-2009
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class ModuleEventReader extends Events
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_event';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### EVENT READER ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Return if no event has been specified
		if (!$this->Input->get('events'))
		{
			return '';
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
		global $objPage;

		$this->Template->event = '';
		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];

		$time = time();

		// Get current event
		$objEvent = $this->Database->prepare("SELECT *, (SELECT title FROM tl_calendar WHERE tl_calendar.id=tl_calendar_events.pid) AS calendar, (SELECT name FROM tl_user WHERE id=author) author FROM tl_calendar_events WHERE pid IN(" . implode(',', $this->cal_calendar) . ") AND (id=? OR alias=?)" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : ""))
								   ->limit(1)
								   ->execute((is_numeric($this->Input->get('events')) ? $this->Input->get('events') : 0), $this->Input->get('events'), $time, $time);

		if ($objEvent->numRows < 1)
		{
			$this->Template->event = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], $this->Input->get('events')) . '</p>';

			// Do not index the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send 404 header
			header('HTTP/1.1 404 Not Found');
			return;
		}

		// Overwrite page title
		if (strlen($objEvent->title))
		{
			$objPage->pageTitle = $objEvent->title;
		}

		$span = Calendar::calculateSpan($objEvent->startTime, $objEvent->endTime);

		// Get date
		if ($span > 0)
		{
			$date = $this->parseDate($GLOBALS['TL_CONFIG'][($objEvent->addTime ? 'datimFormat' : 'dateFormat')], $objEvent->startTime) . ' - ' . $this->parseDate($GLOBALS['TL_CONFIG'][($objEvent->addTime ? 'datimFormat' : 'dateFormat')], $objEvent->endTime);
		}
		elseif ($objEvent->startTime == $objEvent->endTime)
		{
			$date = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objEvent->startTime) . ($objEvent->addTime ? ' (' . $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $objEvent->startTime) . ')' : '');
		}
		else
		{
			$date = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objEvent->startTime) . ($objEvent->addTime ? ' (' . $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $objEvent->startTime) . ' - ' . $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $objEvent->endTime) . ')' : '');
		}

		$until = '';
		$recurring = '';

		// Recurring event
		if ($objEvent->recurring)
		{
			$arrRange = deserialize($objEvent->repeatEach);
			$strKey = 'cal_' . $arrRange['unit'];
			$recurring = sprintf($GLOBALS['TL_LANG']['MSC'][$strKey], $arrRange['value']);

			if ($objEvent->recurrences > 0)
			{
				$until = sprintf($GLOBALS['TL_LANG']['MSC']['cal_until'], $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objEvent->repeatEnd));
			}
		}

		$objTemplate = new FrontendTemplate($this->cal_template);

		// Store raw data
		$objTemplate->setData($objEvent->row());

		$objTemplate->date = $date;
		$objTemplate->start = $objEvent->startTime;
		$objTemplate->end = $objEvent->endTime;
		$objTemplate->class = strlen($objEvent->cssClass) ? ' ' . $objEvent->cssClass : '';
		$objTemplate->recurring = $recurring;
		$objTemplate->until = $until;

		// Encode e-mail addresses
		$this->import('String');
		$objTemplate->details = $this->String->encodeEmail($objEvent->details);

		$this->Template->addImage = false;

		// Add image
		if ($objEvent->addImage && is_file(TL_ROOT . '/' . $objEvent->singleSRC))
		{
			$size = deserialize($objEvent->size);
			$src = $this->getImage($this->urlEncode($objEvent->singleSRC), $size[0], $size[1]);

			if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
			{
				$objTemplate->imgSize = ' ' . $imgSize[3];
			}

			$objTemplate->src = $src;
			$objTemplate->href = $objEvent->singleSRC;
			$objTemplate->alt = htmlspecialchars($objEvent->alt);
			$objTemplate->fullsize = $objEvent->fullsize ? true : false;
			$objTemplate->margin = $this->generateMargin(deserialize($objEvent->imagemargin), 'padding');
			$objTemplate->float = in_array($objEvent->floating, array('left', 'right')) ? sprintf(' float:%s;', $objEvent->floating) : '';
			$objTemplate->caption = $objEvent->caption;
			$objTemplate->addImage = true;
		}

		$arrEnclosures = array();

		// Add enclosure
		if ($objEvent->addEnclosure)
		{
			$arrEnclosure = deserialize($objEvent->enclosure, true);
			$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

			if (is_array($arrEnclosure))
			{
				// Send file to the browser
				if (strlen($this->Input->get('file', true)) && in_array($this->Input->get('file', true), $arrEnclosure))
				{
					$this->sendFileToBrowser($this->Input->get('file', true));
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
		$this->Template->event = $objTemplate->parse();
	}
}

?>