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
 * Table tl_undo
 */
$GLOBALS['TL_DCA']['tl_undo'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'closed'                      => true,
		'notEditable'                 => true,
		'onload_callback' => array
		(
			array('tl_undo', 'checkPermission')
		)
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
			'format'                  => '<span style="color:#b3b3b3;padding-right:3px">[%s]</span>%s',
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
			'flag'                    => 6
		),
		'pid' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_undo']['pid'],
			'sorting'                 => true,
			'foreignKey'              => 'tl_user.name'
		),
		'fromTable' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_undo']['fromTable'],
			'sorting'                 => true
		),
		'query' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_undo']['query']
		),
		'affectedRows' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_undo']['affectedRows']
		),
		'data' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_undo']['data'],
			'search'                  => true
		)
	)
);


/**
 * Class tl_undo
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Controller
 */
class tl_undo extends Backend
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
	 * Check permissions to use table tl_undo
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Show only own undo steps
		$objSteps = $this->Database->prepare("SELECT id FROM tl_undo WHERE pid=?")
								   ->execute($this->User->id);

		// Restrict the list
		$GLOBALS['TL_DCA']['tl_undo']['list']['sorting']['root'] = $objSteps->numRows ? $objSteps->fetchEach('id') : array(0);

		// Redirect if there is an error
		if ($this->Input->get('act') && !in_array($this->Input->get('id'), $GLOBALS['TL_DCA']['tl_undo']['list']['sorting']['root']))
		{
			$this->log('Not enough permissions to '. $this->Input->get('act') .' undo step ID '. $this->Input->get('id'), 'tl_undo checkPermission', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	}
}

?>