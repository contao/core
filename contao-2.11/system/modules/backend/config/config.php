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
		),
		'tasks' => array
		(
			'callback' => 'ModuleTasks'
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
 * Cron jobs
 */
$GLOBALS['TL_CRON']['daily'][]  = array('Automator', 'purgeTempFolder');
$GLOBALS['TL_CRON']['daily'][]  = array('Automator', 'checkForUpdates');
$GLOBALS['TL_CRON']['weekly'][] = array('Automator', 'generateSitemap');
$GLOBALS['TL_CRON']['weekly'][] = array('StyleSheets', 'updateStyleSheets');


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS'] = array();


/**
 * Mime types
 */
$GLOBALS['TL_MIME'] = array();

?>