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
 * Table tl_module
 */
$GLOBALS['TL_DCA']['tl_module'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_theme',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_module', 'checkPermission')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('name'),
			'panelLayout'             => 'filter;sort,search,limit',
			'headerFields'            => array('name', 'author', 'tstamp'),
			'child_record_callback'   => array('tl_module', 'listModule'),
			'child_record_class'      => 'no_padding'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_module']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_module']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_module']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_module']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_module']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('type', 'defineRoot', 'source', 'interactive', 'protected'),
		'default'                     => '{title_legend},name,type',
		'navigation'                  => '{title_legend},name,headline,type;{nav_legend},levelOffset,showLevel,hardLimit,showProtected;{reference_legend:hide},defineRoot;{template_legend:hide},navigationTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'customnav'                   => '{title_legend},name,headline,type;{nav_legend},showProtected,pages;{template_legend:hide},navigationTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'breadcrumb'                  => '{title_legend},name,headline,type;{nav_legend},includeRoot,showHidden;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'quicknav'                    => '{title_legend},name,headline,type;{nav_legend},customLabel,showLevel,hardLimit,includeRoot,showProtected,showHidden;{reference_legend:hide},rootPage;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'quicklink'                   => '{title_legend},name,headline,type;{nav_legend},customLabel,pages;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'booknav'                     => '{title_legend},name,headline,type;{nav_legend},showProtected,showHidden,rootPage;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'articlenav'                  => '{title_legend},name,headline,type;{config_legend},loadFirst;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'sitemap'                     => '{title_legend},name,headline,type;{nav_legend},includeRoot,showProtected,showHidden;{reference_legend:hide},rootPage;{template_legend:hide},navigationTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'login'                       => '{title_legend},name,headline,type;{config_legend},autologin;{redirect_legend},jumpTo,redirectBack;{template_legend:hide},cols;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'logout'                      => '{title_legend},name,headline,type;{redirect_legend},jumpTo,redirectBack;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'personalData'                => '{title_legend},name,headline,type;{config_legend},editable;{redirect_legend},jumpTo;{template_legend:hide},memberTpl,tableless;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'form'                        => '{title_legend},name,headline,type;{include_legend},form;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'search'                      => '{title_legend},name,headline,type;{config_legend},queryType,fuzzy,contextLength,totalLength,perPage,searchType;{redirect_legend:hide},jumpTo;{reference_legend:hide},rootPage;{template_legend:hide},searchTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'articleList'                 => '{title_legend},name,headline,type;{config_legend},skipFirst,inColumn;{reference_legend:hide},defineRoot;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'flash'                       => '{title_legend},name,headline,type;{config_legend},size,transparent,flashvars,altContent;{source_legend},source;{interact_legend:hide},interactive;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'randomImage'                 => '{title_legend},name,headline,type;{config_legend},imgSize,useCaption,fullsize;{source_legend},multiSRC;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space',
		'html'                        => '{title_legend},name,type;{html_legend},html;{protected_legend:hide},protected;{expert_legend:hide},guests'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'defineRoot'                  => 'rootPage',
		'source_internal'             => 'singleSRC',
		'source_external'             => 'url',
		'interactive'                 => 'flashID,flashJS',
		'protected'                   => 'groups'
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['name'],
			'exclude'                 => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'headline' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['headline'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'inputUnit',
			'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50')
		),
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['type'],
			'default'                 => 'navigation',
			'exclude'                 => true,
			'sorting'                 => true,
			'flag'                    => 11,
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_module', 'getModules'),
			'reference'               => &$GLOBALS['TL_LANG']['FMD'],
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50')
		),
		'levelOffset' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['levelOffset'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>5, 'rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'showLevel' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['showLevel'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>5, 'rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'hardLimit' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hardLimit'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'showProtected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['showProtected'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'defineRoot' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['defineRoot'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'rootPage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['rootPage'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array('fieldType'=>'radio', 'tl_class'=>'clr')
		),
		'navigationTpl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['navigationTpl'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_module', 'getNavigationTemplates')
		),
		'pages' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['pages'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'mandatory'=>true, 'tl_class'=>'clr')
		),
		'includeRoot' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['includeRoot'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'showHidden' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['showHidden'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'customLabel' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['customLabel'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64, 'rgxp'=>'extnd', 'tl_class'=>'w50')
		),
		'autologin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['autologin'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array('fieldType'=>'radio')
		),
		'redirectBack' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['redirectBack'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'cols' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cols'],
			'default'                 => '2cl',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('1cl', '2cl'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
			'eval'                    => array('helpwizard'=>true)
		),
		'editable' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['editable'],
			'exclude'                 => true,
			'inputType'               => 'checkboxWizard',
			'options_callback'        => array('tl_module', 'getEditableMemberProperties'),
			'eval'                    => array('multiple'=>true)
		),
		'memberTpl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['memberTpl'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_module', 'getMemberTemplates'),
			'eval'                    => array('tl_class'=>'w50')
		),
		'tableless' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tableless'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
		),
		'form' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['form'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_module', 'getForms')
		),
		'queryType' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['queryType'],
			'default'                 => 'and',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('and', 'or'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
			'eval'                    => array('helpwizard'=>true, 'tl_class'=>'w50')
		),
		'fuzzy' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['fuzzy'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
		),
		'contextLength' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['contextLength'],
			'default'                 => 48,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'totalLength' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['totalLength'],
			'default'                 => 1000,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'perPage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['perPage'],
			'default'                 => 0,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'searchType' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['searchType'],
			'default'                 => 'simple',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('simple', 'advanced'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
			'eval'                    => array('helpwizard'=>true, 'tl_class'=>'w50')
		),
		'searchTpl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['searchTpl'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_module', 'getSearchTemplates'),
			'eval'                    => array('tl_class'=>'w50')
		),
		'inColumn' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['inColumn'],
			'default'                 => 'main',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => $this->getPageSections(),
			'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
			'eval'                    => array('tl_class'=>'w50')
		),
		'skipFirst' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['skipFirst'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'loadFirst' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['loadFirst'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['size'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'transparent' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['transparent'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12')
		),
		'flashvars' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['flashvars'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('nospace'=>true, 'maxlength'=>255, 'tl_class'=>'long clr')
		),
		'altContent' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['altContent'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'allowHtml'=>true, 'style'=>'height:60px;', 'tl_class'=>'clr')
		),
		'source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['source'],
			'default'                 => 'internal',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('internal', 'external'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
			'eval'                    => array('submitOnChange'=>true)
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'mandatory'=>true, 'tl_class'=>'clr')
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['url'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255)
		),
		'interactive' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['interactive'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'flashID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['flashID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'extnd', 'nospace'=>true, 'unique'=>true, 'maxlength'=>64)
		),
		'flashJS' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['flashJS'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('class'=>'monospace')
		),
		'imgSize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['imgSize'],
			'exclude'                 => true,
			'inputType'               => 'imageSize',
			'options'                 => array('crop', 'proportional', 'box'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'useCaption' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['useCaption'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 clr')
		),
		'fullsize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['fullsize'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'multiSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['multiSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'mandatory'=>true)
		),
		'html' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['html'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('allowHtml'=>true, 'class'=>'monospace', 'rte'=>'codeMirror|html', 'helpwizard'=>true),
			'explanation'             => 'insertTags'
		),
		'protected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['protected'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true)
		),
		'guests' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['guests'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox'
		),
		'cssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cssID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50')
		),
		'space' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_module']['space'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		)
	)
);


