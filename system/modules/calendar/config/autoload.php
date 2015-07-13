<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Contao\Calendar'            => 'system/modules/calendar/classes/Calendar.php',
	'Contao\Events'              => 'system/modules/calendar/classes/Events.php',

	// Models
	'Contao\CalendarEventsModel' => 'system/modules/calendar/models/CalendarEventsModel.php',
	'Contao\CalendarFeedModel'   => 'system/modules/calendar/models/CalendarFeedModel.php',
	'Contao\CalendarModel'       => 'system/modules/calendar/models/CalendarModel.php',

	// Modules
	'Contao\ModuleCalendar'      => 'system/modules/calendar/modules/ModuleCalendar.php',
	'Contao\ModuleEventlist'     => 'system/modules/calendar/modules/ModuleEventlist.php',
	'Contao\ModuleEventMenu'     => 'system/modules/calendar/modules/ModuleEventMenu.php',
	'Contao\ModuleEventReader'   => 'system/modules/calendar/modules/ModuleEventReader.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'cal_default'        => 'system/modules/calendar/templates/calendar',
	'cal_mini'           => 'system/modules/calendar/templates/calendar',
	'event_full'         => 'system/modules/calendar/templates/events',
	'event_list'         => 'system/modules/calendar/templates/events',
	'event_teaser'       => 'system/modules/calendar/templates/events',
	'event_upcoming'     => 'system/modules/calendar/templates/events',
	'mod_calendar'       => 'system/modules/calendar/templates/modules',
	'mod_event'          => 'system/modules/calendar/templates/modules',
	'mod_eventlist'      => 'system/modules/calendar/templates/modules',
	'mod_eventmenu'      => 'system/modules/calendar/templates/modules',
	'mod_eventmenu_year' => 'system/modules/calendar/templates/modules',
));
