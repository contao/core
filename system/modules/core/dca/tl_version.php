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
 * Table tl_version
 */
$GLOBALS['TL_DCA']['tl_version'] = array
(

	// Config
	'config' => array
	(
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index',
				'fromTable' => 'index'
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
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'version' => array
		(
			'sql'                     => "smallint(5) unsigned NOT NULL default '1'"
		),
		'fromTable' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'userid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'username' => array
		(
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'description' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'editUrl' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'active' => array
		(
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'data' => array
		(
			'sql'                     => "mediumblob NULL"
		)
	)
);
