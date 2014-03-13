<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * This is the SwiftMailer configuration file. See vendor/swiftmailer
 * for more information.
 */
if (defined('SWIFT_INIT_LOADED')) {
    return;
}

define('SWIFT_INIT_LOADED', true);

require TL_ROOT . '/vendor/swiftmailer/swiftmailer/lib/dependency_maps/cache_deps.php';
require TL_ROOT . '/vendor/swiftmailer/swiftmailer/lib/dependency_maps/mime_deps.php';
require TL_ROOT . '/vendor/swiftmailer/swiftmailer/lib/dependency_maps/message_deps.php';
require TL_ROOT . '/vendor/swiftmailer/swiftmailer/lib/dependency_maps/transport_deps.php';

$preferences = Swift_Preferences::getInstance();

$preferences->setCharset($GLOBALS['TL_CONFIG']['characterSet']);

if (!$GLOBALS['TL_CONFIG']['useFTP']) {
    $preferences->setTempDir(TL_ROOT . '/system/tmp')->setCacheType('disk');
}

if (version_compare(phpversion(), '5.4.7', '<')) {
    $preferences->setQPDotEscape(false);
}
