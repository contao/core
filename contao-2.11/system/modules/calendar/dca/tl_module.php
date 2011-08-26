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
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['calendar']    = '{title_legend},name,headline,type;{config_legend},cal_calendar,cal_noSpan,cal_startDay;{redirect_legend},jumpTo;{template_legend:hide},cal_ctemplate;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['eventlist']   = '{title_legend},name,headline,type;{config_legend},cal_calendar,cal_noSpan,cal_ignoreDynamic,cal_format,cal_order,cal_limit,perPage;{template_legend:hide},cal_template,imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['eventreader'] = '{title_legend},name,headline,type;{config_legend},cal_calendar;{template_legend:hide},cal_template,imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['eventmenu']   = '{title_legend},name,headline,type;{config_legend},cal_calendar,cal_noSpan,cal_showQuantity,cal_format,cal_startDay,cal_order;{redirect_legend},jumpTo;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


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

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_format'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_format'],
	'default'                 => 'cal_month',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_calendar', 'getFormats'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'wizard' => array
	(
		array('tl_module_calendar', 'hideStartDay')
	)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_order'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_order'],
	'default'                 => 'ascending',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('ascending', 'descending'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_noSpan'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_noSpan'],
	'exclude'                 => true,
	'inputType'               => 'checkbox'
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_ignoreDynamic'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_ignoreDynamic'],
	'exclude'                 => true,
	'inputType'               => 'checkbox'
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_limit'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_limit'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_startDay'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_startDay'],
	'default'                 => 0,
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array(0, 1, 2, 3, 4, 5, 6),
	'reference'               => &$GLOBALS['TL_LANG']['DAYS'],
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_template'],
	'default'                 => 'event_full',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_calendar', 'getEventTemplates'),
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_ctemplate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_ctemplate'],
	'default'                 => 'cal_default',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_calendar', 'getCalendarTemplates'),
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_showQuantity'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_showQuantity'],
	'exclude'                 => true,
	'inputType'               => 'checkbox'
);


/**
 * Add the comments template drop-down menu
 */
if (in_array('comments', Config::getInstance()->getActiveModules()))
{
	$GLOBALS['TL_DCA']['tl_module']['palettes']['eventreader'] = str_replace('{protected_legend:hide}', '{comment_legend:hide},com_template;{protected_legend:hide}', $GLOBALS['TL_DCA']['tl_module']['palettes']['eventreader']);
}


/**
 * Class tl_module_calendar
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
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

		$arrCalendars = array();
		$objCalendars = $this->Database->execute("SELECT id, title FROM tl_calendar ORDER BY title");

		while ($objCalendars->next())
		{
			if ($this->User->isAdmin || $this->User->hasAccess($objCalendars->id, 'calendars'))
			{
				$arrCalendars[$objCalendars->id] = $objCalendars->title;
			}
		}

		return $arrCalendars;
	}


	/**
	 * Return the calendar formats depending on the module type
	 * @return array
	 */
	public function getFormats(DataContainer $dc)
	{
		if ($dc->activeRecord->type == 'eventmenu')
		{
			return array('cal_day', 'cal_month', 'cal_year');
		}

		return array
		(
			'cal_list'     => array('cal_day', 'cal_month', 'cal_year', 'cal_all'),
			'cal_upcoming' => array('next_7', 'next_14', 'next_30', 'next_90', 'next_180', 'next_365', 'next_two', 'next_all'),
			'cal_past'     => array('past_7', 'past_14', 'past_30', 'past_90', 'past_180', 'past_365', 'past_two', 'past_all')
		);
	}


	/**
	 * Hide the start day drop-down if not applicable
	 * @return string
	 */
	public function hideStartDay()
	{
		return '
  <script>
  var enableStartDay = function() {
    var e1 = $("ctrl_cal_startDay").getParent("div");
    var e2 = $("ctrl_cal_order").getParent("div");
    if ($("ctrl_cal_format").value == "cal_day") {
      e1.setStyle("display", "block");
      e2.setStyle("display", "none");
	} else {
      e1.setStyle("display", "none");
      e2.setStyle("display", "block");
	}
  };
  window.addEvent("domready", function() {
    if ($("ctrl_cal_startDay")) {
      enableStartDay();
      $("ctrl_cal_format").addEvent("change", enableStartDay);
    }
  });
  </script>';
	}


	/**
	 * Return all event templates as array
	 * @param object
	 * @return array
	 */
	public function getEventTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('event_', $intPid);
	}


	/**
	 * Return all calendar templates as array
	 * @param object
	 * @return array
	 */
	public function getCalendarTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('cal_', $intPid);
	}
}

?>