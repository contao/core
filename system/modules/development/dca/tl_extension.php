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
 * @package    Development
 * @license    LGPL
 * @filesource
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
		'enableVersioning'            => true
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
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
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
				'icon'                => 'system/modules/development/html/apply.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('addBeMod', 'addFeMod', 'addLanguage'),
		'default'                     => 'title,folder;author,copyright,package,license;addBeMod;addFeMod;addLanguage'
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
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['title'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>64)
		),
		'folder' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['folder'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>48, 'rgxp'=>'url', 'decodeEntities'=>true, 'nospace'=>true),
			'save_callback' => array
			(
				array('tl_extension', 'checkFolder')
			)
		),
		'author' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['author'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128)
		),
		'copyright' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['copyright'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128)
		),
		'package' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['package'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'nospace'=>true)
		),
		'license' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['license'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'nospace'=>true)
		),
		'addBeMod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['addBeMod'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'beClasses' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['beClasses'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255)
		),
		'beTables' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['beTables'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255)
		),
		'beTemplates' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['beTemplates'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255)
		),
		'addFeMod' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['addFeMod'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'feClasses' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['feClasses'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255)
		),
		'feTables' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['feTables'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255)
		),
		'feTemplates' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['feTemplates'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255)
		),
		'addLanguage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['addLanguage'],
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'languages' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_extension']['languages'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255)
		)
	)
);


/**
 * Class tl_extension
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_extension extends Backend
{

	/**
	 * Check whether a module exists already
	 * @param string
	 * @return string
	 * @throws Exception
	 */
	public function checkFolder($strFolder)
	{
		if (is_dir(TL_ROOT . '/system/modules/' . $strFolder))
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['tl_extension']['unique'], $strFolder));
		}

		return $strFolder;
	}
}

?>