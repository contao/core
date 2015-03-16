<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Table tl_extension
 */
$GLOBALS['TL_DCA']['tl_extension'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('title'),
			'flag'                    => 1,
			'panelLayout'             => 'search,limit'
		),
		'label' => array
		(
			'fields'                  => array('title', 'folder'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>'
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
				'label'               => &$GLOBALS['TL_LANG']['tl_extension']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_extension']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_extension']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_extension']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'create' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_extension']['create'],
				'href'                => 'key=create',
				'icon'                => 'system/modules/devtools/assets/apply.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('addBeMod', 'addFeMod', 'addLanguage'),
		'default'                     => '{title_legend},title,folder;{license_legend},author,copyright,package,license;{backend_legend},addBeMod;{frontend_legend},addFeMod;{language_legend},addLanguage'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addBeMod'                    => 'beClasses,beTables,beTemplates',
		'addFeMod'                    => 'feClasses,feTables,feTemplates',
		'addLanguage'                 => 'languages',
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
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['title'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'folder' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['folder'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>48, 'decodeEntities'=>true, 'nospace'=>true, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_extension', 'checkFolder')
			),
			'sql'                     => "varchar(48) NOT NULL default ''"
		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['author'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'copyright' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['copyright'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'package' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['package'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'nospace'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'license' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['license'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'nospace'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'addBeMod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['addBeMod'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'beClasses' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['beClasses'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'beTables' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['beTables'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'beTemplates' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['beTemplates'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'addFeMod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['addFeMod'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'feClasses' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['feClasses'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'feTables' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['feTables'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'feTemplates' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['feTemplates'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'addLanguage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['addLanguage'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'languages' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['languages'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		)
	)
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class tl_extension extends Backend
{

	/**
	 * Check whether a module exists already
	 *
	 * @param string        $strFolder
	 * @param DataContainer $dc
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function checkFolder($strFolder, DataContainer $dc)
	{
		if ($strFolder != $dc->activeRecord->folder && is_dir(TL_ROOT . '/system/modules/' . $strFolder))
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['tl_extension']['unique'], $strFolder));
		}

		return $strFolder;
	}
}
