<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
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
		'oncut_callback' => array
		(
			array('tl_calendar_events', 'scheduleUpdate')
		),
		'ondelete_callback' => array
		(
			array('tl_calendar_events', 'scheduleUpdate')
		),
		'onsubmit_callback' => array
		(
			array('tl_calendar_events', 'adjustTime'),
			array('tl_calendar_events', 'scheduleUpdate')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('startTime DESC'),
			'headerFields'            => array('title', 'jumpTo', 'tstamp', 'protected', 'allowComments', 'makeFeed'),
			'panelLayout'             => 'filter;sort,search,limit',
			'child_record_callback'   => array('tl_calendar_events', 'listEvents')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
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
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_calendar_events']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_calendar_events']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_calendar_events']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_calendar_events', 'toggleIcon')
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
		'__selector__'                => array('addTime', 'addImage', 'recurring', 'addEnclosure', 'source'),
		'default'                     => '{title_legend},title,alias,author;{date_legend},addTime,startDate,endDate;{teaser_legend:hide},teaser;{text_legend},details;{image_legend},addImage;{recurring_legend},recurring;{enclosure_legend:hide},addEnclosure;{source_legend:hide},source;{expert_legend:hide},cssClass,noComments;{publish_legend},published,start,stop'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addTime'                     => 'startTime,endTime',
		'addImage'                    => 'singleSRC,alt,size,imagemargin,imageUrl,fullsize,caption,floating',
		'recurring'                   => 'repeatEach,recurrences',
		'addEnclosure'                => 'enclosure',
		'source_internal'             => 'jumpTo',
		'source_article'              => 'articleId',
		'source_external'             => 'url,target'
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'unique'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_calendar_events', 'generateAlias')
			)
		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['author'],
			'default'                 => $this->User->id,
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.name',
			'eval'                    => array('doNotCopy'=>true, 'chosen'=>true, 'mandatory'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'addTime' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['addTime'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true, 'doNotCopy'=>true)
		),
		'startTime' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['startTime'],
			'default'                 => time(),
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 8,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'time', 'mandatory'=>true, 'doNotCopy'=>true, 'tl_class'=>'w50')
		),
		'endTime' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['endTime'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'time', 'doNotCopy'=>true, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_calendar_events', 'setEmptyEndTime')
			)
		),
		'startDate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['startDate'],
			'default'                 => time(),
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'mandatory'=>true, 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'endDate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['endDate'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'save_callback' => array
			(
				array('tl_calendar_events', 'setEmptyEndDate')
			)
		),
		'teaser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['teaser'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr')
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
		'alt' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['alt'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'long')
		),
		'size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['size'],
			'exclude'                 => true,
			'inputType'               => 'imageSize',
			'options'                 => $GLOBALS['TL_CROP'],
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50')
		),
		'imagemargin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['imagemargin'],
			'exclude'                 => true,
			'inputType'               => 'trbl',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'imageUrl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['imageUrl'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50 wizard'),
			'wizard' => array
			(
				array('tl_calendar_events', 'pagePicker')
			)
		),
		'fullsize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fullsize'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
		),
		'caption' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['caption'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50')
		),
		'floating' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['floating'],
			'exclude'                 => true,
			'inputType'               => 'radioTable',
			'options'                 => array('above', 'left', 'right', 'below'),
			'eval'                    => array('cols'=>4, 'tl_class'=>'w50'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC']
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
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_calendar_events', 'checkInterval')
			)
		),
		'recurrences' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['recurrences'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'addEnclosure' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['addEnclosure'],
			'exclude'                 => true,
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
			'options'                 => array('default', 'internal', 'article', 'external'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_calendar_events'],
			'eval'                    => array('submitOnChange'=>true, 'helpwizard'=>true)
		),
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array('fieldType'=>'radio')
		),
		'articleId' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['articleId'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_calendar_events', 'getArticleAlias'),
			'eval'                    => array('chosen'=>true, 'mandatory'=>true)
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['url'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'target' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['target'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
		),
		'cssClass' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['cssClass'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50')
		),
		'noComments' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['noComments'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
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
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		)
	)
);


