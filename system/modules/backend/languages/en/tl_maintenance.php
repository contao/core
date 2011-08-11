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
 * @package    Language
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables']  = array('Purge data', 'Please select the data you want to purge.');
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'] = array('Front end user', 'Automatically log in a front end user to index protected pages.');


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache']    = 'Purge data';
$GLOBALS['TL_LANG']['tl_maintenance']['clearHtml']     = 'system/html';
$GLOBALS['TL_LANG']['tl_maintenance']['clearScripts']  = 'system/scripts';
$GLOBALS['TL_LANG']['tl_maintenance']['clearTemp']     = 'system/tmp';
$GLOBALS['TL_LANG']['tl_maintenance']['clearXml']      = 'XML files';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCss']      = 'CSS files';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared']  = 'The data has been purged';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate']    = 'Live update';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId']  = 'Live update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate']      = 'Your Contao version %s is up to date';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion']    = 'A newer Contao version %s is available';
$GLOBALS['TL_LANG']['tl_maintenance']['betaVersion']   = 'You cannot update beta versions via live update';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId']     = 'Please enter your live update ID';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable']  = 'The temporary folder (system/tmp) is not writeable';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog']     = 'View the change log';
$GLOBALS['TL_LANG']['tl_maintenance']['backupFiles']   = 'Backup the files that will be updated';
$GLOBALS['TL_LANG']['tl_maintenance']['showToc']       = 'List the files in the update archive';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Run the update';
$GLOBALS['TL_LANG']['tl_maintenance']['toc']           = 'Content of the update archive';
$GLOBALS['TL_LANG']['tl_maintenance']['backup']        = 'Backuped files';
$GLOBALS['TL_LANG']['tl_maintenance']['update']        = 'Updated files';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex']   = 'Rebuild the search index';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit']   = 'Rebuild index';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable']  = 'No searchable pages found';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote']     = 'Please wait for the page to load completely before you proceed!';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading']  = 'Please wait while the search index is being rebuilt.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'The search index has been rebuilt. You can now proceed.';

?>