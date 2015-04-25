<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Table tl_session
 */
$GLOBALS['TL_DCA']['tl_session'] = array
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
				'hash' => 'unique'
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
		'name' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'sessionID' => array
		(
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'hash' => array
		(
			'sql'                     => "varchar(40) NULL"
		),
		'ip' => array
		(
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'su' => array
		(
			'sql'                     => "char(1) NOT NULL default ''"
		)
	)
);
