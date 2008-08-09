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
 * Table tl_undo
 */
$GLOBALS['TL_DCA']['tl_undo'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'closed'                      => true,
		'notEditable'                 => true
	),

	// List
	'list'  => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('tstamp DESC'),
			'panelLayout'             => 'sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('tstamp', 'query'),
			'format'                  => '<span style="color:#b3b3b3; padding-right:3px;">[%s]</span>%s',
			'maxCharacters'           => 120
		),
		'operations' => array
		(
			'undo' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_undo']['undo'],
				'href'                => '&amp;act=undo',
				'icon'                => 'undo.gif'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_undo']['show'],
				'href'                => '&amp;act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Fields
	'fields' => array
	(
		'tstamp' => array
		(
			'sorting'                 => true,
			'flag'                    => 6,
			'label'                   => &$GLOBALS['TL_LANG']['tl_undo']['tstamp']
		),
		'fromTable' => array
		(
			'sorting'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_undo']['fromTable']
		),
		'query' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_undo']['query']
		),
		'data' => array
		(
			'search'                  => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_undo']['data']
		),
		'affectedRows' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_undo']['affectedRows']
		)
	)
);

?>