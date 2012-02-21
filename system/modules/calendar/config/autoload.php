<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Calendar
 * @license    LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'Contao\\Calendar'                 => 'system/modules/calendar/Calendar.php',
	'Contao\\Events'                   => 'system/modules/calendar/Events.php',
	'Contao\\ModuleCalendar'           => 'system/modules/calendar/ModuleCalendar.php',
	'Contao\\ModuleEventMenu'          => 'system/modules/calendar/ModuleEventMenu.php',
	'Contao\\ModuleEventReader'        => 'system/modules/calendar/ModuleEventReader.php',
	'Contao\\ModuleEventlist'          => 'system/modules/calendar/ModuleEventlist.php',

	// Models
	'Contao\\CalendarCollection'       => 'system/modules/calendar/models/CalendarCollection.php',
	'Contao\\CalendarEventsCollection' => 'system/modules/calendar/models/CalendarEventsCollection.php',
	'Contao\\CalendarEventsModel'      => 'system/modules/calendar/models/CalendarEventsModel.php',
	'Contao\\CalendarFeedCollection'   => 'system/modules/calendar/models/CalendarFeedCollection.php',
	'Contao\\CalendarFeedModel'        => 'system/modules/calendar/models/CalendarFeedModel.php',
	'Contao\\CalendarModel'            => 'system/modules/calendar/models/CalendarModel.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'cal_default'        => 'system/modules/calendar/templates',
	'cal_mini'           => 'system/modules/calendar/templates',
	'event_full'         => 'system/modules/calendar/templates',
	'event_list'         => 'system/modules/calendar/templates',
	'event_teaser'       => 'system/modules/calendar/templates',
	'event_upcoming'     => 'system/modules/calendar/templates',
	'mod_calendar'       => 'system/modules/calendar/templates',
	'mod_event'          => 'system/modules/calendar/templates',
	'mod_eventlist'      => 'system/modules/calendar/templates',
	'mod_eventmenu'      => 'system/modules/calendar/templates',
	'mod_eventmenu_year' => 'system/modules/calendar/templates',
));

?>