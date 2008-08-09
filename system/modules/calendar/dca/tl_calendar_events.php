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
 * Load tl_content language file
 */
$this->loadLanguageFile('tl_content');


/**
 * Table tl_calendar_events
 */
$GLOBALS['TL_DCA']['tl_calendar_events'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_calendar',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_calendar_events', 'checkPermission'),
			array('tl_calendar_events', 'generateFeed')
		),
		'onsubmit_callback' => array
		(
			array('tl_calendar_events', 'adjustTime')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('startTime'),
			'headerFields'            => array('title', 'jumpTo', 'tstamp', 'makeFeed'),
			'panelLayout'             => 'filter;search,limit',
			'child_record_callback'   => array('tl_calendar_events', 'listEvents')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_calendar_events']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_calendar_events']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_calendar_events']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_calendar_events']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('source', 'addTime', 'addImage', 'recurring', 'addEnclosure'),
		'default'                     => 'title,alias,author;startDate,endDate;addTime;teaser,details;addImage;recurring;addEnclosure;source;published,start,stop',
		'internal'                    => 'title,alias,author;startDate,endDate;addTime;teaser,details;addImage;recurring;addEnclosure;source,jumpTo;published,start,stop',
		'external'                    => 'title,alias,author;startDate,endDate;addTime;teaser,details;addImage;recurring;addEnclosure;source,url,target;published,start,stop'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addTime'                     => 'startTime,endTime',
		'recurring'                   => 'repeatEach,recurrences',
		'addImage'                    => 'singleSRC,alt,imagemargin,size,caption,floating,fullsize',
		'addEnclosure'                => 'enclosure'
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'unique'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128),
			'save_callback' => array
			(
				array('tl_calendar_events', 'generateAlias')
			)
		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['author'],
			'exclude'                 => true,
			'default'                 => $this->User->id,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.name'
		),
		'startDate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['startDate'],
			'default'                 => time(),
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 8,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>10, 'rgxp'=>'date', 'datepicker'=>$this->getDatePickerString())
		),
		'endDate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['endDate'],
			'default'                 => time(),
			'exclude'                 => true,
			'flag'                    => 8,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>10, 'rgxp'=>'date', 'datepicker'=>$this->getDatePickerString())
		),
		'addTime' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['addTime'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'startTime' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['startTime'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'time')
		),
		'endTime' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['endTime'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'time')
		),
		'recurring' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['recurring'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'repeatEach' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['repeatEach'],
			'exclude'                 => true,
			'inputType'               => 'timePeriod',
			'options'                 => array('days', 'weeks', 'months', 'years'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_calendar_events'],
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit')
		),
		'recurrences' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['recurrences'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit')
		),
		'teaser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['teaser'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('style'=>'height:80px;', 'allowHtml'=>true)
		),
		'details' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['details'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE', 'helpwizard'=>true),
			'explanation'             => 'insertTags'
		),
		'addImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['addImage'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'mandatory'=>true)
		),
		'size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['size'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true)
		),
		'alt' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['alt'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'extnd', 'maxlength'=>255)
		),
		'caption' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['caption'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'extnd', 'maxlength'=>255)
		),
		'floating' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['floating'],
			'exclude'                 => true,
			'inputType'               => 'radioTable',
			'options'                 => array('above', 'left', 'right'),
			'eval'                    => array('cols'=>3),
			'reference'               => &$GLOBALS['TL_LANG']['MSC']
		),
		'imagemargin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['imagemargin'],
			'exclude'                 => true,
			'inputType'               => 'trbl',
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true)
		),
		'fullsize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fullsize'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'addEnclosure' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['addEnclosure'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'enclosure' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['enclosure'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'mandatory'=>true)
		),
		'source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['source'],
			'default'                 => 'default',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'radio',
			'options'                 => array('default', 'internal', 'external'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_calendar_events'],
			'eval'                    => array('submitOnChange'=>true, 'helpwizard'=>true)
		),
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array('fieldType'=>'radio', 'helpwizard'=>true),
			'explanation'             => 'jumpTo'
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['url'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'decodeEntities'=>true, 'maxlength'=>255)
		),
		'target' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['target'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 2,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true)
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>10, 'rgxp'=>'date', 'datepicker'=>$this->getDatePickerString())
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>10, 'rgxp'=>'date', 'datepicker'=>$this->getDatePickerString())
		)
	)
);


