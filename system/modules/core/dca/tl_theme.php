<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Table tl_theme
 */
$GLOBALS['TL_DCA']['tl_theme'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_module', 'tl_style_sheet', 'tl_layout'),
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		),
		'onload_callback' => array
		(
			array('tl_theme', 'updateStyleSheet')
		),
		'oncopy_callback' => array
		(
			array('tl_theme', 'scheduleUpdate')
		),
		'onsubmit_callback' => array
		(
			array('tl_theme', 'scheduleUpdate')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('name'),
			'flag'                    => 1,
			'panelLayout'             => 'sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('name'),
			'format'                  => '%s',
			'label_callback'          => array('tl_theme', 'addPreviewImage')
		),
		'global_operations' => array
		(
			'importTheme' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_theme']['importTheme'],
				'href'                => 'key=importTheme',
				'class'               => 'header_theme_import',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
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
				'label'               => &$GLOBALS['TL_LANG']['tl_theme']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_theme']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_theme']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif',
				'attributes'          => 'style="margin-right:3px"'
			),
			'css' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_theme']['css'],
				'href'                => 'table=tl_style_sheet',
				'icon'                => 'css.gif',
				'button_callback'     => array('tl_theme', 'editCss')
			),
			'modules' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_theme']['modules'],
				'href'                => 'table=tl_module',
				'icon'                => 'modules.gif',
				'button_callback'     => array('tl_theme', 'editModules')
			),
			'layout' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_theme']['layout'],
				'href'                => 'table=tl_layout',
				'icon'                => 'layout.gif',
				'button_callback'     => array('tl_theme', 'editLayout')
			),
			'exportTheme' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_theme']['exportTheme'],
				'href'                => 'key=exportTheme',
				'icon'                => 'theme_export.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{title_legend},name,author;{config_legend},folders,screenshot,templates;{vars_legend},vars'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_theme']['name'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'unique'=>true, 'decodeEntities'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_theme']['author'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => true,
			'flag'                    => 11,
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'folders' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_theme']['folders'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('multiple'=>true, 'fieldType'=>'checkbox'),
			'sql'                     => "blob NULL"
		),
		'screenshot' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_theme']['screenshot'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'filesOnly'=>true),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'templates' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_theme']['templates'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_theme', 'getTemplateFolders'),
			'eval'                    => array('includeBlankOption'=>true),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'vars' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_theme']['vars'],
			'inputType'               => 'keyValueWizard',
			'exclude'                 => true,
			'sql'                     => "text NULL"
		)
	)
);


/**
 * Class tl_theme
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class tl_theme extends Backend
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
	 * Add an image to each record
	 * @param array
	 * @param string
	 * @return string
	 */
	public function addPreviewImage($row, $label)
	{
		if ($row['screenshot'] != '')
		{
			$objFile = FilesModel::findByPk($row['screenshot']);

			if ($objFile !== null)
			{
				$label = '<img src="' . TL_FILES_URL . Image::get($objFile->path, 160, 120, 'center_top') . '" width="160" height="120" alt="" class="theme_preview">' . $label;
			}
		}

		return $label;
	}


	/**
	 * Check for modified style sheets and update them if necessary
	 */
	public function updateStyleSheet()
	{
		if ($this->Session->get('style_sheet_update_all'))
		{
			$this->import('StyleSheets');
			$this->StyleSheets->updateStyleSheets();
		}

		$this->Session->set('style_sheet_update_all', null);
	}


	/**
	 * Schedule a style sheet update
	 * 
	 * This method is triggered when a single theme or multiple themes are
	 * modified (edit/editAll) or duplicated (copy/copyAll).
	 */
	public function scheduleUpdate()
	{
		$this->Session->set('style_sheet_update_all', true);
	}


	/**
	 * Return all template folders as array
	 * @return array
	 */
	public function getTemplateFolders()
	{
		return $this->doGetTemplateFolders('templates');
	}


	/**
	 * Return all template folders as array
	 * @param string
	 * @param integer
	 * @return array
	 */
	protected function doGetTemplateFolders($path, $level=0)
	{
		$return = array();

		foreach (scan(TL_ROOT . '/' . $path) as $file)
		{
			if (is_dir(TL_ROOT . '/' . $path . '/' . $file))
			{
				$return[$path . '/' . $file] = str_repeat(' &nbsp; &nbsp; ', $level) . $file;
				$return = array_merge($return, $this->doGetTemplateFolders($path . '/' . $file, $level+1));
			}
		}

		return $return;
	}


	/**
	 * Return the edit CSS button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editCss($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('css', 'themes')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the edit modules button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editModules($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('modules', 'themes')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the edit page layouts button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editLayout($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('layout', 'themes')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
}
