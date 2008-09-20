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
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_content
 */
$GLOBALS['TL_DCA']['tl_content'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_article',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_content', 'checkPermission')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('sorting'),
			'panelLayout'             => 'filter;search,limit',
			'headerFields'            => array('title', 'author', 'inColumn', 'tstamp', 'showTeaser', 'printable', 'published', 'start', 'stop'),
			'child_record_callback'   => array('tl_content', 'addCteType')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_content']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_content']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_content']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_content']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_content', 'deleteElement')
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_content']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_content', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_content']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		),
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('type', 'mooType', 'addImage', 'useImage', 'sortable', 'protected'),
		'default'                     => 'type',
		'headline'                    => 'type;headline;guests,protected;align,space,cssID',
		'text'                        => 'type,headline;text;addImage;guests,protected;align,space,cssID',
		'html'                        => 'type;html;guests,protected',
		'code'                        => 'type,headline;highlight,code;guests,protected;align,space,cssID',
		'image'                       => 'type,headline;singleSRC,alt;imagemargin,size,imageUrl,caption,fullsize;guests,protected;align,space,cssID',
		'hyperlink'                   => 'type,headline;url,linkTitle,embed,target;useImage;guests,protected;align,space,cssID',
		'list'                        => 'type,headline;listitems,listtype;guests,protected;align,space,cssID',
		'table'                       => 'type,headline;tableitems;summary,thead,tfoot;sortable;guests,protected;align,space,cssID',
		'download'                    => 'type,headline;singleSRC,linkTitle;guests,protected;align,space,cssID',
		'downloads'                   => 'type,headline;multiSRC;sortBy,useHomeDir;guests,protected;align,space,cssID',
		'gallery'                     => 'type,headline;multiSRC;imagemargin,size;useHomeDir,fullsize;perRow,sortBy,perPage;guests,protected;align,space,cssID',
		'toplink'                     => 'type;linkTitle;guests,protected;align,space,cssID',
		'accordion'                   => 'type,mooType',
		'accordionsingle'             => 'type,mooType;mooHeadline,mooStyle;text;addImage;mooClasses;guests,protected;align,space,cssID',
		'accordionstart'              => 'type,mooType;mooHeadline,mooStyle;mooClasses;guests,protected;align,space,cssID',
		'accordionstop'               => 'type,mooType;mooClasses;guests,protected',
		'alias'                       => 'type;cteAlias;guests,protected;align,space,cssID',
		'article'                     => 'type;articleAlias;guests,protected',
		'teaser'                      => 'type;article;guests,protected;align,space,cssID',
		'form'                        => 'type,headline;form;guests,protected;align,space,cssID',
		'module'                      => 'type;module;guests,protected;align,space,cssID'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'useImage'                    => 'singleSRC,alt,size,caption',
		'addImage'                    => 'singleSRC,alt,imagemargin,size,imageUrl,caption,floating,fullsize',
		'sortable'                    => 'sortIndex,sortOrder',
		'protected'                   => 'groups'
	),

	// Fields
	'fields' => array
	(
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['type'],
			'default'                 => 'headline',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_content', 'getContentElements'),
			'reference'               => &$GLOBALS['TL_LANG']['CTE'],
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true)
		),
		'headline' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['headline'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'inputUnit',
			'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
			'eval'                    => array('maxlength'=>255)
		),
		'mooType' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['mooType'],
			'default'                 => 'single',
			'exclude'                 => true,
			'inputType'               => 'radio',
			'options'                 => array('single', 'start', 'stop'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_content'],
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true)
		),
		'mooHeadline' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['mooHeadline'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'allowHtml'=>true)
		),
		'mooStyle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['mooStyle'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'decodeEntities'=>true)
		),
		'mooClasses' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['mooClasses'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'alnum')
		),
		'text' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['text'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'rte'=>'tinyMCE', 'helpwizard'=>true),
			'explanation'             => 'insertTags'
		),
		'html' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['html'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'allowHtml'=>true, 'class'=>'monospace')
		),
		'code' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['code'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'preserveTags'=>true, 'decodeEntities'=>true, 'class'=>'monospace')
		),
		'highlight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['highlight'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('C', 'CSharp', 'CSS', 'Delphi', 'HTML', 'Java', 'JavaScript', 'PHP', 'Python', 'Ruby', 'SQL', 'VB', 'XML'),
			'eval'                    => array('includeBlankOption'=>true)
		),
		'addImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['addImage'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'useImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['useImage'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'mandatory'=>true)
		),
		'multiSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['multiSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'mandatory'=>true)
		),
		'useHomeDir' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['useHomeDir'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'sortBy' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['sortBy'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('name_asc', 'name_desc', 'date_asc', 'date_desc', 'meta'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_content']
		),
		'size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['size'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true),
			'save_callback' => array
			(
				array('tl_content', 'limitImageWidth')
			)
		),
		'alt' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['alt'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'caption' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['caption'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255)
		),
		'floating' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['floating'],
			'exclude'                 => true,
			'inputType'               => 'radioTable',
			'options'                 => array('above', 'left', 'right', 'below'),
			'eval'                    => array('cols'=>4),
			'reference'               => &$GLOBALS['TL_LANG']['MSC']
		),
		'imagemargin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['imagemargin'],
			'exclude'                 => true,
			'inputType'               => 'trbl',
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true)
		),
		'fullsize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fullsize'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'perRow' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['perRow'],
			'default'                 => 4,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12)
		),
		'perPage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['perPage'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit')
		),
		'listtype' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['listtype'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('ordered', 'unordered'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_content']
		),
		'listitems' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['listitems'],
			'exclude'                 => true,
			'inputType'               => 'listWizard',
			'eval'                    => array('allowHtml'=>true)
		),
		'tableitems' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['tableitems'],
			'exclude'                 => true,
			'inputType'               => 'tableWizard',
			'eval'                    => array('allowHtml'=>true, 'doNotSaveEmpty'=>true, 'style'=>'width:142px; height:66px;')
		),
		'summary' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['summary'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'thead' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['thead'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'tfoot' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['tfoot'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'sortable' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['sortable'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'sortIndex' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['sortIndex'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit'),
		),
		'sortOrder' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['sortOrder'],
			'default'                 => 'ascending',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('ascending', 'descending'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_content'],
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['url'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255),
			'wizard' => array
			(
				array('tl_content', 'pagePicker')
			)
		),
		'imageUrl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['imageUrl'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255),
			'wizard' => array
			(
				array('tl_content', 'pagePicker')
			)
		),
		'linkTitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['linkTitle'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255)
		),
		'embed' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['embed'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255)
		),
		'target' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['MSC']['target'],
			'exclude'                 => true,
			'inputType'               => 'checkbox'
		),
		'cteAlias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['cteAlias'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_content', 'getAlias'),
			'eval'                    => array('mandatory'=>true, 'submitOnChange'=>true),
			'wizard' => array
			(
				array('tl_content', 'editAlias')
			)
		),
		'articleAlias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['articleAlias'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_content', 'getArticleAlias'),
			'eval'                    => array('mandatory'=>true, 'submitOnChange'=>true),
			'wizard' => array
			(
				array('tl_content', 'editArticleAlias')
			)
		),
		'article' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['article'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_content', 'getArticles'),
			'eval'                    => array('mandatory'=>true, 'submitOnChange'=>true),
			'wizard' => array
			(
				array('tl_content', 'editArticle')
			)
		),
		'form' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['form'],
			'exclude'                 => true,
			'inputType'               => 'radio',
			'foreignKey'              => 'tl_form.title',
			'eval'                    => array('mandatory'=>true)
		),
		'module' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['module'],
			'exclude'                 => true,
			'inputType'               => 'radio',
			'foreignKey'              => 'tl_module.name',
			'eval'                    => array('mandatory'=>true)
		),
		'invisible' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['invisible']
		),
		'protected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['protected'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'guests' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['guests'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox'
		),
		'groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('multiple'=>true)
		),
		'space' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['space'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true)
		),
		'align' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['align'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('left', 'center', 'right', 'justify'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('includeBlankOption'=>true)
		),
		'cssID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['cssID'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2)
		),
		'source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_content']['source'],
			'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'extensions'=>'csv')
		)
	)
);


