<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (c) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
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
			'table'  => array('TableWizard', 'importTable'),
			'list'   => array('ListWizard', 'importList'),
            'icon'   => 'system/themes/default/images/article.gif' // @todo: do we have to put those into the core folder? it's not consistent anyway (e.g. devtools, calendar, faq etc. can't be overriden by CSS anyway)
		),
		'form' => array
		(
			'tables' => array('tl_form', 'tl_form_field'),
            'icon'   => 'system/themes/default/images/form.gif' // @todo: do we have to put those into the core folder? it's not consistent anyway (e.g. devtools, calendar, faq etc. can't be overriden by CSS anyway)
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
			'import' => array('StyleSheets', 'importStyleSheet'),
            'icon'   => 'system/themes/default/images/themes.gif' // @todo: do we have to put those into the core folder? it's not consistent anyway (e.g. devtools, calendar, faq etc. can't be overriden by CSS anyway)
		),
		'page' => array
		(
			'tables' => array('tl_page'),
            'icon'   => 'system/themes/default/images/page.gif' // @todo: do we have to put those into the core folder? it's not consistent anyway (e.g. devtools, calendar, faq etc. can't be overriden by CSS anyway)
        ),
		'tpl_editor' => array
		(
			'tables' => array('tl_templates'),
			'new_tpl' => array('tl_templates', 'addNewTemplate'),
            'icon'   => 'system/themes/default/images/template_editor.gif' // @todo: do we have to put those into the core folder? it's not consistent anyway (e.g. devtools, calendar, faq etc. can't be overriden by CSS anyway)
        )
	),

	// Account modules
	'accounts' => array
	(
		'member' => array
		(
			'tables' => array('tl_member'),
            'icon'   => 'system/themes/default/images/member.gif' // @todo: do we have to put those into the core folder? it's not consistent anyway (e.g. devtools, calendar, faq etc. can't be overriden by CSS anyway)
        ),
		'mgroup' => array
		(
			'tables' => array('tl_member_group'),
            'icon'   => 'system/themes/default/images/mgroup.gif' // @todo: do we have to put those into the core folder? it's not consistent anyway (e.g. devtools, calendar, faq etc. can't be overriden by CSS anyway)
        ),
		'user' => array
		(
			'tables' => array('tl_user'),
            'icon'   => 'system/themes/default/images/user.gif' // @todo: do we have to put those into the core folder? it's not consistent anyway (e.g. devtools, calendar, faq etc. can't be overriden by CSS anyway)
        ),
		'group' => array
		(
			'tables' => array('tl_user_group'),
            'icon'   => 'system/themes/default/images/group.gif' // @todo: do we have to put those into the core folder? it's not consistent anyway (e.g. devtools, calendar, faq etc. can't be overriden by CSS anyway)
        )
	),

	// System modules
	'system' => array
	(
		'files' => array
		(
			'tables' => array('tl_files'),
            'icon'   => 'system/themes/default/images/files.gif' // @todo: do we have to put those into the core folder? it's not consistent anyway (e.g. devtools, calendar, faq etc. can't be overriden by CSS anyway)
        ),
		'log' => array
		(
			'tables' => array('tl_log'),
            'icon'   => 'system/themes/default/images/log.gif' // @todo: do we have to put those into the core folder? it's not consistent anyway (e.g. devtools, calendar, faq etc. can't be overriden by CSS anyway)
        ),
		'settings' => array
		(
			'tables' => array('tl_settings'),
            'icon'   => 'system/themes/default/images/settings.gif' // @todo: do we have to put those into the core folder? it's not consistent anyway (e.g. devtools, calendar, faq etc. can't be overriden by CSS anyway)
        ),
		'maintenance' => array
		(
			'callback' => 'ModuleMaintenance',
            'icon'   => 'system/themes/default/images/cache.gif' // @todo: do we have to put those into the core folder? it's not consistent anyway (e.g. devtools, calendar, faq etc. can't be overriden by CSS anyway)
        ),
		'undo' => array
		(
			'tables' => array('tl_undo'),
            'icon'   => 'system/themes/default/images/undo.gif' // @todo: do we have to put those into the core folder? it's not consistent anyway (e.g. devtools, calendar, faq etc. can't be overriden by CSS anyway)
        )
	)
);


/**
 * Front end modules
 */
$GLOBALS['FE_MOD'] = array
(
	'navigationMenu' => array
	(
		'navigation'     => 'ModuleNavigation',
		'customnav'      => 'ModuleCustomnav',
		'breadcrumb'     => 'ModuleBreadcrumb',
		'quicknav'       => 'ModuleQuicknav',
		'quicklink'      => 'ModuleQuicklink',
		'booknav'        => 'ModuleBooknav',
		'articlenav'     => 'ModuleArticlenav',
		'sitemap'        => 'ModuleSitemap'
	),
	'user' => array
	(
		'login'          => 'ModuleLogin',
		'logout'         => 'ModuleLogout',
		'personalData'   => 'ModulePersonalData',
		'registration'   => 'ModuleRegistration',
		'lostPassword'   => 'ModulePassword',
		'closeAccount'   => 'ModuleCloseAccount'
	),
	'application' => array
	(
		'form'           => 'Form',
		'search'         => 'ModuleSearch'
	),
	'miscellaneous' => array
	(
		'flash'          => 'ModuleFlash',
		'articleList'    => 'ModuleArticleList',
		'randomImage'    => 'ModuleRandomImage',
		'html'           => 'ModuleHtml',
		'rss_reader'     => 'ModuleRssReader'
	)
);


