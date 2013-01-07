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
 * Table tl_search
 */
$GLOBALS['TL_DCA']['tl_search'] = array
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
				'url' => 'index',
				'text' => 'fulltext'
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
		'title' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'url' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'text' => array
		(
			'sql'                     => "mediumtext NULL"
		),
		'filesize' => array
		(
			'sql'                     => "double unsigned NOT NULL default '0'"
		),
		'checksum' => array
		(
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'protected' => array
		(
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'groups' => array
		(
			'sql'                     => "blob NULL"
		),
		'language' => array
		(
			'sql'                     => "varchar(2) NOT NULL default ''"
		)
	)
);
