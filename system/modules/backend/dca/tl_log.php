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
			'fields'                  => array('tstamp DESC', 'id DESC'),
			'panelLayout'             => 'filter;sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('tstamp', 'text'),
			'format'                  => '<span style="color:#b3b3b3;padding-right:3px">[%s]</span> %s',
			'maxCharacters'           => 96,
			'label_callback'          => array('tl_log', 'colorize')
		),
		'global_operations' => array
		(
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
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_log']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
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


/**
 * Class tl_log
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class tl_log extends Backend
{

	/**
	 * Colorize the log entries depending on their category
	 * @param array
	 * @param string
	 * @return string
	 */
	public function colorize($row, $label)
	{
		switch ($row['action'])
		{
			case 'CONFIGURATION':
			case 'REPOSITORY':
				$label = preg_replace('@^(.*</span> )(.*)$@U', '$1 <span class="tl_blue">$2</span>', $label);
				break;

			case 'CRON':
				$label = preg_replace('@^(.*</span> )(.*)$@U', '$1 <span class="tl_green">$2</span>', $label);
				break;

			case 'ERROR':
				$label = preg_replace('@^(.*</span> )(.*)$@U', '$1 <span class="tl_red">$2</span>', $label);
				break;
		}

		return $label;
	}
}

?>