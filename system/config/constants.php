<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Core version
 */
define('VERSION', '3.5');
define('BUILD', '14');
define('LONG_TERM_SUPPORT', true);


/**
 * Plugin versions (backwards compatibility)
 *
 * Version numbers are added to style sheets and JavaScript files to make
 * the web browser reload those resources after a Contao update.
 */
define('ACE', '1.1.8');
define('CSS3PIE', '1.0.0');
define('DROPZONE', '3.12.0');
define('HIGHLIGHTER', '3.0.83');
define('HTML5SHIV', '3.7.2');
define('RESPIMAGE', '1.4.0');
define('SWIPE', '2.0');
define('JQUERY', '1.11.3');
define('JQUERY_UI', '1.11.4');
define('COLORBOX', '1.6.1');
define('MEDIAELEMENT', '2.19.0.1');
define('TABLESORTER', '2.0.5');
define('MOOTOOLS', '1.5.1');
define('COLORPICKER', '1.4');
define('DATEPICKER', '2.2.0');
define('MEDIABOX', '1.4.6');
define('SIMPLEMODAL', '1.2');
define('SLIMBOX', '1.8');


/**
 * Link constants
 *
 * These constants can be used with any HTML link. Their primary purpose is
 * to provide an accessible alternative for common operations.
 *
 * Usage example:
 *   <a href="index.html"<?php echo LINK_NEW_WINDOW_BLUR; ?>>Home</a>
 */
define('LINK_BLUR', ' onclick="this.blur()"');
define('LINK_NEW_WINDOW', ' onclick="return !window.open(this.href)"');
define('LINK_NEW_WINDOW_BLUR', ' onclick="this.blur();return !window.open(this.href)"');


/**
 * Log constants
 *
 * These constants can be used with method $this->log() to add log entries.
 *
 * Usage example:
 *   $this->log('An error occured', __METHOD__, TL_ERROR);
 */
define('TL_ERROR', 'ERROR');
define('TL_ACCESS', 'ACCESS');
define('TL_GENERAL', 'GENERAL');
define('TL_FILES', 'FILES');
define('TL_CRON', 'CRON');
define('TL_FORMS', 'FORMS');
define('TL_CONFIGURATION', 'CONFIGURATION');
define('TL_NEWSLETTER', 'NEWSLETTER');
define('TL_REPOSITORY', 'REPOSITORY');