/**
 * Class tl_calendar_events
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
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
		// HOOK: comments extension required
		if (!in_array('comments', $this->Config->getActiveModules()))
		{
			$key = array_search('allowComments', $GLOBALS['TL_DCA']['tl_calendar_events']['list']['sorting']['headerFields']);
			unset($GLOBALS['TL_DCA']['tl_calendar_events']['list']['sorting']['headerFields'][$key]);
		}

		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->calendars) || empty($this->User->calendars))
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
			case 'paste':
				// Allow
				break;

			case 'create':
				if (!strlen($this->Input->get('pid')) || !in_array($this->Input->get('pid'), $root))
				{
					$this->log('Not enough permissions to create events in calendar ID "'.$this->Input->get('pid').'"', 'tl_calendar_events checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'cut':
			case 'copy':
				if (!in_array($this->Input->get('pid'), $root))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' event ID "'.$id.'" to calendar ID "'.$this->Input->get('pid').'"', 'tl_calendar_events checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				// NO BREAK STATEMENT HERE

			case 'edit':
			case 'show':
			case 'delete':
			case 'toggle':
				$objCalendar = $this->Database->prepare("SELECT pid FROM tl_calendar_events WHERE id=?")
											  ->limit(1)
											  ->execute($id);

				if ($objCalendar->numRows < 1)
				{
					$this->log('Invalid event ID "'.$id.'"', 'tl_calendar_events checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				if (!in_array($objCalendar->pid, $root))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' event ID "'.$id.'" of calendar ID "'.$objCalendar->pid.'"', 'tl_calendar_events checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'select':
			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':
				if (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access calendar ID "'.$id.'"', 'tl_calendar_events checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$objCalendar = $this->Database->prepare("SELECT id FROM tl_calendar_events WHERE pid=?")
											  ->execute($id);

				if ($objCalendar->numRows < 1)
				{
					$this->log('Invalid calendar ID "'.$id.'"', 'tl_calendar_events checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objCalendar->fetchEach('id'));
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act')))
				{
					$this->log('Invalid command "'.$this->Input->get('act').'"', 'tl_calendar_events checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				elseif (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access calendar ID "'.$id.'"', 'tl_calendar_events checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Auto-generate the event alias if it has not been set yet
	 * @param mixed
	 * @param DataContainer
	 * @return string
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$varValue = standardize($this->restoreBasicEntities($dc->activeRecord->title));
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_calendar_events WHERE alias=?")
								   ->execute($varValue);

		// Check whether the alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}


	/**
	 * Automatically set the end time if not set
	 * @param mixed
	 * @param DataContainer
	 * @return string
	 */
	public function setEmptyEndTime($varValue, DataContainer $dc)
	{
		if ($varValue === '')
		{
			$varValue = $dc->activeRecord->startTime;
		}

		return $varValue;
	}


	/**
	 * Set the end date to null if empty
	 * @param mixed
	 * @param DataContainer
	 * @return string
	 */
	public function setEmptyEndDate($varValue, DataContainer $dc)
	{
		if ($varValue === '')
		{
			$varValue = null;
		}

		return $varValue;
	}


	/**
	 * Check for a valid recurrence interval
	 * @param mixed
	 * @return mixed
	 */
	public function checkInterval($varValue)
	{
		$varValue = deserialize($varValue);

		if ($varValue['value'] < 1)
		{
			$varValue['value'] = 1;
		}

		return serialize($varValue);
	}


	/**
	 * Add the type of input field
	 * @param array
	 * @return string
	 */
	public function listEvents($arrRow)
	{
		$time = time();
		$key = ($arrRow['published'] && ($arrRow['start'] == '' || $arrRow['start'] < $time) && ($arrRow['stop'] == '' || $arrRow['stop'] > $time)) ? 'published' : 'unpublished';
		$span = Calendar::calculateSpan($arrRow['startTime'], $arrRow['endTime']);

		if ($span > 0)
		{
			$date = $this->parseDate($GLOBALS['TL_CONFIG'][($arrRow['addTime'] ? 'datimFormat' : 'dateFormat')], $arrRow['startTime']) . ' - ' . $this->parseDate($GLOBALS['TL_CONFIG'][($arrRow['addTime'] ? 'datimFormat' : 'dateFormat')], $arrRow['endTime']);
		}
		elseif ($arrRow['startTime'] == $arrRow['endTime'])
		{
			$date = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $arrRow['startTime']) . ($arrRow['addTime'] ? ' (' . $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $arrRow['startTime']) . ')' : '');
		}
		else
		{
			$date = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $arrRow['startTime']) . ($arrRow['addTime'] ? ' (' . $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $arrRow['startTime']) . ' - ' . $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $arrRow['endTime']) . ')' : '');
		}

		return '
