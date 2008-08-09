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
 * Table tl_log
 */
$GLOBALS['TL_DCA']['tl_log'] = array
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
			'panelLayout'             => 'filter;sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('tstamp', 'text'),
			'format'                  => '<span style="color:#b3b3b3; padding-right:3px;">[%s]</span> %s',
			'maxCharacters'           => 146
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
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_log']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_log']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Fields
	'fields' => array
	(
		'tstamp' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_log']['tstamp'],
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 6
		),
		'source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_log']['source'],
			'filter'                  => true,
			'sorting'                 => true,
			'reference'               => &$GLOBALS['TL_LANG']['tl_log']
		),
		'action' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_log']['action'],
			'filter'                  => true,
			'sorting'                 => true
		),
		'username' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_log']['username'],
			'search'                  => true,
			'filter'                  => true,
			'sorting'                 => true
		),
		'text' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_log']['text'],
			'search'                  => true
		),
		'func' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_log']['func'],
			'sorting'                 => true,
			'filter'                  => true,
			'search'                  => true
		),
		'ip' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_log']['ip'],
			'sorting'                 => true,
			'filter'                  => true,
			'search'                  => true
		),
		'browser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_log']['browser'],
			'sorting'                 => true,
			'search'                  => true
		)
	)
);

?>