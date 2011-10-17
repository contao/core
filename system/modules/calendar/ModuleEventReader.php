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
 * @package    Calendar
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleEventReader
 *
 * Front end module "event reader".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
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
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Return if no event has been specified
		if (!$this->Input->get('events'))
		{
			global $objPage;

			// Do not index the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			return '';
		}

		$this->cal_calendar = $this->sortOutProtected(deserialize($this->cal_calendar));

		// Return if there are no calendars
		if (!is_array($this->cal_calendar) || count($this->cal_calendar) < 1)
		{
			global $objPage;

			// Do not index the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

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
		$objEvent = $this->Database->prepare("SELECT *, author AS authorId, (SELECT title FROM tl_calendar WHERE tl_calendar.id=tl_calendar_events.pid) AS calendar, (SELECT name FROM tl_user WHERE id=author) author FROM tl_calendar_events WHERE pid IN(" . implode(',', array_map('intval', $this->cal_calendar)) . ") AND (id=? OR alias=?)" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<?) AND (stop='' OR stop>?) AND published=1" : ""))
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

		// Overwrite the page title
		if ($objEvent->title != '')
		{
			$objPage->pageTitle = strip_insert_tags($objEvent->title);
		}

		// Overwrite the page description
		if ($objEvent->teaser != '')
		{
			$objPage->description = $this->prepareMetaDescription($objEvent->teaser);
		}

		$span = Calendar::calculateSpan($objEvent->startTime, $objEvent->endTime);

		if ($objPage->outputFormat == 'xhtml')
		{
			$strTimeStart = '';
			$strTimeEnd = '';
			$strTimeClose = '';
		}
		else
		{
			$strTimeStart = '<time datetime="' . date('Y-m-d\TH:i:sP', $objEvent->startTime) . '">';
			$strTimeEnd = '<time datetime="' . date('Y-m-d\TH:i:sP', $objEvent->endTime) . '">';
			$strTimeClose = '</time>';
		}

		// Get date
		if ($span > 0)
		{
			$date = $strTimeStart . $this->parseDate($GLOBALS['TL_CONFIG'][($objEvent->addTime ? 'datimFormat' : 'dateFormat')], $objEvent->startTime) . $strTimeClose . ' - ' . $strTimeEnd . $this->parseDate($GLOBALS['TL_CONFIG'][($objEvent->addTime ? 'datimFormat' : 'dateFormat')], $objEvent->endTime) . $strTimeClose;
		}
		elseif ($objEvent->startTime == $objEvent->endTime)
		{
			$date = $strTimeStart . $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objEvent->startTime) . ($objEvent->addTime ? ' (' . $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $objEvent->startTime) . ')' : '') . $strTimeClose;
		}
		else
		{
			$date = $strTimeStart . $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objEvent->startTime) . ($objEvent->addTime ? ' (' . $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $objEvent->startTime) . $strTimeClose . ' - ' . $strTimeEnd . $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $objEvent->endTime) . ')' : '') . $strTimeClose;
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

		// Override the default image size
		if ($this->imgSize != '')
		{
			$size = deserialize($this->imgSize);

			if ($size[0] > 0 || $size[1] > 0)
			{
				$objEvent->size = $this->imgSize;
			}
		}

		$objTemplate = new FrontendTemplate($this->cal_template);
		$objTemplate->setData($objEvent->row());

		$objTemplate->date = $date;
		$objTemplate->start = $objEvent->startTime;
		$objTemplate->end = $objEvent->endTime;
		$objTemplate->class = strlen($objEvent->cssClass) ? ' ' . $objEvent->cssClass : '';
		$objTemplate->recurring = $recurring;
		$objTemplate->until = $until;

		$this->import('String');

		// Clean the RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$objEvent->details = $this->String->toXhtml($objEvent->details);
		}
		else
		{
			$objEvent->details = $this->String->toHtml5($objEvent->details);
		}

		$objTemplate->details = $this->String->encodeEmail($objEvent->details);
		$objTemplate->addImage = false;

		// Add image
		if ($objEvent->addImage && is_file(TL_ROOT . '/' . $objEvent->singleSRC))
		{
			$this->addImageToTemplate($objTemplate, $objEvent->row());
		}

		$objTemplate->enclosure = array();

		// Add enclosures
		if ($objEvent->addEnclosure)
		{
			$this->addEnclosuresToTemplate($objTemplate, $objEvent->row());
		}

		$this->Template->event = $objTemplate->parse();

		// HOOK: comments extension required
		if ($objEvent->noComments || !in_array('comments', $this->Config->getActiveModules()))
		{
			$this->Template->allowComments = false;
			return;
		}

		// Check whether comments are allowed
		$objCalendar = $this->Database->prepare("SELECT * FROM tl_calendar WHERE id=?")
									  ->limit(1)
									  ->execute($objEvent->pid);

		if ($objCalendar->numRows < 1 || !$objCalendar->allowComments)
		{
			$this->Template->allowComments = false;
			return;
		}

		$this->Template->allowComments = true;

		// Adjust the comments headline level
		$intHl = min(intval(str_replace('h', '', $this->hl)), 5);
		$this->Template->hlc = 'h' . ($intHl + 1);

		$this->import('Comments');
		$arrNotifies = array();

		// Notify system administrator
		if ($objCalendar->notify != 'notify_author')
		{
			$arrNotifies[] = $GLOBALS['TL_ADMIN_EMAIL'];
		}

		// Notify author
		if ($objCalendar->notify != 'notify_admin')
		{
			$objAuthor = $this->Database->prepare("SELECT email FROM tl_user WHERE id=?")
										->limit(1)
										->execute($objEvent->authorId);

			if ($objAuthor->numRows)
			{
				$arrNotifies[] = $objAuthor->email;
			}
		}

		$objConfig = new stdClass();

		$objConfig->perPage = $objCalendar->perPage;
		$objConfig->order = $objCalendar->sortOrder;
		$objConfig->template = $this->com_template;
		$objConfig->requireLogin = $objCalendar->requireLogin;
		$objConfig->disableCaptcha = $objCalendar->disableCaptcha;
		$objConfig->bbcode = $objCalendar->bbcode;
		$objConfig->moderate = $objCalendar->moderate;

		$this->Comments->addCommentsToTemplate($this->Template, $objConfig, 'tl_calendar_events', $objEvent->id, $arrNotifies);
	}
}

?>