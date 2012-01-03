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
 * @package    Repository
 * @license    LGPL
 * @filesource
 */


/**
 * Contao Repository :: Repository settings
 *
 * @copyright  Peter Koch 2008-2010
 * @author     Peter Koch, IBK Software AG
 * @license    See accompaning file LICENSE.txt
 */

// enable/disable SOAP cache
define('REPOSITORY_SOAPCACHE', true);

// valid core versions in descending order
define('REPOSITORY_COREVERSIONS',
		'20100049,20100049;'.	// 2.10.4 stable
		'20100039,20100039;'.	// 2.10.3 stable
		'20100029,20100029;'.	// 2.10.2 stable
		'20100019,20100019;'.	// 2.10.1 stable
		'20100009,20100009;'.	// 2.10.0 stable
		'20100006,20100006;'.	// 2.10.0 RC1
		'20100003,20100003;'.	// 2.10.0 beta1
		'20090059,20090059;'.	// 2.9.5 stable
		'20090049,20090049;'.	// 2.9.4 stable
		'20090039,20090039;'.	// 2.9.3 stable
		'20090029,20090029;'.	// 2.9.2 stable
		'20090019,20090019;'.	// 2.9.1 stable
		'20090009,20090009;'.	// 2.9.0 stable
		'20090006,20090006;'.	// 2.9.0 RC1
		'20090003,20090003;'.	// 2.9.0 beta1
		'20080049,20080049;'.	// 2.8.4 stable
		'20080039,20080039;'.	// 2.8.3 stable
		'20080029,20080029;'.	// 2.8.2 stable
		'20080019,20080019;'.	// 2.8.1 stable
		'20080009,20080009;'.	// 2.8.0 stable
		'20080007,20080007;'.	// 2.8.0 RC2
		'20080006,20080006;'.	// 2.8.0 RC1
		'20070069,20070069;'.	// 2.7.6 stable
		'20070059,20070059;'.	// 2.7.5 stable
		'20070049,20070049;'.	// 2.7.4 stable
		'20070039,20070039;'.	// 2.7.3 stable
		'20070029,20070029;'.	// 2.7.2 stable
		'20070019,20070019;'.	// 2.7.1 stable
		'20070009,20070009;'.	// 2.7.0 stable
		'20070007,20070007;'.	// 2.7.0 RC2
		'20070006,20070006;'.	// 2.7.0 RC1
		'20060079,20060079;'.	// 2.6.7 stable
		'20060069,20060069;'.	// 2.6.6 stable
		'20060059,20060059;'.	// 2.6.5 stable
		'20060049,20060049;'.	// 2.6.4 stable
		'20060039,20060039;'.	// 2.6.3 stable
		'20060029,20060029;'.	// 2.6.2 stable
		'20060019,20060019;'.	// 2.6.1 stable
		'20060009,20060009;'.	// 2.6.0 stable
		'20050119,20060004;'.	// 2.6.0 beta2
		'20050109,20060003;'.	// 2.6.0 beta1
		'20050099,20050099;'.	// 2.5.9 stable
		'20050089,20050089;'.	// 2.5.8 stable
		'20050079,20050079;'.	// 2.5.7 stable
		'20050069,20050069;'.	// 2.5.6 stable
		'20050059,20050059;'.	// 2.5.5 stable
		'20050039,20050039;'.	// 2.5.3 stable
		'20050029,20050029;'.	// 2.5.2 stable
		'20050019,20050019;'.	// 2.5.1 stable
		'20050009,20050009;'.	// 2.5.0 stable
		'20040079,20040079;'.	// 2.4.7 stable
		'20040069,20040069;'.	// 2.4.6 stable
		'20040009,20040009;'.	// 2.4.0 stable
		'20030029,20030029'		// 2.3.2 stable
);

// Where files are stored, relative to TL_ROOT
define('REPOSITORY_FILEROOT', 'tl_files/repository');

// Path of download script
define('REPOSITORY_DOWNLOADS', 'system/modules/rep_server/RepositoryDownload.php?token=');

// HTML tags allowed in long texts
define('REPOSITORY_TEXTTAGS', '<h3><h4><h5><h6><p><br><ol><ul><li><em><strong>');

// # of searchtags allowed
define('REPOSITORY_SEARCHTAGS', 5);

// Assumed maximum length of one line in translation editor
define('REPOSITORY_TRANSLINECHARS', 45);

// Minimum # of lines of textareas in translation editor
define('REPOSITORY_TRANSMINLINES', 3);

// Maximum # of lines of textareas in translation editor
define('REPOSITORY_TRANSMAXLINES', 15);

// Pixel height of one line in translation editor
define('REPOSITORY_TRANSLINEHEIGHT', 15);

// Non editable file extensions
define('REPOSITORY_NOEDIT', 'gif,png,zip,gz,tgz,bz,jpg,jpeg,exe,tif,tiff,db');

// Max # of items to display in each cloud
define('REPOSITORY_MAXCLOUDITEMS', 100);

// Thumbnail size limits
define('REPOSITORY_THUMBWIDTHMAX', 240);
define('REPOSITORY_THUMBHEIGHTMAX', 180);

// Time in seconds after which a dirty cache must be flushed
// NOW   : Used for 'Resynchronize' by repository admins and after delete/unpublish
// NORMAL: Used on updates by authors and translators
// LATER : Used for download-, install- and update-counters, and for ratings.
define('REPOSITORY_CLEARCACHE_NOW', 1);			// immediate
define('REPOSITORY_CLEARCACHE_NORMAL', 300);	// allow 5 minutes max
define('REPOSITORY_CLEARCACHE_LATER', 3600);	// allow 1 hour max

?>