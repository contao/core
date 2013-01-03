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
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Back end modules
 */
$GLOBALS['BE_MOD'] = array
(
	// Content modules
	'content' => array
	(
		'article' => array
		(
			'tables' => array('tl_article', 'tl_content'),
			'table' => array('TableWizard', 'importTable'),
			'list' => array('ListWizard', 'importList')
		),
		'form' => array
		(
			'tables' => array('tl_form', 'tl_form_field')
		)
	),

	// Design modules
	'design' => array
	(
		'themes' => array
		(
			'tables' => array('tl_theme', 'tl_module', 'tl_style_sheet', 'tl_style', 'tl_layout'),
			'importTheme' => array('Theme', 'importTheme'),
			'exportTheme' => array('Theme', 'exportTheme'),
			'import' => array('StyleSheets', 'importStyleSheet')
		),
		'page' => array
		(
			'tables' => array('tl_page')
		)
	),

	// Account modules
	'accounts' => array
	(
		'member' => array
		(
			'tables' => array('tl_member')
		),
		'mgroup' => array
		(
			'tables' => array('tl_member_group')
		),
		'user' => array
		(
			'tables' => array('tl_user')
		),
		'group' => array
		(
			'tables' => array('tl_user_group')
		)
	),

	// System modules
	'system' => array
	(
		'files' => array
		(
			'tables' => array('tl_files')
		),
		'log' => array
		(
			'tables' => array('tl_log')
		),
		'settings' => array
		(
			'tables' => array('tl_settings')
		),
		'maintenance' => array
		(
			'callback' => 'ModuleMaintenance'
		)
	),

	// User modules
	'profile' => array
	(
		'undo' => array
		(
			'tables' => array('tl_undo')
		),
		'login' => array
		(
			'tables' => array('tl_user'),
			'callback' => 'ModuleUser'
		)
	)
);


/**
 * Form fields
 */
$GLOBALS['BE_FFL'] = array
(
	'text'           => 'TextField',
	'password'       => 'Password',
	'textStore'      => 'TextStore',
	'textarea'       => 'TextArea',
	'select'         => 'SelectMenu',
	'checkbox'       => 'CheckBox',
	'checkboxWizard' => 'CheckBoxWizard',
	'radio'          => 'RadioButton',
	'radioTable'     => 'RadioTable',
	'inputUnit'      => 'InputUnit',
	'trbl'           => 'TrblField',
	'chmod'          => 'ChmodTable',
	'pageTree'       => 'PageTree',
	'fileTree'       => 'FileTree',
	'tableWizard'    => 'TableWizard',
	'listWizard'     => 'ListWizard',
	'optionWizard'   => 'OptionWizard',
	'moduleWizard'   => 'ModuleWizard',
	'keyValueWizard' => 'KeyValueWizard',
	'imageSize'      => 'ImageSize',
	'timePeriod'     => 'TimePeriod'
);


/**
 * Page types
 */
$GLOBALS['TL_PTY'] = array
(
	'regular'   => 'PageRegular',
	'forward'   => 'PageForward',
	'redirect'  => 'PageRedirect',
	'root'      => 'PageRoot',
	'error_403' => 'PageError403',
	'error_404' => 'PageError404'
);


/**
 * Maintenance
 */
$GLOBALS['TL_MAINTENANCE'] = array
(
	'PurgeData',
	'LiveUpdate',
	'RebuildIndex'
);


/**
 * Cache tables
 */
$GLOBALS['TL_CACHE'] = array
(
	'tl_undo',
	'tl_version',
	'tl_search',
	'tl_search_index'
);


/**
 * Image crop modes
 */
$GLOBALS['TL_CROP'] = array
(
	'relative' => array
	(
		'proportional', 'box'
	),
	'crop' => array
	(
		'left_top',    'center_top',    'right_top',
		'left_center', 'center_center', 'right_center',
		'left_bottom', 'center_bottom', 'right_bottom'
	)
);


/**
 * Cron jobs
 */
$GLOBALS['TL_CRON'] = array
(
	'monthly' => array
	(
		array('Automator', 'purgeHtmlFolder'),
		array('Automator', 'purgeScriptsFolder'),
		array('Automator', 'purgeTempFolder'),
	),
	'weekly' => array
	(
		array('Automator', 'generateSitemap'),
		array('StyleSheets', 'updateStyleSheets')
	),
	'daily' => array
	(
		array('Automator', 'checkForUpdates')
	),
	'hourly' => array()
);


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS'] = array
(
	'getSystemMessages' => array
	(
		array('Messages', 'versionCheck'),
		array('Messages', 'lastLogin'),
		array('Messages', 'topLevelRoot'),
		array('Messages', 'languageFallback')
	)
);


/**
 * Store the auto_item keywords so they can be ignored
 * when rebuilding the URLs for the search index
 */
$GLOBALS['TL_AUTO_ITEM'] = array('items', 'events');


/**
 * Other global arrays
 */
$GLOBALS['TL_MIME'] = array();
$GLOBALS['TL_PERMISSIONS'] = array();

?>