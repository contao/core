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
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 5,
			'icon'                    => 'pagemounts.gif',
			'paste_button_callback'   => array('tl_page', 'pastePage')
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
				'label'               => &$GLOBALS['TL_LANG']['MSC']['toggleNodes'],
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

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('type', 'autoforward', 'protected', 'createSitemap', 'includeLayout', 'includeCache', 'includeChmod'),
		'default'                     => '{title_legend},title,alias,type;followup,start,stop',
		'regular'                     => '{title_legend},title,alias,type;{meta_legend},pageTitle,robots,description;{protected_legend:hide},protected;{layout_legend:hide},includeLayout;{cache_legend:hide},includeCache;{chmod_legend:hide},includeChmod;{search_legend},noSearch;{expert_legend:hide},cssClass,sitemap,hide,guests;{tabnav_legend:hide},tabindex,accesskey;{publish_legend},published,start,stop',
		'forward'                     => '{title_legend},title,alias,type;{meta_legend},pageTitle;{redirect_legend},redirect,jumpTo;{protected_legend:hide},protected;{layout_legend:hide},includeLayout;{cache_legend:hide},includeCache;{chmod_legend:hide},includeChmod;{expert_legend:hide},cssClass,sitemap,hide,guests;{tabnav_legend:hide},tabindex,accesskey;{publish_legend},published,start,stop',
		'redirect'                    => '{title_legend},title,alias,type;{meta_legend},pageTitle;{redirect_legend},redirect,url,target;{protected_legend:hide},protected;{layout_legend:hide},includeLayout;{cache_legend:hide},includeCache;{chmod_legend:hide},includeChmod;{expert_legend:hide},cssClass,sitemap,hide,guests;{tabnav_legend:hide},tabindex,accesskey;{publish_legend},published,start,stop',
		'root'                        => '{title_legend},title,alias,type;{meta_legend},pageTitle,adminEmail,dateFormat,timeFormat,datimFormat;{dns_legend},dns,staticFiles,staticSystem,staticPlugins,language,fallback;{sitemap_legend:hide},createSitemap;{layout_legend:hide},includeLayout;{cache_legend:hide},includeCache;{chmod_legend:hide},includeChmod;{publish_legend},published,start,stop',
		'error_403'                   => '{title_legend},title,alias,type;{meta_legend},pageTitle,robots,description;{forward_legend:hide},autoforward;{layout_legend:hide},includeLayout;{cache_legend:hide},includeCache;{chmod_legend:hide},includeChmod;{expert_legend:hide},cssClass;{publish_legend},published,start,stop',
		'error_404'                   => '{title_legend},title,alias,type;{meta_legend},pageTitle,robots,description;{forward_legend:hide},autoforward;{layout_legend:hide},includeLayout;{cache_legend:hide},includeCache;{chmod_legend:hide},includeChmod;{expert_legend:hide},cssClass;{publish_legend},published,start,stop'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'autoforward'                 => 'redirect,jumpTo',
		'protected'                   => 'groups',
		'createSitemap'               => 'sitemapName,useSSL',
		'includeLayout'               => 'layout',
		'includeCache'                => 'cache',
		'includeChmod'                => 'cuser,cgroup,chmod'
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true)
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'doNotCopy'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_page', 'generateAlias')
			)
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
			)
		),
		'pageTitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['pageTitle'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'long')
		),
		'language' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['language'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'alpha', 'maxlength'=>2, 'nospace'=>true, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_page', 'languageToLower')
			)
		),
		'robots' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['robots'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('index,follow', 'index,nofollow', 'noindex,follow', 'noindex,nofollow'),
			'eval'                    => array('tl_class'=>'w50')
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['description'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('style'=>'height:60px;', 'tl_class'=>'clr', 'decodeEntities'=>true)
		),
		'redirect' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['redirect'],
			'default'                 => 'permanent',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('permanent', 'temporary'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_page']
		),
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array('fieldType'=>'radio'),
			'save_callback' => array
			(
				array('tl_page', 'checkJumpTo')
			)
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['url'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'target' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['target'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
		),
		'dns' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['dns'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_page', 'checkDns')
			)
		),
		'staticFiles' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['staticFiles'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'trailingSlash'=>false, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_page', 'checkStaticUrl')
			)
		),
		'staticSystem' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['staticSystem'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'trailingSlash'=>false, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_page', 'checkStaticUrl')
			)
		),
		'staticPlugins' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['staticPlugins'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'trailingSlash'=>false, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_page', 'checkStaticUrl')
			)
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
			)
		),
		'adminEmail' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['adminEmail'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'rgxp'=>'friendly', 'decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'dateFormat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['dateFormat'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('helpwizard'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'explanation'             => 'dateFormat'

		),
		'timeFormat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['timeFormat'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'datimFormat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['datimFormat'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'createSitemap' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['createSitemap'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
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
			)
		),
		'useSSL' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['useSSL'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
		),
		'autoforward' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['autoforward'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'protected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['protected'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true)
		),
		'includeLayout' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['includeLayout'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'layout' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['layout'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_page', 'getPageLayouts'),
			'eval'                    => array('chosen'=>true)
		),
		'includeCache' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['includeCache'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'cache' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['cache'],
			'default'                 => 0,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array(0, 5, 15, 30, 60, 300, 900, 1800, 3600, 10800, 21600, 43200, 86400, 259200, 604800, 2592000),
			'reference'               => &$GLOBALS['TL_LANG']['CACHE']
		),
		'includeChmod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['includeChmod'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'cuser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['cuser'],
			'default'                 => $GLOBALS['TL_CONFIG']['defaultUser'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user.username',
			'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'cgroup' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['cgroup'],
			'default'                 => $GLOBALS['TL_CONFIG']['defaultGroup'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_user_group.name',
			'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50')
		),
		'chmod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['chmod'],
			'default'                 => $GLOBALS['TL_CONFIG']['defaultChmod'],
			'exclude'                 => true,
			'inputType'               => 'chmod',
			'eval'                    => array('tl_class'=>'clr')
		),
		'noSearch' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['noSearch'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'cssClass' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['cssClass'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64, 'tl_class'=>'w50')
		),
		'sitemap' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['sitemap'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('map_default', 'map_always', 'map_never'),
			'eval'                    => array('maxlength'=>32, 'tl_class'=>'w50'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_page']
		),
		'hide' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['hide'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'guests' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['guests'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'tabindex' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['tabindex'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'accesskey' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['accesskey'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'maxlength'=>1, 'tl_class'=>'w50')
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['published'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true)
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_page']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard')
		)
	)
);


/**
 * Class tl_page
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
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
		$GLOBALS['TL_DCA']['tl_page']['fields']['cuser']['default'] = ($GLOBALS['TL_CONFIG']['defaultUser'] != '') ? $GLOBALS['TL_CONFIG']['defaultUser'] : $this->User->id;
		$GLOBALS['TL_DCA']['tl_page']['fields']['cgroup']['default'] = ($GLOBALS['TL_CONFIG']['defaultGroup'] != '') ? $GLOBALS['TL_CONFIG']['defaultGroup'] : $this->User->groups[0];

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

			$session['CURRENT']['IDS'] = ($this->Input->get('act') == 'deleteAll') ? $delete_all : $edit_all;
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
		if ($this->Input->get('act') == 'edit')
		{
			$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=(SELECT pid FROM tl_page WHERE id=?)")
									  ->limit(1)
									  ->execute($this->Input->get('id'));

			if ($objPage->numRows && !$this->User->isAllowed(2, $objPage->row()))
			{
				$GLOBALS['TL_DCA']['tl_page']['config']['closed'] = true;
			}
		}

		// Check current action
		if ($this->Input->get('act') && $this->Input->get('act') != 'paste')
		{
			$cid = (CURRENT_ID != '') ? CURRENT_ID : $this->Input->get('id');
			$ids = ($cid != '') ? array($cid) : array();

			// Set permission
			switch ($this->Input->get('act'))
			{
				case 'edit':
				case 'toggle':
					$permission = 1;
					break;

				case 'move':
					$permission = 2;
					$ids[] = $this->Input->get('sid');
					break;

				case 'create':
				case 'copy':
				case 'copyAll':
				case 'cut':
				case 'cutAll':
					$permission = 2;

					// Check the parent page in "paste into" mode
					if ($this->Input->get('mode') == 2)
					{
						$ids[] = $this->Input->get('pid');
					}

					// Check the parent's parent page in "paste after" mode
					else
					{
						$objPage = $this->Database->prepare("SELECT pid FROM tl_page WHERE id=?")
												  ->limit(1)
												  ->execute($this->Input->get('pid'));

						$ids[] = $objPage->pid;
					}
					break;

				case 'delete':
					$permission = 3;
					break;
			}

			// Check user permissions
			if ($this->Input->get('act') != 'show')
			{
				$pagemounts = array();

				// Get all allowed pages for the current user
				foreach ($this->User->pagemounts as $root)
				{
					if ($this->Input->get('act') != 'delete')
					{
						$pagemounts[] = $root;
					}

					$pagemounts = array_merge($pagemounts, $this->getChildRecords($root, 'tl_page'));
				}

				$error = false;
				$pagemounts = array_unique($pagemounts);

				// Do not allow to paste after pages on the root level (pagemounts)
				if (($this->Input->get('act') == 'cut' || $this->Input->get('act') == 'cutAll') && $this->Input->get('mode') == 1 && in_array($this->Input->get('pid'), $this->eliminateNestedPages($this->User->pagemounts)))
				{
					$this->log('Not enough permissions to paste page ID '. $id .' after mounted page ID '. $this->Input->get('pid') .' (root level)', 'tl_page checkPermission', TL_ERROR);
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
					if ($i == 0 && $this->Input->get('act') != 'create' && !$this->User->hasAccess($objPage->type, 'alpty'))
					{
						$this->log('Not enough permissions to  '. $this->Input->get('act') .' '. $objPage->type .' pages', 'tl_page checkPermission()', TL_ERROR);

						$error = true;
						break;
					}
				}

				// Redirect if there is an error
				if ($error)
				{
					$this->log('Not enough permissions to '. $this->Input->get('act') .' page ID '. $cid .' or paste after/into page ID '. $this->Input->get('pid'), 'tl_page checkPermission', TL_ERROR);
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
			$this->Session->set('tl_page_node', $this->Input->get('node'));
			$this->redirect(preg_replace('/&node=[^&]*/', '', $this->Environment->request));
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
					$arrLinks[] = $this->addIcon($objPage->row(), '', null, '', true) . ' <a href="' . $this->addToUrl('node='.$objPage->id) . '">' . $objPage->title . '</a>';
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
		$arrLinks[] = '<img src="' . TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/pagemounts.gif" width="18" height="18" alt=""> <a href="' . $this->addToUrl('node=0') . '">' . $GLOBALS['TL_LANG']['MSC']['filterAll'] . '</a>';
		$arrLinks = array_reverse($arrLinks);

		// Insert breadcrumb menu
		$GLOBALS['TL_DCA']['tl_page']['list']['sorting']['breadcrumb'] .= '

<ul id="tl_breadcrumb">
  <li>' . implode(' &gt; </li><li>', $arrLinks) . '</li>
</ul>';
	}


	/**
	 * Make new top-level pages root pages
	 * @param DataContainer
	 */
	public function setRootType(DataContainer $dc)
	{
		if ($this->Input->get('act') != 'create')
		{
			return;
		}	

		if ($this->Input->get('pid') == 0)
		{
			$GLOBALS['TL_DCA']['tl_page']['fields']['type']['default'] = 'root';
		}
		elseif ($this->Input->get('mode') == 1)
		{
			$objPage = $this->Database->prepare("SELECT * FROM " . $dc->table . " WHERE id=?")
									  ->limit(1)
									  ->execute($this->Input->get('pid'));

			if ($objPage->pid == 0)
			{
				$GLOBALS['TL_DCA']['tl_page']['fields']['type']['default'] = 'root';
			}
		}
	}


	/**
	 * Make sure that top-level pages are root pages
	 * @param mixed
	 * @param DataContainer
	 * @return mixed
	 * @throws Exception
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
		if ($this->Input->get('act') != '')
		{
			return;
		}

		$this->import('Messages');
		$this->addRawMessage($this->Messages->languageFallback());
		$this->addRawMessage($this->Messages->topLevelRoot());
	}


	/**
	 * Auto-generate a page alias if it has not been set yet
	 * @param mixed
	 * @param DataContainer
	 * @return string
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

		$objAlias = $this->Database->prepare("SELECT id FROM tl_page WHERE id=? OR alias=?")
								   ->execute($dc->id, $varValue);

		// Check whether the page alias exists
		if ($objAlias->numRows > ($autoAlias ? 0 : 1))
		{
			$arrPages = array();
			$strDomain = '';
			$strLanguage = '';

			while ($objAlias->next())
			{
				$objCurrentPage = $this->getPageDetails($objAlias->id);
				$domain = ($objCurrentPage->domain != '') ? $objCurrentPage->domain : '*';
				$language = (!$objCurrentPage->rootIsFallback) ? $objCurrentPage->rootLanguage : '*';

				// Store the current page's data
				if ($objCurrentPage->id == $dc->id)
				{
					// Get the DNS and language settings from the POST data (see #4610)
					if ($objCurrentPage->type == 'root')
					{
						$strDomain = $this->Input->post('dns');
						$strLanguage = $this->Input->post('language');
					}
					else
					{
						$strDomain = $domain;
						$strLanguage = $language;
					}
				}
				else
				{
					// Check the domain and language or the domain only
					if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'])
					{
						$arrPages[$domain][$language][] = $objAlias->id;
					}
					else
					{
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
	 * @param DataContainer
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
		$objTotal = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_article WHERE pid=?")
								   ->execute($dc->id);

		if ($objTotal->total > 0)
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
	 * @param DataContainer
	 * @throws Exception
	 */
	public function checkFeedAlias($varValue, DataContainer $dc)
	{
		// No change or empty value
		if ($varValue == $dc->value || $varValue == '')
		{
			return $varValue;
		}

		$varValue = standardize($varValue); // see #5096
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
	 * @param DataContainer
	 * @return mixed
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
	 * @param DataContainer
	 * @return mixed
	 */
	public function checkDns($varValue, DataContainer $dc)
	{
		return str_ireplace(array('http://', 'https://', 'ftp://'), '', $varValue);
	}


	/**
	 * Make sure there is only one fallback per domain (thanks to Andreas Schempp)
	 * @param mixed
	 * @param DataContainer
	 * @return mixed
	 * @throws Exception
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
			$varValue = ($this->Environment->ssl ? 'https://' : 'http://') . $varValue;
		}

		return $varValue;
	}


	/**
	 * Returns all allowed page types as array
	 * @param DataContainer
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
	 * @param DataContainer
	 * @param string
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	public function addIcon($row, $label, DataContainer $dc=null, $imageAttribute='', $blnReturnImage=false, $blnProtected=false)
	{
		$sub = 0;
		$image = ''.$row['type'].'.gif';

		// Page not published or not active
		if ((!$row['published'] || $row['start'] && $row['start'] > time() || $row['stop'] && $row['stop'] < time()))
		{
			$sub += 1;
		}

		// Page hidden from menu
		if ($row['hide'] && !in_array($row['type'], array('redirect', 'forward', 'root', 'error_403', 'error_404')))
		{
			$sub += 2;
		}

		// Page protected
		if (($blnProtected || $row['protected']) && !in_array($row['type'], array('root', 'error_403', 'error_404')))
		{
			$sub += 4;
		}

		// Get the image name
		if ($sub > 0)
		{
			$image = ''.$row['type'].'_'.$sub.'.gif';
		}

		// Return the image only
		if ($blnReturnImage)
		{
			return $this->generateImage($image, '', $imageAttribute);
		}

		// Mark root pages
		if ($row['type'] == 'root' || $this->Input->get('do') == 'article')
		{
			$label = '<strong>' . $label . '</strong>';
		}

		// Add the breadcrumb link
		$label = '<a href="' . $this->addToUrl('node='.$row['id']) . '">' . $label . '</a>';

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
	 * @param DataContainer
	 * @param array
	 * @param string
	 * @param boolean
	 * @param array
	 * @return string
	 */
	public function pastePage(DataContainer $dc, $row, $table, $cr, $arrClipboard=false)
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
		if ($this->Input->get('mode') != 'create' && $row['pid'] == 0)
		{
			$objPage = $this->Database->prepare("SELECT * FROM " . $table . " WHERE id=?")
									  ->limit(1)
									  ->execute($this->Input->get('id'));

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
				if (!$this->User->isAllowed(2, $row) || ($this->Input->get('mode') == 'create' && !$this->User->isAllowed(1, $row)))
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
				if (!$this->User->isAllowed(2, $objPage->row()) || ($this->Input->get('mode') == 'create' && !$this->User->isAllowed(1, $objPage->row())))
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

		// Return the buttons
		$imagePasteAfter = $this->generateImage('pasteafter.gif', sprintf($GLOBALS['TL_LANG'][$table]['pasteafter'][1], $row['id']), 'class="blink"');
		$imagePasteInto = $this->generateImage('pasteinto.gif', sprintf($GLOBALS['TL_LANG'][$table]['pasteinto'][1], $row['id']), 'class="blink"');

		if ($row['id'] > 0)
		{
			$return = $disablePA ? $this->generateImage('pasteafter_.gif', '', 'class="blink"').' ' : '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&amp;mode=1&amp;pid='.$row['id'].(!is_array($arrClipboard['id']) ? '&amp;id='.$arrClipboard['id'] : '')).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$table]['pasteafter'][1], $row['id'])).'" onclick="Backend.getScrollOffset()">'.$imagePasteAfter.'</a> ';
		}

		return $return.($disablePI ? $this->generateImage('pasteinto_.gif', '', 'class="blink"').' ' : '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&amp;mode=2&amp;pid='.$row['id'].(!is_array($arrClipboard['id']) ? '&amp;id='.$arrClipboard['id'] : '')).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$table]['pasteinto'][1], $row['id'])).'" onclick="Backend.getScrollOffset()">'.$imagePasteInto.'</a> ');
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
	 * Recursively add pages to a sitemap
	 * @param DataContainer
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
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
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
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
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

?>