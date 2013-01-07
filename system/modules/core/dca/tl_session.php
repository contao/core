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
			'sql'                     => "varchar(40) NOT NULL default ''"
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