/**
 * Class tl_module
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_module extends Backend
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
	 * Check permissions to edit the table
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		if (!$this->User->hasAccess('modules', 'themes'))
		{
			$this->log('Not enough permissions to access the modules module', 'tl_module checkPermission', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	}


	/**
	 * Return all front end modules as array
	 * @return array
	 */
	public function getModules()
	{
		$groups = array();

		foreach ($GLOBALS['FE_MOD'] as $k=>$v)
		{
			foreach (array_keys($v) as $kk)
			{
				$groups[$k][] = $kk;
			}
		}

		return $groups;
	}


	/**
	 * Return all editable fields of table tl_member
	 * @return array
	 */
	public function getEditableMemberProperties()
	{
		$return = array();

		$this->loadLanguageFile('tl_member');
		$this->loadDataContainer('tl_member');

		foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $k=>$v)
		{
			if ($v['eval']['feEditable'])
			{
				$return[$k] = $GLOBALS['TL_DCA']['tl_member']['fields'][$k]['label'][0];
			}
		}

		return $return;
	}


	/**
	 * Get all forms and return them as array
	 * @return array
	 */
	public function getForms()
	{
		if (!$this->User->isAdmin && !is_array($this->User->forms))
		{
			return array();
		}

		$arrForms = array();
		$objForms = $this->Database->execute("SELECT id, title FROM tl_form ORDER BY title");

		while ($objForms->next())
		{
			if ($this->User->isAdmin || $this->User->hasAccess($objForms->id, 'forms'))
			{
				$arrForms[$objForms->id] = $objForms->title;
			}
		}

		return $arrForms;
	}


	/**
	 * Return all navigation templates as array
	 * @param object
	 * @return array
	 */
	public function getNavigationTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('nav_', $intPid);
	}


	/**
	 * Return all member templates as array
	 * @param object
	 * @return array
	 */
	public function getMemberTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('member_', $intPid);
	}


	/**
	 * Return all search templates as array
	 * @param object
	 * @return array
	 */
	public function getSearchTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('search_', $intPid);
	}


	/**
	 * List a front end module
	 * @param array
	 * @return string
	 */
	public function listModule($row)
	{
		return '<div style="float:left;">'. $row['name'] .' <span style="color:#b3b3b3; padding-left:3px;">['. (isset($GLOBALS['TL_LANG']['FMD'][$row['type']][0]) ? $GLOBALS['TL_LANG']['FMD'][$row['type']][0] : $row['type']) .']</span>' . "</div>\n";
	}
}

?>