<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Operating systems (check Windows CE before Windows and Android before Linux!)
 */
$GLOBALS['TL_CONFIG']['os'] = array
(
	'Macintosh'     => array('os'=>'mac',        'mobile'=>false),
	'Windows CE'    => array('os'=>'win-ce',     'mobile'=>true),
	'Windows Phone' => array('os'=>'win-ce',     'mobile'=>true),
	'Windows'       => array('os'=>'win',        'mobile'=>false),
	'iPad'          => array('os'=>'ios',        'mobile'=>false),
	'iPhone'        => array('os'=>'ios',        'mobile'=>true),
	'iPod'          => array('os'=>'ios',        'mobile'=>true),
	'Android'       => array('os'=>'android',    'mobile'=>true),
	'BB10'          => array('os'=>'blackberry', 'mobile'=>true),
	'Blackberry'    => array('os'=>'blackberry', 'mobile'=>true),
	'Symbian'       => array('os'=>'symbian',    'mobile'=>true),
	'WebOS'         => array('os'=>'webos',      'mobile'=>true),
	'Linux'         => array('os'=>'unix',       'mobile'=>false),
	'FreeBSD'       => array('os'=>'unix',       'mobile'=>false),
	'OpenBSD'       => array('os'=>'unix',       'mobile'=>false),
	'NetBSD'        => array('os'=>'unix',       'mobile'=>false),
);


/**
 * Browsers (check OmniWeb and Silk before Safari and Opera Mini/Mobi before Opera!)
 */
$GLOBALS['TL_CONFIG']['browser'] = array
(
	'MSIE'       => array('browser'=>'ie',           'shorty'=>'ie', 'engine'=>'trident', 'version'=>'/^.*MSIE (\d+(\.\d+)*).*$/'),
	'Trident'    => array('browser'=>'ie',           'shorty'=>'ie', 'engine'=>'trident', 'version'=>'/^.*Trident\/\d+\.\d+; rv:(\d+(\.\d+)*).*$/'),
	'Firefox'    => array('browser'=>'firefox',      'shorty'=>'fx', 'engine'=>'gecko',   'version'=>'/^.*Firefox\/(\d+(\.\d+)*).*$/'),
	'Chrome'     => array('browser'=>'chrome',       'shorty'=>'ch', 'engine'=>'webkit',  'version'=>'/^.*Chrome\/(\d+(\.\d+)*).*$/'),
	'OmniWeb'    => array('browser'=>'omniweb',      'shorty'=>'ow', 'engine'=>'webkit',  'version'=>'/^.*Version\/(\d+(\.\d+)*).*$/'),
	'Silk'       => array('browser'=>'silk',         'shorty'=>'si', 'engine'=>'silk',    'version'=>'/^.*Silk\/(\d+(\.\d+)*).*$/'),
	'Safari'     => array('browser'=>'safari',       'shorty'=>'sf', 'engine'=>'webkit',  'version'=>'/^.*Version\/(\d+(\.\d+)*).*$/'),
	'Opera Mini' => array('browser'=>'opera-mini',   'shorty'=>'oi', 'engine'=>'presto',  'version'=>'/^.*Opera Mini\/(\d+(\.\d+)*).*$/'),
	'Opera Mobi' => array('browser'=>'opera-mobile', 'shorty'=>'om', 'engine'=>'presto',  'version'=>'/^.*Version\/(\d+(\.\d+)*).*$/'),
	'Opera'      => array('browser'=>'opera',        'shorty'=>'op', 'engine'=>'presto',  'version'=>'/^.*Version\/(\d+(\.\d+)*).*$/'),
	'IEMobile'   => array('browser'=>'ie-mobile',    'shorty'=>'im', 'engine'=>'trident', 'version'=>'/^.*IEMobile (\d+(\.\d+)*).*$/'),
	'Camino'     => array('browser'=>'camino',       'shorty'=>'ca', 'engine'=>'gecko',   'version'=>'/^.*Camino\/(\d+(\.\d+)*).*$/'),
	'Konqueror'  => array('browser'=>'konqueror',    'shorty'=>'ko', 'engine'=>'webkit',  'version'=>'/^.*Konqueror\/(\d+(\.\d+)*).*$/')
);
