<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Table tl_page
 */
$GLOBALS['TL_DCA']['tl_page'] = array
(

	// Config
	'config' => array
	(
		'label'                       => $GLOBALS['TL_CONFIG']['websiteTitle'],
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_article'),
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_page', 'checkPermission'),
			array('tl_page', 'addBreadcrumb'),
			array('tl_page', 'setRootType'),
			array('tl_page', 'showFallbackWarning')
		),
		'onsubmit_callback' => array
		(
			array('tl_page', 'updateSitemap'),
			array('tl_page', 'generateArticle')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index',
				'alias' => 'index',
				'type' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 5,
			'icon'                    => 'pagemounts.gif',
			'paste_button_callback'   => array('tl_page', 'pastePage'),
			'panelLayout'             => 'filter,search'
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s',
			'label_callback'          => array('tl_page', 'addIcon')
		),
		'global_operations' => array
		(
			'toggleNodes' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['toggleAll'],
				'href'                => 'ptg=all',
				'class'               => 'header_toggle'
			),
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
				'label'               => &$GLOBALS['TL_LANG']['tl_page']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
				'button_callback'     => array('tl_page', 'editPage')
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_page']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"',
				'button_callback'     => array('tl_page', 'copyPage')
			),
			'copyChilds' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_page']['copyChilds'],
				'href'                => 'act=paste&amp;mode=copy&amp;childs=1',
				'icon'                => 'copychilds.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"',
				'button_callback'     => array('tl_page', 'copyPageWithSubpages')
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_page']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"',
				'button_callback'     => array('tl_page', 'cutPage')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_page']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
				'button_callback'     => array('tl_page', 'deletePage')
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_page']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_page', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_page']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'articles' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_page']['articles'],
				'href'                => 'do=article',
				'icon'                => 'article.gif',
				'button_callback'     => array('tl_page', 'editArticles')
			)
		)
	),

	// Edit
	'edit' => array
	(
		'buttons_callback' => array
		(
			array('tl_page', 'addAliasButton')
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('type', 'autoforward', 'protected', 'createSitemap', 'includeLayout', 'includeCache', 'includeChmod'),
		'default'                     => '{title_legend},title,alias,type;followup,start,stop',
		'regular'                     => '{title_legend},title,alias,type;{meta_legend},pageTitle,robots,description;{protected_legend:hide},protected;{layout_legend:hide},includeLayout;{cache_legend:hide},includeCache;{chmod_legend:hide},includeChmod;{search_legend},noSearch;{expert_legend:hide},cssClass,sitemap,hide,guests;{tabnav_legend:hide},tabindex,accesskey;{publish_legend},published,start,stop',
		'forward'                     => '{title_legend},title,alias,type;{meta_legend},pageTitle;{redirect_legend},redirect,jumpTo;{protected_legend:hide},protected;{layout_legend:hide},includeLayout;{cache_legend:hide},includeCache;{chmod_legend:hide},includeChmod;{expert_legend:hide},cssClass,sitemap,hide,guests;{tabnav_legend:hide},tabindex,accesskey;{publish_legend},published,start,stop',
		'redirect'                    => '{title_legend},title,alias,type;{meta_legend},pageTitle;{redirect_legend},redirect,url,target;{protected_legend:hide},protected;{layout_legend:hide},includeLayout;{cache_legend:hide},includeCache;{chmod_legend:hide},includeChmod;{expert_legend:hide},cssClass,sitemap,hide,guests;{tabnav_legend:hide},tabindex,accesskey;{publish_legend},published,start,stop',
		'root'                        => '{title_legend},title,alias,type;{meta_legend},pageTitle;{dns_legend},dns,staticFiles,staticSystem,staticPlugins,language,fallback;{global_legend:hide},dateFormat,timeFormat,datimFormat,adminEmail;{sitemap_legend:hide},createSitemap;{layout_legend:hide},includeLayout;{cache_legend:hide},includeCache;{chmod_legend:hide},includeChmod;{publish_legend},published,start,stop',
		'error_403'                   => '{title_legend},title,alias,type;{meta_legend},pageTitle,robots,description;{forward_legend:hide},autoforward;{layout_legend:hide},includeLayout;{cache_legend:hide},includeCache;{chmod_legend:hide},includeChmod;{expert_legend:hide},cssClass;{publish_legend},published,start,stop',
		'error_404'                   => '{title_legend},title,alias,type;{meta_legend},pageTitle,robots,description;{forward_legend:hide},autoforward;{layout_legend:hide},includeLayout;{cache_legend:hide},includeCache;{chmod_legend:hide},includeChmod;{expert_legend:hide},cssClass;{publish_legend},published,start,stop'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'autoforward'                 => 'redirect,jumpTo',
		'protected'                   => 'groups',
		'createSitemap'               => 'sitemapName,useSSL',
		'includeLayout'               => 'layout,mobileLayout',
		'includeCache'                => 'cache',
		'includeChmod'                => 'cuser,cgroup,chmod'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'label'                   => array('ID'),
			'search'                  => true,
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'search'                  => true,
			'eval'                    => array('rgxp'=>'folderalias', 'doNotCopy'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_page', 'generateAlias')
			),
			'sql'                     => "varbinary(128) NOT NULL default ''"
		),
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['type'],
			'default'                 => 'regular',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_page', 'getPageTypes'),
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50'),
			'reference'               => &$GLOBALS['TL_LANG']['PTY'],
			'save_callback' => array
			(
				array('tl_page', 'checkRootType')
			),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'pageTitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['pageTitle'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'search'                  => true,
			'eval'                    => array('maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'language' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['language'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'alpha', 'maxlength'=>2, 'nospace'=>true, 'tl_class'=>'w50 clr'),
			'save_callback' => array
			(
				array('tl_page', 'languageToLower')
			),
			'sql'                     => "varchar(2) NOT NULL default ''"
		),
		'robots' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['robots'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'search'                  => true,
			'options'                 => array('index,follow', 'index,nofollow', 'noindex,follow', 'noindex,nofollow'),
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['description'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'search'                  => true,
			'eval'                    => array('style'=>'height:60px', 'tl_class'=>'clr'),
			'sql'                     => "text NULL"
		),
		'redirect' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['redirect'],
			'default'                 => 'permanent',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('permanent', 'temporary'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_page'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'foreignKey'              => 'tl_page.title',
			'eval'                    => array('fieldType'=>'radio'),
			'save_callback' => array
			(
				array('tl_page', 'checkJumpTo')
			),
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['url'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'target' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['target'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'dns' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['dns'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'search'                  => true,
			'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_page', 'checkDns')
			),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'staticFiles' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['staticFiles'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'trailingSlash'=>false, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_page', 'checkStaticUrl')
			),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'staticSystem' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['staticSystem'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'trailingSlash'=>false, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_page', 'checkStaticUrl')
			),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'staticPlugins' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['staticPlugins'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'trailingSlash'=>false, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_page', 'checkStaticUrl')
			),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'fallback' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['fallback'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12'),
			'save_callback' => array
			(
				array('tl_page', 'checkFallback')
			),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'adminEmail' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['adminEmail'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'rgxp'=>'friendly', 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'dateFormat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['dateFormat'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('helpwizard'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'explanation'             => 'dateFormat',
			'sql'                     => "varchar(32) NOT NULL default ''"

		),
		'timeFormat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['timeFormat'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'datimFormat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['datimFormat'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'createSitemap' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['createSitemap'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'sitemapName' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['sitemapName'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'unique'=>true, 'rgxp'=>'alnum', 'decodeEntities'=>true, 'maxlength'=>32, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_page', 'checkFeedAlias')
			),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'useSSL' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['useSSL'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'autoforward' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['autoforward'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'protected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['protected'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true),
			'sql'                     => "blob NULL",
			'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
		),
		'includeLayout' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['includeLayout'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'layout' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['layout'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_layout.name',
			'options_callback'        => array('tl_page', 'getPageLayouts'),
			'eval'                    => array('chosen'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
		),
		'mobileLayout' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['mobileLayout'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_layout.name',
			'options_callback'        => array('tl_page', 'getPageLayouts'),
			'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
		),
		'includeCache' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['includeCache'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'cache' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['cache'],
			'default'                 => 0,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array(0, 5, 15, 30, 60, 300, 900, 1800, 3600, 10800, 21600, 43200, 86400, 259200, 604800, 2592000),
			'reference'               => &$GLOBALS['TL_LANG']['CACHE'],
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'includeChmod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['includeChmod'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'cuser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['cuser'],
			'default'                 => $GLOBALS['TL_CONFIG']['defaultUser'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.username',
			'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
		),
		'cgroup' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['cgroup'],
			'default'                 => $GLOBALS['TL_CONFIG']['defaultGroup'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user_group.name',
			'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
		),
		'chmod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['chmod'],
			'default'                 => $GLOBALS['TL_CONFIG']['defaultChmod'],
			'exclude'                 => true,
			'inputType'               => 'chmod',
			'eval'                    => array('tl_class'=>'clr'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'noSearch' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['noSearch'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'cssClass' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['cssClass'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'search'                  => true,
			'eval'                    => array('maxlength'=>64, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'sitemap' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['sitemap'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('map_default', 'map_always', 'map_never'),
			'eval'                    => array('maxlength'=>32, 'tl_class'=>'w50'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_page'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'hide' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['hide'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'guests' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['guests'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'tabindex' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['tabindex'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'accesskey' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['accesskey'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'maxlength'=>1, 'tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['published'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		)
	)
);


/**
 * Class tl_page
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Core
 */
class tl_page extends Backend
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
	 * Check permissions to edit table tl_page
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		$session = $this->Session->getData();

		// Set the default page user and group
		$GLOBALS['TL_DCA']['tl_page']['fields']['cuser']['default'] = $GLOBALS['TL_CONFIG']['defaultUser'] ?: $this->User->id;
		$GLOBALS['TL_DCA']['tl_page']['fields']['cgroup']['default'] = $GLOBALS['TL_CONFIG']['defaultGroup'] ?: $this->User->groups[0];

		// Restrict the page tree
		$GLOBALS['TL_DCA']['tl_page']['list']['sorting']['root'] = $this->User->pagemounts;

		// Set allowed page IDs (edit multiple)
		if (is_array($session['CURRENT']['IDS']))
		{
			$edit_all = array();
			$delete_all = array();

			foreach ($session['CURRENT']['IDS'] as $id)
			{
				$objPage = $this->Database->prepare("SELECT id, pid, type, includeChmod, chmod, cuser, cgroup FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($id);

				if ($objPage->numRows < 1 || !$this->User->hasAccess($objPage->type, 'alpty'))
				{
					continue;
				}

				$row = $objPage->row();

				if ($this->User->isAllowed(1, $row))
				{
					$edit_all[] = $id;
				}

				// Mounted pages cannot be deleted
				if ($this->User->isAllowed(3, $row) && !$this->User->hasAccess($id, 'pagemounts'))
				{
					$delete_all[] = $id;
				}
			}

			$session['CURRENT']['IDS'] = (Input::get('act') == 'deleteAll') ? $delete_all : $edit_all;
		}

		// Set allowed clipboard IDs
		if (isset($session['CLIPBOARD']['tl_page']) && is_array($session['CLIPBOARD']['tl_page']['id']))
		{
			$clipboard = array();

			foreach ($session['CLIPBOARD']['tl_page']['id'] as $id)
			{
				$objPage = $this->Database->prepare("SELECT id, pid, type, includeChmod, chmod, cuser, cgroup FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($id);

				if ($objPage->numRows < 1 || !$this->User->hasAccess($objPage->type, 'alpty'))
				{
					continue;
				}

				if ($this->User->isAllowed(2, $objPage->row()))
				{
					$clipboard[] = $id;
				}
			}

			$session['CLIPBOARD']['tl_page']['id'] = $clipboard;
		}

		// Overwrite session
		$this->Session->setData($session);

		// Check permissions to save and create new
		if (Input::get('act') == 'edit')
		{
			$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=(SELECT pid FROM tl_page WHERE id=?)")
									  ->limit(1)
									  ->execute(Input::get('id'));

			if ($objPage->numRows && !$this->User->isAllowed(2, $objPage->row()))
			{
				$GLOBALS['TL_DCA']['tl_page']['config']['closed'] = true;
			}
		}

		// Check current action
		if (Input::get('act') && Input::get('act') != 'paste')
		{
			$permission = 0;
			$cid = CURRENT_ID ?: Input::get('id');
			$ids = ($cid != '') ? array($cid) : array();

			// Set permission
			switch (Input::get('act'))
			{
				case 'edit':
				case 'toggle':
					$permission = 1;
					break;

				case 'move':
					$permission = 2;
					$ids[] = Input::get('sid');
					break;

				case 'create':
				case 'copy':
				case 'copyAll':
				case 'cut':
				case 'cutAll':
					$permission = 2;

					// Check the parent page in "paste into" mode
					if (Input::get('mode') == 2)
					{
						$ids[] = Input::get('pid');
					}
					// Check the parent's parent page in "paste after" mode
					else
					{
						$objPage = $this->Database->prepare("SELECT pid FROM tl_page WHERE id=?")
												  ->limit(1)
												  ->execute(Input::get('pid'));

						$ids[] = $objPage->pid;
					}
					break;

				case 'delete':
					$permission = 3;
					break;
			}

			// Check user permissions
			if (Input::get('act') != 'show')
			{
				$pagemounts = array();

				// Get all allowed pages for the current user
				foreach ($this->User->pagemounts as $root)
				{
					if (Input::get('act') != 'delete')
					{
						$pagemounts[] = $root;
					}

					$pagemounts = array_merge($pagemounts, $this->getChildRecords($root, 'tl_page'));
				}

				$error = false;
				$pagemounts = array_unique($pagemounts);

				// Do not allow to paste after pages on the root level (pagemounts)
				if ((Input::get('act') == 'cut' || Input::get('act') == 'cutAll') && Input::get('mode') == 1 && in_array(Input::get('pid'), $this->eliminateNestedPages($this->User->pagemounts)))
				{
					$this->log('Not enough permissions to paste page ID '. Input::get('id') .' after mounted page ID '. Input::get('pid') .' (root level)', 'tl_page checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				// Check each page
				foreach ($ids as $i=>$id)
				{
					if (!in_array($id, $pagemounts))
					{
						$this->log('Page ID '. $id .' was not mounted', 'tl_page checkPermission()', TL_ERROR);

						$error = true;
						break;
					}

					// Get the page object
					$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
											  ->limit(1)
											  ->execute($id);

					if ($objPage->numRows < 1)
					{
						continue;
					}

					// Check whether the current user is allowed to access the current page
					if (!$this->User->isAllowed($permission, $objPage->row()))
					{
						$error = true;
						break;
					}

					// Check the type of the first page (not the following parent pages)
					if ($i == 0 && Input::get('act') != 'create' && !$this->User->hasAccess($objPage->type, 'alpty'))
					{
						$this->log('Not enough permissions to  '. Input::get('act') .' '. $objPage->type .' pages', 'tl_page checkPermission()', TL_ERROR);

						$error = true;
						break;
					}
				}

				// Redirect if there is an error
				if ($error)
				{
					$this->log('Not enough permissions to '. Input::get('act') .' page ID '. $cid .' or paste after/into page ID '. Input::get('pid'), 'tl_page checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
			}
		}
	}


	/**
	 * Add the breadcrumb menu
	 */
	public function addBreadcrumb()
	{
		// Set a new node
		if (isset($_GET['node']))
		{
			$this->Session->set('tl_page_node', Input::get('node'));
			$this->redirect(preg_replace('/&node=[^&]*/', '', Environment::get('request')));
		}

		$intNode = $this->Session->get('tl_page_node');

		if ($intNode < 1)
		{
			return;
		}

		$arrIds = array();
		$arrLinks = array();

		// Generate breadcrumb trail
		if ($intNode)
		{
			$intId = $intNode;

			do
			{
				$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
								->limit(1)
								->execute($intId);

				if ($objPage->numRows < 1)
				{
					// Currently selected page does not exits
					if ($intId == $intNode)
					{
						$this->Session->set('tl_page_node', 0);
						return;
					}

					break;
				}

				$arrIds[] = $intId;

				// No link for the active page
				if ($objPage->id == $intNode)
				{
					$arrLinks[] = $this->addIcon($objPage->row(), '', null, '', true) . ' ' . $objPage->title;
				}
				else
				{
					$arrLinks[] = $this->addIcon($objPage->row(), '', null, '', true) . ' <a href="' . $this->addToUrl('node='.$objPage->id) . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectNode']).'">' . $objPage->title . '</a>';
				}

				// Do not show the mounted pages
				if (!$this->User->isAdmin && $this->User->hasAccess($objPage->id, 'pagemounts'))
				{
					break;
				}

				$intId = $objPage->pid;
			}
			while ($intId > 0 && $objPage->type != 'root');
		}

		// Check whether the node is mounted
		if (!$this->User->isAdmin && !$this->User->hasAccess($arrIds, 'pagemounts'))
		{
			$this->Session->set('tl_page_node', 0);

			$this->log('Page ID '.$intNode.' was not mounted', 'tl_page addBreadcrumb', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		// Limit tree
		$GLOBALS['TL_DCA']['tl_page']['list']['sorting']['root'] = array($intNode);

		// Add root link
		$arrLinks[] = '<img src="' . TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/pagemounts.gif" width="18" height="18" alt=""> <a href="' . $this->addToUrl('node=0') . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectAllNodes']).'">' . $GLOBALS['TL_LANG']['MSC']['filterAll'] . '</a>';
		$arrLinks = array_reverse($arrLinks);

		// Insert breadcrumb menu
		$GLOBALS['TL_DCA']['tl_page']['list']['sorting']['breadcrumb'] .= '

<ul id="tl_breadcrumb">
  <li>' . implode(' &gt; </li><li>', $arrLinks) . '</li>
</ul>';
	}


	/**
	 * Make new top-level pages root pages
	 * @param \DataContainer
	 */
	public function setRootType(DataContainer $dc)
	{
		if (Input::get('act') != 'create')
		{
			return;
		}

		// Insert into
		if (Input::get('pid') == 0)
		{
			$GLOBALS['TL_DCA']['tl_page']['fields']['type']['default'] = 'root';
		}

		// Insert after
		else
		{
			$objPage = $this->Database->prepare("SELECT * FROM " . $dc->table . " WHERE id=?")
									  ->limit(1)
									  ->execute(Input::get('pid'));

			if ($objPage->pid == 0)
			{
				$GLOBALS['TL_DCA']['tl_page']['fields']['type']['default'] = 'root';
			}
		}
	}


	/**
	 * Make sure that top-level pages are root pages
	 * @param mixed
	 * @param \DataContainer
	 * @return mixed
	 * @throws \Exception
	 */
	public function checkRootType($varValue, DataContainer $dc)
	{
		if ($varValue != 'root' && $dc->activeRecord->pid == 0)
		{
			throw new Exception($GLOBALS['TL_LANG']['ERR']['topLevelRoot']);
		}

		return $varValue;
	}


	/**
	 * Show a warning if there is no language fallback page
	 */
	public function showFallbackWarning()
	{
		if (Input::get('act') != '')
		{
			return;
		}

		$this->import('Messages');
		Message::addRaw($this->Messages->languageFallback());
		Message::addRaw($this->Messages->topLevelRoot());
	}


	/**
	 * Auto-generate a page alias if it has not been set yet
	 * @param mixed
	 * @param \DataContainer
	 * @return string
	 * @throws \Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate an alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$varValue = standardize($this->restoreBasicEntities($dc->activeRecord->title));
		}

		$objAlias = $this->Database->prepare("SELECT * FROM tl_page WHERE id=? OR alias=?")
								   ->execute($dc->id, $varValue);

		// Check whether the page alias exists
		if ($objAlias->numRows > ($autoAlias ? 0 : 1))
		{
			$arrPages = array();
			$strDomain = '';
			$strLanguage = '';

			while ($objAlias->next())
			{
				$objCurrentPage = $this->getPageDetails($objAlias);
				$domain = $objCurrentPage->domain ?: '*';
				$language = (!$objCurrentPage->rootIsFallback) ? $objCurrentPage->rootLanguage : '*';

				// Store the current page's data
				if ($objCurrentPage->id == $dc->id)
				{
					$strDomain = $domain;
					$strLanguage = $language;
				}
				else
				{
					if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'])
					{
						// Check domain and language
						$arrPages[$domain][$language][] = $objAlias->id;
					}
					else
					{
						// Check the domain only
						$arrPages[$domain][] = $objAlias->id;
					}
				}
			}

			$arrCheck = $GLOBALS['TL_CONFIG']['addLanguageToUrl'] ? $arrPages[$strDomain][$strLanguage] : $arrPages[$strDomain];

			// Check if there are multiple results for the current domain
			if (!empty($arrCheck))
			{
				if ($autoAlias)
				{
					$varValue .= '-' . $dc->id;
				}
				else
				{
					throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
				}
			}
		}

		return $varValue;
	}


	/**
	 * Convert language tags to lowercase letters
	 * @param string
	 * @return string
	 */
	public function languageToLower($varValue)
	{
		return strtolower($varValue);
	}


	/**
	 * Automatically create an article in the main column of a new page
	 * @param \DataContainer
	 */
	public function generateArticle(DataContainer $dc)
	{
		// Return if there is no active record (override all)
		if (!$dc->activeRecord)
		{
			return;
		}

		// Existing or not a regular page
		if ($dc->activeRecord->tstamp > 0 || !in_array($dc->activeRecord->type, array('regular', 'error_403', 'error_404')))
		{
			return;
		}

		$new_records = $this->Session->get('new_records');

		// Not a new page
		if (!$new_records || (is_array($new_records[$dc->table]) && !in_array($dc->id, $new_records[$dc->table])))
		{
			return;
		}

		// Check whether there are articles (e.g. on copied pages)
		$objTotal = $this->Database->prepare("SELECT COUNT(*) AS count FROM tl_article WHERE pid=?")
								   ->execute($dc->id);

		if ($objTotal->count > 0)
		{
			return;
		}

		// Create article
		$arrSet['pid'] = $dc->id;
		$arrSet['sorting'] = 128;
		$arrSet['tstamp'] = time();
		$arrSet['author'] = $this->User->id;
		$arrSet['inColumn'] = 'main';
		$arrSet['title'] = $dc->activeRecord->title;
		$arrSet['alias'] = $dc->activeRecord->alias;
		$arrSet['published'] = $dc->activeRecord->published;

		$this->Database->prepare("INSERT INTO tl_article %s")->set($arrSet)->execute();
	}


	/**
	 * Check the sitemap alias
	 * @param mixed
	 * @param \DataContainer
	 * @return mixed
	 * @throws \Exception
	 */
	public function checkFeedAlias($varValue, DataContainer $dc)
	{
		// No change or empty value
		if ($varValue == $dc->value || $varValue == '')
		{
			return $varValue;
		}

		$arrFeeds = $this->removeOldFeeds(true);

		// Alias exists
		if (array_search($varValue, $arrFeeds) !== false)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		return $varValue;
	}


	/**
	 * Prevent circular references
	 * @param mixed
	 * @param \DataContainer
	 * @return mixed
	 * @throws \Exception
	 */
	public function checkJumpTo($varValue, DataContainer $dc)
	{
		if ($varValue == $dc->id)
		{
			throw new Exception($GLOBALS['TL_LANG']['ERR']['circularReference']);
		}

		return $varValue;
	}


	/**
	 * Check the DNS settings
	 * @param mixed
	 * @return mixed
	 */
	public function checkDns($varValue)
	{
		return str_ireplace(array('http://', 'https://', 'ftp://'), '', $varValue);
	}


	/**
	 * Make sure there is only one fallback per domain (thanks to Andreas Schempp)
	 * @param mixed
	 * @param \DataContainer
	 * @return mixed
	 * @throws \Exception
	 */
	public function checkFallback($varValue, DataContainer $dc)
	{
		if ($varValue == '')
		{
			return '';
		}

		$objPage = $this->Database->prepare("SELECT id FROM tl_page WHERE type='root' AND fallback=1 AND dns=? AND id!=?")
								  ->execute($dc->activeRecord->dns, $dc->activeRecord->id);

		if ($objPage->numRows)
		{
			throw new Exception($GLOBALS['TL_LANG']['ERR']['multipleFallback']);
		}

		return $varValue;
	}


	/**
	 * Check a static URL
	 * @param mixed
	 * @return array
	 */
	public function checkStaticUrl($varValue)
	{
		if ($varValue != '' && !preg_match('@^https?://@', $varValue))
		{
			$varValue = (Environment::get('ssl') ? 'https://' : 'http://') . $varValue;
		}

		return $varValue;
	}


	/**
	 * Returns all allowed page types as array
	 * @param \DataContainer
	 * @return string
	 */
	public function getPageTypes(DataContainer $dc)
	{
		$arrOptions = array();

		foreach (array_keys($GLOBALS['TL_PTY']) as $pty)
		{
			if ($pty == $dc->value || $this->User->hasAccess($pty, 'alpty'))
			{
				$arrOptions[] = $pty;
			}
		}

		return $arrOptions;
	}


	/**
	 * Return all page layouts grouped by theme
	 * @return array
	 */
	public function getPageLayouts()
	{
		$objLayout = $this->Database->execute("SELECT l.id, l.name, t.name AS theme FROM tl_layout l LEFT JOIN tl_theme t ON l.pid=t.id ORDER BY t.name, l.name");

		if ($objLayout->numRows < 1)
		{
			return array();
		}

		$return = array();

		while ($objLayout->next())
		{
			$return[$objLayout->theme][$objLayout->id] = $objLayout->name;
		}

		return $return;
	}


	/**
	 * Add an image to each page in the tree
	 * @param array
	 * @param string
	 * @param \DataContainer
	 * @param string
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	public function addIcon($row, $label, DataContainer $dc=null, $imageAttribute='', $blnReturnImage=false, $blnProtected=false)
	{
		if ($blnProtected)
		{
			$row['protected'] = true;
		}

		$image = $this->getPageStatusIcon((object)$row);

		// Return the image only
		if ($blnReturnImage)
		{
			return $this->generateImage($image, '', $imageAttribute);
		}

		// Mark root pages
		if ($row['type'] == 'root' || Input::get('do') == 'article')
		{
			$label = '<strong>' . $label . '</strong>';
		}

		// Add the breadcrumb link
		$label = '<a href="' . $this->addToUrl('node='.$row['id']) . '" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['selectNode']).'">' . $label . '</a>';

		// Return the image
		return '<a href="contao/main.php?do=feRedirect&amp;page='.$row['id'].'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['view']).'"' . (($dc->table != 'tl_page') ? ' class="tl_gray"' : '') . ' target="_blank">'.$this->generateImage($image, '', $imageAttribute).'</a> '.$label;
	}


	/**
	 * Return the edit page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editPage($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || ($this->User->hasAccess($row['type'], 'alpty') && $this->User->isAllowed(1, $row))) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the copy page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyPage($row, $href, $label, $title, $icon, $attributes, $table)
	{
		if ($GLOBALS['TL_DCA'][$table]['config']['closed'])
		{
			return '';
		}

		return ($this->User->isAdmin || ($this->User->hasAccess($row['type'], 'alpty') && $this->User->isAllowed(2, $row))) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the copy page with subpages button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyPageWithSubpages($row, $href, $label, $title, $icon, $attributes, $table)
	{
		if ($GLOBALS['TL_DCA'][$table]['config']['closed'])
		{
			return '';
		}

		$objSubpages = $this->Database->prepare("SELECT * FROM tl_page WHERE pid=?")
									  ->limit(1)
									  ->execute($row['id']);

		return ($objSubpages->numRows && ($this->User->isAdmin || ($this->User->hasAccess($row['type'], 'alpty') && $this->User->isAllowed(2, $row)))) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the cut page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function cutPage($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || ($this->User->hasAccess($row['type'], 'alpty') && $this->User->isAllowed(2, $row))) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the paste page button
	 * @param \DataContainer
	 * @param array
	 * @param string
	 * @param boolean
	 * @param array
	 * @return string
	 */
	public function pastePage(DataContainer $dc, $row, $table, $cr, $arrClipboard=null)
	{
		$disablePA = false;
		$disablePI = false;

		// Disable all buttons if there is a circular reference
		if ($arrClipboard !== false && ($arrClipboard['mode'] == 'cut' && ($cr == 1 || $arrClipboard['id'] == $row['id']) || $arrClipboard['mode'] == 'cutAll' && ($cr == 1 || in_array($row['id'], $arrClipboard['id']))))
		{
			$disablePA = true;
			$disablePI = true;
		}

		// Prevent adding non-root pages on top-level
		if (Input::get('mode') != 'create' && $row['pid'] == 0)
		{
			$objPage = $this->Database->prepare("SELECT * FROM " . $table . " WHERE id=?")
									  ->limit(1)
									  ->execute(Input::get('id'));

			if ($objPage->type != 'root')
			{
				$disablePA = true;

				if ($row['id'] == 0)
				{
					$disablePI = true;
				}
			}
		}

		// Check permissions if the user is not an administrator
		if (!$this->User->isAdmin)
		{
			// Disable "paste into" button if there is no permission 2 (move) or 1 (create) for the current page
			if (!$disablePI)
			{
				if (!$this->User->isAllowed(2, $row) || (Input::get('mode') == 'create' && !$this->User->isAllowed(1, $row)))
				{
					$disablePI = true;
				}
			}

			$objPage = $this->Database->prepare("SELECT * FROM " . $table . " WHERE id=?")
									  ->limit(1)
									  ->execute($row['pid']);

			// Disable "paste after" button if there is no permission 2 (move) or 1 (create) for the parent page
			if (!$disablePA && $objPage->numRows)
			{
				if (!$this->User->isAllowed(2, $objPage->row()) || (Input::get('mode') == 'create' && !$this->User->isAllowed(1, $objPage->row())))
				{
					$disablePA = true;
				}
			}

			// Disable "paste after" button if the parent page is a root page and the user is not an administrator
			if (!$disablePA && ($row['pid'] < 1 || in_array($row['id'], $dc->rootIds)))
			{
				$disablePA = true;
			}
		}

		$return = '';

		// Return the buttons
		$imagePasteAfter = $this->generateImage('pasteafter.gif', sprintf($GLOBALS['TL_LANG'][$table]['pasteafter'][1], $row['id']));
		$imagePasteInto = $this->generateImage('pasteinto.gif', sprintf($GLOBALS['TL_LANG'][$table]['pasteinto'][1], $row['id']));

		if ($row['id'] > 0)
		{
			$return = $disablePA ? $this->generateImage('pasteafter_.gif').' ' : '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&amp;mode=1&amp;pid='.$row['id'].(!is_array($arrClipboard['id']) ? '&amp;id='.$arrClipboard['id'] : '')).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$table]['pasteafter'][1], $row['id'])).'" onclick="Backend.getScrollOffset()">'.$imagePasteAfter.'</a> ';
		}

		return $return.($disablePI ? $this->generateImage('pasteinto_.gif').' ' : '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&amp;mode=2&amp;pid='.$row['id'].(!is_array($arrClipboard['id']) ? '&amp;id='.$arrClipboard['id'] : '')).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$table]['pasteinto'][1], $row['id'])).'" onclick="Backend.getScrollOffset()">'.$imagePasteInto.'</a> ');
	}


	/**
	 * Return the delete page button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deletePage($row, $href, $label, $title, $icon, $attributes)
	{
		$root = func_get_arg(7);
		return ($this->User->isAdmin || ($this->User->hasAccess($row['type'], 'alpty') && $this->User->isAllowed(3, $row) && !in_array($row['id'], $root))) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Generate an "edit articles" button and return it as string
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editArticles($row, $href, $label, $title, $icon)
	{
		if (!$this->User->isAdmin && !$this->User->hasAccess('article', 'modules'))
		{
			return '';
		}

		return ($row['type'] == 'regular' || $row['type'] == 'error_403' || $row['type'] == 'error_404') ? '<a href="' . $this->addToUrl($href.'&amp;node='.$row['id']) . '" title="'.specialchars($title).'">'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Automatically generate the folder URL aliases
	 * @return string
	 */
	public function addAliasButton()
	{
		if (Input::post('FORM_SUBMIT') == 'tl_select' && isset($_POST['alias']))
		{
			$session = $this->Session->getData();

			$ids = $session['CURRENT']['IDS'];
			$ids = $this->eliminateNestedPages($ids);
			$ids = $this->getChildRecords($ids, 'tl_page');

			foreach ($ids as $id)
			{
				$objPage = $this->getPageDetails($id);

				if ($objPage !== null)
				{
					$this->Database->prepare("UPDATE tl_page SET alias=? WHERE id=?")
								   ->execute($objPage->folderUrl, $id);
				}
			}

			$this->redirect($this->getReferer());
		}

		return '<input type="submit" name="alias" id="alias" class="tl_submit" accesskey="a" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['aliasSelected']).'"> ';
	}


	/**
	 * Recursively add pages to a sitemap
	 * @param \DataContainer
	 */
	public function updateSitemap(DataContainer $dc)
	{
		$this->import('Automator');
		$this->Automator->generateSitemap($dc->id);
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
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_page::published', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}

		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($row['id']);

		if (!$this->User->isAdmin && !$this->User->isAllowed(2, $objPage->row()))
		{
			return $this->generateImage($icon) . ' ';
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
		Input::setGet('id', $intId);
		Input::setGet('act', 'toggle');
		$this->checkPermission();

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_page::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish page ID "'.$intId.'"', 'tl_page toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_page', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_page']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_page']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_page SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_page', $intId);
	}
}
