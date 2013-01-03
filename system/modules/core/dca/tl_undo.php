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
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		),
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
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_undo']['pid'],
			'sorting'                 => true,
			'foreignKey'              => 'tl_user.name',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
		),
		'tstamp' => array
		(
			'sorting'                 => true,
			'flag'                    => 6,
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'fromTable' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_undo']['fromTable'],
			'sorting'                 => true,
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'query' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_undo']['query'],
			'sql'                     => "text NULL"
		),
		'affectedRows' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_undo']['affectedRows'],
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'data' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_undo']['data'],
			'search'                  => true,
			'sql'                     => "mediumblob NULL"
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
		if (Input::get('act') && !in_array(Input::get('id'), $GLOBALS['TL_DCA']['tl_undo']['list']['sorting']['root']))
		{
			$this->log('Not enough permissions to '. Input::get('act') .' undo step ID '. Input::get('id'), 'tl_undo checkPermission', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	}
}