<div class="cte_type ' . $key . '"><strong>' . $arrRow['title'] . '</strong> - ' . $date . '</div>
<div class="limit_height' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h52' : '') . '">
' . (($arrRow['details'] != '') ? $arrRow['details'] : $arrRow['teaser']) . '
</div>' . "\n";
	}


	/**
	 * Get all articles and return them as array
	 * @param DataContainer
	 * @return array
	 */
	public function getArticleAlias(DataContainer $dc)
	{
		$arrPids = array();
		$arrAlias = array();

		if (!$this->User->isAdmin)
		{
			foreach ($this->User->pagemounts as $id)
			{
				$arrPids[] = $id;
				$arrPids = array_merge($arrPids, $this->getChildRecords($id, 'tl_page'));
			}

			if (empty($arrPids))
			{
				return $arrAlias;
			}

			$objAlias = $this->Database->prepare("SELECT a.id, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid WHERE a.pid IN(". implode(',', array_map('intval', array_unique($arrPids))) .") ORDER BY parent, a.sorting")
									   ->execute($dc->id);
		}
		else
		{
			$objAlias = $this->Database->prepare("SELECT a.id, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid ORDER BY parent, a.sorting")
									   ->execute($dc->id);
		}

		if ($objAlias->numRows)
		{
			$this->loadLanguageFile('tl_article');

			while ($objAlias->next())
			{
				$arrAlias[$objAlias->parent][$objAlias->id] = $objAlias->title . ' (' . (strlen($GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn]) ? $GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn] : $objAlias->inColumn) . ', ID ' . $objAlias->id . ')';
			}
		}

		return $arrAlias;
	}


	/**
	 * Adjust start end end time of the event based on date, span, startTime and endTime
	 * @param DataContainer
	 */
	public function adjustTime(DataContainer $dc)
	{
		// Return if there is no active record (override all)
		if (!$dc->activeRecord)
		{
			return;
		}

		$arrSet['startTime'] = $dc->activeRecord->startDate;
		$arrSet['endTime'] = $dc->activeRecord->startDate;

		// Set end date
		if (strlen($dc->activeRecord->endDate))
		{
			if ($dc->activeRecord->endDate > $dc->activeRecord->startDate)
			{
				$arrSet['endDate'] = $dc->activeRecord->endDate;
				$arrSet['endTime'] = $dc->activeRecord->endDate;
			}
			else
			{
				$arrSet['endDate'] = $dc->activeRecord->startDate;
				$arrSet['endTime'] = $dc->activeRecord->startDate;
			}
		}

		// Add time
		if ($dc->activeRecord->addTime)
		{
			$arrSet['startTime'] = strtotime(date('Y-m-d', $arrSet['startTime']) . ' ' . date('H:i:s', $dc->activeRecord->startTime));
			$arrSet['endTime'] = strtotime(date('Y-m-d', $arrSet['endTime']) . ' ' . date('H:i:s', $dc->activeRecord->endTime));
		}

		// Adjust end time of "all day" events
		elseif ((strlen($dc->activeRecord->endDate) && $arrSet['endDate'] == $arrSet['endTime']) || $arrSet['startTime'] == $arrSet['endTime'])
		{
			$arrSet['endTime'] = (strtotime('+ 1 day', $arrSet['endTime']) - 1);
		}

		$arrSet['repeatEnd'] = 0;

		if ($dc->activeRecord->recurring)
		{
			$arrRange = deserialize($dc->activeRecord->repeatEach);

			$arg = $arrRange['value'] * $dc->activeRecord->recurrences;
			$unit = $arrRange['unit'];

			$strtotime = '+ ' . $arg . ' ' . $unit;
			$arrSet['repeatEnd'] = strtotime($strtotime, $arrSet['endTime']);
		}

		$this->Database->prepare("UPDATE tl_calendar_events %s WHERE id=?")->set($arrSet)->execute($dc->id);
	}


	/**
	 * Check for modified calendar feeds and update the XML files if necessary
	 */
	public function generateFeed()
	{
		$session = $this->Session->get('calendar_feed_updater');

		if (!is_array($session) || empty($session))
		{
			return;
		}

		$this->import('Calendar');

		foreach ($session as $id)
		{
			$this->Calendar->generateFeed($id);
		}

		$this->Session->set('calendar_feed_updater', null);
	}


	/**
	 * Schedule a calendar feed update
	 * 
	 * This method is triggered when a single event or multiple events are
	 * modified (edit/editAll), moved (cut/cutAll) or deleted (delete/deleteAll).
	 * Since duplicated events are unpublished by default, it is not necessary
	 * to schedule updates on copyAll as well.
	 */
	public function scheduleUpdate()
	{
		// Return if there is no ID 
		if (!CURRENT_ID || $this->Input->get('act') == 'copy')
		{
			return;
		}

		// Store the ID in the session
		$session = $this->Session->get('calendar_feed_updater');
		$session[] = CURRENT_ID;
		$this->Session->set('calendar_feed_updater', array_unique($session));
	}


	/**
	 * Return the link picker wizard
	 * @param DataContainer
	 * @return string
	 */
	public function pagePicker(DataContainer $dc)
	{
		$strField = 'ctrl_' . $dc->field . (($this->Input->get('act') == 'editAll') ? '_' . $dc->id : '');
		return ' ' . $this->generateImage('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer" onclick="Backend.pickPage(\'' . $strField . '\')"');
	}


	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_calendar_events::published', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}		

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Disable/enable a user group
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to edit
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_calendar_events::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish event ID "'.$intId.'"', 'tl_calendar_events toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_calendar_events', $intId);
	
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_calendar_events']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_calendar_events']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_calendar_events SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_calendar_events', $intId);

		// Update the RSS feed (for some reason it does not work without sleep(1))
		sleep(1);
		$this->import('Calendar');
		$this->Calendar->generateFeed(CURRENT_ID);
	}
}

?>