/**
 * Content elements
 */
$GLOBALS['TL_CTE'] = array
(
	'texts' => array
	(
		'headline'  => 'ContentHeadline',
		'text'      => 'ContentText',
		'html'      => 'ContentHtml',
		'list'      => 'ContentList',
		'table'     => 'ContentTable',
		'accordion' => 'ContentAccordion',
		'code'      => 'ContentCode'
	),
	'links' => array
	(
		'hyperlink' => 'ContentHyperlink',
		'toplink'   => 'ContentToplink'
	),
	'media' => array
	(
		'image'     => 'ContentImage',
		'gallery'   => 'ContentGallery',
		'player'    => 'ContentMedia',
		'youtube'   => 'ContentYouTube'
	),
	'files' => array
	(
		'download'  => 'ContentDownload',
		'downloads' => 'ContentDownloads'
	),
	'includes' => array
	(
		'article'   => 'ContentArticle',
		'alias'     => 'ContentAlias',
		'form'      => 'Form',
		'module'    => 'ContentModule',
		'teaser'    => 'ContentTeaser'
	)
);


/**
 * Back end form fields
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
	'pageSelector'   => 'PageSelector',
	'fileTree'       => 'FileTree',
	'fileSelector'   => 'FileSelector',
	'fileUpload'     => 'Upload',
	'tableWizard'    => 'TableWizard',
	'listWizard'     => 'ListWizard',
	'optionWizard'   => 'OptionWizard',
	'moduleWizard'   => 'ModuleWizard',
	'keyValueWizard' => 'KeyValueWizard',
	'imageSize'      => 'ImageSize',
	'timePeriod'     => 'TimePeriod',
	'metaWizard'     => 'MetaWizard'
);


/**
 * Front end form fields
 */
$GLOBALS['TL_FFL'] = array
(
	'headline'    => 'FormHeadline',
	'explanation' => 'FormExplanation',
	'html'        => 'FormHtml',
	'fieldset'    => 'FormFieldset',
	'text'        => 'FormTextField',
	'password'    => 'FormPassword',
	'textarea'    => 'FormTextArea',
	'select'      => 'FormSelectMenu',
	'radio'       => 'FormRadioButton',
	'checkbox'    => 'FormCheckBox',
	'upload'      => 'FormFileUpload',
	'hidden'      => 'FormHidden',
	'captcha'     => 'FormCaptcha',
	'submit'      => 'FormSubmit'
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
	'LiveUpdate',
	'RebuildIndex',
	'PurgeData'
);


/**
 * Purge jobs
 */
$GLOBALS['TL_PURGE'] = array
(
	'tables' => array
	(
		'index' => array
		(
			'callback' => array('Automator', 'purgeSearchTables'),
			'affected' => array('tl_search', 'tl_search_index')
		),
		'undo' => array
		(
			'callback' => array('Automator', 'purgeUndoTable'),
			'affected' => array('tl_undo')
		),
		'versions' => array
		(
			'callback' => array('Automator', 'purgeVersionTable'),
			'affected' => array('tl_version')
		)
	),
	'folders' => array
	(
		'images' => array
		(
			'callback' => array('Automator', 'purgeImageCache'),
			'affected' => array('assets/images')
		),
		'scripts' => array
		(
			'callback' => array('Automator', 'purgeScriptCache'),
			'affected' => array('assets/js', 'assets/css')
		),
		'pages' => array
		(
			'callback' => array('Automator', 'purgePageCache'),
			'affected' => array('system/cache/html')
		),
		'internal' => array
		(
			'callback' => array('Automator', 'purgeInternalCache'),
			'affected' => array('system/cache/dca', 'system/cache/language', 'system/cache/sql')
		),
		'temp' => array
		(
			'callback' => array('Automator', 'purgeTempFolder'),
			'affected' => array('system/tmp')
		)
	),
	'custom' => array
	(
		'xml' => array
		(
			'callback' => array('Automator', 'generateXmlFiles')
		)
	)
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
		array('Automator', 'purgeImageCache')
	),
	'weekly' => array
	(
		array('Automator', 'generateSitemap'),
		array('Automator', 'purgeScriptCache')
	),
	'daily' => array
	(
		array('Automator', 'rotateLogs'),
		array('Automator', 'purgeTempFolder'),
		array('Automator', 'checkForUpdates')
	),
	'hourly' => array(),
	'minutely' => array()
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
 * Ignore the auto_item keywords when rebuilding the search index URLs
 */
$GLOBALS['TL_AUTO_ITEM'] = array('items', 'events');


/**
 * Other global arrays
 */
$GLOBALS['TL_MIME'] = array();
$GLOBALS['TL_PERMISSIONS'] = array();
$GLOBALS['TL_MODELS'] = array();
