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
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['calendar']        = 'name,type,headline;cal_calendar,cal_startDay;cal_noSpan,cal_previous,cal_next;guests,protected;align,space,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['minicalendar']    = 'name,type,headline;cal_calendar;cal_noSpan,cal_startDay;jumpTo;cal_previous,cal_next;guests,protected;align,space,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['eventreader']     = 'name,type,headline;cal_calendar,cal_template;guests,protected;align,space,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['eventlist']       = 'name,type,headline;cal_calendar,cal_template;searchable,cal_format,cal_startDay,cal_noSpan;guests,protected;align,space,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['upcoming_events'] = 'name,type,headline;cal_calendar,cal_template;cal_noSpan,cal_limit;guests,protected;align,space,cssID';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['cal_calendar'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_calendar'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_module_calendar', 'getCalendars'),
	'eval'                    => array('mandatory'=>true, 'multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_startDay'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_startDay'],
	'default'                 => 0,
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array(0, 1, 2, 3, 4, 5, 6),
	'reference'               => &$GLOBALS['TL_LANG']['DAYS']
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_previous'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_previous'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('allowHtml'=>true, 'maxlength'=>255)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_next'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_next'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('allowHtml'=>true, 'maxlength'=>255)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_template'],
	'default'                 => 'event_default',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => $this->getTemplateGroup('event_')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_format'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_format'],
	'default'                 => 'cal_month',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('cal_day', 'cal_week', 'cal_month', 'cal_year', 'cal_two', 'next_7', 'next_14', 'next_30', 'next_90', 'next_180', 'next_365'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module']
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_limit'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_limit'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('rgxp'=>'digit')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_noSpan'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_noSpan'],
	'exclude'                 => true,
	'inputType'               => 'checkbox'
);


/**
 * Class tl_module_calendar
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_module_calendar extends Backend
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
	 * Get all calendars and return them as array
	 * @return array
	 */
	public function getCalendars()
	{
		if (!$this->User->isAdmin && !is_array($this->User->calendars))
		{
			return array();
		}

		$arrForms = array();
		$objForms = $this->Database->execute("SELECT id, title FROM tl_calendar ORDER BY title");

		while ($objForms->next())
		{
			if ($this->User->isAdmin || in_array($objForms->id, $this->User->calendars))
			{
				$arrForms[$objForms->id] = $objForms->title;
			}
		}

		return $arrForms;
	}
}

?>