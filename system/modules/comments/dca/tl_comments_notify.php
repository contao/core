<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Comments
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Table tl_comments_notify
 */
$GLOBALS['TL_DCA']['tl_comments_notify'] = array
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
				'id' => 'primary',
				'source' => 'index',
				'parent' => 'index',
				'tokenConfirm' => 'index',
				'tokenRemove' => 'index'
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
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'source' => array
		(
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'parent' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'name' => array
		(
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'email' => array
		(
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'url' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'addedOn' => array
		(
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'ip' => array
		(
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'tokenConfirm' => array
		(
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'tokenRemove' => array
		(
			'sql'                     => "varchar(32) NOT NULL default ''"
		)
	)
);
