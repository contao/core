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
 * Table tl_repository_instfiles
 */
$GLOBALS['TL_DCA']['tl_repository_instfiles'] = array
(

	// Config
	'config' => array
	(
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index'
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
		'filename' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'filetype' => array
		(
			'sql'                     => "char(1) NOT NULL default 'F'"
		),
		'flag' => array
		(
			'sql'                     => "char(1) NOT NULL default ''"
		)
	)
);