/**
 * Class tl_content
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_content extends Backend
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
	 * Check permissions to edit table tl_content
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		$groups = $this->User->groups;

		// Set default user and group
		$GLOBALS['TL_DCA']['tl_page']['fields']['cuser']['default'] = strlen($GLOBALS['TL_CONFIG']['cuser']) ? $GLOBALS['TL_CONFIG']['cuser'] : $this->User->id;
		$GLOBALS['TL_DCA']['tl_page']['fields']['cgroup']['default'] = strlen($GLOBALS['TL_CONFIG']['cgroup']) ? $GLOBALS['TL_CONFIG']['cgroup'] : $groups[0];

		// Get pagemounts
		$pagemounts = array();

		foreach ($this->User->pagemounts as $root)
		{
			$pagemounts[] = $root;
			$pagemounts = array_merge($pagemounts, $this->getChildRecords($root, 'tl_page'));
		}

		$pagemounts = array_unique($pagemounts);

		// View an article
		if (!strlen($this->Input->get('act')) || $this->Input->get('act') == 'create')
		{
			$objArticle = $this->Database->prepare("SELECT p.id, p.pid, p.includeChmod, p.chmod, p.cuser, p.cgroup FROM tl_article a, tl_page p WHERE a.id=? AND a.pid=p.id")
										 ->limit(1)
										 ->execute($this->Input->get('id'));

			// Check whether the page is mounted
			if (!in_array($objArticle->id, $pagemounts))
			{
				$this->log('Page ID ' . $objArticle->id . ' was not mounted', 'tl_content checkPermission()', TL_ERROR);
				$this->redirect('typolight/main.php?act=error');
			}

			// Check whether the article is allowed
			if (!$this->User->isAllowed(4, $objArticle->row()))
			{
				$this->log('Not enough permissions to edit article ID ' . $this->Input->get('id') . ' on page ID ' . $objArticle->id, 'tl_content checkPermission()', TL_ERROR);
				$this->redirect('typolight/main.php?act=error');
			}
		}

		// Edit/move/delete a content element
		elseif ($this->Input->get('act') != 'paste')
		{
			// Check parent element if a content element is being moved
			if ($this->Input->get('act') == 'copy')
			{
				// Check content element
				$objArticle = $this->Database->prepare("SELECT p.id, p.pid, p.includeChmod, p.chmod, p.cuser, p.cgroup, a.id AS aid FROM tl_content c, tl_article a, tl_page p WHERE c.id=? AND c.pid=a.id AND a.pid=p.id")
											 ->limit(1)
											 ->execute($this->Input->get('pid'));

				// Check whether the page is mounted
				if (!in_array($objArticle->id, $pagemounts))
				{
					$this->log('Parent page ID ' . $objArticle->id . ' was not mounted', 'tl_content checkPermission()', TL_ERROR);
					$this->redirect('typolight/main.php?act=error');
				}

				// Check whether the article is allowed
				if (!$this->User->isAllowed(4, $objArticle->row()))
				{
					$this->log('Not enough permissions to copy or move content element ID ' . $this->Input->get('pid') . ' of article ID ' . $objArticle->aid . ' on page ID ' . $objArticle->id, 'tl_content checkPermission()', TL_ERROR);
					$this->redirect('typolight/main.php?act=error');
				}
			}

			// Check content element
			$objArticle = $this->Database->prepare("SELECT p.id, p.pid, p.includeChmod, p.chmod, p.cuser, p.cgroup, a.id AS aid FROM tl_content c, tl_article a, tl_page p WHERE c.id=? AND c.pid=a.id AND a.pid=p.id")
										 ->limit(1)
										 ->execute($this->Input->get('id'));

			// Check whether the page is mounted
			if (!in_array($objArticle->id, $pagemounts))
			{
				$this->log('Page ID ' . $objArticle->id . ' was not mounted', 'tl_content checkPermission()', TL_ERROR);
				$this->redirect('typolight/main.php?act=error');
			}

			// Check whether the article is allowed
			if (!$this->User->isAllowed(4, $objArticle->row()))
			{
				$this->log('Not enough permissions to edit content element ID ' . $this->Input->get('id') . ' of article ID ' . $objArticle->aid . ' on page ID ' . $objArticle->id, 'tl_content checkPermission()', TL_ERROR);
				$this->redirect('typolight/main.php?act=error');
			}
		}
	}


	/**
	 * Check access to a particular content element
	 * @param integer
	 * @return boolean
	 */
	private function checkAccessToElement($id)
	{
		// Get article
		$objArticle = $this->Database->prepare("SELECT c.id AS contentId, a.id AS articleId, p.id AS pageId, p.pid, p.includeChmod, p.chmod, p.cuser, p.cgroup FROM tl_article a, tl_page p, tl_content c WHERE c.id=? AND c.pid=a.id AND a.pid=p.id")
									 ->limit(1)
									 ->execute($id);

		// Check whether page is mounted
		if ($objArticle->numRows < 1)
		{
			$this->log('Invalid content element ID ' . $id, 'tl_content checkAccessToElement()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		$pagemounts = array();

		foreach ($this->User->pagemounts as $root)
		{
			$pagemounts[] = $root;
			$pagemounts = array_merge($pagemounts, $this->getChildRecords($root, 'tl_page'));
		}

		$pagemounts = array_unique($pagemounts);

		// Page is not mounted
		if (!in_array($objArticle->pageId, $pagemounts))
		{
			$this->log('Not enough permissions to modify article ID ' . $objArticle->articleId . ' on page ID ' . $objArticle->pageId, 'tl_content checkAccessToElement()', TL_ERROR);
			return false;
		}

		// Not enough permissions to modify article
		if (!$this->User->isAllowed(4, $objArticle->row()))
		{
			$this->log('Not enough permissions to modify article ID ' . $objArticle->articleId, 'tl_content checkAccessToElement()', TL_ERROR);
			return false;
		}

		return true;
	}


	/**
	 * Return all content elements as array
	 * @return array
	 */
	public function getContentElements()
	{
		$groups = array();

		foreach ($GLOBALS['TL_CTE'] as $k=>$v)
		{
			foreach (array_keys($v) as $kk)
			{
				$groups[$k][] = $kk;
			}
		}

		return $groups;
	}


	/**
	 * Calculate the maximum image width and adjust the current width if necessary
	 * @param mixed
	 * @param object
	 * @return mixed
	 */
	public function limitImageWidth($varValue, DataContainer $dc)
	{
		if (!strlen($GLOBALS['TL_CONFIG']['maxImageWidth']) || $GLOBALS['TL_CONFIG']['maxImageWidth'] < 1)
		{
			return $varValue;
		}

		$arrValue = deserialize($varValue);
		$intMaxWidth = intval($GLOBALS['TL_CONFIG']['maxImageWidth']);

		$objType = $this->Database->prepare("SELECT type, perRow, imagemargin FROM tl_content WHERE id=?")
								  ->limit(1)
								  ->execute($dc->id);

		// Calculate image width if it is an image gallery
		if ($objType->numRows)
		{
			switch ($objType->type)
			{
				case 'text':
					$arrMargin = deserialize($objType->imagemargin);
					if ($arrMargin['unit'] == 'px')
					{
						$intMaxWidth = $intMaxWidth - $arrMargin['left'] - $arrMargin['right'];
					}
					break;

				case 'gallery':
					$arrMargin = deserialize($objType->imagemargin);
					if ($arrMargin['unit'] == 'px')
					{
						$intMaxWidth = $intMaxWidth - ($objType->perRow * ($arrMargin['left'] + $arrMargin['right']));
					}
					$intMaxWidth = floor($intMaxWidth / $objType->perRow);
					break;
			}
		}

		// Adjust width if image is too wide
		if ($intMaxWidth > 0 && $arrValue[0] > $intMaxWidth)
		{
			$arrValue[0] = $intMaxWidth;
		}
		
		return serialize($arrValue);
	}


	/**
	 * Add the type of content element
	 * @param array
	 * @return string
	 */
	public function addCteType($arrRow)
	{
		return '
<div class="cte_type">' . $GLOBALS['TL_LANG']['CTE'][$arrRow['type']][0] . '</div>
<div class="limit_height' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h64' : '') . ' block">
' . $this->getContentElement($arrRow['id']) . '
</div>' . "\n";
	}


	/**
	 * Return the edit alias wizard
	 * @param object
	 * @return string
	 */
	public function editAlias(DataContainer $dc)
	{
		return ($dc->value < 1) ? '' : ' <a href="'.preg_replace('/id=[0-9]+/', 'id=' . $dc->value, ampersand($this->Environment->request)).'" title="'.sprintf(specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $dc->value).'">' . $this->generateImage('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top;"') . '</a>';
	}


	/**
	 * Get all content elements and return them as array
	 * @return array
	 */
	public function getAlias()
	{
		$arrAlias = array();

		$objAlias = $this->Database->prepare("SELECT id, type, headline, text, (SELECT title FROM tl_article WHERE tl_article.id=tl_content.pid) AS title FROM tl_content WHERE id!=? ORDER BY title, sorting")
								   ->execute($this->Input->get('id'));

		while ($objAlias->next())
		{
			$arrHeadline = deserialize($objAlias->headline, true);

			$headline_length = strlen($arrHeadline[0]);
			$headline = $headline_length ? ' (' . substr(preg_replace('/[\n\r\t]+/', ' ', $arrHeadline[0]), 0, 32) . (($headline_length > 32) ? ' …' : '') . ')' : '';

			$text_length = strlen($objAlias->text);
			$text = $text_length ? ' (' . substr(strip_tags(preg_replace('/[\n\r\t]+/', ' ', $objAlias->text)), 0, 32) . (($text_length > 32) ? ' …' : '') . ')' : '';

			$arrAlias[$objAlias->title][$objAlias->id] = $objAlias->id . ' - ' . $GLOBALS['TL_LANG']['CTE'][$objAlias->type][0] . (strlen($headline) ? $headline : $text);
		}

		return $arrAlias;
	}


	/**
	 * Return the edit alias wizard
	 * @param object
	 * @return string
	 */
	public function editArticleAlias(DataContainer $dc)
	{
		return ($dc->value < 1) ? '' : ' <a href="typolight/main.php?do=article&amp;table=tl_article&amp;act=edit&amp;id=' . $dc->value . '" title="'.sprintf(specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $dc->value).'">' . $this->generateImage('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top;"') . '</a>';
	}


	/**
	 * Get all articles and return them as array
	 * @param object
	 * @return array
	 */
	public function getArticleAlias(DataContainer $dc)
	{
		$arrAlias = array();
		$this->loadLanguageFile('tl_article');

		$objAlias = $this->Database->prepare("SELECT id, title, inColumn, (SELECT title FROM tl_page WHERE tl_page.id=tl_article.pid) AS parent FROM tl_article WHERE id!=(SELECT pid FROM tl_content WHERE id=?) ORDER BY parent, sorting")
								   ->execute($dc->id);

		while ($objAlias->next())
		{
			$arrAlias[$objAlias->parent][$objAlias->id] = $objAlias->id . ' - ' . $objAlias->title . ' (' . (strlen($GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn]) ? $GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn] : $objAlias->inColumn) . ')';
		}

		return $arrAlias;
	}


	/**
	 * Get all articles and return them as array
	 * @return array
	 */
	public function getArticles()
	{
		$arrArticle = array();
		$objArticle = $this->Database->execute("SELECT id, title, (SELECT title FROM tl_page WHERE tl_article.pid=tl_page.id) AS parent FROM tl_article ORDER BY parent, sorting");

		while ($objArticle->next())
		{
			$arrArticle[$objArticle->parent][$objArticle->id] = $objArticle->id . ' - ' . $objArticle->title;
		}

		return $arrArticle;
	}


	/**
	 * Return the edit article wizard
	 * @param object
	 * @return string
	 */
	public function editArticle(DataContainer $dc)
	{
		return ($dc->value < 1) ? '' : ' <a href="typolight/main.php?do=article&amp;table=tl_content&amp;id=' . $dc->value . '" title="'.sprintf(specialchars($GLOBALS['TL_LANG']['tl_content']['editarticle'][1]), $dc->value).'">' . $this->generateImage('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editarticle'][0], 'style="vertical-align:top;"') . '</a>';
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
			$this->Database->prepare("UPDATE tl_content SET invisible='" . (strlen($this->Input->get('state')) ? '' : 1) . "' WHERE id=?")
						   ->execute($this->Input->get('tid'));

			$this->redirect($this->getReferer());
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.$row['invisible'];

		if ($row['invisible'])
		{
			$icon = 'invisible.gif';
		}		

		return '<a href="'.$this->addToUrl($href.'&amp;id='.$this->Input->get('id')).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Return the link picker wizard
	 * @param object
	 * @return string
	 */
	public function pagePicker(DataContainer $dc)
	{
		$strField = 'ctrl_' . $dc->field . (($this->Input->get('act') == 'editAll') ? '_' . $dc->id : '');
		return ' ' . $this->generateImage('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top; cursor:pointer;" onclick="Backend.pickPage(\'' . $strField . '\')"');
	}


	/**
	 * Return the delete content element button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteElement($row, $href, $label, $title, $icon, $attributes)
	{
		$objElement = $this->Database->prepare("SELECT id FROM tl_content WHERE cteAlias=? AND type=?")
									 ->limit(1)
									 ->execute($row['id'], 'alias');

		return $objElement->numRows ? $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)) . ' ' : '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}
}

?>