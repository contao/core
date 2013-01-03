<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Calendar
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleEventReader
 *
 * Front end module "event reader".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Calendar
 */
class ModuleEventReader extends \Events
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
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### EVENT READER ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Set the item from the auto_item parameter
		if ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			\Input::setGet('events', \Input::get('auto_item'));
		}

		// Do not index or cache the page if no event has been specified
		if (!\Input::get('events'))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			return '';
		}

		$this->cal_calendar = $this->sortOutProtected(deserialize($this->cal_calendar));

		// Do not index or cache the page if there are no calendars
		if (!is_array($this->cal_calendar) || empty($this->cal_calendar))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;

		$this->Template->event = '';
		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];

		// Get the current event
		$objEvent = \CalendarEventsModel::findPublishedByParentAndIdOrAlias(\Input::get('events'), $this->cal_calendar);

		if ($objEvent === null)
		{
			// Do not index or cache the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send a 404 header
			header('HTTP/1.1 404 Not Found');
			$this->Template->event = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], \Input::get('events')) . '</p>';
			return;
		}

		// Overwrite the page title (see #2853 and #4955)
		if ($objEvent->title != '')
		{
			$objPage->pageTitle = strip_tags(strip_insert_tags($objEvent->title));
		}

		// Overwrite the page description
		if ($objEvent->teaser != '')
		{
			$objPage->description = $this->prepareMetaDescription($objEvent->teaser);
		}

		$span = \Calendar::calculateSpan($objEvent->startTime, $objEvent->endTime);

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
			$date = $strTimeStart . $this->parseDate(($objEvent->addTime ? $objPage->datimFormat : $objPage->dateFormat), $objEvent->startTime) . $strTimeClose . ' - ' . $strTimeEnd . $this->parseDate(($objEvent->addTime ? $objPage->datimFormat : $objPage->dateFormat), $objEvent->endTime) . $strTimeClose;
		}
		elseif ($objEvent->startTime == $objEvent->endTime)
		{
			$date = $strTimeStart . $this->parseDate($objPage->dateFormat, $objEvent->startTime) . ($objEvent->addTime ? ' (' . $this->parseDate($objPage->timeFormat, $objEvent->startTime) . ')' : '') . $strTimeClose;
		}
		else
		{
			$date = $strTimeStart . $this->parseDate($objPage->dateFormat, $objEvent->startTime) . ($objEvent->addTime ? ' (' . $this->parseDate($objPage->timeFormat, $objEvent->startTime) . $strTimeClose . ' - ' . $strTimeEnd . $this->parseDate($objPage->timeFormat, $objEvent->endTime) . ')' : '') . $strTimeClose;
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
				$until = sprintf($GLOBALS['TL_LANG']['MSC']['cal_until'], $this->parseDate($objPage->dateFormat, $objEvent->repeatEnd));
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

		$objTemplate = new \FrontendTemplate($this->cal_template);
		$objTemplate->setData($objEvent->row());

		$objTemplate->date = $date;
		$objTemplate->start = $objEvent->startTime;
		$objTemplate->end = $objEvent->endTime;
		$objTemplate->class = ($objEvent->cssClass != '') ? ' ' . $objEvent->cssClass : '';
		$objTemplate->recurring = $recurring;
		$objTemplate->until = $until;

		$objTemplate->details = '';
		$objElement = \ContentModel::findPublishedByPidAndTable($objEvent->id, 'tl_calendar_events');

		if ($objElement !== null)
		{
			while ($objElement->next())
			{
				$objTemplate->details .= $this->getContentElement($objElement->id);
			}
		}

		$objTemplate->addImage = false;

		// Add an image
		if ($objEvent->addImage && $objEvent->singleSRC != '')
		{
			if (!is_numeric($objEvent->singleSRC))
			{
				$objTemplate->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
			}
			else
			{
				$objModel = \FilesModel::findByPk($objEvent->singleSRC);

				if ($objModel !== null && is_file(TL_ROOT . '/' . $objModel->path))
				{
					$objEvent->singleSRC = $objModel->path;
					$this->addImageToTemplate($objTemplate, $objEvent->row());
				}
			}
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

		$objCalendar = $objEvent->getRelated('pid');
		$this->Template->allowComments = $objCalendar->allowComments;

		// Comments are not allowed
		if (!$objCalendar->allowComments)
		{
			return;
		}

		// Adjust the comments headline level
		$intHl = min(intval(str_replace('h', '', $this->hl)), 5);
		$this->Template->hlc = 'h' . ($intHl + 1);

		$this->import('Comments');
		$arrNotifies = array();

		// Notify the system administrator
		if ($objCalendar->notify != 'notify_author')
		{
			$arrNotifies[] = $GLOBALS['TL_ADMIN_EMAIL'];
		}

		// Notify the author
		if ($objCalendar->notify != 'notify_admin')
		{
			if (($objAuthor = $objEvent->getRelated('author')) !== null && $objAuthor->email != '')
			{
				$arrNotifies[] = $objAuthor->email;
			}
		}

		$objConfig = new \stdClass();

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
