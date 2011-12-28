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
 * Contao Repository :: Language file for tl_repository (en)
 *
 * @copyright  Peter Koch 2008-2010
 * @author     Peter Koch, IBK Software AG
 * @license    See accompaning file LICENSE.txt
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_repository']['lickey']        = array('License key', 'Enter the license key that you got from the extension vendor.');
$GLOBALS['TL_LANG']['tl_repository']['uninstprotect'] = array('Uninstall protection', 'Protect extension against accidental uninstallation.');
$GLOBALS['TL_LANG']['tl_repository']['updateprotect'] = array('Update protection', 'Protect extension against accidental update.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_repository']['action']             = 'Action';
$GLOBALS['TL_LANG']['tl_repository']['actionsummary']      = 'Action summary';
$GLOBALS['TL_LANG']['tl_repository']['actionsuccess']      = 'Actions successful.';
$GLOBALS['TL_LANG']['tl_repository']['actionfailed']       = 'One or more actions failed.';
$GLOBALS['TL_LANG']['tl_repository']['apply']              = 'Apply';
$GLOBALS['TL_LANG']['tl_repository']['byorder']            = 'By %s';
$GLOBALS['TL_LANG']['tl_repository']['checkextension']     = 'Check extension';
$GLOBALS['TL_LANG']['tl_repository']['dbuptodate']         = 'Database is up to date';
$GLOBALS['TL_LANG']['tl_repository']['deletingdirs']       = 'Deleting directories';
$GLOBALS['TL_LANG']['tl_repository']['deletingfiles']      = 'Deleting files';
$GLOBALS['TL_LANG']['tl_repository']['editextension']      = 'Edit settings';
$GLOBALS['TL_LANG']['tl_repository']['extinstrecntf']      = 'Extension install record not found';
$GLOBALS['TL_LANG']['tl_repository']['errdldpkg']          = 'Download of package from repository failed';
$GLOBALS['TL_LANG']['tl_repository']['failed']             = 'failed';
$GLOBALS['TL_LANG']['tl_repository']['fileerrwrite']       = 'Error writing file %s';
$GLOBALS['TL_LANG']['tl_repository']['filesdeleted']       = '%s file(s) deleted';
$GLOBALS['TL_LANG']['tl_repository']['filesinstalled']     = '%s file(s) installed';
$GLOBALS['TL_LANG']['tl_repository']['filesunchanged']     = '%s file(s) unchanged';
$GLOBALS['TL_LANG']['tl_repository']['filesupdated']       = '%s file(s) updated';
$GLOBALS['TL_LANG']['tl_repository']['ftsearch']           = 'Fulltext search';
$GLOBALS['TL_LANG']['tl_repository']['goback']             = 'Go back';
$GLOBALS['TL_LANG']['tl_repository']['install']            = 'Install';
$GLOBALS['TL_LANG']['tl_repository']['installextension']   = 'Install extension';
$GLOBALS['TL_LANG']['tl_repository']['installingext']      = 'Install %s %s build %s';
$GLOBALS['TL_LANG']['tl_repository']['installlogtitle']    = 'File installation/update log';
$GLOBALS['TL_LANG']['tl_repository']['lickeyrequired']     = 'This extension requires a license key. Please visit the vendor\'s shop to purchase one.';
$GLOBALS['TL_LANG']['tl_repository']['none']               = 'None';
$GLOBALS['TL_LANG']['tl_repository']['notfound']           = 'not found';
$GLOBALS['TL_LANG']['tl_repository']['ok']                 = 'OK';
$GLOBALS['TL_LANG']['tl_repository']['okuninstextension']  = 'OK to uninstall <em>%s</em> now?';
$GLOBALS['TL_LANG']['tl_repository']['showdetails']        = 'Show details';
$GLOBALS['TL_LANG']['tl_repository']['stateshint']         = 'Check the states to be included.';
$GLOBALS['TL_LANG']['tl_repository']['status']             = 'Status';
$GLOBALS['TL_LANG']['tl_repository']['success']            = 'success';
$GLOBALS['TL_LANG']['tl_repository']['uninstallextension'] = 'Uninstall extension';
$GLOBALS['TL_LANG']['tl_repository']['updatedatabase']     = 'Update database';
$GLOBALS['TL_LANG']['tl_repository']['updateextension']    = 'Update extension';
$GLOBALS['TL_LANG']['tl_repository']['updateextensions']   = 'Update checked extensions';
$GLOBALS['TL_LANG']['tl_repository']['update']             = 'Update';
$GLOBALS['TL_LANG']['tl_repository']['updates']            = 'Updates';
$GLOBALS['TL_LANG']['tl_repository']['updatehint']         = 'Check the states to be included at updates.';
$GLOBALS['TL_LANG']['tl_repository']['updatingext']        = 'Update %s %s build %s';
$GLOBALS['TL_LANG']['tl_repository']['validate']           = 'Repair';
$GLOBALS['TL_LANG']['tl_repository']['validatingext']      = 'Verify/repair %s %s build %s';


/**
 * Status messages
 */
$GLOBALS['TL_LANG']['tl_repository_statext']['uptodate']        = 'Up to date';
$GLOBALS['TL_LANG']['tl_repository_statext']['notapproved']     = 'Not approved for %s %s';
$GLOBALS['TL_LANG']['tl_repository_statext']['notapprovedwith'] = 'Not approved with %s %s';
$GLOBALS['TL_LANG']['tl_repository_statext']['shouldwork']      = 'Expected to be compatible with %s %s';
$GLOBALS['TL_LANG']['tl_repository_statext']['newversion']      = 'Version %s build %s available';
$GLOBALS['TL_LANG']['tl_repository_statext']['depmissing']      = 'Missing required extension %s';
$GLOBALS['TL_LANG']['tl_repository_statext']['extnotfound']     = 'Extension not found in repository';
$GLOBALS['TL_LANG']['tl_repository_statext']['vernotfound']     = 'Installed version not found in repository';
$GLOBALS['TL_LANG']['tl_repository_statext']['extneedkey']      = 'License key required, install extension in advance';
$GLOBALS['TL_LANG']['tl_repository_statext']['errorinstall']    = 'Corrupted, update/repair required';

?>