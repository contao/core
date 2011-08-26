<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Config
 * @license    LGPL
 * @filesource
 */


/**
 * This is the TCPDF (PDF generator) configuration file. See
 * plugins/tcpdf for more information.
 */
define('K_TCPDF_EXTERNAL_CONFIG', true);
define('K_PATH_MAIN', TL_ROOT . '/plugins/tcpdf/');
define('K_PATH_URL', $this->Environment->base . 'plugins/tcpdf/');
define('K_PATH_FONTS', K_PATH_MAIN . 'fonts/');
define('K_PATH_CACHE', K_PATH_MAIN . 'cache/');
define('K_PATH_URL_CACHE', K_PATH_URL . 'cache/');
define('K_PATH_IMAGES', K_PATH_MAIN . 'images/');
define('K_BLANK_IMAGE', K_PATH_IMAGES . '_blank.png');
define('PDF_PAGE_FORMAT', 'A4');
define('PDF_PAGE_ORIENTATION', 'P');
define('PDF_CREATOR', 'Contao Open Source CMS');
define('PDF_AUTHOR', $this->Environment->url);
define('PDF_HEADER_TITLE', $GLOBALS['TL_CONFIG']['websiteTitle']);
define('PDF_HEADER_STRING', '');
define('PDF_HEADER_LOGO', '');
define('PDF_HEADER_LOGO_WIDTH', 30);
define('PDF_UNIT', 'mm');
define('PDF_MARGIN_HEADER', 0);
define('PDF_MARGIN_FOOTER', 0);
define('PDF_MARGIN_TOP', 10);
define('PDF_MARGIN_BOTTOM', 10);
define('PDF_MARGIN_LEFT', 15);
define('PDF_MARGIN_RIGHT', 15);
define('PDF_FONT_NAME_MAIN', 'freeserif');
define('PDF_FONT_SIZE_MAIN', 12);
define('PDF_FONT_NAME_DATA', 'freeserif');
define('PDF_FONT_SIZE_DATA', 12);
define('PDF_FONT_MONOSPACED', 'freemono');
define('PDF_FONT_SIZE_MONOSPACED', 10); // PATCH
define('PDF_IMAGE_SCALE_RATIO', 1.25);
define('HEAD_MAGNIFICATION', 1.1);
define('K_CELL_HEIGHT_RATIO', 1.25);
define('K_TITLE_MAGNIFICATION', 1.3);
define('K_SMALL_RATIO', 2/3);
define('K_THAI_TOPCHARS', false);
define('K_TCPDF_CALLS_IN_HTML', false);

?>