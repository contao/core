<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
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
