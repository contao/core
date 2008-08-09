<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables']  = array('Clear cache', 'Please select the cache resources you want to clear.');
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'] = array('Front end user', 'To index protected pages, you have to create a front end user who is allowed to access these pages.');


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate']   = 'Live update';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache']   = 'Clear cache';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'The cache has been cleared';
$GLOBALS['TL_LANG']['tl_maintenance']['clearTemp']    = 'Temp folder';
$GLOBALS['TL_LANG']['tl_maintenance']['clearHtml']    = 'Folder system/html';
$GLOBALS['TL_LANG']['tl_maintenance']['clearXml']     = 'Recreate XML sitemaps';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate']     = 'Your TYPOlight version %s is up to date';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion']   = 'A newer TYPOlight version %s is available';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog']    = 'View changelog';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex']  = 'Rebuild search index';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit']  = 'Rebuild index';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'Live Update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['backupFiles']  = 'Backup the files that will be updated';
$GLOBALS['TL_LANG']['tl_maintenance']['showToc']      = 'List the update archive files';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'The temporary folder (system/tmp) is not writeable';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId']    = 'Please enter your live update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'No searchable pages found';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote']    = 'Please wait for the page to load completely before you proceed!';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Run the update';

?>