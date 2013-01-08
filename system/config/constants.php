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
 * Core and plugin versions
 *
 * Version numbers are added to style sheets and JavaScript files to make
 * the web browser reload those resources after a Contao update.
 */
define('VERSION', '3.0');
define('BUILD', '3');
define('LONG_TERM_SUPPORT', false);
define('CODEMIRROR', '2.32');
define('CSS3PIE', '1.0.0');
define('HIGHLIGHTER', '3.0.83');
define('HTML5SHIV', '3.6.1');
define('JQUERY', '1.8.2');
define('JQUERY_UI', '1.9.1');
define('COLORBOX', '1.3.20');
define('MEDIAELEMENT', '2.9.5');
define('COLORPICKER', '1.3');
define('TABLESORTER', '2.0.5');
define('MOOTOOLS', '1.4.5');
define('DATEPICKER', '2.1.1');
define('MEDIABOX', '1.4.6');
define('SIMPLEMODAL', '1.2');
define('SLIMBOX', '1.71');


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
 *   $this->log('An error occured', 'FormGenerator execute()', TL_ERROR);
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
