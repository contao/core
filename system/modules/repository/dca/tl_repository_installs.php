<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   Repository
 * @author    Peter Koch, IBK Software AG
 * @license   See accompaning file LICENSE.txt
 * @copyright Peter Koch 2008-2010
 */


/**
 * Table tl_repository_installs
 */
$GLOBALS['TL_DCA']['tl_repository_installs'] = array
(

	// Config
	'config' => array
	(
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
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
		'extension' => array
		(
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'version' => array
		(
			'sql'                     => "int(9) NOT NULL default '0'"
		),
		'build' => array
		(
			'sql'                     => "int(9) NOT NULL default '0'"
		),
		'alpha' => array
		(
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'beta' => array
		(
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'rc' => array
		(
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'stable' => array
		(
			'sql'                     => "char(1) NOT NULL default '1'"
		),
		'lickey' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'delprot' => array
		(
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'updprot' => array
		(
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'error' => array
		(
			'sql'                     => "char(1) NOT NULL default ''"
		)
	)
);
