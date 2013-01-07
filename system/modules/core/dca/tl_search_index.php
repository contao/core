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
 * Table tl_search_index
 */
$GLOBALS['TL_DCA']['tl_search_index'] = array
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
				'word' => 'index'
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
		'word' => array
		(
			'sql'                     => "varbinary(64) NOT NULL default ''"
		),
		'relevance' => array
		(
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'language' => array
		(
			'sql'                     => "varchar(2) NOT NULL default ''"
		)
	)
);