/**
 * Class tl_calendar_events
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_calendar_events extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Check permissions to edit table tl_calendar_events
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->calendars) || count($this->User->calendars) < 1)
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->calendars;
		}

		$id = strlen($this->Input->get('id')) ? $this->Input->get('id') : CURRENT_ID;

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'select':
				// Allow
				break;

			case 'create':
				if (!strlen($this->Input->get('pid')) || !in_array($this->Input->get('pid'), $root))
				{
					$this->log('Not enough permissions to create events in calendar ID "'.$this->Input->get('pid').'"', 'tl_calendar_events checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}
				break;

			case 'edit':
			case 'show':
			case 'copy':
			case 'delete':
				$objCalendar = $this->Database->prepare("SELECT pid FROM tl_calendar_events WHERE id=?")
											  ->limit(1)
											  ->execute($id);

				if ($objCalendar->numRows < 1)
				{
					$this->log('Invalid event ID "'.$id.'"', 'tl_calendar_events checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}

				if (!in_array($objCalendar->pid, $root))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' event ID "'.$id.'" of calendar ID "'.$objCalendar->pid.'"', 'tl_calendar_events checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
				if (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access calendar ID "'.$id.'"', 'tl_calendar_events checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}

				$objCalendar = $this->Database->prepare("SELECT id FROM tl_calendar_events WHERE pid=?")
											  ->execute($id);

				if ($objCalendar->numRows < 1)
				{
					$this->log('Invalid calendar ID "'.$id.'"', 'tl_calendar_events checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objCalendar->fetchEach('id'));
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act')))
				{
					$this->log('Invalid command "'.$this->Input->get('act').'"', 'tl_calendar_events checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}
				elseif (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access calendar ID "'.$id.'"', 'tl_calendar_events checkPermission', 5);
					$this->redirect('typolight/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Autogenerate a event alias if it has not been set yet
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($varValue))
		{
			$objTitle = $this->Database->prepare("SELECT title FROM tl_calendar_events WHERE id=?")
									   ->limit(1)
									   ->execute($dc->id);

			$autoAlias = true;
			$varValue = standardize($objTitle->title);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_calendar_events WHERE alias=?")
								   ->execute($varValue, $dc->id);

		// Check whether the news alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= '.' . $dc->id;
		}

		return $varValue;
	}


	/**
	 * Add the type of input field
	 * @param array
	 * @return string
	 */
	public function listEvents($arrRow)
	{
		$key = $arrRow['published'] ? 'published' : 'unpublished';
		$span = intval(($arrRow['endTime'] - $arrRow['startTime']) / 86400);

		if ($span > 0)
		{
			$date = date($GLOBALS['TL_CONFIG'][($arrRow['addTime'] ? 'datimFormat' : 'dateFormat')], $arrRow['startTime']) . ' - ' . date($GLOBALS['TL_CONFIG'][($arrRow['addTime'] ? 'datimFormat' : 'dateFormat')], $arrRow['endTime']);
		}
		elseif ($arrRow['startTime'] == $arrRow['endTime'])
		{
			$date = date($GLOBALS['TL_CONFIG']['dateFormat'], $arrRow['startTime']) . ($arrRow['addTime'] ? ' (' . date($GLOBALS['TL_CONFIG']['timeFormat'], $arrRow['startTime']) . ')' : '');
		}
		else
		{
			$date = date($GLOBALS['TL_CONFIG']['dateFormat'], $arrRow['startTime']) . ($arrRow['addTime'] ? ' (' . date($GLOBALS['TL_CONFIG']['timeFormat'], $arrRow['startTime']) . ' - ' . date($GLOBALS['TL_CONFIG']['timeFormat'], $arrRow['endTime']) . ')' : '');
		}

		return '
<div class="cte_type ' . $key . '"><strong>' . $arrRow['title'] . '</strong> - ' . $date . '</div>
<div class="limit_height' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h52' : '') . ' block">
' . $arrRow['details'] . '
</div>' . "\n";
	}


	/**
	 * Adjust start end end time of the event based on date, span, startTime and endTime
	 * @param object
	 */
	public function adjustTime(DataContainer $dc)
	{
		$objEvent = $this->Database->prepare("SELECT * FROM tl_calendar_events WHERE id=?")
								   ->limit(1)
								   ->execute($dc->id);

		if ($objEvent->numRows < 1)
		{
			return;
		}

		$arrSet['startTime'] = $objEvent->startDate;
		$arrSet['endTime'] = $objEvent->startDate;

		// Set end date
		if (strlen($objEvent->endDate))
		{
			if ($objEvent->endDate > $objEvent->startDate)
			{
				$arrSet['endDate'] = $objEvent->endDate;
				$arrSet['endTime'] = $objEvent->endDate;
			}
			else
			{
				$arrSet['endDate'] = $objEvent->startDate;
				$arrSet['endTime'] = $objEvent->startDate;
			}
		}

		// Add time
		if ($objEvent->addTime)
		{
			$blnSummer = date('I', $arrSet['startTime']);
			$arrSet['startTime'] += (date('G', $objEvent->startTime) * 3600) + (intval(date('i', $objEvent->startTime)) * 60);

			if (date('I', $arrSet['startTime']) !== $blnSummer)
			{
				$arrSet['startTime'] = $blnSummer ? ($arrSet['startTime'] + 3600) : ($arrSet['startTime'] - 3600);
			}

			$blnSummer = date('I', $arrSet['endTime']);
			$arrSet['endTime'] += (date('G', $objEvent->endTime) * 3600) + (intval(date('i', $objEvent->endTime)) * 60);

			if (date('I', $arrSet['endTime']) !== $blnSummer)
			{
				$arrSet['endTime'] = $blnSummer ? ($arrSet['endTime'] + 3600) : ($arrSet['endTime'] - 3600);
			}
		}

		// Adjust end time of "all day" events
		elseif ((strlen($objEvent->endDate) && $arrSet['endDate'] == $arrSet['endTime']) || $arrSet['startTime'] == $arrSet['endTime'])
		{
			$blnSummer = date('I', $arrSet['endTime']);
			$arrSet['endTime'] += 86399;

			if (date('I', $arrSet['endTime']) !== $blnSummer)
			{
				$arrSet['endTime'] = $blnSummer ? ($arrSet['endTime'] + 3600) : ($arrSet['endTime'] - 3600);
			}
		}

		$arrSet['repeatEnd'] = 0;

		if ($objEvent->recurring)
		{
			$arrRange = deserialize($objEvent->repeatEach);

			switch ($arrRange['unit'])
			{
				case 'days':
					$multiplier = 86400;
					break;

				case 'weeks':
					$multiplier = 604800;
					break;

				case 'months':
					$multiplier = 2678400;
					break;

				case 'years':
					$multiplier = 31622400;
					break;
			}

			$arrSet['repeatEnd'] = $arrSet['endTime'] + ($arrRange['value'] * $multiplier * $objEvent->recurrences);
		}

		$this->Database->prepare("UPDATE tl_calendar_events %s WHERE id=?")
					   ->set($arrSet)
					   ->execute($dc->id);
	}


	/**
	 * Update the RSS feed
	 */
	public function generateFeed()
	{
		$this->import('Calendar');
		$this->Calendar->generateFeed(CURRENT_ID);
	}
}

?>