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
 * Table tl_style_sheet
 */
$GLOBALS['TL_DCA']['tl_style_sheet'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_style'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_style_sheet', 'updateStyleSheet')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('name'),
			'flag'                    => 1,
			'panelLayout'             => 'search,filter,limit',
		),
		'label' => array
		(
			'fields'                  => array('name', 'media'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>'
		),
		'global_operations' => array
		(
			'import' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style_sheet']['import'],
				'href'                => 'key=import',
				'class'               => 'header_css_import',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
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
				'label'               => &$GLOBALS['TL_LANG']['tl_style_sheet']['edit'],
				'href'                => 'table=tl_style',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style_sheet']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style_sheet']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_style_sheet']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'name,media'
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style_sheet']['name'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'unique'=>true, 'maxlength'=>64, 'spaceToUnderscore'=>true)
		),
		'media' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style_sheet']['media'],
			'inputType'               => 'checkbox',
			'exclude'                 => true,
			'filter'                  => true,
			'options'                 => array('all', 'aural', 'braille', 'embossed', 'handheld', 'print', 'projection', 'screen', 'tty', 'tv'),
			'eval'                    => array('multiple'=>true, 'mandatory'=>true)
		),
		'source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_style_sheet']['source'],
			'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'filesOnly'=>true, 'extensions'=>'css')
		)
	)
);


/**
 * Class tl_style_sheet
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Controller
 */
class tl_style_sheet extends Backend
{

	/**
	 * Update style sheet
	 * @param object
	 */
	public function updateStyleSheet(DataContainer $dc)
	{
		if (!$dc->id)
		{
			return;
		}

		$this->import('StyleSheets');
		$this->StyleSheets->updateStyleSheet($dc->id);
	}
}

?>